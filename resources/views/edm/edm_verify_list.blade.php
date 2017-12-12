<section class="content-header">
	<h1>{{ $txt["edm_verify_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	<form action="/edm_verify" method="POST">
	    <div class="row">
	        <div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<input type="submit" name="verify" value="{{ $txt['verify'] }}" class="btn btn-primary">
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th><input type="checkbox" class="clickAll" target="edm_checkbox"> &nbsp; {{ $txt['select_all'] }}</th>
									<th>{{ $txt['subject'] }}</th>
									<th>{{ $txt['status'] }}</th>
									<th>{{ $txt['has_list'] }} <br /> {{ $txt["edm_verify_rule"] }} </th>
									<th>{{ $txt['edm_send_time'] }}</th>
									<th>{{ $txt['update_time'] }}</th>
								</tr>							
							</thead>
							<tbody>
								@foreach($edm as $row)
								<tr>
									<td>
										@if( $row->status == 1 && $row->has_list === true )
										<input type="checkbox" class="edm_checkbox" value="{{ $row->id }}" name="edm_id[]">
										@endif					
									</td>
									<td>{{ $row->subject }}</td>
									<td>{{ $row->status_txt }}</td>
									<td>{{ $row->has_list_txt }}</td>
									<td>{{ $row->send_time }}</td>
									<td>{{ $row->updated_at }}</td>
								</tr>
								@endforeach													
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
	{{ $edm->links() }}
</section>