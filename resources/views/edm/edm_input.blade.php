<section class="content-header">
	<h1>{{ $txt["edm_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["edm_input"] }}</h3>
				</div>
				<form action="/edm" method="POST" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["edm_subject"] }}</label>
							@if(!empty($edm))
								<input type="hidden" name="edm_id" value="{{ $edm->id }}">						
							@endif
							<input type="text" name="subject" class="form-control" value="@if(!empty($edm)){{ $edm->subject }}@endif" required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["edm_content_type"] }}</label>
							<div class="radio">
								<label>	
									<input type="radio" value="1" name="type" column="edm_content" class="type" @if( !empty($edm) && $edm->type == 1 ) checked  @endif required/>{{ $txt["text"] }}
								</label>
							</div>								
							<div class="radio">
								<label>								
									<input type="radio" value="2" name="type" column="edm_image" class="type" @if( !empty($edm) && $edm->type == 2 ) checked  @endif required/>{{ $txt["image"] }}
								</label>
							</div>								
						</div>
						<div class="form-group chooseType edm_content">
							<label>{{ $txt["edm_content"] }}</label>
							<textarea name="content" class="textarea form-control">@if( !empty($edm) && $edm->type == 1 ){{ $edm->content }}@endif</textarea>
						</div>
						<div class="form-group chooseType edm_image">
							<label>{{ $txt["edm_image"] }}</label>
							<input type="file" name="edm_image"> <br />
							@if( !empty($edm->content) && $edm->type == 2 )
							<img src="{{ $site }}/{{ $edm->content }}" height="100" />
							@endif
						</div>
						<div class="form-group">
							<label>{{ $txt["upload_mail_list"] }}</label>
							<input type="file" name="edm_list"> <br />
							<input type="button" value="{{ $txt['downaload_example'] }}" class="btn btn-primary" onclick="location.href='/edm_example';" >
						</div>
						<div class="form-group">
							<label>{{ $txt["edm_send_time"] }}</label>
							<input type="text" id="start_datetime" name="send_time" class="form-control" value="@if(!empty($edm)){{ $edm->send_time }}@endif" required/>
						</div>						
						<div class="form-group">
							<th colspan="2"><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>															
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>