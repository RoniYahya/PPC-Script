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
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
	{
	header("Location:index.php");
	}


?><?php include("admin.header.inc.php"); ?>
<?php 
	if($script_mode=="demo" && !isset($_GET['force']))
	{
		?>
		<span class="already"><br>
		You cannot delete referral banners in demo !<br>
		<br></span>
		<?php 
		include("admin.footer.inc.php");
		exit(0);
	}

	$id=intval($_GET['id']);
	$filename=$mysql->echo_one("select filename from affiliate_banners where id='$id'");
	$level=$mysql->echo_one("select level from affiliate_banners where id='$id'");
	mysql_query("delete from affiliate_banners where id='$id'");
	mysql_query("update affiliate_banners set level=level-1 where level>'$level'");
 	unlink("affiliate-banners/".$id."/$filename");
	rmdir("affiliate-banners/".$id);
	?>
	
<span class="inserted"><br>
Selected banner has been deleted successfully !</span>
<a href="affiliate-banners.php">Manage Referral Banners</a><br>
<br>

<?php include("admin.footer.inc.php"); ?>