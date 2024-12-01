@props([
    'value',

])

<!-- Box -->

<div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="users">
    <!-- Right pane -->
    <div class="k_setting_right_pane">
        <form wire:submit.prevent="sendInvitation">
            @csrf
            <div>
                <p class="k_form_label">
                    @if($value->icon)
                        <i class="inline-block bi {{ $value->icon }}"></i>
                    @endif
                    <span class="ml-2">{{ $value->label }}</span>
                    @if($value->help)
                    <a href="{{ $value->help }}" target="__blank" title="documentation" class="k_doc_link">
                        <i class="bi bi-question-circle-fill"></i>
                    </a>
                    @endif
                </p>
                <div class="d-flex">
                    <input type="email" wire:model="friend_email" class="k-input k_user_emails text-truncate" style="width: auto;" placeholder="Enter e-mail address">
                    <span wire:click="sendInvitation" class="flex-shrink-0 btn btn-primary k_web_settings_invite">
                        <strong wire:loading.remove>Invite</strong>
                        <span wire:loading wire:target="sendInvitation">...</span>
                    </span>
                    @error('friend_email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mt16">
                <p class="k_form_label">
                    Pending Invites :
                </p>
                <div class="d-block">
                    @forelse($this->pending_invitations as $invitation)
                    <a class="cursor-pointer badge rounded-pill k_web_settings_users">
                        {{ $invitation->email }}
                        <i wire:click.prevent="deleteInvitation({{ $invitation->id }})" wire:confirm="Are you sure you want to cancel the invitation?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Cancel the invitation."></i>
                    </a>
                    @empty
                        <span>No pending invitations.</span>
                    @endforelse
                    <div wire:loading>
                        ......
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
