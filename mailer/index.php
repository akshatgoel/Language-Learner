<?php
header('Content-type: text/html;charset=utf-8');
function sendmail($recepient , $html){
    include('Mail.php');
    include('Mail/mime.php');
    $message = new Mail_mime();
    $text = file_get_contents("mail.txt");

    $message->setTXTBody($text);
    $message->setHTMLBody($html);
    $body = $message->get();
    $extraheaders = array("From"=>"Language Learner<hello@languagelearner.x10.mx>", "Subject"=>"Your Weekly Language Learner Digest");
    $headers = $message->headers($extraheaders);

    $mail = Mail::factory("mail");
    $mail->send($recepient, $headers, $body);
    return 1;
}


	$server="182.72.63.18";
    $user="fc_team_44";
    $password="6GAhVw8aWjPsMyZC";
    $db_name="fc_team_44";
    $connect=mysql_connect($server,$user,$password);
	mysql_set_charset("utf8");
    if($connect)                       //connection to server ok
      $db_select = mysql_select_db($db_name,$connect);
      
	$result = mysql_query("select default_language, email, username from users where notifications = 1");
	$to = 'akshat91@gmail.com';
	$from = 'Language Learner';
	$subject = 'Language Learner Newsletter Updates';
	

//This Function calculates the top 10 words for each language based on word hits
//Input params: $language (Language for which top 10 words are required)
//Output: a super array containing all the data like id, words translations, lesson name
  function top_words($language,$length){
    $qstring="SELECT * FROM ".$language." ORDER BY hits DESC LIMIT ".$length;
    $query= mysql_query($qstring);
    if(!isset($query)){
      return "Error Fetching the top words";
    }
	$words = array();
    while($word = mysql_fetch_array($query)){
		$words[] = $word;
	}
    return $words;
  }//top_words
  function get_mailer_words($data['default_language']){
		$word = top_words($data['default_language'],10);
		if(empty($word))
			continue;
		$header = '<!-- Wrapper -->
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td width="100%" height="100%" background="images/bg.jpg" valign="top">	

		<!-- Main wrapper -->
		<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td valign="top">
				
					<!-- Cant view this email? -->
					<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
						<tr>
							<td mc:edit="cant_view" height="80" style="text-align: center; font-style: italic; font-size: 11px; color: #AAAAAA; font-weight: normal; text-align: center; font-family: Georgia, serif; text-shadow: 1px 1px 1px #FFFFFF;">
								
								<a href="https://apps.facebook.com/lang_learn/" style="color: #AAAAAA; text-decoration: none;">Can\'t view Email?</a>
							
							
							</td>
						</tr>
					</table><!-- End Cant view this email? -->
					
							
					<article class="stampReadySkew"><style type="text/css">.stampReadyEffect {opacity: 1;-webkit-animation-name: stampReadyEffect;-webkit-animation-duration: 2.1s;-webkit-animation-iteration-count: 1;-webkit-animation-timing-function: linear; }@-webkit-keyframes stampReadyEffect { 1% { opacity: 0; -webkit-transform: scale(0.2);}80% { opacity: 0; -webkit-transform: scale(0.2);}95% { opacity: 1; -webkit-transform: scale(1.1);}100% { opacity: 1; -webkit-transform: scale(1);}}</style></article><header class="stampReadyEffect">
					<table width="602" class="stampReady" border="0" cellpadding="0" cellspacing="0" align="center">
						<tbody><tr>
							<td width="602" style="line-height: 1px;">
								<img src="images/pop-up_top.jpg" alt="" border="0" style="display: block;">									
							</td>
						</tr>
					</tbody></table>
							
					<!-- Pop-up Text -->
					<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
						<tbody><tr>
							<td width="1" height="40" bgcolor="#28a2cc"></td>
							<td width="600" height="40" bgcolor="#88d5ef">
								
								<!-- Text -->
								<table width="600" border="0" cellpadding="0" cellspacing="0" align="center" style="font-size: 14px; color: #2081a1; font-weight: bold; text-align: left; font-family: Helvetica, Arial, sans-serif; line-height: 20px; text-shadow: 0px 1px 1px #ffefcd;">
									<tbody><tr>
										<td width="20"></td>
										<td width="545" mc:edit="discount">Howdy '.$name.'!</td>
										<td width="15">
											<img mc:edit="cross" src="images/cross.jpg" alt="" border="0" style="display: block;">
										</td>
										<td width="20"></td>
									</tr>
								</tbody></table>

							</td>
							<td width="1" height="40" bgcolor="#28a2cc"></td>
						</tr>
					</tbody></table><!-- End Pop-up Text -->
					
					<!-- Pop-up Border Bottom Image -->
					<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
						<tbody><tr>
							<td width="602" style="line-height: 1px;">
								<img src="images/pop-up_bottom.jpg" alt="" border="0" style="display: block;">									
							</td>
						</tr>
					</tbody></table><!-- End Pop-up Border Bottom Image -->
					
					<!-- Pop-up Shadow -->
					<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
						<tbody><tr>
							<td width="602" style="line-height: 1px;">
								<img src="images/pop-up_shadow.jpg" alt="" border="0" style="display: block;">									
							</td>
						</tr>
					</tbody></table><!-- End Pop-up Shadow -->
					</header>

					<!-- Empty Table -->
					<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
						<tr>
							<td width="602" height="20">									
							</td>
						</tr>
					</table>
				';
				
$footer = '			
								<!-- Dashed Border -->
								<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
									<tr>
										<td width="1" height="1" bgcolor="#dcdcdc"></td>
										<td width="600" height="1" style="border-bottom: 1px dashed #d4d4d4"></td>
										<td width="1" height="1" bgcolor="#dcdcdc"></td>
									</tr>
								</table>
								
								<!-- Empty Table -->
								<table width="602" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
									<tr>
										<td width="1" height="1" bgcolor="#dcdcdc"></td>
										<td width="600" height="8" style="line-height: 1px;">
											<img src="images/blank.gif" alt="" style="display: block;">									
										</td>
										<td width="1" height="1" bgcolor="#dcdcdc"></td>
									</tr>
								</table>
								
								
							</td>
						</tr>
					</table><!-- End White Color Wrapper -->
					
					<!-- Empty Table -->
					<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
						<tr>
							<td width="602" height="8">
								<img src="images/blank.gif" alt="" border="0" style="display: block;">
							</td>
						</tr>
					</table>

		
				</td>
			</tr>
		</table><!-- End Main wrapper -->

		</td>
	</tr>
</table><!-- End Wrapper -->';

		$text = '<!-- Shadow --> 
								<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
									<tr>
										<td width="1" bgcolor="#dcdcdc"></td>
										<td width="600" bgcolor="#dcdcdc">
										
										<!-- Middle Shadow -->
										<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
											<tr>
												<td width="600" style="line-height: 1px;" valign="top">
													<img src="images/middle_shadow.jpg" alt="" border="0" style="display: block;">								
												</td>
											</tr>
										</table>
										
										</td>
										<td width="1" bgcolor="#dcdcdc"></td>
									</tr>
								</table>
								
								<!-- Start 2 Columns -->
								<table width="602" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
									<tr>
										<td width="1" bgcolor="#dcdcdc"></td>
										<td width="31"></td>
										<td width="539">
										
											<!-- Empty Table -->
											<table width="539" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
												<tr>	
													<td width="539" height="20" style="line-height: 1px;">
														<img src="images/blank.gif" alt="" style="display: block;">	
													</td>							
												</tr>
											</table>
											
											
									
											
											<!-- 2 Colums Text -->
											<table width="539" border="0" cellpadding="0" cellspacing="0" align="center" style="font-size: 14px; color: #b8b8b8; text-align: left; font-family: Helvetica, Arial, sans-serif; line-height: 20px;">
												<tr>
													<td width="249" mc:edit="column_text1" valign="top" mc:edit="text1"><a href="'.$siteUrl.'" style="text-decoration: none; color: #707070; font-weight: bold;">'.$word[0]['word'].'</a><br />'.$word[0]['translation'].'</td>
													<td width="41"></td>
													<td width="249" mc:edit="column_text2" valign="top" mc:edit="text2"><a href="'.$siteUrl.'" style="text-decoration: none; color: #707070; font-weight: bold;">'.$word[1]['word'].'</a><br />'.$word[1]['translation'].'</td>
												</tr>
											</table><!-- End 2 Colums Text -->
											
											<!-- Empty Table -->
											<table width="539" border="0" cellpadding="0" cellspacing="0" align="center">
												<tr>
													<td width="539" height="20" style="line-height: 1px;">
														<img src="images/blank.gif" alt="" style="display: block;">									
													</td>
												</tr>
											</table>
										
											<!-- Preview Buttons -->
											<table width="539" border="0" cellpadding="0" cellspacing="0" align="center">
												<tr>
													<td width="249" height="40" mc:edit="preview_button" valign="top">
														<a href="'.$siteUrl.'"><img mc:edit="button" src="images/preview_button.jpg" alt="" style="display: block;" border="0"></a>
													</td>
													<td width="41" height="40"></td>
													<td width="249" height="40" mc:edit="preview_button" valign="top">
														<a href="'.$siteUrl.'"><img mc:edit="button" src="images/preview_button.jpg" alt="" style="display: block;" border="0"></a>
													</td>
												</tr>
											</table><!-- End Preview Button -->
											
											
											<!-- Empty Table -->
											<table width="539" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
												<tr>
													<td width="539" height="30" style="line-height: 1px;">
														<img src="images/blank.gif" alt="" style="display: block;">									
													</td>
												</tr>
											</table>
											
										<td width="30"></td>
										<td width="1" bgcolor="#dcdcdc"></td>							
									</tr>
								</table><!-- End 2 Columns -->
								
								<!-- Shadow --> 
								<table width="602" border="0" cellpadding="0" cellspacing="0" align="center">
									<tr>
										<td width="1" bgcolor="#dcdcdc"></td>
										<td width="600" bgcolor="#dcdcdc">
										
										<!-- Middle Shadow -->
										<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
											<tr>
												<td width="600" style="line-height: 1px;" valign="top">
													<img src="images/middle_shadow.jpg" alt="" border="0" style="display: block;">								
												</td>
											</tr>
										</table>
										
										</td>
										<td width="1" bgcolor="#dcdcdc"></td>
									</tr>
								</table>
								
								<!-- Start 3 Column Images -->
								<table width="602" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
									<tr>
										<td width="1" bgcolor="#dcdcdc"></td>
										<td width="31"></td>
										<td width="538">
											
											
											<!-- Empty Table -->
											<table width="538" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
												<tr>
													<td width="538" height="20" style="line-height: 1px;">
														<img src="images/blank.gif" alt="" style="display: block;">
													</td>
												</tr>
											</table>
											
											
											<!-- Empty Table -->
											<table width="538" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
												<tr>
													<td width="538" height="8" style="line-height: 1px;">
														<img src="images/blank.gif" alt="" style="display: block;">									
													</td>
												</tr>
											</table>
											
											<!-- 3 Colums Text -->
											<table width="538" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF" style="font-size: 14px; color: #b8b8b8; text-align: left; font-family: Helvetica, Arial, sans-serif; line-height: 20px;">
												<tr>
													<td width="156" height="40" mc:edit="column_text1" valign="top"><a href="#" style="text-decoration: none; color: #707070; font-weight: bold;">'.$word[2]['word'].'</a> <br />'.$word[2]['translation'].'</td>
													<td width="35" height="40"></td>
													<td width="156" height="40" mc:edit="column_text2" valign="top"><a href="#" style="text-decoration: none; color: #707070; font-weight: bold;">'.$word[3]['word'].'</a> <br />'.$word[3]['translation'].'</td>
													<td width="35" height="40"></td>
													<td width="156" height="40" mc:edit="column_text3" valign="top"><a href="#" style="text-decoration: none; color: #707070; font-weight: bold;">'.$word[4]['word'].'</a> <br />'.$word[4]['translation'].'</td>
												</tr>
											</table><!-- End 3 Colums Text -->
											
											<!-- Preview Buttons -->
											<table width="538" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
												<tr>
													<td width="156" height="70 " bgcolor="#ffffff">
														<a href="#"><img mc:edit="button" src="images/preview_button.jpg" alt="" border="0" style="display: block;"></a>
													</td>
													<td width="35" height="70"></td>
													<td width="156" height="70" bgcolor="#ffffff">
														<a href="#"><img mc:edit="button" src="images/preview_button.jpg" alt="" border="0" style="display: block;"></a>
													</td>
													<td width="35" height="70"></td>
													<td width="156" height="70" bgcolor="#ffffff">
														<a href="#"><img mc:edit="button" src="images/preview_button.jpg" alt="" border="0" style="display: block;"></a>
													</td>
												</tr>
											</table><!-- End Preview Buttons -->
											
											<!-- Empty Table -->
											<table width="538" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FFFFFF">
												<tr>
													<td width="538" height="20" style="line-height: 1px;">
														<img src="images/blank.gif" alt="" style="display: block;">									
													</td>
												</tr>
											</table>
											
											
										</td>
										<td width="31"></td>
										<td width="1" bgcolor="#dcdcdc"></td>							
									</tr>
								</table><!-- End Start 3 Column Images -->';
		return $header.$text.$footer;
	}
?>