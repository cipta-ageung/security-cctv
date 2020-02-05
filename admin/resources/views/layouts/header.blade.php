<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
        <a href="{{ route('home') }}" class="big-logo">
            <img src="{{ asset('img/logoresponsive2.png') }}" alt="logo img" class="logo-img">
        </a>
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('img/logoheader2.png') }}" alt="navbar brand" class="navbar-brand">
        </a>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="la la-bars"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg" data-background-color="dark">
        <div class="container-fluid">
            <div class="navbar-minimize">
                <button class="btn btn-minimize btn-rounded">
                    <i class="la la-navicon"></i>
                </button>
            </div>
            <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                
                <li class="nav-item dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false"> <img src="{{ asset('admin/images/').'/'.Auth::user()->avatar }}" alt="image profile" width="36" class="img-circle"></a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <li>
                            <div class="user-box">
                                <div class="u-img"><img src="{{ asset('admin/images/').'/'.Auth::user()->avatar }}" alt="image profile"></div>
                                <div class="u-text">
                                    <h4>{{ Auth::user()->name }}</h4>
                                    <p class="text-muted">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('admin.index') }}">Kelola Admin</a>
                            <a class="dropdown-item" href="{{ route('profil.index') }}">My Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                        </li>
                    </ul>
                </li>   
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
</div>
