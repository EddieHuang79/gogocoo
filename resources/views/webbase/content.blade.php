@include('webbase.header')
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		@include('webbase.nav')
		@include('webbase.menu')
		<div class="content-wrapper">
			@if( $is_over_date === false || in_array( $assign_page, array( 'shop/shop_list', 'store/store_list', 'admin_user/admin_list' ) ) )
			
				@include($assign_page)
			
			@else
				
				@include("webbase.is_over_date")
			
			@endif
		</div>
		@include('webbase.footer')
	</div>
@include('webbase.webend')
