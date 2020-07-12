
            <!-- Start right Content here -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">

                    <!-- Top Bar Start -->
                    <div class="topbar">

                        <nav class="navbar-custom">
                            <!-- Search input -->
                            <div class="search-wrap" id="search-wrap">
                                <div class="search-bar">
                                    <input class="search-input" type="search" placeholder="Search" />
                                    <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                                        <i class="mdi mdi-close-circle"></i>
                                    </a>
                                </div>
                            </div>



                            <ul class="list-inline float-right mb-0">
                                <!-- Search -->
                                <li class="list-inline-item dropdown notification-list">
                                    <a class="nav-link waves-effect toggle-search" href="#"  data-target="#search-wrap">
                                        <i class="mdi mdi-magnify noti-icon"></i>
                                    </a>
                                </li>
                                <!-- Fullscreen -->
                                <li class="list-inline-item dropdown notification-list hidden-xs-down">
                                    <a class="nav-link waves-effect" href="#" id="btn-fullscreen">
                                        <i class="mdi mdi-fullscreen noti-icon"></i>
                                    </a>
                                </li>

                                <li class="list-inline-item dropdown notification-list">
                                    <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                       aria-haspopup="false" aria-expanded="false">
                                        <img src="{{ URL::asset('assets/images/users/avatar-1.jpg')}}" alt="user" class="rounded-circle">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                        <a class="dropdown-item" href="#"><em class="mdi mdi-account"></em>&nbsp;{{ucfirst(\Illuminate\Support\Facades\Auth::user()->fName)}}</a>
                                        <a class="dropdown-item" href="#"><em class="mdi mdi-account-star-variant"></em> {{ucfirst(strtolower(\Illuminate\Support\Facades\Auth::user()->userRole->role))}}</a>
                                        <a class="dropdown-item" href="#"><em class="mdi mdi-bank"></em> {{ucfirst(\Illuminate\Support\Facades\Auth::user()->office->office_name)}}</a>
                                        <a class="dropdown-item" href="#" onclick="$('#goToMyProfileForm').submit();"><em class="mdi mdi-account-circle"></em> My Profile</a>
                                        <button class="dropdown-item" onclick="$('#logout-form').submit();"><i class="dripicons-exit text-muted"></i> Logout</button>
                                    </div>
                                </li>
                            </ul>
                            <form id="goToMyProfileForm"
                                  action="{{route('myProfile')}}"
                                  method="POST">
                                <input type="hidden" class="noClear" name="_token" value="{{csrf_token()}}">
                            </form>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                <input type="hidden" class="noClear" name="_token" value="{{csrf_token()}}">
                            </form>
                            <!-- Page title -->
                            <ul class="list-inline menu-left mb-0">
                                <li class="list-inline-item">
                                    <button type="button" class="button-menu-mobile open-left waves-effect">
                                        <em class="ion-navicon"></em>
                                    </button>
                                </li>
                                <li class="hide-phone list-inline-item app-search">
                                    <h3 class="page-title">{{ $title }}</h3>
                                </li>
                            </ul>

                            <div class="clearfix"></div>
                        </nav>

                    </div>
                    <!-- Top Bar End -->