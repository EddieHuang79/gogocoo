<section class="content-header">
	<h1>{{ $txt["order_export"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')

	<form action="/order_output_process" method="POST">
	    <div class="row basicList">
	        <div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">
							<input type="submit" name="verify" value="{{ $txt['export_format1'] }}" class="btn btn-primary">
						</h3>
					</div>
					<div class="box-body">
						<basic-list ref="basicList" :list="{{ $htmlJsonData }}" :txt="{{ $JsonTxt }}"></basic-list>
					</div>
				</div>
			</div>
		</div>
		{{ $order_data->links() }}
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

	</form>	

</section>