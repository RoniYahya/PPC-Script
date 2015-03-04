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
include("admin.header.inc.php");
$action=$_GET['action'];
 $id=$_GET['id'];
$time=time();
$extime=$mysql->echo_one("select date from messages where id=$id");
if($time<$extime)
{
if($action=="activate")
{

mysql_query("update messages set status='1' where id=$id"); 
}
if($action=="block")
{
mysql_query("update messages set status='0' where id=$id"); 
}
//if($action=="reject")
//{
//mysql_query("update messages set status='0' where id=$id"); 
//}
?>
<span class="inserted"><br>Message status has been  successfully updated! </span><a href="manage-message.php">Manage Messages</a><br><br>
<?php
}
else
{
mysql_query("update messages set status='0' where id=$id"); ?>
<span class="already"><br> You cannot update the status of this message. It has already expired! </span><a href="manage-message.php">Manage Messages</a><br><br>
<?php }
?>
<?php include("admin.footer.inc.php");?>