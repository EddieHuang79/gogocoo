<section class="content-header">
	<h1>{{ $txt["purchase_list"] }}</h1>
</section>
<section class="content">
	@include('webbase.search_tool')

	<form action="/purchase_verify" method="POST">

		<input type="submit" name="verify" value="{{ $txt['purchase_verify'] }}">

		<br />

		<table class="table table-stroped">
			<thead>
				<tr>
					<th><input type="checkbox" class="clickAll" target="puchase_checkbox"> &nbsp; {{ $txt['select_all'] }}</th>
					<th>{{ $txt['product_name'] }}</th>
					<th>{{ $txt['in_warehouse_number'] }}</th>
					<th>{{ $txt['in_warehouse_date'] }}</th>
					<th>{{ $txt['in_warehouse_category'] }}</th>

					@if( !empty( $purchase_extra_column ) )
						@foreach($purchase_extra_column as $row)
							<th>{{ $txt[$row['name']] }}</th>
						@endforeach
					@endif

					<th>{{ $txt['number'] }}</th>
					<th>{{ $txt['status'] }}</th>
					<th>{{ $txt['action'] }}</th>
				</tr>							
			</thead>
			<tbody>
				@foreach($purchase_list as $row)
				<tr>
					<th>
						@if( $row['status'] == 1 )
						<input type="checkbox" class="puchase_checkbox" value="{{ $row['id'] }}" name="purchase_id[]">
						@endif
					</th>
					<th>{{ $row['product_name'] }}</th>
					<th>{{ $row['in_warehouse_number_txt'] }}</th>
					<th>{{ $row['in_warehouse_date'] }}</th>
					<th>{{ $row['in_warehouse_category_txt'] }}</th>

					@if( !empty( $purchase_extra_column ) )
						@foreach($purchase_extra_column as $row2)
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
						<input type="button" value="{{ $txt['edit'] }}" onClick="location.href='/purchase/{{ $row['id'] }}/edit?';"/>
						@endif
					</td>
				</tr>
				@endforeach													
			</tbody>
		</table>
		{{ $purchase_data->links() }}
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

	</form>	

</section>