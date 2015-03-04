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
?>
<?php //include("admin.header.inc.php"); ?>
<?php

$lid=$_POST['lid'];
/*$action=$_POST['action'];
$lid=$_GET['id'];*/
phpsafe($uid);
//echo "select logo_name from ad_loo_details where id='$lid'";exit;
$name=$mysql->echo_one("select logo_name from ad_logo_details where id='$lid'");
echo $action=$_GET['action'];exit;


$flag=0;
mysql_query("BEGIN");
if($action=="block")
{
	//echo "update ppc_users set status=0 where uid=$uid;";
	$result=mysql_query("update ad_logo_details set status=0 where id='$lid'");
	 echo "Status of AdLogo changed.";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>

}
if($action=="activate")
{
	$result=mysql_query("update ad_logo_details set status=1 where id='$lid';"))
	
}

?>