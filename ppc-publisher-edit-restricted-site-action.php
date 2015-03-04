<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");

$ini_error_status=ini_get('error_reporting');
$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}

$site=trim($_POST['site']);
$id=getSafePositiveInteger('id');
if(!mySite($id,$user->getUserID(),$mysql))
{
header("Location:publisher-show-message.php?id=5010");
exit(0);
}
//echo $id;
phpSafe($site);
if($site=="")
{
header("Location:publisher-show-message.php?id=1001");
exit(0);
}
if(!isDomain($site))
{
header("Location:publisher-show-message.php?id=1030");
exit(0);
}
if(strcasecmp(substr($site,0,4),"www.")==0)
{
$site=substr($site,4);
}
//$pagename='<a href=ppc-publisher-manage-restrict-site.php>Manage Restricted Sites</a> |';
$uid=$user->getUserID();

if($mysql->total("ppc_restricted_sites","uid='$uid' and site='$site' and id<>$id ")!=0)
{
header("Location:publisher-show-message.php?id=6043");
exit(0);
}

mysql_query("update ppc_restricted_sites set site='$site' where id='$id'");

if($ini_error_status!=0)
{
	echo mysql_error();
}
$page=urlencode("ppc-publisher-manage-restrict-site.php");
header("Location:publisher-show-success.php?id=1015&type=1&status=1&page=$page");
exit(0);


?>
