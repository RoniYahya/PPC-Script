<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");

if($single_account_mode==0)
{
	if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-user-control-panel.php");
exit(0);	
	}
	else
	{
	header("Location: ppc-publisher-control-panel.php");
exit(0);	
	}
}
$template=new Template();
$template->loadTemplate("publisher-templates/transfer-fund.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$user=new User("ppc_publishers");

$uid=$user->getUserID();
$comid=$mysql->echo_one("select common_account_id from ppc_publishers where uid='$uid'");
if($single_account_mode==1 && $comid==0)
{
	if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-user-control-panel.php");
exit(0);	
	}
	else
	{
	header("Location: ppc-publisher-control-panel.php");
exit(0);	
	}
}


if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();
$amount=$_POST['amount'];
phpSafe($amount);
if($amount=="" || !is_numeric($amount) ) 
{
header("Location:publisher-show-message.php?id=8891");
exit(0);
}
$accou=mysql_query("select accountbalance,common_account_id from ppc_publishers where uid =".$user->getUserID());
$accou1=mysql_fetch_row($accou);
$accountbalance=$accou1[0];
$advst=$mysql->echo_one("select status from ppc_users where common_account_id='$accou1[1]'");
if($advst==0)
{
header("Location:publisher-show-message.php?id=10013");
exit(0);
}
if($advst==-1)
{
header("Location:publisher-show-message.php?id=10013");
exit(0);
}
if($accountbalance<$amount)
{
header("Location:publisher-show-message.php?id=6045");
exit(0);
}

if($min_publisher_acc_balance>$amount)
{
header("Location:publisher-show-message.php?id=6039");
exit(0);
}
if(mysql_query("insert into ppc_publisher_payment_hist (`id`,`uid`,`reqdate`, `amount`,`status`,`paymode`) values('0',$uid,'".time()."',$amount,'0','3')"))
{
	$pubaccount=numberFormat($accou1[0]-$amount);
		mysql_query("update ppc_publishers set accountbalance='$pubaccount' where uid=$uid");
	header("location:publisher-show-message.php?id=8892");
exit;
}
else
{
	header("location:publisher-show-message.php?id=1004");
exit;
}
?>