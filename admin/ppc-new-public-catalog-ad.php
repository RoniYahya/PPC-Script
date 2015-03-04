<?php 

/*--------------------------------------------------+
|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
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



include_once("admin.header.inc.php");?>
<style type="text/css">
<!--
.style9 {font-size: 18px}
.style10 {color: #FF0000}
-->
</style>
<script language="javascript">
function check_value()
				{
if(document.getElementById('pc').checked)
{				if((document.getElementById('text1').value=="")||(document.getElementById('url').value=="")||(document.getElementById('select2').value=="") ||(document.getElementById('summary').value=="") ||(document.getElementById('displayurl').value=="")||(document.getElementById('banner').value=="") )
					{
					//refresh();select2
					alert("Please fill all compulsory fields");
					//document.form1.ad_title.focus();
					return false;
					}
}
				else
					{
					if (trim(document.getElementById('text2').value).length == 0 || trim(document.getElementById('murl').value).length == 0 || trim(document.getElementById('msummary').value).length == 0 || trim(document.getElementById('mdisplayurl').value).length == 0 || document.getElementById('mbanner').value=="" )
						{
						alert("Please fill all compulsory fields");
						return false;
						}	
					}
				}
function trim(stringValue){
	return stringValue.replace(/(^\s*|\s*$)/, "");
}

</script>
<link href="style.css" rel="stylesheet" type="text/css">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="50" colspan="4"  align="left"><?php include "submenus/service-ads.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Create Public Service Catalog Ad</td>
  </tr>
</table>
<form action="ppc-new-public-catalog-ad-action.php" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check_value()">
  <table width="100%"  border="0"  cellpadding="0" cellspacing="0">
   
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr >
      <td colspan="2"><span class="inserted">Please configure your new public service catalog ad details below</span></td>
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
    <tr>
      <td width="23%">Select Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      </td>
      <td width="74%">Desktop
      <input name="wap1" type="radio" id="pc" value="0"  onClick="javascript:loadpc()" checked="checked">
      &nbsp;&nbsp;&nbsp;&nbsp;Wap<input name="wap1" type="radio" id="mob" value="1" onClick="javascript:loadwap()"></td>
    </tr>
    </table>
    <div id="desktop">
    <table width="100%"  border="0"  cellpadding="0" cellspacing="0">
    <tr>
      <td width="23%">&nbsp;</td>
      <td width="74%">&nbsp;</td>
    </tr>
    <tr>
      <td>Name</td>
      <td><input type="text" name="text1"  id="text1" size="50" value=""> <span class="style1 style10"><strong>*</strong></span></td>
    </tr>
	    <tr >
      <td colspan="2">&nbsp;</td>
    </tr>

      <tr>
        <td >Target Language</td>

<td><select name="language" id="language" >

<?php 
$cid=mysql_query("select id from adserver_languages where code='$client_language' and status='1'");
$cid1=mysql_fetch_row($cid);

$res=mysql_query("select id,language,code from adserver_languages  where status='1'");

while($row=mysql_fetch_row($res))
	{ ?>
		
		<option value="<?php echo $row[0]; ?>" <?php if($cid1[0]==$row[0]) { ?> selected="selected" <?php } ?> ><?php echo  $row[1]; ?></option>
		
	<?php }
	?><option value="0">Any Languages</option>
</select><span class="style1 style10"><strong>*</strong></span><br></td>
</tr>
<tr>
  <td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
    <tr>
      <td>Select Catalog Size </td>
      <td><select name="size" id="select2">
        <option selected value="">- select -</option>
        <?php $result=mysql_query("select id,width,height,filesize from catalog_dimension where wapstatus=0 order by id ");
		
$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}
//$selectstring="<select name=\"bannersize\" id=\"bannersize\">";
//$selectstring.="<option value=\"\" selected> - Select - </option>";
	while($row=mysql_fetch_row($result))
	{
		echo "<option value=\"$row[0]\"> $row[1] x $row[2] Max size:$row[3] KB</option>";
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
      <td>Title</td>
      <td><input name="displayurl" type="text" id="displayurl" size="50" maxlength="<?php echo $ad_displayurl_maxlength; ?>">        
<span class="style1 style10"><strong>*</strong></span>[Max. <?php echo $ad_displayurl_maxlength;?> Characters]</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Select Banner </td>
      <td><input name="banner" type="file" id="banner" size="50">        <span class="style1"><span class="style1 style10"><strong>*</strong></span></span><br />
(GIF/JPG/PNG/SWF images)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Description</td>
      <td><input name="summary" type="text" id="summary" size="50" maxlength="<?php echo $ad_description_maxlength; ?>">        
<span class="style1 style10"><strong>*</strong></span> [Max. <?php echo $ad_description_maxlength;?> Characters]</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Target URL </td>
      <td>
<input name="url" type="text" id="url" size="50">        
<span class="style1 style10"><strong>*</strong></span><br />
(Eg.http://www.yoursite.com)</td>
    </tr>
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    
    
    <tr>
      <td>&nbsp;</td>
      <td>
      <input type="submit" name="Submit" value="Create New Public Banner Ad ! "></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
    </div>
        <div id="mobile">
    <table width="100%"  border="0"  cellpadding="0" cellspacing="0">
    <tr>
      <td width="23%">&nbsp;</td>
      <td width="74%">&nbsp;</td>
    </tr>
    <tr>
      <td>Name</td>
      <td><input type="text" name="text2" id="text2"  size="50" value=""> <span class="style1 style10"><strong>*</strong></span></td>
    </tr>
        <tr >
      <td colspan="2">&nbsp;</td>
    </tr>

    
     <tr> <td >Target Language</td>

<td><select name="language1" id="language1" >

<?php 
$cid=mysql_query("select id from adserver_languages where code='$client_language' and status='1'");
$cid1=mysql_fetch_row($cid);

$res=mysql_query("select id,language,code from adserver_languages  where status='1'");

while($row=mysql_fetch_row($res))
	{ ?>
		
		<option value="<?php echo $row[0]; ?>" <?php if($cid1[0]==$row[0]) { ?> selected="selected" <?php } ?> ><?php echo  $row[1]; ?></option>
		
	<?php } ?>
</select><span class="style1 style10"><strong>*</strong></span><br></td>
</tr>
    
      <tr >
      <td colspan="2">&nbsp;</td>
    </tr>
  
    <tr>
      <td>Select Catalog Size </td>
      <td><select name="msize" id="mselect2">
        <option selected value="">- select -</option>
        <?php $result=mysql_query("select id,width,height,filesize from catalog_dimension where wapstatus=1 order by id ");
		
$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}
//$selectstring="<select name=\"bannersize\" id=\"bannersize\">";
//$selectstring.="<option value=\"\" selected> - Select - </option>";
	while($row=mysql_fetch_row($result))
	{
		echo "<option value=\"$row[0]\"> $row[1] x $row[2] Max size:$row[3] KB</option>";
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
      <td>Title</td>
      <td><input name="mdisplayurl" type="text" id="mdisplayurl" size="50" maxlength="<?php echo $GLOBALS['wap_url_length']; ?>">        
<span class="style1 style10"><strong>*</strong></span>[Max. <?php echo $GLOBALS['wap_url_length'];?> Characters]</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Select Banner </td>
      <td><input name="mbanner" type="file" id="mbanner" size="50">        <span class="style1"><span class="style1 style10"><strong>*</strong></span></span><br />
(GIF/JPG/PNG images)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Description</td>
      <td><input name="msummary" type="text" id="msummary" size="50" maxlength="<?php echo $GLOBALS['wap_desc_length']; ?>">        
<span class="style1 style10"><strong>*</strong></span> [Max. <?php echo $GLOBALS['wap_desc_length'];?> Characters]</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Target URL </td>
      <td>
<input name="murl" type="text" id="murl" size="50">        
<span class="style1 style10"><strong>*</strong></span><br />
(Eg.http://www.yoursite.com)</td>
    </tr>
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    
     
    <tr>
      <td>&nbsp;</td>
      <td>
      <input type="submit" name="Submit" value="Create New Public Banner Ad ! "></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
    </div>
    
    
</form>
<script type="text/javascript" language="javascript">

loadpc();

function loadpc()
{
document.getElementById('desktop').style.display="";
document.getElementById('mobile').style.display="none";
document.getElementById ('pc').checked=checked;

}
function loadwap()
{
document.getElementById('desktop').style.display="none";
document.getElementById('mobile').style.display="";
}

</script>
<?php include("admin.footer.inc.php");?>