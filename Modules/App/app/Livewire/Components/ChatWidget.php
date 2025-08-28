<?php

namespace Modules\App\Livewire\Components;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Modules\App\Models\Chat\Conversation;
use Modules\App\Models\Chat\ConversationParticipant;
use Modules\App\Models\Chat\Message;
use Modules\App\Models\Chat\MessageAttachment;
use Modules\ChannelManager\Models\Guest\Guest;

class ChatWidget extends Component
{
    use WithFileUploads;

    // Panel & layout
    public bool $open = false;
    public bool $showThread = true;       // mobile back behavior
    public bool $attachmentMenu = false;  // kept for BC (UI now uses Alpine)

    // Sidebar + contacts
    public array $conversations = [];
    public ?int $selectedConversationId = null;

    public string $search = '';
    public array $contacts = [];          // merged Users + Guests
    public bool $showContacts = false;

    // Compose
    public string $messageText = '';
    /** @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile[] */
    public array $uploads = [];
    public int $unreadTotal = 0;

    // Messages paging (smoothness: fetch in chunks, pull older on scroll)
    public int $pageSize = 40;
    public int $loadedMessages = 0; // how many we currently render

    // Typing (hooks ready for realtime)
    public bool $remoteTyping = false;

    public function mount(): void
    {
        $this->refreshConversations();
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
        // Do not auto-select a conversation
        if (! $this->open) {
            // optional: collapse thread on close for mobile
            $this->showThread = true;
        }
    }

    /** noop you can connect to broadcast/echo */
    public function typingPing(): void
    {
        // hook to broadcast typing to other participant(s) if you wire events
    }

    /** Determine actor (user/guest) */
    protected function actor(): array
    {
        if (Auth::guard('web')->check()) {
            return ['type' => 'user', 'id' => (int) Auth::id(), 'name' => Auth::user()->name ?? 'User'];
        }
        // If you later add a guest guard, adapt here.
        return ['type' => 'user', 'id' => (int) Auth::id(), 'name' => Auth::user()->name ?? 'User'];
    }

    public function refreshConversations(): void
    {
        $actor = $this->actor();

        $items = Conversation::with([
                'participants',
                'messages' => fn($q) => $q->latest()->limit(1),
            ])
            ->whereHas('participants', function ($q) use ($actor) {
                $q->where('participant_type', $actor['type'])
                  ->where('participant_id', $actor['id']);
            })
            ->orderByDesc('updated_at')
            ->limit(100)
            ->get();

        // Batch collect ids for names/avatars
        $otherIds = ['user' => [], 'guest' => []];
        $senderIds = ['user' => [], 'guest' => []];

        foreach ($items as $c) {
            $other = $c->participants->first(function ($p) use ($actor) {
                return !($p->participant_type === $actor['type'] && (int)$p->participant_id === (int)$actor['id']);
            });
            if ($other) { $otherIds[$other->participant_type][] = (int) $other->participant_id; }

            $last = $c->messages->first();
            if ($last && in_array($last->sender_type, ['user','guest'], true)) {
                $senderIds[$last->sender_type][] = (int) $last->sender_id;
            }
        }

        $userMap  = !empty($otherIds['user']) || !empty($senderIds['user'])
            ? User::query()->whereIn('id', array_unique(array_merge($otherIds['user'], $senderIds['user'])))->get()->keyBy('id')
            : collect();
        $guestMap = !empty($otherIds['guest']) || !empty($senderIds['guest'])
            ? Guest::query()->whereIn('id', array_unique(array_merge($otherIds['guest'], $senderIds['guest'])))->get()->keyBy('id')
            : collect();

        $nameFromMap = function (?string $type, ?int $id) use ($userMap, $guestMap) {
            if (!$type || !$id) return null;
            return $type === 'user'
                ? optional($userMap->get($id))->name
                : ($type === 'guest' ? optional($guestMap->get($id))->name : null);
        };
        $avatarFromMap = function (?string $type, ?int $id) use ($userMap, $guestMap) {
            if (!$type || !$id) return null;
            return $type === 'user'
                ? (optional($userMap->get($id))->avatar ?? null)
                : ($type === 'guest' ? (optional($guestMap->get($id))->avatar ?? null) : null);
        };

        $this->conversations = $items->map(function ($c) use ($actor, $nameFromMap, $avatarFromMap) {
            $other = $c->participants->first(fn($p) =>
                !($p->participant_type === $actor['type'] && (int)$p->participant_id === (int)$actor['id'])
            );
            $otherType = $other?->participant_type;
            $otherId   = $other?->participant_id ? (int)$other->participant_id : null;

            $otherName = $nameFromMap($otherType, $otherId) ?? ($otherType === 'guest' ? 'Guest' : 'User');

            $last = $c->messages->first();
            $isMe = $last && $last->sender_type === $actor['type'] && (int)$last->sender_id === (int)$actor['id'];
            $senderName = $isMe ? 'You' : ($nameFromMap($last?->sender_type, $last?->sender_id) ?? '');
            $lastText = trim((string) ($last?->body ?? ''));
            $lastLine = $lastText !== '' ? ($isMe && $senderName !== '' ? "{$senderName}: {$lastText}" : $lastText) : '—';

            $avatar = $avatarFromMap($otherType, $otherId);
            $lastAt = $last?->updated_at ? $last->updated_at->diffForHumans() : null;

            return [
                'id'          => (int) $c->id,
                'title'       => $c->subject ?: $otherName,
                'other_name'  => $otherName,
                'other_type'  => $otherType,
                'avatar'      => $avatar,
                'last_line'   => \Illuminate\Support\Str::limit($lastLine, 80),
                'last_at'     => $lastAt,
                'unread'      => $c->unreadCountForParticipant($actor['type'], $actor['id']),
                'status'      => $c->status,
                // demo flags (persist later if you want)
                'muted'       => false,
                'pinned'      => false,
            ];
        })->toArray();

        $this->unreadTotal = collect($this->conversations)->sum('unread');

        // If current conversation was closed/deleted, unselect it safely
        if ($this->selectedConversationId && !collect($this->conversations)->firstWhere('id', $this->selectedConversationId)) {
            $this->selectedConversationId = null;
            $this->loadedMessages = 0;
        }
    }

