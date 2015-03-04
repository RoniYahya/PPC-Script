<?php
include("config.inc.php");

if(!isset($_COOKIE['inout_admin']))
{
	header("Location:index.php");
	exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	header("Location:index.php");
	exit(0);
}

if($script_mode=="demo")
	{
 include_once("admin.header.inc.php");
		echo "<br>This feature is disabled in demo version<br><br>";
		include("admin.footer.inc.php");
		exit(0);
	}

$uid=$_GET['id'];


//$text=$_POST['text'];
$url=urldecode( $_GET['url']);
//$subject=$_POST['subject'];
//if(get_magic_quotes_gpc())
//	{
//	$text=stripslashes($text);
//	$subject=stripslashes($subject);
//	}
//$email=$mysql->echo_one("select email from nesote_inoutscripts_users where id=$uid");

// if($script_mode!="demo")
// {
//
//include("class.Email.php");
//
//$message = new Email($email, $admin_general_notification_email, $subject, '');
//$message->Cc = ''; 
//$message->Bcc = ''; 
//
//$message->SetHtmlContent(nl2br($text));  
//$message->Send();
//
//	}
	
	$advid=$mysql->echo_one("select uid from ppc_users where common_account_id=$uid");
	$pubid=$mysql->echo_one("select uid from ppc_publishers where common_account_id=$uid");
	
if($advid!=0)
{
	$adcount1=mysql_query("select id from ppc_ads where uid=$advid");
$adcount=mysql_num_rows($adcount1);
if($adcount!=0)
	mysql_query("delete from ppc_ads where uid=$advid");

	mysql_query("delete from ppc_users where common_account_id=$uid ");
}

if($pubid!=0)
{
	$adunitcount=$mysql->echo_one("select count(*) from ppc_custom_ad_block where pid=$pubid");
$restrictedsite=$mysql->echo_one("select count(*) from ppc_restricted_sites where uid=$pubid ");
$payment_detail=$mysql->echo_one("select count(*) from ppc_publisher_payment_details where uid=$pubid ");
if($adunitcount!=0)
{
	mysql_query("delete from ppc_custom_ad_block where pid=$pubid");
	
}

if($payment_detail!=0)
{
mysql_query("delete from ppc_publisher_payment_details where uid=$pubid ");
}

if($restrictedsite!=0)
{
mysql_query("delete from ppc_restricted_sites where uid=$pubid ");	
}


mysql_query("delete from ppc_publishers where common_account_id=$uid ");
}
mysql_query("delete from nesote_inoutscripts_users where id=$uid");

header("location: $url");
exit(0);
?>