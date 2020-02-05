<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Login</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="{{ asset('js/plugin/webfont/webfont.min.js') }}"></script>
	<script>
		WebFont.load({
			google: {"families":["Montserrat:100,200,300,400,500,600,700,800,900"]},
			custom: {"families":["Flaticon", "LineAwesome"], urls: ['{{ asset("css/fonts.css") }}']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	
	<!-- CSS Files -->
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/ready.min.css') }}">
</head>
<body class="login">
	<div class="wrapper wrapper-login">
		<div class="container container-login animated fadeIn">
			<h3 class="text-center">{{ __('Login') }} To Admin</h3>
			<form method="POST" action="{{ route('login') }}">
            @csrf
			<div class="login-form">
				<div class="form-group form-floating-label">
					<input id="email" name="email" type="text" class="form-control input-border-bottom" value="{{ old('email') }}" required autofocus>
					<label for="email" class="placeholder">{{ __('E-Mail Address') }}</label>
					@error('email')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="form-group form-floating-label">
					<input id="password" name="password" type="password" class="form-control input-border-bottom" required>
					<label for="password" class="placeholder">{{ __('Password') }}</label>
					<div class="show-password">
						<i class="flaticon-interface"></i>
					</div>

					@error('password')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
					@enderror
				</div>
				<div class="row form-sub m-0">
					<div class="col col-md-12 login-forget">
					@if (Route::has('password.request'))
						<a href="{{ route('password.request') }}" class="link">Forget Password ?</a>
					@endif
					</div>
				</div>
				<div class="form-action">
					<button type="submit" class="btn btn-primary btn-rounded btn-login">{{ __('Login') }}</button>
				</div>
			</div>

			</form>
		</div>

	</div>
	<script src="{{ asset('js/core/jquery.3.2.1.min.js') }}"></script>
	<script src="{{ asset('js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('js/core/popper.min.js') }}"></script>
	<script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/ready.js') }}"></script>
</body>
</html>