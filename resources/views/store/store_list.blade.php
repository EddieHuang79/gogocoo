<section class="content-header">
	<h1>{{ $txt["store_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	    <div class="row">
	        <div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">{{ $txt["store_list"] }}</h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>{{ $txt['store_name'] }}</th>
									<th>{{ $txt['store_type'] }}</th>
									<th>{{ $txt['store_code'] }}</th>
									<th>{{ $txt['create_time'] }}</th>
									<th>{{ $txt['deadline'] }}</th>
									<th>{{ $txt['action'] }}</th>
								</tr>							
							</thead>
							<tbody>
								@foreach($store as $row)
								<tr>
									<td>{{ $row->store_name }}</td>
									<td>{{ $row->store_type_name }}</td>
									<td>{{ $row->store_code }}</td>
									<td>{{ $row->created_at }}</td>
									<td>{{ $row->deadline }}</td>
									<td>
										<input type="button" class="btn btn-primary" value="{{ $txt['edit'] }}" onClick="location.href='/store/{{ $row->id }}/edit?';"/>
										<input type="button" class="btn btn-primary" value="{{ $txt['extend_deadline'] }}" class="extend_account_deadline" account="{{ $row->store_name }}" userId="{{ $row->id }}"/>				
									</td>
								</tr>
								@endforeach													
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<div class="lightbox extend_lightbox">
		<form method="POST" action="/extend_store_process" id="ShopForm">
			<label class="close_btn"> X </label>
			<div class="popup_title">{{ $txt['extend_deadline'] }}</div>
			<hr />
			<div class="popup_option">
				<div class="popup_option_left">
					{{ $txt['store_name'] }} <label class="account">  </label>
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