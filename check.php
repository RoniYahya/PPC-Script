<?php
include_once("extended-config.inc.php");  
include_once($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");
$template1=new Template();
$user=new User("ppc_users");
$uname=$_GET['un'];
phpsafe($uname);
if($uname!="")
{
$main1=mysql_query("select * from nesote_inoutscripts_users where username='$uname'");
 $main=mysql_num_rows($main1);	

	$publisher1=mysql_query("select * from ppc_publishers where username='$uname'");
 $publisher=mysql_num_rows($publisher1);
	$adver1=mysql_query("select * from ppc_users where username='$uname'");
 $adver=mysql_num_rows($adver1);


	if(($adver=="0") &&($main=="0")&&($publisher=="0"))
	{
echo "<span style='color: green;'>".$template1->checkmsg(10014)."</span>";
	}
else
{

 echo "<span style='color: red;'>".$template1->checkmsg(10015)."</span>";
}
}
?>