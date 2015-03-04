<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");


if($single_account_mode==0)
{
	header("Location: index.php");
exit(0);
}
if($_COOKIE['io_type']==md5("advertiser"))
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_users where username='".$_COOKIE['io_username']."'");
	
}
if($_COOKIE['io_type']==md5("publisher"))
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where username='".$_COOKIE['io_username']."'");

}
if($commonid==0)
{
if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-change-password.php");
exit(0);	
	}
	elseif($_COOKIE['io_type']==md5("publisher"))
	{
	header("Location: ppc-change-publisher-password.php");
exit(0);	
	}
}

if($portal_system==1)
{
	//redirect  this page to portal corrensponding page
}
$user=new User("nesote_inoutscripts_users", "id");
if($user->getUsername()=="demouser" && $script_mode=="demo")
{
header("Location:error-message.php?id=6076");
exit(0);
}

if(!$user->validateUser())
{
header("Location:error-message.php?id=1006");
exit(0);
}
phpSafe($_POST['oldpass']);
phpSafe($_POST['newpass']);
phpSafe($_POST['newpass2']);

$msgid=$user->changePassword($_POST['oldpass'],$_POST['newpass'],$_POST['newpass2'],$min_user_password_length);
if($msgid==1010)
{

header("Location:success-message.php?id=1010");
exit;
}
else
{
header("Location:error-message.php?id=$msgid");
exit;
}

?>