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

phpSafe($id);

$result=mysql_query("select * from ppc_publisher_payment_hist where id='$id'");
$row=mysql_fetch_array($result);


$uid=$row['uid'];
$status=$row['status'];
/*
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
$str="rolled back";
}	
if($status==3)
{
$urlstr="?status=3";
$str="completed";
}	

if($status==-2)
{
$urlstr="?status=-2";
$str="Rejected";
}	
if($status==-3)
{
$urlstr="?status=-3";
$str="Denied";
}	
 */
?>
<br>
<table width="92%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td colspan="2" class="heading">Withdrawal details  </td>
	<td>&nbsp;</td>
  </tr>
    <tr>
    <td colspan="3" align="center" ><br> </td>
  </tr>
  <tr>
    <td width="316">&nbsp;</td>
    <td width="785">&nbsp;</td>
    <td width="89">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Requested By</strong> </td>
    <td><a href="view_profile_publishers.php?id=<?php echo $uid;?>"><?php echo $mysql->echo_one("select username from ppc_publishers where uid='$uid'");?></a> </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="316">&nbsp;</td>
    <td width="785">&nbsp;</td>
    <td width="89">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Request Date</strong> </td>
    <td><?php echo dateTimeFormat($row['reqdate'])?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td><strong>Request Amount</strong> </td>
    <td><?php echo moneyFormat($row['amount']);?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td><strong>Payment Mode</strong> </td>
    <td><?php 
			if($row[3]==0)
			echo "Check";
		if($row[3]==1)
			echo "Paypal";
			if($row[3]==2)
			echo "Bank";
			if($row[3]==3)
			echo "Transfer to Advertiser";
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

   <?php if($row[3]!=3) { // Cases other than transfer to advertiser
  ?>
 
    <tr>
    <td><strong><?php 
			if($row[3]==2 || $row[3]==0)  // Bank or Check
			echo "Payee Name";
		if($row[3]==1)
			echo "Payee Paypal Email";
	?></strong></td>
    <td><?php echo $row[5];	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php } ?>

  <?php 
  if($row[3]==0)  // Check
  {
  ?>

    <tr>
    <td valign="top"><strong>Payee Address</strong></td>
    <td valign="top"><?php echo $row[11]."<br>". $row[12]."<br>". $row[13]."<br>". $row[14]."<br>". $row[15]."<br>". $row[16]."<br>";	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <?php 
  }
  ?>
    <?php 
  if($row[3]==2)  // Bank
  {
  $bank_info="<br>";
	$bank_info.=$row['b_city']."<br>";
	$bank_info.=$row['b_state']."<br>";
	$bank_info.=$row['b_country']."<br>";
	$bank_info.=$row['b_zip']."<br>";
	
  ?>
    <tr>
    <td><strong>Account No.</strong> </td>
    <td><?php if($row['acc_no']) echo $row['acc_no']; else echo "Not Available"; ?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td><strong>Bank Name</strong> </td>
    <td><?php if($row['bank_name']) echo $row['bank_name'] ;  else echo "Not Available";?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td><strong>Swift/Routing No.</strong> </td>
    <td><?php if($row['routing_no']) echo $row['routing_no'];  else echo "Not Available";?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      <tr>
    <td><strong>Account Type</strong> </td>
    <td><?php if($row['acc_type']) echo $row['acc_type'];  else echo "Not Available";?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      <tr>
    <td valign="top"><strong>Bank Address</strong> </td>
    <td><?php if($row['bank_address']) echo nl2br($row['bank_address']).$bank_info;  else echo "Not Available";?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php 
  }
  ?>
  
  <?php if($row[3]!=3) { // Cases other than transfer to advertiser
  ?>
  <tr>
    <td><strong><?php 
	
			if($row[3]==0 )
			echo "Check Number";
			if($row[3]==2 )
			echo "Bank Transaction Id";
		if($row[3]==1)
			echo "Paypal Transaction Id";
	?></strong></td>
    <td><?php 
	if($row[9]==0 || $row[9]==-2 || $row[9]==-3  )
		echo "N/A";
	else
	{
			echo $row[4];
	}
	?>
	</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td><strong><?php 
	
		if($row[3]==0 || $row[3]==2 )
			echo "Payer Information";
		if($row[3]==1)
			echo "Payer paypal";
	?></strong></td>
    <td><?php 
	if($row[9]==0 || $row[9]==-2  || $row[9]==-3  )
		echo "N/A";
	else
	{
		echo $row[6];
	}
	?>
	</td>
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
			if($row[9]==0)
			echo "Pending";
		if($row[9]==1)
			echo "Approved";
		if($row[9]==2)
			echo "Rolled Back";
		if($row[9]==3)
			echo "Completed";
		if($row[9]==-2)
			echo "Rejected";
		if($row[9]==-3)
			echo "Denied";
	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      <tr>
    <td><strong>Process Date</strong></td>
    <td><?php if($row[10]==0)echo "N/A"; else echo  dateTimeFormat($row[10]);	?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
      <tr>
    <td colspan="3" align="center" ><br> </td>
  </tr>
  <tr>
    <td colspan="2"  > <a href="javascript:history.back(-1)">Click here to go back</a></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br>
<br>
<?php include("admin.footer.inc.php"); ?>