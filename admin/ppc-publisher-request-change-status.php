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


?>
<style type="text/css">
<!--
.style7 {color: #000000; font-size: 20px; }
.style8 {color: #666666}
-->
</style>
<style type="text/css">
<!--
.style9 {font-size: 18px}
.style10 {color: #FF0000}
.style11 {font-size:12px; font-weight:bold; color: #CC0000}
-->
</style>

<?php

$id=$_REQUEST['id'];
phpsafe($id);

$req_res=mysql_query("select * from ppc_publisher_payment_hist where id='$id'");
$req_row=mysql_fetch_array($req_res);

if(!$req_row)

{
?>
	<span class="already"><br>
	<?php echo "Invalid request.";?></span>
	<a href="javascript:history.back(-1);">Go Back</a>           <br>
<?php	
	include("admin.footer.inc.php"); 
	die;
}

$status=$req_row['status'];
$type=$req_row['paymode'];
//$status=$_REQUEST['status'];

$newstatus=$_REQUEST['newstatus'];

//$type=-1;
//if(isset($_REQUEST['type']))
	//$type=$_REQUEST['type'];
	
$urlstr="?status=-1";
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


if($type==0)
{
$urlstr.="&type=c";
$str.=" check";
}
if($type==1)
{
$urlstr.="&type=p";
$str.=" paypal";
}	
if($type==2)
{
$urlstr.="&type=b";
$str.=" bank";
}	
if($type==3)
{
$urlstr.="&type=t";
$str.=" transfer";
}	

/*if($newstatus==-2)
{
$urlstr="?status=-2";
$str="Rejected";
}*/





		
		
		
if($newstatus==1) // approve
{

	if($status!=0)
	
	{
	?>
		<span class="already"><br>
		<?php echo "Only pending requests can be approved.";?></span>
		<a href="javascript:history.back(-1);">Go Back</a>           <br>
	<?php	
		include("admin.footer.inc.php"); 
		die;
	}



	if(!isset($_GET['continue']))
	{
		$pid = $mysql->echo_one("select uid from ppc_publisher_payment_hist where id = $id");
		$pname = $mysql->echo_one("select username from ppc_publishers where uid = $pid");
		$time = $mysql->echo_one("select max(processdate) from ppc_publisher_payment_hist where status=3 and  uid = $pid ");
		if($time=="")
			$time=0;
		$fraudclk=$mysql->echo_one("select  count(id) from ppc_fraud_clicks where publisherfraudstatus=1 and pid=$pid and  clicktime>$time ");
		$invclk=$mysql->echo_one("select  count(id) from ppc_fraud_clicks where publisherfraudstatus=2 and pid=$pid and  clicktime>$time ");
		if($fraudclk>0 || $invclk>0)
		{
		?> <table width="100%" border="0" cellpadding="0" cellspacing="0">
		     <tr>
			 <td colspan="3"><br />

		  <span class="bold">Note:</span><span class="info"> <a href="view_profile_publishers.php?id=<?php echo $pid; ?>"><?php echo $pname; ?></a> has possibly done <?php echo $fraudclk?> fraud clicks and <?php echo $invclk?> invalid clicks after last withdrawal . We recommend that you ensure his credibility by checking his click/traffic analysis before approving this request.</span>
		  <br /><br />

		  <a href="ppc-publisher-request-change-status.php?status=0&id=<?php echo $id; ?>&newstatus=-2&continue=1">Reject Request</a>	| <a href="ppc-publisher-request-change-status.php?status=0&id=<?php echo $id; ?>&newstatus=-3&continue=1">Deny Request</a>	| <a href="ppc-publisher-request-change-status.php?status=0&id=<?php echo $id; ?>&newstatus=1&continue=1">Process Request</a>
		  <br /><br />	
		  <ul style="padding-left:15px">
		   <li><span class="info">If you reject the request, the status will become 'Rejected' and the amount will be reverted to publisher account.  </span></li>
		   <li><span class="info">If you deny the request, the status will become 'Denied' and the amount will <strong>not</strong> be reverted to publisher account.  </span></li>
		    <li><span class="info">If you choose to continue, the request shall be processed normally   </span></li>
		   </ul>
		    </td>
        </tr></table>
		<?php
		include("admin.footer.inc.php"); 
		die;
		}

		
	}	

	if($type==3) // transfer
	{
		$time=time();
		$pubfund=mysql_query("select uid,amount from  ppc_publisher_payment_hist where id='$id'");
		$pubid=mysql_fetch_row($pubfund);
		$commonid1=mysql_query("select common_account_id,accountbalance from ppc_publishers where uid='$pubid[0]'");
		$commonid=mysql_fetch_row($commonid1);
		$advid1=mysql_query("select uid,accountbalance from ppc_users where common_account_id='$commonid[0]'");
		$advid=mysql_fetch_row($advid1);
		
		mysql_query("insert into advertiser_fund_deposit_history(id,uid,amount,date,status,routing_no,pay_type,comment) values (0,$advid[0],$pubid[1],$time,3,$pubid[0],'3','transfer publisher to advertiser')");
		$lastid=mysql_insert_id();
		mysql_query("update ppc_publisher_payment_hist set txid=$lastid,status=3,processdate='$time' where id='$id'");
		$account=numberFormat($advid[1]+$pubid[1]);
		mysql_query("update ppc_users set accountbalance='$account' where uid=$advid[0]");
				//$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid='$pubid[0]'");
		//mysql_query("update ppc_publisher_payment_hist set txid=$lastid,status=1,processdate='$time'");
//		$pubaccount=numberFormat($commonid[1]-$pubid[1]);
//		mysql_query("update ppc_publishers set accountbalance='$pubaccount' where uid=$pubid[0]");
		?>
	<br>
	Request status has been successfully changed.
	<a href="ppc-publisher-withdrawal-history.php<?php echo $urlstr;?>">View <?php echo " ".$str." "; ?>withdrawals</a> <br>
	<br>	
<?php	}
	else // check/bank/paypal
	{
	?>
	<form name="approvalForm" method="post" action="ppc-publisher-request-approve-action.php<?php echo $urlstr."&id=".$id;?>">
	  <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2" ><span class="style7">Please input the following details</span></td>
          <td width="113">&nbsp;</td>
        </tr>
        <tr>
          <td width="354">&nbsp;</td>
          <td width="844">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
 <tr>
    <td><strong><?php 
			if($type==0)
			echo "Check Number";
			if($type==2)
			echo "Bank Transaction Id";
		if($type==1)
			echo "Paypal Transaction Id.";
	?></strong></td>
    <td><input type="text" name="txid" size="30"><span class="style10"><strong>*</strong></span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
    <tr>
    <td><strong><?php 
			if($type==0 || ($type==2))
			echo "Payer Information ";
		if($type==1)
			echo "Payer Paypal Email";
	?></strong></td>
    <td><input type="text" name="payerinfo" size="30" value="<?php if($type==1) echo $mysql->echo_one("select value from ppc_settings where name='paypal_email'"); ?>"><span class="style10"><strong>*</strong></span></td>
    <td>&nbsp;</td>
  </tr>

        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
		
     <tr>
          <td>&nbsp;</td>
          <td><input type="hidden" name="type" value="<?php echo $type;?>"><input name="approve" type="submit" value="Update And Approve!"> </td>
          <td>&nbsp;</td>
        </tr>
		     <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
		
      </table>
	</form>
	<?php 
	}  // end of check/bank/paypal
}  // end of approve
else
{
	if($newstatus=="-3") // fund will not be reverted for denial
	{
		$currentstatus=$mysql->echo_one("select status from ppc_publisher_payment_hist where id='$id'");
		if($currentstatus==0)
		{
			mysql_query("update ppc_publisher_payment_hist set status=-3   where id='$id'");
			$uid=$mysql->echo_one("select uid from ppc_publisher_payment_hist where id='$id'");
			//echo "uid".$uid;
		//	$amt=$mysql->echo_one("select amount from ppc_publisher_payment_hist where id='$id'");
			//echo "amt".$amt;
		//	mysql_query("update ppc_publishers set accountbalance=accountbalance+$amt where uid='$uid'");
		}
	}
	else if($newstatus==2)//in case of roll back, revert the amount back to publisher's account
	{
		$currentstatus=$mysql->echo_one("select status from ppc_publisher_payment_hist where id='$id'");
		//echo $currentstatus;
		if($currentstatus==1) // only approved requests can be rolledback
		{
			mysql_query("update ppc_publisher_payment_hist set status ='$newstatus' where id='$id'");
			$uid=$mysql->echo_one("select uid from ppc_publisher_payment_hist where id='$id'");
			//echo "uid".$uid;
			$amt=$mysql->echo_one("select amount from ppc_publisher_payment_hist where id='$id'");
			//echo "amt".$amt;
			mysql_query("update ppc_publishers set accountbalance=accountbalance+$amt where uid='$uid'");
		}	
	}
	else if($newstatus==-2) // reject
	{
	
		$currentstatus=$mysql->echo_one("select status from ppc_publisher_payment_hist where id='$id'");
		if($currentstatus==0) // only pending requests can be rejected
		{
			mysql_query("update ppc_publisher_payment_hist set status ='$newstatus' where id='$id'");
			$uid=$mysql->echo_one("select uid from ppc_publisher_payment_hist where id='$id'");
			//echo "uid".$uid;
			$amt=$mysql->echo_one("select amount from ppc_publisher_payment_hist where id='$id'");
			//echo "amt".$amt;
			mysql_query("update ppc_publishers set accountbalance=accountbalance+$amt where uid='$uid'");
		}
	}	
	else if($newstatus==3) // mark completed
	{
		$currentstatus=$mysql->echo_one("select status from ppc_publisher_payment_hist where id='$id'");
		//echo $currentstatus;
		if($currentstatus==1) // only approved requests can be completed
		{
			mysql_query("update ppc_publisher_payment_hist set status ='$newstatus' where id='$id'");
		}	
	}
	else
	{
	   // invalid status
	}
	?>
	<br>
	Request status has been successfully changed.
	<a href="ppc-publisher-withdrawal-history.php<?php echo $urlstr;?>">View <?php echo " ".$str." "; ?>withdrawals</a> <br>
	<br>
	<?php 
}
 
?>
<?php include("admin.footer.inc.php"); ?>