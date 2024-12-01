@extends('settings::layouts.master')

@section('content')
<!-- Users -->
<div id="users-block" class="setting_block">
    <h2>Users</h2>
    <div class="row k_settings_container">

        <!-- Invite Users -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="users">
            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <form>
                    <div>
                        <p class="k_form_label">
                            <span class="ml-2">Invite Users</span>
                        </p>
                        <div class="d-flex">
                            <input type="text" class="k-input k_user_emails text-truncate" style="width: auto;" placeholder="Entrez l'adresse e-mail">
                            <button type="submit" class="flex-shrink-0 btn btn-primary k_web_settings_invite">
                                <strong wire:loading.remove>Inviter</strong>
                            </button>
                        </div>
                    </div>
                    <div class="mt16">
                        <p class="k_form_label">
                            Pending Invites :
                        </p>
                        <div class="d-block">
                            <a class="cursor-pointer badge rounded-pill k_web_settings_users">
                                usertest5@gmail.com
                                <i wire:click.prevent="deleteInvitation()" wire:confirm="Êtes-vous sûr de vouloir annuler l'invitation de laud@gmail.com ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Annuler l'invitation de laud@gmail.com"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Invite Users End -->
        
        <!-- Active Users -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <i class="inline-block bi bi-people"></i>
                        <span class="ml-2">4 Active Users</span>
                        <a href="#" target="__blank" title="documentation" class="k_doc_link">
                            <i class="bi bi-question-circle-fill"></i>
                        </a>
                    </div>
                    
                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-arrow-right k_button_icon"></i> <span>Manage Users</span>
                        </a>
                    </div>
                    
                </div>
            </div>

        </div>
        <!-- Active Users End -->

    </div>
</div>
<!-- Users End -->

<!-- Languages -->
<div id="users-block" class="setting_block">
    <h2>Languages</h2>
    <div class="row k_settings_container">
        
        <!-- Add Language -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <i class="inline-block bi bi-translate"></i>
                        <span class="ml-2">2 Language(s)</span>
                        <a href="#" target="__blank" title="documentation" class="k_doc_link">
                            <i class="bi bi-question-circle-fill"></i>
                        </a>
                    </div>
                    
                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-plus-circle-fill k_button_icon"></i> <span>Add a Language</span>
                        </a>
                    </div>
                    
                </div>
            </div>

        </div>
        <!-- Add Language End -->

    </div>
</div>
<!-- Languages End -->

<!-- Enterprises -->
<div id="users-block" class="setting_block">
    <h2>Enterprises</h2>
    <div class="row k_settings_container">
        
        <!-- Enterprise Info -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <i class="inline-block bi bi-building"></i>
                        <span class="ml-2">Novotel Nairobi Westland</span>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Republic of Kenya
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-arrow-right k_button_icon"></i> <span>Update Informations</span>
                        </a>
                    </div>
                    <!-- Box Actions -->
                    
                </div>
            </div>

        </div>
        <!-- Enterprise Info End -->
        
        <!-- Document Layouts -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <i class="inline-block bi bi-files"></i>
                        <span class="ml-2">Document Layout</span>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Choose the layout of your documents
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-arrow-right k_button_icon"></i> <span>Configure</span>
                        </a>
                    </div>
                    <!-- Box Actions -->
                    
                </div>
            </div>

        </div>
        <!-- Document Layouts End -->
        
        <!-- Email Templates -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <i class="inline-block bi bi-envelope"></i>
                        <span class="ml-2">E-mail Templates</span>
                        <a href="#" target="__blank" title="documentation" class="k_doc_link">
                            <i class="bi bi-question-circle-fill"></i>
                        </a>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Customize the look and feel of automated emails
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-arrow-right k_button_icon"></i> <span>Configure</span>
                        </a>
                    </div>
                    <!-- Box Actions -->
                    
                </div>
            </div>

        </div>
        <!-- Email Templates End -->

    </div>
</div>
<!-- Enterprises End -->

<!-- Permissions -->
<div id="users-block" class="setting_block">
    <h2>Permissions</h2>
    <div class="row k_settings_container">
        
        <!-- Guest Portal -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <span class="ml-2">Guests Portal</span>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Let your guests log in to access their booking details and invoices.
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-arrow-right k_button_icon"></i> <span>Default Access Rights</span>
                        </a>
                    </div>
                    
                </div>
            </div>

        </div>
        <!-- Guest Portal End -->
        
        <!-- Access Rights -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Left pane -->
            <div class="k_setting_left_pane">
                <div class="k_field_widget k_field_boolean">
                    <div class="k-checkbox form-check d-inline-block">
                        <input type="checkbox" wire:model.live="" class="form-check-input" onclick="checkStatus(this)">
                    </div>
                </div>
            </div>
            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <span class="ml-2">Default Access Rights</span>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Define custom access rights for new team members.
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-arrow-right k_button_icon"></i> <span>Default Access Rights</span>
                        </a>
                    </div>
                    
                </div>
            </div>

        </div>
        <!-- Access Rights End -->
        
        <!-- Import/Export -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Left pane -->
            <div class="k_setting_left_pane">
                <div class="k_field_widget k_field_boolean">
                    <div class="k-checkbox form-check d-inline-block">
                        <input type="checkbox" wire:model.live="" class="form-check-input" onclick="checkStatus(this)">
                    </div>
                </div>
            </div>
            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <span class="ml-2">Import / Export</span>
                        <a href="#" target="__blank" title="documentation" class="k_doc_link">
                            <i class="bi bi-question-circle-fill"></i>
                        </a>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Allow users to import data from CSV/XLS/XLSX files
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                    
                </div>
            </div>

        </div>
        <!-- Import/Export End -->

    </div>
