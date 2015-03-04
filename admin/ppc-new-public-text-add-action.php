<?php 

/*--------------------------------------------------+
|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?>
<?php

$file="export_data";

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

include_once("admin.header.inc.php");?>

<?php

if($_POST['wap1']==0)
{
$title=trim($_POST['title']);
$url=trim($_POST['url']);
$summary=trim($_POST['summary']);
$displayurl=trim($_POST['disp']);
$adlang=trim($_POST['language']);
$name=trim($_POST['txt']);
}
else if($_POST['wap1']==1)
{
$title=trim($_POST['mtitle']);
$url=trim($_POST['murl']);
$summary=trim($_POST['msummary']);
$displayurl=trim($_POST['mdisp']);
$name=trim($_POST['txt1']);
$adlang=trim($_POST['language']);
}
phpSafe($adlang);
phpSafe($title);
phpSafeUrl($url);
phpSafe($summary);
phpSafe($displayurl);

/////////////////////wap

$wap_flag=trim($_POST['wap1']);
phpSafe($wap_flag);
//echo $wap_flag;die;
////////////////////wap


if($title==""||$url==""||$summary==""||$displayurl==""||$adlang==""||$name=="")
{
	?><span class="already"><br><?php echo "Please Go Back and check whether you filled all mandatory fields !";?>           <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
	<?php
	}else
	{

mysql_query("INSERT INTO `ppc_public_service_ads` VALUES ('0', '$url', '$title', '$summary', '".time()."','0','0','1','$displayurl','0','$wap_flag','$adlang','$name','','0');");
$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}

?> <span class="inserted"><br><?php echo "New public service text ad has been created successfully !"; ?></span><a href="ppc-view-public-ads.php">Manage Public Service Ads</a><br><br>
<?php
}
?>
<?php include("admin.footer.inc.php");?>


