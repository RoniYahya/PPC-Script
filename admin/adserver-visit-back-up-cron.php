<?php
include_once("config.inc.php");

error_reporting(1);
$ini_error_status=ini_get('error_reporting');


$p_time=time();
$current_time=mktime(0,0,0,date("m",$p_time),date("d",$p_time),date("Y",$p_time));
$endTime=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));

$srvs_mname=$mysql->echo_one("select name from server_configurations where srv_type='1'");

echo "<br><br>Visit Statistics Of Master Server ".$srvs_mname."<br>";
do
{

$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
$srvs=1;
else
$srvs=$mysql->echo_one("select id from server_configurations where srv_type='1'");

if($srvs == 0 || $srvs =="")
$srvs=1;


$num_row_inserted=0;

if(!$stat_result=mysql_query("select e_time,last_id,status from statistics_updation where task='visit_back_master_".$srvs."'"))
		{
		echo "<br>Data not got from statistics_updation table<br>select e_time,last_id,status from statistics_updation where task='visit_back_master_".$srvs."'";
		break;
		}

    $stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[2];
	$start_time=$stat_row[0];
	
	
	
	if($update_check_ctrl==0)
	{
		if(!mysql_query("delete from publisher_visits_statistics where time >='$start_time' and serverid='$srvs'"))
		{
					echo "<br>Data clearing from publisher_visits_statistics failed<br>delete from publisher_visits_statistics where time >='$start_time' and serverid='$srvs'";
					break;
		}
	}
	
	
	if($update_check_ctrl==1)
	{
	if(!mysql_query("delete from publisher_daily_visits_statistics_master where time <=$start_time"))
	{
					echo "<br>Data clearing from publisher_daily_visits_statistics_master failed<br>delete from publisher_daily_visits_statistics_master where time <=$start_time";
					break;
	}
	
	
	
	}
	
	if($start_time==0)
	{
	$start_time=$mysql->echo_one("select min(time) from publisher_daily_visits_statistics_master");
	//$start_time=$start_time-3600;
	}
	else if($start_time!=0 && $update_check_ctrl!=0)
	{
	$start_time=mktime(0,0,0,date("m",$start_time),date("d",$start_time)+1,date("y",$start_time));
	
	}
	
	if($start_time>=$endTime && $update_check_ctrl==2)
	{
	
		echo "<br>BREAKING - LAST DAY STATISTICS IS COMPLETE<br>";
		break;
	}
	
	echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $endTime (".date("m d Y H i s",$endTime).")"."<br>";
	
	if(!mysql_query("update statistics_updation set status=0 where task='visit_back_master_".$srvs."'"))
	{
	
	 echo "<br>Visit Statistics updation failed<br>update statistics_updation set status=0 where task='visit_back_master_".$srvs."'";
		break;
	
	}
			
	
if(!$result=mysql_query("select pid,page_url,direct_hits,referred_hits,direct_clicks,referred_clicks,direct_impressions,referred_impressions,direct_invalid_clicks,referred_invalid_clicks,direct_fraud_clicks,referred_fraud_clicks,direct_repeated_click,referred_repeated_click,time,serverid FROM `publisher_daily_visits_statistics_master` WHERE time<=$start_time "))
{

echo "<br>Data retrieval failed from publisher_daily_visits_statistics_master table<br>select pid,page_url,direct_hits,referred_hits,direct_clicks,referred_clicks,direct_impressions,referred_impressions,direct_invalid_clicks,referred_invalid_clicks,direct_fraud_clicks,referred_fraud_clicks,direct_repeated_click,referred_repeated_click,time,serverid FROM `publisher_daily_visits_statistics_master` WHERE time<=$start_time";
break;


}
	
	
	$result_count=mysql_num_rows($result);
		
if($result_count > 0)
{




while($row=mysql_fetch_row($result))
{



if(mysql_query("insert into publisher_visits_statistics (`id`,`pid`,`page_url`,`direct_hits`,`referred_hits`,`direct_clicks`,`referred_clicks`,`direct_impressions`,`referred_impressions`,`direct_invalid_clicks`,`referred_invalid_clicks`,`direct_fraud_clicks`,`referred_fraud_clicks`,`direct_repeated_click`,`referred_repeated_click`,`time`,`serverid`) values('',$row[0],'$row[1]',$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15])"))
$num_row_inserted++;
else
			{
			
			echo "<br>Data insertion to publisher_visits_statistics failed<br>insert into publisher_visits_statistics (`id`,`pid`,`page_url`,`direct_hits`,`referred_hits`,`direct_clicks`,`referred_clicks`,`direct_impressions`,`referred_impressions`,`direct_invalid_clicks`,`referred_invalid_clicks`,`direct_fraud_clicks`,`referred_fraud_clicks`,`direct_repeated_click`,`referred_repeated_click`,`time`,`serverid`) values('',$row[0],'$row[1]',$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15])";
				break;



			}




}

}

	
	
if($result_count == $num_row_inserted)
{
mysql_query("update statistics_updation set e_time='$start_time',status=1 where task='visit_back_master_".$srvs."'"); 

if(mysql_query("delete from publisher_daily_visits_statistics_master where time <=$start_time"))
mysql_query("update statistics_updation set e_time='$start_time',status=2 where task='visit_back_master_".$srvs."'");





}
/*else
mysql_query("update statistics_updation set status=0 where task='visit_back_master_".$srvs."'");*/

	
flush();
echo mysql_error();
}while(1);











