@if(!empty($search_tool))
<div><h2 class="sub-header"><input type="button" value="{{ $txt['Show'] }}" class="ShowHide d3"><a href="#">{{ $txt['search_tool'] }}</a></h2></div>

<form method="get" id="search_form" class="d3">

	<div id="search_tool">

		<!-- 日期 -->
		@if(in_array(1, $search_tool))
		<ul class="search_block list-unstyled">
			<li class="item"><a href="#">{{ $txt['Date'] }}</a></li>
			<li class="tool"><input type="text" name="date" class="form-control" placeholder="{{ $txt['Date'] }}" @if(!empty($_GET['date'])) value="{{ $_GET['date'] }}" @endif></li>
		</ul>
		@endif
		
		<!-- 帳號 -->
		@if(in_array(2, $search_tool))
		<ul class="search_block list-unstyled">
			<!-- <li class="item"><a href="#">{{ $txt['account'] }}</a></li> -->
			<li class="tool"><input type="text" name="account" class="form-control" placeholder="{{ $txt['account_input'] }}" @if(!empty($_GET['account'])) value="{{ $_GET['account'] }}" @endif></li>
		</ul>
		@endif
		
		<!-- 姓名 -->
		@if(in_array(3, $search_tool))
		<ul class="search_block list-unstyled">
			<!-- <li class="item"><a href="#">{{ $txt['real_name'] }}</a></li> -->
			<li class="tool"><input type="text" name="real_name" class="form-control" placeholder="{{ $txt['real_name'] }}" @if(!empty($_GET['real_name'])) value="{{ $_GET['real_name'] }}" @endif></li>
		</ul>
		@endif
		

		<!-- 角色名稱 -->
		@if(in_array(6, $search_tool))
		<ul class="search_block list-unstyled">
			<!-- <li class="item"><a href="#">{{ $txt['role_name'] }}</a></li> -->
			<li class="tool"><input type="text" name="role_name"  class="form-control" placeholder="{{ $txt['role_name'] }}" @if(!empty($_GET['role_name'])) value="{{ $_GET['role_name'] }}" @endif></li>
		</ul>
		@endif

		<!-- 服務名稱 -->
		@if(in_array(7, $search_tool))
		<ul class="search_block list-unstyled">
			<!-- <li class="item"><a href="#">{{ $txt['service_name'] }}</a></li> -->
			<li class="tool"><input type="text" name="service_name"  class="form-control" placeholder="{{ $txt['service_name'] }}" @if(!empty($_GET['service_name'])) value="{{ $_GET['service_name'] }}" @endif></li>
		</ul>
		@endif

		<!-- 角色 -->
		@if(in_array(4, $search_tool))
		<ul class="search_block list-unstyled">
			<li class="item"><a href="#">{{ $txt['role_name'] }}</a></li>
			<li class="tool">
				<select name="role_id">
					<option value=""> {{ $txt['select_default'] }} </option>
					@foreach($active_role_list as $row)
						<option value="{{ $row->id }}" @if( isset($_GET['role_id']) && $_GET['role_id'] == $row->id ) selected @endif> {{ $row->name }} </option>
					@endforeach
				</select>				
			</li>
		</ul>
		@endif

		<!-- 狀態 -->
		@if(in_array(5, $search_tool))
		<ul class="search_block list-unstyled">
			<li class="item"><a href="#">{{ $txt['status'] }}</a></li>
			<li class="tool">
				<select name="status">
					<option value=""> {{ $txt['select_default'] }} </option>
					<option value="1" @if( isset($_GET['status']) && $_GET['status'] == 1 ) selected @endif> {{ $txt['enable'] }} </option>
					<option value="2" @if( isset($_GET['status']) && $_GET['status'] == 2 ) selected @endif> {{ $txt['disable'] }} </option>
				</select>
			</li>
		</ul>
		@endif


	</div>
	
	<div><input type="submit" value="{{ $txt['filter'] }}" name="filter"></div>

</form>
@endif

	<br />
	<br />