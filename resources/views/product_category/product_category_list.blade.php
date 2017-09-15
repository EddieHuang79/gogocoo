<section class="content-header">
	<h1>{{ $txt["product_categeory_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	<table class="table table-stroped">
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
						<input type="button" value="{{ $txt['edit'] }}" onClick="location.href='/product_category/{{ $row->id }}/edit?';"/>
					@else
						{{ $txt['system_default_category'] }}
					@endif
				</td>
			</tr>
			@endforeach													
		</tbody>
	</table>
	{{ $product_category_data->links() }}
</section>