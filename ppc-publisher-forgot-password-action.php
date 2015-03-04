<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
//includeClass("Template");

if($single_account_mode==1)
{
	header("Location: forgot-password.php");
exit(0);
}
$user=new User("ppc_publishers");
phpSafe($_POST['username']);
if($_POST['username']=="publisher" && $script_mode=="demo")
{
header("Location:publisher-show-message.php?id=6076");
exit(0);

}

if($user->sendPassword($_POST['username'],"$admin_general_notification_email","Your ".$ppc_engine_name." Login Info")){
//$form=new Form("EditPassword","ppc-forgot-password-action.php");
header("Location:publisher-show-success.php?id=1011&type=1&status=0");
}
else
{
header("Location:publisher-show-message.php?id=1012");
}

?>
