<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
 
$user=new User("ppc_users");
if(!($user->validateUser()))
{
header("Location:show-message.php?id=1006");
exit(0);
}

$ini_error_status=ini_get('error_reporting');
$amount=$_POST['amount'];
$checkno=$_POST['checkno'];
$name=$_POST['accountholdersname'];
$bank=$_POST['bankname'];
$currency_type=$_POST['currency_type'];
$bankaddress=$_POST['bankaddress'];
$transfer_type=$_POST['transfer_type'];
$status=0;
if(isset($_POST['city']) && isset($_POST['swift']) && $_POST['transfer_type']==2)
	{
	$city=$_POST['city'];
	$swift=$_POST['swift'];
	$country=$_POST['country'];
	$comment=$_POST['comment'];
	phpSafe($city);
	phpSafe($swift);
	phpSafe($country);
	phpSafe($comment);
	if($city==""||$swift=="" ||$country=="")
		{
		
			header("Location:show-message.php?id=1001");
			exit(0);
		}
	
	}

phpSafe($amount);
phpSafe($checkno);
phpSafe($name);
phpSafe($bank);
phpSafe($bankaddress);
phpSafe($transfer_type);


if($amount==""||$checkno=="" ||$bank==""||$name==""||$bankaddress=="")
{

	header("Location:show-message.php?id=1001");
	exit(0);
}


if(!is_numeric($checkno) || $checkno < 0)
{
	header("Location:show-message.php?id=6065");
	exit(0);
}
$userid=$user->getUserID();


if(isset($_POST['coupon']))
{
$coupon=trim($_POST['coupon']);
phpSafe($coupon);
}
else
$coupon="";

//$couponid=$mysql->echo_one("select id from gift_code where coupon_code='$coupon'");



$coupon_amt=0;
if($coupon !="" && $coupon !=0)
{

$tm=time();
$count_coupon_id=$coupon;



$coupon_type= $mysql->echo_one("select type from gift_code where id='$count_coupon_id'");
if($coupon_type ==1)
{
$perc_coupon=$mysql->echo_one("select amount from gift_code where id='$count_coupon_id'");

if($perc_coupon=="")
$perc_coupon=0;

$coupon_amt=($perc_coupon*$amount)/100;

}
else if($coupon_type ==2)
{
$coupon_amt=$mysql->echo_one("select amount from gift_code where id='$count_coupon_id'");
if($coupon_amt=="")
$coupon_amt=0;

}




if($coupon_amt == 0)
$couponid="";
else if($count_coupon_id =="")
$couponid=0;
else
$couponid=$count_coupon_id;






}






$t=time();
mysql_query("INSERT INTO `advertiser_fund_deposit_history` (`uid` , `checkno`  , `bankname` ,`accountholdersname` ,`amount` ,`date`, `status`,`currency_type`,`bank_address`, `routing_no`,  `pay_type`, `comment`, `bank_city`, `bank_country`,`coupon_id`) VALUES ('$userid', '$checkno','$bank','$name','$amount', '$t', '$status','$currency_type','$bankaddress', '$swift',  '$transfer_type', '$comment','$city','$country','$couponid')");
$username=$user->getUsername();

$currency =$system_currency;
if ($currency_type==0)
	$currency ="Non ".$system_currency;

		$msg = <<< EOB

Hello,

A new check/bank payment has beeen received at $ppc_engine_name.

Username	 : $username
Amount		 : $amount
Currency type: $currency

Login to your admin area for more details.

Regards,
$ppc_engine_name

EOB;

//echo $msg;exit(0);

if($script_mode!="demo")
xMail($admin_general_notification_email, "$ppc_engine_name - Fund request received", $msg, $admin_general_notification_email, $email_encoding);


if($ini_error_status!=0)
{
	echo mysql_error();
}

header("Location:show-success.php?id=2005");
exit(0);

?>
