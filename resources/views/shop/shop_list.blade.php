<section class="content-header">
	<h1>{{ $txt["service_buy"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content shopList">
	
	<shop-list ref="shopList" :list="{{ $htmlJsonData }}" :txt="{{ $JsonTxt }}"></shop-list>

	<mall-product-lightbox ref="mallProductLightbox" :txt="{{ $JsonTxt }}" token="{{ csrf_token() }}"></mall-product-lightbox>

	<div class="lightbox shop_finish">
		<label class="close_btn"> X </label>
		<div class="subject">  </div>
		<hr>
		<div class="content">  </div>
	</div>
	
	@if(!empty($shop_product_list))
		{{ $shop_product_list->links() }}
	@endif

</section>