<section class="content-header">
	<h1>{{ $txt["product_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<form action="/product" method="POST">
		<table class="table table-stroped">
			<tbody>

				<tr>
					<th width="10%">{{ $txt["product_name"] }}</th>
					<td>
						@if(!empty($product))
						<input type="hidden" name="product_id" value="{{ $product['product_id'] }}">						
						@endif
						<input type="text" name="product_name" value="@if(!empty($product)){{ $product['product_name'] }}@endif" placeholder="{{ $txt['product_name_input'] }}" required/>
					</td>
				</tr>

				@if( !empty( $product_extra_column ) )
				
				@foreach($product_extra_column as $row)
					<tr class="{{ $row['name'] }}">
						<th>{{ $txt[$row['name']] }}</th>
						<td> 

							<!-- 個案處理 -->
							@if( $row['name'] == 'category' )
				
								<input type="hidden" name="ori_category" value="{{ $product['category'] }}">
								{{ $all_category[$product['category']] }} <br />

							@endif
				

							@if( in_array( $row['type'], array('text', 'number') ) )
							<input type="{{ $row['type'] }}" name="{{ $row['name'] }}" placeholder="{{ $txt[$row['name'].'_input'] }}" value="@if(!empty($product)){{ $product[$row['name']] }}@endif"> 
							@endif
							@if( !empty($select_option) && in_array( $row['type'], array('select') ) )
							<select name="{{ $row['name'] }}" id="{{ $row['name'] }}">
								<option value="">{{ $txt['select_default'] }}</option>
								@foreach($select_option[$row['name']] as $key => $value)
								<option value="{{ $key }}"  @if( !empty($product) && $product[$row['name']] == $key ) selected @endif>{{ $value }}</option>
								@endforeach
							</select>
							@endif
							@if( in_array( $row['type'], array('textarea') ) )
							<textarea name="{{ $row['name'] }}" placeholder="{{ $txt[$row['name'].'_input'] }}" cols="50" rows="5">@if(!empty($product)){{ $product[$row['name']] }}@endif</textarea>
							@endif
							@if( in_array( $row['type'], array('file') ) )
							<input type="{{ $row['type'] }}" name="{{ $row['name'] }}"> 
								@if( !empty($product) && $product[$row['name']] )
								<img src="/{{ $product[$row['name']] }}" alt="" width="200">
								@endif
							@endif
						</td>
					</tr>
				@endforeach

				@endif

				<tr>
					<th>{{ $txt["safe_amount"] }}</th>
					<td>
						<input type="number" name="safe_amount" value="@if(!empty($product)){{ $product['safe_amount'] }}@endif"  placeholder="{{ $txt['safe_amount_input'] }}" required/> {{ $txt['safe_amount_desc'] }}
					</td>
				</tr>
		
				@if( $has_spec )
				
				<tr>
					<th>{{ $txt["product_spec"] }}</th>
					<td>
						<table width="50%">
							<tbody>

								<tr>
									@foreach($product_spec_column as $row)	
									<th width="25%">{{ $txt[$row->name] }}</th>
									@endforeach
									<th width="25%">{{ $txt["action"] }}</th>
								</tr>
								
								@if( !empty($product_spec) )

								@foreach($product_spec as $spec_data)

								<tr class="spec_data clone">
									@foreach($product_spec_column as $row)
									<td>
										<select name="{{ $row->name }}[]" required>
											<option value="">{{ $txt['select_default'] }}</option>
											@foreach($row->option as $data)
												<option value="{{ $data->id }}" @if( $data->id == $spec_data['value'][$row->name] ) selected @endif>{{ $data->name }}</option>
											@endforeach
										</select>
									</td>
									@endforeach
									<td>
										<input type="hidden" value="{{ $spec_data['id'] }}" name="spec_id[]">
										<input type="button" value="{{ $txt['remove'] }}" class="removeLabel removeBtn" target="spec_data">
									</td>
								</tr>

								@endforeach

								@else

								<tr class="spec_data clone">
									@foreach($product_spec_column as $row)
									<td>
										<select name="{{ $row->name }}[]" required>
											<option value="">{{ $txt['select_default'] }}</option>
											@foreach($row->option as $data)
												<option value="{{ $data->id }}">{{ $data->name }}</option>
											@endforeach
										</select>
									</td>
									@endforeach
									<td><input type="button" value="{{ $txt['remove'] }}" class="removeLabel removeBtn" target="spec_data"></td>
								</tr>
								
								@endif

							</tbody>
						</table>
						<br />
						<input type="button" value="{{ $txt['add_spec'] }}" class="addbtn" target="spec_data">
					</td>
				</tr>

				@endif

				<tr>
					<th colspan="2"><input type="submit" value="{{ $txt['send'] }}"/></th>
				</tr>															
			</tbody>
		</table>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</section>