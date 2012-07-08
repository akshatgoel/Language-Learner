<?php
session_start();
header('Content-type: text/html;charset=utf-8');
// Provides access to app specific values such as your app id and app secret.
// Defined in 'AppInfo.php'

require_once('AppInfo.php');
require_once('lib/library.php');
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
  'cookie' => true,
  'fileUpload' => true,
));
//edit the permissions needed
$permsneeded='publish_stream,user_photos,read_stream,email';

$loginUrl = $facebook->getLoginUrl(array(
				'scope' => $permsneeded,
				'redirect_uri' => 'https://apps.facebook.com/lang_learn/'
));
$user_id = $facebook->getUser();

if($user_id) {
	try {
		$me = $facebook->api('/me'); 
		} catch(Exception $e) {
			error_log($e);
			echo "<script type='text/javascript'>window.top.location.href = '$loginUrl';</script>";	exit;
	}
}

if ($me){
	$fb_id = $facebook->getUser();
	$access = $facebook->getAccesstoken();
	$name = $me['name'];
	$email = $me['email'];
	if(($app_user = get_user($fb_id)) == -1){
		$_SESSION['user']['id'] = add_user($fb_id, $access, $name, $email);
		$_SESSION['user']['user_language'] = 'spanish';
		added_user_beacon($_SESSION['user']['id']);
	}
	else{
		update_user($fb_id, $access, $name, $email);
		if(empty($_SESSION['user']['user_language'])){
			$_SESSION['user'] = $app_user;
			$_SESSION['user']['user_language'] = $app_user['default_language'];
		}
	}
}
else {			
	echo "<script type='text/javascript'>window.top.location.href = '$loginUrl';</script>";	exit;
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
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# lang_learn: http://ogp.me/ns/fb/lang_learn#">
  <meta property="fb:app_id" content="362249483848728" /> 
  <meta property="og:type"   content="lang_learn:word" /> 
  <meta property="og:url"    content="https://apps.facebook.com/lang_learn/index.php?<?php echo $_GET['word_id']; ?>" /> 
  <meta property="og:title"  content="<?php echo $_SESSION['user']['current_language']; ?>" /> 
  <meta property="og:image"  content="https://s-static.ak.fbcdn.net/images/devsite/attachment_blank.png" /> 


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
				<img src="https://graph.facebook.com/me/picture?access_token=<?php echo $facebook->getAccesstoken(); ?>" style="margin-bottom:20px;float:right;margin-right:14%;"/>
			</div>
			<div class="fl" id="left_menu">
			<img src="images/logo.png" title="Logo"  style="margin-left: 30px;"/>
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
				<?php for($i=0;$i<8;$i++){ ?>
				<?php $today = word_of_day_lookup($_SESSION['user']['user_language'], $i); ?>
				<div class="box">
					<h3> <?php echo date('j F Y',strtotime($today['used'])); ?></h3>
					<div>
						<h4><?php echo $today['word']; ?></h4>
						<em class="s13 mt7"><?php echo $today['translation']; ?></em>
						<p class="s11 mt10"><a href="javascript:void(0);" title="<?php echo $today['lesson_name']; ?>" class="lesson_link"><?php echo $today['lesson_name']; ?></a><?php
					switch(is_learnt($today['id'],$_SESSION['user']['user_language'], $_SESSION['user']['id'])){
						case 1 : echo '<span class="unlearn_btn" id="word'.$today['id'].'">Unlearn';
									break;
						case 0 : 
						default: echo '<span class="learn_btn" id="word'.$today['id'].'">Learn';
									break;
					}
					?></span></p>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</body>
</html>
