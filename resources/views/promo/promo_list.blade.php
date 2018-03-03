<section class="content-header">
	<h1>{{ $txt["promo_price_setting_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row basicList">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<div>
						<input type="button" class="btn btn-primary" value="{{ $txt['add_promo_price'] }}" onclick="location.href='/promo/create?mall_shop_id={{ $id }}';"/>
						<input type="button" class="btn btn-primary" value="{{ $txt['back'] }}" onclick="location.href='/mall';"/>
					</div>
					<br />
					<h3 class="box-title">{{ $txt["promo_price_setting_list"] }}</h3>
				</div>
				<div class="box-body">
					<basic-list ref="basicList" :list="{{ $htmlJsonData }}" :txt="{{ $JsonTxt }}"></basic-list>
				</div>
			</div>
		</div>
	</div>
</section>