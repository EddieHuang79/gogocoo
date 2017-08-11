<section class="content-header">
	<h1>{{ $txt["purchase_input"] }}</h1>
</section>
<section class="content">
	<form action="/purchase" method="POST">
		<table class="table table-stroped">
			<tbody>
				<tr>
					<th width="10%">{{ $txt["product_name"] }}</th>
					<td>
						<input type="text" name="product_name" value="@if(!empty($purchase)){{ $purchase['product_name'] }}@endif" placeholder="{{ $txt['product_name_key_input'] }}" class="autocomplete" AutoCompleteType="product_name" site="{{ $site }}" specId="@if(!empty($purchase)){{ $purchase['spec_id'] }}@endif" required/>
					</td>
				</tr>
				@if( $has_spec == true )
				<tr>
					<th width="10%">{{ $txt["product_spec"] }}</th>
					<td>
						<select name="spec_id" required>
							<option value="">{{ $txt["select_default"] }}</option>
						</select>
					</td>
				</tr>
				@endif
				@if(!empty($purchase))
				<tr>
					<th width="10%">{{ $txt["in_warehouse_number"] }}</th>
					<td>{{ $purchase['in_warehouse_number'] }}</td>
				</tr>
				@endif
				<tr>
					<th width="10%">{{ $txt["number"] }}</th>
					<td>
						<input type="number" name="number" value="@if(!empty($purchase)){{ $purchase['number'] }}@endif" placeholder="{{ $txt['number_input'] }}" required/>
					</td>
				</tr>		
				<tr>
					<th width="10%">{{ $txt["in_warehouse_date"] }}</th>
					<td>
						<input type="text" name="in_warehouse_date" value="@if(!empty($purchase)){{ $purchase['in_warehouse_date'] }}@endif" placeholder="{{ $txt['in_warehouse_date_input'] }}" size="30"/>
					</td>
				</tr>
				<tr>
					<th width="10%">{{ $txt["in_warehouse_category"] }}</th>
					<td>
						<select name="category" required>
							<option value="">{{ $txt["select_default"] }}</option>
							@foreach($in_warehouse_category_data as $key => $value)
								<option value="{{ $key }}" @if( !empty($purchase['category']) && $key == $purchase['category'] ) selected @endif>{{ $value }}</option>
							@endforeach
						</select>
					</td>
				</tr>

				@if( !empty( $purchase_extra_column ) )
				
				@foreach($purchase_extra_column as $row)
					@if( $row['show_on_page'] === true )
					<tr>
						<th>{{ $txt[$row['name']] }}</th>
						<td> 
							@if( in_array( $row['type'], array('text', 'number') ) )
							<input type="{{ $row['type'] }}" name="{{ $row['name'] }}" placeholder="{{ $txt[$row['name'].'_input'] }}" value="@if(!empty($purchase)){{ $purchase[$row['name']] }}@endif"> 
							@endif
							@if( in_array( $row['type'], array('select') ) )
							<select name="{{ $row['name'] }}" id="">
								<option value="">{{ $txt['select_default'] }}</option>
								@foreach($select_option[$row['name']] as $key => $value)
								<option value="{{ $key }}"  @if( !empty($purchase) && $purchase[$row['name']] == $key ) selected @endif>{{ $value }}</option>
								@endforeach
							</select>
							@endif
						</td>
					</tr>
					@endif
				@endforeach

				@endif

				<tr>
					<th colspan="2"><input type="submit" value="{{ $txt['send'] }}"/></th>
				</tr>															
			</tbody>
		</table>
		<input type="hidden" name="purchase_id" value="@if(!empty($purchase)){{ $purchase['id'] }}@endif">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</section>