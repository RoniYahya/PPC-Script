<?php
include_once("config.inc.php");



//error_reporting(E_ALL);
$ini_error_status=ini_get('error_reporting');

$endTime=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));


$srvs_mname=$mysql->echo_one("select name from server_configurations where srv_type='1'");

echo "<br><br><strong>Advertiser Hourly Impression Statistics Of Master Server ".$srvs_mname."</strong><br>";

do
{
$num_row_inserted=0;
$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
$srvs=1;
else
$srvs=$mysql->echo_one("select id from server_configurations where srv_type='1'");

if($srvs == 0 || $srvs =="")
$srvs=1;


if(!$stat_result=mysql_query("select e_time,last_id,status from statistics_updation where task='advertiser_impression_hourly_master_".$srvs."'"))
		{
		echo "<br>Data not got from statistics_updation table<br>select e_time,last_id,status from statistics_updation where task='advertiser_impression_hourly_master_".$srvs."'";
		break;
		}

    $stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[2];
	$start_time=$stat_row[0];
	
	if($start_time>=$endTime && $update_check_ctrl==2)
	{
	
		echo "<br>BREAKING - LAST HOUR STATISTICS IS COMPLETE";
		break;
	}
	
	if($start_time==0)
	{
	$start_time=$mysql->echo_one("select min(time) from advertiser_impression_hourly_master");
	
	
	if($start_time > 0)
	$start_time=$start_time-3600;
	else
	{
	echo "<br>BREAKING - NO DATA IN ADVERTISER HOURLY IMPRESSION MASTER TABLE";
	break;
	}
	}
	
echo "<br>Currently building data for $start_time (".date("d:M:Y H a",$start_time).") - $endTime (".date("d:M:Y H a",$endTime).")";
	
	
	if(!mysql_query("delete from advertiser_impression_daily where time >'$start_time' and server_id='$srvs'"))
				{
					echo "<br>Data clearing from advertiser_impression_daily failed<br>delete from advertiser_impression_daily where time >'$start_time' and server_id='$srvs'";
					break;
				}
	
if(!$adv_impression=mysql_query("select uid,aid,kid,time,sum(cnt),server_id from advertiser_impression_hourly_master where time >$start_time and time <=$endTime group by uid,aid,kid,time order by time"))
{
echo "<br>Data retrieval failed from advertiser_impression_hourly_master table<br>select uid,aid,kid,time,sum(cnt),server_id from advertiser_impression_hourly_master where time >$start_time and time <=$endTime group by uid,aid,kid,time order by time";
break;

}
	
	
	$adv_impression_count=mysql_num_rows($adv_impression);
		
if($adv_impression_count > 0)
{



while($adv_impression_row=mysql_fetch_row($adv_impression))
{



if(mysql_query("insert into advertiser_impression_daily (`id`,`uid`,`aid`,`kid`,`time`,`cnt`,`server_id`) value('0','$adv_impression_row[0]','$adv_impression_row[1]','$adv_impression_row[2]','$adv_impression_row[3]','$adv_impression_row[4]','$adv_impression_row[5]')"))
$num_row_inserted++;
else
			{
echo "<br>Data insertion to advertiser_impression_daily failed<br>insert into advertiser_impression_daily (`id`,`uid`,`aid`,`kid`,`time`,`cnt`,`server_id`) value('0','$adv_impression_row[0]','$adv_impression_row[1]','$adv_impression_row[2]','$adv_impression_row[3]','$adv_impression_row[4]','$adv_impression_row[5]')";
				break;
			}




}

}

	
	
if($adv_impression_count == $num_row_inserted)
{
if(mysql_query("update statistics_updation set e_time='$endTime',status=2 where task='advertiser_impression_hourly_master_".$srvs."'"))
mysql_query("delete from advertiser_impression_hourly_master where time <=$endTime");

}
//else
//mysql_query("update statistics_updation set e_time='0',status=2 where task='advertiser_impression_hourly_master_".$srvs."'");//full completion

	
flush();
echo mysql_error();
}while(1);


	
$master_data="";		
	
$result=mysql_query("select id,server_url,name from server_configurations where srv_type='2' and status='111'");
	
if(mysql_num_rows($result) >0)
{

while($row=mysql_fetch_row($result))
{

echo "<br><br><strong>Advertiser Hourly Impression Statistics Of Slave Server ".$row[2]."</strong><br>";

$s_result=mysql_query("select e_time,last_id,status from statistics_updation where task='advertiser_impression_hourly_slave_".$row[0]."'");
		

    $s_row=mysql_fetch_row($s_result);
	$status=$s_row[2];
	$times=$s_row[0];
	
	
if(!mysql_query("delete from advertiser_impression_daily where time >'".$times."' and server_id='".$row[0]."'"))
				{
					echo "<br>Data clearing from advertiser_impression_daily failed<br>delete from advertiser_impression_daily where time >'".$times."' and server_id='".$row[0]."'";
					break;
				}




if($fp_master=fopen($row[1]."slave-hourly-impressions-backup-cron.php?time=".$times."&status=".$status."&type=1","r")) 
{



	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);

	
 fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-hourly-impressions-backup-cron.php?time=".$times."&status=".$status."&type=1");
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 





if($master_data !="")
{
$exp_mdata=explode('-',$master_data);




if(count($exp_mdata) >2)
echo "<br>The requested slave URL was not found.";
else
{
if($exp_mdata[0] != "Error")
{
echo "<br>Currently building data for ".$times." (".date("d:M:Y H a",$times).") - ".$endTime." (".date("d:M:Y H a",$endTime).")";

mysql_query("update statistics_updation set e_time='".$exp_mdata[0]."',status=2 where task='advertiser_impression_hourly_slave_".$row[0]."'");



}


echo $exp_mdata[1];


}


}
else
echo "<br>Failed to connect to Slave Server ".$row[2];




}


}	
	
	
	//************************************************************************** Publisher *************************************************************************
	
	
