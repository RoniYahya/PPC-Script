<?php 

/*--------------------------------------------------+

|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
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
include("admin.header.inc.php"); 
//$url=urlencode($_SERVER['REQUEST_URI']);



$status=$_GET['status'];
if(isset($_GET['id']))
	{
	$id=$_GET['id'];
phpsafe($id);
	}


$newstatus=$_GET['newstatus'];
$url=$_GET['url'];

?>

	<?php
	$result=mysql_query("select * from  advertiser_fund_deposit_history where id=$id");
	$row=mysql_fetch_row($result);

$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}
	?>

<br><br>
<strong>Note:</strong>
<span class="info">Advertiser has selected the currency as<strong> <?php if($row[8]==1) echo $system_currency; else echo "non $system_currency";?></strong>.Please verify that the check/bank payment received is of the same currency as specified by advertiser. If the currency specified by by advertiser is different you can change it below.</span>
<form action="ppc-advertiser-request-change-status.php" method="post">

<input type="hidden" name="status" value="<?php echo $status; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="newstatus" value="<?php echo $newstatus; ?>">
<input type="hidden" name="url" value="<?php echo $url; ?>">
	<table id="inner" width="100%"  border="0" cellspacing="0" cellpadding="0">
  	<tr>
    <td colspan="5" style="white-space:nowrap">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
	  </tr>
	  	<tr>
	<td colspan="7"><br></td>
	</tr>
      <tr bgcolor="#b7b5b3" class="style11" height="28">
	  <td  style="padding-left:3px;" width="15%">Name</td>
        <td width="15%"><strong>Request date</strong></td>
        <td width="12%" ><strong>Amount(<?php echo $currency_symbol; ?>)</strong></td>
        <td width="12%"><strong>Status</strong></td>
		<td colspan="4" width="44%"><strong>Change currency type </strong></td>
      </tr>
	    
      <tr <?php if($i%2==1) echo 'bgcolor="#ededed"';?> height="28">    
	  <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><?php echo $mysql->echo_one("SELECT USERNAME FROM `ppc_users` WHERE uid='$row[1]'") ?></td>             
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;" ><?php echo dateFormat($row[6]); ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php echo $row[5]; ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php 
		if($row[7]==0)
			echo "Pending";
		if($row[7]==1)
			echo "Approved";
		if($row[7]==2)
			echo "Rejected";
		if($row[7]==3)
			echo "Completed";
			if($row[7]==4)
			echo "Rolled Back";

		 ?>          </td>
		 <td style="border-bottom: 1px solid #b7b5b3;" colspan="4"><?php 
		 $r1="";
		 $r2="";
		 if($row[8]==1)
		 	$r1="checked";
		else
			$r2="checked";
		 echo "<input type=\"radio\" value=\"1\" $r1 name=\"currency_type\"> $system_currency
                  <input  type=\"radio\" value=\"0\"  name=\"currency_type\" $r2>
                 Non $system_currency"; ?></td>
      </tr>
	
	<tr>
	<td colspan="7"><br></td>
	</tr>
	  	<tr>
    <td colspan="5" >   &nbsp;&nbsp;    </td>
    <td colspan="2">&nbsp;</td>
	  </tr>
	</table>	
<center>	<input type="submit" name="submit" value="Approve"></center>
</form>
<br><br>
<strong>Note:</strong>
<span class="info">If the check/bank payment received is in <strong><?php   echo "non $system_currency";?></strong> currency, you can convert it to the system currency later when you mark  this transaction as completed.</span>
<br>
<br>
<style type="text/css">
<!--
.style1 {
	color: #000000;
	font-weight: bold;
}
.style11 {color: white;
	font-weight: bold;}
-->
</style>

<?php include("admin.footer.inc.php"); ?>
