<?php 

/*--------------------------------------------------+
|													 |
| Copyright ? 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?><?php

$file="export_data";

include("config.inc.php");
include("../extended-config.inc.php");  
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

include_once("admin.header.inc.php");?>

<style type="text/css">
<!--
.style7 {color: #000000; font-size: 20px; }
-->
</style>
<script type="text/javascript">

	 
	
function check_value()
				{
				if(document.getElementById('name').value=="")
					{
					//refresh();select2
					alert("Please enter logo name ");
					//document.formal.logoname.focus();
					return false;
					}
					
					
					if(document.getElementById('ad_logos').value=="")
					{
					//refresh();select2
					alert(" Please select an image");
					//document.formal.logoname.focus();
					return false;
					}
					
					var img=document.getElementById('logo').value;
	if(img!="")
	{
		var extn=img.lastIndexOf(".");
		var length1=img.length;
		var extension=img.substring(extn,length1);
		
		if(extension!=".jpeg" && extension!=".gif" && extension!=".jpg" && extension!=".png" && extension!=".JPEG" && extension!=".GIF" && extension!=".JPG" && extension!=".PNG" )
			{
				alert("image not supported");
				return false;
			}
	}
	
				
									
				}
</script>
<form name="form1" method="post" action="create_logos_action.php" enctype="multipart/form-data" >


  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/logo.php"; ?> </td>
  </tr>
  
    <tr >
      <td height="65" colspan="3" scope="row" class="heading">Create New Logos</td>
    </tr>
    
    
    <tr align="left">
       <td   colspan="2"  ><span class="style5"></span><span class="style5"><strong>All fields marked <span class="style10"><strong><font color="red">*</font></strong></span> are compulsory</strong></span></td>
    </tr>
     <tr align="left">
      <td colspan="3" class="inserted" scope="row">&nbsp;</td>
    </tr>
     <tr>
      
	  <td colspan="2">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>
        </strong> </td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="27">AdLogo Name </td>
      <td colspan="2"><label>
        <input type="text" name="name" id="name"> <span class="style1 style10"><strong><font color="red">*</font></strong></span></td>
      </label></td>
    </tr>
    <tr>
      <td height="27">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr><td width="440" height="18" colspan="1">
      </td>
	  <td class="note" colspan="3"></td>
    </tr>
    <tr>
      <td height="27" valign="top">Upload AdLogo</td>
      <td width="546"  ><input name="ad_logos" type="file" id="ad_logos"  size="33" > &nbsp; <span class="style1 style10"><strong><font color="red">*</font></strong></span>
      <?php $path=$GLOBALS['logo_path'];?><br><span class="note">( Max size is 300 x 59 . Please use gif/jpg/png files) </span>      <br />
&nbsp;</td>
    </tr>
    <tr>
      <td height="27">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <th align="center" scope="row">&nbsp;</th>
      <th colspan="2" align="left" scope="row"><input type="submit" name="Submit" value="Create Logo !" onclick="return check_value()"></th>
    </tr>
  </table>
</form>
<?php include("admin.footer.inc.php");?>