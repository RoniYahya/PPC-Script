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
include("admin.header.inc.php"); 

$id=$_REQUEST['id'];
$status=$_REQUEST['status'];
if(isset($_GET['back']))
$back=$_GET['back'];
else
$back=1;
$urlstr="";
$str="all";
if($status==0)
{
$urlstr="?status=0";
$str="pending";
}
if($status==1)
{
$urlstr="?status=1";
$str="approved";
}	
if($status==2)
{
$urlstr="?status=2";
$str="Rejected";
}	
if($status==3)
{
$urlstr="?status=3";
$str="completed";
}
if($status==4)
{
$urlstr="?status=4";
$str="Roll Back";
}		

$result=mysql_query("select * from advertiser_fund_deposit_history where id='$id'");
$row=mysql_fetch_row($result);
$uid=$row[1];
 
?>
<br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td colspan="2" class="heading" >The payment details are given below. </td>
	<td>&nbsp;</td>
  </tr>

  <tr>
    <td width="434">&nbsp;</td>
    <td width="592">&nbsp;</td>
    <td width="82">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Advertiser</strong> </td>
    <td><a href="view_profile.php?id=<?php echo $uid;?>"><?php echo $mysql->echo_one("select username from ppc_users where uid='$uid'"); ?></a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Payment <?php if($row[11]==1||$row[11]==2) /* Check/Bank */ {?> Request <?php } ?> Date</strong> </td>
    <td><?php echo dateFormat($row[6])?></td>
    <td>&nbsp;</td>
  </tr>
  		  <tr>
    <td width="434">&nbsp;</td>
    <td width="592">&nbsp;</td>
    <td width="82">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Payment Mode</strong> </td>
    <td><?php 
	if($row[11]==1)
		echo "Check";
		elseif($row[11]==3)
		echo "Transfer to advertiser";
	else
		echo "Bank";
		
		?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 
  <tr>
    <td><strong>Amount</strong> </td>
    <td><?php if($row[8]==1) echo moneyFormat($row[5]);  else echo numberFormat($row[5])." (Non $system_currency)"; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <?php

  if($row[11]!=3) 
  {
  ?>
   <tr>
    <td><strong>Bankname</strong></td>
    <td><?php echo $row[3];?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td><strong><?php 
	if($row[11]==1)
		echo "Check Number";
	else
		echo "Account number";
		?></strong></td>
    <td><?php echo $row[2]; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
	  <?php
	  if($row[11]==2)
		{
	  ?>
		<tr>
		<td><strong>Swift/Routing no</strong></td>
		<td><?php echo $row[10];?></td>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	  </tr>
	  <?php } ?>
   <tr>
    <td valign="top"><strong>Bank address </strong></td>
    <td><?php echo nl2br($row[9]);?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  

  <?php 
  }
  if($row[11]==2)
  	{
  ?>
    <tr>
    <td><strong>Bank City</strong></td>
    <td><?php echo $row[13];?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td><strong>Bank Country</strong></td>
    <td><?php echo $row[14];?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      <tr>
    <td><strong>Comment</strong></td>
    <td><?php echo nl2br($row[12]);?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php } ?>
  <tr>
    <td><strong>Current status</strong> </td>
    <td><?php 
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
	?></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Coupon</strong> </td>
    <td><?php 
			if($row[15]==0 || $row[15]=="")
			{
			   echo "NA";
			}
			else
			{
			//http://localhost/workspace/adserver_mike/inout_adserver_ultimate/admin/&name=123
			   $coupon_name= $mysql->echo_one("select name from gift_code where id='$row[15]'"); 
				if($coupon_name=='')
					echo "Deleted.";
				else
			   		echo '<a href="coupon-details.php?id='.$row[15].'">'.$coupon_name.'</a>';
			
			}
		
	?></td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      
  <tr>
    <td colspan="2"  ><a href="javascript:history.back(-1)">Click here to go back</a></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br>
<br>
<?php include("admin.footer.inc.php"); ?>