$master_data="";		
$result=mysql_query("select id,server_url,name from server_configurations where srv_type='2' and status='111'");
	
if(mysql_num_rows($result) >0)
{

while($row=mysql_fetch_row($result))
{
echo "<br><br>Visit Statistics Of Slave Server ".$row[2]."<br>";


$s_result=mysql_query("select e_time,last_id,status from statistics_updation where task='visit_back_slave_".$row[0]."'");
		

    $s_row=mysql_fetch_row($s_result);
	$status=$s_row[2];
	$times=$s_row[0];
	
	
	if($status==1)
	{
		mysql_query("delete from publisher_visits_statistics where time >='$times' and serverid='".$row[0]."'");
	}
	
	if(mysql_query("update statistics_updation set status=1 where task='visit_back_slave_".$row[0]."'"))
	{
if(!mysql_query("delete from publisher_visits_statistics where time >'".$times."' and serverid='".$row[0]."'"))
				{
					echo "<br>Data clearing from publisher_visits_statistics failed<br>delete from publisher_visits_statistics where time >'".$times."' and serverid='".$row[0]."'";
					break;
				}

     }
	 else
	 {
	 
	 echo "<br>Visit Statistics updation failed<br>update statistics_updation set status=1 where task='visit_back_slave_".$row[0]."'";
	 break;
	 
	 }
//echo $row[1]."slave-daily-visit-backup-cron.php?time=".$times."&status=".$status."<br>";

if($fp_master=fopen($row[1]."slave-daily-visit-backup-cron.php?time=".$times."&status=".$status,"r")) 
{



	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);

	
 fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-daily-visit-backup-cron.php?time=".$times."&status=".$status);
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
echo "<br>The requested URL was not found on this server<br>";
else
{

if($exp_mdata[0] != "Error")
{
echo "<br>Currently building data for ".$times." (".date("m d Y H i s",$times).") - ".$endTime." (".date("m d Y H i s",$endTime).")"."<br>";

mysql_query("update statistics_updation set e_time='".$exp_mdata[0]."',status=2 where task='visit_back_slave_".$row[0]."'");



}


echo $exp_mdata[1]."<br>";
}

}


else 
echo "<br>Failed to connect to Slave Server ".$row[2]."<br>";

}


}	
	


?>