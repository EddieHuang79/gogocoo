<section class="content-header">
	<h1>{{ $txt["role_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<form action="/role" method="POST">
		<table class="table table-stroped">
			<tbody>
				<tr>
					<th width="10%">{{ $txt["role_name"] }}</th>
					<td>
						@if(!empty($role))
						<input type="hidden" name="role_id" value="{{ $role->id }}">						
						@endif
						<input type="text" name="name" value="@if(!empty($role)){{ $role->name }}@endif"/>
					</td>
				</tr>
				<tr>
					<th>{{ $txt["auth"] }}</th>
					<td class="auth-td">
						@foreach($menu_list as $row)
						<div class="auth-div">
							<label class="RoleListBtn">+</label>
							<input type="checkbox" value="{{ $row['id'] }}" alt="main" group="auth{{ $row['id'] }}" name="auth[]" @if( !empty($role_service) && in_array($row['id'], $role_service) ) checked  @endif >{{ $row['name'] }}
							<ul class="auth">
								@foreach($row['child'] as $child)
								<li><input type="checkbox" value="{{ $child['id'] }}" group="auth{{ $row['id'] }}" name="auth[]" @if( !empty($role_service) && in_array($child['id'], $role_service) ) checked  @endif >{{ $child['name'] }}</li>
								@endforeach
							</ul>
						</div>
						@endforeach
					</td>
				</tr>
				<tr>
					<th>{{ $txt["status"] }}</th>
					<td>
						<input type="radio" value="1" name="active" @if( !empty($role) && $role->status == 1 ) checked  @endif required/>{{ $txt["enable"] }}
						<input type="radio" value="2" name="active" @if( !empty($role) && $role->status == 2 ) checked  @endif required/>{{ $txt["disable"] }}
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