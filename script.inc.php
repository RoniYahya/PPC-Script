<?php

include("extended-config.inc.php");  

$script_version="6.0";

$patch_applied="";

$script_mode="live"; 









//echo strcasecmp ($_SERVER['REQUEST_URI'],'_cache');
//MYSQL Class
$aa=explode("/",$_SERVER['SCRIPT_FILENAME']);
$aa_count=count($aa);


 //$GLOBALS['maintenance_mode']['enabled'];


$enabled_array=array();

$enabled_array=array('show-ads.php','publisher-show-ads.php','load-inline-ads.php','wap-ads.php',"xml-ads.php","pub-ad-click.php","ad-click.php","xml-click.php","ppc-ps-ad-click.php","ppc-ad-click.php","xml-ad-click.php","ppc-publisher-ad-click.php");

if($maintenance_mode['enabled']==1)
{
	
	
	if (!in_array($aa[$aa_count-1],$enabled_array))
//if($aa[$aa_count-1]!="show-ads.php" && $aa[$aa_count-1]!="publisher-show-ads.php" && $aa[$aa_count-1]!="load-inline-ads.php" && $aa[$aa_count-1]!="wap-ads.php" && $aa[$aa_count-1]!="xml-ads.php" && $aa[$aa_count-1]!="pub-ad-click.php" && $aa[$aa_count-1]!="ad-click.php" && $aa[$aa_count-1]!="xml-click.php" &&  $aa[$aa_count-1]!="ppc-ps-ad-click.php" )
{
if($aa[$aa_count-1]!="show-inline-ads.php")
{
	

$url=$maintenance_mode['landing_url'];

	$current_ip=$_SERVER['REMOTE_ADDR'];
	$allowed_ip=$maintenance_mode['allowed_ips'];
	$allow=explode(",",$allowed_ip);
	foreach($allow as $key=>$value) $allow[$key]=trim($value);
	if(!in_array($current_ip,$allow))
	{
	header("location: $url");
	die;
	}
}
else
{
exit;
}
}
}

include($GLOBALS['admin_folder']."/mysql.cls.php");



$mysql=new mysql($mysql_server,$mysql_username,$mysql_password,$mysql_dbname);



include($GLOBALS['admin_folder']."/paging.cls.php"); 



$paging=new paging();


include_once("functions.inc.php"); 
	
//echo setlocale (LC_ALL, '0');

?>