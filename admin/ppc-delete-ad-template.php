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
	$url=urldecode($_GET['url']);
	$id=$_GET['id'];
phpsafe($id);
	mysql_query("delete from ppc_ad_templates where id='$id'");
			$mydir = "ad-templates/$id/"; 
					if($d)
		{

		$d = dir($mydir); 
		while($entry = $d->read()) { 
		 if ($entry!= "." && $entry!= "..") { 
		 
		 unlink("ad-templates/$id/".$entry); 
		 } 
		} 
		$d->close(); 
		rmdir($mydir); 
		}
?>
<span class="inserted"><br>Selected ad has been deleted successfully !</span><br /><br />
<a href="<?php echo $url; ?>">Click here to go back to the page you were viewing</a><br><br>

<?php include("admin.footer.inc.php"); ?>