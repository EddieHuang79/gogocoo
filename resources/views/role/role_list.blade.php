<section class="content-header">
	<h1>{{ $txt["role_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["role_list"] }}</h3>
					<i class="fa fa-search"></i>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-striped">
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
								<td><input type="button" class="btn btn-primary" value="{{ $txt['edit'] }}" onClick="location.href='/role/{{ $row->id }}/edit?';"/></td>
							</tr>
							@endforeach													
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	{{ $role->links() }}
</section>