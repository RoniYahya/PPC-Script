<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");

$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$id=getSafePositiveInteger('id','g');
if(!myAd($id,$user->getUserID(),$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}
$uid=$user->getUserID();
//echo "delete from ppc_ads where uid='$uid' AND id='$id'";
mysql_query("delete from ppc_ads where uid='$uid' AND id='$id'");
mysql_query("delete from ppc_keywords where uid='$uid' AND aid='$id'");
mysql_query("delete from `ad_location_mapping` where adid='$id'");
			$mydir = $GLOBALS['banners_folder']."/$id/"; 
		$d = dir($mydir); 
		if($d)
		{
		
		while($entry = $d->read()) { 
		 if ($entry!= "." && $entry!= "..") { 
		 
		 unlink($GLOBALS['banners_folder']."/$id/".$entry); 
		 } 
		} 
		$d->close(); 
		rmdir($mydir); 
}
//header("Location:show-success.php?id=5007");
if(isset($_GET['url']))
	{
	$url=urldecode($_GET['url']);
	header("Location:$url");
	}
else
	{
	header("Location:ppc-find-ad.php");
	}
exit(0);
?>