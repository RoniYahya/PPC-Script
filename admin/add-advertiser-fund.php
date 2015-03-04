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

$url=urldecode($_GET['url']);
$url=urlencode($url);
?><?php include("admin.header.inc.php"); ?>

<style type="text/css">
<!--
.style1 {color: #0000FF}
.style3 {color: #FF0000}
-->
</style>
<script language="javascript">
function check_value()
				{
				if(document.getElementById('fund').value=="")
					{
						//refresh();
						alert("Please enter the amount ");
						//document.form1.ppc_engine_name.focus();
						return false;
					}
				if(document.getElementById('fund').value<=0)
					{
						//refresh();
						alert("Please enter a positive value ");
						//document.form1.ppc_engine_name.focus();
						return false;
					}
				if(document.getElementById('mode').value=="select")
					{
						
						//refresh();
						alert("Please enter fund mode   ");
						//document.form1.ppc_engine_name.focus();
						return false;
					}
				}
	</script>			
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/advertisers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Add Fund</td>
  </tr>
</table>
	
<form name="form1" method="post" action="add-advertiser-fund-action.php" onSubmit="return check_value()">
<input type="hidden" name="url" value="<?php echo $url; ?>">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr  >
      <td height="30" colspan="2" scope="row"><span class="style1"> </span><span class="inserted">Please fill in the amount and press 'Add' button </span></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td width="46%">&nbsp;</td>
    </tr>

    <tr>
      <td align="left" scope="row">Username</td>
      <td><?php echo $mysql->echo_one("select username from ppc_users where uid='$_GET[id]'"); ?></td>
    </tr>
    <tr>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="24%" align="left" scope="row">Amount to be added </td>
      <td><input name="fund" type="text" id="fund" maxlength="6">
        <span class="style3">*</span></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row" align="left">Fund mode </td>
      <td><select name="mode" id="mode">
		<option value="select" selected="selected"> select</option>
			<option value="bonus"> bonus balance</option>
			<option value="account"> account balance</option>
		</select>&nbsp;&nbsp;&nbsp;<span class="style3">*</span></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">Comments (if any) </td>
      <td><input name="comment" type="text" id="comment"></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td><input name="aid" type="hidden" value="<?php echo $_GET['id']; ?>"></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td><input type="submit" name="Submit" value="Add Fund !"></td>
    </tr>
  </table>
</form>

<?php include("admin.footer.inc.php"); ?>