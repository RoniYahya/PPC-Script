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

$url=urldecode($_GET['url']);
$id=$_GET['id'];
phpsafe($id);

if($script_mode=="demo" && $id==27)
{
include_once("admin.header.inc.php");
echo "<br><span class=\"already\" >You cannot delete this ad unit in demo.</span><br><br>";
include_once("admin.footer.inc.php");
exit(0);
}
//echo $id;
//exit(0);
	mysql_query("delete from `ppc_custom_ad_block` where id=$id;");
	header("Location: $url");
	
	?>