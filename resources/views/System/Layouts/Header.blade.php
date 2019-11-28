<?php
    $check_user = DB::table('profile')->where('Profile_User',Session('user')->User_ID)->first();
    $bg = "#F44336";
?>
@if($check_user)
    @if($check_user->Profile_Status == 1)
        <?php
            $bg = "#23bb3c";
        ?>
    @endif
@endif


<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <a href="{{route('Dashboard')}}" class="logo"><img src="assets/logo/Logo-DAFCO-2.png" height="50"></a>
            <a href="{{route('Dashboard')}}" class="logo-sm"><img src="assets/logo/Logo-DAFCO-1.png" height="36"></a>
        </div>
    </div>
    <!-- Button mobile view to collapse sidebar menu -->
    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="">
                <div class="pull-left">
                    <button type="button" class="button-menu-mobile open-left waves-effect waves-light">
                        <i class="ion-navicon"></i>
                    </button>
                    <span class="clearfix"></span>
                </div>

                <ul class="nav navbar-nav navbar-right pull-right">

                    <li class="dropdown">
                        
                        <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown"
                            aria-expanded="true">
                            <main rel="main">
                                <div class="notification">
                                    <svg viewbox="0 0 166 197">
                                        <path
                                            d="M82.8652955,196.898522 C97.8853137,196.898522 110.154225,184.733014 110.154225,169.792619 L55.4909279,169.792619 C55.4909279,184.733014 67.8452774,196.898522 82.8652955,196.898522 L82.8652955,196.898522 Z"
                                            class="notification--bellClapper"></path>
                                        <path
                                            d="M146.189736,135.093562 L146.189736,82.040478 C146.189736,52.1121695 125.723173,27.9861651 97.4598237,21.2550099 L97.4598237,14.4635396 C97.4598237,6.74321823 90.6498186,0 82.8530327,0 C75.0440643,0 68.2462416,6.74321823 68.2462416,14.4635396 L68.2462416,21.2550099 C39.9707102,27.9861651 19.5163297,52.1121695 19.5163297,82.040478 L19.5163297,135.093562 L0,154.418491 L0,164.080956 L165.706065,164.080956 L165.706065,154.418491 L146.189736,135.093562 Z"
                                            class="notification--bell"></path>
                                    </svg>
                                    @if(!$check_user)
                                    <span class="notification--num">1</span>
                                    @endif
                                </div>

                            </main>
                        </a>
                        
                        {{-- <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);">Notification</a></li>
                            <div class="dropdown-divider"></div>
                            <li><a href="{{route('getProfile')}}"> Wallet SonicX</a></li>
                        </ul> --}}
                    </li>

                    <li class="dropdown">
                        <a href="" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown"
                            aria-expanded="true"><img src="assets/images/users/userProfile.png" alt="user-img"
                                class="img-circle"> </a>
                        <ul class="dropdown-menu">
                            
                            <li><a href="{{route('getProfile')}}"> Profile</a></li>
                            <li><a href="{{route('getProfile')}}" style="color: #f9f7f7; background:{{$bg}} ;">
                                    Verification</a></li>
                            <li><a href="{{route('getLogout')}}"> Logout</a></li>
                        </ul>
                    </li>

                </ul>


            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
</div>