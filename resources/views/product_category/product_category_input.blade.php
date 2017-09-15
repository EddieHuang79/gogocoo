<section class="content-header">
	<h1>{{ $txt["product_categeory_input"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<form action="/product_category" method="POST">
		<table class="table table-stroped">
			<tbody>

				<tr>
					<th width="10%">{{ $txt["parents_category"] }}</th>
					<td>
						@if(!empty($ProductCategory))
						<input type="hidden" name="product_category_id" value="{{ $ProductCategory->id }}">						
						@endif
						
						<select name="parents_category">
							<option value="">{{ $txt['select_default'] }}</option>
							@if(!empty($parents_category_list))
								@foreach($parents_category_list as $index => $row)
								<option value="{{ $index }}" @if( !empty($ProductCategory) && $ProductCategory->parents_id == $index ) selected @endif>{{ $row }}</option>
								@endforeach
							@endif
						</select>
			
					</td>
				</tr>

				<tr>
					<th>{{ $txt["product_category_name"] }}</th>
					<td>
						<input type="text" name="name" value="@if(!empty($ProductCategory)){{ $ProductCategory->name }}@endif"  placeholder="{{ $txt['product_category_name_input'] }}" required/>
					</td>
				</tr>

				<tr>
					<th colspan="2"><input type="submit" value="{{ $txt['send'] }}"/></th>
				</tr>															
			</tbody>
		</table>
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
</section>