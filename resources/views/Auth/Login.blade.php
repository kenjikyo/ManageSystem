<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>DAFCO - Login</title>
    <meta name="description" content="Winkle is a Dashboard & Admin Site Responsive Template by hencework." />
    <meta name="keywords"
        content="admin, admin dashboard, admin template, cms, crm, Winkle Admin, Winkleadmin, premium admin templates, responsive admin, sass, panel, software, ui, visualization, web app, application" />
    <meta name="author" content="hencework" />
    <base href="{{asset('/dafco').'/'}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" />


    <link rel="shortcut icon" href="assets/logo/Logo-DAFCO-1.png">

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css?v={{time()}}" rel="stylesheet" type="text/css">
    
	<!-- Start Alexa Certify Javascript -->
	<script type="text/javascript">
		_atrk_opts = { atrk_acct: "vaigt1zDGU20kU", domain: "dafco.org", dynamic: true };
		(function () { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://certify-js.alexametrics.com/atrk.js"; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(as, s); })();
	</script>
	<noscript><img src="https://certify.alexametrics.com/atrk.gif?account=vaigt1zDGU20kU" style="display:none"
			height="1" width="1" alt="" /></noscript>
	<!-- End Alexa Certify Javascript -->
    <style>
    .swal2-header{
        font-size: 1.5rem;
    }
    .swal2-styled.swal2-confirm{
        background-color: #673AB7;
    }
    .swal2-popup{
        width: 46em;
    }
    </style>
    <script type="text/javascript">
		_atrk_opts = { atrk_acct: "vaigt1zDGU20kU", domain: "dafco.org", dynamic: true };
		(function () { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://certify-js.alexametrics.com/atrk.js"; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(as, s); })();
	</script>
	<noscript><img src="https://certify.alexametrics.com/atrk.gif?account=vaigt1zDGU20kU" style="display:none"
			height="1" width="1" alt="" /></noscript>
</head>

<body>
	<div class="bgform_auth"></div>
    <div class="wrapper-page wrapper-page-form">
        <div class="panel panel-color panel-primary panel-pages bg-form">

            <div class="panel-body">
                <h3 class="text-center m-t-0 m-b-30">
                    <span class=""><img src="assets/logo/Logo-DAFCO-2.png" alt="logo" height="70"></span>
                </h3>
                <h4 class="text-white text-center m-t-0"><b>Sign In</b></h4>

                <form class="form-horizontal m-t-20" method="POST" action="{{route('postLogin')}}">
                    @csrf
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" name="email" type="email" required="" placeholder="Email">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" name="password" type="password" required=""
                                placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>

                    <div class="form-group m-t-30 m-b-0">
                        <div class="col-sm-7">
                            <a href="{{ route('getForgotPassword') }}" class="text-white"><i
                                    class="fa fa-lock m-r-5"></i> Forgot
                                your password?</a>
                        </div>
                        <div class="col-sm-5 text-right">
                            <a href="{{ route('getRegister') }}" class="text-white">Create an account</a>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src='https://www.google.com/recaptcha/api.js?hl=us'></script>
    <script type="text/javascript">
        $(document).ready(function() {
		@if(Session::has('otp'))
			var CSRF_TOKEN = '{{ csrf_token() }}';
			swal.fire({
				title: 'Enter Authentication',
				text: 'Please enter authentication code.',
				input: 'text',
				type: 'input',
				name: 'txtOTP',
				type: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Submit',
				showLoaderOnConfirm: true,
				confirmButtonClass: 'btn btn-confirm',
				cancelButtonClass: 'btn btn-cancel'
				}).then(function (otp) {
					console.log(otp);
					$.ajax({
						url: "{{route('postLoginCheckOTP')}}",
						type: 'POST',
						data: {_token: CSRF_TOKEN, otp:otp.value},
						success: function (data) {
							if(data == 1){
								location.href = "{{route('Dashboard')}}";
							}else{
								swal.fire({
									title: 'Error',
									text: "Authentication Code Is Wrong",
									type: 'error',
									confirmButtonClass: 'btn btn-confirm',
									allowOutsideClick: false
								}).then(function() {
									location.href = "{{route('getLogin')}}";
								})
							}
						}
					});
		    	});
		@endif
	});

    console.clear();

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
        $('#sign-in-form').submit(function(e) {
            rcres = grecaptcha.getResponse();
            if (!rcres.length) {
                e.preventDefault();
            }
        });

    </script>
</body>

</html>
