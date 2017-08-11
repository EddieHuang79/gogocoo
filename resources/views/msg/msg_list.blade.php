<section class="content-header">
	<h1>{{ $txt["msg_list"] }}</h1>
</section>
<section class="content">
	@include('webbase.search_tool')
	<table class="table table-stroped">
		<thead>
			<tr>
				<th>{{ $txt['id'] }}</th>
				<th>{{ $txt['subject'] }}</th>
				<th>{{ $txt['content'] }}</th>
				<th>{{ $txt['notice_role'] }}</th>
				<th>{{ $txt['show_type'] }}</th>
				<th>{{ $txt['msg_type'] }}</th>
				<th>{{ $txt['is_public'] }}</th>
				<th>{{ $txt['start_date'] }}</th>
				<th>{{ $txt['end_date'] }}</th>
				<th>{{ $txt['create_time'] }}</th>
				<th>{{ $txt['update_time'] }}</th>
				<th>{{ $txt['action'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@foreach($msg as $row)
			<tr>
				<td>{{ $row->id }}</td>
				<td>{{ $row->subject }}</td>
				<td>{{ $row->content }}</td>
				<td>{{ $row->role_id }}</td>
				<td>{{ $row->show_type }}</td>
				<td>{{ $row->msg_type }}</td>
				<td>{{ $row->public_txt }}</td>
				<td>{{ $row->start_date }}</td>
				<td>{{ $row->end_date }}</td>
				<td>{{ $row->created_at }}</td>
				<td>{{ $row->updated_at }}</td>
				<td>
					@if( $row->public == 0 )
					<input type="button" value="{{ $txt['edit'] }}" onClick="location.href='/msg/{{ $row->id }}/edit?';"/>
					@endif
					<input type="button" value="{{ $txt['clone'] }}" onClick="location.href='/msg_clone?msg_id={{ $row->id }}';"/>
				</td>
			</tr>
			@endforeach													
		</tbody>
	</table>
	{{ $msg->links() }}
</section>