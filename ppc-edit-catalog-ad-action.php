<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("ImageResizer");

include("swf-function-inc.php");

$user=new User("ppc_users");
if(!$user->validateUser())
{
	header("Location:show-message.php?id=1006");
	exit(0);
}

$adname=trim($_POST['adname']);
$bannersize=$_POST['bannersize'];
$file=$_FILES['banner']['name'];
$file=str_replace('#','',$file);                              //************** For SWF



$summary=$_POST['summary'];
$displayurl=$_POST['displayurl'];
$url=$_POST['url'];
$maxamount=trim($_POST['maxamount']);
$adlang=trim($_POST['language']);
$adult_status=trim($_POST['adult_status']);





$dummyurl=trim($_POST['dummylink']);
$dummysummary=trim($_POST['dummysummary']);
$dummydisplayurl=trim($_POST['dummydisplayurl']);
$dummyadult=trim($_POST['dummyadult']);
//$dummybannersize=trim($_POST['dummybannersize']);
//$dummytitle=trim($_POST['dummytitle']);   //this equals $file value

phpSafe($dummyurl);
phpSafe($dummysummary);
phpSafe($dummydisplayurl);
phpSafe($dummyadult);
//phpSafe($dummybannersize);
//phpSafe($dummytitle);
phpSafe($adult_status);


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


//echo $file."ddddd";
//print_r($_POST);
$id=getSafePositiveInteger('id','p');
if(!myAd($id,$user->getUserID(),$mysql))
{
	header("Location:show-message.php?id=5010");
	exit(0);
}
phpSafe($adlang);
phpSafe($maxamount);
phpSafe($bannersize);
phpSafeUrl($url);
phpSafe($file);
phpSafe($adname);


///////////////////////wap
/*
if(isset($_POST['wap']))
{
$wap_flag=$_POST['wap'];
}
else 
{
	$wap_flag=0;
}
phpsafe($wap_flag);*/

$wap_flag=$mysql->echo_one("select wapstatus from ppc_ads where id=$id ");

if($wap_flag==1)
{
	$wap_table='wap_ad_block';
	$wap_table1='wap_custom_ad_block';
	$wap_string="and wapstatus=1";
	
	$durl_length=$GLOBALS['wap_url_length'];
	$desc_length=$GLOBALS['wap_desc_length'];
}

else
{
	$wap_table='ppc_ad_block';
	$wap_table1='ppc_custom_ad_block';
	$wap_string="and wapstatus=0";
	
	
	$durl_length= $ad_displayurl_maxlength;
	$desc_length=$ad_description_maxlength;
	
}

if(get_magic_quotes_gpc())
{
$summary=stripslashes($summary);
$displayurl=stripslashes($displayurl);
}		

$summary=mb_substr($summary,0,$desc_length,$ad_display_char_encoding);
$displayurl=mb_substr($displayurl,0,$durl_length,$ad_display_char_encoding);
phpSafe($displayurl);
phpSafe($summary);

///////////////////////wap

if($adname==""||$bannersize==""||$maxamount=="" || $summary=="" || $displayurl==""||$adlang=="")
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

if($file!="")
{

	$exten=strtolower(substr($file,-4));
	
	
	
	if($wap_flag==1)
    {
	if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png" )
	{
		header("Location:show-message.php?id=5014");
		exit(0);
	}
	
	}
	else
	{
	
	if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png" && $exten!=".swf")
	{
	header("Location:show-message.php?id=10010");
	exit(0);
	
	
	}
	
	}
	
	
	
	
}

$newexten=str_replace('.',"",$exten);                    //************************ For Flash Ads ******************************







$sqladdon="";

