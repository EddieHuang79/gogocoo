<section class="content-header">
	<h1>{{ $txt["stock_list"] }}</h1>
</section>
<section class="content">
	@include('webbase.search_tool')

	<table class="table table-stroped">
		<thead>
			<tr>
				<th>{{ $txt['product_name'] }}</th>
				@if($has_spec)
				<th>{{ $txt['product_spec'] }}</th>
				@endif
				<th>{{ $txt['in_warehouse_number'] }}</th>
				<th>{{ $txt['in_warehouse_date'] }}</th>
				<th>{{ $txt['deadline'] }}</th>
				<th>{{ $txt['stock'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@foreach($stock_list as $row)
			<tr>
				<th>{{ $row['product_name'] }}</th>
				@if($has_spec)
				<th>{{ $row['spec_data'] }}</th>
				@endif
				<th>{{ $row['in_warehouse_number'] }}</th>
				<th>{{ $row['in_warehouse_date'] }}</th>
				<th>{{ $row['deadline'] }}</th>
				<th>{{ $row['stock'] }}</th>
			</tr>
			@endforeach													
		</tbody>
	</table>
	{{ $stock_data->links() }}

</section>