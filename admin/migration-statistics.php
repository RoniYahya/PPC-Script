<?php

include("../extended-config.inc.php");  
include("config.inc.php");

if(!isset($_COOKIE['inout_admin']))
{
	exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	exit(0);
}



$key=md5($username.$password);
$getkey=$_GET['key'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript">
function show()
{
parent.window.location.href="migration-statistics.php";
}
</script>
</head>

<body style="background-color:#CCCCCC;padding:2px 2px;">



 
<div style="font-weight:bold;padding:3px 0px;" class="inserted">Migration info (<a  class="inserted" href="javascript:history.back(-1)">Back</a>)</div>
<table      border="0"  cellpadding="0" cellspacing="0" width="100%"   class="datatable" style="background-color:#FFFFFF">
<?php
//if( $key==$getkey )
{
?>
<tr>
 <td width="56%"  >Account mode</td>
 <td width="1%">:</td>
 <td width="43%"><?php 

if($single_account_mode==1)
{
echo "SSO migration ";

}
else
{
echo "Dual-sign-on";

}  

   ?></td>
  </tr>

<tr class="specialrow">
 <td width="56%"  >Single-sign-on accounts</td>
 <td width="1%">:</td>
 <td width="43%"><?php 
if($single_account_mode==1)
echo numberFormat($mysql->echo_one("select count(*) from nesote_inoutscripts_users where 1"),0);
else
echo "N/A";
   ?></td>
  </tr>
<tr>
 <td width="56%"  >Advertisers pending migration</td>
 <td width="1%">:</td>
 <td width="43%"><?php 

echo numberFormat($mysql->echo_one("select count(*) from ppc_users where common_account_id=0"),0);

   ?></td>
  </tr>
<tr class="specialrow">
 <td width="56%"  >Publishers pending migration</td>
 <td width="1%">:</td>
 <td width="43%"><?php 

echo numberFormat($mysql->echo_one("select count(*) from ppc_publishers where common_account_id=0"),0);
   ?></td>
  </tr>
 <?php } ?> 
</table>


 


</body>
</html>