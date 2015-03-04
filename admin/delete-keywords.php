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
$kid=trim($_GET['id']);
phpsafe($kid);
$key=$mysql->echo_one("select keyword from system_keywords where id='$kid'");
if($key!=$keywords_default)
{
	
mysql_query("delete from ppc_keywords where sid=$kid");
mysql_query("delete from system_keywords where id=$kid");
}
else
{
	include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="You cannot delete default keyword !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 
}
header("Location: ".urldecode($_GET['url']));
exit(0);
?>