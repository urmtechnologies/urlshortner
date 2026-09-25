@php $role = auth()->user()->role; @endphp
<div class="sidebar" id="sidebar">
    <div class="sidebar-logo bg-white">
        <div><a href="{{ route($role . '.dashboard') }}" class="logo logo-normal"><img width="150"
                    src="{{ asset('assets/img/logo.png') }}" alt="Sembark"></a><a href="{{ route($role . '.dashboard') }}"
                class="logo-small"><img src="{{ asset('assets/img/logo.png') }}" alt="Sembark"></a><a
                href="{{ route($role . '.dashboard') }}" class="dark-logo"><img src="{{ asset('assets/img/logo.png') }}"
                    alt="Sembark"></a></div><button type="button" class="sidebar-close" aria-label="Close menu"><i
                class="ti ti-x"></i></button>
    </div>
    <div class="sidebar-inner" data-simplebar>
        <div id="sidebar-menu" class="sidebar-menu">
            <ul role="menu">
                <li class="menu-title"><span>MANAGE</span></li>
                <li><a href="{{ route($role . '.dashboard') }}"
                        class="{{ request()->routeIs($role . '.dashboard') ? 'active' : '' }}"><i
                            class="ti ti-layout-dashboard"></i><span>Dashboard</span></a></li>
                @if ($role === 'superadmin')
                    <li><a href="{{ route('superadmin.clients.index') }}"
                            class="{{ request()->routeIs('superadmin.clients.*') ? 'active' : '' }}"><i
                                class="ti ti-building"></i><span>Clients</span></a></li>
                    <li><a href="{{ route('superadmin.invite.index') }}"
                            class="{{ request()->routeIs('superadmin.invite.*') ? 'active' : '' }}"><i
                                class="ti ti-user-plus"></i><span>Invite Client Admin</span></a></li>
                @elseif($role === 'admin')
                    <li><a href="{{ route('admin.members.index') }}"
                            class="{{ request()->routeIs('admin.members.*') ? 'active' : '' }}"><i
                                class="ti ti-users"></i><span>Team</span></a></li>
                    <li><a href="{{ route('admin.invite.index') }}"
                            class="{{ request()->routeIs('admin.invite.*') ? 'active' : '' }}"><i
                                class="ti ti-user-plus"></i><span>Invite Team</span></a></li>
                @endif
                <li><a href="{{ route($role . '.short-urls.index') }}"
                        class="{{ request()->routeIs($role . '.short-urls.*') ? 'active' : '' }}"><i
                            class="ti ti-link"></i><span>Short URLs</span></a></li>
                @if ($role !== 'superadmin')
                    <li><a href="{{ route($role . '.short-urls.create') }}"><i class="ti ti-plus"></i><span>Generate
                                URL</span></a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
