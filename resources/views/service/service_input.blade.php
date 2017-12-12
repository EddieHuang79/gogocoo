<section class="content-header">
	<h1>{{ $txt["service_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["service_input"] }}</h3>
				</div>
				<form @if( isset($service->id) ) action="/service/{{ $service->id }}" @else action="/service" @endif method="POST">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["service_name"] }}</label>
							<input type="text" name="name" class="form-control" value="@if(!empty($service)){{ $service->name }}@endif"/>
						</div>
						<div class="form-group">
							<label>{{ $txt["link"] }}</label>
							<input type="text" name="link" class="form-control" value="@if(!empty($service->link)){{ $service->link }}@endif"/>
						</div>
						<div class="form-group">
							<label>{{ $txt["parents_service"] }}</label>
							<select name="parents_id" class="form-control">
								<option value="">{{ $txt["select_default"] }}</option>
								@foreach($parents_service as $row)
								<option value="{{ $row->id }}" @if( !empty($service) && $service->parents_id == $row->id) selected @endif >{{ $row->name }}</option>
								@endforeach	
							</select>
						</div>
						<div class="form-group">
							<label>{{ $txt["auth"] }}</label>
						</div>					
						<div class="form-group">
							@foreach($role_list as $row)
	                   		<div class="checkbox">
		                        <label>		
									<input type="checkbox" value="{{ $row->id }}" name="auth[]" @if( isset($role_service[$row->id])) checked @endif ) /> {{ $row->name }} <br />
								</label>
							</div>							
							@endforeach	
						</div>
						<div class="form-group">
							<label>{{ $txt["status"] }}</label>
							<div class="radio">
								<label>	
									<input type="radio" value="1" name="active" @if( !empty($service) && $service->status == 1 ) checked  @endif />{{ $txt["enable"] }}
								</label>
							</div>								
							<div class="radio">
								<label>							
									<input type="radio" value="2" name="active" @if( !empty($service) && $service->status == 2 ) checked  @endif />{{ $txt["disable"] }}
								</label>
							</div>						
						</div>
						<div class="form-group">
							<th colspan="2"><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>
						@if(!empty($service))
							<input type="hidden" name="_method" value="patch">				
						@endif											
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>