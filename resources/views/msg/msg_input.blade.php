<section class="content-header">
	<h1>{{ $txt["msg_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["msg_input"] }}</h3>
				</div>
				<form action="/msg" method="POST">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["notice_range"] }} - {{ $txt["start_date"] }}</label>
							<input type="text" id="start_datetime" name="start_date" class="form-control" value="@if(!empty($msg)){{ $msg->start_date }}@endif" required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["notice_range"] }} - {{ $txt["end_date"] }}</label>
							<input type="text" id="end_datetime" name="end_date" class="form-control" value="@if(!empty($msg)){{ $msg->end_date }}@endif" required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["subject"] }}</label>
							@if(!empty($msg))
								<input type="hidden" name="msg_id" value="{{ $msg->id }}">						
							@endif
							<input type="text" name="subject" class="form-control" value="@if(!empty($msg)){{ $msg->subject }}@endif" required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["content"] }}</label>
							<textarea name="content" class="textarea form-control" required>@if(!empty($msg)){{ $msg->content }}@endif</textarea>
						</div>
						<div class="form-group">
							<label>{{ $txt["notice_role"] }}</label>
							<select name="role_id" class="form-control">
								<option value="">{{ $txt["all_role"] }}</option>
								@foreach($msg_option['role_data'] as $key => $value)
								<option value="{{ $key }}" @if( !empty($msg) && $msg->role_id == $key ) selected @endif >{{ $value }}</option>
								@endforeach								
							</select>
						</div>
						<div class="form-group">
							<label>{{ $txt["show_type"] }}</label>
							<select name="show_type" class="form-control" required>
								<option value="">{{ $txt["select_default"] }}</option>
								@foreach($msg_option['show_type'] as $key => $value)
								<option value="{{ $key }}" @if( !empty($msg) && $msg->show_type == $key ) selected @endif >{{ $value }}</option>
								@endforeach								
							</select>
						</div>
						<div class="form-group">
							<label>{{ $txt["msg_type"] }}</label>
							<select name="msg_type" class="form-control" required>
								<option value="">{{ $txt["select_default"] }}</option>
								@foreach($msg_option['msg_type'] as $key => $value)
								<option value="{{ $key }}" @if( !empty($msg) && $msg->msg_type == $key ) selected @endif >{{ $value }}</option>
								@endforeach								
							</select>
						</div>
						<div class="form-group">
							<label>{{ $txt["status"] }}</label>
							<div class="radio">
								<label>	
									<input type="radio" value="1" name="public" @if( !empty($msg) && $msg->public != 0 ) checked  @endif required/>{{ $txt["enable"] }}
								</label>
							</div>								
							<div class="radio">
								<label>								
									<input type="radio" value="0" name="public" @if( !empty($msg) && $msg->public == 0 ) checked  @endif required/>{{ $txt["disable"] }}
								</label>
							</div>								
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