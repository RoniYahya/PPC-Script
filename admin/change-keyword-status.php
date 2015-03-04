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
$kid=trim($_GET['id']);
phpsafe($kid);
$action=trim($_GET['action']);
phpsafe($action);
if($action=="activate")
{
	mysql_query("update ppc_keywords set status=1 where sid=$kid");
	mysql_query("update system_keywords set status=1 where id=$kid");
}
elseif($action=="block")
{
		mysql_query("update ppc_keywords set status=0 where sid=$kid");
			mysql_query("update system_keywords set status=0 where id=$kid");
}
else
{
	mysql_query("update ppc_keywords set status=0 where sid=$kid");
		mysql_query("update system_keywords set status=0 where id=$kid");
}
header("Location: ".urldecode($_GET['url']));
exit(0);
?>