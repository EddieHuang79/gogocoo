<div>
	
	@if($edm->type === 1)
	<p> {{ $edm->content }} </p>
	@endif

	@if($edm->type === 2)
	<img src="{{ $site }}/{{ $edm->content }}" alt="">
	@endif

</div>