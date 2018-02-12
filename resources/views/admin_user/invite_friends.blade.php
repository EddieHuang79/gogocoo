<div class="lightbox inviteCode">
	<div class="login-box2">
		<label class="close_btn"> X </label>
		<div class="login-logo">
			<a href="#"><b>{{ $txt['Site'] }}</b> Welcome </a>
		</div>
		<div class="login-box-body">
			<form action="/invite_friend" method="POST">
				<h2 class="large-title hero-title__large">獲得多達100個月的{{ $txt['Site'] }}免費時數！</h2>
				<h3 class="medium-title hero-title__small">邀请朋友加入 {{ $txt['Site'] }}，每當您有一位朋友註冊{{ $txt['Site'] }}時，您和朋友都可以獲得 1個月的免費時數。</h3>
				<br>
				<div class="form-group has-feedback">
					<input type="email" class="form-control" placeholder="Email" name="email" required/>
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="text" class="form-control" placeholder="{{ $txt['friend_name'] }}" name="friend_name" maxlength="10" required/>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-8">    
						<div class="checkbox icheck"></div>                        
					</div>
					<div class="col-xs-4">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<button type="submit" class="btn btn-primary btn-block btn-flat">{{ $txt['send_invite_code'] }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>