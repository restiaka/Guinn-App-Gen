  <script>
  <?php $CI = &get_instance(); $CI->load->model('setting_m'); ?>
  var APP_APPLICATION_ID = '<?php echo $CI->setting_m->get('APP_APPLICATION_ID');?>';
	var APP_EXT_PERMISSIONS = '<?php echo $CI->setting_m->get('APP_EXT_PERMISSIONS');?>';

  window.fbAsyncInit = function() {
    FB.init({
				appId: APP_APPLICATION_ID, 
				status: true, 
				cookie: true,
				xfbml: true,
				oauth: true
			});
	fbApiInitialized = true;
	FB.Canvas.setAutoGrow(91);
  };
  
  function fbRequireLogin(dialogType,redirectURL)
  {
   		 	FB.getLoginStatus(function(response) {
					if (response.session) {
						FB.api(
							  {
								method: 'fql.query',
								query: 'SELECT '+ APP_EXT_PERMISSIONS +' FROM permissions WHERE uid = '+ response.session.uid
							  },
							  function(response){
								  for(i in response[0]) {if(response[0][i] == 0) { fbDialogLogin(dialogType,redirectURL); break; }};
							  }
							 );
					}else{
					 	fbDialogLogin(dialogType,redirectURL);						
					}
			});
  }
  
  function fbSendRequest()
  {
    fbEnsureInit(function(){
       FB.ui({ 
			  method: 'apprequests', 
			  message: 'Lets get it.', 
			  data: 'tracking information for the user'
			 },
			 function(response) {
				    if(response) {
						$.post('<?php echo APP_CANVAS_URL?>rpc/invite',response, function(data) {
						  console.log(data);
						});
					 }
				   }
			 );
    });
  }
  
  function fbRevokeApps(uid)
  {
   	FB.api(
			  {
				method: 'auth.revokeAuthorization',
				uid: uid
			  },
			  function(response){
			 	if(response == true) 
				 alert("Authorization Revoked Successfully");
				 else
				 alert("Authorization Failed to Revoke, Please Try Again");
			  }
		 );
  }
  
    function fbEventSubscribe(event)
  { 
   /* event :
	* auth.login -- fired when the user logs in
    * auth.logout -- fired when the user logs out
    * auth.sessionChange -- fired when the session changes
    * auth.statusChange -- fired when the status changes
    * xfbml.render -- fired when a call to FB.XFBML.parse() completes
    * edge.create -- fired when the user likes something (fb:like)
    * edge.remove -- fired when the user unlikes something (fb:like)
    * comment.create -- fired when the user adds a comment (fb:comments)
    * comment.remove -- fired when the user removes a comment (fb:comments)
    * fb.log -- fired on log message
	*/
	fbEnsureInit(function(){
		FB.Event.subscribe(event, function(response) {
			
			switch (event){ 
			  case "edge.create" : 
				 var qs = getUrlVars(response);
				/* $.post('<?php echo site_url('/rpc/like')?>/'+qs['m'], function(data) {
				  console.log(data);
				}); */
			  break;
			  case "edge.remove" : 
				 var qs = getUrlVars(response);
				/* $.post('<?php echo site_url('/rpc/unlike')?>/'+qs['m'], function(data) {
				  console.log(data);
				}); */
			  break;
			  case "comment.create" :
			  console.log(response);
				  var commentID = response.commentID;
				  var qs = getUrlVars(response.href);
				  var parentCommentID = response.parentCommentID;
					/* $.post('<?php echo site_url('/rpc/commentcreate')?>/'+qs['m']+'/'+commentID+'/?url='+response.href, function(data) {
							  console.log(data);
							}); */
			  break;
			  case "comment.remove" :
			  console.log(response);
				  var commentID = response.commentID;
				  var qs = getUrlVars(response.href);
				  var parentCommentID = response.parentCommentID;
				  /* $.post('<?php echo site_url('/rpc/commentremove')?>/'+qs['m']+'/'+commentID+'/?url='+response.href, function(data) {
					  console.log(data);
					}); */
			  break;
			  case "auth.login" :
				  window.top.location.reload();
			  break;
			  case "auth.logout" :
				  window.top.location.reload();
			  break;
			}
			
			
		});
	});
  } 
  
  function getUrlVars(obj){
	var vars = [];
	var hash = [];
	var hashes = obj.slice(obj.indexOf('?') + 1).split('&');
	 
	for(var i = 0; i < hashes.length; i++)
	{
	hash = hashes[i].split('=');
	vars.push(hash[0]);
	vars[hash[0]] = hash[1];
	}
	 
	return vars;
	}
  
  function fbIsAppUsers(uid)
  { 
   var response_isset = false;
   var result;
    FB.api(
			  {
				method: 'users.isAppUser',
				uid: uid
			  },
			   function (response) {
			     response_isset = true;
				 result = response;
			   }
		 );
		 
  }
  


 function fbEnsureCallback(vars,callback)
 {
   if (!vars) {
        setTimeout(function() { fbEnsureCallback(vars); }, 50);
    } else {
        if (callback) { callback(); }
    }
 }
  
   function addToPage(redirectURI) {

        // calling the API ...
        var obj = {
          method: 'pagetab',
          redirect_uri: redirectURI,
        };

        FB.ui(obj);
      }

  
  function fbEnsureInit(callback) {
    if (!window.fbApiInitialized) {
        setTimeout(function() { fbEnsureInit(callback); }, 50);
    } else {
        if (callback) { callback(); }
    }
  }
  
  function fbDialogLogin(dialogType,redirectURL)
  {
   var dialogType = dialogType || "fb_login";
   fbEnsureInit(function(){
				switch(dialogType){
				  case "fb_login" : FB.login(function(response) {
									   if (response.authResponse) {
									       if(redirectURL) 
											 window.top.location.href = redirectURL;
									   } 
									}, {scope: APP_EXT_PERMISSIONS});	
									break;
				}	
   });
  }
  

  
</script>