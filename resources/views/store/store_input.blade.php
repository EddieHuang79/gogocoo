<section class="content-header">
	<h1>{{ $txt["store_input"] }}</h1>
	@if(!empty($store_status["free"]))
		<h5>{{ $txt["store_input_desc"] }}</h5>
	@endif
    @if(!empty($ErrorMsg))
    	@foreach($ErrorMsg as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @endif
	@include('webbase.breadcrumb')
</section>
<section class="content">

	@if( $store_status["left"] > 0 )
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["store_input"] }}</h3>
				</div>
				<form @if( isset($store->id) ) action="/store/{{ $store->id }}" @else action="/store" @endif method="POST">				    
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["store_name"] }}</label>
							<input type="text" id="inputStoreName" name="StoreName" class="form-control" @if(!empty($store)) value="{{ $store->store_name }}" @endif @if( !empty($OriData['StoreName']) ) value="{{ $OriData['StoreName'] }}" @endif placeholder="{{ $txt['store_name_input'] }}" maxlength="6" required>
						</div>
						<div class="form-group">
							<label>{{ $txt["store_code"] }}</label>
							<input type="text" id="inputStoreCode" name="StoreCode" class="form-control" @if(!empty($store)) value="{{ $store->store_code }}" @endif @if( empty($store) && !empty($OriData['StoreCode']) ) value="{{ $OriData['StoreCode'] }}" @endif placeholder="{{ $txt['store_code_input'] }}" maxlength="3" @if(!empty($edit)) readonly="true" @endif >
						</div>
						<div class="form-group">
							<label>{{ $txt["store_type"] }}</label>
							@if( empty($store_status["free"]) && empty($store) )
								@include('tool.store_type')
							@else
								<br />								
								{{ $store_info->store_type_name }}
								<input type="hidden" name="store_type_id" value="{{ $store_info->store_type }}">
							@endif
						</div>
						@if( empty($store) )
						<div class="form-group">
							<label>{{ $txt["deadline"] }}</label>
							@if( isset($store_status["free"]) && $store_status["free"] > 0 )
								{{ $txt['free_date_spec_desc'] }} {{ $deadline }}
							@endif
							@if( isset($store_status["free"]) && $store_status["free"] <= 0 )
								<select name="date_spec" class="form-control" required>
									<option value="">{{ $txt['select_default'] }}</option>
									@foreach($deadline as $index => $date_spec)
										<option value="{{ $index }}">{{ $date_spec }}</option>
									@endforeach
								</select>
							@endif
						</div>
						@endif
						<div class="form-group">
							<label><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>
						@if(!empty($store->id))
							<input type="hidden" name="_method" value="patch">				
						@endif		
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
	      	</div>
        </div>
  	</div>
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