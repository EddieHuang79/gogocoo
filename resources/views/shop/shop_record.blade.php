<section class="content-header">
	<h1>{{ $txt["service_buy_record"] }}</h1>
	<h6>{{ $txt["record_desc"] }}</h6>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row basicList">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["service_buy_record"] }}</h3>
				</div>
				<div class="box-body">
					<basic-list ref="basicList" :list="{{ $htmlJsonData }}" :txt="{{ $JsonTxt }}"></basic-list>
				</div>
			</div>
		</div>
	</div>
	@if(!empty($shop_record))
		{{ $shop_record['data']->links() }}
	@endif
</section>