    public function markAsRead(int $conversationId): void
    {
        $actor = $this->actor();
        ConversationParticipant::where('conversation_id', $conversationId)
            ->where('participant_type', $actor['type'])
            ->where('participant_id', $actor['id'])
            ->update(['last_read_at' => now()]);
        $this->refreshConversations();
    }

    public function markAsUnread(int $conversationId): void
    {
        $actor = $this->actor();
        ConversationParticipant::where('conversation_id', $conversationId)
            ->where('participant_type', $actor['type'])
            ->where('participant_id', $actor['id'])
            ->update(['last_read_at' => now()->subYears(20)]);
        $this->refreshConversations();
    }

    public function closeConversation(int $conversationId): void
    {
        if ($c = Conversation::find($conversationId)) {
            $c->update(['status' => 'closed']);
        }
        if ($this->selectedConversationId === $conversationId) {
            $this->selectedConversationId = null;
            $this->loadedMessages = 0;
        }
        $this->refreshConversations();
    }

    public function deleteConversation(int $conversationId): void
    {
        if ($c = Conversation::find($conversationId)) {
            $c->delete();
        }
        if ($this->selectedConversationId === $conversationId) {
            $this->selectedConversationId = null;
            $this->loadedMessages = 0;
        }
        $this->refreshConversations();
    }

    public function togglePin(int $conversationId): void
    {
        $this->conversations = collect($this->conversations)->map(function($row) use ($conversationId){
            if ($row['id'] === $conversationId) { $row['pinned'] = !($row['pinned'] ?? false); }
            return $row;
        })->sortByDesc('pinned')->values()->all();
    }

    public function toggleMute(int $conversationId): void
    {
        $this->conversations = collect($this->conversations)->map(function($row) use ($conversationId){
            if ($row['id'] === $conversationId) { $row['muted'] = !($row['muted'] ?? false); }
            return $row;
        })->all();
    }

    public function selectConversation(int $id): void
    {
        $actor = $this->actor();

        $conversation = Conversation::whereHas('participants', function ($q) use ($actor) {
                $q->where('participant_type', $actor['type'])
                  ->where('participant_id', $actor['id']);
            })
            ->findOrFail($id);

        $this->selectedConversationId = (int) $conversation->id;

        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('participant_type', $actor['type'])
            ->where('participant_id', $actor['id'])
            ->update(['last_read_at' => now()]);

        $this->loadedMessages = $this->pageSize; // reset window
        $this->refreshConversations();

        $this->dispatch('scroll-bottom');
        $this->showThread = true;
    }

