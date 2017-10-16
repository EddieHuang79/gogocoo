<section class="content-header">
    @if(!empty($ErrorMsg))
    	@foreach($ErrorMsg as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @endif
	<h1>{{ $txt["admin_user_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">

	@if( !isset($account_status["left"]) || $account_status["left"] > 0 )
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["admin_user_input"] }}</h3>
				</div>
				<form action="/user" method="POST" role="form">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["account"] }}</label>
							@if(!empty($user))
								{{ $user->account }}
							<input type="hidden" name="user_id" value="{{ $user->id }}">
							@else
								<input type="text" name="account" class="form-control" @if( !empty($OriData) ) value="{{ $OriData['account'] }}" @endif placeholder="{{ $txt['account_input'] }}" size="40" required/>
							@endif
						</div>
						<div class="form-group">
							<label>{{ $txt["password"] }}</label>
							<input type="password" name="password" class="form-control" value="" placeholder="{{ $txt['password_input'] }}" size="40"/>
						</div>
						<div class="form-group">
							<label>{{ $txt["real_name"] }}</label>
							<input type="text" name="real_name" class="form-control" @if(!empty($user)) value="{{ $user->real_name }}" @endif @if( empty($user) && !empty($OriData['real_name']) ) value="{{ $OriData['real_name'] }}" @endif placeholder="{{ $txt['realname_input'] }}" size="40" required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["telephone"] }}</label>
							<input type="text" name="mobile" class="form-control" @if(!empty($user)) value="{{ $user->mobile }}" @endif @if( empty($user) && !empty($OriData['mobile']) ) value="{{ $OriData['mobile'] }}" @endif placeholder="{{ $txt['phone_input'] }}" size="40" required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["auth"] }}</label>
						</div>
						<div class="checkbox">
							<label>				
								@foreach($role_list as $row)
								<input type="checkbox" value="{{ $row->id }}" name="auth[]" @if( isset($user_role) && in_array( $row->id, $user_role ) ) checked @endif )/> {{ $row->name }} <br />
								@endforeach	
							</label>
						</div>
						<div class="form-group">
							<label>{{ $txt["status"] }}</label>
						</div>
						<div class="form-group">
							<div class="radio">
								<label>
									<input type="radio" value="1" name="active" @if( !empty($user) && $user->status == 1 ) checked  @endif required/>{{ $txt["enable"] }}									
								</label>
							</div>
							<div class="radio">
								<label>								
									<input type="radio" value="2" name="active" @if( !empty($user) && $user->status == 2 ) checked  @endif required/>{{ $txt["disable"] }}
								</label>
							</div>
						</div>
						@if( empty($user) )
						<div class="form-group">
							<label>{{ $txt["deadline"] }}</label>
							@if( isset($account_status["free"]) && $account_status["free"] > 0 )
								<br />
								{{ $txt['free_date_spec_desc'] }} {{ $deadline }}
							@endif
							@if( isset($account_status["free"]) && $account_status["free"] <= 0 )
								<select name="date_spec" class="form-control" required>
									<option value="">{{ $txt['select_default'] }}</option>
									@foreach($deadline as $index => $date_spec)
										<option value="{{ $index }}">{{ $date_spec }}</option>
									@endforeach
								</select>
							@endif
						</div>
						@endif
						<div class="form-group">
							<label><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>															
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
		        	</div>
				</form>
	      	</div>
        </div>
  	</div>

	@else

	<div class="lightbox">
		<div class="subject">{{ $txt["create_account_disable"] }} <label class="close_btn"> X </label> </div>
		<hr>
		<div class="content">
			{{ $txt["create_account_disable_desc"] }} 
			<br />
			<br />
			<br />
			<button class="btn btn-lg btn-primary btn-block" type="button" onclick="location.href='/buy';">{{ $txt['go_to_store'] }}</button>
		</div>
	</div>

	@endif

</section>