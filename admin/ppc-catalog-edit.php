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

if($script_mode=="demo")
	{ 
		echo "<br><span class=\"already\">You cannot do this in demo.</span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
	}

 ?>

<style type="text/css">
<!--
.style1 {color: #0000FF}
.style3 {color: #FF0000}
-->
</style>

  <?php
  $id=$_GET['id'];
phpsafe($id);
$wapstatus=$mysql->echo_one("select wapstatus from catalog_dimension where id=$id");
$table="wap_ad_block";
if($wapstatus==0)
$table="ppc_ad_block";

 $adblock_usage=$mysql->total("$table","catalog_size='$id' and (ad_type=7 or ad_type=4)  ");

if($mysql->total("ppc_public_service_ads","bannersize='$id' and adtype=2 ")>=1 || $mysql->total("ppc_ads","bannersize='$id' and adtype=2 ") >=1)// || $mysql->total("$table","catalog_size='$id'") >=1)
		  {
				$msg="You cannot edit this catalog dimension. It is already used in ads. "." <a href=\"javascript:history.back(-1);\">Go Back</a><br><br><br>";  
				
				?>
				<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  
			   <tr>
				   <td   colspan="3">&nbsp;</td>
				</tr>
				<tr  >
				  <td height="30" colspan="3"   scope="row"><span class="already"><?php echo $msg; ?> </span></td>
				</tr>
				<tr>
				  <th colspan="3"   scope="row"></th>
				</tr>
				</table>
				<?php
		  }
		 else
		  {
		  
		  ?>
		  
	<table width="100%"  border="0"  cellpadding="0" cellspacing="0">
    <tr>
      <td width="14%" colspan="4"  scope="row">&nbsp;</td>
    </tr>
	<tr><td width="14%" colspan="4"   scope="row" class="heading">Edit Catalog</td>
	</tr>
  </table>
  
<form name="form1" method="post" action="edit-catalog-action.php">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr >
      <th height="30" colspan="2" scope="row">&nbsp;</th>
    </tr>
    <tr >
      <td height="30" colspan="2" scope="row"> <span class="inserted">    Please edit the details and press Update button </span></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td width="48%">&nbsp;</td>
    </tr>

	<?php
	
	$result=mysql_query("select * from catalog_dimension where id='$id'");
	$row=mysql_fetch_row($result);
	?>
	  <tr>
      <td align="left" scope="row">Width </td>
      <td><input name="width" type="text" id="width" value="<?php echo
	  $row[2];?>">
        <span class="style3">*</span></td>
    </tr>
   
    <tr>
      <th align="left" scope="row">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
	 <tr>
      <td width="23%" align="left" scope="row">Height</td>
      <td><input name="height" type="text" id="height" value="<?php echo
	  $row[1];?>">
        <span class="style3">*</span></td>
    </tr>
  
    <tr>
      <th align="left" scope="row">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left" scope="row">File size</td>
      <td><input name="size" type="text" id="size" value="<?php echo
	  $row[3];?>">
        <span class="style3">*</span></td>
    </tr>
    <tr>
      <th align="left" scope="row">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>

    <tr>
       <td scope="row"></td>
      <td><input type="hidden" name="id" value="<?php echo $row[0];?>"><input type="hidden" name="wap" value="<?php echo $row[4];?>">
          <input type="submit" name="Submit" value="Update Changes !"></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
 <?php if($adblock_usage>0) { ?>
    <tr>
      <td colspan="2" scope="row" class="already" align="left">Please note that this dimension is used in <?php echo $adblock_usage; ?> adblocks(s); make sure that adblock preview is not clipped after you edit the dimensions. </td>
    </tr>
<?php } ?>
   <tr>
      <td colspan="2" scope="row">&nbsp;</td>
    </tr>
  </table>
</form>
<?php
}
 include("admin.footer.inc.php"); ?>