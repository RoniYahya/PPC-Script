<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
//includeClass("Template");

if($single_account_mode==0)
{
	

	header("location: index.php");
	exit;
	
}
if($portal_system==1)
{
	//redirect  this page to portal corrensponding page
}
if(isset($_POST['radiobutton']))
{
$table=$_POST['radiobutton'];
phpSafe($table);
if($table=="1")
$user=new User("ppc_users");
elseif($table=="2")
$user=new User("ppc_publishers");
else
$user=new User("nesote_inoutscripts_users", "id");
}
else
{
	$user=new User("nesote_inoutscripts_users", "id");
}
phpSafe($_POST['username']);
if($_POST['username']=="demouser" && $script_mode=="demo")
{
header("Location: error-message.php?id=6076");
exit(0);
}
if($user->sendPassword($_POST['username'],"$admin_general_notification_email","Your ".$ppc_engine_name." Login Info")){
//$form=new Form("EditPassword","ppc-forgot-password-action.php");
header("Location: success-message.php?id=1011");
}
else
{
header("Location: error-message.php?id=1012");
}
?>