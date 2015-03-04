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
include("../extended-config.inc.php");  
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
	if($script_mode=="demo" )
	{
	if(!isset($_GET['force']))
	{
		?>
		<span class="already"><br>You cannot delete public service ads in demo !<br><br></span>
		<?php 
		include("admin.footer.inc.php");
		exit(0);
	}	
	}
	$url=urldecode($_GET['url']);
	$id=$_GET['id'];
phpsafe($id);
	mysql_query("delete from ppc_public_service_ads where id='$id'");
			$mydir = $GLOBALS['service_banners_folder']."/$id/"; 
					if($d)
		{

		$d = dir($mydir); 
		while($entry = $d->read()) { 
		 if ($entry!= "." && $entry!= "..") { 
		 
		 unlink($GLOBALS['service_banners_folder']."/$id/".$entry); 
		 } 
		} 
		$d->close(); 
		rmdir($mydir); 
		}
?>
<span class="inserted"><br>Selected ad has been deleted successfully !</span><br /><br />
<a href="<?php echo $url; ?>">Click here to go back to the page you were viewing</a><br><br>

<?php include("admin.footer.inc.php"); ?>