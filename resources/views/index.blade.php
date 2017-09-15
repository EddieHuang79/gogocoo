<section class="content-header">
	<h1>{{ $txt['Site'] }}</h1>
	<div class="Report">
		<div class="DataRow">
			<div class="ChildRow">
				<div class="OrderCnt">
					<div>
						<h6>{{ $txt['this_week_order_cnt'] }}</h6>
						<h1>{{ $week_order_cnt }}</h1>								
					</div>		
				</div>
				<div class="OrderCancelCnt">
					<div>
						<h6>{{ $txt['this_week_cancel_order_cnt'] }}</h6>
						<h1>{{ $week_cancel_order_cnt }}</h1>								
					</div>		
				</div>				
			</div>
			<div class="ChildRow">
				<div class="TodayInWs">
					<div>
						<h6>{{ $txt['today_in_ws'] }}</h6>
						<h1>{{ $today_in_ws_cnt }}</h1>								
					</div>		
				</div>
				<div class="TodayOutWs">
					<div>
						<h6>{{ $txt['today_out_ws'] }}</h6>
						<h1>{{ $today_out_ws_cnt }}</h1>								
					</div>		
				</div>
			</div>
		</div>
		<div class="ReportrRow">
			<div id="OrderStatus" class="OrderStatus"></div>
			<div id="StockStatus" class="StockStatus"></div>
		</div>
		<div class="ReportrRow">
			<div id="ProductCategory" class="ProductCategory"></div>
			<div id="HotSellTop5" class="HotSellTop5"></div>
		</div>
		<div class="ReportrRow">
			<div id="HotSellTop5Stack" class="HotSellTop5Stack"></div>
			<div></div>
		</div>
	</div>
</section>