<?php 
/*--------------------------------------------------+
|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/
?><?php 
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");


$ini_error_status=ini_get('error_reporting');

$paymode=$_POST['radiobutton'];
$paypalemail=$_POST['paypalemail'];
$payeename=$_POST['payeename'];
$address1=$_POST['address1'];
$address2=$_POST['address2'];
$city=$_POST['city'];
$state=$_POST['state'];
$country=$_POST['country'];
$zip=$_POST['zip'];
$url=urldecode($_POST['url']);
phpSafe($paymode);
phpSafe($paypalemail);
phpSafe($payeename);
phpSafe($address1);
phpSafe($address2);
phpSafe($city);
phpSafe($state);
phpSafe($country);
phpSafe($zip);

//added on 09 November 2009

$acc_no=$_POST['acc_no'];
$routing_no=$_POST['routing_no'];
$acc_type=$_POST['acc_type'];

$bank_address=$_POST['bank_address'];
$bank_name=$_POST['bank_name'];
$acc_type=$_POST['acc_type'];

$b_city=$_POST['b_city'];
$b_state=$_POST['b_state'];
$b_country=$_POST['b_country'];
$b_zip=$_POST['b_zip'];


phpSafe($acc_no);
phpSafe($routing_no);
phpSafe($acc_type);
phpSafe($bank_address);
phpSafe($bank_name);
phpSafe($acc_type);

phpSafe($b_city);
phpSafe($b_state);
phpSafe($b_country);
phpSafe($b_zip);

$bank_str="";
$paypal_str="";
$check_str="";
$user=new User("ppc_publishers");
//echo $paymode;
//exit(0);
$msg=0;
if($paymode==1)
{
	if(!$user->isValidEmail($paypalemail))
	{
	header("Location:publisher-show-message.php?id=6009");
	exit(0);
	}
	$paypalcount=$mysql->echo_one("select count(*) from ppc_publisher_payment_details where paypalemail='$paypalemail'");
	if($paypalcount==0)
	{
$msg=1013;
$paypal_str=", paypalemail = '$paypalemail'";
	}
	else
	{
		$msg=8893;
	}
}
else if($paymode==2)
	{
		if($payeename=="" || $b_country=="" || $b_zip=="" || $acc_no=="" || $routing_no=="" || $acc_type=="" || $b_state=="" || $bank_name=="" || $bank_address=="")
		{
			header("Location:publisher-show-message.php?id=6088");
			exit(0);
		}
	$bank_str=",payeename='$payeename',acc_no='$acc_no',routing_no='$routing_no',acc_type='$acc_type',bank_address='$bank_address',bank_name='$bank_name',acc_type='$acc_type',b_city='$b_city' ,b_state='$b_state' ,b_country='$b_country' ,b_zip='$b_zip'";
	$msg=1036;
	}
else
{
	if($payeename=="" || $address1=="" || $country=="" || $zip=="" || $state=="" || $city=="")
	{
		header("Location:publisher-show-message.php?id=6046");
		exit(0);
	}
	$check_str=",payeename='$payeename', address1='$address1', address2='$address2',city='$city' ,state='$state' ,country='$country' ,zip='$zip'";
	$msg=1013;
}
$uid=$user->getUserID();

//$as=mysql_query("select * from  ppc_publisher_payment_details where uid='$uid'");
//$sd=mysql_num_rows($as);
//if($single_account_mode==1 && $sd==0)
//{
//	mysql_query("insert into ppc_publisher_payment_details (id,uid,paymentmode,payeename,address1,address2,city,state,country,zip) values('0','$uid','$paymode','$payeename','$address1','$address2','$city','$state','$country','$zip')");
//}
mysql_query("update ppc_publisher_payment_details set paymentmode ='$paymode' $paypal_str $check_str $bank_str where uid='$uid'");

//echo "update ppc_publisher_payment_details set paymentmode ='$paymode' $paypal_str $check_str $bank_str where uid='$uid'";
if($ini_error_status!=0)
{
	echo mysql_error();
}
$page="ppc-publisher-configure-payment.php";
if($url!="")
	header("Location:$url");
else
	header("Location:publisher-show-success.php?id=$msg&type=1&status=1&page=$page");
exit(0);
?>