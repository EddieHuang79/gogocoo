<section class="content-header">
	<h1>{{ $txt['Site'] }}</h1>
	@include('webbase.breadcrumb')
</section>

<section class="content">

	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>{{ $week_order_cnt }}</h3>
					<p>{{ $txt['this_week_order_cnt'] }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-bag"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{ $week_cancel_order_cnt }}</h3>
					<p>{{ $txt['this_week_cancel_order_cnt'] }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>{{ $today_in_ws_cnt }}</h3>
					<p>{{ $txt['today_in_ws'] }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-add"></i>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{ $today_out_ws_cnt }}</h3>
					<p>{{ $txt['today_out_ws'] }}</p>
				</div>
				<div class="icon">
					<i class="ion ion-pie-graph"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<img src="{{ URL::asset('webimage/loading.gif') }}" alt="loadingImg" class="loadingImg">
	</div>

	<div class="row report">

		<section class="col-lg-6 connectedSortable">
			<div class="box box-success">
				<div id="OrderStatus" class="OrderStatus"></div>
			</div>
			<div class="box box-success">
				<div id="ProductCategory" class="ProductCategory"></div>
			</div>
        </section>

		<section class="col-lg-6 connectedSortable">
			<div class="box box-success">
				<div id="StockStatus" class="StockStatus"></div>
			</div>
			<div class="box box-success">
				<div id="HotSellTop5" class="HotSellTop5"></div>
			</div>
        </section>

	</div>

</section>