</div>
<!-- Permissions End -->

<!-- Contacts -->
<div id="users-block" class="setting_block">
    <h2>Contacts</h2>
    <div class="row k_settings_container">
        
        <!-- SmS Sending -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <span class="ml-2">SmS Sending</span>
                        <a href="#" target="__blank" title="documentation" class="k_doc_link">
                            <i class="bi bi-question-circle-fill"></i>
                        </a>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Send messages directly to your guests.
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-arrow-right k_button_icon"></i> <span>Buy Kredit</span>
                        </a>
                    </div>
                    
                </div>
            </div>

        </div>
        <!-- SmS Sending End -->
        
        <!-- Koverae IAP -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <span class="ml-2">Koverae IAP</span>
                        <a href="#" target="__blank" title="documentation" class="k_doc_link">
                            <i class="bi bi-question-circle-fill"></i>
                        </a>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Allow users to import data from CSV/XLS/XLSX files
                        </span>
                    </div>
                    <!-- Box Description -->

                    <!-- Box Actions -->
                    <div class="mt-2 d-block">
                        <a class="outline-none btn btn-link k_web_settings_access_rights">
                            <i class="bi bi-arrow-right k_button_icon"></i> <span>View My Kover Services</span>
                        </a>
                    </div>
                    
                    
                </div>
            </div>

        </div>
        <!-- Koverae IAP End -->

    </div>
</div>
<!-- Contacts End -->

<!-- Integrations -->
<div id="users-block" class="setting_block">
    <h2>Integrations</h2>
    <div class="row k_settings_container">
        
        <!-- Geolocation -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Left pane -->
            <div class="k_setting_left_pane">
                <div class="k_field_widget k_field_boolean">
                    <div class="k-checkbox form-check d-inline-block">
                        <input type="checkbox" wire:model.live="" class="form-check-input" onclick="checkStatus(this)">
                    </div>
                </div>
            </div>
            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <span class="ml-2">Geolocation</span>
                        <a href="#" target="__blank" title="documentation" class="k_doc_link">
                            <i class="bi bi-question-circle-fill"></i>
                        </a>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Geolocate your partners and customers.
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                    <!-- Box Input -->
                    <div class="mt-3 ps-3">
                        <select id="geolocation" class="k-input">
                            <option value=""></option>
                            <option selected value="">Open Street Map</option>
                            <option value="">Google Place Map</option>
                        </select>
                    </div>
                    <!-- Box Input End -->
                    
                </div>
            </div>

        </div>
        <!-- Geolocation End -->
        
        <!-- Koverae IAP -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="">

            <!-- Left pane -->
            <div class="k_setting_left_pane">
                <div class="k_field_widget k_field_boolean">
                    <div class="k-checkbox form-check d-inline-block">
                        <input type="checkbox" wire:model.live="" class="form-check-input" onclick="checkStatus(this)">
                    </div>
                </div>
            </div>
            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                        <span class="ml-2">reCAPTCHA</span>
                        <a href="#" target="__blank" title="documentation" class="k_doc_link">
                            <i class="bi bi-question-circle-fill"></i>
                        </a>
                    </div>
                    
                    <!-- Box Description -->
                    <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                        <span>
                            Protect your forms from spam and abuse.
                        </span>
                    </div>
                    <!-- Box Description -->
                    
                </div>
            </div>

        </div>
        <!-- Koverae IAP End -->

    </div>
</div>
<!-- Integrations End -->

<!-- Developers -->
<div id="users-block" class="setting_block">
    <h2>Developers</h2>
    <div class="row k_settings_container">
        
        <!-- Developer -->
        <div class="k_settings_box col-12 col-lg-6 k_searchable_setting">

            <!-- Right pane -->
            <div class="k_setting_right_pane">
                <div class="mt12">
                    <a href="https://ndako.koverae.com/docs" target="_blank" class="cursor-pointer d-block">
                        View the documentation
                    </a>
                </div>
            </div>

        </div>
        <!-- Developer End -->
        
    </div>
</div>
<!-- Developers End -->

<!-- Ndako -->
<div id="users-block" class="setting_block">
    <h2>Ndako</h2>
    <div class="row k_settings_container">
        
        <!-- About -->
        <div class="k_settings_box col-12 col-lg-12 k_searchable_setting" style="width: 100%;">

            <!-- Right pane -->
            <div class="k_setting_right_pane" style="width: 100%;">
                <div class="mt-1" style="width: 100%;">
                    <span>Ndako SaaS ~ v1.0 (Enterprise Edition)</span>
                    <br>
                    <span>Copyright © 2024</span>
                    <a href="https://ndako.koverae.com" target="_blank" class="cursor-pointer">
                        Koverae Ltd,
                    </a>
                    <a href="http://ndako.koverae.com/privacy-policies#legal" target="_blank" class="cursor-pointer">
                        Ndako Enterprise Edition License v1.0
                    </a>
                </div>
            </div>

        </div>
        <!-- About End -->
        
    </div>
</div>
<!-- Ndako End -->
@endsection
