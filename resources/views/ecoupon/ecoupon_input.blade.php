<section class="content-header">
	@if(!empty($ErrorMsg))
		@foreach($ErrorMsg as $error)
		    <div class="error">{{ $error }}</div>
		@endforeach
	@endif
	<h1>{{ $txt["ecoupon_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content ecoupon basicForm" >
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["ecoupon_input"] }}</h3>
				</div>
				<basic-form ref="basicForm" :list="{{ $htmlJsonData }}" token="{{ csrf_token() }}" :txt="{{ $JsonTxt }}"></basic-form>
	      	</div>
        </div>
  	</div>
</section>