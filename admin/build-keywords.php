<?php
include_once("config.inc.php");
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
$result=mysql_query("select keyword,time,status from ppc_keywords group by keyword");
//echo "select keyword,time from ppc_keywords group by keyword";

while($fes=mysql_fetch_row($result))
{
	$des=$mysql->echo_one("select id from system_keywords where keyword='$fes[0]'");	
	if($des=='')
	{
		mysql_query("insert into system_keywords (`id`, `keyword`, `createtime`,`status`) values('0','$fes[0]','$fes[1]','$fes[2]')");	
		$lastid=mysql_insert_id();
		mysql_query("update ppc_keywords set sid='$lastid' where keyword='$fes[0]'");
	}
}
$default_count=$mysql->echo_one("select id from system_keywords where keyword='".$GLOBALS['keywords_default']."'");
$default_key=$GLOBALS['keywords_default'];
if($default_count=='')
{
	mysql_query("insert into system_keywords(`id` ,`keyword` ,`createtime` ,`rating` ,`status` ) values ('0','$default_key','".time()."','0','1')");
}
?>