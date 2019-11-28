<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
        <title>DAFCO - Forgot Password</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <base href="{{asset('/dafco').'/'}}">

		<link rel="shortcut icon" href="assets/logo/Logo-DAFCO-1.png">

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css?v={{time()}}" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            _atrk_opts = { atrk_acct: "vaigt1zDGU20kU", domain: "dafco.org", dynamic: true };
            (function () { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://certify-js.alexametrics.com/atrk.js"; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(as, s); })();
        </script>
        <noscript><img src="https://certify.alexametrics.com/atrk.gif?account=vaigt1zDGU20kU" style="display:none"
                height="1" width="1" alt="" /></noscript>
	</head>
	<body>
		<!-- Begin page -->
		<div class="bgform_auth"></div>
        <div class="wrapper-page wrapper-page-form">
            <div class="panel panel-color panel-primary panel-pages bg-form">

                <div class="panel-body">
                    <h3 class="text-center m-t-0 m-b-30">
                        <span class=""><img src="assets/logo/Logo-DAFCO-2.png" alt="logo" height="70"></span>
                    </h3>
                    <h4 class="text-white text-center m-t-0"><b>Reset Password</b></h4>

                    <form method="POST" class="form-horizontal m-t-20" action="{{ route('postForgotPassword') }}">
                        @csrf
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="Email" type="email" required="" placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Send Mail</button>
                            </div>
                        </div>

                        <div class="form-group m-t-30 m-b-0">
                            <div class="col-sm-12 text-center">
                                <a href="{{ route('getLogin') }}" class="text-white">Back to LOGIN !</a>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
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

        <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
		<script type="text/javascript">
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
		</script>
	</body>
</html>
