<?php 

/*--------------------------------------------------+

|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
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

$url=$_REQUEST['url'];
$id=$_REQUEST['id'];
phpsafe($id);

$newstatus=$_REQUEST['newstatus'];
phpsafe($newstatus);

if($newstatus==3)// Completed
{

	$amt= $mysql->echo_one("select amount from advertiser_fund_deposit_history where id='$id'");
	$currentstat=$mysql->echo_one("select status from advertiser_fund_deposit_history where id='$id'");
	$uid=$mysql->echo_one("select uid from advertiser_fund_deposit_history where id='$id'");
	if(isset($_POST['conversion_amt']))
	{
		$conversion_amt=trim($_POST['conversion_amt']);
		if(!is_numeric($conversion_amt) || $conversion_amt<=0)
		{
			include("admin.header.inc.php");
			echo "<br>Please go back fill in a proper conversion rate."; ?> <a href="javascript:history.back(-1);">Go Back</a><br> <?php
				include("admin.footer.inc.php");
				exit(0);
		}
		$amt=$_POST['amt'];
		$amt=round($amt/$conversion_amt,2);
		if(	$currentstat==1)
		{
			mysql_query("update advertiser_fund_deposit_history set status ='$newstatus',amount='$amt',currency_type=1  where id='$id'");
			mysql_query("update ppc_users  set accountbalance =accountbalance+'$amt' where uid='$uid'");
			
			$coupon_id=$mysql->echo_one("select coupon_id from advertiser_fund_deposit_history where id='$id'");
			if($coupon_id > 0)
			{
						
						//$coupon_expry=$mysql_echo_one("select date from advertiser_fund_deposit_history where id='$id'");
				$coupon_amt=0;
	
				$coupon_type= $mysql->echo_one("select type from gift_code where id='$coupon_id'");
				if($coupon_type ==1)
				{
					$perc_coupon=$mysql->echo_one("select amount from gift_code where id='$coupon_id'");
					
					if($perc_coupon=="")
					$perc_coupon=0;
					
					$coupon_amt=($perc_coupon*$amt)/100;
				
				}
				else if($coupon_type ==2)
				{
					$coupon_amt=$mysql->echo_one("select amount from gift_code where id='$coupon_id'");
					if($coupon_amt=="")
					$coupon_amt=0;
				
				}

			}
			
		
			if($coupon_amt > 0)
			{

				mysql_query("update ppc_users  set bonusbalance =bonusbalance+'$coupon_amt' where uid='$uid'");
				mysql_query("insert into advertiser_bonus_deposit_history values('0','$uid','$coupon_amt',0,'Coupon Bonous',".time().",'$coupon_id','$id','bk')"); /* Bonous Coupon Type 0 */
				mysql_query("UPDATE gift_code set count = count+1 where id='$coupon_id'");
			
			}
			
			//*************************
			
			$balance=mysql_query("select accountbalance from ppc_users where uid='$uid'");
			$balancerow=mysql_fetch_row($balance);
			
			if($balancerow[0]>=$advertiser_minimum_account_balance)
			{
			mysql_query("update ppc_users set balancestatus=0 where uid='$uid'");
			
			}
			
			//**************************

		}	
	}
	else
	{
		if(	$currentstat==1)
		{
			mysql_query("update advertiser_fund_deposit_history set status ='$newstatus' where id='$id'");
			mysql_query("update ppc_users  set accountbalance =accountbalance+'$amt' where uid='$uid'");

			$coupon_id=$mysql->echo_one("select coupon_id from advertiser_fund_deposit_history where id='$id'");
			if($coupon_id > 0)
			{
			
				$coupon_amt=0;
				
				$coupon_type= $mysql->echo_one("select type from gift_code where id='$coupon_id'");
				if($coupon_type ==1)
				{
					$perc_coupon=$mysql->echo_one("select amount from gift_code where id='$coupon_id'");
					
					if($perc_coupon=="")
					$perc_coupon=0;
					
					$coupon_amt=($perc_coupon*$amt)/100;
				
				}
				else if($coupon_type ==2)
				{
					$coupon_amt=$mysql->echo_one("select amount from gift_code where id='$coupon_id'");
					if($coupon_amt=="")
					$coupon_amt=0;
				
				}
				

			}
			
			
		
			if($coupon_amt > 0)
			{

				mysql_query("update ppc_users  set bonusbalance =bonusbalance+'$coupon_amt' where uid='$uid'");
				mysql_query("insert into advertiser_bonus_deposit_history values('0','$uid','$coupon_amt',0,'Coupon Bonous',".time().",'$coupon_id','$id','ch')"); /* Bonous Coupon Type 0 */
				mysql_query("UPDATE gift_code set count = count+1 where id='$coupon_id'");
								
			}
				

			//*************************
			
			
			$balance=mysql_query("select accountbalance from ppc_users where uid='$uid'");
			$balancerow=mysql_fetch_row($balance);
			
			if($balancerow[0]>=$advertiser_minimum_account_balance)
			{
			mysql_query("update ppc_users set balancestatus=0 where uid='$uid'");
			
			}
			
			//**************************
		}
	}
}
else
{
	
	if(isset($_POST['currency_type']))
	{
		$currency_type=$_POST['currency_type'];
		mysql_query("update advertiser_fund_deposit_history set status ='$newstatus',currency_type='$currency_type' where id='$id'");
	}
	else
	{
		mysql_query("update advertiser_fund_deposit_history set status ='$newstatus' where id='$id'");
		
	}
}

$url=urldecode($url);
header("location: $url");
exit(0);

 
?>