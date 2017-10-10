<div id="Verify">
	<div class="form-group has-feedback">
		<input type="text" id="VerifyCode" class="form-control" placeholder="{{ $txt['verify_input'] }}" name="verify"  maxlength="4" required/>
	</div>
	<div class="verify_code">
		<img src="{{ URL::asset($Verifycode_img) }}" alt="" height="36">
	</div>
</div>
<br />