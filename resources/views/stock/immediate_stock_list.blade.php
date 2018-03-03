<section class="content-header">
	<h1>{{ $txt["over_expire_stock_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	    <div class="row basicList">
	        <div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">{{ $txt["over_expire_stock_list"] }}</h3>
					</div>
					<div class="box-body">
						<basic-list ref="basicList" :list="{{ $htmlJsonData }}" :txt="{{ $JsonTxt }}"></basic-list>
					</div>
				</div>
			</div>
		</div>
	{{ $stock_data->links() }}

</section>