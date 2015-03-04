<?php
set_time_limit(0);
require("ppc-reset-ads.php");
require("update-keyword-weightage.php");
require("adserver-slave-status-check-cron.php");
$p_time=time();
$current_time=$p_time-(24*60*60);
$current_time=mktime(date("H",$current_time),0,0,date("m",$current_time),date("d",$current_time),date("Y",$current_time));

error_reporting(1);
$ini_error_status=ini_get('error_reporting');



// creating click backup 
	
$update_check_ctrl=$mysql->echo_one("select status from statistics_updation where  task='click_backup'");
if($update_check_ctrl==2)//previous backup fully completed
{
	//echo "update_check_ctrl==2";
//	echo "update statistics_updation set  e_time='$p_time',status=0 where task='click_backup'";
	mysql_query("update statistics_updation set  e_time='$p_time',status=0 where task='click_backup'");
	//exit(0);
	mysql_query("insert ignore  into ppc_clicks(`id`, `uid`, `aid`, `kid`, `clickvalue`, `ip`, `time`, `pid`, `publisher_profit`, `pub_rid`, `pub_ref_profit`, `adv_rid`, `adv_ref_profit`, `bid`, `vid`,`country`,`current_time`,`browser`,`platform`,`version`,`user_agent`) select id,uid,aid, kid, clickvalue,ip, time,pid,publisher_profit,pub_rid,pub_ref_profit, adv_rid, adv_ref_profit,bid,vid,country ,`current_time`,browser,platform,version,user_agent FROM `ppc_daily_clicks` WHERE (time<$current_time) ");
	mysql_query("update statistics_updation set status=1 where task='click_backup'"); // partial completion
	mysql_query("delete from ppc_daily_clicks where time<$current_time");
	mysql_query("update statistics_updation set status=2 where task='click_backup'");//full completion

}
else
{
	$result=mysql_query("select e_time from statistics_updation where task='click_backup'");
	$row=mysql_fetch_row($result);
	
	$e_time=$row[0]-(24*60*60);
	if($update_check_ctrl==0)
	{
		//echo "update_check_ctrl==0";
		mysql_query("insert ignore into ppc_clicks(`id`, `uid`, `aid`, `kid`, `clickvalue`, `ip`, `time`, `pid`, `publisher_profit`, `pub_rid`, `pub_ref_profit`, `adv_rid`, `adv_ref_profit`, `bid`, `vid`,`country`,`current_time`,`browser`,`platform`,`version`,`user_agent`) select id,uid,aid, kid, clickvalue,ip, time,pid,publisher_profit,pub_rid,pub_ref_profit, adv_rid, adv_ref_profit,bid,vid,country,`current_time`,browser,platform,version,user_agent FROM `ppc_daily_clicks` WHERE (time<$e_time) ");
		
		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
		mysql_query("update statistics_updation set status=1 where task='click_backup'"); // partial completion
		mysql_query("delete from ppc_daily_clicks where time<$e_time");
		}
		if($update_check_ctrl==1)
		{
		//echo "update_check_ctrl==1";
		mysql_query("delete from ppc_daily_clicks where time<$e_time");
		}
		mysql_query("update statistics_updation set  e_time='$p_time',status=0 where task='click_backup'");
		mysql_query("insert ignore  into ppc_clicks(`id`, `uid`, `aid`, `kid`, `clickvalue`, `ip`, `time`, `pid`, `publisher_profit`, `pub_rid`, `pub_ref_profit`, `adv_rid`, `adv_ref_profit`, `bid`, `vid`,`county`,`current_time`,`browser`,`platform`,`version`,`user_agent`) select id,uid,aid, kid, clickvalue,ip, time,pid,publisher_profit,pub_rid,pub_ref_profit, adv_rid, adv_ref_profit,bid,vid,country,`current_time`,browser,platform,version,user_agent FROM `ppc_daily_clicks` WHERE (time<$current_time) ");
		mysql_query("delete from ppc_daily_clicks where time<$current_time");
		mysql_query("update statistics_updation set status=2 where task='click_backup'");
	}


	
// creating refferral backup 
$p_time=time();
$current_time=mktime(0,0,0,date("m",$p_time),date("d",$p_time),date("Y",$p_time));
$stat_result=mysql_query("select e_time,status from statistics_updation where  task='refferral_backup'");
$stat_row=mysql_fetch_row($stat_result);
$last_run_time=$stat_row[0];
$update_check_ctrl=$stat_row[1];
if($update_check_ctrl==2)//previous backup fully completed 
{
	refferralBackup($last_run_time,$current_time);
}
else
{
	$backup_completion=0;
	if($update_check_ctrl==0)
	{
//			echo "update_check_ctrl==0";
		if(mysql_query("insert ignore  into referral_visits (`id`,`ip`,`time`,`rid`,`host_name`,`ref_url`,`unique_hits`,`repeated_hits`) select id,ip,time,rid,host_name,ref_url,unique_hits,repeated_hits FROM `referral_daily_visits` WHERE time<=$last_run_time "))
			if(mysql_query("update statistics_updation set status=1 where task='refferral_backup'")) // partial completion
				if(mysql_query("delete from referral_daily_visits where time<=$last_run_time"))
					if(mysql_query("update statistics_updation set status=2 where task='refferral_backup'"))
						$backup_completion=1;
		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
	}
	if($update_check_ctrl==1)
	{
//			echo "update_check_ctrl==1";
		if(mysql_query("delete from referral_daily_visits where time<=$last_run_time"))
			if(mysql_query("update statistics_updation set status=2 where task='refferral_backup'"))
				$backup_completion=1;
		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
	}
	if($backup_completion==1)
	{
		refferralBackup($last_run_time,$current_time);
	}
}
	
