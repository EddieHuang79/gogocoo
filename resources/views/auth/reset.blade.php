<form class="form-signin position-absolute-center" role="form" method="POST" action="/forgot_password">
    <h2>{{ $txt["reset"] }}</h2>
    <br />
    <h3>{{ $txt["reset_description"] }}</h3>
    <br />
    <button class="btn btn-lg btn-primary btn-block btnAction" type="button" onclick="location.href='/login';">{{ $txt['login'] }}</button>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>