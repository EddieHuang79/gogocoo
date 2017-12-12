<section class="content-header">
	<h1>{{ $txt["mall_product_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["mall_product_input"] }}</h3>
				</div>
				<form @if( isset($mall->id) ) action="/mall/{{ $mall->id }}" @else action="/mall" @endif action="/mall" method="POST" enctype="multipart/form-data" id="mallForm">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["product_name"] }}</label>
							@if(!empty($mall))
							<input type="hidden" name="mall_product_id" value="{{ $mall->id }}">						
							@endif
							<input type="text" name="product_name" class="InputText form-control" value="@if(!empty($mall)){{ $mall->product_name }}@endif" placeholder="{{ $txt['product_name_input'] }}" required/>
						</div>
						<div class="form-group">
							<label>{{ $txt["product_description"] }}</label>
							<textarea name="description" class="textarea form-control" placeholder="{{ $txt['product_description_input'] }}" style="width: 60%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required>@if(!empty($mall)){{ $mall->description }}@endif</textarea>
						</div>
						<div class="form-group">
							<label>{{ $txt["product_image"] }}</label>
							<input type="file" name="product_image" /> 
							@if(!empty($mall->pic))
								<img src="{{ URL::asset($mall->pic) }}" alt="" width="100">
							@endif
						</div>
						<div class="form-group">
							<label>
								{{ $txt["include_service"] }}
								<br />
								<h6>{{ $txt["include_service_desc"] }}</h6>
							</label>
							<div class="include_service">
								@if(!empty($mall_product_rel))
									@foreach($mall_product_rel as $mall_product_id => $mall_product)
									<div class="include_service_target clone_div_parents clone">
										<div class="clone_div_child">
											<select name="service[]" required>
												<option value="">{{ $txt['select_default'] }}</option>
												@foreach($child_product as $index => $row)
												<option value="{{ $index }}" @if( $mall_product_id == $index ) selected @endif >{{ $row }}</option>
												@endforeach
											</select>
											<input type="text" name="number[]" value="{{ $mall_product['number'] }}" placeholder="請輸入數量" required>{{ $txt['service_unit'] }}
											<input type="text" name="date_spec[]" class="InputText" placeholder="{{ $txt['date_range_input'] }}" value="{{ $mall_product['date_spec'] }}" required>{{ $txt['day_unit'] }}
										</div>
										<input type="button" class="removeLabel removeBtn" value="{{ $txt['remove_block'] }}" target="include_service_target">									
									</div>
									@endforeach
								@else
									<div class="include_service_target clone_div_parents clone">
										<div class="clone_div_child">
											<select name="service[]" required>
												<option value="">{{ $txt['select_default'] }}</option>
												@foreach($child_product as $index => $row)
												<option value="{{ $index }}">{{ $row }}</option>
												@endforeach
											</select>
											<input type="text" name="number[]" placeholder="請輸入數量" required>{{ $txt['service_unit'] }}
											<input type="text" name="date_spec[]" class="InputText" placeholder="{{ $txt['date_range_input'] }}" value="" required>{{ $txt['day_unit'] }}
										</div>
										<input type="button" class="removeLabel removeBtn" value="{{ $txt['remove_block'] }}" target="include_service_target">
									</div>							
								@endif
								<input type="button" class="addbtn" value="{{ $txt['add_service'] }}" target="include_service_target">
							</div>
						</div>
						<div class="form-group">
							<label>{{ $txt["price"] }}</label>
							{{ $txt['cost_unit'] }}
							<input type="text" name="cost" class="form-control" placeholder="{{ $txt['cost_input'] }}" value="@if(!empty($mall)) {{ $mall->cost }} @endif" required>
						</div>
						<div class="form-group">
							<label>{{ $txt["status"] }}</label>
							<div class="radio">
								<label>		
									<input type="radio" value="1" name="public" @if( !empty($mall) && $mall->public != 0 ) checked  @endif required/>{{ $txt["enable"] }}
								</label>
							</div>								
							<div class="radio">
								<label>									
									<input type="radio" value="0" name="public" @if( !empty($mall) && $mall->public == 0 ) checked  @endif required/>{{ $txt["disable"] }}
								</label>
							</div>							
						</div>
						<div class="form-group">
							<label>{{ $txt["product_live_date"] }} - {{ $txt["start_date"] }}</label>
							<input type="text" id="start_date" name="start_date" class="form-control" value="@if( !empty($mall) &&  strtotime($mall->start_date) > 0 ){{ $mall->start_date }}@endif"  placeholder="{{ $txt['start_date_input'] }}" required/> <br />
						</div>
						<div class="form-group">
							<label>{{ $txt["product_live_date"] }} - {{ $txt["end_date"] }}</label>
							<input type="text" id="end_date" name="end_date" class="form-control" value="@if( !empty($mall) &&  strtotime($mall->end_date) > strtotime('1970-01-01 23:59:59') ){{ $mall->end_date }}@endif" placeholder="{{ $txt['end_date_input'] }}" required/>
						</div>
						<div class="form-group">
							<label><input type="button" class="btn btn-primary" value="{{ $txt['send'] }}" onclick="MallSubmit();"/></label>
						</div>															
						@if(!empty($mall))
							<input type="hidden" name="_method" value="patch">				
						@endif	
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>