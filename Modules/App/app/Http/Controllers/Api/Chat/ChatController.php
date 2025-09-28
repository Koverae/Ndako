<?php

namespace Modules\App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\App\Models\Chat\Conversation;
use Modules\App\Models\Chat\ConversationParticipant;
use Modules\App\Models\Chat\Message;
use Modules\App\Models\Chat\MessageAttachment;
use Modules\App\Transformers\Chat\ConversationResource;
use Modules\App\Transformers\Chat\MessageResource;
use Modules\ChannelManager\Models\Guest\Guest;

class ChatController extends Controller
{
    /**
     * Resolve the acting principal (extend if you add a guest guard later).
     */
    protected function actor(): array
    {
        return [
            'type' => 'user',
            'id'   => (int) Auth::id(),
            'name' => Auth::user()->name ?? 'User',
        ];
    }

    /**
     * Ensure the current actor is a participant of the given conversation.
     */
    protected function assertParticipant(Conversation $conversation): void
    {
        $actor = $this->actor();

        abort_unless(
            $conversation->participants()
                ->where('participant_type', $actor['type'])
                ->where('participant_id', $actor['id'])
                ->exists(),
            403,
            'You are not a participant of this conversation.'
        );
    }

    /**
     * GET /conversations
     * List conversations for the actor (newest first).
     */
    public function indexConversations(Request $request)
    {
        $actor = $this->actor();

        $items = Conversation::query()
            ->with([
                'participants',
                'messages' => fn ($q) => $q->latest()->limit(1),
            ])
            ->whereHas('participants', function ($q) use ($actor) {
                $q->where('participant_type', $actor['type'])
                  ->where('participant_id', $actor['id']);
            })
            ->orderByDesc('updated_at')
            ->paginate(min((int) $request->query('per_page', 30), 100));

        return ConversationResource::collection($items);
    }

    /**
     * GET /conversations/{conversation}
     * Retrieve conversation meta + participants (guarded).
     */
    public function showConversation(Request $request, Conversation $conversation)
    {
        $this->assertParticipant($conversation);

        return new ConversationResource(
            $conversation->load(['participants'])
        );
    }

    /**
     * GET /conversations/{conversation}/messages
     * Paginated messages (newest first). Client can reverse for chronological.
     */
    public function messages(Request $request, Conversation $conversation)
    {
        $this->assertParticipant($conversation);

        $perPage = min((int) $request->query('per_page', 40), 100);

        $messages = Message::query()
            ->with('attachments')
            ->where('conversation_id', $conversation->id)
            ->orderByDesc('id')
            ->paginate($perPage);

        return MessageResource::collection($messages);
    }

