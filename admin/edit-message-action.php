<?php
//print_r($_POST);
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


$mid=$_GET['mid'];
phpSafe($mid);
$messfor=trim($_POST['mess-for']);
$message=trim($_POST['message']);
$exdate=trim($_POST['exdate']);
$expiry=explode("/",$exdate);
phpSafe($messfor);
$time=time();
 $extime=mktime(0,0,0,$expiry[0],$expiry[1]+1,$expiry[2]);
if($messfor==""||$message==""||$exdate=="")
{
?>
<span class="already"><br><?php echo "Please go back and check whether you filled all mandatory fields !";?>           <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
<?php 
}
elseif($time>$extime)
{ ?>
<span class="already"><br><?php echo "Please  check the expiry date !";?>           <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
<?php }
else
{

 	$terms=mysql_real_escape_string($terms);
if(!get_magic_quotes_gpc())
		{
			$message=mysql_real_escape_string(trim($message));
		}
		// htmlentities(stripslashes($message));
		
		mysql_query("update messages set message='$message',messagefor='$messfor',date='$extime',status='-1' where id='$mid'");
		?> <span class="inserted"><br><?php echo "Your message has been  successfully updated!"; ?></span> <a href="manage-message.php">Manage Messages</a><br><br> 
		<?php
}
?><?php include("admin.footer.inc.php");?>