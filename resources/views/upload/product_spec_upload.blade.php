<section class="content-header">
	<h1>{{ $txt["product_spec_upload"] }}</h1>
</section>
<section class="content">
	<form action="/product_spec_upload_process" method="POST" enctype="multipart/form-data">
		<table class="table table-stroped">
			<tbody>
				<tr>
					<th width="10%">{{ $txt["product_spec_upload_format_download"] }}</th>
					<td colspan="2"><input type="button" name="product_spec_upload_format_download" value="{{ $txt['download'] }}" onclick="location.href='/product_spec_upload_format_download';"/></td>
				</tr>	
				<tr>
					<th width="10%">{{ $txt["product_spec_upload"] }}</th>
					<td width="10%"><input type="file" name="user_product_spec_upload"/></td>
					<td><input type="submit" value="{{ $txt['upload'] }}"/></td>
				</tr>													
			</tbody>
		</table>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</section>