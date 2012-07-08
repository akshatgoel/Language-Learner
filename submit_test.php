<?php
session_start();
header('Content-type: text/html;charset=utf-8');
if(!isset($_SESSION['user']['id']) || !isset($_POST))
	header("Location: index.php");
require_once('AppInfo.php');
require_once('lib/library.php');
// Enforce https on production
if (substr(AppInfo::getUrl(), 0, 8) != 'https://' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  header('Location: https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  exit();
}

$user_id = $_SESSION['user']['id'];
$lang = $_SESSION['user']['user_language'];
$diff = 1;

foreach($_POST as $que=>$ans)
	test_validate($user_id,$que,$lang,$diff,$ans);
	
test_give_beacon($user_id);
unset($_POST);
// This provides access to helper functions defined in 'utils.php'
require_once('utils.php');


?>
<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

    <title><?php echo he($app_name); ?></title>
	<link rel="stylesheet" href="stylesheets/styles.css" media="Screen" type="text/css" />
	<link rel="stylesheet" href="stylesheets/jquery.jscrollpane.css" media="Screen" type="text/css" />
	<link rel="stylesheet" href="stylesheets/jquery.datepick.css" media="Screen" type="text/css" />

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
  <script type="text/javascript" src="/javascript/single.js" ></script>
    <script type="text/javascript" src="/javascript/fb.js" ></script>
<!-- the mousewheel plugin - optional to provide mousewheel support -->
	<script type="text/javascript" src="/javascript/jquery.mousewheel.js"></script>

	<!-- the jScrollPane script -->
	<script type="text/javascript" src="/javascript/jquery.jscrollpane.min.js"></script>
	<script type="text/javascript" src="/javascript/jquery.datepick.min.js"></script>
    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
  </head>
  
		
  <body>
  <input type="hidden" value="<?php echo $_SESSION['user']['default_language']; ?>" id="user_def_lang">
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
	<div id="overlay" style="display:none;height:100px;width:150px;z-index:200;position:absolute;top:40%;left:40%;">
		<div style="position:absolute;z-index:205;top:20%;left:20%;" ><img src="images/loader.gif"/></div>
	</div>
	<div id="language_change" class="popup">
		
	</div>
	<div id="help_div" class="popup content-area" style="max-width:50%;max-height:40%;">
		<h2 class="ul pb10" style="text-shadow: 0 0 3px #cfcfcf;">Help</h2>
		<h3> Why can't I view the application correctly?</h3>
		<p class="pb10">We use a standard technology called HTML5 in our application. Most modern web browsers like Safari, Firefox, and Chrome work well with HTML5. If you're not using an HTML5 compliant browser, we recommend you upgrade.</p>

		<h3> Why can't I login on the application?</h3>
		<p class="pb10">Please check that you are using modern web browsers like Safari, Firefox and Chrome. Also, make sure you don't have VPN connections activated.</p>
		<br />
		<br />
	</div>
		<div id="wrapper" >
			<div id="top_bar">
				<a href="javascript:void(0);" style="text-decoration:none;" id="change_language_btn"><span class="link" style="padding: 20px;">Change Language (<?php echo ucwords($_SESSION['user']['user_language']); ?>) </span></a>
				<form method="post" action="actions/search.php" id="search_form" style="margin-top:-15px;">
					<input type="submit" value="search" style="width: 100px;margin-right: 14%;cursor:pointer;margin-left:7px;padding-top:7px;padding-bottom:9px;">
					<input type="text" value="Search word" name="search" class="em text_box">
				</form>
			</div>
			<div class="fl" id="left_menu">
				<img src="images/logo.png" title="Logo" style="margin-left:20px;"/>
<br />
<br />
				<ul>
					<li>
						<a href="javascript:void(0);" title="homepage" class="ajax_link"><span class="link" > Home </span></a>
					</li>
					<li>
						<a href="javascript:void(0);" title="word_of_the_day" class="ajax_link"><span class="link" > Word of the day </span></a>
					</li>
					<li>
						<a href="javascript:void(0);" title="browse_lessons" class="ajax_link"><span class="link" > Browse Lessons </span></a>
					</li>
					<li>
						<a href="javascript:void(0);" title="browse_my_words" class="ajax_link"><span class="link" > Browse My Words </span></a>
					</li>
					<li>
						<a href="javascript:void(0);" title="top_words" class="ajax_link"><span class="link" > Top 10 words </span></a>
					</li>
					<li>
						<a href="javascript:void(0);" title="take_test" class="ajax_link"><span class="link" > Take Test </span></a>
					</li>
					<li>
						<a href="javascript:void(0);" title="dashboard" class="ajax_link"><span class="link" > Dashboard </span></a>
					</li>
					
					<li>
						<a href="javascript:void(0);" title="help" class="ajax_link"><span class="link"> Help </span></a>
					</li>
				</ul>
									
					 
			</div>
			<div class="fr scroll-pane" id="right_content">
				
				<div class="box" style="width:80%;">
					<h3> Kudos!</h3>
					<div>
						<h4>You have successfully taken the test. </h4>
						<p >
							Reputation points have been updated on your profile. 
						</p>
					</div>
				</div>
				
			</div>
		</div>
	</body>
</html>
