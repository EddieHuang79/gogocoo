<section class="content-header">
	<h1>{{ $txt["product_categeory_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["product_categeory_list"] }}</h3>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>{{ $txt['parents_category'] }}</th>
								<th>{{ $txt['product_category_name'] }}</th>
								<th>{{ $txt['action'] }}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($product_category_data as $row)
							<tr>
								<th>{{ $row->parents_name }}</th>
								<th>{{ $row->name }}</th>
								<td>
									@if($row->shop_id > 0)
										<input type="button" class="btn btn-primary" value="{{ $txt['edit'] }}" onClick="location.href='/product_category/{{ $row->id }}/edit?';"/>
									@else
										{{ $txt['system_default_category'] }}
									@endif
								</td>
							</tr>
							@endforeach													
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	{{ $product_category_data->links() }}
</section>