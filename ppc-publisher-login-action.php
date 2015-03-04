<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


$ini_error_status=ini_get('error_reporting');
includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");
if($single_account_mode==1)
{
if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-user-control-panel.php");
exit(0);	
	}
	elseif($_COOKIE['io_type']==md5("publisher"))
	{
	header("Location: ppc-publisher-control-panel.php");
exit(0);	
	}
	else
	{
		header("Location: login.php");
exit(0);
	}
}
$username=$_POST['username'];
$password=$_POST['password'];
phpSafe($username);
phpSafe($password);
//echo 
if($user->isEmailVerified($username,$password))
{
header("Location:publisher-show-message.php?id=1039");
exit(0);
}

if($user->pendingAccount($username,$password))
{
header("Location:publisher-show-message.php?id=1018");
exit(0);
}







if($user->blockedAccount($username,$password))
{
header("Location:publisher-show-message.php?id=1017");
exit(0);
}

if(!$user->cookieUser($username,$password,"publisher"))
{
header("Location:publisher-show-message.php?id=1005");
exit(0);
}

//echo ' Client IP: ';
$public_ip=getUserIP();
$currtime=time();
$time=$currtime-($fraud_time_interval*60*60);
$pid=$user->getUserID($username);


$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
$serverid=1;
else
{
$serverid=$mysql->echo_one("select server_id from ppc_publishers where uid='$pid'");
if($serverid == 0)
$serverid=$mysql->echo_one("select id from server_configurations where min_range<='$pid' and  max_range >='$pid'");

}

if($serverid == 0 || $serverid =="")
$serverid=1;







$fraudclicks=mysql_query("select id,clickvalue,aid,uid,ip,time,pid,publisher_profit,bid,vid,pub_rid,pub_ref_profit,adv_rid,adv_ref_profit,`current_time`,browser,platform,version,user_agent,serverid,direct_status  from ppc_daily_clicks where pid=$pid and ip='$public_ip'   and clickvalue>0 and time>$time");
$numFraudClicks=mysql_num_rows($fraudclicks);

while($row=mysql_fetch_row($fraudclicks))
{
		
		
if($serverid==1)
{
	$direct_hits=$mysql->echo_one("select direct_hits from publisher_daily_visits_statistics_master where id=$row[9]");
				$vid=$row[9];
				if($direct_hits>0)
				{
				    mysql_query("update publisher_daily_visits_statistics_master set direct_fraud_clicks=direct_fraud_clicks+1,direct_clicks=direct_clicks-1 where id='$vid'");
				}
				else
				{
					mysql_query("update publisher_daily_visits_statistics_master set referred_fraud_clicks=referred_fraud_clicks+1,referred_clicks=referred_clicks-1 where id='$vid'");
				}
		
		}
		
		
    mysql_query("update ppc_users set accountbalance=accountbalance+$row[1] where uid=$row[3]");
	mysql_query("update ppc_ads set amountused=amountused-$row[1] where id=$row[2]");
	
	mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[7] where uid=$pid");
	mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[11] where uid=$row[10]");
	mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[13] where uid=$row[12]");
	
	mysql_query("insert into ppc_fraud_clicks values('0',$row[2],$row[6],$row[14],'$row[4]',1,$row[8],$row[9],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20])");
	mysql_query("delete from ppc_daily_clicks where id=$row[0]");
	

	
if($ini_error_status!=0)
{
	echo mysql_error();
}
}
if($numFraudClicks>0)
	$user->sendFraudAlert($_POST['username'],$admin_general_notification_email,$ppc_engine_name);

//mysql_query("update ppc_fraud_clicks set publisherfraudstatus=1 where ip='$public_ip' and  pid=$pid and clicktime>$time");

mysql_query("update ppc_publishers set lastlogin =".time().",lastloginip='$public_ip' where uid=".$user->getUserID($username));

header("Location:ppc-publisher-control-panel.php");
exit(0);

?>