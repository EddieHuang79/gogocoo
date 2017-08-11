<section class="content-header">
	<h1>{{ $txt["service_public"] }}</h1>
</section>
<section class="content">
	@include('webbase.search_tool')
	<table class="table table-stroped">
		<thead>
			<tr>
				<th>{{ $txt['service_name'] }}</th>
				<th>{{ $txt['action'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@if(!empty($service))
				@foreach($service as $row)
					<tr>
						<th>{{ $row->name }}</th>
						<td>{{ $public_txt[$row->public] }}</td>
						<td>@if($row->public == 2)<input type="button" value="{{ $txt['public'] }}" onClick="location.href='/service_public_process?service_id={{ $row->id }}';"/>@endif</td>
					</tr>
				@endforeach
			@else
					<tr>
						<th colspan="3">{{ $txt['find_nothing'] }}</th>
					</tr>			
			@endif											
		</tbody>
	</table>
	{{ $service->links() }}
</section>