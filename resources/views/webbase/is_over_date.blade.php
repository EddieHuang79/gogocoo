<section class="content">
	<div class="lightbox">
	  <div class="subject">{{ $txt["is_over_date_notice"] }} <label class="close_btn"> X </label> </div>
	  <hr>
	  <div class="content">
		@foreach($over_date_des as $row)
			<div>{{ $row }}</div>
		@endforeach
		
		<br />
		<br />

		<button class="btn btn-lg btn-primary btn-block" type="button" onclick="location.href='/buy';">{{ $txt['go_to_store'] }}</button>

	  </div>
	</div>
</section>