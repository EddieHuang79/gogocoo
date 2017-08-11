<form class="position-absolute-center form-signin" role="form" method="POST" action="/register">
    @if(!empty($ErrorMsg))
    	@foreach($ErrorMsg as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @endif
    <h2 class="form-signin-heading">{{ $txt['register_title'] }}</h2>
    <label for="inputEmail" class="sr-only">{{ $txt['account_input'] }}</label>
    <input type="email" id="inputStoreEmail" name="StoreEmail" class="form-control" placeholder="{{ $txt['account_input'] }}" @if(!empty($Social_login)) value="{{ $Social_login['account'] }}" style="display: none;" @endif required autofocus>
    <label for="inputPassword" class="sr-only">{{ $txt['password_input'] }}</label>
    <input type="password" id="inputStorePassword" name="StorePassword" class="form-control" placeholder="{{ $txt['password_input'] }}" @if(!empty($Social_login)) value="{{ $Social_login['pwd'] }}" style="display: none;" @endif required>
    <label for="inputReCheckPassword" class="sr-only">{{ $txt['password_check_input'] }}</label>
    <input type="password" id="inputReCheckPassword" name="ReCheckPassword" class="form-control" placeholder="{{ $txt['password_check_input'] }}" @if(!empty($Social_login)) value="{{ $Social_login['pwd'] }}" style="display: none;" @endif required>
    <label for="inputMobile" class="sr-only">{{ $txt['phone_input'] }}</label>
    <input type="text" id="inputMobile" name="Mobile" class="form-control" placeholder="{{ $txt['phone_input'] }}" required>
    <label for="inputRealName" class="sr-only">{{ $txt['real_name_input'] }}</label>
    <input type="text" id="inputRealName" name="RealName" class="form-control" placeholder="{{ $txt['real_name_input'] }}" required>
    @include('tool.store_type')
    <label for="inputStoreName" class="sr-only">{{ $txt['store_name_input'] }}</label>
    <input type="text" id="inputStoreName" name="StoreName" class="form-control" placeholder="{{ $txt['store_name_input'] }}" maxlength="6" required>
    <label for="inputStoreName" class="sr-only">{{ $txt['store_code_input'] }}</label>
    <input type="text" id="inputStoreCode" name="StoreCode" class="form-control" placeholder="{{ $txt['store_code_input'] }}" maxlength="3">
	@include('webbase.verify_code')
    <button class="btn btn-lg btn-primary btn-block btnAction" type="submit">{{ $txt['send'] }}</button>
    <button class="btn btn-lg btn-primary btn-block btnAction" type="button" onclick="location.href='/login';">{{ $txt['back'] }}</button>
    @if(!empty($Social_login))
    <input type="hidden" name="social_register" value="1">
    @endif
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>