<form class="form-signin position-absolute-center" role="form" method="POST" action="/login">
    @if(!empty($ErrorMsg))
    	@foreach($ErrorMsg as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @endif
	<h2>{{ $txt["Site"] }}</h2>
	<label for="inputEmail" class="sr-only">{{ $txt['account_input'] }}</label>
	<input type="email" id="inputEmail" class="form-control" placeholder="{{ $txt['account_input'] }}" name="email" required autofocus>
	<label for="inputPassword" class="sr-only">{{ $txt['password_input'] }}</label>
	<input type="password" id="inputPassword" class="form-control" placeholder="{{ $txt['password_input'] }}" name="pwd" required>				
	@include('webbase.verify_code')
	<br />
	<button class="btn btn-lg btn-primary btn-block" type="submit">{{ $txt['login'] }}</button>
	<button class="btn btn-lg btn-primary btn-block" onclick="location.href='/register';" type="button">{{ $txt['register'] }}</button>
	<button class="btn btn-lg btn-primary btn-block" onclick="location.href='/forgot_password';" type="button">{{ $txt['forgot_password'] }}</button>
	<button class="btn btn-lg btn-primary btn-block" id="facebook_login" type="button" onclick="checkLoginState();">{{ $txt['facebook_login'] }}</button>
	<!-- <div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="login_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="true"></div> -->
	<!-- <fb:login-button scope="public_profile,email" onlogin="checkLoginState();"></fb:login-button> -->
	<button class="btn btn-lg btn-primary btn-block" id="google_login" type="button">{{ $txt['google_login'] }}</button>
	@include('auth.facebook_login')
	@include('auth.google_login')
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>