<section class="content-header">
	<h1>{{ $txt["purchase_upload"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["purchase_upload"] }}</h3>
				</div>
				<form action="/purchase_upload_process" method="POST" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["purchase_upload_format_download"] }}</label>
							<input type="button" name="purchase_upload_format_download" value="{{ $txt['download'] }}" onclick="location.href='/purchase_upload_format_download';"/>
						</div>	
						<div class="form-group">
							<label>{{ $txt["purchase_upload"] }}</label>
							<input type="file" name="user_purchase_upload"/>
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