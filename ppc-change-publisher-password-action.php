<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");

$user=new User("ppc_publishers");
if($user->getUsername()=="publisher" && $script_mode=="demo")
{
header("Location:publisher-show-message.php?id=6076");
exit(0);
}
$pubid=$user->getUserID();
$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid=$pubid");
if(($commonid!=0))
{
if($_COOKIE['io_type']==md5("Common"))
	{				
header("Location: change-password.php");
exit(0);	
	}
}
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
phpSafe($_POST['oldpass']);
phpSafe($_POST['newpass']);
phpSafe($_POST['newpass2']);

$msgid=$user->changePassword($_POST['oldpass'],$_POST['newpass'],$_POST['newpass2'],$min_user_password_length);
if($msgid==1010)
{

header("Location:publisher-show-success.php?id=1010");
}
else
{
header("Location:publisher-show-message.php?id=$msgid");
}

?>
