<?php 
/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/
?><?php 
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");

$user=new User("ppc_publishers");


if(!($user->validateUser()))
{
header("Location: publisher-show-message.php?id=1006");
exit(0);
}
$amount=$_POST['amount'];


phpSafe($amount);


if($amount=="" || !is_numeric($amount) ) 
{
header("Location: publisher-show-message.php?id=6036");
exit(0);
}

$currentuser=$user->getUserID();
$currentusername=$mysql->echo_one("select username from ppc_publishers where uid ='$currentuser'");
//$user->getUsername();
//echo $currentusername;
//$minbalance = $mysql->echo_one("select value from ppc_settings where name ='min_publisher_acc_balance'");
$accountbalance = $mysql->echo_one("select accountbalance from ppc_publishers where uid =".$currentuser);
if($accountbalance<$amount)
{
header("Location: publisher-show-message.php?id=6045");
exit(0);
}

if($min_publisher_acc_balance>$amount)
{
header("Location: publisher-show-message.php?id=6039");
exit(0);
}
$bankdet1=mysql_query("select bank_name,bank_address,acc_no,routing_no,acc_type,b_city,b_state,b_country,b_zip from ppc_publisher_payment_details where uid =".$currentuser);
$bankdet=mysql_fetch_row($bankdet1);
$emailid= $mysql->echo_one("select email from ppc_publishers where uid =".$currentuser);
$currency = $mysql->echo_one("select value from ppc_settings where name ='paypal_currency'");
$paymode = $mysql->echo_one("select paymentmode from ppc_publisher_payment_details where uid =".$currentuser);
$address1= $mysql->echo_one("select address1 from ppc_publisher_payment_details where uid =".$currentuser);
$address2= $mysql->echo_one("select address2 from ppc_publisher_payment_details where uid =".$currentuser);
$city=$mysql->echo_one("select city from ppc_publisher_payment_details where uid =".$currentuser);
$state=$mysql->echo_one("select state from ppc_publisher_payment_details where uid =".$currentuser);
$country=$mysql->echo_one("select country from ppc_publisher_payment_details where uid =".$currentuser);
$zip=$mysql->echo_one("select zip from ppc_publisher_payment_details where uid =".$currentuser);
$details="";
if($paymode==0 || $paymode==2)
{
$details= $mysql->echo_one("select payeename from ppc_publisher_payment_details where uid =".$currentuser);
}
else
{
$details= $mysql->echo_one("select paypalemail from ppc_publisher_payment_details where uid =".$currentuser);
}
$subject="Withdrawal Request";
$body=" 

Hello,

You have got a new withdrawal request from ".$currentusername." for an amount of ".$amount." ".$currency." You may view and process the request from your admin area.

Thanks,
$ppc_engine_name
";
if(mysql_query("insert into ppc_publisher_payment_hist values('0','$currentuser','".time()."','$paymode','','$details','','$amount','$currency','0','','$address1','$address2','$city','$state','$country','$zip','$bankdet[0]','$bankdet[1]','$bankdet[2]','$bankdet[3]','$bankdet[4]','$bankdet[5]','$bankdet[6]','$bankdet[7]','$bankdet[8]')"))
{
mysql_query("update ppc_publishers set accountbalance=accountbalance-$amount where uid =".$currentuser);
mail("$admin_payment_notification_email", "$subject", $body,
     "From: $currentusername<$emailid>\r\n"
    ."Reply-To:  $currentusername<$emailid>\r\n"
    ."X-Mailer: PHP/" . phpversion());
	
header("Location: publisher-show-success.php?id=6037&type=1&status=1"); //$page=$pagename;
exit(0);
}
else
{
header("Location: publisher-show-message.php?id=1004");
exit(0);
}
?>