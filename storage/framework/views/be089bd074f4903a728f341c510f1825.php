<?php
    use Illuminate\Support\Facades\Storage;
    use Carbon\Carbon;
?>

<div class="ndako-chat" wire:ignore.self
     x-data="ndakoChat()"
     x-on:message-sent.window="setTimeout(() => $dispatch('scroll-bottom'), 50)"
     x-on:scroll-bottom.window="
        const el = document.querySelector('.ndako-chat__body');
        if (el) el.scrollTop = el.scrollHeight;"
>
    
    <button type="button" class="ndako-chat__fab" aria-label="Open chat" wire:click="toggle">
        <!--[if BLOCK]><![endif]--><?php if($unreadTotal > 0): ?>
            <span class="ndako-chat__badge"><?php echo e($unreadTotal > 99 ? '99+' : $unreadTotal); ?></span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <svg viewBox="0 0 24 24" width="26" height="26">
            <path d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM6 9h12M6 13h8"
                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </button>

    
    <div class="ndako-chat__panel <?php echo e($open ? 'ndako-chat__panel--open' : ''); ?>" role="dialog" aria-label="Chat">

        
        <aside class="ndako-chat__sidebar <?php echo e($showThread ? '' : 'is-full'); ?>">
            <div class="ndako-chat__sidebar-header">
                <div class="ndako-chat__title">Chats</div>
                <button class="ndako-chat__ghost" wire:click="refreshConversations">Refresh</button>
                <button class="ndako-chat__tiny" title="New chat"
                        @click="$wire.showContacts=true; setTimeout(()=>document.getElementById('ndako-search').focus(),0)">+
                </button>
            </div>

            
            <div class="ndako-chat__search">
                <div class="ndako-input-wrap">
                    <input id="ndako-search" type="text" placeholder="Search contacts..."
                           wire:model.live.debounce.300ms="search">
                    <span class="ndako-spin" wire:loading wire:target="search"></span>
                </div>

                <!--[if BLOCK]><![endif]--><?php if($showContacts): ?>
                    <div class="ndako-chat__search-pop" wire:loading.remove wire:target="search">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <button class="ndako-chat__contact"
                                    wire:click="startConversationWith('<?php echo e($c['type']); ?>', <?php echo e($c['id']); ?>)">
                                <!--[if BLOCK]><![endif]--><?php if(!empty($c['avatar'])): ?>
                                    <img src="<?php echo e(Storage::url('avatars/' . $c['avatar'])); ?>"
                                         class="ndako-chip <?php echo e($c['type'] === 'user' ? 'chip-user' : 'chip-guest'); ?>"
                                         alt="">
                                <?php else: ?>
                                    <div class="ndako-chip <?php echo e($c['type'] === 'user' ? 'chip-user' : 'chip-guest'); ?>">
                                        <?php echo e(strtoupper($c['type'][0])); ?>

                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div>
                                    <div class="ndako-chat__contact-name"><?php echo e($c['name']); ?></div>
                                    <div class="ndako-chat__contact-label"><?php echo e($c['label']); ?></div>
                                </div>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="ndako-chat__empty">No matching contacts</div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="ndako-chat__search-pop" wire:loading wire:target="search">
                        <!--[if BLOCK]><![endif]--><?php for($i=0; $i<4; $i++): ?>
                            <div class="ndako-skel-line"></div>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div class="ndako-chat__convos">

                <div wire:loading wire:target="selectConversation" class="ndako-skeleton">
                    <!--[if BLOCK]><![endif]--><?php for($i=0;$i<6;$i++): ?>
                        <div class="ndako-skel-row"></div>
                    <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div wire:loading.remove wire:target="selectConversation">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="ndako-convo-wrap"
                             x-data="ndakoSwipe({
                                id: <?php echo e($c['id']); ?>,
                                width: () => window.innerWidth < 460 ? 208 : 264,
                             })"
                             x-on:ndako-close-all-swipes.window="close()"
                             @keydown.escape.window="close()">

                            
                            <div class="ndako-actions"
                                 :class="{ 'is-open': opened }"
                                 :style="`width:${railWidth}px`"
                                 aria-hidden="true">
                                <button class="ndako-act ndako-act--pin" title="<?php echo e(($c['pinned'] ?? false) ? 'Unpin' : 'Pin'); ?>"
                                        wire:click="togglePin(<?php echo e($c['id']); ?>)" @click.stop="close()">
                                    <i class="bi bi-pin-angle-fill"></i>
                                </button>
                                <button class="ndako-act ndako-act--mute" title="<?php echo e(($c['muted'] ?? false) ? 'Unmute' : 'Mute'); ?>"
                                        wire:click="toggleMute(<?php echo e($c['id']); ?>)" @click.stop="close()">
                                    <i class="bi bi-bell-slash-fill"></i>
                                </button>
                                <!--[if BLOCK]><![endif]--><?php if(($c['unread'] ?? 0) > 0): ?>
                                    <button class="ndako-act ndako-act--read" title="Mark as read"
                                            wire:click="markAsRead(<?php echo e($c['id']); ?>)" @click.stop="close()">
                                        <i class="bi bi-check2-all"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="ndako-act ndako-act--read" title="Mark as unread"
                                            wire:click="markAsUnread(<?php echo e($c['id']); ?>)" @click.stop="close()">
                                        <i class="bi bi-dot"></i>
                                    </button>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <button class="ndako-act ndako-act--close" title="Close conversation"
                                        wire:click="closeConversation(<?php echo e($c['id']); ?>)" @click.stop="close()">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                                <button class="ndako-act ndako-act--danger" title="Delete"
                                        wire:click="deleteConversation(<?php echo e($c['id']); ?>)" @click.stop="close()">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>

                            
                            <div class="ndako-convo <?php echo e($selectedConversationId === $c['id'] ? 'is-active' : ''); ?>"
                                 role="button" tabindex="0"
                                 x-on:touchstart.passive="start($event)"
                                 x-on:touchmove.passive="move($event)"
                                 x-on:touchend="end()"
                                 x-on:mousedown="start($event)" x-on:mousemove="move($event)" x-on:mouseup="end()"
                                 x-on:mouseleave="cancel()"
                                 x-on:click.outside="close()"
                                 :style="`transform: translateX(${translateX}px)`"
                                 @keydown.enter.prevent="$wire.selectConversation(<?php echo e($c['id']); ?>); $wire.showThread = true; $dispatch('ndako-close-all-swipes')">

                                
                                <button class="ndako-convo__avatar"
                                        wire:click="selectConversation(<?php echo e($c['id']); ?>)"
                                        @click="$wire.showThread=true; $dispatch('ndako-close-all-swipes')">
                                    <!--[if BLOCK]><![endif]--><?php if(!empty($c['avatar'])): ?>
                                        <img src="<?php echo e(Storage::url('avatars/' . $c['avatar'])); ?>" alt="">
                                    <?php else: ?>
                                        <div class="ndako-convo__fallback">
                                            <?php echo e(strtoupper(mb_substr($c['other_name'], 0, 1))); ?>

                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <span class="ndako-convo__chip <?php echo e(($c['other_type'] ?? 'user') === 'user' ? 'chip-user' : 'chip-guest'); ?>">
                                        <?php echo e(strtoupper(mb_substr($c['other_type'] ?? 'u', 0, 1))); ?>

                                    </span>
                                </button>

                                
                                <button class="ndako-convo__main"
                                        wire:click="selectConversation(<?php echo e($c['id']); ?>)"
                                        @click="$wire.showThread=true; $dispatch('ndako-close-all-swipes')">
                                    <div class="ndako-convo__row1">
                                        <div class="ndako-convo__title">
                                            <!--[if BLOCK]><![endif]--><?php if(($c['pinned'] ?? false)): ?> <span class="ndako-pin">📌</span> <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php echo e($c['other_name']); ?>

                                        </div>
                                        <div class="ndako-convo__time"><?php echo e($c['last_at']); ?></div>
                                    </div>
                                    <div class="ndako-convo__row2">
                                        <div class="ndako-convo__last"><?php echo e($c['last_line']); ?></div>
                                        <div class="ndako-convo__badges">
                                            <!--[if BLOCK]><![endif]--><?php if(($c['muted'] ?? false)): ?> <span class="ndako-convo__mute" title="Muted">🔕</span> <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <!--[if BLOCK]><![endif]--><?php if(($c['unread'] ?? 0) > 0): ?> <span class="ndako-convo__unread"><?php echo e($c['unread']); ?></span> <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                </button>

                                
                                <div class="ndako-convo__kebab kebab-inline">
                                    <button class="ndako-kebab__btn"
                                            title="More"
                                            @click.stop="openRail()"
                                            aria-label="More options">⋮</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="ndako-chat__empty">No conversations yet.</div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </aside>

        
        <section class="ndako-chat__thread <?php echo e($showThread ? 'is-open' : 'is-hidden'); ?>">
            <header class="ndako-chat__header">
                <div class="ndako-chat__left">
                    <button class="ndako-chat__back" title="Back" @click="$wire.toggleThread()">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>

                    <?php $current = collect($conversations)->firstWhere('id', $selectedConversationId); ?>

                    <div class="ndako-chat__title ndako-chat__title--rich">
                        <!--[if BLOCK]><![endif]--><?php if($selectedConversationId && !empty($current['avatar'])): ?>
                            <img src="<?php echo e(Storage::url('avatars/' . $current['avatar'])); ?>" class="ndako-title-avatar" alt="">
                        <?php elseif($selectedConversationId): ?>
                            <div class="ndako-title-avatar ndako-title-fallback">
                                <?php echo e(strtoupper(mb_substr($current['other_name'] ?? 'C', 0, 1))); ?>

                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <div class="ndako-title-texts">
                            <div class="ndako-title-name">
                                <?php echo e($selectedConversationId ? ($current['other_name'] ?? 'Chat') : 'Ndako Chat'); ?>

                            </div>
                            <!--[if BLOCK]><![endif]--><?php if($selectedConversationId): ?>
                                <div class="ndako-title-sub">
                                    <span x-show="$wire.remoteTyping" x-cloak>typing…</span>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>

                <div class="ndako-chat__actions ndako-actions-header">
                    <!--[if BLOCK]><![endif]--><?php if($selectedConversationId): ?>
                        <button class="ndako-ghost-icon" title="Voice call"><i class="bi bi-telephone"></i></button>
                        <button class="ndako-ghost-icon" title="Video call"><i class="bi bi-camera-video"></i></button>
                        <button class="ndako-ghost-icon" title="Contact info"><i class="bi bi-info-circle"></i></button>
                        <button class="ndako-ghost-icon" title="Search in chat"><i class="bi bi-search"></i></button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <button class="ndako-chat__close" aria-label="Close" wire:click="toggle">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                </div>
            </header>

            
            <!--[if BLOCK]><![endif]--><?php if(!$selectedConversationId): ?>
                <div class="ndako-thread-empty">
                    <img src="<?php echo e(asset('assets/images/logo/logo-circle-white.png')); ?>" alt="Ndako" class="ndako-empty-logo"
                         onerror="this.style.display='none'">
                    <div class="ndako-empty-title">Ndako Messenger</div>
                    <div class="ndako-empty-sub">Select a conversation or start a new one to begin chatting.</div>
                </div>
            <?php else: ?>
                
                <div class="ndako-chat__body" id="ndako-body"
                     x-data="ndakoMenus($wire)"
                     @contextmenu.prevent="openConvMenu($event)"
                     @click="hideAll()"
                     @scroll.passive="onBodyScroll($event)">

                    
                    <div class="ndako-load-older" x-show="canLoadOlder" @click="$wire.loadOlder(); $nextTick(()=>scrollToAnchor())">
                        <i class="bi bi-chevron-up"></i> Older messages
                    </div>

                    <?php
                        $prevDayKey = null;
                        $today = Carbon::today();
                        $yesterday = Carbon::yesterday();
                        $labelForDate = function(Carbon $d) use ($today, $yesterday) {
                            if ($d->isSameDay($today)) return 'Today';
                            if ($d->isSameDay($yesterday)) return 'Yesterday';
                            return $d->format('d/m/Y');
                        };
                    ?>

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $msgDay = Carbon::parse($m->created_at)->startOfDay();
                            $dayKey = $msgDay->format('Y-m-d');
                        ?>

                        <!--[if BLOCK]><![endif]--><?php if($dayKey !== $prevDayKey): ?>
                            <div class="ndako-timeline"><span><?php echo e($labelForDate($msgDay)); ?></span></div>
                            <?php $prevDayKey = $dayKey; ?>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <?php
                            $isMine = ($m->sender_type === 'user' && optional(auth()->user())->id === $m->sender_id);
                            $timeOnly = Carbon::parse($m->created_at)->format('H:i');
                        ?>

                        <div class="ndako-chat__msg <?php echo e($isMine ? 'is-user' : 'is-agent'); ?>">
                            <div class="ndako-chat__bubble <?php echo e($isMine ? 'b-user' : 'b-peer'); ?>"
                                 @contextmenu.prevent.stop="openMsgMenu($event, <?php echo e($m->id); ?>, <?php echo e(json_encode($isMine)); ?>)"
                                 @touchstart.passive="press($event, <?php echo e($m->id); ?>, <?php echo e(json_encode($isMine)); ?>)"
                                 @touchend="clearPress()" @touchmove="clearPress()">
                                <!--[if BLOCK]><![endif]--><?php if($m->body): ?>
                                    <div class="ndako-chat__text"><?php echo nl2br(e($m->body)); ?></div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <!--[if BLOCK]><![endif]--><?php if($m->attachments->count()): ?>
                                    <div class="ndako-chat__files">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $m->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(Storage::disk('public')->url($a->path)); ?>"
                                               target="_blank" class="ndako-chat__file">
                                                <?php echo e($a->original_name); ?>

                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                <div class="ndako-chat__time"><?php echo e($timeOnly); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                    
                    <div class="ndako-msgmenu" x-show="convMenu.open" x-transition.opacity x-cloak
                         :style="`left:${convMenu.x}px; top:${convMenu.y}px`" @click.stop>
                        <button @click="closeConvo()"><i class="bi bi-x-circle"></i> Close conversation</button>
                        <button class="danger" @click="deleteConvo()"><i class="bi bi-trash"></i> Delete conversation</button>
                    </div>

                    
                    <div class="ndako-msgmenu" x-show="msgMenu.open" x-transition.opacity x-cloak
                         :style="`left:${msgMenu.x}px; top:${msgMenu.y}px`" @click.stop>
                        <button @click="reply()"><i class="bi bi-reply"></i> Reply</button>
                        <button @click="copy()"><i class="bi bi-clipboard"></i> Copy</button>
                        <button @click="forward()"><i class="bi bi-forward"></i> Forward</button>
                        <button class="danger" @click="remove()"><i class="bi bi-trash"></i> Delete</button>
                    </div>
                </div>

                
                <form class="ndako-chat__inputbar" wire:submit.prevent="send"
                      x-data="ndakoInput($wire)" @click.away="hidePopovers()">

                    
                    <button type="button" class="ndako-chat__clip" title="Attach"
                            @click="toggleAttach()"
                            wire:loading.attr="disabled" wire:target="send,uploads">
                        <i class="bi bi-paperclip"></i>
                    </button>

                    <template x-if="attachOpen">
                        <div class="ndako-attach ndako-attach--grid" x-transition.scale.origin.bottom.left>
                            <label class="ndako-attach__item">
                                <input type="file" wire:model="uploads" multiple accept="image/*,video/*" hidden>
                                <i class="bi bi-image"></i><span>Photos</span>
                            </label>
                            <label class="ndako-attach__item">
                                <input type="file" wire:model="uploads" multiple accept=".pdf,.doc,.docx,application/*" hidden>
                                <i class="bi bi-file-earmark-text"></i><span>Document</span>
                            </label>
                            <label class="ndako-attach__item">
                                <input type="file" wire:model="uploads" accept="image/*" capture="environment" hidden>
                                <i class="bi bi-camera"></i><span>Camera</span>
                            </label>
                            <label class="ndako-attach__item">
                                <input type="file" hidden>
                                <i class="bi bi-mic"></i><span>Audio</span>
                            </label>
                        </div>
                    </template>

                    
                    <button type="button" class="ndako-emoji-btn" title="Emoji" @click="toggleEmoji()">
                        <i class="bi bi-emoji-smile"></i>
                    </button>

                    <template x-if="emojiOpen">
                        <div class="ndako-emoji-pop" x-transition.opacity x-cloak>
                            <div class="ndako-emoji-tabs">
                                <button :class="{active: tab==='smileys'}" @click="tab='smileys'">😊</button>
                                <button :class="{active: tab==='gestures'}" @click="tab='gestures'">👍</button>
                                <button :class="{active: tab==='symbols'}" @click="tab='symbols'">✨</button>
                            </div>
                            <div class="ndako-emoji-grid">
                                <template x-for="emo in currentEmojis" :key="emo">
                                    <button type="button" @click="$wire.messageText += emo"><span x-text="emo"></span></button>
                                </template>
                            </div>
                        </div>
                    </template>

                    
                    <input type="text" class="ndako-chat__input" placeholder="Type a message…"
                           wire:model.live.debounce.250ms="messageText"
                           @input="onInput()"
                           @keydown.enter.stop
                           @focus="focused=true" @blur="focused=false"
                           <?php echo e(!$selectedConversationId ? 'disabled' : ''); ?>>

                    
                    <template x-if="canSend">
                        <button type="submit" class="ndako-chat__send" title="Send"
                                :disabled="!$wire.selectedConversationId"
                                wire:loading.attr="disabled" wire:target="send,uploads">
                            <i class="bi bi-send-fill" x-show="!sending"></i>
                            <span class="ndako-btnspin" wire:loading wire:target="send"></span>
                        </button>
                    </template>
                    <template x-if="!canSend">
                        <button type="button" class="ndako-chat__send" title="Voice note"
                                @mousedown.prevent="startRec()" @mouseup.prevent="stopRec()"
                                @touchstart.prevent="startRec()" @touchend.prevent="stopRec()">
                            <i class="bi bi-mic-fill" x-show="!recording"></i>
                            <span x-show="recording" class="ndako-rec-dot"></span>
                            <span x-show="recording" class="ndako-rec-time" x-text="recTime"></span>
                        </button>
                    </template>
                </form>

                <div wire:loading wire:target="uploads" class="ndako-progress">
                    Uploading… <span class="ndako-bar"></span>
                </div>

                <!--[if BLOCK]><![endif]--><?php if(count($uploads) > 0): ?>
                    <div class="ndako-attach__preview">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $uploads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="ndako-attach__chip"><?php echo e($f->getClientOriginalName()); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </section>
    </div>

    
    <script>
      document.addEventListener('alpine:init', () => {
        // Swipe rail
        Alpine.data('ndakoSwipe', (opts) => ({
          translateX: 0, startX: 0, dragging: false,
          railWidth: 0, opened: false, id: opts.id,
          width: opts.width,
          init(){ this.railWidth = this.width(); window.addEventListener('resize', () => this.railWidth = this.width()); },
          start(e){ const p = e.touches ? e.touches[0] : e; this.dragging = true; this.startX = p.clientX - this.translateX; this.$dispatch('ndako-close-all-swipes'); },
          move(e){ if(!this.dragging) return; const p = e.touches ? e.touches[0] : e; let next = p.clientX - this.startX; next = Math.min(20, Math.max(next, -this.railWidth)); this.translateX = next; },
          end(){ if(!this.dragging) return; this.dragging = false; const t = -this.railWidth * 0.3; if(this.translateX >= 0){ this.close(); } else { this.translateX <= t ? this.open() : this.close(); } },
          cancel(){ if(this.dragging) this.end(); },
          open(){ this.translateX = -this.railWidth; this.opened = true; },
          openRail(){ this.open(); },
          close(){ this.translateX = 0; this.opened = false; },
        }));

        // Context menus + older loader
        Alpine.data('ndakoMenus', ($wire) => ({
          convMenu:{open:false,x:0,y:0},
          msgMenu:{open:false,x:0,y:0,id:null,mine:false},
          pressTimer:null,
          canLoadOlder:true,
          openConvMenu(e){ if (e.target.closest('.ndako-chat__bubble')) return; this.hideAll(); this.convMenu={open:true,x:e.clientX,y:e.clientY}; },
          openMsgMenu(e,id,mine){ this.hideAll(); this.msgMenu={open:true,x:e.clientX,y:e.clientY,id:id,mine:mine}; },
          press(e,id,mine){ this.clearPress(); this.pressTimer=setTimeout(()=>{ const p=e.touches?e.touches[0]:e; this.openMsgMenu(p,id,mine); },450); },
          clearPress(){ if(this.pressTimer){ clearTimeout(this.pressTimer); this.pressTimer=null; } },
          hideAll(){ this.convMenu.open=false; this.msgMenu.open=false; this.msgMenu.id=null; },
          copy(){ navigator.clipboard?.writeText(document.getSelection()?.toString() || ''); this.hideAll(); },
          reply(){ this.hideAll(); /* plug reply UI if needed */ },
          forward(){ this.hideAll(); /* plug forward UI if needed */ },
          remove(){ this.hideAll(); /* plug deleteMessage(msgMenu.id) */ },
          closeConvo(){ this.hideAll(); $wire.closeConversation($wire.selectedConversationId); },
          deleteConvo(){ this.hideAll(); $wire.deleteConversation($wire.selectedConversationId); },
          onBodyScroll(e){
            const el = e.target;
            // enable button when scrolled near top
            this.canLoadOlder = el.scrollTop <= 32;
          },
          scrollToAnchor(){
            // keep context roughly stable after loading older
            const body = document.getElementById('ndako-body');
            body && (body.scrollTop = 60);
          }
        }));

        // Input + emojis + typing + voice UI
        Alpine.data('ndakoInput', ($wire) => ({
          attachOpen:false, emojiOpen:false, tab:'smileys',
          sending:false, typingDebounce:null,
          canSend:false, focused:false,
          recording:false, recSec:0, recTimer:null,
          get recTime(){ const m=String(Math.floor(this.recSec/60)).padStart(2,'0'); const s=String(this.recSec%60).padStart(2,'0'); return `${m}:${s}`; },
          get currentEmojis(){
            const smileys = '😀😃😄😁😆😅😂🤣😊🙂🙃😉😍😘😗😚😙😋😛😜🤪😝🫠🤗🤭🤫🤔🤐🤨😐😑😶🫥😏😒🙄😬😮‍💨🤥😌😴🤤😪😮😯😲😳🥵🥶🥴🤯😕😟🙁☹️'.split('');
            const gestures = '👍👎👏🙌🙏🤝💪👌🤌🤏✌️🤘🤙👋🤚✋🖐️🖖👈👉👆👇☝️✊👊🤛🤜'.split('');
            const symbols = '❤️🧡💛💚💙💜🤎🖤🤍💔❣️💕💞💓💗💖💘💝💟✨⭐🌟🔥🎉✅❌⚠️❗❓💯🕒📌📎'.split('');
            return this.tab==='smileys'?smileys : this.tab==='gestures'?gestures : symbols;
          },
          toggleAttach(){ this.attachOpen = !this.attachOpen; if(this.attachOpen) this.emojiOpen=false; },
          toggleEmoji(){ this.emojiOpen = !this.emojiOpen; if(this.emojiOpen) this.attachOpen=false; },
          hidePopovers(){ this.attachOpen=false; this.emojiOpen=false; },
          onInput(){
            this.canSend = ($wire.messageText || '').trim().length>0;
            clearTimeout(this.typingDebounce);
            this.typingDebounce = setTimeout(()=>{ /* optional $wire.typingPing() */ }, 500);
          },
          // Voice UI (UI only – wire MediaRecorder later)
          startRec(){ if(! $wire.selectedConversationId) return; if(this.recording) return;
            this.recording=true; this.recSec=0; this.recTimer=setInterval(()=>this.recSec++,1000);
          },
          stopRec(){ if(!this.recording) return; this.recording=false; clearInterval(this.recTimer); },
        }));

        Alpine.data('ndakoChat', () => ({ /* for global state if needed */ }));
      })
    </script>

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
.ndako-chat__ghost { background:#fff; border:1px solid #111827; color:#111827; border-radius:8px; padding:6px 10px; cursor:pointer; }
.ndako-chat__tiny { width:26px; height:26px; border-radius:6px; border:1px solid #111827; background:#111827; color:#fff; cursor:pointer; }

.ndako-chat__search { position:relative; padding:8px; border-bottom:1px solid #e5e7eb; }
.ndako-input-wrap { position:relative; }
.ndako-chat__search input { width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:10px; outline:none; }
.ndako-spin { position:absolute; right:10px; top:50%; transform:translateY(-50%); width:16px; height:16px; border:2px solid #d1d5db; border-top-color:#111827; border-radius:50%; animation: ndako-spin .8s linear infinite; }
.ndako-chat__search-pop { position:absolute; left:8px; right:8px; top:48px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 16px 40px rgba(0,0,0,.12); max-height:260px; overflow:auto; z-index:20; }
.ndako-chat__contact { display:flex; align-items:center; gap:10px; width:100%; text-align:left; padding:10px 12px; border:none; background:#fff; cursor:pointer; }
.ndako-chat__contact:hover { background:#f8fafc; }
.ndako-chat__contact-name { font-weight:600; }
.ndako-chat__contact-label { font-size:12px; color:#6b7280; }
.ndako-chip { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:999px; font-size:12px; object-fit:cover; }
.chip-user  { background:#111827; color:#fff; }
.chip-guest { background:#e5e7eb; color:#111827; }

/* Conversation list */
.ndako-chat__convos { overflow-y:auto; padding:0; }

/* Action rail */
.ndako-convo-wrap { position:relative; overflow:hidden; }
.ndako-actions { position:absolute; inset:0 0 0 auto; display:grid; grid-auto-flow:column; align-items:center; justify-items:center; gap:8px; padding:6px; background:#f0f2f5; z-index:0; pointer-events:none; }
.ndako-actions.is-open { pointer-events:auto; }
.ndako-act { border:none; border-radius:12px; cursor:pointer; font-size:18px; color:#fff; display:flex; align-items:center; justify-content:center; width:40px; height:40px; transition:transform .12s ease, opacity .12s ease, background .2s ease; }
.ndako-act:hover { transform:scale(1.05); }
.ndako-act:active { transform:scale(.95); opacity:.9; }
.ndako-act--pin{background:#fbbf24;} .ndako-act--mute{background:#9ca3af;} .ndako-act--read{background:#2563eb;} .ndako-act--close{background:#64748b;} .ndako-act--danger{background:#ef4444;}

/* Row */
.ndako-convo { display:grid; grid-template-columns:56px 1fr 34px; align-items:center; gap:8px; padding:10px 10px; border-bottom:1px solid #f1f5f9; background:#fff; position:relative; will-change:transform; transition:transform .18s cubic-bezier(.2,.8,.2,1); z-index:1; }
.ndako-convo.is-active { background:#f9fafb; }
.ndako-convo:hover { background:#f8fafc; }

/* Avatar block */
.ndako-convo__avatar { position:relative; width:46px; height:46px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; overflow:hidden; border:none; background:#e5e7eb; cursor:pointer; }
.ndako-convo__avatar img { width:100%; height:100%; object-fit:cover; }
.ndako-convo__fallback { font-weight:600; color:#111827; }
.ndako-convo__chip { position:absolute; right:-4px; bottom:-4px; width:18px; height:18px; border-radius:999px; display:flex; align-items:center; justify-content:center; font-size:10px; border:2px solid #fff; }

/* Middle block */
.ndako-convo__main { text-align:left; border:none; background:transparent; cursor:pointer; padding:0; margin:0; }
.ndako-convo__row1 { display:flex; align-items:center; justify-content:space-between; gap:10px; }
.ndako-convo__title { font-weight:600; color:#0f172a; display:flex; align-items:center; gap:6px; }
.ndako-pin { font-size:14px; opacity:.85; }
.ndako-convo__time { font-size:12px; color:#64748b; white-space:nowrap; }
.ndako-convo__row2 { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:2px; }
.ndako-convo__last { font-size:13px; color:#374151; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.ndako-convo__badges { display:flex; align-items:center; gap:8px; }
.ndako-convo__unread { min-width:20px; padding:0 6px; height:20px; line-height:20px; font-size:12px; border-radius:999px; color:#fff; background:#111827; text-align:center; }
.ndako-convo__mute { font-size:14px; color:#94a3b8; }

/* Kebab */
.ndako-convo__kebab { display:none; justify-content:center; align-items:center; }
.ndako-kebab__btn { width:28px; height:28px; border-radius:8px; border:none; background:transparent; cursor:pointer; color:#475569; }
.ndako-kebab__btn:hover { background:#eef2f7; }
@media (hover:hover) and (pointer:fine) { .ndako-convo__kebab.kebab-inline { display:flex; } }

/* Header + title */
.ndako-chat__header { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-bottom:1px solid #e5e7eb; background:#f9fafb; }
.ndako-chat__left { display:flex; align-items:center; gap:10px; }
.ndako-chat__back { display:none; background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:6px; cursor:pointer; }
.ndako-chat__title--rich { display:flex; align-items:center; gap:10px; }
.ndako-title-avatar { width:32px; height:32px; border-radius:50%; background:#e5e7eb; display:inline-flex; align-items:center; justify-content:center; font-weight:600; object-fit:cover; }
.ndako-title-fallback { color:#111827; }
.ndako-title-texts { display:flex; flex-direction:column; line-height:1.1; }
.ndako-title-name { font-weight:600; color:#111827; }
.ndako-title-sub { font-size:12px; color:#6b7280; }
.ndako-actions-header { display:flex; gap:8px; align-items:center; }
.ndako-ghost-icon { border:1px solid #e5e7eb; background:#fff; color:#0f172a; border-radius:10px; padding:6px 8px; cursor:pointer; }
.ndako-ghost-icon:hover { background:#f3f4f6; }
.ndako-chat__close { background:transparent; border:none; color:#6b7280; cursor:pointer; padding:6px; border-radius:8px; }
.ndako-chat__close:hover { background:#f3f4f6; color:#111827; }

/* Empty */
.ndako-thread-empty { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; gap:10px; background:#fff; }
.ndako-empty-logo { width:10em; height:auto; opacity:.9; }
.ndako-empty-title { font-weight:700; color:#0f172a; font-size:18px; }
.ndako-empty-sub { color:#64748b; font-size:13px; }

/* Body wallpaper */
.ndako-chat__body { flex:1; padding:12px 12px 4px; overflow:auto;
  background:
    radial-gradient(circle at 25% 20%, rgba(255,255,255,0.9) 0 40px, transparent 41px),
    radial-gradient(circle at 75% 0%, rgba(255,255,255,0.85) 0 60px, transparent 61px),
    linear-gradient(180deg, #e8f0e8, #e4efe8);
  scroll-behavior:smooth;
}
.ndako-load-older { display:flex; align-items:center; justify-content:center; gap:6px; margin:6px 0 8px; }
.ndako-load-older i { font-size:14px; }
.ndako-load-older { color:#475569; font-size:12px; cursor:pointer; }

/* Timeline */
.ndako-timeline { display:flex; justify-content:center; margin:10px 0 14px; }
.ndako-timeline span { background:#f1f5f9; color:#475569; font-size:12px; padding:6px 10px; border-radius:999px; border:1px solid #e2e8f0; }

/* Bubbles */
.ndako-chat__msg { display:flex; margin:6px 0; }
.ndako-chat__msg.is-user { justify-content:flex-end; }
.ndako-chat__bubble { max-width:78%; position:relative; padding:6px 8px 18px; border-radius:10px; box-shadow:0 1px 0 rgba(0,0,0,.06); }
.ndako-chat__bubble.b-peer { background:#ffffff; border-top-left-radius:4px; }
.ndako-chat__bubble.b-user { background:#dcf8c6; border-top-right-radius:4px; }
.ndako-chat__msg.is-agent .ndako-chat__bubble::after { content:""; position:absolute; left:-6px; top:8px; width:0; height:0; border-top:6px solid transparent; border-bottom:6px solid transparent; border-right:6px solid #ffffff; }
.ndako-chat__msg.is-user .ndako-chat__bubble::after { content:""; position:absolute; right:-6px; top:8px; width:0; height:0; border-top:6px solid transparent; border-bottom:6px solid transparent; border-left:6px solid #dcf8c6; }
.ndako-chat__text { white-space:pre-wrap; word-wrap:break-word; color:#111827; }
.ndako-chat__files { margin-top:4px; display:flex; gap:6px; flex-wrap:wrap; }
.ndako-chat__file { font-size:12px; text-decoration:underline; color:#0f172a; }
.ndako-chat__time { position:absolute; bottom:4px; right:6px; font-size:11px; opacity:.6; }

/* Menus */
.ndako-msgmenu { position:fixed; background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 18px 40px rgba(15,23,42,.12); padding:6px; z-index:99999; min-width:180px; }
.ndako-msgmenu button { width:100%; text-align:left; border:none; background:#fff; padding:8px 10px; border-radius:8px; font-size:14px; cursor:pointer; display:flex; align-items:center; gap:8px; }
.ndako-msgmenu button:hover { background:#f8fafc; }
.ndako-msgmenu .danger { color:#b91c1c; }

/* Input, attach, emoji */
.ndako-chat__inputbar { display:flex; align-items:center; gap:8px; padding:10px; border-top:1px solid #e5e7eb; background:#f9fafb; position:relative; }
.ndako-chat__clip, .ndako-emoji-btn { border:1px solid #e5e7eb; background:#fff; cursor:pointer; padding:6px; border-radius:10px; }
.ndako-chat__clip:hover, .ndako-emoji-btn:hover { background:#f3f4f6; }
.ndako-attach { position:absolute; bottom:52px; left:10px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:10px; box-shadow:0 16px 40px rgba(0,0,0,.12); z-index:10; }
.ndako-attach--grid { display:grid; grid-template-columns: repeat(2, minmax(120px,1fr)); gap:10px; }
.ndako-attach__item { display:flex; align-items:center; gap:8px; border:1px dashed #cbd5e1; border-radius:10px; padding:8px 10px; font-size:13px; color:#111827; cursor:pointer; background:#fff; transition: background .15s; }
.ndako-attach__item:hover { background:#f8fafc; }
.ndako-attach__item i { font-size:18px; }
.ndako-emoji-pop { position:absolute; bottom:52px; left:56px; background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:8px; box-shadow:0 16px 40px rgba(0,0,0,.12); z-index:10; width:max-content; }
.ndako-emoji-tabs { display:flex; gap:6px; margin-bottom:6px; }
.ndako-emoji-tabs button { border:1px solid #e5e7eb; background:#fff; border-radius:8px; padding:4px 6px; cursor:pointer; }
.ndako-emoji-tabs button.active { background:#f3f4f6; }
.ndako-emoji-grid { display:grid; grid-template-columns: repeat(10, 24px); gap:6px; max-width: 280px; max-height: 180px; overflow:auto; }
.ndako-emoji-grid button { width:24px; height:24px; line-height:24px; border:none; background:#fff; border-radius:6px; cursor:pointer; }
.ndako-emoji-grid button:hover { background:#f3f4f6; }

.ndako-rec-dot { display:inline-block; width:8px; height:8px; border-radius:999px; background:#ef4444; margin-right:6px; animation:pulse 1s infinite; }
.ndako-rec-time { font-size:12px; }
@keyframes pulse { 0%,100%{opacity:.4} 50%{opacity:1} }

.ndako-attach__preview { padding:6px 10px; background:#fff; border-top:1px solid #e5e7eb; display:flex; gap:6px; flex-wrap:wrap; }
.ndako-attach__chip { font-size:12px; background:#f3f4f6; padding:4px 8px; border-radius:999px; }

.ndako-chat__input { flex:1; padding:10px 12px; border-radius:10px; border:1px solid #e5e7eb; outline:none; font-size:14px; background:#fff; }
.ndako-chat__input:focus { border-color:#111827; box-shadow:0 0 0 3px rgba(17,24,39,.08); }
.ndako-chat__send { padding:0 12px; border-radius:10px; border:1px solid #111827; background:#111827; color:#fff; cursor:pointer; display:flex; align-items:center; gap:6px; min-height:36px; }
.ndako-chat__send i { font-size:16px; }
.ndako-btnspin { display:inline-block; width:16px; height:16px; border:2px solid #d1d5db; border-top-color:#fff; border-radius:50%; animation: ndako-spin .8s linear infinite; margin-left:2px; }

/* Upload progress */
.ndako-progress { display:flex; align-items:center; gap:10px; padding:8px 12px; font-size:13px; color:#111827; background:#fff; border-top:1px solid #e5e7eb; }
.ndako-bar { flex:1; height:4px; border-radius:999px; background:linear-gradient(90deg,#111827 0%, #9ca3af 50%, #111827 100%); background-size:200% 100%; animation: ndako-shimmer 1.2s infinite; opacity:.5; }

/* Responsive */
@media (max-width: 900px) {
  .ndako-chat__panel { width: calc(100vw - 20px); right: 10px; height: 70vh; bottom: 82px; }
  .ndako-chat { right: 10px; bottom: 10px; }
  .ndako-chat__sidebar { width: 100%; }
  .ndako-chat__thread { width: 100%; }
  .ndako-chat__back { display:inline-flex; }
}
@media (max-width: 640px) {
  .ndako-chat__panel { height: 78vh; }
  .ndako-chat__sidebar { display: <?php echo e($selectedConversationId ? 'none' : 'flex'); ?>; }
  .ndako-chat__thread { display: <?php echo e($selectedConversationId ? 'flex' : 'none'); ?>; }
}
@media (max-width: 460px) {
  .ndako-convo__title { font-size: 14px; }
  .ndako-convo__last  { font-size: 12px; }
  .ndako-convo__time  { font-size: 11px; }
  .ndako-act { min-width: 36px; }
}
@keyframes ndako-spin { to { transform: rotate(360deg);} }
    </style>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/chat-widget.blade.php ENDPATH**/ ?>