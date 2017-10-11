<section class="content-header">
	<h1>{{ $txt["service_buy"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	
	@include('webbase.search_tool')

	<div class="mall_product_list">

	@if(!empty($shop_product_list))
		@foreach($shop_product_list as $row)
			<div class="mall_product">
				<img src="{{ $row->pic }}" alt="{{ $row->product_name }}" MallProductId="{{ $row->id }}">
			</div>
		@endforeach
	@else
		<div class="mall_product"> {{ $txt['find_nothing'] }} </div>		
	@endif	
		
	</div>

	<div class="lightbox mall_product_lightbox">
		<form method="POST" action="/shop_buy_process" id="ShopForm">
				<label class="close_btn"> X </label>
				<div class="mall_product_name">mall_product_name</div>
				<hr />
				<div class="mall_child_product">{{ $txt['include_service'] }}</div>			
				<div class="mall_product_option">
					<div class="mall_product_spec">
						{{ $txt['cost_unit'] }} <label class="cost">  </label>
					</div>
					<div class="mall_product_number">
						<div class="number_btn minus" target="[name='mall_product_number']">-</div> <div><input type="text" name="mall_product_number" value="1" size="1" style="width: 30px; text-align: center;" readonly="true"></div> <div class="number_btn plus" target="[name='mall_product_number']">+</div>
					</div>
				</div>			
				<div class="mall_product_cost"></div>			
				<div class="mall_product_btn">
					<input type="hidden" class="mall_shop_id" name="mall_shop_id">
					<input type="hidden" name="total">
					<input type="submit" value="{{ $txt['buy'] }}" class="btn btn-primary">
					<input type="button" value="{{ $txt['back'] }}" class="btn btn-primary" onclick="ClosePopup();">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</div>			
		</form>
	</div>

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