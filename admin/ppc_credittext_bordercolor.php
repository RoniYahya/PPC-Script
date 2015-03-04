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

include_once("admin.header.inc.php");
$credit_text_color=$_POST['credit_text_color'];
phpsafe($credit_text_color);
if($credit_text_color=="")
	{
	$credit_text_color="#FFFFFF";
	}
$border_text_color=$_POST['border_text_color'];
phpsafe($border_text_color);
if($border_text_color=="")
	{
	$border_text_color="#000000";
	}
if($mysql->echo_one("select id from ppc_credittext_bordercolor where credit_text_color='$credit_text_color' and border_color='$border_text_color'"))
{
?>
<br />
<span class="already"><br><?php echo " The specified combination is already present. ";?>
 <a href="javascript:history.back(-1)">Go Back</a></span> 


<?php
}
else
{	
mysql_query("INSERT INTO `ppc_credittext_bordercolor` ( `credit_text_color`, `border_color`) VALUES ( '$credit_text_color', '$border_text_color');");
?>
<br />
<span class="inserted"><br><?php echo " New color combination  has been successfully added. ";?>
 <a href="ppc-edit-credittext-bordercolor.php">Continue</a></span> 
</span> 
<?php 
}
?>
<br>
<?php include("admin.footer.inc.php");?>