<section class="content-header">
	<h1>{{ $txt["stock_total_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	    <div class="row">
	        <div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">{{ $txt["stock_total_list"] }}</h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>{{ $txt['product_name'] }}</th>
									@if($has_spec)
									<th>{{ $txt['product_spec'] }}</th>
									@endif
									<th>{{ $txt['stock'] }}</th>
								</tr>							
							</thead>
							<tbody>
								@if( !empty($stock_list) )
									@foreach($stock_list as $row)
									<tr>
										<th>{{ $row['product_name'] }}</th>
										@if($has_spec)
										<th>{{ $row['spec_data'] }}</th>
										@endif
										<th>{{ $row['stock'] }}</th>
									</tr>
									@endforeach
								@else
									<tr>
										<th colspan="3">{{ $txt['find_nothing'] }}</th>
									</tr>
								@endif													
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	{{-- $stock_data->links() --}}

</section>