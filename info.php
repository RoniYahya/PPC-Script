<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

//sleep(1);

$key=md5($username.$password);
$getkey=$_GET['key'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="ppc-style.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript">
function show()
{
parent.window.location.href="migration-statistics.php";
}
</script>
</head>
<style>
	td { color:#333}
</style>
<body bgcolor="#CCCCCC" style="padding:2px 2px;">



 
<div style="font-weight:bold;padding:5px 0px; background; background-color: #CCCCCC;">User account info (<a href="info.php?key=<?php echo $getkey;?>">Reload</a>)</div>
<table      border="0"  cellpadding="0" cellspacing="0" width="100%"   class="datatable" style="background-color:#FFFFFF">
<?php
if( $key==$getkey )
{
?>
<tr>
 <td width="51%"  >Account mode</td>
 <td width="1%">:</td>
 <td width="48%"><?php 

if($single_account_mode==1)
{
echo "Single-sign-on";
if($account_migration==1) echo " (<a href='".$GLOBALS['admin_folder']."/migration-statistics.php'>Migration</a>)";//echo " (<a href='javascript:show()'>Migration</a>)";

}
else
{
echo "Dual-sign-on";

}  

   ?></td>
  </tr>

<tr class="specialrow">
 <td width="51%"  >Single-sign-on accounts</td>
 <td width="1%">:</td>
 <td width="48%"><?php 
if($single_account_mode==1)
echo numberFormat($mysql->echo_one("select count(*) from nesote_inoutscripts_users where 1"),0);
else
echo "N/A";
   ?></td>
  </tr>
<tr>
 <td width="51%"  >Active advertiser accounts</td>
 <td width="1%">:</td>
 <td width="48%"><?php 

echo numberFormat($mysql->echo_one("select count(*) from ppc_users where status=1"),0);

   ?></td>
  </tr>
<tr class="specialrow">
 <td width="51%"  >Active publisher accounts</td>
 <td width="1%">:</td>
 <td width="48%"><?php 

echo numberFormat($mysql->echo_one("select count(*) from ppc_publishers where status=1"),0);
   ?></td>
  </tr>
 <?php } ?> 
</table>


 


</body>
</html>
