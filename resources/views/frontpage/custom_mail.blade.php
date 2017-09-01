<h1>客戶來信</h1>
<div>
    <h2>姓名: {{ $name }} </h2>  
</div>
<div>
    <h2>email: {{ $email }} </h2>  
</div>
<div>
    <h2>電話: {{ $phone }} </h2>  
</div>
<div>
    @foreach($msg as $row)
	<div>{{ $row }}</div> <br />
    @endforeach
</div>