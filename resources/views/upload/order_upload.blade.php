<section class="content-header">
	<h1>{{ $txt["order_upload"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row basicForm">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["order_upload"] }}</h3>
				</div>
				<basic-form ref="basicForm" :list="{{ $htmlJsonData }}" token="{{ csrf_token() }}" :txt="{{ $JsonTxt }}"></basic-form>
	      	</div>
        </div>
  	</div>
</section>