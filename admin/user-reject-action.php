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





$uid=$_POST['uid'];
$url=urldecode( $_POST['url']);
$text=$_POST['text'];
if(get_magic_quotes_gpc())
	$text=stripslashes($text);
	
$email=$mysql->echo_one("select email from nesote_inoutscripts_users where id=$uid");


$subject=$_POST['subject'];
if(get_magic_quotes_gpc())
	{
	$text=stripslashes($text);
	$subject=stripslashes($subject);
	}
	
		  if($script_mode!="demo")
 {

  include("class.Email.php");

	  $message = new Email($email,$admin_general_notification_email, $subject,'');
	   
	  $message->Cc = ''; 
	  $message->Bcc = ''; 
	  $message->SetHtmlContent(nl2br($text));  
	$message->Send();
		
}
	if($portal_system!=1)
	{
		mysql_query("update nesote_inoutscripts_users set status=0 where id='$uid';");
	}
				mysql_query("update ppc_publishers set status=0 where common_account_id='$uid';");
				mysql_query("update ppc_users set status=0 where common_account_id='$uid';");
					header("location:$url");
				exit(0);
				
?>