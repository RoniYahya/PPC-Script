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


$uid=trim($_POST['uid']);
$url=urldecode($_POST['url']);
	//echo $url;die;
$text=trim($_POST['text']);
phpSafe($uid);
//phpSafe($url);
//phpSafe($text);
if(get_magic_quotes_gpc())
	$text=stripslashes($text);

//You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '' at line 1 
$email=$mysql->echo_one("select email from nesote_inoutscripts_users where id='$uid'");


$subject=$_POST['subject'];
if(get_magic_quotes_gpc())
	{
	$text=stripslashes($text);
	$subject=stripslashes($subject);
	}
	  if($script_mode!="demo")
 {

  include("class.Email.php");
  
	  $message = new Email($email, $admin_general_notification_email, $subject,'');
	  $message->Cc = ''; 
	  $message->Bcc = ''; 
	  
	  $message->SetHtmlContent(nl2br($text));  
	$message->Send();
}
$flag=0;
mysql_query("BEGIN"); 
if($portal_system!=1)
{
	if(!mysql_query("update nesote_inoutscripts_users set status=1 where id='$uid'"))
	{
		$flag=1;
	}
}
if(!mysql_query("update ppc_publishers set status=1 where common_account_id='$uid'"))
{
	$flag=1;
}
if(!mysql_query("update ppc_users set status=1 where common_account_id='$uid'"))
{
	$flag=1;
}
if($flag==1)
{
		mysql_query("ROLLBACK");
		include_once("admin.header.inc.php"); ?>
		<span class="already">An error has occurred. Please try again later. <a href="javascript:history.back(-1);">Go Back</a></span>
<?php
include("admin.footer.inc.php"); 
die;
}
else
{
	mysql_query("COMMIT");
	header("location:$url");
				exit(0);	
}		
?>