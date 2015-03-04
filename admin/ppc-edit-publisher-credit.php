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

<style type="text/css">
<!--
.style1 {color: #0000FF}
.style2 {color: #666666}
.style3 {color: #FF0000}
-->
</style>
<script language="javascript">
function check_value()
				{
				var stringid=document.getElementById('strid').value;
						
				if(document.getElementById('credit_'+stringid).value=="")
					{
				
					alert("English credit text should not be blank");
					document.getElementById('credit_'+stringid).focus();
					return false;
					}
				else
					{
					 if (trim(document.getElementById('credit_'+stringid).value).length == 0)
					 	{
							alert("English credit text should not be blank");
							document.getElementById('credit_'+stringid).focus();
							return false;
						}
					}
				
				}
				
				
function check_value_image()
				{
				
				var stringid=document.getElementById('strid').value;
				if(document.getElementById('image_credit_'+stringid).value=="")
					{
					alert("Image credit text for english language should not be blank");
					document.getElementById('image_credit_'+stringid).focus();
					return false;
					}
				else
					{
					 if (trim(document.getElementById('image_credit_'+stringid).value).length == 0)
					 	{
							alert("Image credit text for english language should not be blank");
							document.getElementById('image_credit_'+stringid).focus();
							return false;
						}
					}
					
				
				}				
								
				
				
				
function trim(stringValue){
				return stringValue.replace(/(^\s*|\s*$)/, "");
		}

</script>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/credits.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Edit Credit Text</td>
  </tr>
</table>
<br />
<?php
$id=$_GET['id'];
phpsafe($id);

$type=$mysql->echo_one("select credittype from ppc_publisher_credits where id='$id'");

if($type == 0)
{
?>

<form name="form1" method="post" action="ppc-edit-publisher-credit-action.php" onSubmit="return check_value()">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<?php
	$lang=mysql_query("select * from adserver_languages where status='1' order by language asc");
  
	$result=mysql_query("select * from ppc_publisher_credits where id='$id' or parent_id=$id");
	$num=mysql_num_rows($result);
	if($num!=0)
	{?>
	    <tr  >
      <td height="30" colspan="2" scope="row"> <span class="inserted">Please make the changes and press Update button </span></td>
    </tr>
	<?php
	while($row=mysql_fetch_row($lang))
	{
	
	 if($row[3] == "en")
	 $strid=$row[0];
	 
	 		
		
	if($row[3] =='en')
	$data_res=mysql_query("select * from ppc_publisher_credits where id='$id'");
	else
	$data_res=mysql_query("select * from ppc_publisher_credits where parent_id='$id' and language_id='$row[0]'");
	
	$row1=mysql_fetch_row($data_res);
		
		
		
		
		
	?>
    <tr>
    <td width="21%" align="left" scope="row"> Credit Text </td>
    <td><input name="credit_<?php echo $row[0]; ?>" type="text" id="credit_<?php echo $row[0]; ?>" value="<?php echo $row1[1];?>">
    <?php if($row[3] == "en"){?> <span class="style3">*</span><?php } ?> <?php echo $row[1]; ?></td>
    </tr>
    <tr>
    <td align="left" scope="row">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    <?php }?>
    <tr>  
	<td scope="row"><input type="hidden" name="strid" id="strid" value="<?php echo $strid;?>"></td>
      <td>
	  <input type="hidden" name="credittype" id="credittype" value="1">
	  <input type="hidden" name="parent" value="<?php echo $id;?>">
      <input type="submit" name="Submit" value="Update Changes !" /></td>
    </tr> <?php
	 } else
		  { ?>
    <tr>
      <td colspan="3"><?php echo "No Record Found<br><br>";}?></td>
    </tr> 
  </table>
</form>

<?php
}
else if($type ==1)
{
?>

<form name="form2" method="post" action="ppc-edit-publisher-credit-action.php"  enctype="multipart/form-data" onSubmit="return check_value_image()">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<?php
	
    $lang=mysql_query("select * from adserver_languages where status='1' order by language asc");
  
  
	$result=mysql_query("select * from ppc_publisher_credits where id='$id'");
	$num=mysql_num_rows($result);
	
	if($num!=0)
	{?>
	    <tr  >
      <td height="30" colspan="2" scope="row"> <span class="inserted">Please make the changes and press Update button </span></td>
    </tr>
	<?php

		
		
		
	while($lang_row=mysql_fetch_row($lang))
	{
	
	 if($lang_row[3] == "en")
	 $strid=$lang_row[0];
	
	
	if($lang_row[3] =='en')
	$data_res=mysql_query("select * from ppc_publisher_credits where id='$id'");
	else
	$data_res=mysql_query("select * from ppc_publisher_credits where parent_id='$id' and language_id='$lang_row[0]'");
	
	$row=mysql_fetch_row($data_res);
	
	
	
	?>
    <tr>
      <td width="14%" align="left" scope="row">Image Credit</td>
      <td width="86%"><input type="file" name="image_credit_<?php  echo $lang_row[0];  ?>" id="image_credit_<?php  echo $lang_row[0];  ?>"  style="width:200px" />&nbsp;Current Image&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  
	  <?php if($row[1]!="")
	  {
	  ?>
	  <img src="../credit-image/<?php echo $id; ?>/<?php  echo $row[1];  ?>" width="200px" height="15px" />&nbsp;&nbsp;<?php  echo $lang_row[1];  ?>
	  <?php
	  }
	  else
	  {
	  echo "No Credit Image"." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$lang_row[1];
	  }
	  ?>
	  
	  </td>
	  
    </tr>
	
	<tr>
      <td align="left" scope="row" height="10px"></td>
      <td></td>
    </tr>
    <!--<tr>
      <td align="left" scope="row">Current Credit Image</td>
      <td><img src="../credit-image/<?php echo $id; ?>/<?php  echo $row[1];  ?>" width="200px" height="15px" /></td>
    </tr>-->
	
	
	<?php
	}
	?>
   <tr>
      <td align="left" scope="row" height="10px"></td>
      <td></td>
    </tr>
    <tr>  
	<td scope="row"><input type="hidden" name="credittype" id="credittype" value="2"></td>
      <td>
	  <input type="hidden" name="strid" id="strid" value="<?php echo $strid;?>">
	  <input type="hidden" name="id" id="id" value="<?php echo $id;?>">
      <input type="submit" name="Submit" value="Update Changes !" /></td>
    </tr> <?php
	 } else
		  { ?>
    <tr>
      <td colspan="3"><?php echo "No Record Found<br><br>";}?></td>
    </tr> 
  </table>
</form>





<?php
}
?>
<?php include("admin.footer.inc.php"); ?>