    /**
     * POST /conversations
     * Start or reuse a 1:1 conversation with a contact.
     */
    public function startConversation(Request $request)
    {
        $data = $request->validate([
            'participant_type' => 'required|in:user,guest',
            'participant_id'   => 'required|integer',
        ]);

        $me = $this->actor();

        if ((string) $data['participant_type'] === (string) $me['type'] && (int) $data['participant_id'] === (int) $me['id']) {
            throw ValidationException::withMessages(['participant_id' => 'Cannot start a conversation with yourself.']);
        }

        // Reuse if already exists
        $existing = Conversation::query()
            ->whereHas('participants', function ($q) use ($me) {
                $q->where('participant_type', $me['type'])->where('participant_id', $me['id']);
            })
            ->whereHas('participants', function ($q) use ($data) {
                $q->where('participant_type', $data['participant_type'])->where('participant_id', $data['participant_id']);
            })
            ->first();

        if ($existing) {
            return new ConversationResource($existing->load(['participants']));
        }

        // Create a new 1:1 conversation
        $conv = DB::transaction(function () use ($me, $data) {
            /** @var Conversation $conv */
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
                    'participant_type' => $data['participant_type'],
                    'participant_id'   => (int) $data['participant_id'],
                ],
                ['role' => $data['participant_type'] === 'user' ? 'support' : 'guest']
            );

            return $conv;
        });

        return new ConversationResource($conv->load(['participants']));
    }

    /**
     * POST /conversations/{conversation}/message
     * Send a message (text + optional attachments).
     */
    public function send(Request $request, Conversation $conversation)
    {
        $this->assertParticipant($conversation);

        $validated = $request->validate([
            'body'     => 'nullable|string|max:5000',
            'files.*'  => 'file|max:10240', // 10MB each
        ]);

        $me = $this->actor();

        if (empty(trim((string) ($validated['body'] ?? ''))) && !$request->hasFile('files')) {
            throw ValidationException::withMessages(['body' => 'Message cannot be empty.']);
        }

        $message = DB::transaction(function () use ($conversation, $validated, $request, $me) {
            /** @var Message $msg */
            $msg = Message::create([
                'conversation_id' => $conversation->id,
                'sender_type'     => $me['type'],
                'sender_id'       => $me['id'],
                'type'            => 'text',
                'body'            => trim((string) ($validated['body'] ?? '')) ?: null,
            ]);

            // Save each attachment to storage and DB
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = $file->store('chat', 'public');

                    MessageAttachment::create([
                        'message_id'    => $msg->id,
                        'path'          => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type'     => $file->getClientMimeType(),
                        'size'          => $file->getSize(),
                    ]);
                }
            }

            // Touch conversation updated_at
            $conversation->touch();

            return $msg;
        });

        // Mark the sender's last_read_at so their unread stays at 0 for this message
        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('participant_type', $me['type'])
            ->where('participant_id', $me['id'])
            ->update(['last_read_at' => now()]);

        return new MessageResource($message->load('attachments'));
    }

    /**
     * POST /conversations/{conversation}/read
     * Mark conversation as read for the actor.
     */
    public function markRead(Conversation $conversation)
    {
        $this->assertParticipant($conversation);

        $actor = $this->actor();

        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('participant_type', $actor['type'])
            ->where('participant_id', $actor['id'])
            ->update(['last_read_at' => now()]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * POST /conversations/{conversation}/unread
     * Force conversation as unread (set a very old last_read_at).
     */
    public function markUnread(Conversation $conversation)
    {
        $this->assertParticipant($conversation);

        $actor = $this->actor();

        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('participant_type', $actor['type'])
            ->where('participant_id', $actor['id'])
            ->update(['last_read_at' => now()->subYears(20)]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * POST /conversations/{conversation}/close
     * Soft-close a conversation.
     */
    public function close(Conversation $conversation)
    {
        $this->assertParticipant($conversation);

        $conversation->update(['status' => 'closed']);

        return new ConversationResource($conversation->fresh('participants'));
    }

    /**
     * DELETE /conversations/{conversation}
     * Delete a conversation (and attachments files).
     */
    public function destroy(Conversation $conversation)
    {
        $this->assertParticipant($conversation);

        DB::transaction(function () use ($conversation) {
            // Delete attachment files from storage
            $attachments = MessageAttachment::query()
                ->whereIn('message_id', function ($q) use ($conversation) {
                    $q->select('id')->from('messages')->where('conversation_id', $conversation->id);
                })
                ->get();

            foreach ($attachments as $att) {
                if ($att->path && Storage::disk('public')->exists($att->path)) {
                    Storage::disk('public')->delete($att->path);
                }
            }

            // Cascade delete messages & participants via DB relations if set up,
            // otherwise delete explicitly:
            Message::where('conversation_id', $conversation->id)->delete();
            ConversationParticipant::where('conversation_id', $conversation->id)->delete();

            $conversation->delete();
        });

        return response()->json(['status' => 'deleted']);
    }

    /**
     * GET /contacts/search?q=...
     * Merge Users + Guests, return unified list for the popover.
     */
    public function searchContacts(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if ($q === '') {
            return response()->json(['data' => []]);
        }

        $users = User::query()
            // ->isCompany(current_company()->id)
                ->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
                })
                ->limit(8)
                    ->get()
                        ->map(function (User $u) {
                            return [
                                'type'   => 'user',
                                'id'     => (int) $u->id,
                                'name'   => $u->name ?? 'User',
                                'label'  => $u->email ?? '',
                                'avatar' => $u->getAvatarUrlAttribute() ?? null,
                            ];
                    });

        $guests = Guest::query()
            ->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
            })
            ->limit(8)
                ->get()
                    ->map(function (Guest $g) {
                        return [
                            'type'   => 'guest',
                            'id'     => (int) $g->id,
                            'name'   => $g->name ?? 'Guest',
                            'label'  => $g->email ?? ($g->phone ?? ''),
                            'avatar' => $g->getAvatarUrlAttribute() ?? null,
                        ];
                    });

        // Keep a balanced list (max 12 total like the Livewire)
        $merged = $users->concat($guests)->take(12)->values();

        return response()->json(['data' => $merged]);
    }

}
