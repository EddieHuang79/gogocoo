<div id="Verify">
	<div><input type="text" id="VerifyCode" class="form-control" placeholder="{{ $txt['verify_input'] }}" name="verify"  maxlength="4" required></div>
	<div class="verify_code">
		@foreach($Verifycode_img as $row)
		<img src="{{ URL::asset('_images') }}/{{ $row }}.png" alt="" width="50">
		@endforeach	
	</div>
	<div><input type="button" value="{{ $txt['refresh_verify_code'] }}" class="refresh_verify_code btn btn-primary"></div>
</div>