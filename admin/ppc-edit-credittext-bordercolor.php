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
?>

  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/colors.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage credit text/border color combinations</td>
  </tr>
</table>
<p><strong>Note:</strong><span class="info">Only these combinations will be available for ceating ad blocks as well as ad units.</span> </p>

 
	
	  <?php
		  $result1=mysql_query("select * from ppc_credittext_bordercolor order by id DESC");
			$total1=$mysql->echo_one("select count(*) from ppc_credittext_bordercolor");
			if($total1>0)
				{
			
		  ?>
	
      <table width="100%"  align="left" class="datatable" cellpadding="0" cellspacing="0">
        <tr class="headrow">
          <td width="198"><strong>No</strong></td>
          <td width="344"><strong>Credit text color </strong></td>
          <td width="263"><strong>Border color </strong></td>
          <td width="486">&nbsp;</td>
        </tr>
		<?php 
		$i=1;
		while($row=mysql_fetch_row($result1))
			{
			?>
        <tr <?php if($i%2==1)  { ?>class="specialrow" <?php }?>>
          <td height="20"><?php echo $i; ?>
            <div align="center"></div></td>
          <td><label>
		
            <input name="credit_text_color" type="text" id="credit_text_color" size="10" maxlength="10" style="background-color:<?php echo $row[1]; ?>">
          </label></td>
          <td><input name="border_color" type="text" id="border_color" size="10" maxlength="10" style="background-color:<?php echo $row[2]; ?>"></td>
          <td><div align="center"><a href="ppc-change-credittext-bordercolor.php?id=<?php echo $row[0]; ?>">Edit</a> | <a href="ppc-delete-credittext-bordercolor.php?id=<?php echo $row[0]; ?>">Delete</a> </div></td>
        </tr>
		
		<?php
		$i=$i+1;
		}
		?>
      </table>
	  <?php }
	  else
	  	{
	  	?>
		<span ><br><?php echo " No Record found! ";?></span><p></p>
<br>
<?php } ?>
      
	  
<?php include("admin.footer.inc.php"); ?>
