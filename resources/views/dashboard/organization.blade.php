<div class="with-sidebar with-sidebar:2/3">
    <x-quick-links>
        @can('view', $memberable)
            <li>
                <a
                    href="{{ $memberable->checkStatus('draft') && $user->can('edit', $memberable) ? localized_route('organizations.edit', $memberable) : localized_route('organizations.show', $memberable) }}">{{ __('My organization’s page') }}</a>
            </li>
        @endcan
        @can('viewAny', App\Models\Project::class)
            @if ($memberable->isConnector() || $memberable->isConsultant())
                <li>
                    <a href="{{ localized_route('projects.my-projects') }}">{{ __('Projects I’m contracted for') }}</a>
                </li>
            @endif
            @if ($memberable->isParticipant())
                <li>
                    <a
                        href="{{ !$memberable->isConnector() && !$memberable->isConsultant() ? localized_route('projects.my-projects') : localized_route('projects.my-participating-projects') }}">{{ __('Projects I’m participating in') }}</a>
                </li>
            @endif
            <li>
                <a href="{{ localized_route('projects.my-running-projects') }}">{{ __('Projects I’m running') }}</a>
            </li>
        @endcan
    </x-quick-links>
    <div class="border-divider mt-14 mb-12 border-x-0 border-t-3 border-b-0 border-solid pt-6">
        @include('dashboard.partials.notifications', [
            'notifications' => $user->allUnreadNotifications(),
        ])
    </div>
</div>
