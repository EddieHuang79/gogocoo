<section class="content-header">
	<h1>{{ $txt["edm_cancel_list"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	@include('webbase.search_tool')
	<form action="/edm_cancel" method="POST">
	    <div class="row basicList">
	        <div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<input type="submit" name="cancel" value="{{ $txt['cancel'] }}" class="btn btn-primary">
					</div>
					<div class="box-body">
						<basic-list ref="basicList" :list="{{ $htmlJsonData }}" :txt="{{ $JsonTxt }}"></basic-list>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
	{{ $edm->links() }}
</section>