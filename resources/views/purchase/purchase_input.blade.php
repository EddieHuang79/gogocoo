<section class="content-header">
	<h1>{{ $txt["purchase_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["purchase_input"] }}</h3>
				</div>
				<form action="/purchase" method="POST">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["product_name"] }}</label>
							<input type="text" name="product_name" class="form-control autocomplete" value="@if(!empty($purchase)){{ $purchase['product_name'] }}@endif" placeholder="{{ $txt['product_name_key_input'] }}" AutoCompleteType="product_name" site="{{ $site }}" specId="@if(!empty($purchase)){{ $purchase['spec_id'] }}@endif" required/>
							<input type="hidden" name="product_id" value="@if(!empty($purchase)){{ $purchase['product_id'] }}@endif">
							<input type="hidden" name="keep_for_days" value="@if(!empty($purchase)){{ $purchase['keep_for_days'] }}@endif">
						</div>
						@if( $has_spec == true )
						<div class="form-group">
							<label>{{ $txt["product_spec"] }}</label>
							<select name="spec_id" class="form-control" required>
								<option value="">{{ $txt["select_default"] }}</option>
							</select>
						</div>
						@endif
						@if(!empty($purchase))
						<div class="form-group">
							<label>{{ $txt["in_warehouse_number"] }}</label>
							{{ $purchase['in_warehouse_number'] }}
						</div>
						@endif
						<div class="form-group">
							<label>{{ $txt["number"] }}</label>
							<input type="number" name="number" class="form-control" value="@if(!empty($purchase)){{ $purchase['number'] }}@endif" placeholder="{{ $txt['number_input'] }}" min="1" required/>
						</div>		
						<div class="form-group">
							<label>{{ $txt["in_warehouse_date"] }}</label>
							<input type="text" name="in_warehouse_date" class="form-control" value="@if(!empty($purchase)){{ $purchase['in_warehouse_date'] }}@endif" placeholder="{{ $txt['in_warehouse_date_input'] }}" size="30"/>						
						</div>
						<div class="form-group">
							<label>{{ $txt["in_warehouse_category"] }}</label>						
							<select name="category" class="form-control" required>
								<option value="">{{ $txt["select_default"] }}</option>
								@foreach($in_warehouse_category_data as $key => $value)
									<option value="{{ $key }}" @if( !empty($purchase['category']) && $key == $purchase['category'] ) selected @endif>{{ $value }}</option>
								@endforeach
							</select>
						</div>
						@if( !empty( $purchase_extra_column ) )
							@foreach($purchase_extra_column as $row)
								@if( $row['show_on_page'] === true )
								<div class="form-group">
									<label>{{ $txt[$row['name']] }}</label>
									@if( in_array( $row['type'], array('text', 'number') ) )
									<input type="{{ $row['type'] }}" name="{{ $row['name'] }}" class="form-control" placeholder="{{ $txt[$row['name'].'_input'] }}" value="@if(!empty($purchase)){{ $purchase[$row['name']] }}@endif"> 
									@endif
									@if( in_array( $row['type'], array('select') ) )
									<select name="{{ $row['name'] }}" class="form-control" id="">
										<option value="">{{ $txt['select_default'] }}</option>
										@foreach($select_option[$row['name']] as $key => $value)
										<option value="{{ $key }}" selected>{{ $value }}</option>
										<!-- <option value="{{-- $key --}}"  @if( !empty($purchase) && $purchase[$row['name']] == $key ) selected @endif >{{-- $value --}}</option> -->
										@endforeach
									</select>
									@endif
								</div>
								@endif
							@endforeach
						@endif
						<div class="form-group">
							<label><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>
						<input type="hidden" name="purchase_id" value="@if(!empty($purchase)){{ $purchase['id'] }}@endif">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>	
				</form>
	      	</div>
        </div>
  	</div>
</section>