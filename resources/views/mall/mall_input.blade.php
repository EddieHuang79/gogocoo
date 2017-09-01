<section class="content-header">
	<h1>{{ $txt["mall_product_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<form action="/mall" method="POST" enctype="multipart/form-data">
		<table class="table table-stroped">
			<tbody>

				<tr>
					<th width="10%">{{ $txt["product_name"] }}</th>
					<td>
						@if(!empty($mall))
						<input type="hidden" name="mall_product_id" value="{{ $mall->id }}">						
						@endif
						<input type="text" name="product_name" class="InputText" value="@if(!empty($mall)){{ $mall->product_name }}@endif" placeholder="{{ $txt['product_name_input'] }}" required/>
					</td>
				</tr>

				<tr>
					<th width="10%">{{ $txt["product_description"] }}</th>
					<td>
						<textarea name="description" class="textarea" placeholder="{{ $txt['product_description_input'] }}" style="width: 60%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" required>@if(!empty($mall)){{ $mall->description }}@endif</textarea>
					</td>
				</tr>

				<tr>
					<th>{{ $txt["product_image"] }}</th>
					<td class="auth-td">
						<input type="file" name="product_image" /> 
						@if(!empty($mall->pic))
							<img src="{{ URL::asset($mall->pic) }}" alt="" width="100">
						@endif
					</td>
				</tr>

				<tr>
					<th>{{ $txt["include_service"] }}</th>
					<td>
						<div class="include_service">
							@if(!empty($mall_product_rel))
								@foreach($mall_product_rel as $mall_product_id => $mall_product)
								<div class="include_service_target clone_div_parents clone">
									<div class="clone_div_child">
										<select name="service[]">
											<option value="">{{ $txt['select_default'] }}</option>
											@foreach($child_product as $index => $row)
											<option value="{{ $index }}" @if( $mall_product_id == $index ) selected @endif >{{ $row }}</option>
											@endforeach
										</select>
									</div>
									<input type="button" class="removeLabel removeBtn" value="{{ $txt['remove_block'] }}" target="include_service_target">									
								</div>
								@endforeach
							@else
								<div class="include_service_target clone_div_parents clone">
									<div class="clone_div_child">
										<select name="service[]">
											<option value="">{{ $txt['select_default'] }}</option>
											@foreach($child_product as $index => $row)
											<option value="{{ $index }}">{{ $row }}</option>
											@endforeach
										</select>
									</div>
									<input type="button" class="removeLabel removeBtn" value="{{ $txt['remove_block'] }}" target="include_service_target">
								</div>							
							@endif
							<input type="button" class="addbtn" value="{{ $txt['add_service'] }}" target="include_service_target">
						</div>
					</td>
				</tr>

				<tr>
					<th>{{ $txt["product_spec"] }}</th>
					<td>
						<div class="product_spec">
							@if(!empty($mall->spec_id))
								@foreach($mall->spec_id as $row)
								<div class="product_spec_target clone_div_parents clone">
									<div class="product_price clone_div_child">{{ $txt['cost_unit'] }}<input type="text" name="cost[]" class="InputText" placeholder="{{ $txt['cost_input'] }}" value="{{ $row->cost }}"></div>
									<div class="product_date clone_div_child"><input type="text" name="date_range[]" class="InputText" placeholder="{{ $txt['date_range_input'] }}" value="{{ $row->date_spec }}">{{ $txt['day_unit'] }}</div>
									<input type="button" class="removeLabel removeBtn" value="{{ $txt['remove_block'] }}" target="product_spec_target">
								</div>
								@endforeach
							@else
								<div class="product_spec_target clone_div_parents clone">
									<div class="product_price clone_div_child">{{ $txt['cost_unit'] }}<input type="text" name="cost[]" class="InputText" placeholder="{{ $txt['cost_input'] }}"></div>
									<div class="product_date clone_div_child"><input type="text" name="date_range[]" class="InputText" placeholder="{{ $txt['date_range_input'] }}">{{ $txt['day_unit'] }}</div>
									<input type="button" class="removeLabel removeBtn" value="{{ $txt['remove_block'] }}" target="product_spec_target">
								</div>							
							@endif
							<input type="button" class="addbtn" value="{{ $txt['add_spec'] }}" target="product_spec_target">
						</div>
					</td>
				</tr>

				<tr>
					<th>{{ $txt["status"] }}</th>
					<td>
						<input type="radio" value="1" name="public" @if( !empty($mall) && $mall->public != 0 ) checked  @endif required/>{{ $txt["enable"] }}
						<input type="radio" value="0" name="public" @if( !empty($mall) && $mall->public == 0 ) checked  @endif required/>{{ $txt["disable"] }}
					</td>
				</tr>

				<tr>
					<th width="10%">{{ $txt["product_live_date"] }}</th>
					<td>
						{{ $txt["start_date"] }}<input type="text" name="start_date" class="InputDate" placeholder="{{ $txt['start_date_input'] }}" value="@if( !empty($mall) &&  strtotime($mall->start_date) > 0 ){{ $mall->start_date }}@endif"/> <br />
						{{ $txt["end_date"] }}<input type="text" name="end_date" class="InputDate" placeholder="{{ $txt['end_date_input'] }}" value="@if( !empty($mall) &&  strtotime($mall->end_date) > 0 ){{ $mall->end_date }}@endif"/>
					</td>
				</tr>

				<tr>
					<th colspan="2"><input type="submit" value="{{ $txt['send'] }}"/></th>
				</tr>															
			</tbody>
		</table>
		<input type="hidden" name="mall_shop_id" value="@if(!empty($mall)){{ $mall->id }}@endif">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</section>