<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/


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
$msg="";
$id=$_POST['id'];
$height=trim($_POST['height']);
$width=trim($_POST['width']);
$size=trim($_POST['size']);
$wap_flag=trim($_POST['wap']);


//echo print_r($_POST);

if($height!="" && $width!="" && $size!="")
{

	
		  if($mysql->total("catalog_dimension","id<>'$id' and height='$height' and width='$width' and wapstatus='$wap_flag' ")>0)
		  {
		   include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="Specified catalog dimension already exists !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 
			
		  }
		  else
		  {
		
				mysql_query("update catalog_dimension set height='$height', width=$width, filesize=$size where id='$id'");
		   include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="Catalog dimension updated !"." <a href=\"ppc-manage-catalog.php\">Manage Catalog Dimensions</a>";?>
					 <span class="inserted"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 
			
			 
		 }
}
else
{
	 include("admin.header.inc.php");
	  echo "<br><br>";
	$msg="Please fill the necessary fields !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
	 <span class="already"><?php echo $msg;?> </span> 
	 <?php 
	 echo "<br><br>";
	 include("admin.footer.inc.php"); ?>
	 <?php
}
?>
