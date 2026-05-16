<div class="sidebar__area">
    <div class="sidebar__close">
        <button class="close-btn">
            <i class="fa fa-close"></i>
        </button>
    </div>
    <div class="sidebar__brand">
        <a href="assets/dashboard">
            <img src="assets/images/logo/" alt="icon">
        </a>
    </div>
    <ul id="sidebar-menu" class="sidebar__menu">
        @if(isSuperAdmin())
        <li class="mm-active">
            <a href="{{ route('super_admin.dashboard')}}">
                <img src="assets/images/icons/sidebar/dashboard.svg" alt="icon">
                <span>Dashboard</span>
            </a>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-user"></i>
                <span>Admin Manage</span>
            </a>
            <ul>
                <li class="">
                    <a href="{{ route('super_admin.admins.list') }}">
                        <i class="fa fa-circle"></i>
                        <span>Admin List</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('super_admin.list')}}">
                        <i class="fa fa-circle"></i>
                        <span>Super Admin Lists</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('super_admin.create.super_admin')}}">
                        <i class="fa fa-circle"></i>
                        <span>Create Super Admin</span>
                    </a>
                </li>

            </ul>
        </li>
        @endif
        

        @if(isAdmin())
        <li class="">
            <a href="{{ route('admin.dashboard')}}">
                <img src="assets/images/icons/sidebar/dashboard.svg" alt="icon">
                <span>Dashboard</span>
            </a>
        </li>
        <li class="">
            <a href="{{ route('admin.profile.show') }}">
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </a>
        </li>
        <li class="">
            <a href="{{ route('admin.profile.edit')}}">
                <i class="fa-solid fa-user"></i>
                <span>Edit Profile</span>
            </a>
        </li>
        <li class="">
            <a class="has-arrow" href="#">
                <i class="fas fa-user"></i>
                <span>Manager</span>
            </a>
            <ul>
                <li class="">
                    <a href="">
                        <i class="fa fa-circle"></i>
                        <span>Manager List</span>
                    </a>
                </li>
                <li class="">
                    <a href="">
                        <i class="fa fa-circle"></i>
                        <span>Create Manager</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="">
            <a href="{{ route('admin.building.index')}}">
                <i class="fa-solid fa-building"></i>
                <span>Buildings</span>
            </a>
        </li>
        @endif
    </ul>
</div>