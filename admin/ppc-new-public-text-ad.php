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
if(document.getElementById('pc').checked)
{
				if((document.getElementById('txt').value=="")||(document.getElementById('title2').value=="")||(document.getElementById('summary2').value=="")||(document.getElementById('url2').value=="")||(document.getElementById('url22').value==""))
					{
					//refresh();
					alert("Please fill all compulsory fields");
					//document.form1.ad_title.focus();
					return false;
					}
}
					else
						{
								
							if(trim(document.getElementById('txt1').value).length==0)
							{
							alert("Please fill all compulsory fields");
							//document.form1.ad_title.focus();
							return false;
							}

							if(trim(document.getElementById('mtitle2').value).length==0)
							{
							alert("Please fill all compulsory fields");
							//document.form1.ad_title.focus();
							return false;
							}
							if(trim(document.getElementById('msummary2').value).length==0)
							{
							alert("Please fill all compulsory fields");
							//document.form1.ad_title.focus();
							return false;
							}
							if(trim(document.getElementById('murl2').value).length==0)
							{
							alert("Please fill all compulsory fields");
							//document.form1.ad_title.focus();
							return false;
							}
							if(trim(document.getElementById('murl22').value).length==0)
							{
							alert("Please fill all compulsory fields");
							//document.form1.ad_title.focus();
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
    <td height="50" colspan="4"  align="left"><?php include "submenus/service-ads.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Create Public Service Text Ad</td>
  </tr>
</table>


<form name="form1" method="post" action="ppc-new-public-text-add-action.php" onsubmit="return check_value()">
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
      <td   colspan="2"   class="inserted">Please configure your new public service text ad details below</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td   colspan="2"  ><span class="style5"></span><span class="style5"><strong>All fields marked <span class="style10"><strong>*</strong></span> are compulsory</strong></span></td>
    </tr>
    
    <tr>
      <td width="194">&nbsp;</td>
      <td width="1100">&nbsp;</td>
    </tr>
    <tr align="center">
      <td colspan="2" align="left"> 
      Select Type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      Desktop
      <input name="wap1" type="radio" id="pc" value="0"  onClick="javascript:loadpc()" checked="checked">
      &nbsp;&nbsp;&nbsp;&nbsp;Wap<input name="wap1" type="radio" id="mob" value="1" onClick="javascript:loadwap()"></td>
    </tr>
    <tr><td colspan="2" align="center">
    <div id="desktop">
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="15%">&nbsp;</td>
      <td width="85%">&nbsp;</td>
    </tr>
     <tr>
      <td>Name</td>
      <td><input type="text" name="txt" id="txt" size="50" value=""> <span class="style1 style10"><strong>*</strong></span></td>
    </tr>
    <tr> <td>&nbsp;</td>
<td>&nbsp;</td>
     <tr> <td >Target Language</td>

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
<tr> <td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

    <tr>
      <td>Ad Title </td>
      <td><input name="title" type="text" id="title2" maxlength="<?php echo $ad_title_maxlength;  ?>" size="30" >
          <span class="style1 style10 style2"> <span class="style5"><strong><span class="style10"><strong>*</strong></span></strong></span></span> [Max. <?php echo $ad_title_maxlength;  ?> Characters]</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Ad Summary </td>
      <td><input name="summary" type="text" id="summary2" size="50" maxlength="<?php echo $ad_description_maxlength; ?>">
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style10"><strong>*</strong></span></strong></span></strong></span> [Max. <?php echo $ad_description_maxlength; ?> Characters]</td>
    </tr>
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td>Target URL</td>
      <td><input name="url" type="text" id="url2" size="50" value="">
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style10"><strong>*</strong></span></strong></span></strong></span>(Eg.http://www.yoursite.com)</td>
    </tr>
   
    
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>Display URL</td>
      <td><input name="disp" type="text" id="url22" size="50" maxlength="<?php echo $ad_displayurl_maxlength;?>">
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style10"><strong>*</strong></span></strong></span></strong></span> [Max. <?php echo $ad_displayurl_maxlength;?> Characters]</td>
    </tr></table>
    </div>
    <div id="mobile" style="display:none ;"><table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="15%">&nbsp;</td>
      <td width="85%">&nbsp;</td>
    </tr>
    <tr>
      <td>Name</td>
      <td><input type="text" name="txt1" id="txt1" size="50" value=""> <span class="style1 style10"><strong>*</strong></span></td>
    </tr>
    <tr> <td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
    <tr><td>Target Language</td>

<td><select name="language1" id="language1" >

<?php 
$cid=mysql_query("select id from adserver_languages where code='$client_language' and status='1'");
$cid1=mysql_fetch_row($cid);

$res=mysql_query("select id,language,code from adserver_languages  where status='1'");

while($row=mysql_fetch_row($res))
	{ ?>
		
		<option value="<?php echo $row[0]; ?>" <?php if($cid1[0]==$row[0]) { ?> selected="selected" <?php } ?> ><?php echo  $row[1]; ?></option>
		
	<?php }
	?>
	<option value="0">Any Languages</option>
</select><span class="style1 style10"><strong>*</strong></span><br></td>
</tr>
<tr> <td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
    <tr>
      <td>Ad Title </td>
      <td><input name="mtitle" type="text" id="mtitle2" maxlength="<?php echo $GLOBALS['wap_title_length'];?>" size="30" >
          <span class="style1 style10 style2"> <span class="style5"><strong><span class="style10"><strong>*</strong></span></strong></span></span> [Max. <?php echo $GLOBALS['wap_title_length'];?> Characters]</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Ad Summary </td>
      <td><input name="msummary" type="text" id="msummary2" size="50" maxlength="<?php echo $GLOBALS['wap_desc_length'];?>">
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style10"><strong>*</strong></span></strong></span></strong></span> [Max. <?php echo $GLOBALS['wap_desc_length'];?> Characters]</td>
    </tr>
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td>Target URL</td>
      <td><input name="murl" type="text" id="murl2" size="50" value="">
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style10"><strong>*</strong></span></strong></span></strong></span>(Eg.http://www.yoursite.com)</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>Display URL</td>
      <td><input name="mdisp" type="text" id="murl22" size="50" maxlength="<?php echo $GLOBALS['wap_url_length'];?>" >
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style10"><strong>*</strong></span></strong></span></strong></span> [Max. <?php echo $GLOBALS['wap_url_length'];?> Characters]</td>
    </tr></table>
    </div>
    <tr>
      <td colspan="2">&nbsp;<input type="hidden" name="wap" id="wap" value="<?php echo $wap_flag;?>"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Create New Public Text Ad ! "></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<script type="text/javascript" language="javascript">

loadpc();

function loadpc()
{
document.getElementById('desktop').style.display="";
document.getElementById('mobile').style.display="none";


}
function loadwap()
{
document.getElementById('desktop').style.display="none";
document.getElementById('mobile').style.display="";

}

</script>
<?php include("admin.footer.inc.php");?>