<?php

/**
 * This sample app is provided to kickstart your experience using Facebook's
 * resources for developers.  This sample app provides examples of several
 * key concepts, including authentication, the Graph API, and FQL (Facebook
 * Query Language). Please visit the docs at 'developers.facebook.com/docs'
 * to learn more about the resources available to you
 */

// Provides access to app specific values such as your app id and app secret.
// Defined in 'AppInfo.php'
require_once('AppInfo.php');

// Enforce https on production
if (substr(AppInfo::getUrl(), 0, 8) != 'https://' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  exit();
}

// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');


/*****************************************************************************
 *
 * The content below provides examples of how to fetch Facebook data using the
 * Graph API and FQL.  It uses the helper functions defined in 'utils.php' to
 * do so.  You should change this section so that it prepares all of the
 * information that you want to display to the user.
 *
 ****************************************************************************/

require_once('sdk/src/facebook.php');

$facebook = new Facebook(array(
  'appId'  => AppInfo::appID(),
  'secret' => AppInfo::appSecret(),
));

$user_id = $facebook->getUser();
if ($user_id) {
  try {
    // Fetch the viewer's basic information
    $basic = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    // If the call fails we check if we still have a user. The user will be
    // cleared if the error is because of an invalid accesstoken
    if (!$facebook->getUser()) {
      header('Location: '. AppInfo::getUrl($_SERVER['REQUEST_URI']));
      exit();
    }
  }

  // This fetches some things that you like . 'limit=*" only returns * values.
  // To see the format of the data you are retrieving, use the "Graph API
  // Explorer" which is at https://developers.facebook.com/tools/explorer/
  $likes = idx($facebook->api('/me/likes?limit=4'), 'data', array());

  // This fetches 4 of your friends.
  $friends = idx($facebook->api('/me/friends?limit=4'), 'data', array());

  // And this returns 16 of your photos.
  $photos = idx($facebook->api('/me/photos?limit=16'), 'data', array());

  // Here is an example of a FQL call that fetches all of your friends that are
  // using this app
  $app_using_friends = $facebook->api(array(
    'method' => 'fql.query',
    'query' => 'SELECT uid, name FROM user WHERE uid IN(SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1'
  ));
}

// Fetch the basic info of the app that they are using
$app_info = $facebook->api('/'. AppInfo::appID());

$app_name = idx($app_info, 'name', '');

