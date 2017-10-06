<section class="content-header">
	<h1>{{ $txt["order_upload"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["order_upload"] }}</h3>
				</div>
				<form action="/order_upload_process" method="POST" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["order_upload_format_download"] }}</label>
							<input type="button" name="order_upload_format_download" value="{{ $txt['download'] }}" onclick="location.href='/order_upload_format_download';"/>
						</div>	
						<div class="form-group">
							<label>{{ $txt["order_upload"] }}</label>
							<input type="file" name="user_order_upload"/>
						</div>	
						<div class="form-group">
							<label><input type="submit" class="btn btn-primary" value="{{ $txt['upload'] }}"/></label>
						</div>								
						<input type="hidden" name="_token" value="{{ csrf_token() }}">													
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>