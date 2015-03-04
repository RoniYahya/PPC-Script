<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();

$id=getSafePositiveInteger('id','g');
if( $script_mode=="demo" && ($id=="1" || $id=="6"))
{
header("Location:show-message.php?id=6076");
exit(0);
}

$url=$_GET['url'];
$val=$mysql->echo_one("select count(*) from ppc_custom_ad_block where id='$id' and pid='$uid'");
if($val==0)
{
header("Location:publisher-show-message.php?id=1020");
exit(0);
}
mysql_query("delete from ppc_custom_ad_block where id='$id'");	
header("location: $url");
exit(0);
?> 
