<section class="content-header">
	<h1>{{ $txt["msg_input"] }}</h1>
</section>
<section class="content">
	<form action="/msg" method="POST">
		<table class="table table-stroped">
			<tbody>

				<tr>
					<th width="10%">{{ $txt["subject"] }}</th>
					<td>
						@if(!empty($msg))
						<input type="hidden" name="msg_id" value="{{ $msg->id }}">						
						@endif
						<input type="text" name="subject" value="@if(!empty($msg)){{ $msg->subject }}@endif" required/>
					</td>
				</tr>

				<tr>
					<th width="10%">{{ $txt["content"] }}</th>
					<td>
						<textarea name="content" class="textarea" style="width: 60%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required>@if(!empty($msg)){{ $msg->content }}@endif</textarea>
					</td>
				</tr>

				<tr>
					<th>{{ $txt["notice_role"] }}</th>
					<td class="auth-td">
						<select name="role_id">
							<option value="">{{ $txt["all_role"] }}</option>
							@foreach($msg_option['role_data'] as $key => $value)
							<option value="{{ $key }}" @if( !empty($msg) && $msg->role_id == $key ) selected @endif >{{ $value }}</option>
							@endforeach								
						</select>
					</td>
				</tr>

				<tr>
					<th width="10%">{{ $txt["show_type"] }}</th>
					<td>
						<select name="show_type" required>
							<option value="">{{ $txt["select_default"] }}</option>
							@foreach($msg_option['show_type'] as $key => $value)
							<option value="{{ $key }}" @if( !empty($msg) && $msg->show_type == $key ) selected @endif >{{ $value }}</option>
							@endforeach								
						</select>
					</td>
				</tr>

				<tr>
					<th width="10%">{{ $txt["msg_type"] }}</th>
					<td>
						<select name="msg_type" required>
							<option value="">{{ $txt["select_default"] }}</option>
							@foreach($msg_option['msg_type'] as $key => $value)
							<option value="{{ $key }}" @if( !empty($msg) && $msg->msg_type == $key ) selected @endif >{{ $value }}</option>
							@endforeach								
						</select>
					</td>
				</tr>

				<tr>
					<th>{{ $txt["status"] }}</th>
					<td>
						<input type="radio" value="1" name="public" @if( !empty($msg) && $msg->public != 0 ) checked  @endif required/>{{ $txt["enable"] }}
						<input type="radio" value="0" name="public" @if( !empty($msg) && $msg->public == 0 ) checked  @endif required/>{{ $txt["disable"] }}
					</td>
				</tr>

				<tr>
					<th width="10%">{{ $txt["notice_range"] }}</th>
					<td>
						{{ $txt["start_date"] }}<input type="text" name="start_date" value="@if(!empty($msg)){{ $msg->start_date }}@endif" required/> <br />
						{{ $txt["end_date"] }}<input type="text" name="end_date" value="@if(!empty($msg)){{ $msg->end_date }}@endif" required/>
					</td>
				</tr>

				<tr>
					<th colspan="2"><input type="submit" value="{{ $txt['send'] }}"/></th>
				</tr>															
			</tbody>
		</table>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</section>