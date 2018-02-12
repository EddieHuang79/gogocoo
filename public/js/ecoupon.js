if ( $(".ecoupon").length > 0 ) 
{

	var ChangeEcouponType = function(){

		var type = $(this).val();

			$(".ecouponType").hide();

			if ( type !== "" ) 
			{

				$(".ecouponType"+type).show();
			
			};

	},
	ChangeEcouponMatchType = function(){

		var type = parseInt($(this).val());

			$(".storeType").hide();
			$(".matchType").hide();

			if ( type === 3 ) 
			{

				$(".storeType").show();

			};

			if ( type === 4 ) 
			{

				$(".matchType4").show();

			};

	},
	EcouponFormSubmit = function(){

		$(this).parents("form").submit();

	},
	EcouponInit = function(){

		var type = parseInt($("[name='type']").val()),
			ecoupon_content = JSON.parse($("[name='ecoupon_content']").val()),
			i = 0;


			$("[name='type']").trigger("change");
			$("[name='match_type']").trigger("change");

// console.log(ecoupon_content);

			if ( type === 3) 
			{

				$.map(ecoupon_content, function(item, index){

					// var data = item.split(',');

					console.log(index);
					console.log(item);

					if ( $(".ecoupon_rule").eq(i).length === 0 ) 
					{

						$(".add_block").click();

					};

					$(".ecoupon_rule").eq(i).find("[name='ecoupon_content[3]["+index+"][]']:last").val(item[0]);

					i++;

				});

			};

	};

	setTimeout(function(){ EcouponInit(); }, 1000);

	$(".storeType").hide();

	$("[name='type']").on("change", ChangeEcouponType);
	$("[name='match_type']").on("change", ChangeEcouponMatchType);
	// $(".EcouponFormSubmit").on("click", EcouponFormSubmit);

};