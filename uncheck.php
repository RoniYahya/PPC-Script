<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");
$user=new User("ppc_users");
$template1=new Template();
$uname=$_GET['un'];
phpsafe($uname);
$type=$_GET['type'];
if($uname!="")
{
	
if($type=='1')
{
$a=mysql_query("select * from ppc_users where username='$uname'");
}
elseif($type=='2')
{
	$a=mysql_query("select * from ppc_publishers where username='$uname'");
}
else
{
	$a=mysql_query("select * from nesote_inoutscripts_users where username='$uname'");
}
$no=mysql_numrows($a);
if($no!=0)
{
 echo "<span style='color: red;'>".$template1->checkmsg(10015)."</span>";
 }
 else
 {
 	echo "<span style='color: green;'>".$template1->checkmsg(10014)."</span>";
 }
}
else
{
echo "";
}


?>
