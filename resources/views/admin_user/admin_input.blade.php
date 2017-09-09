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

	<form action="/user" method="POST">
		<table class="table table-stroped">
			<tbody>
				<tr>
					<th>{{ $txt["account"] }}</th>
					<td>
						@if(!empty($user))
						{{ $user->account }}
						<input type="hidden" name="user_id" value="{{ $user->id }}">
						@else
						<input type="text" name="account" value="" placeholder="{{ $txt['account_input'] }}" size="40" required/>
						@endif</td>
				</tr>
				<tr>
					<th>{{ $txt["password"] }}</th>
					<td><input type="password" name="password" value="" placeholder="{{ $txt['password_input'] }}" size="40"/></td>
				</tr>
				<tr>
					<th>{{ $txt["real_name"] }}</th>
					<td><input type="text" name="real_name" value="@if(!empty($user->real_name)){{ $user->real_name }}@endif" placeholder="{{ $txt['realname_input'] }}" size="40" required/></td>
				</tr>
				<tr>
					<th>{{ $txt["telephone"] }}</th>
					<td><input type="text" name="mobile" value="@if(!empty($user->mobile)){{ $user->mobile }}@endif" placeholder="{{ $txt['phone_input'] }}" size="40" required/></td>
				</tr>
				<tr>
					<th>{{ $txt["auth"] }}</th>
					<td>
						@foreach($role_list as $row)
						<input type="checkbox" value="{{ $row->id }}" name="auth[]" @if( isset($user_role) && in_array( $row->id, $user_role ) ) checked @endif )/> {{ $row->name }} <br />
						@endforeach	
					</td>
				</tr>
				<tr>
					<th>{{ $txt["status"] }}</th>
					<td>
						<input type="radio" value="1" name="active" @if( !empty($user) && $user->status == 1 ) checked  @endif required/>{{ $txt["enable"] }}
						<input type="radio" value="2" name="active" @if( !empty($user) && $user->status == 2 ) checked  @endif required/>{{ $txt["disable"] }}
					</td>
				</tr>
				@if( empty($user) )
				<tr>
					<th>{{ $txt["deadline"] }}</th>
					<td>
						
						@if( isset($account_status["free"]) && $account_status["free"] > 0 )
							{{ $txt['free_date_spec_desc'] }} {{ $deadline }}
						@endif

						@if( isset($account_status["free"]) && $account_status["free"] <= 0 )
							<select name="date_spec" required>
								<option value="">{{ $txt['select_default'] }}</option>
								@foreach($deadline as $index => $date_spec)
									<option value="{{ $index }}">{{ $date_spec }}</option>
								@endforeach
							</select>
						@endif

					</td>
				</tr>
				@endif
				<tr>
					<th colspan="2"><input type="submit" value="{{ $txt['send'] }}"/></th>
				</tr>															
			</tbody>
		</table>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>

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