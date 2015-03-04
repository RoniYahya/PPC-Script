<?php 

/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?><?php

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

//include_once("admin.header.inc.php");
$id=$_REQUEST['id'];
phpsafe($id);
$status=$_REQUEST['status'];
$url=urldecode($_POST['url']);
$urlstr="";
$str="all";
if($status==0)
{
$urlstr="?status=0";
$str="pending";
}

	mysql_query("update advertiser_fund_deposit_history set status=1 where id='$id'");
	header("location:$url");
	exit(0);
	?>
	
