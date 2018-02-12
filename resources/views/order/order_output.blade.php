<section class="content-header">
	<h1>{{ $txt["order_export"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')

	<form action="/order_output_process" method="POST">
	    <div class="row">
	        <div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">
							<input type="submit" name="verify" value="{{ $txt['export_format1'] }}" class="btn btn-primary">
						</h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th><input type="checkbox" class="clickAll" target="order_checkbox"> &nbsp; {{ $txt['select_all'] }}</th>
									<th>{{ $txt['order_number'] }}</th>
									<th>{{ $txt['product_name'] }}</th>
									<th>{{ $txt['out_warehouse_date'] }}</th>
									<th>{{ $txt['out_warehouse_category'] }}</th>

									@if( !empty( $order_extra_column ) )
										@foreach($order_extra_column as $row)
											@if( $row['show_on_page'] === true )
											<th>{{ $txt[$row['name']] }}</th>
											@endif
										@endforeach
									@endif

									<th>{{ $txt['number'] }}</th>
									<th>{{ $txt['status'] }}</th>
								</tr>							
							</thead>
							<tbody>
								@if( !empty($order_list) )
									@foreach($order_list as $row)
									<tr>
										<th>
											<input type="checkbox" class="order_checkbox" value="{{ $row['id'] }}" name="order_id[]">
										</th>
										<th>{{ $row['order_number_txt'] }}</th>
										<th>{{ $row['product_name'] }}</th>
										<th>{{ $row['out_warehouse_date'] }}</th>
										<th>{{ $row['out_warehouse_category_txt'] }}</th>

										@if( !empty( $order_extra_column ) )
											@foreach($order_extra_column as $row2)
												@if( $row2['show_on_page'] === true )
												<th>
													@if( isset($row[$row2['name']."_txt"]) )
													{{ $row[$row2['name']."_txt"] }}
													@else
													{{ $row[$row2['name']] }}
													@endif
												</th>
												@endif
											@endforeach
										@endif
										
										<th>{{ $row['number'] }}</th>
										<th>{{ $row['status_txt'] }}</th>
									</tr>
									@endforeach
								@else
									<tr>
										<th colspan="7">{{ $txt['find_nothing'] }}</th>
									</tr>
								@endif													
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		{{ $order_data->links() }}
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

	</form>	

</section>