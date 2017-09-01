<section class="content-header">
	<h1>{{ $txt["service_buy_record"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	<table class="table table-stroped">
		<thead>
			<tr>
				<th>{{ $txt['product_name'] }}</th>
				<th>{{ $txt['product_spec'] }}</th>
				<th>{{ $txt['number'] }}</th>
				<th>{{ $txt['deadline'] }}</th>
				<th>{{ $txt['paid_at'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@if(!empty($shop_record))
				@foreach($shop_record as $row)
					<tr>
						<th>{{ $row->product_name }}</th>
						<th>{{ $txt["cost_unit"] }}{{ $row->cost }}/{{ $row->date_spec }}{{ $txt["day_unit"] }}</th>
						<th>{{ $row->number }}</th>
						<th>{{ $row->deadline }}</th>
						<th>{{ $row->paid_at }}</th>
					</tr>
				@endforeach
			@else
					<tr>
						<th colspan="2">{{ $txt['find_nothing'] }}</th>
					</tr>			
			@endif											
		</tbody>
	</table>
	@if(!empty($shop_record))
		{{ $shop_record->links() }}
	@endif
</section>