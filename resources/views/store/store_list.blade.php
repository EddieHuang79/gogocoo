<section class="content-header">
	<h1>{{ $txt["store_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	<table class="table table-stroped">
		<thead>
			<tr>
				<th>{{ $txt['store_name'] }}</th>
				<th>{{ $txt['store_type'] }}</th>
				<th>{{ $txt['store_code'] }}</th>
				<th>{{ $txt['create_time'] }}</th>
				<th>{{ $txt['deadline'] }}</th>
				<th>{{ $txt['action'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@foreach($store as $row)
			<tr>
				<td>{{ $row->store_name }}</td>
				<td>{{ $row->store_type_name }}</td>
				<td>{{ $row->store_code }}</td>
				<td>{{ $row->created_at }}</td>
				<td>{{ $row->deadline }} 23:59:59</td>
				<td>
					<input type="button" value="{{ $txt['edit'] }}" onClick="location.href='/store/{{ $row->id }}/edit?';"/>
				</td>
			</tr>
			@endforeach													
		</tbody>
	</table>
</section>