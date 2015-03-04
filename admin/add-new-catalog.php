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
?><?php include("admin.header.inc.php"); 
if(isset($_GET['pid']))
$pid=$_GET['pid'];
else
$pid=0;
//$sname=$mysql->echo_one("select domain from cpm_domain_tab where id='$sid'");
?>

<form name="form1" method="post" action="add-catalog-action.php">

  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
     <tr >
      <td height="22" colspan="2"  scope="row">&nbsp;</td>
    </tr>
    <tr >
      <td height="19" colspan="2"  scope="row" class="heading"> Add New Catalog </td>
    </tr>
    <tr >
      <td height="19" colspan="2"  scope="row">&nbsp;</td>
    </tr>
    <tr >
      <td height="30" colspan="2"  scope="row"><span class="inserted">Please fill up the following fields and Press Add Button </span></td>
    </tr>
    <tr>
      <td colspan="2"  scope="row"><span class="style2">All Fields marked <span class="style3">*</span> are mandatory </span></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td width="50%">&nbsp;</td>
    </tr>
    <tr>
      <td width="18%" align="left" scope="row">Height </td>
      <td><input name="height" type="text" id="height">
        <span class="style3">*</span></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left" scope="row">Width </td>
      <td><input name="width" type="text" id="width">
        <span class="style3">*</span></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left" scope="row">File size (kb) </td>
      <td><input name="size" type="text" id="size">
        <span class="style3">*</span></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="left" scope="row">Mode</td>
      <td><select name="wap"><option value="0">Desktop</option><option value="1">WAP</option></select></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<tr>
      <td scope="row">&nbsp;</td>
      <td><input type="submit" name="Submit" value="Add New Catalog !"></td>
    </tr>
	<tr>
	  <td scope="row">&nbsp;</td>
	  <td>&nbsp;</td>
    </tr>
	<tr>
	  <td colspan="2" scope="row">&nbsp;</td>
    </tr>
  </table>
</form>

<?php include("admin.footer.inc.php"); ?>