function refferralBackup($last_run_time,$current_time)
{
	global $ini_error_status;
	if($last_run_time<$current_time)//this check is to prevent re-do of backup for last day 
		if(mysql_query("update statistics_updation set  e_time='$current_time',status=0 where task='refferral_backup'"))
			if(mysql_query("insert ignore  into referral_visits (`id`,`ip`,`time`,`rid`,`host_name`,`ref_url`,`unique_hits`,`repeated_hits`) select id,ip,time,rid,host_name,ref_url,unique_hits,repeated_hits FROM `referral_daily_visits` WHERE time<=$current_time "))
				if(mysql_query("update statistics_updation set status=1 where task='refferral_backup'")) // partial completion
					if(mysql_query("delete from referral_daily_visits where time<=$current_time"))
						mysql_query("update statistics_updation set status=2 where task='refferral_backup'");//full completion
	if($ini_error_status!=0)
	{
		echo mysql_error();
	}
	
}	



//if(date("G",time())%2==0)
	require("adserver-data-back-up-cron.php");


require("update-keyword-statistics.php");

// captcha status updation
$captcha_status_time=time();
mysql_query("update ppc_publishers set  captcha_status=0 where captcha_status=1 and captcha_time < $captcha_status_time ;");

if($account_migration==1)
{
	if($mysql->echo_one("select uid from ppc_users where common_account_id=0 limit 0,1") =='')
		if($mysql->echo_one("select uid from ppc_publishers where common_account_id=0 limit 0,1")=='')
			mysql_query("update ppc_settings set  value=0 where name='account_migration' ;");
}

// deleting visits older than 30 days 
$visit_time=mktime(0,0,0,date("m",time()),date("d",time())-30,date("Y",time()));
mysql_query("delete from  publisher_visits_statistics where time<'$visit_time'");
require("adserver-slave-hourly-visit-update-cron.php");
if(date("G",time())==0) // once a day
	require("adserver-visit-back-up-cron.php");



//if(date("G",time())==0) // once a day
	require("adserver-slave-optimize-cron.php");



if(date("G",time())==0) // once a day
{
	mysql_query("OPTIMIZE  TABLE advertiser_impression_hourly_master,publisher_impression_hourly_master");
	mysql_query("OPTIMIZE  TABLE advertiser_impression_daily,publisher_impression_daily");
	mysql_query("OPTIMIZE  TABLE ppc_daily_clicks,ppc_clicks");
	mysql_query("OPTIMIZE  TABLE publisher_daily_visits_statistics_master,publisher_visits_statistics");
	mysql_query("OPTIMIZE  TABLE referral_daily_visits,referral_visits");
	if(date("w",time())==0) // once a week
		{
			mysql_query("OPTIMIZE  TABLE ppc_ads,ppc_keywords");
			mysql_query("OPTIMIZE  TABLE ad_location_mapping,statistics_updation ,ppc_public_service_ads ,ppc_fraud_clicks ,ppc_custom_ad_block ");
		}
}


//*************************************Time Targeting**********************************
if($time_date_targetting==1)
{


$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
$time=time();
mysql_query("update ppc_ads set time_status='0'");


//$time_row=mysql_query("update  ppc_ads  left outer join time_targeting t on ppc_ads.id=t.aid  set time_status='1' where (date_tar_s <='$time' and '$time' < date_tar_e) or (date_tar_s is null and  date_tar_e is null and  date_flg is null ) and ppc_ads.status =1 ");

$time_row=mysql_query("update  ppc_ads  left outer join time_targeting t on ppc_ads.id=t.aid  set time_status='1' where (date_tar_s <='$today' and '$today' <= date_tar_e and date_flg=0) or (date_tar_s <='$time' and '$time' < date_tar_e and (date_flg=1 or date_flg=2 or date_flg=3)) or (date_tar_s is null and  date_tar_e is null and  date_flg is null ) and ppc_ads.status =1 ");

}
else
{
mysql_query("update ppc_ads set time_status='1'");
}


//*************************************Time Targeting end**********************************	


//TODO optimize slave tables.
	
mysql_query("update statistics_updation set e_time=".time()." where task='cron_run_time'");	
?>