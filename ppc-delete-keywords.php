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
$id=getSafePositiveInteger('id','g');

if(!myKeyword($id,$user->getUserID(),$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}

$url=$_GET['url'];

$uid=$user->getUserID();
$aid=$mysql->echo_one("select aid from ppc_keywords where uid='$uid' AND id='$id'");
$wap_flag=$mysql->echo_one("select wapstatus from ppc_ads where id='$aid' ");
$url.="&wap=$wap_flag";
$rem_keyword=$mysql->echo_one("select count(*) from ppc_keywords where uid='$uid' AND aid='$aid'");

if($rem_keyword==1 && $ad_keyword_mode==1)
	{
	header("Location:show-message.php?id=1035");
exit(0);
	}
$keyword=$mysql->echo_one("select keyword from ppc_keywords where uid='$uid' AND id='$id'");
if($keyword=="$keywords_default" && $ad_keyword_mode==3)
{

header("Location:show-message.php?id=1021");
exit(0);
}
else
{
mysql_query("delete from ppc_keywords where uid='$uid' AND id='$id'");
$rem_keyword=$mysql->echo_one("select count(*) from ppc_keywords where uid='$uid' AND aid='$aid'");
//echo "<br>select count(*) from ppc_keywords where uid='$uid' AND id='$id'<br>";
//echo "rem_keyword=$rem_keyword";
//echo "select count(id) from ppc_keywords where uid='$uid' AND id='$id'";
if($rem_keyword==0 && $ad_keyword_mode==3)
	{
	if($keywords_default!="")
		{
	//	echo "am going to insert default keyword";
		mysql_query("insert into ppc_keywords values('0','$aid','$uid','$keywords_default','$min_click_value','$auto_keyword_approve','".time()."','$min_click_value');");
//		echo "insert into ppc_keywords values('0','$aid','$uid','$keywords_default','$min_click_value','$auto_keyword_approve','".time()."','$min_click_value');";
		
			if($ini_error_status!=0)
			{
				echo mysql_error();
			}
	
		}
	}
header("Location:$url");
//header("Location:show-success.php?id=5006&page=$url");
exit(0);
}

?>