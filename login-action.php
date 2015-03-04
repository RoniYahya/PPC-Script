<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


if($single_account_mode==0)
{
	

	header("location: index.php");
	exit;

}
includeClass("User");
includeClass("Form");
$user=new User("nesote_inoutscripts_users","id");
$username=$_POST['username'];
$password=$_POST['password'];

phpSafe($username);
phpSafe($password);
//echo 


$public_ip=getUserIP();
$currtime=time();
$time=$currtime-($fraud_time_interval*60*60);




if($user->isEmailVerified($username,$password))
{
header("Location:show-message.php?id=1039");
exit(0);
}



$type1="";
//$type1=$_POST['radiobutton'];
if(isset($_POST['radiobutton']))
	{
		if($_POST['radiobutton']==1)
		{
			$type1="advertiser";
		}
		elseif($_POST['radiobutton']==2)
		{
			$type1="publisher";
		}
		else
		{
			$type1="Common";
		}
	}
	
$comid=0;
$pubid=0;	
if($user->checkStatus($username,$password,$type1))
{
header("Location:show-message.php?id=1005");
exit(0);
}
if(!$user->cookieUser($username,$password,$type1))
{
header("Location:show-message.php?id=1005");
exit(0);
}
else
{
	$comid=$user->getUserID($username);
	$pubid=$mysql->echo_one("select uid from ppc_publishers where common_account_id='$comid'");
	
	$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
$serverid=1;
else
{
$serverid=$mysql->echo_one("select server_id from ppc_publishers where uid='$pubid'");
if($serverid == 0)
$serverid=$mysql->echo_one("select id from server_configurations where min_range<='$pubid' and  max_range >='$pubid'");

}

if($serverid == 0 || $serverid =="")
$serverid=1;
	
	
$fraudclicks=mysql_query("select id,clickvalue,aid,uid,ip,time,pid,publisher_profit,bid,vid,pub_rid,pub_ref_profit,adv_rid,adv_ref_profit,`current_time`,browser,platform,version,user_agent,serverid,direct_status  from ppc_daily_clicks where pid=$pubid and ip='$public_ip'   and clickvalue>0 and time>$time");
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
	
	mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[7] where uid=$pubid");
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
	
$advstatus=$mysql->echo_one("select status from ppc_users where common_account_id=".$comid);
$pubstatus=$mysql->echo_one("select status from ppc_publishers where common_account_id=".$comid);
if($advstatus==1)	
mysql_query("update ppc_users set lastlogin =".time()."  where common_account_id=".$comid);
if($pubstatus==1)	
mysql_query("update ppc_publishers set lastlogin =".time().",lastloginip='$public_ip'  where common_account_id=".$comid);

header("Location:control-panel.php");
exit(0);

}
?>