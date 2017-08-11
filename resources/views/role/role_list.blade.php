<section class="content-header">
	<h1>{{ $txt["role_list"] }}</h1>
</section>
<section class="content">
	@include('webbase.search_tool')
	<table class="table table-stroped">
		<thead>
			<tr>
				<th>{{ $txt['role_name'] }}</th>
				<th>{{ $txt['status'] }}</th>
				<th>{{ $txt['action'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@foreach($role as $row)
			<tr>
				<th>{{ $row->name }}</th>
				<td>{{ $active_to_text[$row->status] }}</td>
				<td><input type="button" value="{{ $txt['edit'] }}" onClick="location.href='/role/{{ $row->id }}/edit?';"/></td>
			</tr>
			@endforeach													
		</tbody>
	</table>
	{{ $role->links() }}
</section>