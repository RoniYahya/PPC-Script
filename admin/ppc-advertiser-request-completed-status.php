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

//$url=urlencode($_SERVER['REQUEST_URI']);


$ini_error_status=ini_get('error_reporting');

$status=$_GET['status'];
if(isset($_GET['id']))
	{
	$id=$_GET['id'];
phpsafe($id);
	}


$newstatus=$_GET['newstatus'];
$url=$_GET['url'];

	$result=mysql_query("select * from  advertiser_fund_deposit_history where id=$id");
	$row=mysql_fetch_row($result);

if($ini_error_status!=0)
{
	echo mysql_error();
}

if($row[8]==1)
	{
	$loc='ppc-advertiser-request-change-status.php?status='.$status.'&id='.$id.'&newstatus='.$newstatus.'&url='.$url;
	header("location:$loc");
	exit(0);
	}

	
	?>
	<?php include("admin.header.inc.php");  ?>
<br>
<strong>Note:</strong>
<span class="info">You may convert the payment received in <strong><?php   echo "non $system_currency";?></strong> currency to <strong> <?php   echo  $system_currency;?></strong> currency here.</span>
<form action="ppc-advertiser-request-change-status.php" method="post">

<input type="hidden" name="status" value="<?php echo $status; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="newstatus" value="<?php echo $newstatus; ?>">
<input type="hidden" name="url" value="<?php echo $url; ?>">
<input type="hidden" name="amt" value="<?php echo $row[5]; ?>">
	<table id="inner" width="100%"  border="0" cellspacing="0" cellpadding="0">
  	<tr>
    <td colspan="5" style="white-space:nowrap">&nbsp;</td>
    <td width="22%" colspan="2">&nbsp;</td>
	  </tr>

      <tr bgcolor="#b7b5b3" class="style11" height="28">
	  <td  style="padding-left:3px;" width="15%">Name</td>
        <td width="15%"><strong>Request date</strong></td>
        <td width="16%" ><strong>Amount(<?php echo $currency_symbol; ?>)</strong></td>
        <td width="14%"><strong>Status</strong></td>
		<td colspan="4"><strong>Enter the conversion rate </strong></td>
      </tr>
	    
      <tr <?php if($i%2==1) echo 'bgcolor="#ededed"';?> height="28">    
	  <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><?php echo $mysql->echo_one("SELECT USERNAME FROM `ppc_users` WHERE uid='$row[1]'") ?></td>             
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;" ><?php echo dateFormat($row[6]); ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php echo $row[5];   if($row[7]!=3 && $row[8]==0)  echo " (Non $system_currency)";  ?></td>
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
		 <td style="border-bottom: 1px solid #b7b5b3;" colspan="4">
 1 <?php  echo  $system_currency;  ?> = <input type="text" name="conversion_amt" id="conversion_amt"> <?php  echo  "non ".$system_currency;  ?>
 </td>
      </tr>
	
	<tr>
	<td colspan="7"><br></td>
	</tr>
	  	<tr>
    <td colspan="5" >   &nbsp;&nbsp;    </td>
    <td colspan="2">&nbsp;</td>
	  </tr>
	</table>	
<center>	<input type="submit" name="submit" value="Update"></center>
</form>
<br><br>
<br><br>
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
