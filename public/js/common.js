var Show_current_position = function(){
		var service_id = $(".service_id").val();
		if (parseInt(service_id) > 0)
		{
			$("[page='page"+service_id+"']").parents(".treeview-menu").addClass("active");
			$("[page='page"+service_id+"']").parents(".treeview-menu").toggle();
		};
	},
	RemoveBtn = function(){
		var target_class = $(this).attr("target"),
			target_len = $("."+target_class).length;
		if (target_len > 1) 
		{
			$(this).parents(".clone").remove();
		}
		else
		{
			$(this).parents(".clone").find("input[type='text'],select,input[type='hidden']").val('');
		}
	},
	AddBtn = function(){
		var target_class = $(this).attr("target");
		$("."+target_class+":first").clone().insertAfter("."+target_class+":last");
		$("."+target_class+":last").find("input[type='text'],select,input[type='hidden']").val('');
	},
	ClickAll = function(){
		var group = $(this).attr("group"),
			Dom = $("[group='"+group+"']"),
			status = Dom.prop("checked") == true ? true : false ;

		Dom.each(function(e){
			$(this).prop("checked", status);
		});
	},
	Search_tool_display = function() {
		var txt = $("#search_form:visible").length <= 0 ? "隱藏" : "顯示" ;
		$("#search_form").toggle();
		$(".ShowHide").val(txt);
	},
	Refresh_verify_code = function() {
		$.ajax({
			url: "/refresh",
			data: "",
			type: 'POST',
			success: function( response ) {
				response.forEach(function(value, key){
					$(".verify_code>img").eq(key).attr("src", "../_images/"+value+".png");
				});
			}
		});
	},
	Ajax_init = function() {
		$.ajaxSetup({
		  headers: {
		    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		  }
		});
	},
	RoleListBtn = function() {
		var label_txt = $(this).parents(".auth-div").find("ul.auth:visible").length > 0 ? "+" : "-" ;
		$(this).parents(".auth-div").find("ul.auth").toggle();
		$(this).text(label_txt);
	},
	ClosePopup = function() {
		$(".lightbox").hide();
		var placeholder = $("[name='store_type']").attr("placeholder_bk");
		if ( placeholder !== "undefined" &&  placeholder != "") 
			$("[name='store_type']").attr("placeholder", placeholder);
	},
	call_store_type = function() {
		var placeholder = $(this).attr("placeholder");
		$(this).attr("placeholder", "");
		$(this).attr("placeholder_bk", placeholder);
		$(".store_type_border").show();
		$(".store_type>li").hide();
		$(".store_type>li.parent").show();
		$(".store_type>li").removeClass('store_type_choose');
	}
	store_type_find_child = function() {
		var optid = $(this).attr("optid");
		$(".store_type>li").hide();
		$(".store_type>li[optid='"+optid+"']").addClass('store_type_choose').show();
		$(".store_type>li[parentsid='"+optid+"']").show();
	},
	set_store_type = function() {
		var choose_opt = $(this).text(),
			optid = $(this).attr("optid");
		$("[name='store_type']").val(choose_opt);
		$("[name='store_type_id']").val(optid);
		$(".store_type_border").hide();
	},
	change_store = function() {
		var store_id = $(this).attr("storeId"),
			selected = $(this).hasClass("selected");

		if ( selected === false ) 
		{
			$(".switch_store").val(store_id);
			$("form.change_store").submit();
		};

	},
	show_shop_div = function(){

		var mall_product_id = $(this).attr("MallProductId"),
			mall_product_lightbox_width = ( $(".mall_product_lightbox").width() / 2 ) * -1;
		
		$(".mall_product_lightbox").css( "margin-left", mall_product_lightbox_width );

		$(".mall_child_product").find("div").remove();

		$.ajax({
			url: "/get_mall_product",
			data: "mall_product_id="+mall_product_id,
			type: 'POST',
			success: function( response ) {
				var data = JSON.parse(response);
				$(".mall_product_name").text(data.mall_product_name);
				$(".mall_product_description").text(data.mall_product_description);
				// $(".mall_product_pic").find("img").attr("src",data.mall_product_pic);
				$(".mall_product_cost").text("NT. 0");
				$('.mall_product_spec>select').find("option[classkey='append']").remove();
				$('[name="mall_product_number"]').val("1");
				$.map(data.include_service, function(value, index) {
					var dom = "<div>"+value+"</div>";
					$(".mall_child_product").append(dom);
				});
				data.mall_product_spec.forEach(function(value, key){
					$('.mall_product_spec>select').append($('<option>', {
					    value: value.id,
					    spec: value.cost+'/'+value.date_spec,
					    text: 'NT.'+value.cost+' / '+value.date_spec+'天',
					    classkey: "append"
					}));
				});
				$('.mall_product_spec>select').val('');
				$(".mall_product_lightbox").fadeIn(200);
			}
		});		
	},
	plus = function(){
		var target = $(this).attr("target"),
			ori_value = $(target).val(),
			new_value = parseInt(ori_value) + 1;

		$(target).val(new_value);

		shop_calc();
			
	},
	minus = function(){
		var target = $(this).attr("target"),
			ori_value = $(target).val(),
			new_value = parseInt(ori_value) > 1 ? parseInt(ori_value) - 1 : parseInt(ori_value);

		$(target).val(new_value);

		shop_calc();
			
	},
	shop_calc = function(){
		var spec_tmp = $("[name='mall_product_spec']").val(),
			spec = $("[name='mall_product_spec']>option[value='"+spec_tmp+"']").attr("spec");
			number = $("[name='mall_product_number']").val(),
			spec_data = spec.split("/"),
			total = parseInt(number) * parseInt(spec_data[0]),
			price_txt = isNaN(total) ? "NT. 0" : "NT. "+total,
			total_price = isNaN(total) ? 0 : total ;
		$(".mall_product_cost").text(price_txt);
	},
	ShopSubmit = function(){
		var spec = $("[name='mall_product_spec']"),
			number = $("[name='mall_product_number']");
		if ( spec.val() == '' ) 
		{
			spec.focus();
			return false;
		};
		$.ajax({
			url: "/shop_buy_process",
			data: "mall_product_spec="+spec.val()+"&mall_product_number="+number.val(),
			type: 'POST',
			success: function( response ) {
				var data = JSON.parse(response);
				$(".shop_finish").find(".subject").text(data.subject);
				$(".shop_finish").find(".content").text(data.content);
				$(".mall_product_lightbox").hide();
				$(".shop_finish").fadeIn();
			}
		});	
	},
	dragHandler = function(e){
		e.preventDefault();
	},
	dropImage = function(e){
		e.preventDefault();
		var files = e.dataTransfer.files,
			objXhr = new XMLHttpRequest(),
			url = "/photo_upload_process",
			objForm = new FormData();
		objXhr.open('POST', url) ;
		objForm.append('photo_upload_preview', files[0]);
		objXhr.send(objForm);
	

		objXhr.upload.onprogress = function(e) {

		    if (e.lengthComputable)
		    {
		        var intComplete = (e.loaded / e.total) * 100 | 0,
		        	elProgress = $("#upload_progress");

		        elProgress.text(intComplete + '%') ; // 控制進度條的顯示數字，例如65%

		        elProgress.attr("style","width: "+intComplete+'%;') ; // 控制進度條的長度

		        elProgress.attr('aria-valuenow', intComplete) ;
		    }
		};

		objXhr.onload = function(e){
		    /*接收後端傳回的Response，本範例的後端程式會傳回每個圖檔是否都上傳成功，以及上傳成功的圖片數量*/

		    var arrData = JSON.parse(objXhr.responseText),
		    	img = new Image(),
		    	images_container = $(".upload_image_preview") ;
		    	// $(".crop_txt").show();
		        img.src = arrData ;
		        img.className = 'image' ;
		        img.id = 'crop_image' ;
		        images_container.append(img) ;
		    $(".autoUpload").hide();
		    $(".cropImage").show();
			$('#crop_image').cropper({
				aspectRatio: 1,
				background: false,
				zoomable: false

			});
		};
	},
	upload_crop_image = function(e){
		// Upload cropped image to server if the browser supports `HTMLCanvasElement.toBlob`
		getRoundedCanvas($('#crop_image').cropper('getCroppedCanvas')).toBlob(function (blob) {
				var formData = new FormData();

				formData.append('photo_upload', blob);

				$.ajax('/photo_upload_process', {
					method: "POST",
					data: formData,
					processData: false,
					contentType: false,
					success: function (response) {
						location.reload();
					},
					error: function (error) {
						console.log(error+'Upload error');
					}
				});
		});
	},
	getRoundedCanvas = function(sourceCanvas){
		var canvas = document.createElement('canvas'),
			context = canvas.getContext('2d'),
			width = sourceCanvas.width,
			height = sourceCanvas.height;

		canvas.width = width;
		canvas.height = height;
		context.beginPath();
		context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI);
		context.strokeStyle = 'rgba(0,0,0,0)';
		context.stroke();
		context.clip();
		context.drawImage(sourceCanvas, 0, 0, width, height);

		return canvas;
	},
	AutoComplete = function(){
		var type = $(this).attr("AutoCompleteType"),
			url = $(this).attr("site");

			switch(type)
			{

				case 'product_name':

					url += "/product/get_product_list";
				
					break;

			}

			$( "[name='"+type+"']" ).autocomplete({
		    	source: url,
		    	close: function( event, ui ) {
		    		$( "[name='product_name']" ).attr("specId", "");
		    		get_product_spec();
		    	}
		    });

	},
	clickAllFunction = function(){
		var	target = $(this).attr("target"),
			Dom = $("."+target),
			status = $(this).prop("checked") == true ? true : false ;

		Dom.each(function(e){
			$(this).prop("checked", status);
		});

	},
	get_product_spec = function(){
		var	product_name = $( "[name='product_name']" ).val(),
			url = $( "[name='product_name']" ).attr("site");

		if ( product_name == '' || typeof(product_name) == "undefined" ) 
		{
			return false;
		};

		$.ajax({
			url: url+"/get_product_spec",
			data: "product_name="+product_name,
			type: 'POST',
			success: function( response ) {
				var data = JSON.parse(response),
					spec_id = $( "[name='product_name']" ).attr("specId");
				$('[name="spec_id"]>option.append').remove();
				data.forEach(function(value, key){
					var	spec = "顏色:"+value.value.color+"，尺寸:"+value.value.size+"，字型:"+value.value.font_type;
					$('[name="spec_id"]').append($('<option>', {
					    value: value.id,
					    text: spec,
					    class: "append"
					}));
				});
				$('[name="spec_id"]').val(spec_id);
			}
		});	
	},
	count_lightbox_width = function(){
		var lightbox_width = ( $(".lightbox").width() / 2 ) * -1;
		$(".lightbox").css( "margin-left", lightbox_width );
	};


