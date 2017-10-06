<section class="content-header">
	<h1>{{ $txt["product_categeory_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["product_categeory_input"] }}</h3>
				</div>
				<form action="/product_category" method="POST">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["parents_category"] }}</label>
							@if(!empty($ProductCategory))
								<input type="hidden" name="product_category_id" value="{{ $ProductCategory->id }}">						
							@endif
							<select name="parents_category" class="form-control">
								<option value="">{{ $txt['select_default'] }}</option>
								@if(!empty($parents_category_list))
									@foreach($parents_category_list as $index => $row)
									<option value="{{ $index }}" @if( !empty($ProductCategory) && $ProductCategory->parents_id == $index ) selected @endif>{{ $row }}</option>
									@endforeach
								@endif
							</select>
						</div>
						<div class="form-group">
							<label>{{ $txt["product_category_name"] }}</label>
							<input type="text" name="name" class="form-control" value="@if(!empty($ProductCategory)){{ $ProductCategory->name }}@endif"  placeholder="{{ $txt['product_category_name_input'] }}" required/>
						</div>
						<div class="form-group">
							<label><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">															
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>