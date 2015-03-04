<?php

//print_r($_POST); exit;
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");

$ini_error_status=ini_get('error_reporting');
$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}

//print_r($_POST);exit;

$title=trim($_POST['title']);
$url=trim($_POST['url']);
$summary=trim($_POST['summary']);
$maxamount=trim($_POST['maxamount']);
$displayurl=trim($_POST['displayurl']);
$wap_flag=trim($_POST['wap_flag']);
$adlang=trim($_POST['language']);
$url1=trim($_POST['url1']);
$adname=trim($_POST['adname']);
$adult_status=trim($_POST['adult_status']);
phpSafe($adlang);
phpSafeUrl($url);
phpSafe($maxamount);
phpSafe($wap_flag);
phpSafe($adult_status);
$url=str_replace("http://",'',$url);
$url=str_replace("https://",'',$url);

$url=$url1."://".$url;

       if($wap_flag==1)
       {
            $table='';
            $wap_string='and wapstatus=1';
$title_length=$GLOBALS['wap_title_length'];
	$durl_length=$GLOBALS['wap_url_length'];
	$desc_length=$GLOBALS['wap_desc_length'];
       }
      else
        {
        	$table='';
            $wap_string='and wapstatus=0';
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
if($title==""||$url==""||$summary==""||$maxamount=="" || $displayurl==""||$adlang==""||$adname=="")
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

//////////****************time targetting***************************////////////////////
//**********************Date******************


$time_duration_flag=0;
$t_duration=$_POST['duration']; //option 1,2,3 according to the order


 $tm_flg=0;	
if($t_duration==2)
{
	$date_flg=0;
	
$sdate=trim($_POST['popup_container']);    //start date in real time format(eg:12/21/2012)
$edate=trim($_POST['popup_container1']);   //end date in real time format

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




$temp_val=$min_click_value;
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
	$temp_val=$maxclkamount;

}
$userid=$user->getUserID();
$username=$user->getUsername($userid);
mysql_query("INSERT INTO `ppc_ads` ( `id` , `uid` , `link` , `title` , `summary` , `maxamount` , `amountused` , `createtime` , `status` ,`adtype`,`displayurl`,`updatedtime`,`wapstatus`,`adlang`,`name`,`adult_status`)
VALUES ('0', '$userid', '$url', '$title', '$summary', '$maxamount', '0', '".time()."', '-1','0','$displayurl','".time()."',$wap_flag,$adlang,'$adname','$adult_status')");
$id=$mysql->echo_one("select id from ppc_ads where uid='$userid' $wap_string order by id desc limit 0,1");


mysql_query("INSERT INTO `ad_location_mapping` (`adid` , `country` , `region` , `city`)
VALUES ('$id', '00', '00', '00')");
//if($auto_keyword_approve==0)
//{
//	$key_status=-1;
//}
//else
//{
//	$key_status=$auto_keyword_approve;
//}


if($keywords_default!="")
{
	$mainid=$mysql->echo_one("select id from system_keywords where keyword='$keywords_default'");
	mysql_query("insert into ppc_keywords values('0','$id','$userid','$keywords_default','$temp_val','1','".time()."','$min_click_value','$mainid');");

	if($ini_error_status!=0)
	{
		echo mysql_error();
	}

}


//****************************************************** CODE FOR TIME TARGETING ***********************************************








 
if($t_duration==2 && $tm_flg==1	)
{
	
mysql_query("insert into time_targeting values('0','$id','$sdate','$edate','0','0','0','0','$date_flg','0','0')");
}
else if($t_duration==3 && $tm_flg==1)
{
	$date_flg=trim($_POST['timeduration']);
	
	$timeduration=trim($_POST['timeduration']);
    
	
     mysql_query("insert into time_targeting values('0','$id','0','0','0','0','0','0','$date_flg','0','0')");
     
}



if($time_targeting==1)
{
//echo "UPDATE `ppc_ads` SET `beg_time`='$beg_time',`end_time`='$end_time' WHERE id='$id'"; exit;
mysql_query("UPDATE `ppc_ads` SET `beg_time`='$beg_time',`end_time`='$end_time' WHERE id='$id'");

}


//****************************************************** CODE FOR TIME TARGETING ***********************************************





//admin notification mail
$msg = <<< EOB

Hello,

A new text ad has been created at $ppc_engine_name.

Title		: $title
Ad id		: $id
Created by	: $username


Login to your admin area for approving/deleting this ad.

Regards,
$ppc_engine_name

EOB;

//echo $msg;

if($script_mode!="demo")
xMail($admin_general_notification_email, "$ppc_engine_name - Text Ad Created", $msg, $admin_general_notification_email, $email_encoding);
//

if($ad_keyword_mode==2)
header("Location:ppc-manage-target-locations.php?id=$id&wap=$wap_flag&pass=true");
else
header("Location:ppc-manage-keywords.php?id=$id&pass=true&wap=$wap_flag");
exit(0);
?>