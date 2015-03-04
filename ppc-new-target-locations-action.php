<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
includeClass("User");
//includeClass("Form");
//includeClass("Template");

$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}


$pass=$_POST['pass'];


phpSafe($pass);
$uid=$user->getUserID();

$id=$_POST['id'];


$wap_flag=$_POST['wap'];
phpSafe($wap_flag);


if(!myAd($id,$user->getUserID(),$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}
$loc_orig="";
$loc="";
if($_POST['ctr_orig']!="")
{
$loc_orig=substr($_POST['ctr_orig'],0,-1);
}
if(trim($_POST['ctr'])!="")
{
$loc=substr(trim($_POST['ctr']),0,-1);
phpSafe($loc);

insertLocation($id,$loc,$loc_orig,$mysql);
}
else
{
		mysql_query("delete from ad_location_mapping where adid='$id'");

		if($mysql->total("ad_location_mapping"," adid='$id'  AND country='00' and region='00' and city='00'")==0)
		{
		
				mysql_query("insert into ad_location_mapping (adid,country,region,city) values('$id','00','00','00');");
		}


}


function insertLocation($id,$loc,$loc_orig,$mysql)
{
	$countries_orig=explode(",",$loc_orig);
	$countries=explode(",",$loc);
	$countries_orig=array_diff($countries_orig,$countries);
	$countries_orig="'".implode("','",$countries_orig)."'";
//	echo $countries_orig;exit(0);
	mysql_query("delete from ad_location_mapping where adid='$id' and country in ( $countries_orig )");
	//echo mysql_error();
	//exit(0);
	foreach($countries as $value)
			{
	        
					if($mysql->total("ad_location_mapping"," adid='$id'  AND country='$value' and region='' and city=''")==0)
					{
							mysql_query("insert into ad_location_mapping (adid,country,region,city) values('$id','$value','','');");
					}
	        }
			
			
			$count_row=$mysql->echo_one("select count(id) from ad_location_mapping where adid='$id' and country<>'00'");
			if($count_row >0)
			{
			mysql_query("delete from ad_location_mapping where adid='$id' and country='00'");
			
			}
			
			
}

if($pass==true)
{
	$turl=urlencode("ppc-manage-keywords.php?id=$id&wap=$wap_flag");
	header("Location:show-success.php?id=5001&page=$turl");
	exit(0);
}	

header("Location:ppc-manage-target-locations.php?id=$id&wap=$wap_flag");
exit(0);
?>