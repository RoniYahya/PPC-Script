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


?><?php include("admin.header.inc.php"); 

			$id=$_GET['id'];		
			phpsafe($id);	
			
$wapstatus=$mysql->echo_one("select wapstatus from catalog_dimension where id=$id");
$table="wap_ad_block";
if($wapstatus==0)
$table="ppc_ad_block";

  $adblock_usage=$mysql->total("$table","catalog_size='$id' and (ad_type=7 or ad_type=4)  ");

if($mysql->total("ppc_public_service_ads","bannersize='$id' and adtype=2 ")>=1 || $mysql->total("ppc_ads","bannersize='$id' and adtype=2 ") >=1 || $adblock_usage >=1)
{
?>
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
				<tr>
					  <td scope="row">&nbsp;</td>
				</tr>

			<tr>
			  <td align="left" scope="row"><span class="already">Selected catalog cannot be deleted. It is used in ad(s) or adblock(s)<br><br></span></td>
			</tr>
	
		  </table>


<?php
}
else
{
?>
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
				<tr>
					  <td scope="row">&nbsp;</td>
				</tr>
			<?php
					mysql_query("delete from catalog_dimension where id='$id'");				
			
			?>
			<tr>
			  <td align="left" scope="row"><span class="inserted">Selected catalog has been deleted successfully. <br><br></span></td>
			</tr>
	
		  </table>


<?php
}
?>

<?php include("admin.footer.inc.php"); ?>