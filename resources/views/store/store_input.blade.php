<section class="content-header">
	<h1>{{ $txt["store_input"] }}</h1>
	@if(!empty($store_status["free"]))
		<h5>{{ $txt["store_input_desc"] }}</h5>
	@endif
    @if(!empty($ErrorMsg))
    	@foreach($ErrorMsg as $error)
            <div class="error">{{ $error }}</div>
        @endforeach
    @endif
	@include('webbase.breadcrumb')
</section>
<section class="content">

	@if( $store_status["left"] > 0 )
	<div class="row basicForm">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["store_input"] }}</h3>
				</div>
				<basic-form ref="basicForm" :list="{{ $htmlJsonData }}" token="{{ csrf_token() }}" :txt="{{ $JsonTxt }}"></basic-form>
	      	</div>
        </div>
  	</div>
	@else

	<div class="lightbox">
		<div class="subject">{{ $txt["create_store_disable"] }} <label class="close_btn"> X </label> </div>
		<hr>
		<div class="content">
			{{ $txt["create_store_disable_desc"] }} 
			<br />
			<br />
			<br />
			<button class="btn btn-lg btn-primary btn-block" type="button" onclick="location.href='/buy';">{{ $txt['go_to_store'] }}</button>
		</div>
	</div>

	@endif

</section>