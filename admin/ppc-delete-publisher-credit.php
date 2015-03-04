<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/

?><?php
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


?><?php include("admin.header.inc.php"); ?>
  
  <?php 
	$id=$_REQUEST['id'];
	phpsafe($id);
	
	
$type=$mysql->echo_one("select credittype from ppc_publisher_credits where id='$id'");	
	
if($type == 0)
{	
	
	$num=$mysql->echo_one("select count(*) from ppc_ad_block where credit_text=$id");
	$num1=$mysql->echo_one("select count(*) from ppc_custom_ad_block where credit_text=$id");
	$num2=$mysql->echo_one("select count(*) from wap_ad_block where credit_text=$id");
	//echo $id;
	$result=mysql_query("select * from ppc_publisher_credits  where parent_id='$id'");
	$adb=0;
	$wadb=0;
	$cadb=0;
while($row1=mysql_fetch_row($result))
{
	$ad=mysql_query("select * from ppc_ad_block where credit_text=$row1[0]");
	$adn=mysql_num_rows($ad);
	if($adn!=0)
	{
		$adb++;
	}
	$wad=mysql_query("select * from wap_ad_block where credit_text=$row1[0]");
	$wadn=mysql_num_rows($ad);
	if($awdn!=0)
	{
		$wadb++;
	}
$ad1=mysql_query("select * from ppc_custom_ad_block where credit_text=$row1[0]");
	$adn1=mysql_num_rows($ad);
	if($adn1!=0)
	{
		$cadb++;
	}
}
	if($num==0 && $num1==0 && $num2==0 && $adb==0 && $wadb==0 && $cadb==0)
		{
	mysql_query("delete from ppc_publisher_credits where id='$id'");
	mysql_query("delete from ppc_publisher_credits where parent_id='$id'");
	
$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}

	?><br />

	<span class="inserted">Selected credit text was deleted successfully. <a href="ppc-manage-publisher-credit.php">Continue</a><br><br></span>
	<?php } 
else
	{ ?>
	
<span class="already"><br><?php echo " Credit text cannot be deleted. Some ad blocks/ad units are still using this credit text. ";?>
 <a href="javascript:history.back(-1);">Go Back</a></span>
<br>
<?php } ?>



<?php
}
else if($type == 1)
{

$num=$mysql->echo_one("select count(*) from ppc_ad_block where credit_text=$id");
$num1=$mysql->echo_one("select count(*) from ppc_custom_ad_block where credit_text=$id");
$num2=$mysql->echo_one("select count(*) from wap_ad_block where credit_text=$id");



$result=mysql_query("select * from ppc_publisher_credits  where parent_id='$id'");
	$adb=0;
	$wadb=0;
	$cadb=0;
while($row1=mysql_fetch_row($result))
{
	$ad=mysql_query("select * from ppc_ad_block where credit_text=$row1[0]");
	$adn=mysql_num_rows($ad);
	if($adn!=0)
	{
		$adb++;
	}
	$wad=mysql_query("select * from wap_ad_block where credit_text=$row1[0]");
	$wadn=mysql_num_rows($ad);
	if($awdn!=0)
	{
		$wadb++;
	}
$ad1=mysql_query("select * from ppc_custom_ad_block where credit_text=$row1[0]");
	$adn1=mysql_num_rows($ad);
	if($adn1!=0)
	{
		$cadb++;
	}
}







if($num==0 && $num1==0 && $num2==0 && $adb==0 && $wadb==0 && $cadb==0)
{
//$credit_name=$mysql->echo_one("select credit from ppc_publisher_credits where id='$id'");
mysql_query("delete from ppc_publisher_credits where id='$id'");
mysql_query("delete from ppc_publisher_credits where parent_id='$id'");
//rmdir("../credit-image/".$id); 


$mydir = "../credit-image/".$id."/"; 
		$d = dir($mydir); 
		if($d)
		{
		
		while($entry = $d->read())
		 { 
		if ($entry!= "." && $entry!= "..")
		 { 
		 unlink("../credit-image/".$id."/".$entry); 
		 } 
		} 
		$d->close(); 
		rmdir($mydir); 

        }









	
$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}

	?><br />

	<span class="inserted">Selected credit text was deleted successfully. <a href="ppc-manage-publisher-credit.php">Continue</a><br><br></span>
	<?php include("admin.footer.inc.php");
	exit;
} 
else
{ ?>
	
<span class="already"><br><?php echo " Credit text cannot be deleted. Some ad blocks/ad units are still using this credit text. ";?>
 <a href="javascript:history.back(-1);">Go Back</a></span>
<br>
<?php
include("admin.footer.inc.php");
exit;


} 



}


?>
<?php include("admin.footer.inc.php"); ?>