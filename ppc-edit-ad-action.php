<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");

$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}

$adname=trim($_POST['adname']);
$title=trim($_POST['title']);
$url=trim($_POST['url']);
$summary=trim($_POST['summary']);
$maxamount=trim($_POST['maxamount']);
$displayurl=trim($_POST['displayurl']);
$adlang=trim($_POST['language']);
$adult_status=trim($_POST['adult_status']);
$id=getSafePositiveInteger('id','p');
if(!myAd($id,$user->getUserID(),$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}



$dummytitle=trim($_POST['dummytitle']);
$dummyurl=trim($_POST['dummylink']);
$dummysummary=trim($_POST['dummysummary']);
$dummydisplayurl=trim($_POST['dummydisplayurl']);
$dummyadult=trim($_POST['dummyadult']);

phpSafe($dummytitle);
phpSafe($dummyurl);
phpSafe($dummysummary);
phpSafe($dummydisplayurl);
phpSafe($dummyadult);


phpSafe($adname);

phpSafe($adlang);

phpSafeUrl($url);

phpSafe($maxamount);

phpSafe($dummyadult);

phpSafe($adult_status);


/*if(isset($_POST['wap']))
{
$wap_flag=$_POST['wap'];
}
else 
{
	$wap_flag=0;
}
phpsafe($wap_flag);*/
$wap_flag=$mysql->echo_one("select wapstatus from ppc_ads where id=$id ");



//////////****************time targetting***************************////////////////////

$time_duration_flag=0;
$t_duration=$_POST['duration'];

 $tm_flg=0;	
if($t_duration==2)
{
	$date_flg=0;
	
$sdate=trim($_POST['popup_container']);
$edate=trim($_POST['popup_container1']);

phpSafe($sdate);
phpSafe($edate);

$sdate=strtotime($sdate);
$edate=strtotime($edate);

if($sdate=='')
{

header("Location:show-message.php?id=10501");
exit(0);
}
if($edate=='')
{

header("Location:show-message.php?id=10502");
exit(0);
}
if($sdate > $edate)
{

header("Location:show-message.php?id=10503");
exit(0);
}
$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
if($edate<$today)
{

header("Location:show-message.php?id=10504");
exit(0);
}

else
{ 
$tm_flg=1;	
}
}
else if($t_duration==3)
{
$tm_flg=1;	
}

//**********************Time******************
if($time_targeting==1)
{


	$beg_time=trim($_POST['beg_time']);
	$end_time=trim($_POST['end_time']); 
	phpSafe($beg_time);
	phpSafe($end_time);
	
	if(($beg_time=="") || ($beg_time<0) || ($beg_time>24))
	{
	 $beg_time=0;
	}
	
	if(($end_time=="") || ($end_time<0) || ($end_time>24))
	{
	 $end_time=0;
	}
	if($end_time==$beg_time)
	
	{
	 $beg_time=0;
	 $end_time=0;
	}
}

//////////****************time targetting***************************////////////////////



if($wap_flag==1)
{
	$wap_table='wap_ad_block';
	$wap_table1='wap_custom_ad_block';
	$wap_string='and wapstatus=1';
	$name='wap';
	$preview_file='wap-adblock-preview.php';
	
	
	$title_length=$GLOBALS['wap_title_length'];
	$durl_length=$GLOBALS['wap_url_length'];
	$desc_length=$GLOBALS['wap_desc_length'];
	
}

else
{
	$wap_table='ppc_ad_block';
	$wap_table1='ppc_custom_ad_block';
	$name='ppc';
	$wap_string='and wapstatus=0';
    $preview_file='adblock_preview.php';
    
    
    $title_length= $ad_title_maxlength;
	$durl_length= $ad_displayurl_maxlength;
	$desc_length=$ad_description_maxlength;
	
}

if(get_magic_quotes_gpc())
{
$title=stripslashes($title);
$summary=stripslashes($summary);
$displayurl=stripslashes($displayurl);
}		

$title=mb_substr($title,0,$title_length,$ad_display_char_encoding);
$summary=mb_substr($summary,0,$desc_length,$ad_display_char_encoding);
$displayurl=mb_substr($displayurl,0,$durl_length,$ad_display_char_encoding);


phpSafe($title);
phpSafe($summary);
phpSafe($displayurl);

if($adname==""||$title==""||$url==""||$summary==""||$maxamount=="" || $displayurl==""||$adlang=="")
{

	header("Location:show-message.php?id=1001");
	exit(0);
}
if(!is_numeric($maxamount))
{
	header("Location:show-message.php?id=2003");
	exit(0);
	
}

if($maxamount<$default_admaxamount)
{
	header("Location:show-message.php?id=2004");
	exit(0);
	
}
if($ad_keyword_mode==2)
	{
	$maxclkamount=trim($_POST['maxclkamount']);
	phpSafe($maxclkamount);
	if($maxclkamount=="")
	{
	
		header("Location:show-message.php?id=1001");
		exit(0);
	}

	if($maxclkamount<$min_click_value)
	{
		header("Location:show-message.php?id=2008");
		exit(0);
		
	}
	

}



$t=time();
$uid=$user->getUserID();
$username=$user->getUsername($uid);
//admin notification mail
		$msg = <<< EOB

Hello,

One of the text ads has been edited at $ppc_engine_name.

Title		: $title
Ad id		: $id
Edited by	: $username


Login to your admin area for activating this ad.

Regards,
$ppc_engine_name

EOB;

//echo $msg;









if(($dummytitle != $title) || ($dummyurl != $url) || ($dummysummary != $summary)  || ($dummydisplayurl != $displayurl) || ($dummyadult != $adult_status))
{
mysql_query("update ppc_ads set name='$adname',link='$url',title='$title', summary='$summary',maxamount='$maxamount',displayurl='$displayurl', status=-1,updatedtime='$t',adlang='$adlang',adult_status='$adult_status' where id=$id $wap_string");

}
else
{
mysql_query("update ppc_ads set name='$adname',link='$url',title='$title', summary='$summary',maxamount='$maxamount',displayurl='$displayurl',updatedtime='$t',adlang='$adlang' where id=$id $wap_string");

}


if(($dummytitle != $title) || ($dummyurl != $url) || ($dummysummary != $summary)  || ($dummydisplayurl != $displayurl))
{

if($script_mode!="demo")
xMail($admin_general_notification_email, "$ppc_engine_name - Text Ad Modified", $msg, $admin_general_notification_email, $email_encoding);


}


//****************************************************** CODE FOR TIME TARGETING ***********************************************
//$aa=mysql_query("select status from ppc_ads where id=$id $wap_string");
//$bb=mysql_fetch_row($aa);

$bb=$mysql->echo_one("select status from ppc_ads where id=$id");
$time_count=$mysql->echo_one("select count(id) from time_targeting WHERE `aid` =$id ;");
if($t_duration==1 && $time_count>0)
{
	mysql_query("delete from time_targeting where aid=$id;");
}

if($t_duration==2 && $tm_flg==1	)
{
if($time_count>0)
mysql_query("UPDATE `time_targeting` SET `date_tar_s` = '$sdate',`date_tar_e` = '$edate',`date_flg` = '0' WHERE `aid` =$id ;");
else
mysql_query("insert into time_targeting values('0','$id','$sdate','$edate','0','0','0','0','$date_flg','0','0')");
}
else if($t_duration==3 && $tm_flg==1)
{
    $date_flg=trim($_POST['timeduration']);
	$timeduration=trim($_POST['timeduration']);
    
	
	if($time_count>0)
	{ 
	  if($bb!=-1)
	  {
	  	
		$sdate=time(); 
		//echo $date_flag; exit;
		if($date_flg ==1)
		{
		$edate=$sdate+60*60*24*7;
		}
		if($date_flg ==2)
		{
		$edate=$sdate+60*60*24*14;
		}
		if($date_flg ==3)
		{
		$edate=$sdate+60*60*24*30;
    	}
		
	    mysql_query("UPDATE `time_targeting` SET `date_tar_s` = '$sdate',`date_tar_e` = '$edate',`date_flg` = '$date_flg' WHERE `aid` ='$id' ;");
	    //mysql_query("UPDATE `time_targeting` SET `date_tar_s` = 0,`date_tar_e` =0,`date_flg` = '$date_flg' WHERE `aid` =$id ;");
	  }
	 else
	  {
       mysql_query("UPDATE `time_targeting` SET `date_tar_s` = 0,`date_tar_e` =0,`date_flg` = '$date_flg' WHERE `aid` ='$id' ;");
	  }
	}
    else
	{
     mysql_query("insert into time_targeting values('0','$id','0','0','0','0','0','0','$date_flg','0','0')");
	 }
     
}

if($time_targeting==1)
{
//echo "UPDATE `ppc_ads` SET `beg_time`='$beg_time',`end_time`='$end_time' WHERE id='$id'"; exit;
mysql_query("UPDATE `ppc_ads` SET `beg_time`='$beg_time',`end_time`='$end_time' WHERE id='$id'");

}

//****************************************************** CODE FOR TIME TARGETING ***********************************************

if($ad_keyword_mode==2){
mysql_query("update ppc_keywords set maxcv='$maxclkamount' where aid='$id'");
$page=urlencode("manage-ads.php?wap=$wap_flag&pass=true");
}
else{
//echo "update ppc_ads set link='$url',title='$title', summary='$summary',maxamount='$maxamount' where id=$id";
$page=urlencode("ppc-manage-keywords.php?id=$id&wap=$wap_flag&pass=true");
}
header("Location:show-success.php?id=5011&page=$page");



?>