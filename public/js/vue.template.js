var basicForm = {

		template: '<form :action="list.action" method="POST" enctype="multipart/form-data">\
						<div class="box-body">\
							<div v-for="(item, index) in list">\
								<div v-if="item.type === 1 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<input type="text" :name="item.key" class="form-control" :value="item.value" :placeholder="item.placeholder" required/>\
								</div>\
								<div v-if="item.type === 2 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<select :name="item.key" class="form-control" @change="EventFunc(index)" required>\
										<option value="">{{ txt.select_default }}</option>\
										<option v-for="(ChildItem, ChildIndex) in item.data" :value="ChildIndex">{{ ChildItem }}</option>\
									</select>\
								</div>\
								<div v-if="item.type === 3 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ txt.status }}</label><h6>{{ item.desc }}</h6>\
									<div class="radio" v-for="(ChildItem, ChildIndex) in item.data">\
										<label>\
											<input type="radio" :value="ChildIndex" :name="item.key" required/>{{ ChildItem }}\
										</label>\
									</div>\
								</div>\
								<div v-if="item.type === 4 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<div :class="item.key" v-for="(dataItem, dataIndex) in item.data">\
										<input type="button" :value="txt.remove_block" class="remove btn btn-primary" :target=" \'.\' + item.key" @click="removeBlock($event)"> <br /><br />\
										<div v-for="(childItem, childIndex) in item.child"><label>{{ childItem.childTitle }}</label>: <input type="text" :name="childItem.key" class="form-control" :value="dataItem[childItem.childTitle]" /></div>\
									</div>\
									<input type="button" :value="txt.add_block" class="add_block btn btn-primary" :target=" \'.\' + item.key" @click="addBlock($event)">\
								</div>\
								<div v-if="item.type === 5 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<div>{{ item.value }}</div>\
								</div>\
								<div v-if="item.type === 6 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<div v-for="(dataItem, dataIndex) in item.data">\
										<input type="checkbox" :value="dataItem.id" :name="item.key" />{{ dataItem.name }}\
									</div>\
								</div>\
								<div v-if="item.type === 7 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<input type="password" :name="item.key" class="form-control" :value="item.value" :placeholder="item.placeholder" required/>\
								</div>\
								<div v-if="item.type === 8 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<input type="file" :name="item.key"> <br />\
									<input type="button" :value="txt.downaload_example" class="btn btn-primary" @click="linkTo(item.Samplelink)" >\
								</div>\
								<div v-if="item.type === 9 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<select :name="item.key" class="form-control" @change="EventFunc(index)" required>\
										<option value="">{{ txt.select_default }}</option>\
										<option v-for="(ChildItem, ChildIndex) in item.data" :value="ChildIndex">{{ ChildItem }}</option>\
									</select> <br />\
									<select :name="item.SubMenuKey" class="hide ajaxSelect form-control">\
										<option value="">{{ txt.select_default }}</option>\
									</select>\
								</div>\
								<div v-if="item.type === 10 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<input type="text" :name="item.key" class="form-control autocomplete" :value="item.value" :placeholder="item.placeholder" @click="autocomplete($event, site, item.key)" required/>\
								</div>\
								<div v-if="item.type === 11 && item.display === true" class="form-group" :class="item.attrClass">\
									<input type="hidden" :name="item.key" :value="item.value">\
								</div>\
								<div v-if="item.type === 12 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<div>{{ item.value }}</div>\
								</div>\
								<div v-if="item.type === 13 && item.display === true" class="form-group" :class="item.attrClass">\
									<label>{{ item.title }}</label><h6>{{ item.desc }}</h6>\
									<div class="auth-div" v-for="(ChildItem, ChildIndex) in item.data">\
										<label v-if="!Array.isArray(ChildItem.child)" class="RoleListBtn" @click="extendRoleList($event)">+</label>\
										<input type="checkbox" :value="ChildItem.id" alt="main" :class="\'auth\' + ChildItem.id" :name="item.key" @click="clickAll(\'auth\' + ChildItem.id)">{{ ChildItem.name }}\
										<ul class="auth">\
											<li v-for="(SubChildItem, SubChildIndex) in ChildItem.child"><input type="checkbox" :value="SubChildItem.id" :class="\'auth\' + ChildItem.id" :name="item.key" >{{ SubChildItem.name }}</li>\
										</ul>\
									</div>\
								</div>\
							</div>\
							<div class="form-group">\
								<input type="submit" class="btn btn-primary" :value="txt.send "/>\
								<input type="hidden" name="_method" :value="list.method" />\
								<input type="hidden" name="_token" :value="token" />\
							</div>\
						</div>\
					</form>',
		props: ["txt", "list", "token", "site"],
		mounted: function(){
			
			this.$nextTick(function() {
		
				var list = this.list;

				$.map(list, function(value, index){

					if ( value.hasPlugin !== '' ) 
					{

						switch(value.hasPlugin)
						{

							case "DateTimePicker":

								$("[name='"+value.key+"']").datetimepicker({
																minDate: new Date(),
																dateFormat: 'yy/mm/dd', 
																timeFormat: 'HH:mm:ss'
															});

								break;

							case "DatePicker":

								$("[name='"+value.key+"']").datepicker({dateFormat: "yy-mm-dd"});

								break;

						}

					};

					if ( value.value !== '' && parseInt(value.type) === 2 ) 
					{

						$("[name='"+value.key+"']").val(value.value);

						basicForm.$refs.basicForm.EventFunc( index );

					}

					if ( value.value !== '' && parseInt(value.type) === 3 ) 
					{

						$("[name='"+value.key+"'][value='"+value.value+"']").attr("checked", true);

					}

					if ( value.value !== '' && parseInt(value.type) === 6 ) 
					{

						var data = value.value;

							$.map(data, function(item){

								$("[name='"+value.key+"'][value='"+item+"']").attr("checked", true);

							});						

					}

					if ( value.value !== '' && parseInt(value.type) === 9 ) 
					{

						$("[name='"+value.key+"']").val(value.value);

						basicForm.$refs.basicForm.EventFunc( value.SubMenuKey );

						setTimeout(function(){ $(".ajaxSelect").val(value.SubValue) }, 500);

					}

					if ( value.value !== '' && parseInt(value.type) === 13 ) 
					{

						var data = value.value;

							$.map(data, function(item){

								$(".auth"+item).prop("checked", true);

								console.log(".auth"+item);

							});								

					}

					if ( value.required === false ) 
					{

						$("[name='"+value.key+"']").attr("required", false);					

					}

				});

			})			
		
		},
		methods: {

			addBlock: function(event) {

				var target = $(event.currentTarget).attr("target"),
					limit = $(event.currentTarget).attr("limit"),
					len = $(target).length;

					if (limit > 0 && limit <= len) 
					{
						return false;
					};

					$(target+":first").clone().insertAfter(target+":last");
					$(target+":last").find("input[type='text'],select,input[type='number']").val('');

			},

			removeBlock: function() {

				var target = $(event.currentTarget).attr("target"),
					len = $(target).length;

				if (len > 1) 
				{
					$(event.currentTarget).parents(target).remove();
				};

				if (len <= 1) 
				{
					$(event.currentTarget).parents(target).find("input[type='text'],select,input[type='number']").val('');
				};	
				
			},

			EventFunc: function(key){

				switch(key)
				{

					case 'ecoupon_type':

						var type = parseInt($("[name='type']").val());

							$(".ecouponType").addClass("hide");

							$(".ecouponType").find("input").attr("required", false);

							$(".ecouponType" + type).removeClass("hide");

							$(".ecouponType" + type).find("input").attr("required", true);

						break;


					case 'ecoupon_match_type':

						var type = parseInt($("[name='match_type']").val());

							$(".matchType").find("input,select").attr("required", false);

							$(".matchType").addClass("hide");

							$(".matchType" + type).removeClass("hide");

							$(".matchType" + type).find("input,select").attr("required", true);

						break;


					case 'category':

							this.ajaxSelect( "category" );

						break;

					case 'store_type_select':
					case 'store_type_id':

							this.ajaxSelect( "store_type_select" );

						break;

				}

			},

			linkTo: function(link){

				location.href = link;

			},

			ajaxSelect: function(key){

				switch(key)
				{

					case "category":

							$('.ajaxSelect').hasClass("hide") ? $('.ajaxSelect').removeClass("hide") : "" ;

							$.ajax({
								url: "/get_child_list",
								type: 'POST',
								data: "parents_id="+$("[name='parents_category']").val(),
								success: function( response ) {

									var return_data = JSON.parse(response);

									$('.ajaxSelect').find("option.append").remove();

									$.map(return_data, function(value, index) {
										$('.ajaxSelect').append($('<option>', {
										    value: index,
										    text: value,
										    class: "append"
										}));
									});
								
								}
							});	

						break;


					case "store_type_select":

							$('.ajaxSelect').hasClass("hide") ? $('.ajaxSelect').removeClass("hide") : "" ;

							$.ajax({
								url: "/get_child_store",
								type: 'POST',
								data: "parents_store="+$("[name='parents_store_type']").val(),
								success: function( response ) {

									var return_data = JSON.parse(response);

									$('.ajaxSelect').find("option.append").remove();

									$.map(return_data, function(value, index) {
										$('.ajaxSelect').append($('<option>', {
										    value: index,
										    text: value,
										    class: "append"
										}));
									});
								
								}
							});	

						break;

				}

			},

			autocomplete: function(event, site, type){


				var url = site;

					switch(type)
					{

						case 'product_name':

							url += "/product/get_product_list";

							$( "[name='"+type+"']" ).autocomplete({
						    	source: url,
						    	close: function( event, ui ) {

						    	},
						    	select: function( event, ui ) {
						    		var product_id = ui.item.index,
						    			keep_for_days = ui.item.keep_for_days;
						    		$("[name='product_id']").val(product_id);
						    		$("[name='keep_for_days']").val(keep_for_days);
						    	},
						    	response: function( event, ui ){


						    	}
						    });

							break;

					}

			},

			extendRoleList: function(event){

				var label_txt = $(event.currentTarget).parents(".auth-div").find("ul.auth:visible").length > 0 ? "+" : "-" ;
					
					$(event.currentTarget).parents(".auth-div").find("ul.auth").toggle();
					
					$(event.currentTarget).text(label_txt);

			},

			clickAll: function(target){

				$("."+target).prop("checked", $("."+target+":first").prop("checked"));

			}

		}
		
	},
	basicList = {

		template: '<table class="table table-bordered table-striped">\
						<thead>\
							<tr>\
								<th v-for="(item, index) in list.title">\
									<div v-if="item.clickAll"><input type="checkbox" class="clickAll" @click="clickAll(item.target)"> {{ txt.select_all }}</div>\
									<div v-if="typeof item === \'string\'">{{ item }}</div>\
								</th>\
							</tr>\
						</thead>\
						<tbody>\
							<tr v-for="item2 in list.data">\
								<td v-for="row in item2.data">\
									<div v-if="!Array.isArray(row)">\
										<div v-if="row.isImage && row.data != \'\'"><img :src="row.data" alt="ProductPic" :class="row.class"/></div>\
										<div v-if="row.checkbox"><input type="checkbox" :name="row.key" :value="row.id" :class="row.class"/></div>\
										<div v-if="typeof row !== \'object\'">{{ row }}</div>\
									</div>\
									<div v-if="Array.isArray(row)">\
										<div v-for="(row1,index) in row">\
											<div>{{ row1 }}</div>\
										</div>\
									</div>\
								</td>\
								<td>\
									<input v-if="item2.Editlink" type="button" class="btn btn-primary" :value="txt.edit" @click="linkTo(item2.Editlink)"/>\
									<input v-if="item2.ExtendBtn" type="button" class="btn btn-primary extend_account_deadline" :value="txt.extend_deadline" @click="extend_account_deadline( item2 )"/>\
									<input v-if="item2.Clonelink" type="button" class="btn btn-primary" :value="txt.clone" @click="linkTo(item2.Clonelink)"/>\
									<input v-if="item2.PromoSettinglink" type="button" class="btn btn-primary" :value="txt.promo_price_setting" @click="linkTo(item2.PromoSettinglink)"/>\
									<div v-if="item2.actionWord">{{ item2.actionWord }}</div>\
								</td>\
							</tr>\
							<tr v-if="list.data.length < 1">\
								<th :colspan="list.title.length + 1">{{ txt.find_nothing }}</th>\
							</tr>\
						</tbody>\
					</table>',
		props: ["txt", "list"],
		methods: {

			linkTo: function(link){

				location.href = link;

			},

			extend_account_deadline: function( item ){
				
				$(".popup_option").find(".account").text( item.data.store_name );
				$("[name='user_id']").val( item.id );

				$.ajax({
					url: "/get_extend_deadline_option",
					type: 'POST',
					success: function( response ) {

						var data = JSON.parse(response);

						if ( data.length !== 0 ) 
						{

							$('[name="date_spec"]>.append').remove();

							$.map(data, function(value, index) {
								$('[name="date_spec"]').append($('<option>', {
								    value: index,
								    text: value,
								    class: "append"
								}));
							});

						}
						else
						{

							$("div.popup_option").text("擴充道具不足，請前往商城購買！");
							$("div.mall_product_btn").text("");
							$("div.mall_product_btn").append("<button class=\"btn btn-lg btn-primary btn-block\" type=\"button\" onclick=\"location.href='/buy';\">前往商城購買</button>");

						}

						$(".extend_lightbox").show();
					
					}
				});	

			},

			clickAll: function(target){

				$("."+target).prop("checked", $(".clickAll").prop("checked"));

			}

		}

	},
	shopList = {

		template: '<div>\
					<div class="mall_product" v-for="item in list" @click="callLightBox(item.id)">\
						<img :src="item.pic" :alt="item.product_name" :MallProductId="item.id">\
					</div>\
					<div class="mall_product" v-if="list.length < 1">{{ txt.find_nothing }}</div></div>',
		props: ["txt", "list"],
		methods: {

			callLightBox: function(id){

				var mall_product_lightbox_width = ( $(".mall_product_lightbox").width() / 2 ) * -1;
				
				$(".mall_product_lightbox").css( "margin-left", mall_product_lightbox_width );

				$(".mall_child_product").find("div").remove();

				$.ajax({
					url: "/get_mall_product",
					data: "mall_product_id="+id,
					type: 'POST',
					success: function( response ) {
						var data = JSON.parse(response),
							total = parseInt(data.promo) > 0 ? parseInt(data.promo) * 1 : parseInt(data.cost) * 1,
							scope = {
										mallProductName: data.mall_product_name,
										cost: parseInt(data.cost),
										promo: parseInt(data.promo),
										subTotal: total,
										ecouponMinus: 0,
										total: total,
										mallShopId: data.mall_shop_id,
										includeService: [],
										costClass: parseInt(data.promo) > 0 ? "line-through" : "",
										ecouponList: data.ecoupon_list
									};

							$.map(data.include_service, function(row) {
		
								scope.includeService[ scope.includeService.length ] = {
									productName: row['product_name'],
									number: row['number'],
									dateSpec: row['date_spec']
								}

							});

							shopList.$refs.mallProductLightbox.data = scope ;

							$(".mall_product_lightbox").fadeIn(200);

					}
				});

			}

		}

	},
	mallProductLightbox = {

		template: '<div class="lightbox mall_product_lightbox">\
						<form method="POST" action="/shop_buy_process" id="ShopForm">\
							<label class="close_btn" @click="ClosePopup"> X </label>\
							<div class="mall_product_name">{{ data.mallProductName }}</div>\
							<hr />\
							<div class="mall_child_product">\
								{{ txt.include_service }}\
								<div v-for="item in data.includeService"> {{ item.productName }} X {{ item.number }} 可用 {{ item.dateSpec }} 天 </div>\
							</div>\
							<div class="mall_product_option">\
								<div class="mall_product_spec">\
									<div> {{ txt.cost_unit }} <label class="cost" :class="data.costClass"> {{ data.cost }} </label>  </div>\
									<div class="promo_price" v-if="data.promo > 0"> {{ txt.promo_price }} {{ txt.cost_unit }} <label class="promo_price"> {{ data.promo }} </label>  </div>\
								</div>\
								<div class="mall_product_number">\
									<div class="number_btn minus" @click="minusNumber">-</div> <div><input type="text" name="mall_product_number" :value="defaultNumber" size="1" style="width: 30px; text-align: center;" readonly="true"></div> <div class="number_btn plus" @click="addNumber">+</div>\
								</div>\
							</div>\
							<div style="width: 85%; margin: 0px auto;" v-if="data.ecouponList.length > 0">\
								<select class="form-control" name="Ecoupon" @change="getEcouponDiscount($event)">\
									<option value="">{{ txt.ecoupon_select_default }}</option>\
									<option v-for="item in data.ecouponList" :value="item.code">{{ item.name }}</option>\
								</select>\
							</div>\
							<div class="mall_product_cost">\
								<div>{{ txt.sub_total }} NT. {{ data.subTotal }}</div>\
								<div v-if="data.ecouponMinus < 0">{{ txt.ecoupon_discount }} NT. {{ data.ecouponMinus }}</div>\
								<div>{{ txt.total }} NT. {{ data.total }}</div>\
							</div>\
							<div class="mall_product_btn">\
								<input type="hidden" class="mall_shop_id" name="mall_shop_id" :value="data.mallShopId">\
								<input type="hidden" name="total" :value="data.total">\
								<input type="submit" :value="txt.buy" class="btn btn-primary">\
								<input type="button" :value="txt.back" class="btn btn-primary" @click="ClosePopup">\
								<input type="hidden" name="_token" :value="token">\
							</div>\
						</form>\
					</div>',
		data: function() {
			return {
				data: {
					mallShopId: 0,
					mallProductName: "",
					cost: "",
					promo: 0,
					subTotal: 0,
					ecouponMinus: 0,
					total: 0,
					includeService: [],
					costClass: "",
					ecouponList: []
				},
				defaultNumber: 1
			}
		},
		props: ["txt", "token"],
		methods: {

			ClosePopup: function(){

				$(".lightbox").hide();
				
				this.data = {
					mallShopId: 0,
					mallProductName: "",
					cost: "",
					promo: 0,
					subTotal: "",
					includeService: [],
					costClass: ""
				};

			},

			minusNumber: function(){

				var ori_value = this.defaultNumber,
					new_value = parseInt(ori_value) > 1 ? parseInt(ori_value) - 1 : parseInt(ori_value);

					this.defaultNumber = new_value;

					this.getEcouponDiscount();

					this.shop_calc();

			},

			addNumber: function(){

				var ori_value = this.defaultNumber,
					new_value = parseInt(ori_value) + 1;

					this.defaultNumber = new_value;

					this.getEcouponDiscount();

					this.shop_calc();

			},


			shop_calc: function(){

				var number = this.defaultNumber,
					promo = this.data.promo,
					price = promo > 0 ? promo : this.data.cost,
					total = number * price,
					total_price = isNaN(total) ? 0 : total,
					ecouponMinus = this.data.ecouponMinus;

					this.data.subTotal = total_price;

					this.data.total = total_price + ecouponMinus;
			
			},

			getEcouponDiscount: function(){

				var code = $("[name='Ecoupon']").val();

				$.ajax({
					url: "/getEcouponDiscount",
					data: "code="+code+"&sub_total="+this.data.subTotal,
					type: 'POST',
					success: function( response ) {
						
						var result = JSON.parse(response),
							subTotal = shopList.$refs.mallProductLightbox.data.subTotal,
							ecouponMinus = parseInt(result.discount_price),
							total = subTotal + ecouponMinus;

						shopList.$refs.mallProductLightbox.data.ecouponMinus = ecouponMinus;
						shopList.$refs.mallProductLightbox.data.total = total;

					}
				});

			}

		}

	};