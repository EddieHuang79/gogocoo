@include('webbase.header')
	<div class="wrapper">
		@include('webbase.nav')
		@include('webbase.menu')
		<div class="content-wrapper">
			@include($assign_page)
		</div>
		@include('webbase.footer')
	</div>
@include('webbase.webend')
