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
	mysql_set_charset("utf8");
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
		return 1;
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

	function toggle_mail_notify($id){
    
      $db=db_connect();
      if($db==0){
            $change_mail_notify=mysql_query("UPDATE users SET notifications=!notifications WHERE id='$id'");
			return 1;
        }
        else{
          return "Error Executing get_mail_status query";
        }
    }  //toggle_mail_notify

//This Function converts the mail status from 0's and 1's to human readable On and Off switches.
//Input Params $id (User's Primary ID)
  function show_mail_status($id){
    $current=get_mail_status($id);
    if($current==0){
      return "No";
    }
    else if($current==1){
      return "Yes";
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



//This Function returns details of previous word of the days
//Input params: $language (Word Language), $date (The date when it was the word of day)
  function word_of_day_lookup($language,$old){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
	$used = date('Y-m-d',strtotime('-'.$old.' days'));
    $qstr="SELECT * FROM ".$language." WHERE used='$used'";
    $downloadinfo=mysql_query($qstr);
    if(!isset($downloadinfo)){
      return "Error downloading word info";
    }
    $data=mysql_fetch_assoc($downloadinfo);
    return $data;
  }//word_of_day_old

  //This Function returns details of previous word of the days
//Input params: $language (Word Language), $date (The date when it was the word of day)
  function word_of_day_lookup2($language,$date){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $qstr="SELECT * FROM ".$language." WHERE used='".mysql_real_escape_string($date)."'";
    $downloadinfo=mysql_query($qstr);
    if(!isset($downloadinfo)){
      return -1;
    }
    $data=mysql_fetch_assoc($downloadinfo);
    return $data;
  }//word_of_day_old

//This Function calculates the top 10 words for each language based on word hits
//Input params: $language (Language for which top 10 words are required)
//Output: a super array containing all the data like id, words translations, lesson name
  function top_words($language,$length){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
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

//This Function finds distinct lessons for each language
//Input params: $language (Language)
//Output: a super array containing all the data like id, words translations, lesson name
  function get_lessons($language){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $qstring="SELECT distinct(lesson_name), count(word) as words FROM ".$language." GROUP BY lesson_name";
    $query= mysql_query($qstring);
    if(!isset($query)){
      return "Error Fetching the top words";
    }
	$lessons = array();
    while($lesson = mysql_fetch_array($query)){
		$lessons[] = $lesson;
	}
    return $lessons;
  }//get_lessons

  
  function get_languages(){
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$query = "select distinct(name) from languages";
	$result = mysql_query($query);
	$langs = array();
	while($lang = mysql_fetch_assoc($result))
		$langs[] = $lang['name'];
	return $langs;
  }
  
  
  function learn_word($word_id, $lang, $user_id){
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$word_id = mysql_real_escape_string($word_id);
	$lang = mysql_real_escape_string($lang);
	$user_id = mysql_real_escape_string($user_id);
	$query = "insert ignore into history(user_id, word_id, lang) values($user_id, $word_id, '$lang')";
	$result = mysql_query($query);
	$query = "update $lang set hits = hits+1 where id= $word_id";
	$result = mysql_query($query);
	return 1;
  }
  function unlearn_word($word_id, $lang, $user_id){
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$word_id = mysql_real_escape_string($word_id);
	$lang = mysql_real_escape_string($lang);
	$user_id = mysql_real_escape_string($user_id);
	$query = "delete from history where user_id = $user_id and word_id = $word_id and lang = '$lang'";
	$result = mysql_query($query);
	$query = "update $lang set hits = hits-1 where id= $word_id";
	$result = mysql_query($query);
	return 1;
  }
  function is_learnt($word_id, $lang, $user_id){
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$word_id = mysql_real_escape_string($word_id);
	$lang = mysql_real_escape_string($lang);
	$user_id = mysql_real_escape_string($user_id);
	$query = "select id from history where user_id = $user_id and word_id = $word_id and lang = '$lang'";
	//echo $query;
	$result = mysql_query($query);
	return mysql_num_rows($result);
  }
  
//This function gets the words from a lesson.
//Input params: $language (Word language), $lesson (Name of the lesson)
  function get_words($language,$lesson){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $qword="SELECT id,word,translation, hits FROM ".$language." WHERE lesson_name='$lesson'";
	//echo $qword;
    $result=mysql_query($qword);
	$words = array();
    while($word = mysql_fetch_array($result)){
		$words[] = $word;
	}
	return $words;
  }//get_words
  
  
  
//This function adds the rep points to user's account
//Input params: $id (user's primary id), $points (number of rep points awarded)
  function rep_adder($id,$points){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $query=mysql_query("SELECT rep FROM users WHERE id='$id'");
    $row=mysql_fetch_assoc($query);
    $current_rep=$row['rep'];
    $new_rep=$current_rep+$points;
    return $new_rep;
  }//rep_adder


//This function lists all the words that a user have learnt in a given language
//Input Params: $id(User's primary id), $language (user's language)
  function browse_words($id,$language,$flag){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
   // $qstr="SELECT word,translation,lesson_name FROM ".$language." WHERE id IN (SELECT word_id FROM history WHERE user_id='$id' AND lang='$language' AND flag='$flag')";
	$qstr = "SELECT word,translation,lesson_name ,history.timestamp FROM ".$language.",history WHERE ".$language.".id = history.word_id and history.user_id='".$id."' and history.lang='".$language."' AND history.flag='".$flag."'";
    $query=mysql_query($qstr);
    $num_words=mysql_num_rows($query);
    if($num_words==0){
      return "No Words Found";
    }
	//return $words;
	$arr = array();
	while($words = mysql_fetch_array($query)){
          $arr[]=$words;
    }
    return $arr;
  }//browse_words


//This function changes the default language of the user
//Input params: $id ($user id), $lang(The new default languge)
  function change_default_lang($id,$lang){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
	$query = "UPDATE users SET default_language='$lang' WHERE id='$id'";
    $update_lang=mysql_query($query);
    if(!mysql_affected_rows()){
      return "Failed to update language";
    }
    return "Default Language Changed Successfully";

  }//change_default_lang


//This function checks if the user is eligible for taking the test ot not
//Input Params: $id (user's primary id) $lang ($language)
  function test_eligible($id,$lang){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $query=mysql_query("SELECT word_id FROM history WHERE user_id='$id' AND lang='$lang' AND flag=0 ORDER BY rand() LIMIT 10");
    $words_num=mysql_num_rows($query);
    if($words_num<10){
      return "You haven't learnt enough words to take a test in this language, learn more words or try other languages";
    }
    $id_list=array();
    while($row=mysql_fetch_assoc($query)){
      $id_list[]=$row['word_id'];
    }
    //return $id_list;
    $arr=array();
    for($i=0;$i<10;$i++){
      $u=$id_list[$i];
      $getwords="SELECT id,word,translation FROM ".$lang." WHERE id='$u'";
      $runquery=mysql_query($getwords);
      $words = array();
      while($word = mysql_fetch_array($runquery)){
        $words[] = $word;
        $arr[$i]=$words;
      }
    }
    return $arr;
  }//test_eligible


//This function generates options for the questions in a test
//Input params: $id (id of the word), $diff ($difficulty level)
  function test_generate($id,$lang,$diff){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    if($diff==1){
      $level=2;
    }else if($diff==2){
      $level=3;
    }
    $getmaxrows="SELECT MAX(id) FROM ".$lang;
    $runquery=mysql_query($getmaxrows);
    $rand=array();
    $tarr=array();
    for($i=0;$i<$level;$i++){
      $rows = mysql_result($runquery,0);
      read:
      $rand[$i]=randomize($rows,"r");
      if($rand[$i]==$id){
        goto read;
      }
      $t="SELECT translation FROM ".$lang." WHERE id='$rand[$i]'";
      $query=mysql_query($t);
      $tarr[$i]=mysql_result($query,0);
    }
    return $tarr;
  }//test_generate


//This function validates the answers
//Input params: $user_id (user's primary key), $id(id of the word), $lang(word language), $diff = ($difficulty), $t (user's answer)
  function test_validate($user_id,$id,$lang,$diff,$t){
    $db=db_connect();
    if($db!=0){
      return $db;
    }
    $qstr="SELECT translation FROM ".$lang." WHERE id='$id'";
    $query=mysql_query($qstr);
    $result=mysql_result($query,0);
    if($result==$t){
      if($diff==1){
        rep_adder($user_id,10);
      }
      if($diff==2){
        rep_adder($user_id,15);
      }
      $str=mysql_query("UPDATE history SET flag=0 WHERE user_id='$user_id' AND word_id='$id' AND lang='$lang'");
      return "Correct";
    }
    else{
      return "Wrong";
    }
  }//test_validate


  /* ************************************
  //		LOAD ANALYTICS FUNCTIONS
  *************************************** */
  
   function update_language_beacon($user_id, $lang){
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$query = "insert into beacon(user_id, activity) values($user_id,'Lang-".$lang."')";
	$result = mysql_query($query);
	return mysql_insert_id();
  }
  function update_def_language_beacon($user_id, $lang){
	$db=db_connect();
    if($db!=0){
      return $db;
    }
	$query = "insert into beacon(user_id, activity) values($user_id,'Def-".$lang."')";
	$result = mysql_query($query);
	return mysql_insert_id();
  }
  
	function added_user_beacon($user_id){
		$db=db_connect();
		if($db!=0){
		  return $db;
		}
		$query = "insert into beacon(user_id, activity) values($user_id,'NewUser')";
		$result = mysql_query($query);
		return 1;
	}
	
	function learnt_word_beacon($word_id, $lang, $user_id){
		$db=db_connect();
		if($db!=0){
		  return $db;
		}
		$query = "insert into beacon(user_id, activity) values($user_id,'Learnt:".$word_id."::".$lang."')";
		$result = mysql_query($query);
		return 1;
	}
	function unlearnt_word_beacon($word_id, $lang, $user_id){
		$db=db_connect();
		if($db!=0){
		  return $db;
		}
		$query = "insert into beacon(user_id, activity) values($user_id,'Unlearnt:".$word_id."::".$lang."')";
		$result = mysql_query($query);
		return 1;
	}
	
?>
