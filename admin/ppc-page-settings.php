<?php 

/*--------------------------------------------------+
|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
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
function nega(x)
{
var s_len=x.value.length ;
var s_charcode = 0;
 for (var s_i=0;s_i<s_len;s_i++)
{
s_charcode = x.value.charCodeAt(s_i);
 if(!((s_charcode>=48 && s_charcode<=57)))
 {
         alert("Only Numeric Values Allowed");
           x.value='';
		   document.form1.ppc_page_width.value = "";  
          x.focus();
        return false;
       }
     }
	    return true;
}
	 
	function validate(s)
	{
	 var width=document.getElementById('ppc_page_width').value;
	 var widthtype=document.getElementById('size').options[document.getElementById('size').selectedIndex].value;
	// alert width;
	//  alert widthtype;
	 if(widthtype=='%')
	 {
			 if(width>100 || width <50)
			 {
			 alert("Width should be between 50% and 100%");
			 s.ppc_page_width.value= "";
			s.ppc_page_width.focus();
			   return false;
			 }
			 else
			  return true;
			 
	 }
	 else
	 {
		  if(width>1200 || width <700)
			 {
			 alert("Width should be between 700 px and 1200 px");
			 document.form1.ppc_page_width.value= "";
			 document.form1.ppc_page_width.focus();
			   return false;
			 }
			 else
			  return true;
	 
	 }
   

}
function check_value(s)
				{
				if(document.getElementById('ppc_page_width').value=="")
					{
					//refresh();select2
					alert("Please enter the page width ");
					document.form1.ppc_page_width.focus();
					return false;
					}
					return validate(s);
				
				}
</script>
<form name="form1" method="post" action="ppc-setting-action.php" enctype="multipart/form-data"  onSubmit="return check_value(this)">
<input type="hidden" name="redir_url" value="ppc-page-settings.php" />
<?php
$value1=$mysql->echo_one("select value from ppc_settings where name='ppc_engine_name'");
$value3=$mysql->echo_one("select value from ppc_settings where name='min_user_password_length'");
$value4=$mysql->echo_one("select value from ppc_settings where name='ad_display_char_encoding'");

?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr >
      <td height="65" colspan="3" scope="row" class="heading">Theme Settings </td>
    </tr>
    
    
    <tr align="left">
      <td colspan="3" class="inserted" scope="row">Theme settings are listed. You may change them below. </td>
    </tr>
     <tr align="left">
      <td colspan="3" class="inserted" scope="row">&nbsp;</td>
    </tr>
     <tr>
      <td height="27">Enter the page width </td>
	  <?php 
	  $key1=$mysql->echo_one("select value from ppc_settings where name='color_theme'");
	  $width=$mysql->echo_one("select value from ppc_settings where name='page_width'");
	  $type=substr($width,-1);
	  if($type!="%")
	  {
	  $type=substr($width,-2);
	  $wid=substr($width,0,-2);
	  }
	  else	  
	  $wid=substr($width,0,-1);
	  $width=$wid;	  	
	  ?>
      <td colspan="2"><input name="ppc_page_width" type="text" id="ppc_page_width" size="10" value="<?php echo $width;?>" onChange="return nega(this)">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>
        <select name="size" id="size">
		<option value="%" <?php if($type=="%") echo "selected";?>> % </option>
			<option value="px" <?php if($type=="px") echo "selected";?>> px </option>
		</select></strong> </td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="27">Select  the color theme </td>
      <td colspan="2"><label>
        <select name="color_theme">
      <?php
	  foreach ($color_code as $key=>$value)
 { ?>
 <option value="<?php echo $key; ?>" <?php 
			  				  if($key1==$key) echo "selected";			  
			  ?>><?php echo $key; ?></option>
 	
 <?php	
 }
 ?>
      </select>
      </label></td>
    </tr>
    <tr>
      <td height="27">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
  <tr>
      <td height="27" valign="top">Logo display options</td>
      <td colspan="3"><label>  
      <select name="logo_option" id="logo_option">
      <option value="io"  <?php if($logo_display_option=="io") echo "selected";?>>Logo image only</option>
      <option value="to" <?php if($logo_display_option=="to") echo "selected";?>>Logo text only</option>
      <option value="iat" <?php if($logo_display_option=="iat") echo "selected";?>>Logo image and text</option> 
      </select>
    </label><br />
<span class="note">( Logo text is the engine name itself and can be modifed in the message files.)</span></td>
    </tr>
    <tr><td width="440" height="18" colspan="1">
      </td>
	  <td class="note" colspan="3"></td>
    </tr>
    <tr>
      <td height="27" valign="top">Change Logo</td>
      <td width="546"  ><input name="logo" type="file" id="logo"  size="33" > &nbsp;
      <?php $path=$GLOBALS['logo_path'];?><br><span class="note">( Max size is 400 x 100 and 100 KB. Please use gif/jpg/png files) </span>      <br />
<img src="../<?php echo $GLOBALS['banners_folder']; ?>/logo/<?php echo $path; ?>"  align="absmiddle">&nbsp;</td>
    </tr>
	
<!--	////////////////////////////////////adserver 5.4-////////////////////////////////////////-->
	<tr>
      <td height="27" valign="top">Public Background Image</td>
      <td width="546"  ><input name="glogo" type="file" id="glogo"  size="33"  value=""> &nbsp;
      <?php $path=$GLOBALS['logo_path'];?><br>
<img src="../<?php echo $GLOBALS['banners_folder']; ?>/logo/<?php echo $public_background; ?>"  align="absmiddle" style="width: 120px; height: 65px;">&nbsp;</td>
    </tr>
    
    
    <td height="22">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="27">Public Background Image type</td>
      <td colspan="2"><label>
        <select name="bgimage_type">

 <option value="1" <?php if($bgimage_type==1) echo "selected"; ?>>fixed</option>
 <option value="2" <?php if($bgimage_type==2) echo "selected"; ?>>scroll</option>
      </select>
      </label></td>
    </tr>
    <tr>
      <td height="27">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    
    
	<tr>
      <td height="27" valign="top">Advertiser Image</td>
      <td width="546"  ><input name="alogo" type="file" id="alogo"  size="33"  value=""> &nbsp;
      <?php $path=$GLOBALS['logo_path'];?><br>
<img src="../<?php echo $GLOBALS['banners_folder']; ?>/logo/<?php echo $advertiser_image; ?>"  align="absmiddle" style="width: 120px; height: 65px;">&nbsp;</td>
    </tr>
	<tr>
      <td height="27" valign="top">Publisher Image</td>
      <td width="546"  ><input name="plogo" type="file" id="plogo"  size="33"  value=""> &nbsp;
      <?php $path=$GLOBALS['logo_path'];?><br>
<img src="../<?php echo $GLOBALS['banners_folder']; ?>/logo/<?php echo $publisher_image; ?>"  align="absmiddle" style="width: 120px; height: 65px;">&nbsp;</td>
    </tr>
	<!--	////////////////////////////////////adserver 5.4-////////////////////////////////////////-->
    <tr>
      <td height="27">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <th align="center" scope="row">&nbsp;</th>
      <th colspan="2" align="left" scope="row"><input type="submit" name="Submit" value="Update !"></th>
    </tr>
  </table>
</form>
<?php include("admin.footer.inc.php");?>