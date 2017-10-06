<div class="form-group has-feedback">
	<select name="parents_store_type" class="form-control" required>
		<option value="">{{ $txt['plz_choose_store_type'] }}</option>
	    @foreach($store_type as $parent_id => $parent_row)
	    	<option value="{{ $parent_id }}"> {{ $parent_row["name"] }} </option>
	    @endforeach
	</select>
</div>

<div class="form-group has-feedback">
	<select name="store_type_id" class="form-control" required>
		<option value="">{{ $txt['plz_choose_child_store_type'] }}</option>
	    @foreach($store_type as $parent_id => $parent_row)
	        @foreach($parent_row["data"] as $child_id => $child_row)
	        <option parentsid="{{ $parent_id }}" value="{{ $child_id }}" class="child_store_type_id"> {{ $child_row["name"] }} </option>
	        @endforeach
	    @endforeach
	</select>
</div>
