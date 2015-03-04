<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
 
//include_once("messages.".$client_language.".inc.php");
$user=new User("ppc_publishers");

include("graphutils.inc.php");
include("publisher_statistics_utils.php");
$template=new Template();

$template->loadTemplate("publisher-templates/ppc-publisher-profit-statistics.tpl.html");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}

$uid=$user->getUserID();
$user_id=$uid;
$last_login=$mysql->echo_one("select  lastlogin from ppc_publishers where uid='$user_id'");
$last_login_ip=$mysql->echo_one("select  lastloginip from ppc_publishers where uid='$user_id'");


$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
$serverid=1;
else
{
$serverid=$mysql->echo_one("select server_id from ppc_publishers where uid='$user_id'");
if($serverid == 0)
$serverid=$mysql->echo_one("select id from server_configurations where min_range<='$user_id' and  max_range >='$user_id'");

}

if($serverid == 0 || $serverid =="")
$serverid=1;

$fraudtime_interval=time()-($fraud_time_interval*60*60);
$public_ip=getUserIP();

if($last_login<$fraudtime_interval)
	{
	////////////////
	$last_login=$last_login-($fraud_time_interval*60*60);
	$fraudclicks=mysql_query("select id,clickvalue,aid,uid,ip,time,pid,publisher_profit,bid,vid,pub_rid,pub_ref_profit,adv_rid,adv_ref_profit,`current_time`,browser,platform,version,user_agent,serverid,direct_status from ppc_daily_clicks where pid=$user_id and ip='$last_login_ip'   and clickvalue>0 and time>$last_login");
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
			
			mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[7] where uid=$user_id");
			mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[11] where uid=$row[10]");
			mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[13] where uid=$row[12]");
			
			mysql_query("insert into ppc_fraud_clicks values('0',$row[2],$row[6],$row[14],'$row[4]',1,$row[8],$row[9],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20])");
			mysql_query("delete from ppc_daily_clicks where id=$row[0]");
			
		
			
		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
	}
	/////////////////
		//mysql_query("update ppc_publishers set lastlogin='".time()."',lastloginip='".$public_ip."' where uid='$user_id'");
		//echo "update ppc_publishers set lastlogin='".time()."',lastloginip='".$public_ip."' where uid='$user_id'";
	}
$flag_time=0;


//$template=new Template();
//
//$template->loadTemplate("publisher-templates/ppc-publisher-profit-statistics.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
if(!isset($_GET['statistics']))
{	$show="day";	}
else
{ $show=$_GET['statistics']; }


if($show=="day")
{
$flag_time=-1;	
$showmessage="Today";
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));
$beg_time=$time;

//echo date("d M h a",$end_time);
}
else if($show=="week")
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",time())+1-11,1,date("Y",time()));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time())+1,1,date("y",time()));
$beg_time=$time;
}
else if($show=="all")
{
	$flag_time=2;
	$showmessage="All Time";
	$time=0;
	$end_time=mktime(0,0,0,1,1,date("y",time())+1);
$beg_time=$time;

}
else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}

$selected_colorcode=array();
foreach ($color_code as $key=>$value)
{   
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}   

$returnVal=plotPublisherGraphs($show,$flag_time,$beg_time,$end_time,$selected_colorcode,$uid,$referral_system);

$FC=$returnVal[0];
$FD=$returnVal[1];



$click=getPubAdClicks($time,$mysql,$uid,$flag_time);
$total_impressions=getPubAdImpressions($time,$mysql,$uid,$flag_time);
if($total_impressions==0)
	{
	$ctr=0;
	}
else
	{
	$ctr=($click/$total_impressions) * 100;
	$ctr=numberFormat($ctr);
	}
	
$re= getPubPublisherprofit($time,$mysql,$uid,$flag_time);


$aearning=getAdvertiserReferralProfitOfPublisher($time,$mysql,$uid,$flag_time);
$pearning=getPublisherReferralProfitOfPublisher($time,$mysql,$uid,$flag_time);
$tot=$pearning+$aearning+$re;
//$tablestructure=getTimeBasedPublisherStatistics($show,$flag_time,$beg_time,$end_time,$uid);
$template->openLoop("STATISTICS",getTimeBasedPublisherStatistics($show,$flag_time,$beg_time,$end_time,$uid));
$template->setLoopField("{LOOP(STATISTICS)-ID}","id");
$template->setLoopField("{LOOP(STATISTICS)-IMP}","impressions");
$template->setLoopField("{LOOP(STATISTICS)-CLK}","clicks");
$template->setLoopField("{LOOP(STATISTICS)-MONEY}","clickvalue");
$template->closeLoop();
$template->setValue("{CLICK}",numberFormat($click,0));
$template->setValue("{IMP}",$total_impressions);
$template->setValue("{CTR}",$ctr);
$template->setValue("{NET}",$re);

$template->setValue("{AEARNING}",$aearning);
$template->setValue("{PEARNING}",$pearning);
$template->setValue("{TOT}",$tot);
if($currency_format=="$$")
$template->setValue("{CURRENCY}",$system_currency); 
else 
$template->setValue("{CURRENCY}",$currency_symbol);

$template->setValue("{SHOW}",$show);

$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$template->setValue("{SHOWSTATUS}",$showmessage);
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  

$template->setValue("{ENGINE_TITLE}",$engine_title);
$msg1= str_replace("{SHOWSTATUS}",$showmessage,$template->checkPubMsg(8968));
$template->setValue("{MSG1}",$msg1); 

$msg3= str_replace("{DATE1}",dateTimeFormat($adserver_upgradation_date),$template->checkPubMsg(8937));
$template->setValue("{MSG3}",$msg3);

$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

$template->setValue("{REFERRAL}",$referral_system);
$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding); 
//echo    $template->getPage();
eval('?>'.$template->getPage().'<?php ');
//
?>
