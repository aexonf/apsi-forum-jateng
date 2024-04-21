<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="">Apsi Forum</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="">Apsi Forum</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            <li class="{{ Request::is('admin.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}" id="route-admin"><i class="fas fa-home"></i>
                    <span>Dashboard</span></a>
            </li>


            @auth

                @if (auth()->user()->role == 'SUPERADMIN')
                    <li class="{{ request()->path() === 'dashboard/supervisor' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.supervisor.index') }}"><i
                                class="fa-solid fa-person-circle-plus"></i></i>
                            <span>Pengawas Management</span></a>
                    </li>

                    <li class="{{ request()->path() === 'dashboard/admin-management' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.admin-management.index') }}"><i
                                class="fa-solid fa-person-circle-plus"></i></i>
                            <span>Admin Management</span></a>
                    </li>

                    <li class="{{ request()->path() === 'dashboard/publication' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.publication.index') }}"><i
                                class="fa-solid fa-file"></i></i>
                            <span>Publication</span></a>
                    </li>
                @elseif (auth()->user()->role == 'ADMIN')
                    <li class="{{ request()->path() === 'dashboard/forum' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.forum.index') }}"><i class="fa-solid fa-table"></i></i>
                            <span>Forum</span></a>
                    </li>
                @endif
            @endauth

    </aside>
</div>
