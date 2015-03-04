<?php 







/*--------------------------------------------------+



|													 |



| Copyright © 2006 http://www.inoutscripts.com/      |



| All Rights Reserved.								 |



| Email: contact@inoutscripts.com                    |



|                                                    |



+---------------------------------------------------*/















?><?php



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


$pid=$_POST['pid'];
$url=$_POST['url'];
$subject=$_POST['subject'];
$text=$_POST['text'];


$repeet_clicks=$_POST['repeet_clicks'];

if($repeet_clicks==1)
{
//$public_ip=getUserIP();
$currtime=time();
$time=$currtime-(24*60*60);
//$pid=$user->getUserID($username);




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




$fraudclicks=mysql_query("select id,clickvalue,aid,uid,ip,time,pid,publisher_profit,bid,vid,pub_rid,pub_ref_profit,adv_rid,adv_ref_profit,`current_time`,browser,platform,version,user_agent,serverid,direct_status  from ppc_daily_clicks where pid=$pid   and clickvalue>0 and time>$time");
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
	else{
			mysql_query("update publisher_daily_visits_statistics_master set referred_fraud_clicks=referred_fraud_clicks+1,referred_clicks=referred_clicks-1 where id='$vid'");
        }
		
}		
		
		
		
    mysql_query("update ppc_users set accountbalance=accountbalance+$row[1] where uid=$row[3]");
	mysql_query("update ppc_ads set amountused=amountused-$row[1] where id=$row[2]");
	
	mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[7] where uid=$pid");
	mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[11] where uid=$row[10]");
	mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[13] where uid=$row[12]");
	
	//mysql_query("insert into ppc_fraud_clicks values('0',$row[2],$row[6],$row[5],'$row[4]',1,$row[8],$row[9])");
	
	
	
	
	mysql_query("insert into ppc_fraud_clicks values('0',$row[2],$row[6],$row[14],'$row[4]',1,$row[8],$row[9],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20])");
	mysql_query("delete from ppc_daily_clicks where id=$row[0]");
}
}//if($repeet_clicks==1)



//echo "$subject <br> $body";
//echo "pid=$pid---url	=$url";

$email=$mysql->echo_one("select email from ppc_publishers where uid='$pid'");
//$uid=$mysql->echo_one("select b.uid from ppc_ads a,ppc_users b where a.uid=b.uid and a.id='$id'");
//$email=$mysql->echo_one("select email from ppc_publishers where uid=$uid");
//echo $email;
//exit(0);
$subject=$_POST['subject'];
if(get_magic_quotes_gpc())
	{
	$text=stripslashes($text);
	$subject=stripslashes($subject);
	}
	
			  if($script_mode!="demo")
 {


include("class.Email.php");

$message = new Email($email, $admin_general_notification_email, $subject, '');
$message->Cc = ''; 
$message->Bcc = ''; 

$message->SetHtmlContent(nl2br($text));  
$message->Send();
}

//exit(0);

mysql_query("update ppc_publishers set status=0 where uid='$pid'");
mysql_query("delete from ppc_clicks where pid=$pid and clickvalue=0");
mysql_query("delete from ppc_fraud_clicks where pid=$pid and  (publisherfraudstatus=1 or publisherfraudstatus=2)");
//echo "delete from ppc_clicks where pid=$pid and clickvalue=0";
//echo "delete from ppc_fraud_clicks where pid=$pid and  publisherfraudstatus=0";
header("Location:$url");
exit(0);
?>