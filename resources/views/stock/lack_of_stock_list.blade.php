<section class="content-header">
	<h1>{{ $txt["lack_stock_list"] }}</h1>
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
				<th>{{ $txt['safe_amount'] }}</th>
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
				<th>{{ $row['safe_amount'] }}</th>
				<th>{{ $row['stock'] }}</th>
			</tr>
			@endforeach													
		</tbody>
	</table>
	{{-- $stock_data->links() --}}

</section>