Show_current_position();
Ajax_init();
get_product_spec();
count_lightbox_width();

$(".addbtn").on("click", AddBtn);
$(document).on("click", ".removeLabel", RemoveBtn);
$("[alt='main']").on("click", ClickAll);
$("#search_tool>.search_block>.tool>[name='date']").datepicker({format: "yy-mm-dd"});
$(".ShowHide").on("click", Search_tool_display);
$(".refresh_verify_code").on("click", Refresh_verify_code);
$(".RoleListBtn").on("click", RoleListBtn);
$(".close_btn").on("click", ClosePopup);
$("[name='start_date']").datepicker({format: "yyyy-mm-dd"});
$("[name='end_date']").datepicker({format: "yyyy-mm-dd"});
$("[name='in_warehouse_date']").datepicker({format: "yyyy-mm-dd"});
$("[name='out_warehouse_date']").datepicker({format: "yyyy-mm-dd"});
$("[name='store_type']").on("click", call_store_type);
$(".store_type>li.parent").on("click", store_type_find_child);
$(".store_type>li.child").on("click", set_store_type);
$(".branch_select>li").on("click", change_store);
$(".mall_product>img").on("click", show_shop_div);
$(".plus").on("click", plus);
$(".minus").on("click", minus);
$("[name='mall_product_spec']").on("change", shop_calc);
$(".crop").on("click", upload_crop_image);
$(".autocomplete").on("click", AutoComplete);
$(".clickAll").on("click", clickAllFunction);
