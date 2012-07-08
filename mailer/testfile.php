<?php
  require_once("mail.php");
  $address="johnson7mark@yahoo.com";
  $saddress="languagelearner77.yahoo.com";
  $mail= new PHPMailerLite();
  $send= $mail->AddAddress($address, $name = '');
  $frm= $mail-> SetFrom($saddress, $name = '',$auto=1);
  $comm= $mail-> Send();

  $response= new phpmailerException();
  $emessege=$response-> errorMessage();
  print_r($send);
  echo "<br/>";
  print_r($frm);
  echo "<br/>";
  print_r($comm);
  echo "<br/>";
  echo $emessege;
?>

//plus.smtp.mail.yahoo.com