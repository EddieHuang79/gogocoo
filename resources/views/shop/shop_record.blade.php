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
				<th>{{ $txt['include_service'] }}</th>
				<th>{{ $txt['number'] }}</th>
				<th>{{ $txt['price'] }}</th>
				<th>{{ $txt['total'] }}</th>
				<th>{{ $txt['paid_at'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@if(!empty($shop_record))
				@foreach($shop_record['result'] as $row)
					<tr>
						<th>{{ $row['mall_product_name'] }}</th>
						<th>
							@if( !empty($row['include_service']) )
								@foreach($row['include_service'] as $service)
								<div>{{ $service["product_name"] }} / {{ $service["number"] }}{{ $txt['service_unit'] }} / {{ $service["date_spec"] }}{{ $txt['day_unit'] }}</div>
								@endforeach	
							@endif								
						</th>
						<th>{{ $row['number'] }}</th>
						<th>{{ $txt["cost_unit"] }}{{ $row['cost'] }}</th>
						<th>{{ $txt["cost_unit"] }}{{ $row['total']  }}</th>
						<th>{{ $row['paid_at'] }}</th>
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
		{{ $shop_record['data']->links() }}
	@endif
</section>