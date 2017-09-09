var	month_order_view = function(){

		if ( $("body").find("#OrderStatus").length < 1 ) 
		{
			return false;
		};
		
		$.ajax({
			url: "/month_order_view",
			type: 'POST',
			success: function( data ) {

			var data1 = JSON.parse(data),
				data2 = $.map(data1, function(el) { return parseInt(el) });

				Highcharts.chart('OrderStatus', {
				    chart: {
				        type: 'column'
				    },
				    title: {
				        text: '每月訂單/銷單狀況'
				    },
				    xAxis: {
				        categories: [
				            'Jan',
				            'Feb',
				            'Mar',
				            'Apr',
				            'May',
				            'Jun',
				            'Jul',
				            'Aug',
				            'Sep',
				            'Oct',
				            'Nov',
				            'Dec'
				        ],
				        crosshair: true
				    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: '訂單數'
				        }
				    },
				    tooltip: {
				        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				            '<td style="padding:0"><b>{point.y:.0f} 單</b></td></tr>',
				        footerFormat: '</table>',
				        shared: true,
				        useHTML: true
				    },
				    plotOptions: {
				        column: {
				            pointPadding: 0.2,
				            borderWidth: 0
				        }
				    },
				    series: [{
				        name: '銷售',
				        data: data2

				    }]
				});

			}
		});

	},
	year_stock_view = function(){

		if ( $("body").find("#StockStatus").length < 1 ) 
		{
			return false;
		};

		$.ajax({
			url: "/year_stock_view",
			type: 'POST',
			success: function( data ) {

			var data1 = JSON.parse(data),
				Indata = $.map(data1['in'], function(el) { return parseInt(el) }),
				Outdata = $.map(data1['out'], function(el) { return parseInt(el) });
				
			    Highcharts.chart('StockStatus', {
			        chart: {
			            type: 'bar'
			        },
			        title: {
			            text: '每日出入庫狀況'
			        },
			        xAxis: [{
			            categories: [
				            'Jan',
				            'Feb',
				            'Mar',
				            'Apr',
				            'May',
				            'Jun',
				            'Jul',
				            'Aug',
				            'Sep',
				            'Oct',
				            'Nov',
				            'Dec'
				        ],
			            reversed: false,
			            labels: {
			                step: 1
			            }
			        }, { // mirror axis on right side
			            opposite: true,
			            reversed: false,
			            categories: [
				            'Jan',
				            'Feb',
				            'Mar',
				            'Apr',
				            'May',
				            'Jun',
				            'Jul',
				            'Aug',
				            'Sep',
				            'Oct',
				            'Nov',
				            'Dec'
				        ],
			            linkedTo: 0,
			            labels: {
			                step: 1
			            }
			        }],
			        yAxis: {
			            title: {
			                text: null
			            },
			            labels: {
			                formatter: function () {
			                    return Math.abs(this.value);
			                }
			            }
			        },

			        plotOptions: {
			            series: {
			                stacking: 'normal'
			            }
			        },

			        tooltip: {
			            formatter: function () {
			                return '<b>' + this.series.name + ', 數量 ' + this.point.category + '</b><br/>' +
			                    'Population: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0);
			            }
			        },

			        series: [{
			            name: '入庫',
			            data: Indata
			        }, {
			            name: '出庫',
			            data: Outdata
			        }]
			    });	

			}
		});

	},
	month_top_five = function(){

		if ( $("body").find("#HotSellTop5").length < 1 ) 
		{
			return false;
		};

		$.ajax({
			url: "/year_product_top5",
			type: 'POST',
			success: function( data ) {

			var data1 = JSON.parse(data),
				total = $.map(data1['total'], function(el) { return el }),
				top5 = $.map(data1['top5'], function(el) { return el });
				
				Highcharts.chart('HotSellTop5', {
				    chart: {
				        type: 'column'
				    },
				    title: {
				        text: '月銷售Top 5'
				    },
				    xAxis: {
				        type: 'category'
				    },
				    yAxis: {
				        title: {
				            text: '銷售量'
				        }

				    },
				    legend: {
				        enabled: false
				    },
				    plotOptions: {
				        series: {
				            borderWidth: 0,
				            dataLabels: {
				                enabled: true,
				                format: '{point.y:.0f}'
				            }
				        }
				    },

				    tooltip: {
				        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
				    },

				    series: [{
				        name: '年報表',
				        colorByPoint: true,
				        data: total
				    }],
				    drilldown: {
				        series: top5
				    }
				});

			}
		});

	};

month_order_view();
year_stock_view();
month_top_five();
