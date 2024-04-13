<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="">OEE</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="">OEE</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>

            {{-- <li class="{{ Request::is('admin') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin') }}" id="route-admin"><i class="fas fa-home"></i>
                    <span>Dashboard</span></a>
            </li> --}}

            <li class="{{ request()->path() === 'dashboard/forum' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.forum.index') }}"><i class="fa-solid fa-table"></i></i>
                    <span>Forum</span></a>
            </li>

    </aside>
</div>
