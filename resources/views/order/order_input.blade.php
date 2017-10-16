<section class="content-header">
	<h1>{{ $txt["order_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["order_input"] }}</h3>
				</div>
				<form action="/order" method="POST">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["product_name"] }}</label>
							<input type="text" name="product_name" class="form-control autocomplete" value="@if(!empty($order)){{ $order['product_name'] }}@endif" placeholder="{{ $txt['product_name_key_input'] }}" AutoCompleteType="product_name" site="{{ $site }}" specId="@if(!empty($order)){{ $order['spec_id'] }}@endif" required/>
							<input type="hidden" name="product_id" value="@if(!empty($order)){{ $order['product_id'] }}@endif">
						</div>
						@if( $has_spec == true )
						<div class="form-group">
							<label>{{ $txt["product_spec"] }}</label>							
							<select name="spec_id" class="form-control" required>
								<option value="">{{ $txt["select_default"] }}</option>
							</select>
						</div>
						@endif
						<div class="form-group">
							<label>{{ $txt["number"] }}</label>
							<input type="number" name="number" class="form-control" value="@if(!empty($order)){{ $order['number'] }}@endif" placeholder="{{ $txt['number_input'] }}" min="1" required/>							
						</div>		
						<div class="form-group">
							<label>{{ $txt["out_warehouse_date"] }}</label>
							<input type="text" name="out_warehouse_date" class="form-control" value="@if(!empty($order)){{ $order['out_warehouse_date'] }}@endif" placeholder="{{ $txt['out_warehouse_date_input'] }}" size="30"/>							
						</div>
						<div class="form-group">
							<label>{{ $txt["out_warehouse_category"] }}</label>							
							<select name="category" class="form-control" required>
								<option value="">{{ $txt["select_default"] }}</option>
								@foreach($out_warehouse_category_data as $key => $value)
									<option value="{{ $key }}" @if( !empty($order['category']) && $key == $order['category'] ) selected @endif>{{ $value }}</option>
								@endforeach
							</select>
						</div>
						@if( !empty( $order_extra_column ) )
							@foreach($order_extra_column as $row)
								@if( $row['show_on_page'] === true )
								<div class="form-group">
									<label>{{ $txt[$row['name']] }}</label>
									@if( in_array( $row['type'], array('text', 'number') ) )
										<input type="{{ $row['type'] }}" name="{{ $row['name'] }}" class="form-control" placeholder="{{ $txt[$row['name'].'_input'] }}" value="@if(!empty($order)){{ $order[$row['name']] }}@endif"> 
									@endif
									@if( in_array( $row['type'], array('select') ) )
										<select name="{{ $row['name'] }}" class="form-control" id="">
											<option value="">{{ $txt['select_default'] }}</option>
											@foreach($select_option[$row['name']] as $key => $value)
											<option value="{{ $key }}"  @if( !empty($order) && $order[$row['name']] == $key ) selected @endif>{{ $value }}</option>
											@endforeach
										</select>
									@endif
									@if( in_array( $row['type'], array('textarea') ) )
										<textarea name="{{ $row['name'] }}" class="form-control" placeholder="{{ $txt[$row['name'].'_input'] }}" cols="50" rows="5">@if(!empty($order)){{ $order[$row['name']] }}@endif</textarea>
									@endif
								</div>
								@endif
							@endforeach
						@endif
						<div class="form-group">
							<label><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>															
						<input type="hidden" name="order_id" value="@if(!empty($order)){{ $order['id'] }}@endif">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>	
				</form>
	      	</div>
        </div>
  	</div>
</section>