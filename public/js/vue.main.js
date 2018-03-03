
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
		},
		methods: {

			SearchToolDisplay: function() {
				$(".search_tool").toggle('slow');
			}
			
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