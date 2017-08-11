<label for="input_store_type" class="sr-only">{{ $txt['plz_choose_store_type'] }}</label>
<input type="text" placeholder="{{ $txt['plz_choose_store_type'] }} ▼" value="@if(!empty($store)) $store->store_type_name @endif" name="store_type" class="form-control" required>
<input type="hidden" name="store_type_id" value="@if(!empty($store)) $store->store_type @endif">
<div class="lightbox store_type_border">
    <label class="close_btn"> X </label> 
    <ul class="store_type ul_to_select">
        @foreach($store_type as $parent_id => $parent_row)
        <li class="parent" optid="{{ $parent_id }}"> {{ $parent_row["name"] }} </li>
            @foreach($parent_row["data"] as $child_id => $child_row)
            <li class="child" optid="{{ $child_id }}" parentsid={{ $parent_id }}> {{ $child_row["name"] }} </li>
            @endforeach
        @endforeach
    </ul>
</div>
