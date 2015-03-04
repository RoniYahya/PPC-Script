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
?><?php
$msg="";
$height=trim($_POST['height']);
$width=trim($_POST['width']);
$size=trim($_POST['size']);
$wap=trim($_POST['wap']);
//echo print_r($_POST);
if($height!="" && $width!="" && $size!="" && $wap!="")
{

	
		  if($mysql->total("catalog_dimension"," height='$height' and width='$width'  and wapstatus=$wap ")>0)
		  {
				$msg="Catalog already exists !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
		  }
		  else
		  {
				mysql_query("insert into catalog_dimension (height,width,filesize,wapstatus) values ('$height','$width','$size',$wap)");
				//echo "insert into catalog_dimension (height,width,size) values ('$height','$width','$size')";
			   //$msg="New category has been added successfully!";
			   header("Location:ppc-manage-catalog.php");
			   exit(0);
			  
				
		 }
		 echo mysql_error();	
}
else
{
	$msg="Please fill the necessary fields !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";
}
include("admin.header.inc.php");
?>
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
      <td align="center" scope="row">&nbsp;</td>
    </tr>
  </table>
  <span class="already"><?php echo $msg;?> </span> 
<?php include("admin.footer.inc.php"); ?>