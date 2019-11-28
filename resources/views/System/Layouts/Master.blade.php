<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description"  content="DAF is a foreign-exchange securities investment fund company and owns Artificial Intelligence Technology with DAF BOT AI. Having the ability to multi-exchange transactions and bring about huge profits."/>
	<meta name="keywords" content="DAF is a foreign-exchange securities investment fund company and owns Artificial Intelligence Technology with DAF BOT AI. Having the ability to multi-exchange transactions and bring about huge profits."/>
	<meta name="author"  content="DAF is a foreign-exchange securities investment fund company and owns Artificial Intelligence Technology with DAF BOT AI. Having the ability to multi-exchange transactions and bring about huge profits."/>
    <meta name="" content="" />
    <base href="{{asset('/dafco').'/'}}">
    <title>DAFCO - @yield('title')</title>
    <link rel="shortcut icon" href="assets/logo/Logo-DAFCO-1.png">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css?v{{time()}}" rel="stylesheet" type="text/css">

    <!-- Toast CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" />
    <style>
        .swal2-header {
            font-size: 1.5rem;
        }

        .swal2-styled.swal2-confirm {
            background-color: #673AB7;
        }

        .swal2-popup {
            width: 46em;
        }

        #wrapper.enlarged .left.side-menu {
            overflow: initial !important;
        }
    </style>
    <style>
        .topfix{
            right: -35px;
            position: fixed;
            z-index: 999;
            top: 8rem;
        }
        .topfix li{
            list-style: none;
        }

        main {
            display: flex;
			justify-content: center;
			align-items: center;
			height: auto;
			padding-top: 15px;
			position: relative;
			margin-bottom: -15px;
        }
		.nav>li {
			display: inline-block;
		}
        main .notification {
            position: relative;
            /* width: 10em;
            height: 10em; */

        }

        main .notification svg {
            height: 2.5em;

        }

        main .notification svg>path {
            fill: #ffa800;
        }

        main .notification--bell {
            animation: bell 2.2s linear infinite;
            transform-origin: 50% 0%;
        }

        main .notification--bellClapper {
            animation: bellClapper 2.2s 0.1s linear infinite;
        }

        main .notification--num {
            position: absolute;
			top: 0%;
			left: 60%;
			font-size: 17px;
			border-radius: 50%;
			width: 1.25em;
			height: 1.25em;
			background-color: #F44336;
			border: 6px solid #F44336;
			color: #FFFFFF;
			text-align: center;
			line-height: 10px;
			animation: notification 3.2s ease;
        }

        @keyframes bell {

            0%,
            25%,
            75%,
            100% {
                transform: rotate(0deg);
            }

            40% {
                transform: rotate(10deg);
            }

            45% {
                transform: rotate(-10deg);
            }

            55% {
                transform: rotate(8deg);
            }

            60% {
                transform: rotate(-8deg);
            }
        }

        @keyframes bellClapper {

            0%,
            25%,
            75%,
            100% {
                transform: translateX(0);
            }

            40% {
                transform: translateX(-.15em);
            }

            45% {
                transform: translateX(.15em);
            }

            55% {
                transform: translateX(-.1em);
            }

            60% {
                transform: translateX(.1em);
            }
        }

        @keyframes notification {

            0%,
            25%,
            75%,
            100% {
                opacity: 1;
            }

            30%,
            70% {
                opacity: 0;
            }
        }
        .dropdown-divider{
            height: 0;
            margin: .5rem 0;
            overflow: hidden;
            border-top: 1px solid #e9ecef;
        }
    </style>

    @yield('css')
    <script type="text/javascript">
        _atrk_opts = { atrk_acct: "vaigt1zDGU20kU", domain: "dafco.org", dynamic: true };
		(function () { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://certify-js.alexametrics.com/atrk.js"; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(as, s); })();
    </script>
    <noscript><img src="https://certify.alexametrics.com/atrk.gif?account=vaigt1zDGU20kU" style="display:none"
            height="1" width="1" alt="" /></noscript>
</head>

<body class="fixed-left">
    <div id="wrapper">
        <!-- Top Menu Items -->
        @include('System.Layouts.Header')
        <!-- /Top Menu Items -->

        <!-- Left Sidebar Menu -->
        @include('System.Layouts.Menu')
        <!-- /Left Sidebar Menu -->

        <!-- Main Content -->
        <div class="content-page">
            <div class="backgroungImg"></div>
            @yield('content')

            <!-- Footer -->
            @include('System.Layouts.Footer')
            <!-- /Footer -->

        </div>
        <!-- /Main Content -->

    </div>
    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>

    <script src="assets/js/app.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        @if(Session::get('flash_level') == 'success')
        toastr.success('{{ Session::get('flash_message') }}', 'Success!', {timeOut: 3500})
    @elseif(Session::get('flash_level') == 'error')
        toastr.error('{{ Session::get('flash_message') }}', 'Error!', {timeOut: 3500})
    @endif

    @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            toastr.error('{{$error}}', 'Error!', {timeOut: 3500})
        @endforeach
    @endif
    $(document).ready(function() {
        @if(!(DB::table('users')->where('User_ID', session('user')->User_ID)->value('User_WalletGTC')))
        $('#notifi-wallet').modal('show');
        @endif
    })
    </script>

    
    <script>
        $(document).ready(function () {
            @if(isset($RandomToken))
            $('form').append('<input type="hidden" name="CodeSpam" value="{{ $RandomToken }}">');
            @endif
        });
        
    </script>
    

    @yield('script')
</body>

</html>