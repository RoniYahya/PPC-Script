<?php 

/*--------------------------------------------------+
|													 |
| Copyright ? 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?>
<?php

$file="export_data";

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

include_once("admin.header.inc.php");




?>
<style type="text/css">
<!--
.style9 {font-size: 18px}
.style10 {color: #FF0000}
-->
</style>
<script language="javascript">
function check_value()
				{
				
		
					if(document.getElementById('txt1').value=="")
					{
					//refresh();select2
					alert("Please fill all compulsory fields");
					//document.form1.ad_title.focus();
					return false;
					}
				if(document.getElementById('select2').value=="")
					{
					//refresh();select2
					alert("Please fill all compulsory fields");
					//document.form1.ad_title.focus();
					return false;
					}
			if (document.getElementById('banner2').value=="")
				{
				alert("Please fill all compulsory fields");
				return false;
				}				
		}
function trim(stringValue){
	return stringValue.replace(/(^\s*|\s*$)/, "");
}

</script>
<link href="style.css" rel="stylesheet" type="text/css">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="50" colspan="4"  align="left"><?php include "submenus/ad-template.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Create Templates For Text Ads</td>
  </tr>
</table>


<form action="ppc-new-ad-template-action.php" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check_value()">
  <table width="100%"  border="0"   cellpadding="0" cellspacing="0">
  
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr >
      <td colspan="2"><span class="inserted">Please configure your new text ad template details below</span></td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><span class="style5"><strong>All fields marked <span class="style1 style10">*</span> are compulsory</strong></span></td>
    </tr>
    <tr>
      <td width="23%">&nbsp;</td>
      <td width="74%">&nbsp;</td>
    </tr>
    </table>
    <div id="desktop"> 
    <table width="100%"  border="0"   cellpadding="0" cellspacing="0">
    
    
      <tr>
      <td width="23%">&nbsp;</td>
      <td width="74%">&nbsp;</td>
    </tr>
    <tr>
      <td>Name</td>
      <td><input type="text" name="txt1" id="txt1" size="50" value=""> <span class="style1 style10"><strong>*</strong></span></td>
    </tr>
    <tr>
      <td width="23%">&nbsp;</td>
      <td width="74%">&nbsp;</td>
    </tr>

    <tr>
      <td>Select Adblock </td>
      <td><select name="size" id="select2">
        <option selected value="">- select -</option>
       
        
        <?php $result=mysql_query("select id,width,height,ad_block_name,status from ppc_ad_block where ad_type=3 or ad_type=1 order by id ");
		
$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}

	while($row=mysql_fetch_row($result))
	{
		
		echo "<option value=\"$row[0]\"> ( $row[1] x $row[2] ) $row[3] </option>";
	}
//$selectstring.="</select>";
  
?>
      </select>        <span class="style1 style10"><strong>*</strong></span></td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Select Banner </td>
      <td><input name="banner" type="file" id="banner2" size="50"><span class="style1"><span class="style1 style10"><strong>*</strong></span></span></td>
    </tr>
	<tr><td></td><td>(GIF/JPG/PNG/GIF images)</td></tr>
  
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
      <input type="submit" name="Submit" value="Create New Text Ad Template ! "></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  </div>
     
</form>
<script type="text/javascript" language="javascript">



</script>
<?php include("admin.footer.inc.php");?>