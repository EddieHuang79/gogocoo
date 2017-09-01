<div class="form-signin position-absolute-center">
	<h2 class="form-signin-heading">{{ $txt['register_finish'] }}</h2>
	<table class="table table-stroped">
		<tbody>
			<tr>
				<th>{{ $txt["account"] }}</th>
				<td>{{ $Register_user["account"] }}</td>
			</tr>
			<tr>
				<th>{{ $txt["store_name"] }}</th>
				<td>{{ $Register_user["real_name"] }}</td>
			</tr>
			<tr>
				<th>{{ $txt["store_code"] }}</th>
				<td>{{ $Register_user["StoreCode"] }}</td>
			</tr>
			<tr>
				<th>{{ $txt["mobile"] }}</th>
				<td>{{ $Register_user["mobile"] }}</td>
			</tr>												
		</tbody>
	</table>	
	<button class="btn btn-lg btn-primary btn-block" onclick="location.href='/admin_index';">{{ $txt['enter_website'] }}</button>
</div>