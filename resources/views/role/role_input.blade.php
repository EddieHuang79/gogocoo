<section class="content-header">
	<h1>{{ $txt["role_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["role_input"] }}</h3>
				</div>
				<form action="/role" method="POST">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["role_name"] }}</label>
							@if(!empty($role))
							<input type="hidden" name="role_id" value="{{ $role->id }}">						
							@endif
							<input type="text" name="name" class="form-control" value="@if(!empty($role)){{ $role->name }}@endif" required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["auth"] }}</label>
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
						</div>
						<div class="form-group">
							<label>{{ $txt["status"] }}</label>
							<div class="radio">
								<label>							
									<input type="radio" value="1" name="active" @if( !empty($role) && $role->status == 1 ) checked  @endif required/>{{ $txt["enable"] }}
								</label>
							</div>							
							<div class="radio">
								<label>							
									<input type="radio" value="2" name="active" @if( !empty($role) && $role->status == 2 ) checked  @endif required/>{{ $txt["disable"] }}
								</label>
							</div>						
						</div>
						<div class="form-group">
							<th colspan="2"><input type="submit" class="btn btn-primary"  value="{{ $txt['send'] }}"/></label>
						</div>															
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>