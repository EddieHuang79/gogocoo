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
				<form @if( isset($edm->id) ) action="/edm/{{ $edm->id }}" @else action="/edm" @endif method="POST" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["edm_send_time"] }}</label>
							<input type="text" id="start_datetime" name="send_time" class="form-control" value="@if(!empty($edm)){{ $edm->send_time }}@endif" required/>
						</div>	
						<div class="form-group">
							<label>{{ $txt["edm_subject"] }}</label>
							<input type="text" name="subject" class="form-control" @if(!empty($edm))value="{{ $edm->subject }}"@endif required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["edm_content_type"] }}</label>
							<select name="type" class="form-control" required>
								<option value="">{{ $txt["select_default"] }}</option>
								<option value="4" @if( !empty($edm) && $edm->type === 4 ) selected @endif>{{ $txt["edm_type4"] }}</option>
								<option value="5" @if( !empty($edm) && $edm->type === 5 ) selected @endif>{{ $txt["edm_type5"] }}</option>
							</select>				
						</div>
						@if( !empty($edm_rel) )
							@foreach($edm_rel as $row)
							<div class="form-group MProductId">
								<label>{{ $txt["mall_shop_id"] }}</label>
								<div class="form-group">
									<input type="number" class="form-control" name="mall_shop_id[]" value="{{ $row->mall_shop_id }}" min="1" required/>
									<input type="button" class="btn btn-primary remove" target=".MProductId" value="{{ $txt['remove_block'] }}"/>
								</div>
							</div>
							@endforeach
						@else
							<div class="form-group MProductId">
								<label>{{ $txt["mall_shop_id"] }}</label>
								<div class="form-group">
									<input type="number" class="form-control" name="mall_shop_id[]" min="1" required/>
									<input type="button" class="btn btn-primary remove" target=".MProductId" value="{{ $txt['remove_block'] }}"/>
								</div>
							</div>
						@endif
						<div class="form-group">
							<label>{{ $txt["upload_mail_list"] }}</label>
							<input type="file" name="edm_list"> <br />
							<input type="button" value="{{ $txt['downaload_example'] }}" class="btn btn-primary" onclick="location.href='/edm_example';" >
						</div>					
						<div class="form-group">
							<input type="button" class="btn btn-primary add_block" value="{{ $txt['add_block'] }}" target=".MProductId"/>
							<input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/>
						</div>
						@if(!empty($edm))
							<input type="hidden" name="_method" value="patch">				
						@endif														
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>