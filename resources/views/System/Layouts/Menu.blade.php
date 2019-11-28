<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <div class="user-details">
            <div class="text-center">
                <?php
                    $name_img = "EMPTY";
                    $level = DB::table('users')->where('User_ID',Session('user')->User_ID)->first();
                    $name_img = $level->User_Agency_Level;
                ?>
                <p><img src="assets/images/level/LEVEL_{{$name_img}}.png" alt="" class="img-circle"></p>
            </div>
            <div class="user-info">
                <div class="dropdown">
                    <a href="javascript:void(0)" style="color:#fff">Email: 
                        {{ Session('user')->User_Email}}</a>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">#
                        {{ Session('user')->User_ID}} <i class="fa fa-caret-down" aria-hidden="true"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{route('getProfile')}}"> Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="{{route('getLogout')}}"> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{route('Dashboard')}}" data-toggle="collapse" data-target="#">
                        <div class="pull-left pull-left-nav"><img src="dist/img/ic-nav/dashboard.png" width="25"
                                style="margin-right: 10px;"><span class="right-nav-text">Dashboard</span></div>
                        <div class="clearfix"></div>
                    </a>
                </li>

                <li class="has_sub">
                    <a class="" href="javascript:void(0);" data-toggle="collapse" data-target="#">
                        <div class="pull-left pull-left-nav"><img src="dist/img/ic-nav/wallet.png" width="25"
                                style="margin-right: 10px;"><span class="right-nav-text">My wallet</span></div>
                        <div class="pull-right"><i class="ti-angle-down"></i></div>
                        <div class="clearfix"></div>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('system.getDeposit')}}">Deposit</a></li>
                        <li><a href="{{ route('system.getWithdraw')}}">Withdraw</a></li>
                        <li><a href="{{ route('system.getTransfer')}}">Transfer</a></li>
                    </ul>

                </li>



                <li>
                    <a class="" href="{{route('system.getInvestment')}}" data-toggle="collapse" data-target="#">
                        <div class="pull-left pull-left-nav"><img src="dist/img/ic-nav/money.png" width="25"
                                style="margin-right: 10px;"><span class="right-nav-text">Investment</span></div>
                        <div class="clearfix"></div>
                    </a>
                </li>

                <li class="has_sub">
                    <a class="" href="javascript:void(0);" data-toggle="collapse" data-target="#">
                        <div class="pull-left pull-left-nav"><img src="dist/img/ic-nav/value.png" width="25"
                                style="margin-right: 10px;"><span class="right-nav-text">Member</span></div>
                        <div class="pull-right"><i class="ti-angle-down"></i></div>
                        <div class="clearfix"></div>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('system.user.getList')}}">Member List</a></li>
                        <li><a href="{{ route('system.user.getTree')}}">Member Tree</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" data-toggle="collapse" data-target="#">
                        <div class="pull-left pull-left-nav"><img src="dist/img/ic-nav/clock.png" width="25"
                                style="margin-right: 10px;"><span class="right-nav-text">History</span></div>
                        <div class="pull-right"><i class="ti-angle-down"></i></div>
                        <div class="clearfix"></div>
                    </a>
                    <ul class="list-unstyled">
                        <li>
                            <a href="{{route('system.history.getHistoryWallett')}}">History Wallet</a>
                        </li>
                        
                        <li>
                            <a href="{{route('system.history.getHistoryCommisson')}}">History Commission</a>
                        </li>
                <li>
                    <a href="{{route('system.history.getHistoryInvestment')}}">History Investment</a>
                </li>
            </ul>
            </li>

            <li>
                <a class="" href="{{route('Ticket')}}" data-toggle="collapse" data-target="#">
                    <div class="pull-left pull-left-nav"><img src="dist/img/ic-nav/tag.png" width="25"
                            style="margin-right: 10px;"><span class="right-nav-text">Ticket</span></div>
                    <div class="clearfix"></div>
                </a>
            </li>

            @if(session('user')->User_Level == 1 || session('user')->User_Level == 2 || session('user')->User_Level
            == 3)
            <li class="has_sub">
                <a class="" href="javascript:void(0);" data-toggle="collapse" data-target="#">
                    <div class="pull-left pull-left-nav"><img src="dist/img/ic-nav/admin-with-cogwheels.png" width="25"
                            style="margin-right: 10px;"><span class="right-nav-text">Admin</span></div>
                    <div class="pull-right"><i class="ti-angle-down"></i></div>
                    <div class="clearfix"></div>
                </a>
                <ul class="list-unstyled">
                    <li>
                        <a href="{{route('system.admin.getMemberListAdmin')}}">Member</a>
                    </li>


                    <li>
                        <a href="{{route('system.admin.getWallet')}}">Wallet</a>
                    </li>


                    <li>
                        <a href="{{route('system.admin.InvestmentList')}}">Investment</a>
                    </li>
                    <li>
                        <a href="{{route('system.admin.getInterest')}}">Interest</a>
                    </li>
                    <li>
                        <a href="{{route('system.admin.getStatistical')}}">Statistic</a>
                    </li>
                    <li>
                        <a href="{{route('system.admin.getProfile')}}">KYC</a>
                    </li>
                    <li>
                        <a href="{{route('getTicketAdmin')}}">Ticket</a>
                    </li>
                    <li>
                        <a href="{{route('system.admin.getLogMail')}}">Log</a>
                    </li>
                    <li>
                        <a href="{{route('system.admin.getLogSOX')}}">Log SOX</a>
                    </li>
                </ul>
            </li>
            @endif
            </ul>
        </div>
        <div class="clearfix"></div>
    </div> <!-- end sidebarinner -->
</div>