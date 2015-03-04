<?php

//print_r($_POST);
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

if($script_mode=="demo")
{
?>

<span class="already">You cannot do this in demo.</span>
<?php
include("admin.footer.inc.php"); 
die;
}
?>
<link rel="stylesheet" type="text/css" href="epoch_styles.css" />



<script type="text/javascript" src="epoch_classes.js"></script>



<script type="text/javascript">



/*You can also place this code in a separate file and link to it like epoch_classes.js*/



var dp_cal;      







window.onload = function () {



		dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('exdate'));	



		



};











</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/messages.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Create Message</td>
  </tr>
</table>


  
  <form name="create-message" method="post" action="create-message-action.php">
  <table width="100%" border="0">
    <tr>
      <td width="16%">&nbsp;</td>
      <td width="69%">&nbsp;</td>
    </tr>
     <tr>
      <td>Message for</td>
      <td><select name="mess-for">
	  <option value="advertiser">Advertiser</option>
	  <option value="publisher">Publisher</option>
	  <option value="common">Common	  </option>
	  </select>	  </td>
    </tr>
    <tr> <td>&nbsp;</td>
<td>&nbsp;</td></tr>
     <tr> <td >Message</td>
  <td><textarea name="message" cols="50" rows="5"></textarea></td>
  </tr>
      <tr> <td>&nbsp;</td>
<td>&nbsp;</td></tr>
<tr>
<td>Expiry</td>
<td><input type="text" name="exdate"  value="" id="exdate" readonly="readonly"/></td>
</tr>
<tr>
<td >&nbsp;</td>
<td  align="left"><input type="submit" name="create" value="Create Message" /></td>
</tr>
</table>
</form>
    <?php
   include_once("admin.footer.inc.php");
?>