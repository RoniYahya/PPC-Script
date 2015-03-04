<?php 

/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?><?php

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
	if( $script_mode=="demo")
	{
		echo "<br>This link is disabled in demo version<br><br>";
		include("admin.footer.inc.php");
		exit(0);
	}?>



<?php 
$value1=$mysql->echo_one("select value from ppc_settings where name='affiliate_id'");
?>
<form name="form1" method="post" action="ppc-setting-action.php">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr >
      <td height="60" colspan="2" scope="row" class="heading">Inoutscripts Affiliate Id </td>
    </tr>
    
   <tr align="left">
	<td height="25" colspan="2" align="center" scope="row"><div align="left">
        <p class="inserted">Please configure your inoutscripts affiliate id which will be appended to the footer url of adserver pages, to track your referrals. If  your referrals buy any of our products, you will earn commision.  </p>
        </div></td>
    </tr>
    <tr>
      <td width="26%" height="24">&nbsp;</td>
      <td width="74%">&nbsp;</td>
    </tr>
    <tr>
      <td height="24" >Inoutscripts Affiliate Id&nbsp;&nbsp;:</td>
      <td><input name="affiliate_id" type="text" id="affiliate_id" size="10" value="<?php if($value1!=0)echo $value1; ?>">  &nbsp; <input type="submit" name="Submit" value="Update !"></td>
    </tr>
    <tr align="center" valign="middle">
      <td scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
    </tr>
    <tr align="center" valign="middle">
      <td scope="row">&nbsp;</td>
      <td align="left" scope="row"></td>
    </tr>
    <tr align="center" valign="middle">
      <td scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
    </tr>
    <tr align="center" valign="middle">
      <td colspan="2" align="left" class="info" scope="row"><strong>Note</strong> :Please read our <a href="http://www.inoutscripts.com/affiliate/">affiliate terms and conditions</a></td>
    </tr>
  </table>
</form>
<?php include("admin.footer.inc.php");?>