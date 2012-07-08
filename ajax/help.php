<?php
session_start();
header('Content-type: text/html;charset=utf-8');
require_once('../lib/library.php');
view_help_beacon($_SESSION['user']['id']);
?>
<div class="box">
<h3> Why can't I view the application correctly?</h3>
<div>
<p class="pb10">We use a standard technology called HTML5 in our application. Most modern web browsers like Safari, Firefox, and Chrome work well with HTML5. If you're not using an HTML5 compliant browser, we recommend you upgrade.</p></div>

</div>
<div class="box">
<h3> Why can't I login on the application?</h3>
<div>
<p class="pb10">Please check that you are using modern web browsers like Safari, Firefox and Chrome. Also, make sure you don't have VPN connections activated.</p></div>
<br />
<br />
</div>