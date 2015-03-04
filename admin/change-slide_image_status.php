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
$lid=$_GET['id'];
phpsafe($lid);
//echo "select logo_name from ad_loo_details where id='$lid'";exit;
//$name=$mysql->echo_one("select logo_name from ad_logo_details where id='$lid'");
$action=$_GET['action'];
$name=$_GET['name'];
//$url=$_GET['url'];
//echo $action;

if($script_mode=="demo")
	{
		if($lid<5)
		{
	
		include("admin.header.inc.php");
		$str='<br><br><br><br><br><br><br><center><span class="already">These Operation not allowed demo!</span> <br> <br>
		<a href="javascript:history.back(-1);"><strong>Go Back</strong></a>         </center>
		';
		
		echo $str; 
		
		exit;
		}
	}

if($action=="block")
{
	//echo "update ad_logo_details set status=0 where id='$lid'";exit;
	mysql_query("update adserver_public_images set status=0 where id='$lid'");

}
elseif($action=="activate")
{

	//echo "update ad_logo_details set status=0 where id='$lid'";exit;
	mysql_query("update adserver_public_images set status=1 where id='$lid'");
	

}

else
{
	unlink("../ad_logos/$name");
	mysql_query("delete from adserver_public_images where id='$lid'");
	
}



header("Location:manage_slide_image.php");
	exit;
?>

