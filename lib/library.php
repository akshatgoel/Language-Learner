<?php

//This funtion connects to the database
//Input params: N/A
//Return Value: Returns 0 if connection to server and database is successful
//Returns 1 if connection to server failed, Returns 2 if connection to database failed

  function db_connect(){
    $server="182.72.63.18";
    $user="fc_team_44";
    $password="6GAhVw8aWjPsMyZC";
    $db_name="fc_team_44";
    $connect=mysql_connect($server,$user,$password);
    if($connect){                       //connection to server ok
      $db_select = mysql_select_db($db_name,$connect);
      if($db_select){
        return 0;  //db selection ok
      }
      else{
        return 'Cannot select the database';  //db selection failed
      }
    }
    else{
      return 'Cannot Connect to the server';  //connection to server failed
    }

  }  //db_connect

	function get_user($fbid){
		$db=db_connect();
		if($db!=0){
		  return $db;
		}
		$query = "select * from users where fb_id = $fbid";
		$result = mysql_query($query);
		if(mysql_num_rows($result)){
			$data =  mysql_fetch_array($result);
			return $data;
		}
		return '-1';
	}
	function update_user($fbid, $access, $name, $email){
		$db=db_connect();
		if($db!=0){
		  return $db;
		}
		$query = "update users set access_token = '$access', username = '$name', email = '$email' where fb_id = $fbid";
		$result = mysql_query($query);
		//return mysql_affected_rows();
	}
	
	function add_user($fb_id, $access, $username, $email){
		$db=db_connect();
		if($db!=0){
		  return $db;
		}
		$query = "insert into users(fb_id, access_token, username, email) values($fb_id,'$access','$username','$email'); ";
		$result = mysql_query($query);
		return mysql_insert_id();
	}

//This Function Gets the Current Status of email notifications of user
//Input Params : $id (Primary key of the user)
  function get_mail_status($id){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $get_mail_status=mysql_query("SELECT notifications FROM users WHERE id='$id'");
    if(isset($get_mail_status)){
    while($row=mysql_fetch_assoc($get_mail_status)){
      $current=$row['notifications'];
    }
    return $current;
    }
  }   //get_mail_status


//This function changes the email notification status of a user
//Input Params: $state (Specifies the state user has requested, must be either zero to remove notification or 1 to enable notifications)
//$id (Users's primary id)
//Return Values:
  function mail_notify($state,$id){
    if($state==0|$state==1){
      $db=db_connect();
      if($db==0){
        $current=get_mail_status($id);
        if($current==$state){
          //return "The Email Notifications are already Enabled/disabled";
          if($current==1){
            return "The Email Notifications are already Enabled";
          }
          else{
            return"The Email Notifications are already Disabled";
          }
        }
        else{
            $change_mail_notify=mysql_query("UPDATE users SET notifications='$state' WHERE id='$id'");
            if(isset($change_mail_notify)){
              return "Your Settings have been saved successfully";
            }
            else{
              return "Failed to update your settings, please try later.";
            }
          }
        }
        else{
          return "Error Executing get_mail_status query";
        }
      }
      else{
        return $db;
      }
    }  //mail_notify



//This Function converts the mail status from 0's and 1's to human readable On and Off switches.
//Input Params $id (User's Primary ID)
  function show_mail_status($id){
    $current=get_mail_status($id);
    if($current==0){
      return "Off";
    }
    else if($current==1){
      return "On";
    }
  } //show_mail_status


//This Function returns user details to be used in dashboard.
//Input params : $id (user's primary id)
  function dashboard($id){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $query=mysql_query("SELECT username,email,rep,last_login,notifications FROM users WHERE id='$id'");
    if(!isset($query)){
      return "Error Executing the dashboard command";
    }
    $row=mysql_fetch_assoc($query);
    return $row;
  }//dashboard


// This Function generates random numeric and alphanumeric strings. Also used to generate a random array index using the array randomizer mode
//Input Params: $length (Length of the string to generate/number of elements in an array), $filter('a' for alphanumeric, 'n' for numeric and 'r' for randomizer mode)
  function randomize($length,$filter){
    if($filter=="a"){   //alpha numeric (limited to 40 characters)
      $random=rand(23456789,98765432);
      $hash=sha1($random);
      $number=substr($hash,$length);
      return $number;
    }
    if($filter=="n"){   //numeric (no limit)
      while($length>0){
        $random=rand(1,9);
        $number=$number+$random;
        $number=$number*10;
        $length=$length-1;
      }
      $number=$number/10;
      return $number;
    }
    if($filter=="r"){    // array randomizer mode
      $random=rand(0,$length);
      return $random;
    }
  }//randomize


