<?php 
/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

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
?>
<?php include("admin.header.inc.php"); ?>





<style type="text/css">



.style1 {color: #FF0000}



</style>
<script language="javascript">

function isPositive(strString)
   {

   var strValidChars = "0123456789.";
   var strChar;
   var retResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above

   for (i = 0; i < strString.length && retResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         retResult = false;
         }
      }
   return retResult;
   }
   
   
   
function check_value()
				{
				if(document.getElementById('servername').value=="")
					{
					alert("Please fill server name");
					document.getElementById('servername').focus();
					return false;
					}
					else if(document.getElementById('serverurl').value=="")
					{
					alert("Please fill access url");
					document.getElementById('serverurl').focus();
					return false;
					}
					else if(isPositive(document.getElementById('minrange').value)==false) 
					{	
                    alert("Please enter positive range start value.");
					document.getElementById('minrange').focus();
					return false;
   	                }
					else if(document.getElementById('maxrange').value==0)
					{
					alert("Please enter  range end value value greater than 0");
					document.getElementById('maxrange').focus();
					return false;
					}
	                else if(isPositive(document.getElementById('maxrange').value)==false) 
					{	
                     alert("Please enter positive range end value.");
					document.getElementById('maxrange').focus();
					return false;
   	                }
										
					else if(document.getElementById('minrange').value > 0 )
					{
					if(parseInt(document.getElementById('maxrange').value) <= parseInt(document.getElementById('minrange').value))
					{
					alert("Range End Value must be greater than range start value ");
					document.getElementById('minrange').focus();
					return false;
					}
					
					
					}
					
					
					
					
				}

</script>
<?php
	
	
	$url=urldecode($_GET['url']);
	$url=urlencode($url);
	
	
	$id=$_GET['id'];
	$result=mysql_query("select * from server_configurations where id='$id'");
	$row=mysql_fetch_row($result);
	?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/loadbalance.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Edit Server</td>
  </tr>
</table>
<br />
<form action="edit-server-action.php" method="post" enctype="multipart/form-data" onsubmit="return check_value()">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">



<tr>
  
    <td width="153" height="36">Server Type </td>
    <td width="5">:</td>
    <td width="330">
	<?php if($row[5] ==1){ ?><strong>Application Server</strong><input type="hidden" name="srvtype"  id="srvtype" value="1" /><?php }
	else {?>
	 
	<label>
      <select id="srvtype" name="srvtype">
	   <option value="2" <?php if($row[5] ==2){ echo "selected";} ?>>Load Balance Server</option>
	   <!--<option value="3" <?php //if($row[5] ==3){ echo "selected";} ?>>Statistics Server</option>-->
	   </select>
	   </label><strong><span class="style1">*</span></strong>
	   <?php
	   }
	   ?>
    </td>
  </tr>
  
  
  
 <tr>
  
    <td width="153" height="36">Server Name </td>
    <td width="5">:</td>
    <td width="330"><label>
       <input name="servername" type="text" id="servername" value="<?php echo $row[1];?>" size="30">
    </label><strong><span class="style1">*</span></strong></td>
  </tr>
  
  <tr>
  
    <td height="36">Access Url </td>
    <td>:</td>
    <td><label>
       <input name="serverurl" type="text" id="serverurl" value="<?php echo $row[2];?>" size="30">
    </label><strong><span class="style1">*</span></strong></td>
  </tr>
  
  
   <tr>
  
    <td height="36">Range Start Value </td>
    <td>:</td>
    <td><label>
       <input name="minrange" type="text" id="minrange" size="10" value="<?php echo $row[3];?>">
    </label><strong><span class="style1">*</span></strong><span class="note">(Starting value of the publisher id range for this server)</span></td>
  </tr>
  
  
   <tr>
  
    <td height="36">Range End Value </td>
    <td>:</td>
    <td><label>
       <input name="maxrange" type="text" id="maxrange" size="10" value="<?php echo $row[4];?>">
    </label>
    <strong><span class="style1">*</span></strong><span class="note">(Range End Value must be greater than Range Start Value.)</span></td>
  </tr>
  
 
  
  
  
  <tr>
    <td height="36"> </td>
    <td> </td>
    <td  ><label>

	<input type="hidden" name="url" value="<?php echo $url;?>">
	<input type="hidden" name="id" value="<?php echo $row[0];?>">
    <input type="submit" name="Submit" value="Submit">
    </label></td>
    </tr>
</table>
</form>



<?php include("admin.footer.inc.php"); ?>		