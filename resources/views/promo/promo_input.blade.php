<section class="content-header">
    @if(!empty($ErrorMsg))
        <div class="error">{{ $txt['promo_repeat'] }}</div>
    @endif
	<h1>{{ $txt["promo_price_setting"] }}</h1>
	@include('webbase.breadcrumb')
</section>
<section class="content">
	<div class="row">
        <div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">{{ $txt["promo_price_setting"] }}</h3>
				</div>
				<form @if( isset($promo->id) ) action="/promo/{{ $promo->id }}" @else action="/promo" @endif action="/promo" method="POST" enctype="multipart/form-data" id="promoForm">
					<div class="box-body">
						<div class="form-group">
							<label>{{ $txt["promo_price"] }}</label>
							{{ $txt['cost_unit'] }}
							<input type="text" name="cost" class="form-control" placeholder="{{ $txt['promo_price_input'] }}" @if(!empty($promo)) value=" {{ $promo->cost }}" @endif @if( isset($OriData["cost"]) && !empty($OriData["cost"]) ) value="{{ $OriData['cost'] }}" @endif required>
						</div>
						<div class="form-group">
							<label>{{ $txt["status"] }}</label>
							<div class="radio">
								<label>		
									<input type="radio" value="1" name="status" @if( !empty($promo) && $promo->status == 1 ) checked  @endif required/>{{ $txt["enable"] }}
								</label>
							</div>								
							<div class="radio">
								<label>									
									<input type="radio" value="2" name="status" @if( !empty($promo) && $promo->status == 2 ) checked  @endif required/>{{ $txt["disable"] }}
								</label>
							</div>							
						</div>
						<div class="form-group">
							<label>{{ $txt["start_date"] }}</label>
							<input type="text" id="start_date" name="start_date" class="form-control" @if( !empty($promo) &&  strtotime($promo->start_date) > 0 ) value="{{ $promo->start_date }}" @endif @if( isset($OriData["start_date"]) && !empty($OriData["start_date"]) ) value="{{ $OriData['start_date'] }}" @endif required/> <br />
						</div>
						<div class="form-group">
							<label>{{ $txt["end_date"] }}</label>
							<input type="text" id="end_date" name="end_date" class="form-control" @if( !empty($promo) &&  strtotime($promo->end_date) > strtotime('1970-01-01 23:59:59') ) value="{{ $promo->end_date }}" @endif @if( isset($OriData["end_date"]) && !empty($OriData["end_date"]) ) value="{{ $OriData['end_date'] }}" @endif required/>
						</div>
						<div class="form-group">
							<label><input type="submit" class="btn btn-primary" value="{{ $txt['send'] }}"/></label>
						</div>															
						@if(!empty($promo))
							<input type="hidden" name="_method" value="patch">				
						@endif	
						<input type="hidden" name="mall_shop_id" @if(!empty($promo)) value="{{ $promo->mall_shop_id }}" @endif @if(isset($mall_shop_id)) value="{{ $mall_shop_id }}" @endif>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
					</div>
				</form>
	      	</div>
        </div>
  	</div>
</section>