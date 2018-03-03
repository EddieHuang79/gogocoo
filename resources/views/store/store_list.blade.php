<section class="content-header">
	<h1>{{ $txt["store_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row basicList">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["store_list"] }}</h3>
				</div>
				<div class="box-body">
					<basic-list ref="basicList" :list="{{ $htmlJsonData }}" :txt="{{ $JsonTxt }}"></basic-list>
				</div>
			</div>
		</div>
	</div>
	<div class="lightbox extend_lightbox">
		<form method="POST" action="/extend_store_process" id="ShopForm">
			<label class="close_btn"> X </label>
			<div class="popup_title">{{ $txt['extend_deadline'] }}</div>
			<hr />
			<div class="popup_option">
				<div class="popup_option_left">
					{{ $txt['store_name'] }} <label class="account">  </label>
				</div>
				<div class="popup_option_right">
					{{ $txt['extend'] }}{{ $txt['deadline'] }}
					<select name="date_spec" required>
						<option value="">{{ $txt['select_default'] }}</option>
					</select>
				</div>
			</div>			
			<div class="mall_product_btn">
				<input type="hidden" name="user_id" value="">
				<input type="button" value="{{ $txt['send'] }}" onclick="ExtendSubmit();">
				<input type="button" value="{{ $txt['back'] }}" onclick="ClosePopup();">
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

</section>