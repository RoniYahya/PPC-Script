<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







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

//$result=mysql_query("select  * from ppc_users a  order by a.uid DESC  ");
//if(mysql_num_rows($result)==0)
//	{
//     echo "<br>No results found <br>";
//
//	}


include("admin.header.inc.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/advertisers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Export Advertisers</td>
  </tr>
</table>

<br>
<form action="export-adv-action.php" method="post">
<table border="0"  align="center" width="60%">
<tr><td colspan="3" headers="50px" align="center"><p><b>Export Advertiser Details</b></p></td></tr>
<tr><td  >Status
         &nbsp;&nbsp;<select name="type">
                              <option value="3">All</option>
                              <option value="1">Active</option>
                              <option value="0">Blocked</option>
                              <option value="-2">Pending and email not confirmed</option>
                              <option value="-1">Pending and email  confirmed</option>
                              
                     </select>
    </td>
    <td><input type="submit" value="Export"></td></tr>

</table>
</form><br>
<? include("admin.footer.inc.php"); ?>