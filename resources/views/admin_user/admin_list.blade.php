<section class="content-header">
	<h1>{{ $txt["admin_user_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	<table class="table table-stroped">
		<thead>
			<tr>
				<th>{{ $txt['account'] }}</th>
				<th>{{ $txt['real_name'] }}</th>
				<th>{{ $txt['telephone'] }}</th>
				<th>{{ $txt['auth'] }}</th>
				<th>{{ $txt['status'] }}</th>
				<th>{{ $txt['action'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@foreach($user as $row)
				<tr>
					<th>{{ $row->account }}</th>
					<td>{{ $row->real_name }}</td>
					<td>{{ $row->mobile }}</td>
					<td> @if( !empty($row->auth) ) @foreach($row->auth as $role) {{$role}} <br /> @endforeach  @endif </td>
					<td>{{ $active_to_text[$row->status] }}</td>
					<td><input type="button" value="{{ $txt['edit'] }}" onClick="location.href='/user/{{ $row->id }}/edit?';"/></td>
				</tr>
			@endforeach													
		</tbody>
	</table>
	{{ $user->links() }}
</section>