if($file!="")
{

$width=$mysql->echo_one("select width from catalog_dimension where id=$bannersize $wap_string");
$height=$mysql->echo_one("select height from catalog_dimension where id=$bannersize $wap_string");
$filesize=$mysql->echo_one("select filesize from catalog_dimension where id=$bannersize $wap_string");
$size=getimagesize($_FILES['banner']['tmp_name']);


if((($_FILES['banner']['size'])/1024) > $filesize )
		{
			header("Location:show-message.php?id=5016");
			exit(0);
		}

if($newexten=="swf")
{
if(($size[0] > $width) || ($size[1] > $height) || ($size[0] < $width) || ($size[1] < $height) )
{	
	header("Location:show-message.php?id=5012");
	exit(0);

}
}

if($newexten=="swf")
{
$name_new=$_FILES['banner']['tmp_name'];
$swf_file_news=Inout_FlashImageRetrieveFun($name_new);

list($result_news, $flg_href_new) = Inout_FlashInfoFun($swf_file_news);    //*************** For Finding HardCoded Links***********************************

if($flg_href_new !=0)
{

header("Location:show-message.php?id=10011");             //*************** If non-replacable Hard Coded Links Exists in the Image *******
exit(0);
}

}



	
	if(copy($_FILES['banner']['tmp_name'],$GLOBALS['banners_folder']."/$id/".$file))
	{
	
	//************************ Changes For Flash Ads ***************************************************************************



if($newexten=="swf")
{
$hard_count=0;

$name=$GLOBALS['banners_folder']."/$id/".$file;
$swf_file=Inout_FlashImageRetrieveFun($name);

list($result, $flg_href) = Inout_FlashInfoFun($swf_file);    //*************** For Finding HardCoded Links***********************************

/*
if($flg_href !=0)
{
header("Location:show-message.php?id=10011");             //*************** If non-replacable Hard Coded Links Exists in the Image *******
exit(0);
}
*/

if(count($result) >0)                                    //*************** If HardCoded Links Already Exists ****************************
{
$hard_count=count($result);

}



if(count($result) >0)                                    //*************** If HardCoded Links Already Exists ****************************
{
   if ($swf_file) {
        if (Inout_FlashVersionFun($swf_file) >= 3 && Inout_FlashInfoFun($swf_file))
		 {
		
		   // SWF's requiring player version 6+ which are already compressed should stay compressed
            if (Inout_FlashVersionFun($swf_file) >= 6 && Inout_FlashCompressedFun($swf_file))
			{
                $compress = true;
            } 
			elseif (isset($compress))
			{
                $compress = true;
            } 
			else 
			{
                $compress = false;
            }

           $convert_links = array();
          
for($i=0;$i < count($result);$i++)
{
$convert_links[$i]=$i+1;
}

  list($result, $parameters) = Inout_FlashConvertFun($swf_file, $compress, $convert_links);              //********** For Removing HardCoded Links ***************

                     
$name=$GLOBALS['banners_folder']."/$id/".$file;           
Inout_FlashImageStoreFun($name,$result);                                                                    //********** For Storing Converted Images ***************     


            
        }
    }

}



$sqladdon=",bannersize='$bannersize', title='".$file."',contenttype='".$newexten."',hardcodelinks='".$hard_count."'";

}
//************************ Changes For Flash Ads ***************************************************************************
else
{	
		
		$sqladdon=",bannersize='$bannersize', title='".$file."',contenttype='',hardcodelinks='0'";

       //  image resizing
  
		$rimg = new ImageResizer($GLOBALS['banners_folder']."/$id/$file");
		$rimg->resize($width,$height,$GLOBALS['banners_folder']."/$id/$file");
		


	}	




	
	}
}





$t=time();
$uid=$user->getUserID();
$username=$user->getUsername($uid);




if(($dummyurl != $url) || ($dummysummary != $summary) || ($dummydisplayurl != $displayurl) || ($sqladdon !="") || ($dummyadult != $adult_status))
{

mysql_query("update ppc_ads set name='$adname',link='$url' $sqladdon , displayurl='$displayurl',summary='$summary',maxamount='$maxamount', status=-1,updatedtime='$t',adlang='$adlang',adult_status='$adult_status' where id=$id $wap_string");


}
else
{

mysql_query("update ppc_ads set name='$adname',link='$url' $sqladdon , displayurl='$displayurl',summary='$summary',maxamount='$maxamount',updatedtime='$t',adlang='$adlang' where id=$id $wap_string");

}


//echo "update ppc_ads set link='$url' $sqladdon displayurl='$displayurl',summary='$summary',maxamount='$maxamount', status=-1,updatedtime='$t' where id=$id $wap_string";
//die;

//echo "update ppc_ads set link='$url' $sqladdon ,maxamount='$maxamount', status=-1,updatedtime='$t' where id=$id";
//send admin notification mail

		$msg = <<< EOB

Hello,

One of the catalog ads has been edited at $ppc_engine_name.

Banner Id	: $id
Edited by	: $username

Login to your admin area for activating this ad.

Regards,
$ppc_engine_name

EOB;

//echo $msg;
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

if(($dummyurl != $url) || ($dummysummary != $summary) || ($dummydisplayurl != $displayurl) || ($sqladdon !=""))
{


if($script_mode!="demo")
xMail($admin_general_notification_email, "$ppc_engine_name - Catalog Ad Modified", $msg, $admin_general_notification_email, $email_encoding);

}


//****************************************************** CODE FOR TIME TARGETING ***********************************************
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


//send admin notification mail
if($ad_keyword_mode==2){
mysql_query("update ppc_keywords set maxcv='$maxclkamount' where aid='$id'");
$page=urlencode("manage-ads.php?wap=$wap_flag");
}
else{
//echo "update ppc_ads set link='$url',title='$title', summary='$summary',maxamount='$maxamount' where id=$id";
$page=urlencode("ppc-manage-keywords.php?id=$id&wap=$wap_flag");
}


header("Location:show-success.php?id=9009&page=$page");

?>