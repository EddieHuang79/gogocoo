<div style="font-family: Microsoft JhengHei;">親愛的  <label style="color: red; font-family: Microsoft JhengHei;">{{ $user->real_name }}</label>先生/小姐，您好！</div>
<div style="font-family: Microsoft JhengHei;">歡迎您成為「{{ $txt['Site'] }}」會員！</div>
<br />
<div style="font-family: Microsoft JhengHei;">在這個特別的日子，我們將提供您以下功能服務的優惠</div>
<div style="font-family: Microsoft JhengHei;">帳號：<label style="color: red; font-family: Microsoft JhengHei;">{{ $user->account }}</label></div>
<br />
<div style="font-family: Microsoft JhengHei;">{{ $mall->product_name }}</div>
@if( !empty($mall->pic) )
	<div><img src="{{ URL::asset($mall->pic) }}" alt="" width="100"></div>
@endif
<div style="font-family: Microsoft JhengHei;">{{ $mall->description }}</div>
<div style="font-family: Microsoft JhengHei;">使用期間 {{ $mall->spec_id[0]->date_spec }} 天</div>
<br />
<br />
<div style="font-family: Microsoft JhengHei;">若您或貴公司欲享有更多加值服務，歡迎進一步購買各種加值服務。</div>
<div style="font-family: Microsoft JhengHei;">感謝您成為{{ $txt['Site'] }}的用戶! 讓我們有機會為您服務!!</div>
<br />
<div style="font-family: Microsoft JhengHei;">{{ $txt['Site'] }}營運團隊 敬上</div>
<br />
<br />
<div style="font-family: Microsoft JhengHei;">此為系統發信，請勿回信！</div>