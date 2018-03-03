<section class="content-header">
	<h1>{{ $txt["purchase_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row basicForm">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["purchase_input"] }}</h3>
				</div>
				<basic-form ref="basicForm" :list="{{ $htmlJsonData }}" token="{{ csrf_token() }}" :txt="{{ $JsonTxt }}" site="{{ $site }}"></basic-form>
	      	</div>
        </div>
  	</div>
</section>