    public function loadOlder(): void
    {
        if (!$this->selectedConversationId) return;
        $total = Message::where('conversation_id', $this->selectedConversationId)->count();
        $this->loadedMessages = min($total, $this->loadedMessages + $this->pageSize);
    }

    /** Search contacts across Users & Guests */
    public function updatedSearch(): void
    {
        $term = trim($this->search);
        $this->showContacts = strlen($term) > 0;

        if (!$this->showContacts) {
            $this->contacts = [];
            return;
        }

        $users = User::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            })
            ->limit(8)
            ->get();

        $guests = Guest::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            })
            ->limit(8)
            ->get();

        $this->contacts = $users->merge($guests)
            ->take(12)
            ->map(function ($item) {
                return [
                    'type'  => $item instanceof User ? 'user' : 'guest',
                    'id'    => (int) $item->id,
                    'name'  => $item->name ?? ($item instanceof User ? 'User' : 'Guest'),
                    'label' => $item->email ?? ($item->phone ?? ''),
                    'avatar' => $item->avatar ?? null
                ];
            })
            ->values()
            ->all();
    }

    /** Start or reuse a 1:1 conversation with a contact */
    public function startConversationWith(string $participantType, int $participantId): void
    {
        $me = $this->actor();
        if ($participantType === $me['type'] && $participantId === $me['id']) return;

        $existing = Conversation::whereHas('participants', function ($q) use ($me) {
                $q->where('participant_type', $me['type'])->where('participant_id', $me['id']);
            })
            ->whereHas('participants', function ($q) use ($participantType, $participantId) {
                $q->where('participant_type', $participantType)->where('participant_id', $participantId);
            })
            ->first();

        if ($existing) {
            $this->selectConversation($existing->id);
            $this->open = true; $this->showContacts = false; $this->search = '';
            return;
        }

        $conv = Conversation::create([
            'subject'    => null,
            'status'     => 'open',
            'created_by' => Auth::id() ?: null,
        ]);

        ConversationParticipant::firstOrCreate(
            [
                'conversation_id'  => $conv->id,
                'participant_type' => $me['type'],
                'participant_id'   => $me['id'],
            ],
            ['role' => $me['type'] === 'user' ? 'support' : 'guest']
        );

        ConversationParticipant::firstOrCreate(
            [
                'conversation_id'  => $conv->id,
                'participant_type' => $participantType,
                'participant_id'   => $participantId,
            ],
            ['role' => $participantType === 'user' ? 'support' : 'guest']
        );

        $this->selectConversation($conv->id);
        $this->open = true; $this->showContacts = false; $this->search = '';
    }

    public function toggleThread(): void
    {
        $this->showThread = ! $this->showThread;
    }

    public function toggleAttachmentMenu(): void
    {
        $this->attachmentMenu = ! $this->attachmentMenu;
    }

    public function send(): void
    {
        $this->validate([
            'messageText' => 'nullable|string|max:5000',
            'uploads.*'   => 'file|max:10240', // 10MB each
        ]);

        if (!$this->selectedConversationId) {
            return;
        }

        $me = $this->actor();
        if (trim($this->messageText) === '' && count($this->uploads) === 0) {
            return;
        }

        $msg = Message::create([
            'conversation_id' => $this->selectedConversationId,
            'sender_type'     => $me['type'],
            'sender_id'       => $me['id'],
            'type'            => 'text',
            'body'            => trim($this->messageText) !== '' ? $this->messageText : null,
        ]);

        foreach ($this->uploads as $file) {
            $path = $file->store('chat', 'public');
            MessageAttachment::create([
                'message_id'    => $msg->id,
                'path'          => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
            ]);
        }

        $this->messageText = '';
        $this->uploads = [];

        // Nudge paging window down
        $this->loadedMessages = max($this->pageSize, $this->loadedMessages + 1);

        $this->refreshConversations();
        $this->dispatch('message-sent');
        $this->dispatch('scroll-bottom');
    }

    /** Messages accessor – returns the most recent N (loadedMessages) */
    public function getMessagesProperty()
    {
        if (!$this->selectedConversationId) {
            return collect();
        }

        $count = $this->loadedMessages ?: $this->pageSize;

        return Message::with('attachments', 'sender')
            ->where('conversation_id', $this->selectedConversationId)
            ->orderBy('created_at')
            ->take($count)
            ->get();
    }

    public function render()
    {
        return view('app::livewire.components.chat-widget');
    }
}
