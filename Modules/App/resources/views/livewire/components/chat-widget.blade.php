<div class="ndako-chat" wire:ignore.self x-data
     x-on:message-sent.window="setTimeout(() => $dispatch('scroll-bottom'), 50)"
     x-on:scroll-bottom.window="
        const el = document.querySelector('.ndako-chat__body');
        if (el) el.scrollTop = el.scrollHeight;"
>
    {{-- Floating FAB --}}
    <button type="button" class="ndako-chat__fab" aria-label="Open chat" wire:click="toggle">
        @if($unreadTotal > 0)
            <span class="ndako-chat__badge">{{ $unreadTotal > 99 ? '99+' : $unreadTotal }}</span>
        @endif
        <svg viewBox="0 0 24 24" width="26" height="26"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM6 9h12M6 13h8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </button>

    {{-- Panel --}}
    <div class="ndako-chat__panel {{ $open ? 'ndako-chat__panel--open' : '' }}" role="dialog" aria-label="Chat">

        {{-- SIDEBAR --}}
        <aside class="ndako-chat__sidebar {{ $showThread ? '' : 'is-full' }}">
            <div class="ndako-chat__sidebar-header">
                <div class="ndako-chat__title">Chats</div>
                <button class="ndako-chat__tiny" title="New chat" @click="$wire.showContacts=true; setTimeout(()=>document.getElementById('ndako-search').focus(),0)">+</button>
            </div>

            {{-- Contact Search --}}
            <div class="ndako-chat__search">
                <input id="ndako-search" type="text" placeholder="Search contacts..."
                       wire:model.debounce.300ms="search">
                @if($showContacts)
                    <div class="ndako-chat__search-pop">
                        @forelse($contacts as $c)
                            <button class="ndako-chat__contact"
                                    wire:click="startConversationWith('{{ $c['type'] }}', {{ $c['id'] }})">
                                <div class="ndako-chat__contact-name">{{ $c['name'] }}</div>
                                <div class="ndako-chat__contact-label">{{ $c['label'] }}</div>
                            </button>
                        @empty
                            <div class="ndako-chat__empty">No matching contacts</div>
                        @endforelse
                    </div>
                @endif
            </div>

            {{-- Conversation list --}}
            <div class="ndako-chat__convos">
                @foreach($conversations as $c)
                    <button class="ndako-chat__convo {{ $selectedConversationId === $c['id'] ? 'is-active' : '' }}"
                            wire:click="selectConversation({{ $c['id'] }})"
                            @click="$wire.showThread=true">
                        <div class="ndako-chat__convo-title">{{ $c['subject'] ?? 'Chat' }}</div>
                        <div class="ndako-chat__convo-meta">
                            <span class="ndako-chat__muted">{{ $c['updated'] }}</span>
                            @if($c['unread'] > 0)
                                <span class="ndako-chat__pill">{{ $c['unread'] }}</span>
                            @endif
                        </div>
                        <div class="ndako-chat__convo-snippet">{{ \Illuminate\Support\Str::limit($c['last'] ?? '—', 50) }}</div>
                    </button>
                @endforeach
            </div>
        </aside>

        {{-- THREAD --}}
        <section class="ndako-chat__thread {{ $showThread ? 'is-open' : 'is-hidden' }}">
            <header class="ndako-chat__header">
                <div class="ndako-chat__left">
                    <button class="ndako-chat__back" title="Back" @click="$wire.toggleThread()">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="ndako-chat__title">
                        @php $current = collect($conversations)->firstWhere('id', $selectedConversationId); @endphp
                        {{ $current['subject'] ?? 'Chat' }}
                    </div>
                </div>
                <div class="ndako-chat__actions">
                    <button class="ndako-chat__ghost" wire:click="refreshConversations">Refresh</button>
                    <button class="ndako-chat__close" aria-label="Close" wire:click="toggle">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </header>

            <div class="ndako-chat__body" id="ndako-body">
                @php $act = auth()->user(); @endphp
                @foreach($this->messages as $m)
                    <div class="ndako-chat__msg {{ $m->sender_type === 'user' && optional(auth()->user())->id === $m->sender_id ? 'is-user' : ($m->sender_type === 'guest' ? 'is-guest' : 'is-agent') }}">
                        <div class="ndako-chat__bubble">
                            @if($m->body)
                                <div class="ndako-chat__text">{!! nl2br(e($m->body)) !!}</div>
                            @endif
                            @if($m->attachments->count())
                                <div class="ndako-chat__files">
                                    @foreach($m->attachments as $a)
                                        <a href="{{ Storage::disk('public')->url($a->path) }}" target="_blank" class="ndako-chat__file">
                                            {{ $a->original_name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <div class="ndako-chat__time">{{ $m->created_at->format('M d, H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Input bar with WhatsApp-like attachment menu --}}
            <form class="ndako-chat__inputbar" wire:submit.prevent="send">
                <button type="button" class="ndako-chat__clip" title="Attach" @click="$wire.toggleAttachmentMenu()">
                    <svg viewBox="0 0 24 24" width="20" height="20"><path d="M21.44 11.05l-8.49 8.49a5 5 0 01-7.07-7.07l9.19-9.19a3.5 3.5 0 014.95 4.95l-9.19 9.19a2 2 0 11-2.83-2.83l8.49-8.49" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>

                @if($attachmentMenu)
                    <div class="ndako-attach">
                        {{-- Photo/Video --}}
                        <label class="ndako-attach__item">
                            <input type="file" wire:model="uploads" multiple accept="image/*,video/*" hidden>
                            <span>Photo/Video</span>
                        </label>
                        {{-- Document --}}
                        <label class="ndako-attach__item">
                            <input type="file" wire:model="uploads" multiple accept=".pdf,.doc,.docx,application/*" hidden>
                            <span>Document</span>
                        </label>
                        {{-- Camera (mobile) --}}
                        <label class="ndako-attach__item">
                            <input type="file" wire:model="uploads" accept="image/*" capture="environment" hidden>
                            <span>Camera</span>
                        </label>
                    </div>
                @endif>

                <input type="text" class="ndako-chat__input" placeholder="Type a message…"
                       wire:model.defer="messageText" @disabled(!$selectedConversationId)>

                <button type="submit" class="ndako-chat__send" title="Send" @disabled(!$selectedConversationId)>
                    <svg viewBox="0 0 24 24" width="18" height="18">
                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <path d="M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/>
                    </svg>
                </button>
            </form>

            {{-- Optional: show names of selected files --}}
            @if(count($uploads) > 0)
                <div class="ndako-attach__preview">
                    @foreach($uploads as $i => $f)
                        <span class="ndako-attach__chip">{{ $f->getClientOriginalName() }}</span>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
    <style>
/* Base + FAB */
.ndako-chat { position: fixed; right: 20px; bottom: 20px; z-index: 9999; font-family: system-ui,-apple-system,"Segoe UI",Roboto,Inter,Arial,sans-serif; }
.ndako-chat__fab { position: relative; display:flex; align-items:center; justify-content:center; width:56px; height:56px; border-radius:50%; background:#111827; color:#fff; border:none; cursor:pointer; box-shadow:0 8px 24px rgba(0,0,0,.2),0 2px 6px rgba(0,0,0,.15); }
.ndako-chat__badge { position:absolute; top:-6px; right:-6px; min-width:20px; height:20px; padding:0 6px; background:#ef4444; color:#fff; border-radius:999px; font-size:12px; line-height:20px; text-align:center; box-shadow:0 0 0 2px #fff; }

/* Panel */
.ndako-chat__panel { display:none; position:fixed; right:20px; bottom:90px; width:900px; max-width:calc(100vw - 40px); height:540px; background:#fff; border:1px solid #e5e7eb; border-radius:14px; box-shadow:0 18px 60px rgba(0,0,0,.22),0 8px 24px rgba(0,0,0,.16); overflow:hidden; }
.ndako-chat__panel--open { display:flex; }

/* Sidebar / Thread */
.ndako-chat__sidebar { width:320px; border-right:1px solid #e5e7eb; display:flex; flex-direction:column; }
.ndako-chat__sidebar.is-full { width:100%; }
.ndako-chat__thread { flex:1; display:flex; flex-direction:column; min-width:0; }
.ndako-chat__thread.is-hidden { display:none; }
.ndako-chat__thread.is-open { display:flex; }

/* Sidebar header + search */
.ndako-chat__sidebar-header { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; }
.ndako-chat__title { font-weight:600; color:#111827; }
.ndako-chat__tiny { width:26px; height:26px; border-radius:6px; border:1px solid #111827; background:#111827; color:#fff; cursor:pointer; }
.ndako-chat__search { position:relative; padding:8px; border-bottom:1px solid #e5e7eb; }
.ndako-chat__search input { width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; outline:none; }
.ndako-chat__search-pop { position:absolute; left:8px; right:8px; top:48px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 16px 40px rgba(0,0,0,.12); max-height:260px; overflow:auto; z-index:20; }
.ndako-chat__contact { display:block; width:100%; text-align:left; padding:10px 12px; border:none; background:#fff; cursor:pointer; }
.ndako-chat__contact:hover { background:#f8fafc; }
.ndako-chat__contact-name { font-weight:600; }
.ndako-chat__contact-label { font-size:12px; color:#6b7280; }

/* Conversation list */
.ndako-chat__convos { overflow:auto; padding:6px; }
.ndako-chat__convo { width:100%; text-align:left; border:1px solid transparent; border-radius:10px; padding:8px; margin:6px 0; background:#fff; cursor:pointer; }
.ndako-chat__convo:hover { background:#f3f4f6; }
.ndako-chat__convo.is-active { border-color:#111827; background:#f9fafb; }
.ndako-chat__convo-title { font-weight:600; }
.ndako-chat__convo-meta { display:flex; gap:6px; align-items:center; margin-top:2px; }
.ndako-chat__muted { color:#6b7280; font-size:12px; }
.ndako-chat__pill { background:#111827; color:#fff; border-radius:999px; padding:0 6px; font-size:12px; line-height:18px; }
.ndako-chat__convo-snippet { color:#374151; font-size:13px; margin-top:4px; }
.ndako-chat__empty { padding:14px; text-align:center; color:#6b7280; }

/* Thread header/body */
.ndako-chat__header { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
.ndako-chat__left { display:flex; align-items:center; gap:8px; }
.ndako-chat__back { display:none; background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:6px; cursor:pointer; }
.ndako-chat__actions { display:flex; gap:8px; align-items:center; }
.ndako-chat__ghost { background:#fff; border:1px solid #111827; color:#111827; border-radius:8px; padding:6px 10px; cursor:pointer; }
.ndako-chat__close { background:transparent; border:none; color:#6b7280; cursor:pointer; padding:6px; border-radius:8px; }
.ndako-chat__close:hover { background:#f3f4f6; color:#111827; }

/* Messages */
.ndako-chat__body { flex:1; padding:12px; overflow:auto; background:#fff; }
.ndako-chat__msg { display:flex; margin:6px 0; }
.ndako-chat__msg.is-user { justify-content:flex-end; }
.ndako-chat__bubble { max-width:80%; background:#f3f4f6; color:#111827; padding:8px 10px; border-radius:12px; }
.ndako-chat__msg.is-user .ndako-chat__bubble { background:#111827; color:#fff; }
.ndako-chat__text { white-space:pre-wrap; word-wrap:break-word; }
.ndako-chat__files { margin-top:6px; display:flex; gap:6px; flex-wrap:wrap; }
.ndako-chat__file { font-size:12px; text-decoration:underline; color:inherit; }
.ndako-chat__time { margin-top:4px; font-size:11px; opacity:.7; }

/* Input bar + attachment menu */
.ndako-chat__inputbar { display:flex; align-items:center; gap:8px; padding:10px; border-top:1px solid #e5e7eb; background:#f9fafb; position:relative; }
.ndako-chat__clip { border:none; background:transparent; cursor:pointer; padding:6px; border-radius:10px; }
.ndako-attach { position:absolute; bottom:52px; left:10px; display:flex; gap:8px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:8px; box-shadow:0 16px 40px rgba(0,0,0,.12); }
.ndako-attach__item { border:1px dashed #9ca3af; border-radius:10px; padding:6px 10px; font-size:13px; color:#111827; cursor:pointer; background:#fff; }
.ndako-attach__preview { padding:6px 10px; background:#fff; border-top:1px solid #e5e7eb; display:flex; gap:6px; flex-wrap:wrap; }
.ndako-attach__chip { font-size:12px; background:#f3f4f6; padding:4px 8px; border-radius:999px; }

.ndako-chat__input { flex:1; padding:10px 12px; border-radius:10px; border:1px solid #e5e7eb; outline:none; font-size:14px; }
.ndako-chat__input:focus { border-color:#111827; box-shadow:0 0 0 3px rgba(17,24,39,.08); }
.ndako-chat__send { padding:0 12px; border-radius:10px; border:1px solid #111827; background:#111827; color:#fff; cursor:pointer; }

/* Responsive: mobile shows either sidebar OR thread; back btn appears */
@media (max-width: 900px) {
  .ndako-chat__panel { width: calc(100vw - 20px); right: 10px; height: 70vh; bottom: 82px; }
  .ndako-chat { right: 10px; bottom: 10px; }
  .ndako-chat__sidebar { width: 100%; }
  .ndako-chat__thread { width: 100%; }
  .ndako-chat__back { display:inline-flex; }
}
</style>
</div>