?>
<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

    <title><?php echo he($app_name); ?></title>
	<link rel="stylesheet" href="stylesheets/styles.css" media="Screen" type="text/css" />

    <!-- These are Open Graph tags.  They add meta data to your  -->
    <!-- site that facebook uses when your content is shared     -->
    <!-- over facebook.  You should fill these tags in with      -->
    <!-- your data.  To learn more about Open Graph, visit       -->
    <!-- 'https://developers.facebook.com/docs/opengraph/'       -->
    <meta property="og:title" content="<?php echo he($app_name); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo AppInfo::getUrl(); ?>" />
    <meta property="og:image" content="<?php echo AppInfo::getUrl('/logo.png'); ?>" />
    <meta property="og:site_name" content="<?php echo he($app_name); ?>" />
    <meta property="og:description" content="My first app" />
    <meta property="fb:app_id" content="<?php echo AppInfo::appID(); ?>" />

    <script type="text/javascript" src="/javascript/jquery-1.7.1.min.js"></script>
	
  <script type="text/javascript" src="/javascript/jquery.masonry.js" ></script>
  <script type="text/javascript">
	$(document).ready(function(){
	
		var inpVal = '';
		
		$('#right_content').masonry({
		  itemSelector: '.box',
		  animate:true
		});
		
		$(window).bind("resize", function(){ $('#right_content').masonry().reload(); });
		
		$('.text_box').focus(function(){
		
			inpVal = $(this).val();
			$(this).val('');
			$(this).removeClass('em');
		
		});
		$('.text_box').blur(function(){
		
			if($(this).val() == ''){
				$(this).val(inpVal);
				$(this).addClass('em');
			}
		
		});
		
		$('#change_language_btn').click(function(){
			$.get('ajax/language.php', function(data) {
				//  $.modal(data);
				 alert('Load was performed.');
			});
		
		});
		
	});
    </script>
    <script type="text/javascript">
      function logResponse(response) {
        if (console && console.log) {
          console.log('The response was', response);
        }
      }

      $(function(){
        // Set up so we handle click on the buttons
        $('#postToWall').click(function() {
          FB.ui(
            {
              method : 'feed',
              link   : $(this).attr('data-url')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });

        $('#sendToFriends').click(function() {
          FB.ui(
            {
              method : 'send',
              link   : $(this).attr('data-url')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });

        $('#sendRequest').click(function() {
          FB.ui(
            {
              method  : 'apprequests',
              message : $(this).attr('data-message')
            },
            function (response) {
              // If response is null the user canceled the dialog
              if (response != null) {
                logResponse(response);
              }
            }
          );
        });
      });
    </script>

    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
  </head>
  
		
  <body>
    <div id="fb-root"></div>
    <script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo AppInfo::appID(); ?>', // App ID
          channelUrl : '//<?php echo $_SERVER["HTTP_HOST"]; ?>/channel.html', // Channel File
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true // parse XFBML
        });

        // Listen to the auth.login which will be called when the user logs in
        // using the Login button
        FB.Event.subscribe('auth.login', function(response) {
          // We want to reload the page now so PHP can read the cookie that the
          // Javascript SDK sat. But we don't want to use
          // window.location.reload() because if this is in a canvas there was a
          // post made to this page and a reload will trigger a message to the
          // user asking if they want to send data again.
          window.location = window.location;
        });

        FB.Canvas.setAutoGrow();
      };

      // Load the SDK Asynchronously
      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/all.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
		<div id="wrapper" >
			<div id="top_bar">
				<a href="javascript:void(0);" style="text-decoration:none;" id="change_language_btn"><span class="link" style="padding: 20px;">Change Language </span></a>
				<input type="text" value="Search word" name="search" class="em text_box">
			</div>
			<div class="fl" id="left_menu">
				<img src="images/logo.png" title="Logo" style="margin-left:20px;"/>
<br />
<br />
				<ul>
					<li>
						<a href="#"><span class="link" > Home </span></a>
					</li>
					<li>
						<a href="#"><span class="link" > Word of the day </span></a>
					</li>
					<li>
						<a href="#"><span class="link" > Browse Lessons </span></a>
					</li>
					<li>
						<a href="#"><span class="link" > Top 10 words </span></a>
					</li>
					<li>
						<a href="#"><span class="link" > Take Test </span></a>
					</li>
					<li>
						<a href="#"><span class="link" > Revise words </span></a>
					</li>
					<li>
						<a href="#"><span class="link" > Dashboard </span></a>
					</li>
					
					<li>
						<a href="#"><span class="link" > Help </span></a>
					</li>
				</ul>
									
					 
			</div>
			<div class="fr scroll-pane" id="right_content">
				<div class="box">
					<h3>July 1, 2012</h3>
					<div>
						<h4>mumpsimus</h4>
						<em class="s13 mt7">Adherence to or persistence in an erroneous use of language, memorization, practice, belief, etc., out of habit or obstinacy. </em>
						<p class="s11 mt10">I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..</p>
					</div>
				</div>
				<div class="box">
					<h3>July 1, 2012</h3>
					<div>
						<h4>mumpsimus</h4>
						<em class="s13 mt7">I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus.. </em>
						<p class="s11 mt10">I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..</p>
					</div>
				</div>
				<div class="box">
					<h3>July 1, 2012</h3>
					<div>
						<h4>mumpsimus</h4>
						<em class="s13 mt7">I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus.. </em>
						<p class="s11 mt10">I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..</p>
					</div>
				</div>
				<div class="box">
					<h3>July 1, 2012</h3>
					<div>
						<h4>mumpsimus</h4>
						<em class="s13 mt7">Adherence to or persistence in an erroneous use of language, memorization, practice, belief, etc., out of habit or obstinacy. </em>
						<p class="s11 mt10">I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..</p>
					</div>
				</div>
				<div class="box">
					<h3>July 1, 2012</h3>
					<div>
						<h4>mumpsimus</h4>
						<em class="s13 mt7">Adherence to or persistence in an erroneous use of language, memorization, practice, belief, etc., out of habit or obstinacy. </em>
						<p class="s11 mt10">I profess, my good lady," replied I, "that had any one but you made such a declaration, I should have thought it as capricious as that of the clergyman, who, without vindicating his false reading, preferred, from habit's sake, his old Mumpsimus..</p>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
