<div style="font-family: Microsoft JhengHei;">親愛的  <label style="color: red; font-family: Microsoft JhengHei;">{{ $real_name }}</label>先生/小姐，您好！</div>
<div style="font-family: Microsoft JhengHei;">歡迎您成為「{{ $txt['Site'] }}」會員！</div>
<br />
<br />
<div style="font-family: Microsoft JhengHei;">{{ $txt['Site'] }}新上線了一個"服務A"的功能，邀請尊榮用戶優先試用體驗，若體驗後能回饋給我們意見，服務正式上線時，用戶將享有使用上的優惠!!!</div>
<br />

@foreach($mall as $row)
	<div style="font-family: Microsoft JhengHei;">{{ $row->product_name }}</div>
	@if( !empty($row->pic) )
		<div><img src="{{ URL::asset($row->pic) }}" alt="" width="100"></div>
	@endif
	<div style="font-family: Microsoft JhengHei;">{{ $row->description }}</div>
@endforeach
<br />
<br />
<div style="font-family: Microsoft JhengHei;">若您或貴公司欲享有更多加值服務，歡迎進一步購買各種加值服務。</div>
<div style="font-family: Microsoft JhengHei;">感謝您成為{{ $txt['Site'] }}的用戶!</div>
<br />
<div style="font-family: Microsoft JhengHei;">{{ $txt['Site'] }}營運團隊 敬上</div>
<br />
<br />
<div style="font-family: Microsoft JhengHei;">此為系統發信，請勿回信！</div>