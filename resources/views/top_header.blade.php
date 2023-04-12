<div class="nav-header">
    <a href="/app" class="brand-logo">
        <img class="" src="{{asset('web/images/logo.png')}}" id="logo" style="width: 60%;" alt>
        <img class="logo-compact" src="images/logo-text.png" alt>
        <img class="brand-title" src="images/logo-text.png" alt>

    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>

<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div class="dashboard_bar">
                        Dashboard 
                    </div>
                </div>

                {{-- <div class="header-center">
                    <a href="/app" class="brand-logo">
                        <img class="" src="{{asset('web/images/logo.png')}}" id="logo" style="width: 48px" alt>
                        <img class="logo-compact" src="images/logo-text.png" alt>
                        <img class="brand-title" src="images/logo-text.png" alt>
                
                    </a>
                </div> --}}

                <ul class="navbar-nav header-right">
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <div class="header-info">
                                <span class="text-black">Hello, <strong>{{ Session()->get('member')['fname']
                                        }}</strong></span>
                                <p class="fs-12 mb-0">{{ Session()->get('member')['username'] }}</p>
                            </div>
                            <img src="{{ Session()->get('member')['profile_image'] }}" width="20" alt>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="/app/profile" class="dropdown-item ai-icon">
                                <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
                                    height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <span class="ml-2">Profile </span>
                            </a>

                            <a href="/signout" class="dropdown-item ai-icon">
                                <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18"
                                    height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" y1="12" x2="9" y2="12" />
                                </svg>
                                <span class="ml-2">Logout </span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>