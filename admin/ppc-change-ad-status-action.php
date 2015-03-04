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
$id=$_POST['id'];
phpsafe($id);
$action=$_POST['action'];

$text=$_POST['text'];
$url=urldecode($_POST['url']);
$send_mail=$_POST['send_mail'];
$subject=$_POST['subject'];
//echo $send_mail;
//exit(0);

if(get_magic_quotes_gpc())
	{
	$text=stripslashes($text);
	$subject=stripslashes($subject);
	}
$email=$mysql->echo_one("select b.email from ppc_ads a,ppc_users b where a.uid=b.uid and a.id='$id'");
//$email=$mysql->echo_one("select email from ppc_publishers where uid=$uid");


 /* if($script_mode!="demo")
 {

if($send_mail==1)
	{
	include("class.Email.php");
	$message = new Email($email, $admin_general_notification_email, $subject, '');
	$message->Cc = ''; 
	$message->Bcc = ''; 
	$message->SetHtmlContent(nl2br($text));  
	$message->Send();

	}
}*/
if($action=="block")
{
//echo "update ppc_users set status=0 where uid=$uid;";
mysql_query("update ppc_ads set status=0 where id=$id;");
}
if($action=="activate")
{
mysql_query("update ppc_ads set status=1 where id='$id';");
/////////////************************time targetting************************//////


$date_flag=$mysql->echo_one("select date_flg from time_targeting WHERE `aid` =$id ;");
if($date_flag !='' && $date_flag !=0)
{
	$sdate=time();
if($date_flag ==1)
{
	$edate=$sdate+60*60*24*7;
}
if($date_flag ==2)
{
	$edate=$sdate+60*60*24*14;
}
if($date_flag ==3)
{
	$edate=$sdate+60*60*24*30;
}
	mysql_query("UPDATE `time_targeting` SET `date_tar_s` = '$sdate',`date_tar_e` = '$edate' WHERE `aid` =$id ;");
}

/////////////************************time targetting************************//////

}
header("location:$url");
exit(0);
?>
