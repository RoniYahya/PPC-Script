<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
//includeClass("Form");
//includeClass("Template");

//$template=new Template();
//$template->loadTemplate("ppc-templates/ppc-add-keywords.tpl.html");
$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$id=getSafePositiveInteger('id','g');
$type=getSafeInteger('type','g');
$url=$_GET['url'];
if(!myAd($id,$user->getUserID(),$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}
$uid=$user->getUserID();

mysql_query("update ppc_ads set pausestatus=$type where uid='$uid' AND id='$id'");
header("Location: $url");
?>