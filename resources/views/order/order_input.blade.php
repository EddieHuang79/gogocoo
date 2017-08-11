<section class="content-header">
	<h1>{{ $txt["order_input"] }}</h1>
</section>
<section class="content">
	<form action="/order" method="POST">
		<table class="table table-stroped">
			<tbody>
				<tr>
					<th width="10%">{{ $txt["product_name"] }}</th>
					<td>
						<input type="text" name="product_name" value="@if(!empty($order)){{ $order['product_name'] }}@endif" placeholder="{{ $txt['product_name_key_input'] }}" class="autocomplete" AutoCompleteType="product_name" site="{{ $site }}" specId="@if(!empty($order)){{ $order['spec_id'] }}@endif" required/>
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
				<tr>
					<th width="10%">{{ $txt["number"] }}</th>
					<td>
						<input type="number" name="number" value="@if(!empty($order)){{ $order['number'] }}@endif" placeholder="{{ $txt['number_input'] }}" required/>
					</td>
				</tr>		
				<tr>
					<th width="10%">{{ $txt["out_warehouse_date"] }}</th>
					<td>
						<input type="text" name="out_warehouse_date" value="@if(!empty($order)){{ $order['out_warehouse_date'] }}@endif" placeholder="{{ $txt['out_warehouse_date_input'] }}" size="30"/>
					</td>
				</tr>
				<tr>
					<th width="10%">{{ $txt["out_warehouse_category"] }}</th>
					<td>
						<select name="category" required>
							<option value="">{{ $txt["select_default"] }}</option>
							@foreach($out_warehouse_category_data as $key => $value)
								<option value="{{ $key }}" @if( !empty($order['category']) && $key == $order['category'] ) selected @endif>{{ $value }}</option>
							@endforeach
						</select>
					</td>
				</tr>

				@if( !empty( $order_extra_column ) )
				
				@foreach($order_extra_column as $row)
					@if( $row['show_on_page'] === true )
					<tr>
						<th>{{ $txt[$row['name']] }}</th>
						<td> 
							@if( in_array( $row['type'], array('text', 'number') ) )
							<input type="{{ $row['type'] }}" name="{{ $row['name'] }}" placeholder="{{ $txt[$row['name'].'_input'] }}" value="@if(!empty($order)){{ $order[$row['name']] }}@endif"> 
							@endif
							@if( in_array( $row['type'], array('select') ) )
							<select name="{{ $row['name'] }}" id="">
								<option value="">{{ $txt['select_default'] }}</option>
								@foreach($select_option[$row['name']] as $key => $value)
								<option value="{{ $key }}"  @if( !empty($order) && $order[$row['name']] == $key ) selected @endif>{{ $value }}</option>
								@endforeach
							</select>
							@endif
							@if( in_array( $row['type'], array('textarea') ) )
							<textarea name="{{ $row['name'] }}" placeholder="{{ $txt[$row['name'].'_input'] }}" cols="50" rows="5">@if(!empty($order)){{ $order[$row['name']] }}@endif</textarea>
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
		<input type="hidden" name="order_id" value="@if(!empty($order)){{ $order['id'] }}@endif">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</section>