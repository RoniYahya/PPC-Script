<?php 
/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/
?><?php 
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");




$email=trim($_POST['email']);


phpSafe($email);


if($email=="")
{
header("Location:publisher-show-message.php?id=1001");
exit(0);
}

if(!isDomain($email))
	{
	header("Location:publisher-show-message.php?id=1030");
exit(0);
}
if(strcasecmp(substr($email,0,4),"www.")==0)
{
$mail=substr($email,4);
}
else
$mail=$email;


$user=new User("ppc_publishers");

$userid=$user->getUserID();


$pagename='<a href=ppc-publisher-manage-restrict-site.php>Manage Restricted Sites</a> |';

if($mysql->total("ppc_restricted_sites","uid='$userid' and site='$mail'")==0)
{
mysql_query("insert into ppc_restricted_sites values('0','$userid','$mail')");
if(isset($_POST['customid'])&& trim($_POST['customid']!=""))
{
$inid=$mysql->echo_one("select bid from ppc_custom_ad_block where id=".$_POST['customid']."");
$intype=$mysql->echo_one("select ad_type from ppc_ad_block where id='$inid'");

if($intype!=7 && $intype!=6 )
{

header("Location:publisher-show-success.php?id=1014&page=".urlencode("ppc-edit-adblock.php?customid=".$_POST['customid']));

}else
{
header("Location:publisher-show-success.php?id=1014&page=".urlencode("ppc-edit-inline-adblock.php?customid=".$_POST['customid']));
}
}
else
{

header("Location:publisher-show-success.php?id=1014&page=".urlencode("ppc-publisher-manage-restrict-site.php"));
exit(0);
}
}
else
{
header("Location:publisher-show-message.php?id=6043");
exit(0);
}
?>