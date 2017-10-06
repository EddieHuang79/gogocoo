<section class="content-header">
	<h1>{{ $txt["admin_user_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
    <div class="row">
        <div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["admin_user_list"] }}</h3>
				</div>
				<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>{{ $txt['account'] }}</th>
								<th>{{ $txt['real_name'] }}</th>
								<th>{{ $txt['telephone'] }}</th>
								<th>{{ $txt['auth'] }}</th>
								<th>{{ $txt['status'] }}</th>
								<th>{{ $txt['deadline'] }}</th>
								<th>{{ $txt['action'] }}</th>
							</tr>							
						</thead>
						<tbody>
							@foreach($user as $row)
								<tr>
									<th>{{ $row->account }}</th>
									<td>{{ $row->real_name }}</td>
									<td>{{ $row->mobile }}</td>
									<td> @if( !empty($row->auth) ) @foreach($row->auth as $role) {{$role}} <br /> @endforeach  @endif </td>
									<td>{{ $active_to_text[$row->status] }}</td>
									<td>{{ $row->deadline }}</td>
									<td>
										<input type="button" class="btn btn-primary" value="{{ $txt['edit'] }}" onClick="location.href='/user/{{ $row->id }}/edit?';"/>
										<input type="button" class="btn btn-primary" value="{{ $txt['extend_deadline'] }}" class="extend_account_deadline" account="{{ $row->account }}" userId="{{ $row->id }}"/>
									</td>
								</tr>
							@endforeach													
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	{{ $user->links() }}

	<div class="lightbox extend_lightbox">
		<form method="POST" action="/extend_user_process" id="ShopForm">
			<label class="close_btn"> X </label>
			<div class="popup_title">{{ $txt['extend_deadline'] }}</div>
			<hr />
			<div class="popup_option">
				<div class="popup_option_left">
					{{ $txt['account'] }} <label class="account">  </label>
				</div>
				<div class="popup_option_right">
					{{ $txt['extend'] }}{{ $txt['deadline'] }}
					<select name="date_spec" required>
						<option value="">{{ $txt['select_default'] }}</option>
					</select>
				</div>
			</div>			
			<div class="mall_product_btn">
				<input type="hidden" name="user_id" value="">
				<input type="button" value="{{ $txt['send'] }}" onclick="ExtendSubmit();">
				<input type="button" value="{{ $txt['back'] }}" onclick="ClosePopup();">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
			</div>			
		</form>
	</div>


	<div class="lightbox shop_finish">
		<label class="close_btn"> X </label>
		<div class="subject">  </div>
		<hr>
		<div class="content">  </div>
	</div>

</section>