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

$file="export_data";

include("config.inc.php");
if(!isset($_COOKIE['inout_admin']))
{
header("Location:index.php");
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
	{
	header("Location:index.php");
	}

include_once("admin.header.inc.php");
$id=intval($_GET['id']);

?>
<style type="text/css">
<!--
.style9 {font-size: 18px}
.style10 {color: #FF0000}
-->
</style>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    
    <tr>
      <td     colspan="4" scope="row" height="50" ><?php include ("submenus/referral.php"); ?></td>
    </tr>
    <tr>
      <td     colspan="4" scope="row"class="heading" >      Edit   Referral Banner </td>
    </tr>
  </table>
<form action="edit-affiliate-banner-action.php" method="post" enctype="multipart/form-data" name="form1">
  <table width="100%"  border="0"  cellpadding="0" cellspacing="0">
    
	
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr >
      <td colspan="2"><span class="inserted">Please upload the new referral banner below</span></td>
    </tr>
    
    <tr>
      <td width="22%">&nbsp;</td>
      <td width="62%">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Select Banner </td>
      <td><input name="banner" type="file" id="banner2" size="50">        <span class="style1"><span class="style1 style10"><strong>*</strong></span></span><input name="id" type="hidden" value="<?php echo $id;?>"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Update Referral Banner ! "></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>

<?php include("admin.footer.inc.php");?>