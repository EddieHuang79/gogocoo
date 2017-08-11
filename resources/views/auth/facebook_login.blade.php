<div id="fb-root"></div>


<script>
// 324760437959558 test
window.fbAsyncInit = function() {
	FB.init({
		appId      : '1119977651436429',
		cookie     : true,
		xfbml      : true,
		version    : 'v2.9'
	});
	FB.AppEvents.logPageView();
};

(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/zh_TW/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.9&appId=1119977651436429";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


var facebook_login = function(user_data){


	$.ajax({
		url: "http://www.gogocoo.com/social_process",
		type: "POST",
		cache: false,
		data: "param="+JSON.stringify(user_data)+"&way=1",
		success : function(response){

			var result = JSON.parse( response );
			location.href = result.path;      
		}
	});				

},
statusChangeCallback = function(response){

	if (response.status === 'connected') {
		FB.api('/me', function(response) {
			facebook_login(response);
		});
	} else {
		alert("您尚未登入facebook！");
		FB.login();
	}

},
checkLoginState = function(){
	
	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});

};
</script>