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
$id=$_GET['id'];
phpsafe($id);
	if($script_mode=="demo")
	{ 
		if($id==1 )
		{
		echo "<br><span class=\"already\">You cannot delete this in demo. <a href=\"javascript:history.back(-1);\">Go Back</a></span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
		}
	}
	$num=$mysql->echo_one("select count(*) from ppc_ad_block where credit_text_border_color=$id");
	$num2=$mysql->echo_one("select count(*) from wap_ad_block where credit_text_border_color=$id");
	$num1=$mysql->echo_one("select count(*) from ppc_custom_ad_block where credit_color=$id");
	if($num==0 && $num1==0 && $num2==0)
	{
	mysql_query("delete from `ppc_credittext_bordercolor` where id=$id;");
	?>
<br />
<span class="inserted"> <?php echo "Color combination has been successfully deleted. ";?> <a href="ppc-edit-credittext-bordercolor.php">Continue</a></span>
<br>
<?php } 
else
{
?><br />

<span class="already"><?php echo "Color combination cannot be deleted. Some ad units/ad blocks are using this. ";?> <a href="javascript:history.back(-1);">Go Back</a></span> 
<br>
<?php } include("admin.footer.inc.php");?>