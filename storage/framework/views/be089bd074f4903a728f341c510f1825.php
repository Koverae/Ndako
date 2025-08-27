<div class="ndako-chat" wire:ignore.self x-data
     x-on:message-sent.window="setTimeout(() => $dispatch('scroll-bottom'), 50)"
     x-on:scroll-bottom.window="
        const el = document.querySelector('.ndako-chat__body');
        if (el) el.scrollTop = el.scrollHeight;"
>
    
    <button type="button" class="ndako-chat__fab" aria-label="Open chat" wire:click="toggle">
        <!--[if BLOCK]><![endif]--><?php if($unreadTotal > 0): ?>
            <span class="ndako-chat__badge"><?php echo e($unreadTotal > 99 ? '99+' : $unreadTotal); ?></span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <svg viewBox="0 0 24 24" width="26" height="26"><path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM6 9h12M6 13h8" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    </button>

    
    <div class="ndako-chat__panel <?php echo e($open ? 'ndako-chat__panel--open' : ''); ?>" role="dialog" aria-label="Chat">
        
        <aside class="ndako-chat__sidebar">
            <div class="ndako-chat__sidebar-header">
                <div class="ndako-chat__title">Conversations</div>
                <button class="ndako-chat__tiny" wire:click="startSupportChat" title="New with Support">+</button>
            </div>

            <div class="ndako-chat__convos">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <button class="ndako-chat__convo <?php echo e($selectedConversationId === $c['id'] ? 'is-active' : ''); ?>"
                            wire:click="selectConversation(<?php echo e($c['id']); ?>)">
                        <div class="ndako-chat__convo-title"><?php echo e($c['subject']); ?></div>
                        <div class="ndako-chat__convo-meta">
                            <span class="ndako-chat__muted"><?php echo e($c['updated']); ?></span>
                            <!--[if BLOCK]><![endif]--><?php if($c['unread'] > 0): ?>
                                <span class="ndako-chat__pill"><?php echo e($c['unread']); ?></span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="ndako-chat__convo-snippet"><?php echo e(\Illuminate\Support\Str::limit($c['last'] ?? '—', 50)); ?></div>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="ndako-chat__empty">
                        No conversations yet.
                        <button class="ndako-chat__ghost" wire:click="startSupportChat">Start Support Chat</button>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </aside>

        
        <section class="ndako-chat__thread">
            <header class="ndako-chat__header">
                <div class="ndako-chat__title">
                    <?php
                        $current = collect($conversations)->firstWhere('id', $selectedConversationId);
                    ?>
                    <?php echo e($current['subject'] ?? 'Support'); ?>

                    <!--[if BLOCK]><![endif]--><?php if(($current['status'] ?? 'open') === 'closed'): ?>
                        <span class="ndako-chat__status">Closed</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="ndako-chat__actions">
                    <label class="ndako-chat__attach">
                        <input type="file" wire:model="uploads" multiple hidden>
                        <span>Attach</span>
                    </label>
                    <button class="ndako-chat__ghost" wire:click="resolveConversation" <?php if(!$selectedConversationId): echo 'disabled'; endif; ?>>
                        Resolve
                    </button>
                    <button class="ndako-chat__close" aria-label="Close" wire:click="toggle">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </header>

            <div class="ndako-chat__body" id="ndako-body">
                <?php $uid = auth()->id(); ?>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="ndako-chat__msg <?php echo e($m->user_id === $uid ? 'is-user' : 'is-agent'); ?>">
                        <div class="ndako-chat__bubble">
                            <!--[if BLOCK]><![endif]--><?php if($m->body): ?>
                                <div class="ndako-chat__text"><?php echo nl2br(e($m->body)); ?></div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if($m->attachments->count()): ?>
                                <div class="ndako-chat__files">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $m->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(Storage::disk('public')->url($a->path)); ?>" target="_blank" class="ndako-chat__file">
                                            <?php echo e($a->original_name); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <div class="ndako-chat__time"><?php echo e($m->created_at->format('M d, H:i')); ?></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <form class="ndako-chat__inputbar" wire:submit.prevent="send">
                <input type="text" class="ndako-chat__input" placeholder="Type a message…"
                       wire:model.defer="messageText" <?php if(!$selectedConversationId): echo 'disabled'; endif; ?>>
                <button type="submit" class="ndako-chat__send" title="Send" <?php if(!$selectedConversationId): echo 'disabled'; endif; ?>>
                    <svg viewBox="0 0 24 24" width="18" height="18">
                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <path d="M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" fill="none"/>
                    </svg>
                </button>
            </form>
        </section>
    </div>

