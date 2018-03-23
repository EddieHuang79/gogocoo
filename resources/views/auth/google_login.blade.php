<div id="auth-status" style="display: inline; padding-left: 25px"></div>

<script>

var GoogleAuth,
    SCOPE = 'https://www.googleapis.com/auth/drive.metadata.readonly',
    login_click = 0,
    handleClientLoad = function(){
      // Load the API's client and auth2 modules.
      // Call the initClient function after the modules load.
      gapi.load('client:auth2', initClient);      
    },
    initClient = function(){
      // Retrieve the discovery document for version 3 of Google Drive API.
      // In practice, your app can retrieve one or more discovery documents.
      var discoveryUrl = 'https://www.googleapis.com/discovery/v1/apis/drive/v3/rest';

      // Initialize the gapi.client object, which app uses to make API requests.
      // Get API key and client ID from API Console.
      // 'scope' field specifies space-delimited list of access scopes.
      gapi.client.init({
        'apiKey': '',
        'discoveryDocs': [discoveryUrl],
        'clientId': '399494959799-eoq2fri8fbjjuev5pllpgbdnl9akk5f1.apps.googleusercontent.com',
        'scope': SCOPE
      }).then(function () {
        GoogleAuth = gapi.auth2.getAuthInstance();

        // Listen for sign-in state changes.
        GoogleAuth.isSignedIn.listen(updateSigninStatus);

        // Handle initial sign-in state. (Determine if user is already signed in.)
        var user = GoogleAuth.currentUser.get();
        setSigninStatus();


        // Call handleAuthClick function when user clicks on
        //      "Sign In/Authorize" button.
        $('#google_login').click(function() {
          handleAuthClick();
        }); 
        $('#revoke-access-button').click(function() {
          revokeAccess();
        }); 
      });      

    },
    handleAuthClick = function(){
      if (GoogleAuth.isSignedIn.get()) {
          // User is authorized and has clicked 'Sign out' button.
          GoogleAuth.signOut();
      } else {
          // User is not signed in. Start Google auth flow.
          GoogleAuth.signIn();
          login_click = 1;
      }      
    },
    revokeAccess = function(){
      GoogleAuth.disconnect();     
    }
    setSigninStatus = function(isSignedIn){
      var user = GoogleAuth.currentUser.get(),
          isAuthorized = user.hasGrantedScopes(SCOPE);

      if (isAuthorized) 
      {
        var user_data = {"id":user.El,"email":user.w3.U3};

        if (login_click)
        {
          $.ajax({
            url: "http://www.gogocoo.com/social_process",
            type: "POST",
            cache: false,
            data: "param="+JSON.stringify(user_data)+"&way=2",
            success : function(response){

              var result = JSON.parse( response );

              if ( result.error === false ) 
              {

                  location.href = result.path;      

              }
              else
              {

                  $(".error").remove();

                  var html = "";

                  result.msg.forEach( function(e){

                      html += "<div class='error'>" + e + "</div>";

                  });

                  $( html ).insertBefore(".has-feedback:first");

                  return false;

              }

            
            }

          });      
        };
      }   
    },
    updateSigninStatus = function(isSignedIn){
      setSigninStatus();      
    }

</script>
<script async defer src="https://apis.google.com/js/api.js" 
onload="this.onload=function(){};handleClientLoad()" 
onreadystatechange="if (this.readyState === 'complete') this.onload()">
</script>