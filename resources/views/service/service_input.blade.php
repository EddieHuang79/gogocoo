<section class="content-header">
	<h1>{{ $txt["service_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<form action="/service" method="POST">
		<table class="table table-stroped">
			<tbody>
				<tr>
					<th>{{ $txt["service_name"] }}</th>
					<td>
						@if(!empty($service))
						<input type="hidden" name="service_id" value="{{ $service->id }}">						
						@endif
						<input type="text" name="name" value="@if(!empty($service)){{ $service->name }}@endif"/>
					</td>
				</tr>
				<tr>
					<th>{{ $txt["link"] }}</th>
					<td><input type="text" name="link" value="@if(!empty($service->link)){{ $service->link }}@endif"/></td>
				</tr>
				<tr>
					<th>{{ $txt["parents_service"] }}</th>
					<td>
						<select name="parents_id">
							<option value="">{{ $txt["select_default"] }}</option>
							@foreach($parents_service as $row)
							<option value="{{ $row->id }}" @if( !empty($service) && $service->parents_id == $row->id) selected @endif >{{ $row->name }}</option>
							@endforeach	
						</select>
					</td>
				</tr>
				<tr>
					<th>{{ $txt["auth"] }}</th>
					<td>
						@foreach($role_list as $row)
						<input type="checkbox" value="{{ $row->id }}" name="auth[]" @if( isset($role_service[$row->id])) checked @endif ) /> {{ $row->name }} <br />
						@endforeach	
					</td>
				</tr>
				<tr>
					<th>{{ $txt["status"] }}</th>
					<td>
						<input type="radio" value="1" name="active" @if( !empty($service) && $service->status == 1 ) checked  @endif />{{ $txt["enable"] }}
						<input type="radio" value="2" name="active" @if( !empty($service) && $service->status == 2 ) checked  @endif />{{ $txt["disable"] }}
					</td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" value="{{ $txt['send'] }}"/></th>
				</tr>															
			</tbody>
		</table>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</section>