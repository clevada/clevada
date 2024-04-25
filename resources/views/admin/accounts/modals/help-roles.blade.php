<div class="modal fade custom-modal" tabindex="-1" aria-labelledby="rolesLabel" aria-hidden="true" id="help-roles">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">


            <div class="modal-header">
                <h5 class="modal-title" id="rolesLabel">{{ __('Accounts roles help') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="fw-bold mb-1">{{ __('Administrator') }}</div>
                <i class="bi bi-dot"></i>
                {{ __('Administrators have full access to all areas (website content, setings, manage accounts, template...). Only administrators can create administrator or manager accounts.') }}
                <br>
                <i class="bi bi-dot"></i> {{ __('The first administrator is the website owner. Only the owner can manage the subsciption. Only the owner can delete other admin accounts.') }}

                <div class="fw-bold mb-1 mt-3">{{ __('Manager') }}</div>
                <i class="bi bi-dot"></i>
                {{ __('Managers have full access (create / update / delete) to all site content (manage any post from any author, manage categories, manage likes and comments, manage polls, static pages, contact messages and ads).') }}
                <br>
                <i class="bi bi-dot"></i>
                {{ __("Managers do not have access to manage administrator or other manager accounts. Managers have access to the Recycle Bin and can permanently delete or recover items from the Recycle Bin.") }}
                <br>
                <i class="bi bi-dot"></i> {{ __('Managers can create accounts for editors, authors and contributors.') }}'

                <div class="fw-bold mb-1 mt-3">{{ __('Developer') }}</div>
                <i class="bi bi-dot"></i>
                {{ __('Developers have access to manage the website template. This is useful when the administrator does not have the knowledge to work with the template builder and wants to transfer access to an external developer to create the site template.') }}

                <div class="fw-bold mb-1 mt-3">{{ __('Editor') }}</div>
                <i class="bi bi-dot"></i> {{ __('Editors have full access (create / update / delete) to all posts from any author.') }}

                <div class="fw-bold mb-1 mt-3">{{ __('Author') }}</div>
                <i class="bi bi-dot"></i> {{ __('Authors can create posts (articles) that are published automatically.') }}

                <div class="fw-bold mb-1 mt-3">{{ __('Contributor') }}</div>
                <i class="bi bi-dot"></i> {{ __('Authors can create posts (articles), but these articles must be approved by an editor, manager or administrator.') }}

                <div class="fw-bold mb-1 mt-3">{{ __('Registered user') }}</div>
                <i class="bi bi-dot"></i> {{ __('Registered users can add comments to website articles.') }}
            </div>

            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-primary">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
