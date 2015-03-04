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
?>
<?php //include("admin.header.inc.php"); ?>
<?php

$uid=$_POST['uid'];
$action=$_POST['action'];


$text=$_POST['text'];
$url=urldecode( $_POST['url']);

if(get_magic_quotes_gpc())
$text=stripslashes($text);

$email=$mysql->echo_one("select email from nesote_inoutscripts_users where id='$uid'");

$subject=$_POST['subject'];
if(get_magic_quotes_gpc())
$subject=stripslashes($subject);
include("class.Email.php");

if($script_mode!="demo")
{

	$message = new Email($email, $admin_general_notification_email, $subject, '');
	$message->Cc = '';
	$message->Bcc = '';

	$message->SetHtmlContent(nl2br($text));
	$message->Send();

}

$flag=0;
mysql_query("BEGIN");
if($action=="block")
{
	//echo "update ppc_users set status=0 where uid=$uid;";
	if($portal_system!=1)
	{
		if(!mysql_query("update nesote_inoutscripts_users set status=0 where id='$uid';"))
		{
			$flag=1;
		}
	}
	if(!mysql_query("update ppc_users set status=0 where common_account_id='$uid';"))
	{
		$flag=1;
	}
	if(!mysql_query("update ppc_publishers set status=0 where common_account_id='$uid';"))
	{
		$flag=1;
	}

}
if($action=="activate")
{
	if($portal_system!=1)
	{
		if(!mysql_query("update nesote_inoutscripts_users set status=1 where id='$uid';"))
		{
			$flag=1;
		}
	}
	if(!mysql_query("update ppc_users set status=1 where common_account_id='$uid';"))
	{
		$flag=1;
	}
	if(!mysql_query("update ppc_publishers set status=1 where common_account_id='$uid';"))
	{
		$flag=1;
	}
}
if($action=="reject")
{
	//echo "update ppc_users set status=0 where uid=$uid;";
	if($portal_system!=1)
	{
		if(!mysql_query("update nesote_inoutscripts_users set status=0 where id='$uid';"))
		{
			$flag=1;
		}
	}
	if(!mysql_query("update ppc_users set status=0 where common_account_id='$uid';"))
	{
		$flag=1;
	}
	if(!mysql_query("update ppc_publishers set status=0 where common_account_id='$uid';"))
	{
		$flag=1;
	}
}
if($flag==1)
{
	mysql_query("ROLLBACK");
	include_once("admin.header.inc.php"); ?>
<span class="already">An error has occurred. Please try again later. <a
	href="javascript:history.back(-1);">Go Back</a></span>
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