<section class="content-header">
	<h1>{{ $txt["promo_price_setting_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<div>
						<input type="button" class="btn btn-primary" value="{{ $txt['add_promo_price'] }}" onclick="location.href='/promo/create?mall_shop_id={{ $id }}';"/>
						<input type="button" class="btn btn-primary" value="{{ $txt['back'] }}" onclick="location.href='/mall';"/>
					</div>
					<br />
					<h3 class="box-title">{{ $txt["promo_price_setting_list"] }}</h3>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>{{ $txt['promo_price'] }}</th>
								<th>{{ $txt['status'] }}</th>
								<th>{{ $txt['start_date'] }}</th>
								<th>{{ $txt['end_date'] }}</th>
								<th>{{ $txt['action'] }}</th>
							</tr>							
						</thead>
						<tbody>
							@if( !empty($promo) )
								@foreach($promo as $row)
								<tr>
									<td> {{ $txt["cost_unit"] }} {{ $row['cost'] }} </td>
									<td>{{ $row['status_txt'] }}</td>
									<td>{{ $row['start_date'] }}</td>
									<td>{{ $row['end_date'] }}</td>
									<td>
										<input type="button" class="btn btn-primary" value="{{ $txt['edit'] }}" onclick="location.href='/promo/{{ $row['id'] }}/edit';" />
									</td>
								</tr>
								@endforeach
							@else
								<tr>
									<td colspan="6"> {{ $txt['find_nothing'] }} </td>
								</tr>
							@endif								
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>