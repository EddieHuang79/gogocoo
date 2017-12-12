<div style="font-family: Microsoft JhengHei;">親愛的  <label style="color: red; font-family: Microsoft JhengHei;">{{ $real_name }}</label>先生/小姐，您好！</div>
<div style="font-family: Microsoft JhengHei;">歡迎您成為「{{ $txt['Site'] }}」會員！</div>
<br />
<br />
<div style="font-family: Microsoft JhengHei;">{{ $txt['Site'] }}目前有以下服務優惠活動</div>
<br />
@foreach($mall as $row)
	<div style="font-family: Microsoft JhengHei;">{{ $row->product_name }}</div>
	@if( !empty($row->pic) )
		<div><img src="{{ URL::asset($row->pic) }}" alt="" width="100"></div>
	@endif
	<div style="font-family: Microsoft JhengHei;"{{ $row->description }}</div>
	@if( isset($row->promo) )
		<div style="font-family: Microsoft JhengHei;">原價${{ $row->cost }}，現正優惠${{ $row->promo }}</div>
	@else
		<div style="font-family: Microsoft JhengHei;">驚喜價${{ $row->cost }}</div>
	@endif
	<br />
@endforeach
<div style="font-family: Microsoft JhengHei;">若您或貴公司欲享有更多加值服務，歡迎進一步購買各種加值服務。</div>
<div style="font-family: Microsoft JhengHei;">感謝您成為{{ $txt['Site'] }}的用戶!</div>
<br />
<div style="font-family: Microsoft JhengHei;">{{ $txt['Site'] }}營運團隊 敬上</div>
<br />
<br />
<div style="font-family: Microsoft JhengHei;">此為系統發信，請勿回信！</div>