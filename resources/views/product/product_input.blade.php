<section class="content-header">
	<h1>{{ $txt["product_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["product_input"] }}</h3>
				</div>
				<form action="/product" method="POST">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["product_name"] }}</label>
							@if(!empty($product))
								<input type="hidden" name="product_id" value="{{ $product['product_id'] }}">						
							@endif
							<input type="text" name="product_name" class="form-control" value="@if(!empty($product)){{ $product['product_name'] }}@endif" placeholder="{{ $txt['product_name_input'] }}" required/>
						</div>
						@if( !empty( $product_extra_column ) )
						@foreach($product_extra_column as $row)
							<div class="form-group {{ $row['name'] }}">
								<label>{{ $txt[$row['name']] }}</label>								 
								<!-- 個案處理 -->
								@if( $row['name'] == 'category' && !empty($product) )
									<input type="hidden" name="ori_category" value="{{ $product['category'] }}">
									{{ $all_category[$product['category']] }} <br />
								@endif
								@if( in_array( $row['type'], array('text', 'number') ) )
									<input type="{{ $row['type'] }}" name="{{ $row['name'] }}" class="form-control" placeholder="{{ $txt[$row['name'].'_input'] }}" value="@if(!empty($product)){{ $product[$row['name']] }}@endif"> 
								@endif
								@if( !empty($select_option) && in_array( $row['type'], array('select') ) )
								<select name="{{ $row['name'] }}" class="form-control" id="{{ $row['name'] }}">
									<option value="">{{ $txt['select_default'] }}</option>
									@foreach($select_option[$row['name']] as $key => $value)
									<option value="{{ $key }}"  @if( !empty($product) && $product[$row['name']] == $key ) selected @endif>{{ $value }}</option>
									@endforeach
								</select>
								@endif
								@if( in_array( $row['type'], array('textarea') ) )
									<textarea name="{{ $row['name'] }}" class="form-control" placeholder="{{ $txt[$row['name'].'_input'] }}" cols="50" rows="5">@if(!empty($product)){{ $product[$row['name']] }}@endif</textarea>
								@endif
								@if( in_array( $row['type'], array('file') ) )
									<input type="{{ $row['type'] }}" name="{{ $row['name'] }}"> 
									@if( !empty($product) && $product[$row['name']] )
										<img src="/{{ $product[$row['name']] }}" alt="" width="200">
									@endif
								@endif
							</div>
						@endforeach
						@endif
						<div class="form-group">
							<label>{{ $txt["safe_amount"] }}</label>
							<input type="number" name="safe_amount" class="form-control" value="@if(!empty($product)){{ $product['safe_amount'] }}@endif"  placeholder="{{ $txt['safe_amount_input'] }}" required/>
						</div>
						<div class="form-group">
							<th colspan="2"><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>															
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>