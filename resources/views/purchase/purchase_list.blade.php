<section class="content-header">
	<h1>{{ $txt["purchase_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')

	<form action="/purchase_verify" method="POST">
	    <div class="row basicList">
	        <div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<input type="submit" name="verify" value="{{ $txt['purchase_verify'] }}" class="btn btn-primary">
					</div>
					<div class="box-body">
						<basic-list ref="basicList" :list="{{ $htmlJsonData }}" :txt="{{ $JsonTxt }}"></basic-list>
					</div>
				</div>
			</div>
		</div>
		{{ $purchase_data->links() }}
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

	</form>	

</section>