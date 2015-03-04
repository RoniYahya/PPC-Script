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

$res=mysql_query("select * from adserver_languages where status='1' order by language asc");
$nos=mysql_num_rows($res);
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
				if(document.getElementById('filesize_'+stringid).value=="")
					{
					alert("English credit text should not be blank");
					document.getElementById('filesize_'+stringid).focus();
					return false;
					}
				else
					{
					 if (trim(document.getElementById('filesize_'+stringid).value).length == 0)
					 	{
							alert("English credit text should not be blank");
							document.getElementById('filesize_'+stringid).focus();
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
   <td   colspan="4" scope="row" class="heading">Add Credit Text</td>
  </tr>
  
  <tr >
      <td height="30" colspan="2"  scope="row"><span class="style1"> </span><span class="inserted">Please fill in the credit and press 'Add' button </span></td>
    </tr>
	
	 <tr >
      <td height="30" colspan="2"  scope="row"><span class="style1"> </span><span class="inserted">Fields marked <span class="style3">*</span> are compulsory</span></td>
    </tr>
	
  
  <tr >
      <td height="30" colspan="2"  scope="row"><input type="radio" name="ctext" id="crtext" onclick="hideimage()" checked="checked" value="1"><strong>Text Credit Text</strong></input>&nbsp;<input type="radio" name="ctext" id="crimage" onclick="hidetext()" value="2"><strong>Image Credit Text</strong></input></td>
    </tr>
  
  
</table>







<div id="textcredit" style="display:none">
<form name="form1" method="post" action="ppc-add-new-publisher-credit-action.php" onSubmit="return check_value()">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    
    <tr>
      <td scope="row">&nbsp;</td>
      <td width="54%">&nbsp;<input name="number" type="hidden" value="<?php echo $nos; ?>"></td>
    </tr>
<?php 

$strid="";
while($row=mysql_fetch_row($res))
			{
			 if($row[3] == "en")
			 $strid=$row[0];
			
				 ?>
    <tr>
      <td width="16%" align="left" scope="row"> Credit Text </td>
      <td><input name="credit_<?php echo $row[0]; ?>" type="text" id="filesize_<?php echo $row[0]; ?>">
        <?php if($row[3] == "en"){ ?><span class="style3">*</span><?php } ?> <?php echo $row[1];  ?></td>
    </tr>
    <tr>
      <td scope="row"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td><?php } ?>
      <td>
 
      <input type="hidden" name="strid" id="strid" value="<?php echo $strid; ?>" />
	  <input type="hidden" name="credittype" id="credittype" value="1" />
	  <input type="submit" name="Submit" value="Add New Credit !"></td>
    </tr>
  </table>
</form>
</div>
<div id="imagecredit" style="display:none">






<form name="form2" method="post" action="ppc-add-new-publisher-credit-action.php" enctype="multipart/form-data" onSubmit="return check_value_image()">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    
    <tr>
      <td scope="row">&nbsp;</td>
      <td width="54%">&nbsp;</td>
    </tr>
	
	<?php 
	mysql_data_seek($res,0);
	while($row=mysql_fetch_row($res))
	{
	
	 if($row[3] == "en")
     $strid=$row[0];
	?>
    <tr>
      <td width="16%" align="left" scope="row">Image Credit</td>
      <td><input type="file" name="image_credit_<?php echo $row[0]; ?>" id="image_credit_<?php echo $row[0]; ?>"  />
        <?php if($row[3] == "en"){ ?><span class="style3">*</span><?php } ?>&nbsp;<?php echo $row[1]; ?> </td>
    </tr>
	<tr>
      <td scope="row"></td>
      <td>&nbsp;</td>
    </tr>
	<?php
	}
	?>
    <tr>
      <td scope="row"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td>
	  <input name="number" type="hidden" value="<?php echo $nos; ?>">
	  <input type="hidden" name="strid" id="strid" value="<?php echo $strid; ?>" />
	  <input type="hidden" name="credittype" id="credittype" value="2" />
	  <input type="submit" name="Submit" value="Add New Credit !"></td>
    </tr>
  </table>
</form>



</div>

<script type="text/javascript" language="javascript">
function hidetext()
{
document.getElementById('textcredit').style.display='none';
document.getElementById('imagecredit').style.display="";
}

function hideimage()
{
document.getElementById('textcredit').style.display="";
document.getElementById('imagecredit').style.display='none';

}

hideimage();
</script>



<?php include("admin.footer.inc.php"); ?>