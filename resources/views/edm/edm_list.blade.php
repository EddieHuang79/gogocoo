<section class="content-header">
	<h1>{{ $txt["edm_data_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["edm_data_list"] }}</h3>
					<i class="fa fa-search"></i>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>{{ $txt['id'] }}</th>
								<th>{{ $txt['subject'] }}</th>
								<th>{{ $txt['content'] }}</th>
								<th>{{ $txt['status'] }}</th>
								<th>{{ $txt['has_list'] }}</th>
								<th>{{ $txt['edm_send_time'] }}</th>
								<th>{{ $txt['update_time'] }}</th>
								<th>{{ $txt['action'] }}</th>
							</tr>							
						</thead>
						<tbody>
							@foreach($edm as $row)
							<tr>
								<td>{{ $row->id }}</td>
								<td>{{ $row->subject }}</td>
								<td>
									@if( $row->type == 2 && !empty($row->content) )
									<img src="{{ $row->content }}" alt="" height="100">
									@else
									{{ $row->content }}
									@endif
								</td>
								<td>{{ $row->status_txt }}</td>
								<td>{{ $row->has_list_txt }}</td>
								<td>{{ $row->send_time }}</td>
								<td>{{ $row->updated_at }}</td>
								<td>
									@if( $row->status == 1 )
									<input type="button" class="btn btn-primary" value="{{ $txt['edit'] }}" onClick="location.href='/edm/{{ $row->id }}/edit?';"/>
									@endif
									<input type="button" class="btn btn-primary" value="{{ $txt['clone'] }}" onClick="location.href='/edm_clone?edm_id={{ $row->id }}';"/>
								</td>
							</tr>
							@endforeach													
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	{{ $edm->links() }}
</section>