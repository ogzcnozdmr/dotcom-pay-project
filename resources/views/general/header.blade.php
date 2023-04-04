<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <a href="{{ route('home.start') }}" class="logo">
            <span class="logo-light">
                <img src="{{ asset('assets/images/logo/light.png') }}" alt="" height="60">
            </span>
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo/light.png') }}" alt="" height="20">
            </span>
        </a>
    </div>

    <nav class="navbar-custom">
        <ul class="navbar-right list-inline float-right mb-0">
            <!-- full screen -->
            <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                    <i class="mdi mdi-arrow-expand-all noti-icon"></i>
                </a>
            </li>
            <!-- notification -->
            @if(session()->get('users')['authority'] === "admin")
                <li class="dropdown notification-list list-inline-item">
                    <a id="notification_approve" class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="mdi mdi-bell-outline noti-icon"></i>
                        @if(count($__global['notifications']) !== 0)
                            <span class="badge badge-pill badge-danger noti-icon-badge">
                                {{ count($__global['notifications']) > 9 ? "9+" : count($__global['notifications']) }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg px-1">
                        <h6 class="dropdown-item-text">
                            Bildirimler
                        </h6>
                        <div class="slimscroll notification-item-list">
                            @foreach($__global['notifications'] as $value)
                                <a href="javascript:void(0);" class="dropdown-item notify-item {{ $value['notifications_read'] == '0' ? 'active' : '' }}">
                                    <div class="notify-icon {{ $value['notifications_result'] == '0' ? 'bg-danger' : 'bg-success' }}">
                                        <i class="{{ $value['notifications_icon'] }}"></i>
                                    </div>
                                    <p class="notify-details"><b>{{ $value['notifications_title'] }}</b>
                                        <span class="text-muted">{{ $value['notifications_content'] }}({{ date_translate($value['notifications_date'], 1) }})</span>
                                    </p>
                                </a>
                            @endforeach
                        </div>
                        @if(count($__global['notifications']) == 0)
                            <p class="dropdown-item text-center notify-all text-primary"> Bildirim yok.</p>
                        @endif
                    </div>
                </li>
            @endif
            <li class="dropdown notification-list list-inline-item">
                <div class="dropdown notification-list nav-pro-img">
                    <a class="dropdown-toggle nav-link arrow-none nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('assets/images/users/user-4.jpg') }}" alt="user" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <a class="dropdown-item" href="{{ route('profile.start') }}">
                            <i class="mdi mdi-account-circle"></i> Profile
                        </a>
                        <a class="dropdown-item text-danger" href="{{ route('logout.start') }}">
                            <i class="mdi mdi-power text-danger"></i> Logout
                        </a>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>
        </ul>
    </nav>
</div>

<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu" id="side-menu">
                @if(in_array(1, $__global['authorization_array']) || in_array(2, $__global['authorization_array']))
                    <li class="menu-title">Menu</li>
                @endif

                @if(in_array(2, $__global['authorization_array']))
                    <li>
                        <a href="{{ route('home.start') }}" class="waves-effect">
                            <i class="icon-accelerator"></i> <span> Anasayfa </span>
                        </a>
                    </li>
                @endif

                @if(in_array(1, $__global['authorization_array']))
                    <li>
                        <a href="{{ route('pay.screen') }}" class="waves-effect"><i class="icon-shopping-cart"></i><span> {{ session()->get('users')['authority'] === "admin" ? "Sanal Pos" : "Ödeme Yap" }} </span></a>
                    </li>

                    @if($__global['authority_seller'] === 1)
                        <li>
                            <a href="{{ route('pay.list') }}" class="waves-effect"><i class="icon-todolist"></i><span> {{ session()->get('users')['authority'] === "admin" ? "Ödeme Listesi" : "Ödemelerim" }} </span></a>
                        </li>
                    @endif
                @endif

                @if(in_array(3, $__global['authorization_array']) || in_array(4, $__global['authorization_array']))
                    <li class="menu-title">Bayi</li>
                @endif

                @if(in_array(3, $__global['authorization_array']))
                    <li>
                        <a href="{{ route('seller.add') }}" class="waves-effect"><i class="icon-plus"></i><span> Bayi Ekle </span></a>
                    </li>
                @endif

                @if(in_array(4, $__global['authorization_array']))
                    <li>
                        <a href="{{ route('seller.start') }}" class="waves-effect"><i class="icon-graph"></i><span> Bayi Listele </span></a>
                    </li>
                @endif

                @if(session()->get('users')['authority'] === "admin")
                    <li class="menu-title">Yönetim</li>
                    <li>
                        <a href="{{ route('bank.start') }}" class="waves-effect"><i class="icon-setting-1"></i><span> Banka Ayarları </span></a>
                    </li>
                    <li>
                        <a href="{{ route('installment.start') }}" class="waves-effect"><i class="icon-share"></i><span> + Taksit Ayarları </span></a>
                    </li>
                    <li>
                        <a href="{{ route('authority.transactionConstraint') }}" class="waves-effect"><i class="icon-cloud-upload"></i><span> İşlem Kısıtla </span></a>
                    </li>
                @endif

                @if(in_array(5, $__global['authorization_array']))
                    <li class="menu-title">Haberler</li>
                    <li>
                        <a href="javascript:void(0);" class="waves-effect"><i class="icon-paper-sheet"></i><span> Haberler <span class="float-right menu-arrow"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                        <ul class="submenu">
                            <li><a href="{{ route('news.add') }}">Haber Ekle</a></li>
                            <li><a href="{{ route('news.start') }}">Haber Listele</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>