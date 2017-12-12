<section class="content-header">
	<h1>{{ $txt["mall_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["mall_list"] }}</h3>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>{{ $txt['id'] }}</th>
								<th>{{ $txt['product_name'] }}</th>
								<th>{{ $txt['product_image'] }}</th>
								<th>{{ $txt['price'] }}</th>
								<th>{{ $txt['include_service'] }}/ {{ $txt['number'] }} /{{ $txt['day_unit'] }}</th>
								<th>{{ $txt['public'] }}</th>
								<th>{{ $txt['start_date_input'] }}</th>
								<th>{{ $txt['end_date_input'] }}</th>
								<th>{{ $txt['action'] }}</th>
							</tr>							
						</thead>
						<tbody>
							@foreach($mall as $row)
							<tr>
								<td>{{ $row->id }}</td>
								<td>{{ $row->product_name }}</td>
								<td>
									@if(!empty($row->pic))
										<img src="{{ URL::asset($row->pic) }}" alt="" width="100">
									@endif
								</td>
								<td> {{ $txt["cost_unit"] }} {{ $row->cost }} </td>
								<td>
									@if( !empty($row->include_service) )
										@foreach($row->include_service as $service)
										<div>{{ $service["product_name"] }} / {{ $service["number"] }}{{ $txt['service_unit'] }} / {{ $service["date_spec"] }}{{ $txt['day_unit'] }}</div>
										@endforeach	
									@endif					
								</td>
								<td>{{ $row->public_txt }}</td>
								<td>{{ $row->start_date_desc }}</td>
								<td>{{ $row->end_date_desc }}</td>
								<td>
									<input type="button" class="btn btn-primary" value="{{ $txt['edit'] }}" onClick="location.href='/mall/{{ $row->id }}/edit?';"/>
									<input type="button" class="btn btn-primary" value="{{ $txt['promo_price_setting'] }}" onClick="location.href='/promo?mall_shop_id={{ $row->id }}';"/>
								</td>
							</tr>
							@endforeach													
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>