<style>
/* Container + FAB */
.ndako-chat { position: fixed; right: 20px; bottom: 20px; z-index: 9999; font-family: system-ui, -apple-system, "Segoe UI", Roboto, Inter, Arial, sans-serif; }
.ndako-chat__fab { position: relative; display:flex; align-items:center; justify-content:center; width:56px; height:56px; border-radius:50%; background:#111827; color:#fff; border:none; cursor:pointer; box-shadow:0 8px 24px rgba(0,0,0,.2),0 2px 6px rgba(0,0,0,.15); }
.ndako-chat__badge { position:absolute; top:-6px; right:-6px; min-width:20px; height:20px; padding:0 6px; background:#ef4444; color:#fff; border-radius:999px; font-size:12px; line-height:20px; text-align:center; box-shadow:0 0 0 2px #fff; }

/* Panel layout */
.ndako-chat__panel { display:none; position:fixed; right:20px; bottom:90px; width:720px; max-width:calc(100vw - 40px); height:480px; background:#fff; border:1px solid #e5e7eb; border-radius:14px; box-shadow:0 18px 60px rgba(0,0,0,.22),0 8px 24px rgba(0,0,0,.16); overflow:hidden; }
.ndako-chat__panel--open { display:flex; }
.ndako-chat__sidebar { width:260px; border-right:1px solid #e5e7eb; display:flex; flex-direction:column; }
.ndako-chat__thread { flex:1; display:flex; flex-direction:column; min-width:0; }

/* Sidebar */
.ndako-chat__sidebar-header { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:#f9fafb; border-bottom:1px solid #e5e7eb; }
.ndako-chat__title { font-weight:600; color:#111827; }
.ndako-chat__tiny { width:26px; height:26px; border-radius:6px; border:1px solid #111827; background:#111827; color:#fff; cursor:pointer; }
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
.ndako-chat__ghost { background:#fff; border:1px solid #111827; color:#111827; border-radius:8px; padding:6px 10px; cursor:pointer; }

/* Thread */
.ndako-chat__header { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
.ndako-chat__status { margin-left:8px; font-size:12px; color:#6b7280; border:1px solid #e5e7eb; border-radius:8px; padding:2px 6px; }
.ndako-chat__actions { display:flex; gap:8px; align-items:center; }
.ndako-chat__attach { border:1px dashed #9ca3af; border-radius:8px; padding:4px 8px; font-size:13px; color:#111827; cursor:pointer; }
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

/* Input */
.ndako-chat__inputbar { display:flex; gap:8px; padding:10px; border-top:1px solid #e5e7eb; background:#f9fafb; }
.ndako-chat__input { flex:1; padding:10px 12px; border-radius:10px; border:1px solid #e5e7eb; outline:none; font-size:14px; }
.ndako-chat__input:focus { border-color:#111827; box-shadow:0 0 0 3px rgba(17,24,39,.08); }
.ndako-chat__send { padding:0 12px; border-radius:10px; border:1px solid #111827; background:#111827; color:#fff; cursor:pointer; }

/* Responsive */
@media (max-width: 720px) {
  .ndako-chat__panel { width: calc(100vw - 20px); right: 10px; height: 70vh; bottom: 82px; }
  .ndako-chat { right: 10px; bottom: 10px; }
  .ndako-chat__sidebar { display:none; }
  .ndako-chat__bubble { max-width: 92%; }
}
</style>
</div>

<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/chat-widget.blade.php ENDPATH**/ ?>