echo "<br><br><strong>Publisher Hourly Impression Statistics Of Master Server ".$srvs_mname."</strong><br>";


do
{
$num_row_inserted=0;
$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
$srvs=1;
else
$srvs=$mysql->echo_one("select id from server_configurations where srv_type='1'");


if($srvs == 0 || $srvs =="")
$srvs=1;

if(!$stat_result=mysql_query("select e_time,last_id,status from statistics_updation where task='publisher_impression_hourly_master_".$srvs."'"))
		{
		echo "<br>Data not got from statistics_updation table<br>select e_time,last_id,status from statistics_updation where task='publisher_impression_hourly_master_".$srvs."'";
		break;
		}

    $stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[2];
	$start_time=$stat_row[0];
	
	if($start_time>=$endTime && $update_check_ctrl==2)
	{
		echo "<br>BREAKING - LAST HOUR STATISTICS IS COMPLETE";
		break;
	}
	
	if($start_time==0)
	{
	$start_time=$mysql->echo_one("select min(time) from publisher_impression_hourly_master");
	
	if($start_time > 0)
	$start_time=$start_time-3600;
	else
	{
	echo "<br>BREAKING - NO DATA IN PUBLISHER HOURLY IMPRESSION MASTER TABLE";
	break;
	}
	}
echo "<br>Currently building data for $start_time (".date("d:M:Y H a",$start_time).") - $endTime (".date("d:M:Y H a",$endTime).")";
	
	if(!mysql_query("delete from publisher_impression_daily where time >'$start_time' and server_id='$srvs'"))
				{
					echo "<br>Data clearing from publisher_impression_daily failed<br>delete from publisher_impression_daily where time >'$start_time' and server_id='$srvs'";
					break;
				}
	
if(!$adv_impression=mysql_query("select pid,bid,time,sum(cnt),server_id from publisher_impression_hourly_master where time >$start_time and time <=$endTime group by pid,bid,time order by time"))
{
echo "<br>Data retrieval failed from publisher_impression_hourly_master table<br>select pid,bid,time,sum(cnt),server_id from publisher_impression_hourly_master where time >$start_time and time <=$endTime group by pid,bid,time order by time";
break;

}
	
	
	$adv_impression_count=mysql_num_rows($adv_impression);
		
if($adv_impression_count > 0)
{


while($adv_impression_row=mysql_fetch_row($adv_impression))
{



if(mysql_query("insert into publisher_impression_daily (`id`,`pid`,`bid`,`time`,`cnt`,`server_id`) value('0','$adv_impression_row[0]','$adv_impression_row[1]','$adv_impression_row[2]','$adv_impression_row[3]','$adv_impression_row[4]')"))
$num_row_inserted++;
else
			{
echo "<br>Data insertion to publisher_impression_daily failed<br>insert into publisher_impression_daily (`id`,`pid`,`bid`,`time`,`cnt`,`server_id`) value('0','$adv_impression_row[0]','$adv_impression_row[1]','$adv_impression_row[2]','$adv_impression_row[3]','$adv_impression_row[4]')";
				break;
			}




}

}

	
	
if($adv_impression_count == $num_row_inserted)
{
if(mysql_query("update statistics_updation set e_time='$endTime',status=2 where task='publisher_impression_hourly_master_".$srvs."'"))
mysql_query("delete from publisher_impression_hourly_master where time <=$endTime");

}
//else
//mysql_query("update statistics_updation set e_time='0',status=2 where task='publisher_impression_hourly_master_".$srvs."'");//full completion

	
flush();
echo mysql_error();
}while(1);

	
	
$master_data="";	
	
$result=mysql_query("select id,server_url,name from server_configurations where srv_type='2' and status='111'");
	
if(mysql_num_rows($result) >0)
{

while($row=mysql_fetch_row($result))
{

echo "<br><br><strong>Publisher Hourly Impression Statistics Of Slave Server ".$row[2]."</strong><br>";


$s_result=mysql_query("select e_time,last_id,status from statistics_updation where task='publisher_impression_hourly_slave_".$row[0]."'");
		

    $s_row=mysql_fetch_row($s_result);
	$status=$s_row[2];
	$times=$s_row[0];
	
	
if(!mysql_query("delete from publisher_impression_daily where time >'".$times."' and server_id='".$row[0]."'"))
				{
					echo "<br>Data clearing from publisher_impression_daily failed<br>delete from publisher_impression_daily where time >'".$times."' and server_id='".$row[0]."'";
					break;
				}




if($fp_master=fopen($row[1]."slave-hourly-impressions-backup-cron.php?time=".$times."&status=".$status."&type=2","r")) 
{



	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);

	
 fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-hourly-impressions-backup-cron.php?time=".$times."&status=".$status."&type=2");
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 

if($master_data !="")
{
$exp_mdata=explode('-',$master_data);








if(count($exp_mdata) >2)
echo "<br>The requested slave URL was not found.<br>";
else
{

if($exp_mdata[0] != "Error")
{

echo "<br>Currently building data for ".$times." (".date("d:M:Y H a",$times).") - ".$endTime." (".date("d:M:Y H a",$endTime).")";
mysql_query("update statistics_updation set e_time='".$exp_mdata[0]."',status=2 where task='publisher_impression_hourly_slave_".$row[0]."'");


}


echo $exp_mdata[1];

}

}
else
echo "<br>Failed to connect to Slave Server ".$row[2];
}





}	
	
	
	
	
	


?>