//This function selects the word of the day for each language and writes it on the database
//Input Params : $language (name of the language)
  function word_of_day($language){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $zero="0000-00-00";
    $date=date("Y-m-d");
    $qstring="SELECT GROUP_CONCAT(id) FROM ".$language." WHERE used='$zero'";
    $query=mysql_query($qstring);
    $results=explode(',',mysql_result($query,0));
    if(!isset($query)){
      return "Error selecting the word of the day..";
    }
    $num=count($results);
    $num=$num-1;
    $rand_num=randomize($num,"r");
    $rand_id=$results[$rand_num];
    //return $rand_id;
    $updateword=mysql_query("UPDATE word SET tab_id='$rand_id' WHERE language='$language'");
    if(!isset($updateword)){
      return "Error updating word table";
    }
    $qupdatelang="UPDATE ".$language." SET used='$date' WHERE id='$rand_id'";
    $updatelang=mysql_query($qupdatelang);
    if(!isset($updatelang)){
      return "Error updating Date";
   }
    return "Successful!!";
  }//word_of_day


//This function looksup for the word of the day for a specific language in the db
//Input params: $language (The language we want to look up for)
//Output : It returns and array containing the word and translation of the word
  function word_of_day_lookup($language){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $query=mysql_query("SELECT tab_id FROM word WHERE language='$language'");
    if(!isset($query)){
      return "Error selecting the tab_id";
    }
    while($row=mysql_fetch_assoc($query)){
      $tab_id=$row["tab_id"];
    }
    $qstr="SELECT * FROM ".$language." WHERE id='$tab_id'";
    $downloadinfo=mysql_query($qstr);
    if(!isset($downloadinfo)){
      return "Error downloading word info";
    }
    $data=mysql_fetch_assoc($downloadinfo);
    return $data;
  }//word_of_day_lookup


//This Function calculates the top 10 words for each language based on word hits
//Input params: $language (Language for which top 10 words are required)
  function top_words($language){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $qstring="SELECT id FROM ".$language." ORDER BY hits DESC LIMIT 10";
    $query= mysql_query($qstring);
    if(!isset($query)){
      return "Error Fetching the top words";
    }
    for($i=0;$i<10;$i++){
      $data[$i]=mysql_result($query,$i);
    }
    //return $data;
    foreach($data as $id){
      $qword="SELECT word FROM ".$language." ORDER BY hits DESC LIMIT 10";
      $word=mysql_query($qword);
      for($i=0;$i<10;$i++){
        $wlist[$i]=mysql_result($word,$i);
      }
      $qtrans="SELECT translation FROM ".$language." ORDER BY hits DESC LIMIT 10";
      $translation=mysql_query($qtrans);
      for($i=0;$i<10;$i++){
        $tlist[$i]=mysql_result($translation,$i);
      }
      $qlesson="SELECT lesson_name FROM ".$language." ORDER BY hits DESC LIMIT 10";
      $lesson=mysql_query($qlesson);
      for($i=0;$i<10;$i++){
        $llist[$i]=mysql_result($lesson,$i);
      }
      $qhits="SELECT hits FROM ".$language." ORDER BY hits DESC LIMIT 10";
      $hits=mysql_query($qhits);
      for($i=0;$i<10;$i++){
        $hlist=mysql_result($hits,$i);
      }
    }
    for($j=0;$j<10;$j++){
      $ten=$j+10;
      $twenty=$j+20;
      $thirty=$j+30;
      $forty=$j+40;
      $superarr[$j]=$data[$j];
      $superarr[$ten]=$wlist[$j];
      $superarr[$twenty]=$tlist[$j];
      $superarr[$thirty]=$llist[$j];
      //$superarr[$forty]=$hlist[$j];
    }
    return $superarr;
  }//top_words

  
  function get_languages(){
  
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$query = "select distinct(name) from languages";
	$result = mysql_query($query);
	return $result;
  
  }
  
  function update_language_beacon($user_id, $lang){
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$query = "insert into beacon(user_id, activity) values($user_id,'Lang-".$lang."')";
	$result = mysql_query($query);
	return mysql_insert_id();
  }
  
  function learn_word($word_id, $lang, $user_id){
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$word_id = mysql_real_escape_string($word_id);
	$lang = mysql_real_escape_string($lang);
	$user_id = mysql_real_escape_string($user_id);
	$query = "insert ignore into history(user_id, word_id, lang) values($user_id, $word_id, '$lang') on duplicate unique key ignore";
	$result = mysql_query($query);
  }
?>
