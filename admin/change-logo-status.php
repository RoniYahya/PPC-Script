<?php
include("config.inc.php");

$lid=$_GET['id'];
phpsafe($lid);
//echo "select logo_name from ad_loo_details where id='$lid'";exit;
//$name=$mysql->echo_one("select logo_name from ad_logo_details where id='$lid'");
$action=$_GET['action'];
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
	mysql_query("update ad_logos_details set status=0 where id='$lid'");
	

}
elseif($action=="activate")
{


	//echo "update ad_logo_details set status=0 where id='$lid'";exit;
	mysql_query("update ad_logos_details set status=1 where id='$lid'");
	

}
elseif($action=="approve")
{

	
	//echo "update ad_logo_details set status=0 where id='$lid'";exit;
	mysql_query("update ad_logos_details set status=1 where id='$lid'");
	

}
else
{


	//echo "update ad_logo_details set status=0 where id='$lid'";exit;
	unlink("../ad_logos/");
	mysql_query("delete ad_logos_details where id='$lid'");
	

}



header("Location:manage_logos.php");
	exit(0);
?>

