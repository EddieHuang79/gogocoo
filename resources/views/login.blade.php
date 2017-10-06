<div class="login-box">
	<div class="login-logo">
		<a href="#">{{ $txt["Site"] }}</a>
	</div>
	<div class="login-box-body">
		<form method="POST" action="/login">
		    @if(!empty($ErrorMsg))
		    	@foreach($ErrorMsg as $error)
		            <div class="error">{{ $error }}</div>
		        @endforeach
		    @endif
			<div class="form-group has-feedback">
				<input type="email" class="form-control" placeholder="{{ $txt['account_input'] }}" name="email" required autofocus/>
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" id="inputPassword" class="form-control" placeholder="{{ $txt['password_input'] }}" name="pwd" required/>
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			@include('webbase.verify_code')
			<div class="row">
				<div class="col-xs-6">    
					<button class="btn btn-primary btn-block btnAction refresh_verify_code" type="button">{{ $txt['refresh_verify_code'] }}</button> 
				</div>
				<div class="col-xs-6">
					<button type="submit" class="btn btn-primary btn-block btn-flat">{{ $txt['login'] }}</button>
				</div>
			</div>

			<div class="social-auth-links text-center">
				<p>- OR -</p>
				<a href="#" class="btn btn-block btn-social btn-facebook btn-flat" id="facebook_login" onclick="checkLoginState();"><i class="fa fa-facebook"></i> {{ $txt['facebook_login'] }}</a>
				<a href="#" class="btn btn-block btn-social btn-google-plus btn-flat" id="google_login"><i class="fa fa-google-plus"></i> {{ $txt['google_login'] }}</a>
			</div>

	        <a href="/forgot_password">{{ $txt['forgot_password'] }}</a><br>
	        <a href="/register" class="text-center">{{ $txt['register'] }}</a>

			@include('auth.facebook_login')
			@include('auth.google_login')
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
		</form>
	</div>
</div>