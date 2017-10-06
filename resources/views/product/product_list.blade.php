<section class="content-header">
	<h1>{{ $txt["product_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["product_list"] }}</h3>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>{{ $txt['product_name'] }}</th>

								@if( !empty( $product_extra_column ) )
								
								@foreach($product_extra_column as $row)
								<th>{{ $txt[$row['name']] }}</th>
								@endforeach

								@endif				

								<th>{{ $txt['safe_amount'] }}</th>
								<th>{{ $txt['action'] }}</th>
							</tr>							
						</thead>
						<tbody>
							@foreach($product_list as $row)
							<tr>
								<th>{{ $row['product_name'] }}</th>

								@foreach($product_extra_column as $data)
								<th>
									
									@if( $data['name']=='pic' && !empty($row[$data['name']]) )
									<img src="/{{ $row[$data['name']] }}" alt="{{ $data['name'] }}" width="200">
									@else
										@if( isset($row[$data['name']."_txt"]) )
										{{ $row[$data['name']."_txt"] }}
										@else
										{{ $row[$data['name']] }}
										@endif					
									@endif

								</th>
								@endforeach

								<th>{{ $row['safe_amount'] }}</th>
								<td><input type="button" class="btn btn-primary" value="{{ $txt['edit'] }}" onClick="location.href='/product/{{ $row['product_id'] }}/edit?';"/></td>
							</tr>
							@endforeach													
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	{{ $product_data->links() }}
</section>