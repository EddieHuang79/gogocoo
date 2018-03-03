<section class="content-header">
    @if(!empty($ErrorMsg))
        <div class="error">{{ $txt['promo_repeat'] }}</div>
    @endif
	<h1>{{ $txt["promo_price_setting"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row basicForm">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["promo_price_setting"] }}</h3>
				</div>
				<basic-form ref="basicForm" :list="{{ $htmlJsonData }}" token="{{ csrf_token() }}" :txt="{{ $JsonTxt }}"></basic-form>
	      	</div>
        </div>
  	</div>
</section>