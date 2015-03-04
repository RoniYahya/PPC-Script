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
//if($script_mode=="demo")
//	{
//		 include("admin.header.inc.php");
//		echo "<br>This feature is disabled in demo version<br><br>";
//		include("admin.footer.inc.php");
//		exit(0);
//	}
$cid=trim($_GET['id']);
//$uid=trim($_GET['uid']);
phpSafe($cid);

$usercount=$mysql->echo_one("select status from nesote_inoutscripts_users where id=$cid");
if($usercount=="")
{
	include("admin.header.inc.php");
		echo "<br>Invalid Id! <br><br>";
	include("admin.footer.inc.php");
		exit(0);
}
else
{
	
	$flag=0;
	mysql_query("BEGIN"); 
	if(!mysql_query("update ppc_users set parent_status='$usercount' where common_account_id='$cid'"))
	{
		$flag=1;
	}
if(!mysql_query("update ppc_publishers set parent_status='$usercount' where common_account_id='$cid'"))
	{
		$flag=1;
	}
	
	
	
	
	if($flag1==1)
{
		mysql_query("ROLLBACK");
	include("admin.header.inc.php");
		echo "<br>Invalid Id! <br><br>";
	include("admin.footer.inc.php");
		exit(0);
}
else
{
	mysql_query("COMMIT");
	header("Location: view-user-profile.php?id=$cid");
	exit(0);
}	
	
}

?>