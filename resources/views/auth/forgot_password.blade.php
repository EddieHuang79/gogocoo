<form class="form-signin position-absolute-center" role="form" method="POST" action="/forgot_password">
    @if(!empty($ErrorMsg))
        @foreach($ErrorMsg as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @endif
    <h2>{{ $txt["forgot_password"] }}</h2>
    <label for="inputEmail" class="sr-only">{{ $txt['account_input'] }}</label>
    <input type="email" id="inputEmail" class="form-control" placeholder="{{ $txt['account_input'] }}" name="email" required autofocus>
    @include('webbase.verify_code')
    <br />
    <button class="btn btn-lg btn-primary btn-block" type="submit">{{ $txt['send'] }}</button>
    <button class="btn btn-lg btn-primary btn-block btnAction" type="button" onclick="location.href='/login';">{{ $txt['back'] }}</button>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>