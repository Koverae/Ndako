<?php

namespace Modules\App\Livewire\Components;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Modules\App\Models\Chat\Conversation;
use Modules\App\Models\Chat\ConversationParticipant;
use Modules\App\Models\Chat\Message;
use Modules\App\Models\Chat\MessageAttachment;
use Modules\ChannelManager\Models\Guest\Guest;

class ChatWidget extends Component
{
    use WithFileUploads;


    public bool $open = false;
    public bool $showThread = true;         // toggle thread visibility (mobile back behavior)
    public bool $attachmentMenu = false;    // WhatsApp-like menu

    // sidebar + contacts
    public array $conversations = [];
    public ?int $selectedConversationId = null;

    public string $search = '';
    public array $contacts = [];            // merged Users + Guests search results
    public bool $showContacts = false;

    // compose
    public string $messageText = '';
    public array $uploads = [];             // TemporaryUploadedFile[]
    public int $unreadTotal = 0;

    public function mount()
    {
        $this->refreshConversations();
    }

    public function toggle()
    {
        $this->open = ! $this->open;
        if ($this->open && is_null($this->selectedConversationId) && !empty($this->conversations)) {
            $this->selectConversation($this->conversations[0]['id']);
        }
    }

    /** Determine the current actor (user or guest). Adjust guards as per your app. */
    protected function actor(): array
    {
        if (Auth::guard('web')->check()) {
            return ['type' => 'user', 'id' => Auth::id(), 'name' => Auth::user()->name ?? 'User'];
        }
        if (Auth::guard('guest')->check()) {
            $g = Auth::guard('guest')->user();
            return ['type' => 'guest', 'id' => $g->id, 'name' => $g->name ?? 'Guest'];
        }
        // fallback: assume web
        return ['type' => 'user', 'id' => Auth::id(), 'name' => Auth::user()->name ?? 'User'];
    }

    public function refreshConversations(): void
    {
        $actor = $this->actor();

        $items = Conversation::with([
                'participants',
                'messages' => fn($q) => $q->latest()->limit(1)
            ])
            ->whereHas('participants', function ($q) use ($actor) {
                $q->where('participant_type', $actor['type'])
                  ->where('participant_id', $actor['id']);
            })
            ->orderByDesc('updated_at')
            ->limit(50)
            ->get();

        $this->conversations = $items->map(function ($c) use ($actor) {
            $last = optional($c->messages->first())->body;
            return [
                'id'      => $c->id,
                'subject' => $c->subject ?? 'Chat',
                'status'  => $c->status,
                'last'    => $last,
                'unread'  => $c->unreadCountForParticipant($actor['type'], $actor['id']),
                'updated' => $c->updated_at->diffForHumans(),
            ];
        })->toArray();

        $this->unreadTotal = collect($this->conversations)->sum('unread');
    }

    public function selectConversation(int $id): void
    {
        $actor = $this->actor();
        $conversation = Conversation::whereHas('participants', function($q) use ($actor) {
                $q->where('participant_type', $actor['type'])
                  ->where('participant_id', $actor['id']);
            })
            ->findOrFail($id);

        $this->selectedConversationId = $conversation->id;

        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('participant_type', $actor['type'])
            ->where('participant_id', $actor['id'])
            ->update(['last_read_at' => now()]);

        $this->refreshConversations();
        $this->dispatch('scroll-bottom');
        $this->showThread = true; // on mobile, ensure thread is shown
    }

    /** Search contacts across Users & Guests */
    public function updatedSearch(): void
    {
        $term = trim($this->search);
        $this->showContacts = strlen($term) > 0;

        if (!$this->showContacts) { $this->contacts = []; return; }

        $users = User::query()
            ->where(function($q) use ($term){
                $q->where('name','like',"%{$term}%")
                  ->orWhere('email','like',"%{$term}%");
            })
            ->limit(8)->get()
            ->map(fn($u) => ['type'=>'user','id'=>$u->id,'name'=>$u->name ?? 'User','label'=>$u->email]);

        $guests = Guest::query()
            ->where(function($q) use ($term){
                $q->where('name','like',"%{$term}%")
                  ->orWhere('email','like',"%{$term}%")
                  ->orWhere('phone','like',"%{$term}%");
            })
            ->limit(8)->get()
            ->map(fn($g) => ['type'=>'guest','id'=>$g->id,'name'=>$g->name ?? 'Guest','label'=>$g->email ?? $g->phone]);

        $this->contacts = $users->merge($guests)->take(12)->values()->all();
    }

    /** Start or reuse a 1:1 conversation with a contact */
    public function startConversationWith(string $participantType, int $participantId): void
    {
        $me = $this->actor();

        // Reuse an existing 1:1 between me and them
        $existing = Conversation::whereHas('participants', function($q) use ($me) {
                $q->where('participant_type',$me['type'])->where('participant_id',$me['id']);
            })
            ->whereHas('participants', function($q) use ($participantType,$participantId) {
                $q->where('participant_type',$participantType)->where('participant_id',$participantId);
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

        ConversationParticipant::create([
            'conversation_id' => $conv->id,
            'participant_type'=> $me['type'],
            'participant_id'  => $me['id'],
            'role'            => $me['type'] === 'user' ? 'support' : 'guest',
        ]);

        ConversationParticipant::create([
            'conversation_id' => $conv->id,
            'participant_type'=> $participantType,
            'participant_id'  => $participantId,
            'role'            => $participantType === 'user' ? 'support' : 'guest',
        ]);

        $this->selectConversation($conv->id);
        $this->open = true; $this->showContacts = false; $this->search = '';
    }

    public function toggleThread(): void { $this->showThread = ! $this->showThread; }

    public function toggleAttachmentMenu(): void { $this->attachmentMenu = ! $this->attachmentMenu; }

    public function attachDocuments($files): void { /* handled by Livewire binding */ }

    public function send(): void
    {
        $this->validate([
            'messageText' => 'nullable|string|max:5000',
            'uploads.*'   => 'file|max:10240', // 10MB each
        ]);

        if (!$this->selectedConversationId) return;

        $me = $this->actor();

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

        $this->refreshConversations();
        $this->dispatch('message-sent');
        $this->dispatch('scroll-bottom');
    }

    public function getMessagesProperty()
    {
        if (!$this->selectedConversationId) return collect();
        return Message::with('attachments','sender')
            ->where('conversation_id',$this->selectedConversationId)
            ->orderBy('created_at')
            ->take(300)
            ->get();
    }



    public function render()
    {
        return view('app::livewire.components.chat-widget');
    }
}
