<section class="content-header">
	<h1>{{ $txt["store_input"] }}</h1>
	@if(!empty($store_status["free"]))
		<h5>{{ $txt["store_input_desc"] }}</h5>
	@endif
	@include('webbase.breadcrumb')
</section>
<section class="content">

	@if( $store_status["left"] > 0 )
	
	<form action="/store" method="POST">				    
		<table class="table table-stroped" style="width: 50%;">
			<tbody>

				<tr>
					<th width="10%">{{ $txt["store_name"] }}</th>
					<td><input type="text" id="inputStoreName" name="StoreName" class="form-control" value="@if(!empty($store)){{ $store->store_name }}@endif" placeholder="{{ $txt['store_name_input'] }}" maxlength="6" required></td>
				</tr>

				<tr>
					<th width="10%">{{ $txt["store_code"] }}</th>
					<td><input type="text" id="inputStoreCode" name="StoreCode" class="form-control" value="@if(!empty($store)){{ $store->store_code }}@endif" placeholder="{{ $txt['store_code_input'] }}" maxlength="3" @if(!empty($edit)) readonly="true" @endif ></td>
				</tr>

				<tr>
					<th>{{ $txt["store_type"] }}</th>
					<td>
						@if( empty($store_status["free"]) && empty($store) )
							@include('tool.store_type')
						@else
							{{ $store_info->store_type_name }}
							<input type="hidden" name="store_type_id" value="{{ $store_info->store_type }}">
						@endif
					</td>
				</tr>

				<tr>
					<th colspan="2"><input type="submit" value="{{ $txt['send'] }}"/></th>
				</tr>	

			</tbody>
		</table>
		<input type="hidden" name="store_id" value="@if(!empty($store)){{ $store->id }}@endif">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>

	@else

	<div class="lightbox">
		<div class="subject">{{ $txt["create_store_disable"] }} <label class="close_btn"> X </label> </div>
		<hr>
		<div class="content">
			{{ $txt["create_store_disable_desc"] }} 
			<br />
			<br />
			<br />
			<button class="btn btn-lg btn-primary btn-block" type="button" onclick="location.href='/buy';">{{ $txt['go_to_store'] }}</button>
		</div>
	</div>

	@endif

</section>