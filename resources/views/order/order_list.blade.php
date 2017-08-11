<section class="content-header">
	<h1>{{ $txt["order_list"] }}</h1>
</section>
<section class="content">
	@include('webbase.search_tool')

	<form action="/order_verify" method="POST">

		<input type="submit" name="verify" value="{{ $txt['purchase_verify'] }}">

		<br />

		<table class="table table-stroped">
			<thead>
				<tr>
					<th><input type="checkbox" class="clickAll" target="order_checkbox"> &nbsp; {{ $txt['select_all'] }}</th>
					<th>{{ $txt['order_number'] }}</th>
					<th>{{ $txt['product_name'] }}</th>
					<th>{{ $txt['out_warehouse_date'] }}</th>
					<th>{{ $txt['out_warehouse_category'] }}</th>

					@if( !empty( $order_extra_column ) )
						@foreach($order_extra_column as $row)
							<th>{{ $txt[$row['name']] }}</th>
						@endforeach
					@endif

					<th>{{ $txt['number'] }}</th>
					<th>{{ $txt['status'] }}</th>
					<th>{{ $txt['action'] }}</th>
				</tr>							
			</thead>
			<tbody>
				@foreach($order_list as $row)
				<tr>
					<th>
						@if( $row['status'] == 1 )
						<input type="checkbox" class="order_checkbox" value="{{ $row['id'] }}" name="order_id[]">
						@endif
					</th>
					<th>{{ $row['order_number_txt'] }}</th>
					<th>{{ $row['product_name'] }}</th>
					<th>{{ $row['out_warehouse_date'] }}</th>
					<th>{{ $row['out_warehouse_category_txt'] }}</th>

					@if( !empty( $order_extra_column ) )
						@foreach($order_extra_column as $row2)
							<th>
								@if( isset($row[$row2['name']."_txt"]) )
								{{ $row[$row2['name']."_txt"] }}
								@else
								{{ $row[$row2['name']] }}
								@endif
							</th>
						@endforeach
					@endif
					
					<th>{{ $row['number'] }}</th>
					<th>{{ $row['status_txt'] }}</th>
					<td>
						@if( $row['status'] == 1 )
						<input type="button" value="{{ $txt['edit'] }}" onClick="location.href='/order/{{ $row['id'] }}/edit?';"/>
						@endif
					</td>
				</tr>
				@endforeach													
			</tbody>
		</table>
		{{ $order_data->links() }}
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

	</form>	

</section>