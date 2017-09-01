<section class="content-header">
	<h1>{{ $txt["mall_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	<table class="table table-stroped">
		<thead>
			<tr>
				<th>{{ $txt['id'] }}</th>
				<th>{{ $txt['product_name'] }}</th>
				<th>{{ $txt['product_image'] }}</th>
				<th>{{ $txt['product_spec'] }}</th>
				<th>{{ $txt['include_service'] }}</th>
				<th>{{ $txt['public'] }}</th>
				<th>{{ $txt['start_date_input'] }}</th>
				<th>{{ $txt['end_date_input'] }}</th>
				<th>{{ $txt['action'] }}</th>
			</tr>							
		</thead>
		<tbody>
			@foreach($mall as $row)
			<tr>
				<td>{{ $row->id }}</td>
				<td>{{ $row->product_name }}</td>
				<td>
					@if(!empty($row->pic))
						<img src="{{ URL::asset($row->pic) }}" alt="" width="100">
					@endif
				</td>
				<td>
					@foreach($row->spec as $spec)
					<div>{{ $spec }}</div>
					@endforeach	
				</td>
				<td>
					@if( !empty($row->include_service) )
						@foreach($row->include_service as $service)
						<div>{{ $service }}</div>
						@endforeach	
					@endif					
				</td>
				<td>{{ $row->public_txt }}</td>
				<td>{{ $row->start_date_desc }}</td>
				<td>{{ $row->end_date_desc }}</td>
				<td>
					<input type="button" value="{{ $txt['edit'] }}" onClick="location.href='/mall/{{ $row->id }}/edit?';"/>
				</td>
			</tr>
			@endforeach													
		</tbody>
	</table>
</section>