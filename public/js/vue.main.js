
if ( $(".basicForm").length > 0 ) 
{

	var basicForm = new Vue({
		el: '.basicForm',
		components: {
			basicForm: basicForm
		}
	});

}


if ( $(".basicList").length > 0 ) 
{

	var basicList = new Vue({
		el: '.basicList',
		components: {
			basicList: basicList
		}
	});

}


if ( $(".shopList").length > 0 ) 
{

	var shopList = new Vue({
		el: '.shopList',
		components: {
			shopList: shopList,
			mallProductLightbox: mallProductLightbox,
		}
	});

}