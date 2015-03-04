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
$id=trim($_GET['id']);
$adult_status=trim($_GET['status']);



mysql_query("update ppc_ads set adult_status='$adult_status' where id=$id");

header("Location:ppc-view-ads.php");
exit(0);

?>