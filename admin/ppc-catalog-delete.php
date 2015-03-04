<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/

?>
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
?><?php include("admin.header.inc.php"); 

$id=$_GET['id'];

  
?>

<style type="text/css">
<!--
.style1 {color: #0000FF}
.style2 {color: #666666}
.style3 {color: #FF0000}
-->
</style>

<?php
if($mysql->total("ppc_public_service_ads","bannersize='$id' and adtype=2 ")>=1 || $mysql->total("ppc_ads","bannersize='$id' and adtype=2 ") >=1 || $mysql->total("ppc_ad_block","catalog_size='$id'") >=1 || $mysql->total("wap_ad_block","catalog_size='$id'") >=1)
		  {
				$msg="You cannot delete this catalog ! It is already used in ads/ad blocks"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
				
				?>
				<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  
   <tr>
	   <td align="center" colspan="3">&nbsp;</td>
    </tr>
    <tr align="center">
      <th height="30" colspan="3" align="center" scope="row"><span class="already"><?php echo $msg; ?> </span></th>
    </tr>
    <tr>
      <th colspan="3" align="center" scope="row"></th>
    </tr>
    </table>
				<?php
		  }
		  else
		  {
		  
		  ?>



<form name="form1" method="post" action="delete-catalog-confirm.php">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
  
   <tr>
	   <td align="center" colspan="3">&nbsp;</td>
    </tr>
    <tr align="center">
      <th height="30" colspan="3" align="center" scope="row"><span class="inserted">Delete Confirmation </span></th>
    </tr>
    <tr>
      <th colspan="3" align="center" scope="row"></th>
    </tr>
    <tr>
      <td width="5%" scope="row">&nbsp;</td>
      <td colspan="2" align="left" scope="row">This will delete the selected catalog. Press confirm to continue deletion. <br>        
        <br>
      <input type="submit" name="Submit" value="Confirm deletion !"></td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <th width="48%" align="left" scope="row"><input type="hidden" name="id" value="<?php echo $_GET['id'] ?>"></th>
      <td width="47%">&nbsp;</td>
    </tr>
	<tr>
      <td colspan="2" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<tr>
	  <td colspan="2" scope="row">&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
  </table>
</form>

<?php
}
 include("admin.footer.inc.php"); ?>