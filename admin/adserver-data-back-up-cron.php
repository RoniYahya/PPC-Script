<?php
///Advertiser daily statistics



include_once("config.inc.php");


set_time_limit(0);
//error_reporting(E_ALL);


include("adserver-hourly-data-back-up-cron.php");

/*

mysql_query("TRUNCATE `advertiser_daily_statistics`;");
mysql_query("TRUNCATE `advertiser_monthly_statistics`;");
mysql_query("TRUNCATE `advertiser_yearly_statistics`;");
mysql_query("TRUNCATE `publisher_daily_statistics`;");
mysql_query("TRUNCATE `publisher_monthly_statistics`;");
mysql_query("TRUNCATE `publisher_yearly_statistics`;");
mysql_query("TRUNCATE `daily_referral_statistics`;");
mysql_query("TRUNCATE `monthly_referral_statistics`;");
mysql_query("TRUNCATE `yearly_referral_statistics`;");
echo mysql_error();

mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='advertiser_daily_statistics' LIMIT 1 ;");
mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='advertiser_monthly_statistics' LIMIT 1 ;");
mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='advertiser_yearly_statistics' LIMIT 1 ;");
mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='publisher_daily_statistics' LIMIT 1 ;");
mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='publisher_monthly_statistics' LIMIT 1 ;");
mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='publisher_yearly_statistics' LIMIT 1 ;");
mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='daily_referral_statistics' LIMIT 1 ;");
mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='monthly_referral_statistics' LIMIT 1 ;");
mysql_query("UPDATE `statistics_updation` SET `e_time` = '0',`status` = '2' WHERE `statistics_updation`.`task` ='yearly_referral_statistics' LIMIT 1 ;");


*/

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
//$time_start = microtime_float();
//echo $time_start;

//include_once("admin.header.inc.php");
$ctime=time();
$hour= date("H",$ctime);

//if ($hour%2 ==0)

//{

//*********************************************************************************************************************************************************************************

$crow=$mysql->echo_one("select count(id) from server_configurations");



echo "<br><br><strong>Advertiser Daily</strong><br>";



do
{
	if(!$stat_result=mysql_query("select e_time,last_id,status from statistics_updation where task='advertiser_daily_statistics'"))
		{
		echo "<br>Data not got from statistics_updation table<br>select e_time,last_id,status from statistics_updation where task='advertiser_daily_statistics'";
		break;
		}
	$stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[2];
	$start_time=$stat_row[0];

	
	$today_time=time();
	$yesterday=mktime(0,0,0,date("n",$today_time),date("j",$today_time),date("Y",$today_time));
	if($start_time>=$yesterday && $update_check_ctrl==2)
	{
		echo "<br>BREAKING - LAST DAY STATISTICS IS COMPLETE";
		break;
	}
	
		//echo $yesterday."<br>";exit(0);
	if($start_time==0)
	{

		if($min_time_imp=mysql_query("select min(time) from advertiser_impression_daily"))
		{					
			$min_time_imp_row=mysql_fetch_row($min_time_imp);
			$temp=$min_time_imp_row[0];
		}
		else
		{
			echo "<br>Query failed while getting minimum time from impression table<br>select min(time) from advertiser_impression_daily";
			break;
		}	
		if($min_time_clk=mysql_query("select min(time) from ppc_clicks"))
		{					
			$min_time_clk_row=mysql_fetch_row($min_time_clk);
			$temp1=$min_time_clk_row[0];
			
			
		}
		else
		{
			echo "<br>Query failed while getting minimum time from clicks table<br>select min(time) from ppc_clicks";
			break;
		}			
		echo "<br>Min time - imp = $temp = ".date("m d Y H i s",$temp);
		echo "<br>Min time - clk = $temp1 = ".date("m d Y H i s",$temp1);
		if($temp=='' && $temp1=='')
		{
		echo "<br>No impressions and clicks";
		break;
		}
		
		
		if(	$temp1!="")
		{
				if($temp1<$temp || $temp1==$temp || $temp=='')
				{
					$e_time=mktime(0,0,0,date("n",$temp1),date("j",$temp1)+1,date("Y",$temp1));
				}
		}
		if(	$temp!="")
		{
				if($temp<$temp1  || $temp1==$temp  || $temp1=='')
				{
					$e_time=mktime(0,0,0,date("n",$temp),date("j",$temp)+1,date("Y",$temp));
				}
		
		}
	
	
	}
	else
	{
		if($update_check_ctrl==2)
		{
			$e_time=mktime(0,0,0,date("n",$start_time),date("j",$start_time)+1,date("Y",$start_time));
		}
		else
		{
			$e_time=$stat_row[0];
			$start_time=mktime(0,0,0,date("n",$e_time),date("j",$e_time)-1,date("Y",$e_time));
		}

	}


if($e_time>$yesterday)
	{
		echo "<br>BREAKING - LAST DAY STATISTICS IS COMPLETE";
		break;
	}

$query_e_time=$e_time;
if($query_e_time == $yesterday)
{
$query_e_time=mktime(date("H",$today_time),0,0,date("n",$today_time),date("j",$today_time)-1,date("Y",$today_time));
}




	echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $query_e_time (".date("m d Y H i s",$query_e_time).")";



   
   
   

if($crow >0)
{

	if(!$stat_result_hourly=mysql_query("select e_time,status,task from statistics_updation where task like 'advertiser_impression_hourly_%'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status,task from statistics_updation where task like 'advertiser_impression_hourly_%'";
			break;
		}
	$hourly_err='';	
	while($stat_row_hourly=mysql_fetch_row($stat_result_hourly))
	{
	
	if( $stat_row_hourly[0]<=$e_time)
	{
		$hourly_err=$stat_row_hourly[2];
		break; //cannot build as the hourly table data is not complete.
	}	
	}	
	if(	$hourly_err!='')
 	{
			echo "<br>BREAKING - HOURLY ADVERTISER TASK INCOMPLETE - ".$hourly_err;
			break;
 	}
	
}	
	
	

	if(mysql_query("update statistics_updation set  e_time='$e_time',status=0 where task='advertiser_daily_statistics'"))
	{
		if(!mysql_query("delete from advertiser_daily_statistics where time='$e_time'"))
		{
			echo "<br>Data clearing from advertiser_daily_statistics failed<br>delete from advertiser_daily_statistics where time='$e_time'";
			break;
		}
	}
	else
	{
		echo "<br>Statistics updation failed<br>update statistics_updation set  e_time='$e_time',status=0 where task='advertiser_daily_statistics'";
		break;
	}

	if(!$result1=mysql_query("select uid,aid,kid,sum(cnt) from advertiser_impression_daily WHERE (time>$start_time and time<=$query_e_time)  group by uid,aid,kid"))
	{
		echo "<br>Data retrieval failed from advertiser_impression_daily table<br>select uid,aid,kid,sum(cnt) from advertiser_impression_daily WHERE (time>$start_time and time<=$query_e_time)  group by uid,aid,kid";
		break;
	}
	//if(mysql_num_rows($result)==0)
			//{
	if(!$result2=mysql_query("select uid,aid,kid,count(id),COALESCE(sum(clickvalue),0),COALESCE(sum(publisher_profit),0),COALESCE(sum(pub_ref_profit),0),COALESCE(sum(adv_ref_profit),0) from ppc_clicks WHERE (time>$start_time and time<=$e_time)  group by uid,aid,kid"))
	{
	
		echo "<br>Data retrieval failed from ppc_clicks table<br>select uid,aid,kid,count(id),COALESCE(sum(clickvalue),0),COALESCE(sum(publisher_profit),0),COALESCE(sum(pub_ref_profit),0),COALESCE(sum(adv_ref_profit),0) from ppc_clicks WHERE (time>$start_time and time<=$e_time)  group by uid,aid,kid";
		break;
	}
				//$clk_flag=1;
			//}

	$result_arr=array();
	while($row=mysql_fetch_row($result1))
	{
	$key=$row[2];
	
	$result_arr[$key][0]=$row[0];
	$result_arr[$key][1]=$row[1];
	$result_arr[$key][2]=$row[2];
	$result_arr[$key][3]=$row[3];
	$result_arr[$key][4]=0;
	$result_arr[$key][5]=0;
	$result_arr[$key][6]=0;
	
	$result_arr[$key][7]=0;
	$result_arr[$key][8]=0;
	
	}
	
	while($row=mysql_fetch_row($result2))
	{
	$key=$row[2];
	$result_arr[$key][0]=$row[0];
	$result_arr[$key][1]=$row[1];
	$result_arr[$key][2]=$row[2];
	if($result_arr[$key][3]=="")
	$result_arr[$key][3]=0;
	$result_arr[$key][4]=$row[3];
	$result_arr[$key][5]=$row[4];
	$result_arr[$key][6]=$row[5];
	
	$result_arr[$key][7]=$row[6];
	$result_arr[$key][8]=$row[7];
	}
	
	$num_row_inserted=0;
	$result_count=count($result_arr);


//echo $result_count."<br>";
	foreach ( $result_arr as $k => $v)
	{
			if(mysql_query("insert into advertiser_daily_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$v[0]','$v[1]',
	    '$v[2]','$v[3]',$e_time,'$v[4]','$v[5]','$v[6]','$v[7]','$v[8]')"))
			$num_row_inserted++;
		else
			{
				echo "<br>Data insertion to advertiser_daily_statistics failed<br>insert into advertiser_daily_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$v[0]','$v[1]',
	    '$v[2]','$v[3]',$e_time,'$v[4]','$v[5]','$v[6]','$v[7]','$v[8]')";
				break;
			}
	}

	
	if($num_row_inserted==$result_count)
	{
	
		if($e_time==$yesterday)
		{
			mysql_query("update statistics_updation set e_time='$e_time',status=1 where task='advertiser_daily_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST DAY STATISTICS";
			//echo "<br>Partial completion";
			break;
		}
		else
		mysql_query("update statistics_updation set e_time='$e_time',status=2 where task='advertiser_daily_statistics'");//full completion
	}
	else
	{
		
	}

flush();
echo mysql_error();
}while(1);










echo "<br><br><strong>Publisher Daily</strong><br>";

do
{
	if(!$stat_result=mysql_query("select e_time,last_id,status from statistics_updation where task='publisher_daily_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,last_id,status from statistics_updation where task='publisher_daily_statistics'";
			break;
		}
	$stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[2];
	$start_time=$stat_row[0];

	$today_time=time();
	$yesterday=mktime(0,0,0,date("n",$today_time),date("j",$today_time),date("Y",$today_time));
	if($start_time>=$yesterday && $update_check_ctrl==2)
	{
		echo "<br>BREAKING - LAST DAY STATISTICS IS COMPLETE";
		break;
	}
	
	if($start_time==0)
	{
		if($min_time_imp=mysql_query("select min(time) from publisher_impression_daily"))
		{					
			$min_time_imp_row=mysql_fetch_row($min_time_imp);
			$temp=$min_time_imp_row[0];
		}
		else
		{
			echo "<br>Query failed while getting minimum time from impression table<br>select min(time) from publisher_impression_daily";
			break;
		}	
		if($min_time_clk=mysql_query("select min(time) from ppc_clicks"))
		{					
			$min_time_clk_row=mysql_fetch_row($min_time_clk);
			$temp1=$min_time_clk_row[0];
		}
		else
		{
			echo "<br>Query failed while getting minimum time from clicks table<br>select min(time) from ppc_clicks";
			break;
		}			
		echo "<br>Min time - imp = $temp = ".date("m d Y H i s",$temp);
		echo "<br>Min time - clk = $temp1 = ".date("m d Y H i s",$temp1);
		if($temp=='' && $temp1=='')
		{
			echo "<br>No impressions and clicks";
			break;
		}

		
				if(	$temp1!="")
		{
				if($temp1<$temp  || $temp1==$temp || $temp=='')
				{
					$e_time=mktime(0,0,0,date("n",$temp1),date("j",$temp1)+1,date("Y",$temp1));
				}
		}
		if(	$temp!="")
		{
				if($temp<$temp1  || $temp1==$temp || $temp1=='')
				{
					$e_time=mktime(0,0,0,date("n",$temp),date("j",$temp)+1,date("Y",$temp));
				}
		
		}


	}
	else
	{
		if($update_check_ctrl==2)
		{
			$e_time=mktime(0,0,0,date("n",$start_time),date("j",$start_time)+1,date("Y",$start_time));
		}
		else
		{
			$e_time=$stat_row[0];
			$start_time=mktime(0,0,0,date("n",$e_time),date("j",$e_time)-1,date("Y",$e_time));
		}

	}
	
	
	
	
	if($e_time>$yesterday)
	{
		echo "<br>BREAKING - LAST DAY STATISTICS IS COMPLETE";
		break;
	}

$query_e_time=$e_time;
if($query_e_time == $yesterday)
{
$query_e_time=mktime(date("H",$today_time),0,0,date("n",$today_time),date("j",$today_time)-1,date("Y",$today_time));
}


	echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $query_e_time (".date("m d Y H i s",$query_e_time).")";

	 
	 
	 
	 
	 
if($crow >0)
{	 
	if(!$stat_result_hourly=mysql_query("select e_time,status,task from statistics_updation where task like 'publisher_impression_hourly_%'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status,task from statistics_updation where task like 'publisher_impression_hourly_%'";
			break;
		}
	$hourly_err='';	
	while($stat_row_hourly=mysql_fetch_row($stat_result_hourly))
	{
	if( $stat_row_hourly[0]<=$e_time)
	{
		$hourly_err=$stat_row_hourly[2];
		break; //cannot build as the hourly table data is not complete.
	}	
	}	
	if(	$hourly_err!='')
 	{
			echo "<br>BREAKING - HOURLY PUBLISHER TASK INCOMPLETE - ".$hourly_err;
			break;
 	}
	
}	
	
	
	
	
	
	
	
	if(mysql_query("update statistics_updation set  e_time='$e_time',status=0 where task='publisher_daily_statistics'"))
	{
	if(!mysql_query("delete from publisher_daily_statistics where time='$e_time'"))
		{
			echo "<br>Data clearing from publisher_daily_statistics failed<br>delete from publisher_daily_statistics where time='$e_time'";
			break;
		}
	}
	else
	{
		echo "<br>Statistics updation failed<br>update statistics_updation set  e_time='$e_time',status=0 where task='publisher_daily_statistics'";
		break;
	}
	
	if(!$result1=mysql_query("select pid,bid,sum(cnt) from publisher_impression_daily WHERE (time>$start_time and time<=$query_e_time)  group by pid,bid"))
	{
		echo "<br>Data retrieval failed from publisher_impression_daily table<br>select pid,bid,sum(cnt) from publisher_impression_daily WHERE (time>$start_time and time<=$query_e_time)  group by pid,bid";
		break;
	}
	//if(mysql_num_rows($result)==0)
			//{
	if(!$result2=mysql_query("select pid,bid,count(id),COALESCE(sum(clickvalue),0),COALESCE(sum(publisher_profit),0),COALESCE(sum(pub_ref_profit),0),COALESCE(sum(adv_ref_profit),0) from ppc_clicks WHERE (time>$start_time and time<=$e_time)  group by pid,bid"))
	{
		echo "<br>Data retrieval failed from ppc_clicks table<br>select pid,bid,count(id),COALESCE(sum(clickvalue),0),COALESCE(sum(publisher_profit),0),COALESCE(sum(pub_ref_profit),0),COALESCE(sum(adv_ref_profit),0) from ppc_clicks 
WHERE (time>$start_time and time<=$e_time)  group by pid,bid";
		break;
	}
			//$clk_flag=1;
			//}

	$result_arr=array();
	while($row=mysql_fetch_row($result1))
	{
	$key=$row[0].":".$row[1];
	
	$result_arr[$key][0]=$row[0];
	$result_arr[$key][1]=$row[1];
	$result_arr[$key][2]=$row[2];
	$result_arr[$key][3]=0;
	$result_arr[$key][4]=0;
	$result_arr[$key][5]=0;
		
	$result_arr[$key][6]=0;
	$result_arr[$key][7]=0;
	}
	
	while($row=mysql_fetch_row($result2))
	{
	$key=$row[0].":".$row[1];

	$result_arr[$key][0]=$row[0];
	$result_arr[$key][1]=$row[1];
	if($result_arr[$key][2]=="")
	$result_arr[$key][2]=0;
	$result_arr[$key][3]=$row[2];
	$result_arr[$key][4]=$row[3];
	$result_arr[$key][5]=$row[4];
		
	$result_arr[$key][6]=$row[5];
	$result_arr[$key][7]=$row[6];
	}
	
	$num_row_inserted=0;
	$result_count=count($result_arr);
	foreach ( $result_arr as $k => $v)
	{
			if(mysql_query("insert into publisher_daily_statistics (`id`,`uid`, `bid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) 
values ('0','$v[0]','$v[1]','$v[2]',$e_time,'$v[3]','$v[4]','$v[5]','$v[6]','$v[7]')"))
			$num_row_inserted++;
		else
			{
				echo "<br>Data insertion to publisher_daily_statistics failed<br>insert into publisher_daily_statistics (`id`,`uid`, `bid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) 
values ('0','$v[0]','$v[1]','$v[2]',$e_time,'$v[3]','$v[4]','$v[5]','$v[6]','$v[7]')";
				break;
			}
	}



	if($num_row_inserted==$result_count)
	{
	if($e_time==$yesterday)
		{
			mysql_query("update statistics_updation set e_time='$e_time',status=1 where task='publisher_daily_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST DAY STATISTICS";
			//echo "<br>Partial completion";
			break;
		}
		else
			mysql_query("update statistics_updation set e_time='$e_time',status=2 where task='publisher_daily_statistics'");//full completion
	}
	else
	{
		//remains in status 0  ==> incomplete
	}
echo mysql_error();
flush();
}while(1);










//**********************************************************************************************************************************************************************************



?>

<?php


///advertiser monthly statistics

echo "<br><br><strong>Advertiser Monthly</strong><br>";


do
{

	if(!$stat_result=mysql_query("select e_time,status from statistics_updation where task='advertiser_monthly_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='advertiser_monthly_statistics'";
			break;
		}
	$stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[1];
	
	$start_time=$stat_row[0];

	$this_month_time=time();
	$this_month_first_day=mktime(0,0,0,date("n",$this_month_time),1,date("Y",$this_month_time));
	
	if($start_time>=$this_month_first_day && $update_check_ctrl==2)
	{
		echo "<br>BREAKING - LAST MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
		break;
	}
	
	if($update_check_ctrl==2)
		{
			//$delete_time=$start_time-(30*24*60*60);
			$delete_time=mktime(0,0,0,date("n",$start_time),date("j",$start_time)-32,date("Y",$start_time));
 			if(mysql_query("delete from advertiser_daily_statistics where time<'$delete_time'"))// clear the data from daily table
			{
				echo "<br>Old data deleted from advertiser_daily_statistics<br>delete from advertiser_daily_statistics where time<'$delete_time'";
			}
			else
			{
				echo "<br>Old data deletion from advertiser_daily_statistics failed<br>delete from advertiser_daily_statistics where time<'$delete_time'";
			}
		}
	

	if($start_time==0)
	{
		if($min_time_daily=mysql_query("select min(time) from advertiser_daily_statistics"))
		{					
			$min_time_daily_row=mysql_fetch_row($min_time_daily);

			echo "<br>Min time = $min_time_daily_row[0] = ".date("m d Y H i s",$min_time_daily_row[0]);
			if($min_time_daily_row[0]=='')
			{
				echo "<br>No data in the advertiser_daily_statistics table";
				break;
			}
			else
			{
				$e_time=mktime(0,0,0,date("n",$min_time_daily_row[0]),1,date("Y",$min_time_daily_row[0]));
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from advertiser_daily_statistics table<br>select min(time) from advertiser_daily_statistics";
			break;
		}	

	}
	else
	{
		if($update_check_ctrl==2)
		{
			$e_time=mktime(0,0,0,date("n",$start_time)+1,date("j",$start_time),date("Y",$start_time));
		}
		else
		{
			$e_time=$start_time;
			$start_time=mktime(0,0,0,date("n",$start_time)-1,date("j",$start_time),date("Y",$start_time));
		}

	}

	//$e_time=mktime(0,0,0,date("n",$start_time),1,date("Y",$start_time));	//ending of the month for which stat is built
	
	echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $e_time (".date("m d Y H i s",$e_time).")";


	if(!$stat_result_daily=mysql_query("select e_time,status from statistics_updation where task='advertiser_daily_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='advertiser_daily_statistics'";
			break;
		}
	$stat_row_daily=mysql_fetch_row($stat_result_daily);
	if( $stat_row_daily[0]<=$e_time)
	{
		echo "<br>BREAKING - DAILY TABLE INCOMPLETE";
		break; //cannot build as the daily table data is not complete.
	}	
	
	$tmp_clk=0;
	$tmp_imp=0;

	//echo "<br>Update control flag = $update_check_ctrl";

	if(mysql_query("update statistics_updation set status=0,e_time='$e_time' where task='advertiser_monthly_statistics'"))
		{//partial completion
			if(!mysql_query("delete from advertiser_monthly_statistics where time='$e_time'"))
				{
					echo "<br>Data clearing from advertiser_monthly_statistics failed<br>delete from advertiser_monthly_statistics where time='$e_time'";
					break;
				}
		}
	else
		{
			echo "<br>Statistics updation failed<br>update statistics_updation set status=0,e_time='$e_time' where task='advertiser_monthly_statistics'";
			break;
		}
	if(!$result=mysql_query("select uid,aid,kid,sum(impression),sum(clk_count),sum(money_spent),sum(publisher_profit),sum(pub_ref_profit),sum(adv_ref_profit) from advertiser_daily_statistics WHERE (time<=$e_time and time>$start_time)  group by uid,aid,kid"))
		{
			echo "<br>Data retrieval failed from advertiser_daily_statistics table<br>select uid,aid,kid,sum(impression),sum(clk_count),sum(money_spent),sum(publisher_profit),sum(pub_ref_profit),sum(adv_ref_profit) from advertiser_daily_statistics WHERE (time<=$e_time and time>$start_time)  group by uid,aid,kid";
			break;
		}
	
	while($row=mysql_fetch_row($result))
	{
		if(!mysql_query("insert into advertiser_monthly_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time,'$row[4]','$row[5]','$row[6]','$row[7]','$row[8]')"))
			{
				echo"<br>Data insertion to advertiser_monthly_statistics failed<br>insert into advertiser_monthly_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time,'$row[4]','$row[5]','$row[6]','$row[7]','$row[8]')";
				break;
				}
		$tmp_clk=$tmp_clk+$row[4];
		$tmp_imp=$tmp_imp+$row[3];
	}
	$mnthly_stat=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where time='$e_time'");
	$monthly_stat=mysql_fetch_row($mnthly_stat);
	if(($tmp_clk==$monthly_stat[1]) && ($tmp_imp==$monthly_stat[0]))
	{
			mysql_query("update statistics_updation set status=2,e_time='$e_time' where task='advertiser_monthly_statistics'");//full completion
		if($e_time==$this_month_first_day)
		{
		//	mysql_query("update statistics_updation set status=1,e_time='$e_time' where task='advertiser_monthly_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
			break;
		}
	//	else
	}

echo mysql_error();
flush();
}while (1);
?> 


<?php
///publisher monthly statistics

echo "<br><br><strong>Publisher Monthly</strong><br>";

do
{

	if(!$stat_result=mysql_query("select e_time,status from statistics_updation where task='publisher_monthly_statistics'"))
	{
		echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='publisher_monthly_statistics'";
		break;
	}
	$stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[1];

	$start_time=$stat_row[0];

	$this_month_time=time();
	$this_month_first_day=mktime(0,0,0,date("n",$this_month_time),1,date("Y",$this_month_time));
	if($start_time>=$this_month_first_day && $update_check_ctrl==2)
	{
	echo "<br>BREAKING - LAST MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
	break;
	}
	
		if($update_check_ctrl==2)
		{
			//$delete_time=$start_time-(30*24*60*60);
			$delete_time=mktime(0,0,0,date("n",$start_time),date("j",$start_time)-32,date("Y",$start_time));
 			if(mysql_query("delete from publisher_daily_statistics where time<'$delete_time'"))// clear the data from daily table
			{
				echo "<br>Old data deleted from publisher_daily_statistics<br>delete from publisher_daily_statistics where time<'$delete_time'";
			}
			else
			{
				echo "<br>Old data deletion from publisher_daily_statistics failed<br>delete from publisher_daily_statistics where time<'$delete_time'";
			}
		}


	if($start_time==0)
	{
		if($min_time_daily=mysql_query("select min(time) from publisher_daily_statistics"))
		{					
			$min_time_daily_row=mysql_fetch_row($min_time_daily);

			echo "<br>Min time = $min_time_daily_row[0] = ".date("m d Y H i s",$min_time_daily_row[0]);
			if($min_time_daily_row[0]=='')
			{
				echo "<br>No data in the publisher_daily_statistics table";
				break;
			}
			else
			{
				$e_time=mktime(0,0,0,date("n",$min_time_daily_row[0]),1,date("Y",$min_time_daily_row[0]));
			}

		}
		else
		{
			echo "<br>Query failed while getting minimum time from publisher_daily_statistics table<br>select min(time) from publisher_daily_statistics";
			break;
		}	

	}
	else
	{
		if($update_check_ctrl==2)
		{
			$e_time=mktime(0,0,0,date("n",$start_time)+1,date("j",$start_time),date("Y",$start_time));
		}
		else
		{
			$e_time=$start_time;
			$start_time=mktime(0,0,0,date("n",$start_time)-1,date("j",$start_time),date("Y",$start_time));
		}

	}

	//$e_time=mktime(0,0,0,date("n",$start_time),1,date("Y",$start_time));

	echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $e_time (".date("m d Y H i s",$e_time).")";
	
	if(!$stat_result_daily=mysql_query("select e_time,status from statistics_updation where task='publisher_daily_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='publisher_daily_statistics'";
			break;
		}
	$stat_row_daily=mysql_fetch_row($stat_result_daily);
	
	if($stat_row_daily[0]<=$e_time )
	{
		echo "<br>BREAKING - DAILY TABLE INCOMPLETE";
		break; //cannot build as the daily table data is not complete.

	}	

	$tmp_clk=0;
	$tmp_imp=0;

	//echo "<br>Update control flag = $update_check_ctrl";

	if(mysql_query("update statistics_updation set status=0,e_time='$e_time' where task='publisher_monthly_statistics'"))
		{//partial completion
			if(!mysql_query("delete from publisher_monthly_statistics where time='$e_time'"))
				{
				echo "<br>Data clearing failed from publisher_monthly_statistics<br>delete from publisher_monthly_statistics where time='$e_time'";
				break;
				}
		}
	else
		{
			echo "<br>Statistics updation failed<br>update statistics_updation set status=0,e_time='$e_time' where task='publisher_monthly_statistics'";
			break;
		}
	if(!$result=mysql_query("select uid,bid,sum(impression),sum(clk_count),sum(money_spent),sum(publisher_profit),sum(pub_ref_profit),sum(adv_ref_profit) from publisher_daily_statistics WHERE (time<=$e_time and time>$start_time)  group by uid,bid"))
		{
			echo "<br>Data retrieval failed from publisher_daily_statistics table<br>select uid,bid,sum(impression),sum(clk_count),sum(money_spent),sum(publisher_profit),sum(pub_ref_profit),sum(adv_ref_profit) from publisher_daily_statistics WHERE (time<=$e_time and time>$start_time)  group by uid,bid";
			break;
		}  //uid denotes pid
		

	while($row=mysql_fetch_row($result))
	{
		if(!mysql_query("insert into publisher_monthly_statistics (`id`,`uid`, `bid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]',$e_time,'$row[3]','$row[4]','$row[5]','$row[6]','$row[7]')"))
			{
				echo "<br>Data insertion to publisher_monthly_statistics failed<br>insert into publisher_monthly_statistics (`id`,`uid`, `bid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]',$e_time,'$row[3]','$row[4]','$row[5]','$row[6]','$row[7]')";
				break;
				}
		$tmp_clk=$tmp_clk+$row[3];
		$tmp_imp=$tmp_imp+$row[2];
	}
	$mnthly_stat=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0) from publisher_monthly_statistics where time='$e_time'");
	$monthly_stat=mysql_fetch_row($mnthly_stat);
	if(($tmp_clk==$monthly_stat[1]) && ($tmp_imp==$monthly_stat[0]))
	{
			mysql_query("update statistics_updation set status=2,e_time='$e_time' where task='publisher_monthly_statistics'");//full completion
		if($e_time==$this_month_first_day)
		{
		//	mysql_query("update statistics_updation set status=1,e_time='$e_time' where task='publisher_monthly_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
			break;
		}
	//	else
			
	}
	
echo mysql_error();
flush();
}while (1);
?>

<?php
//include("extended-config.inc.php");  
//include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
///advertiser yearly statistics

echo "<br><br><strong>Advertiser Yearly</strong><br>";

do
{

	if(!$stat_result=mysql_query("select e_time,status from statistics_updation where task='advertiser_yearly_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='advertiser_yearly_statistics'";
			break;
		}
	$stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[1];
	
	$start_time=$stat_row[0];

	$this_year_time=time();
	
	$this_year_first_day=mktime(0,0,0,1,1,date("Y",$this_year_time));
	
	
	if($start_time>=$this_year_first_day && $update_check_ctrl==2)
	{
	echo "<br>BREAKING - LAST YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
	break;
	}
	
	
	
	
	
	
	if($update_check_ctrl==2)
		{
			//$delete_time=$start_time-(365*24*60*60);
			$delete_time=mktime(0,0,0,1,1-366,date("Y",$start_time));
				
 			if(mysql_query("delete from advertiser_monthly_statistics where time<'$delete_time'"))// clear the data from monthly table
			{
				echo "<br>Old data deleted from advertiser_monthly_statistics<br>delete from advertiser_monthly_statistics where time<'$delete_time'";
			}
			else
			{
				echo "<br>Old data deletion from advertiser_monthly_statistics failed<br>delete from advertiser_monthly_statistics where time<'$delete_time'";
			}
		}


	if($start_time==0)
	{
		if($min_time_monthly=mysql_query("select min(time) from advertiser_monthly_statistics"))
		{					
			$min_time_monthly_row=mysql_fetch_row($min_time_monthly);
			echo "<br>Min time = $min_time_monthly_row[0] = ".date("m d Y H i s",$min_time_monthly_row[0]);
			if($min_time_monthly_row[0]=='')
			{
				echo "<br>No data in the advertiser_monthly_statistics table";
				break;
			}
			else
			{
				$e_time=mktime(0,0,0,1,1,date("Y",$min_time_monthly_row[0]));
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from advertiser_monthly_statistics table<br>select min(time) from advertiser_monthly_statistics";
			break;
		}	

	}
	else
	{
		
		if($update_check_ctrl==2)
		{
			$e_time=mktime(0,0,0,1,1,date("Y",$start_time)+1);
		}
		else
		{
			$e_time=$start_time;

			$start_time=mktime(0,0,0,1,1,date("Y",$start_time)-1);
		}

	}

	//$e_time=$start_time;
	echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $e_time (".date("m d Y H i s",$e_time).")";
	
	if(!$stat_result_monthly=mysql_query("select e_time,status from statistics_updation where task='advertiser_monthly_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='advertiser_monthly_statistics'";
			break;
		}
	$stat_row_monthly=mysql_fetch_row($stat_result_monthly);
	
	//if($stat_row_monthly[0]>$start_time && $stat_row_monthly[0]<=$e_time && $stat_row_monthly[1]!=2)
	
	
	
	if(($stat_row_monthly[0]==$e_time && $stat_row_monthly[1]!=2) || ($stat_row_monthly[0]<$e_time) )
	{
		echo "<br>BREAKING - MONTHLY TABLE INCOMPLETE";
		break; //cannot build as the monthly table data is not complete.

	}	
	

	
	
	// creating impression backup
	$tmp_clk=0;
	$tmp_imp=0;

	//echo "<br>Update control flag = $update_check_ctrl";

	if(mysql_query("update statistics_updation set status=0,e_time='$e_time' where task='advertiser_yearly_statistics'"))
		{//partial completion
		if(!mysql_query("delete from advertiser_yearly_statistics where time='$e_time'"))
			{
				echo "<br>Data clearing from advertiser_yearly_statistics failed<br>delete from advertiser_yearly_statistics where time='$e_time'";
				break;
			}
		}
	else
		{
			echo "<br>Statistics updation failed<br>update statistics_updation set status=0,e_time='$e_time' where task='advertiser_yearly_statistics'";
			break;
		}
	if(!$result=mysql_query("select uid,aid,kid,sum(impression),sum(clk_count),sum(money_spent),sum(publisher_profit),sum(pub_ref_profit),sum(adv_ref_profit) from advertiser_monthly_statistics WHERE (time<=$e_time and time>$start_time)  group by uid,aid,kid"))
		{
			echo "<br>Data retrieval failed from advertiser_monthly_statistics table<br>select uid,aid,kid,sum(impression),sum(clk_count),sum(money_spent),sum(publisher_profit),sum(pub_ref_profit),sum(adv_ref_profit) from advertiser_monthly_statistics WHERE (time<=$e_time and time>$start_time)  group by uid,aid,kid";
			break;
		}
	while($row=mysql_fetch_row($result))
	{
		if(!mysql_query("insert into advertiser_yearly_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time,'$row[4]','$row[5]','$row[6]','$row[7]','$row[8]')"))
			{
				echo "<br>Data insertion to advertiser_yearly_statistics failed<br>insert into advertiser_yearly_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time,'$row[4]','$row[5]','$row[6]','$row[7]','$row[8]')";
				break;
			}
		$tmp_clk=$tmp_clk+$row[4];
		$tmp_imp=$tmp_imp+$row[3];
		//echo "<br>insert into advertiser_yearly_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time,'$row[4]','$row[5]','$row[6]','$row[7]','$row[8]')";
	}
	$mnthly_stat=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0) from advertiser_yearly_statistics where time='$e_time'");
	$yearly_stat=mysql_fetch_row($mnthly_stat);
	if(($tmp_clk==$yearly_stat[1]) && ($tmp_imp==$yearly_stat[0]))
	{
			mysql_query("update statistics_updation set status=2,e_time='$e_time' where task='advertiser_yearly_statistics'");//full completion
		if($e_time==$this_year_first_day)
		{
		//	mysql_query("update statistics_updation set status=1,e_time='$e_time' where task='advertiser_yearly_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			//echo "<br>Partial completion";
			break;
		}
	//	else
}
echo mysql_error();
flush();
}while (1);
?>




<?php
///publisher yearly statistics

echo "<br><br><strong>Publisher Yearly</strong><br>";


do
{

	if(!$stat_result=mysql_query("select e_time,status from statistics_updation where task='publisher_yearly_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='publisher_yearly_statistics'";
			break;
		}
	$stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[1];

	$start_time=$stat_row[0];

	$this_year_time=time();
	$this_year_first_day=mktime(0,0,0,1,1,date("Y",$this_year_time));
	
	
	if($start_time>=$this_year_first_day && $update_check_ctrl==2)
	{
	echo "<br>BREAKING - LAST YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
	break;
	}
	
	
	if($update_check_ctrl==2)
			{
				//$delete_time=$start_time-(365*24*60*60);
				$delete_time=mktime(0,0,0,1,1-366,date("Y",$start_time));
				if(mysql_query("delete from publisher_monthly_statistics where time<'$delete_time'"))// clear the data from monthly table
				{
					echo "<br>Old data deleted from publisher_monthly_statistics<br>delete from publisher_monthly_statistics where time<'$delete_time'";
				}
				else
				{
					echo "<br>Old data deletion from publisher_monthly_statistics failed<br>delete from publisher_monthly_statistics where time<'$delete_time'";
				}
			}
	//if($start_time>=$this_year_first_day && $update_check_ctrl==2)
	//{
		//echo "<br>BREAKING - LAST YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
		//break;
	//}

	if($start_time==0)
	{
		if($min_time_monthly=mysql_query("select min(time) from publisher_monthly_statistics"))
		{					
			$min_time_monthly_row=mysql_fetch_row($min_time_monthly);
			echo "<br>Min time = $min_time_monthly_row[0] = ".date("m d Y H i s",$min_time_monthly_row[0]);
			if($min_time_monthly_row[0]=='')
			{
				echo "<br>No data in the publisher_monthly_statistics table";
				break;
			}
			else
			{
				$e_time=mktime(0,0,0,1,1,date("Y",$min_time_monthly_row[0]));
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from publisher_monthly_statistics table<br>select min(time) from publisher_monthly_statistics";
			break;
		}	

	}
	else
	{

		if($update_check_ctrl==2)
			{
			$e_time=mktime(0,0,0,1,1,date("Y",$start_time)+1);
			}
		else
			{
			$e_time=$start_time;
				$start_time=mktime(0,0,0,1,1,date("Y",$start_time)-1);
			}

	}

	//$e_time=$start_time;
	
	echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $e_time (".date("m d Y H i s",$e_time).")";

	if(!$stat_result_monthly=mysql_query("select e_time,status from statistics_updation where task='publisher_monthly_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='publisher_monthly_statistics'";
			break;
		}
	$stat_row_monthly=mysql_fetch_row($stat_result_monthly);
	
	
	
	
	//if($stat_row_monthly[0]>$start_time && $stat_row_monthly[0]<=$e_time && $stat_row_monthly[1]!=2)
	if(($stat_row_monthly[0]==$e_time && $stat_row_monthly[1]!=2) || ($stat_row_monthly[0]<$e_time) )
	{
		echo "<br>BREAKING - MONTHLY TABLE INCOMPLETE";
		break; //cannot build as the monthly table data is not complete.

	}	



	// creating impression backup
	$tmp_clk=0;
	$tmp_imp=0;

	//echo "<br>Update control flag = $update_check_ctrl";

	if(mysql_query("update statistics_updation set status=0,e_time='$e_time' where task='publisher_yearly_statistics'"))
		{//partial completion
		if(!mysql_query("delete from publisher_yearly_statistics where time='$e_time'"))
			{
				echo "<br>Data clearing failed from publisher_yearly_statistics<br>delete from publisher_yearly_statistics where time='$e_time'";
				break;
			}
		}
	else
		{
			echo "<br>Statistics updation failed<br>update statistics_updation set status=0,e_time='$e_time' where task='publisher_yearly_statistics'";
			break;
		}
	if(!$result=mysql_query("select uid,bid,sum(impression),sum(clk_count),sum(money_spent),sum(publisher_profit),sum(pub_ref_profit),sum(adv_ref_profit) from publisher_monthly_statistics WHERE (time<=$e_time and time>$start_time)  group by uid,bid"))
		{
		echo "<br>Data retrieval failed from publisher_monthly_statistics table<br>select uid,bid,sum(impression),sum(clk_count),sum(money_spent),sum(publisher_profit),sum(pub_ref_profit),sum(adv_ref_profit) from publisher_monthly_statistics WHERE (time<=$e_time and time>$start_time)  group by uid,bid";
		break;
		}
	while($row=mysql_fetch_row($result))
	{
		if(!mysql_query("insert into publisher_yearly_statistics (`id`,`uid`, `bid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]',$e_time,'$row[3]','$row[4]','$row[5]','$row[6]','$row[7]')"))
			{
				echo "<br>Data insertion to publisher_yearly_statistics failed<br>insert into publisher_yearly_statistics (`id`,`uid`, `bid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]',$e_time,'$row[3]','$row[4]','$row[5]','$row[6]','$row[7]')";
				break;
			}
		$tmp_clk=$tmp_clk+$row[3];
		$tmp_imp=$tmp_imp+$row[2];
//		echo "<br>insert into publisher_yearly_statistics (`id`,`uid`, `bid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`) values ('0','$row[0]','$row[1]',	    '$row[2]',$e_time,'$row[3]','$row[4]','$row[5]')";
	}
	$mnthly_stat=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0) from publisher_yearly_statistics where time='$e_time'");
	$yearly_stat=mysql_fetch_row($mnthly_stat);
	if(($tmp_clk==$yearly_stat[1]) && ($tmp_imp==$yearly_stat[0]))
	{
			mysql_query("update statistics_updation set status=2,e_time='$e_time' where task='publisher_yearly_statistics'");//full completion
		if($e_time==$this_year_first_day)
		{
		//	mysql_query("update statistics_updation set status=1,e_time='$e_time' where task='publisher_yearly_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			//echo "<br>Partial completion";
			break;
		}
	//	else
	}
echo mysql_error();
flush();
}while (1);


//if ($hour%2 ==0)

//{
echo "<br><br><strong>Referral Daily</strong><br>";

do
{
    if(!$stat_result=mysql_query("select e_time,last_id,status from statistics_updation where task='daily_referral_statistics'"))
        {
        echo "<br>Data not got from statistics_updation table<br>select e_time,last_id,status from statistics_updation where task='daily_referral_statistics'";
        break;
        }
    $stat_row=mysql_fetch_row($stat_result);
    $update_check_ctrl=$stat_row[2];
    $start_time=$stat_row[0];//$mysql->echo_one("select e_time from statistics_updation where  task='daily_referral_statistics'");

    //echo $start_time."<br>";
    $today_time=time();
    $yesterday=mktime(0,0,0,date("n",$today_time),date("j",$today_time),date("Y",$today_time));
    if($start_time>=$yesterday && $update_check_ctrl==2)
    {
        echo "<br>BREAKING - LAST DAY STATISTICS IS COMPLETE";
        break;
    }
   
        //echo $yesterday."<br>";exit(0);
    if($start_time==0)
    {

        if($min_time_clk=mysql_query("select min(time) from ppc_clicks"))
        {                   
            $min_time_clk_row=mysql_fetch_row($min_time_clk);
            $temp1=$min_time_clk_row[0];
        }
        else
        {
            echo "<br>Query failed while getting minimum time from clicks table<br>select min(time) from ppc_clicks";
            break;
        }           
        echo "<br>Min time - clk = $temp1 = ".date("m d Y H i s",$temp1);
        if($temp1=='')
        {
        echo "<br>No clicks";
        break;
        }

       
            $e_time=mktime(0,0,0,date("n",$temp1),date("j",$temp1)+1,date("Y",$temp1));
       
    }
    else
    {
        if($update_check_ctrl==2)
        {
            $e_time=mktime(0,0,0,date("n",$start_time),date("j",$start_time)+1,date("Y",$start_time));
        }
        else
        {
            $e_time=$stat_row[0];
            $start_time=mktime(0,0,0,date("n",$e_time),date("j",$e_time)-1,date("Y",$e_time));
        }

    }

    echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $e_time (".date("m d Y H i s",$e_time).")";



    if(mysql_query("update statistics_updation set  e_time='$e_time',status=0 where task='daily_referral_statistics'"))
    {
        if(!mysql_query("delete from daily_referral_statistics where time='$e_time'"))
        {
            echo "<br>Data clearing from daily_referral_statistics failed<br>delete from daily_referral_statistics where time='$e_time'";
            break;
        }
    }
    else
    {
        echo "<br>Statistics updation failed<br>update statistics_updation set  e_time='$e_time',status=0 where task='daily_referral_statistics'";
        break;
    }

    if(!$result1=mysql_query("select adv_rid,sum(adv_ref_profit) from ppc_clicks where (time>$start_time and time<=$e_time) and adv_rid <>0  group by adv_rid"))
    {
        echo "<br>Data retrieval failed from ppc_clicks table<br>select adv_rid,sum(adv_ref_profit) from ppc_clicks where (time>$start_time and time<=$e_time) and adv_rid <>0  group by adv_rid";
        break;
    }
    //if(mysql_num_rows($result)==0)
            //{
    if(!$result2=mysql_query("select pub_rid,sum(pub_ref_profit) from ppc_clicks where (time>$start_time and time<=$e_time) and pub_rid <> 0 group by pub_rid"))
    {
   
        echo "<br>Data retrieval failed from ppc_clicks table<br>select pub_rid,sum(pub_ref_profit) from ppc_clicks where (time>$start_time and time<=$e_time) and pub_rid <> 0 group by pub_rid";
        break;
    }
                //$clk_flag=1;
            //}

    $result_arr=array();
    while($row=mysql_fetch_row($result1))
    {
    $key=$row[0];
   
    $result_arr[$key][0]=$row[0];
    $result_arr[$key][1]=$row[1];
    $result_arr[$key][2]=0;
   
    }
   
    while($row=mysql_fetch_row($result2))
    {
    $key=$row[0];
    $result_arr[$key][0]=$row[0];
    if($result_arr[$key][1]=="")
        $result_arr[$key][1]=0;
    $result_arr[$key][2]=$row[1];
    }
   
    $num_row_inserted=0;
    $result_count=count($result_arr);
    foreach ( $result_arr as $k => $v)
    {
	
	//if(($v[1]!=0) || ($v[2]!=0))
		//	{
					if(!mysql_query("insert into daily_referral_statistics (`id`,`pid`, `adv_ref_profit`, `pub_ref_profit`, `time`) values ('0','$v[0]','$v[1]',
				'$v[2]',$e_time)"))
					{
				
						echo "<br>Data insertion to daily_referral_statistics failed<br>insert into daily_referral_statistics (`id`,`pid`, `adv_ref_profit`, `pub_ref_profit`, `time`) values ('0','$v[0]','$v[1]',
				'$v[2]',$e_time)";
						break;
					}
				$num_row_inserted++;
			//}
    }
//$tot_entry_rows=$mysql->echo_one("select count(*) from daily_referral_statistics where time=$e_time");
    //if($num_row_inserted==$tot_entry_rows)


if($num_row_inserted==$result_count)

    {
   
        if($e_time==$yesterday)
        {
            mysql_query("update statistics_updation set e_time='$e_time',status=1 where task='daily_referral_statistics'");//partial completion
            echo "<br>BREAKING AFTER UPDATING LAST DAY STATISTICS";
            //echo "<br>Partial completion";
            break;
        }
        else
        mysql_query("update statistics_updation set e_time='$e_time',status=2 where task='daily_referral_statistics'");//full completion
    }
    else
    {
        //remains in status 0  ==> incomplete
    }

flush();
echo mysql_error();
}while(1);


//}

echo "<br><br><strong>Referral Monthly</strong><br>";


do
{

    if(!$stat_result=mysql_query("select e_time,status from statistics_updation where task='monthly_referral_statistics'"))
        {
            echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='monthly_referral_statistics'";
            break;
        }
    $stat_row=mysql_fetch_row($stat_result);
    $update_check_ctrl=$stat_row[1];
   
    $start_time=$stat_row[0];

    $this_month_time=time();
    $this_month_first_day=mktime(0,0,0,date("n",$this_month_time),1,date("Y",$this_month_time));
   
   if($start_time>=$this_month_first_day && $update_check_ctrl==2)
	{
	echo "<br>BREAKING - LAST MONTH STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
	break;
	}
   
   
   
    if($update_check_ctrl==2)
        {
            $delete_time=mktime(0,0,0,date("n",$start_time),date("j",$start_time)-32,date("Y",$start_time));
             if(mysql_query("delete from daily_referral_statistics where time<'$delete_time'"))// clear the data from daily table
            {
                echo "<br>Old data deleted from daily_referral_statistics<br>delete from daily_referral_statistics where time<'$delete_time'";
            }
            else
            {
                echo "<br>Old data deletion from daily_referral_statistics failed<br>delete from daily_referral_statistics where time<'$delete_time'";
            }
        }
   

    if($start_time==0)
    {
        if($min_time_daily=mysql_query("select min(time) from daily_referral_statistics"))
        {                   
            $min_time_daily_row=mysql_fetch_row($min_time_daily);

			echo "<br>Min time = $min_time_daily_row[0] = ".date("m d Y H i s",$min_time_daily_row[0]);
			if($min_time_daily_row[0]=='')
			{
				echo "<br>No data in the publisher_daily_statistics table";
				break;
			}
			else
			{
				$e_time=mktime(0,0,0,date("n",$min_time_daily_row[0]),1,date("Y",$min_time_daily_row[0]));
			}
        }
        else
        {
            echo "<br>Query failed while getting minimum time from daily_referral_statistics table<br>select min(time) from daily_referral_statistics";
            break;
        }   
       
    }
    else
    {
        if($update_check_ctrl==2)
        {
            $e_time=mktime(0,0,0,date("n",$start_time)+1,1,date("Y",$start_time));
        }
        else
        {
			$e_time=$start_time;

            $start_time=mktime(0,0,0,date("n",$start_time)-1,1,date("Y",$start_time));
               
        }

    }

   // $e_time=mktime(0,0,0,date("n",$start_time),1,date("Y",$start_time));    //ending of the month for which stat is built
   
    echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $e_time (".date("m d Y H i s",$e_time).")";


    if(!$stat_result_daily=mysql_query("select e_time,status from statistics_updation where task='daily_referral_statistics'"))
        {
            echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='daily_referral_statistics'";
            break;
        }
    $stat_row_daily=mysql_fetch_row($stat_result_daily);
    if( $stat_row_daily[0]<=$e_time )
    {
        echo "<br>BREAKING - DAILY TABLE INCOMPLETE";
        break; //cannot build as the daily table data is not complete.

    }   
   
    $tmp_clk=0;
    $tmp_imp=0;


    if(mysql_query("update statistics_updation set status=0,e_time='$e_time' where task='monthly_referral_statistics'"))
        {//partial completion
            if(!mysql_query("delete from monthly_referral_statistics where time='$e_time'"))
                {
                    echo "<br>Data clearing from monthly_referral_statistics failed<br>delete from monthly_referral_statistics where time='$e_time'";
                    break;
                }
        }
    else
        {
            echo "<br>Statistics updation failed<br>update statistics_updation set status=0,e_time='$e_time' where task='monthly_referral_statistics'";
            break;
        }
    if(!$result=mysql_query("select pid,sum(adv_ref_profit),sum(pub_ref_profit) from daily_referral_statistics WHERE (time<=$e_time and time>$start_time)  group by pid"))
        {
            echo "<br>Data retrieval failed from daily_referral_statistics table<br>select pid,sum(adv_ref_profit),sum(pub_ref_profit) from daily_referral_statistics WHERE (time<=$e_time and time>$start_time)  group by pid";
            break;
        }
   $num_row_inserted=0;
   $tot_entry_rows=mysql_num_rows($result);
    while($row=mysql_fetch_row($result))
    {
        if(!mysql_query("insert into monthly_referral_statistics (`id`,`pid`, `adv_ref_profit`, `pub_ref_profit`, `time`) values ('0','$row[0]','$row[1]','$row[2]',$e_time)"))
            {
                echo"<br>insert into monthly_referral_statistics (`id`,`pid`, `adv_ref_profit`, `pub_ref_profit`, `time`) values ('0','$row[0]','$row[1]','$row[2]',$e_time)";
                break;
                }
			else
				{
				$num_row_inserted++;
			}
        //$tmp_clk=$tmp_clk+$row[1];
      //  $tmp_imp=$tmp_imp+$row[2];



	//  $tot_entry_rows++;
    }
//    $mnthly_stat=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from monthly_referral_statistics where time='$e_time'");
 //   $monthly_stat=mysql_fetch_row($mnthly_stat);

 //   if(("$tmp_clk"=="$monthly_stat[0]") && ("$tmp_imp"=="$monthly_stat[1]"))
 //$tot_entry_rows=$mysql->echo_one("select count(*) from monthly_referral_statistics where time=$e_time");
    if($num_row_inserted==$tot_entry_rows)
    {
            mysql_query("update statistics_updation set status=2,e_time='$e_time' where task='monthly_referral_statistics'");//full completion
		if($e_time==$this_month_first_day)
        {
         //   mysql_query("update statistics_updation set status=1,e_time='$e_time' where task='monthly_referral_statistics'");//partial completion
            echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
            break;
        }
	//	else
    }
	
echo mysql_error();
flush();
}while (1);


echo "<br><br><strong>Referral Yearly</strong><br>";

do
{

    if(!$stat_result=mysql_query("select e_time,status from statistics_updation where task='yearly_referral_statistics'"))
        {
            echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='yearly_referral_statistics'";
            break;
        }
    $stat_row=mysql_fetch_row($stat_result);
    $update_check_ctrl=$stat_row[1];
   
    $start_time=$stat_row[0];

    $this_year_time=time();
   
    $this_year_first_day=mktime(0,0,0,1,1,date("Y",$this_year_time));
	
	
	if($start_time>=$this_year_first_day && $update_check_ctrl==2)
	{
	echo "<br>BREAKING - LAST YEAR STATISTICS IS COMPLETE !!!!!!!!!!!!!!!!!!!!!!!!!";
	break;
	}
	
	
    if($update_check_ctrl==2)
        {
            //$delete_time=$start_time-(365*24*60*60);
            $delete_time=mktime(0,0,0,1,1-366,date("Y",$start_time));
             if(mysql_query("delete from monthly_referral_statistics where time<'$delete_time'"))// clear the data from monthly table
            {
                echo "<br>Old data deleted from monthly_referral_statistics<br>delete from monthly_referral_statistics where time<'$delete_time'";
            }
            else
            {
                echo "<br>Old data deletion from monthly_referral_statistics failed<br>delete from monthly_referral_statistics where time<'$delete_time'";
            }
        }

    if($start_time==0)
    {
        if($min_time_monthly=mysql_query("select min(time) from monthly_referral_statistics"))
        {                   
            $min_time_monthly_row=mysql_fetch_row($min_time_monthly);
			echo "<br>Min time = $min_time_monthly_row[0] = ".date("m d Y H i s",$min_time_monthly_row[0]);
			if($min_time_monthly_row[0]=='')
			{
				echo "<br>No data in the publisher_monthly_statistics table";











				break;
			}
			else
			{
				$e_time=mktime(0,0,0,1,1,date("Y",$min_time_monthly_row[0]));
			}
        }
        else
        {
            echo "<br>Query failed while getting minimum time from monthly_referral_statistics table<br>select min(time) from monthly_referral_statistics";
            break;
        }   

    }
    else
    {
       
        if($update_check_ctrl==2)
        {
			$e_time=mktime(0,0,0,1,1,date("Y",$start_time)+1);
        }
        else
        {
			$e_time=$start_time;
            $start_time=mktime(0,0,0,1,1,date("Y",$start_time)-1);
        }

    }

    echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $e_time (".date("m d Y H i s",$e_time).")";
   
    if(!$stat_result_monthly=mysql_query("select e_time,status from statistics_updation where task='monthly_referral_statistics'"))
        {
            echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='monthly_referral_statistics'";
            break;
        }
    $stat_row_monthly=mysql_fetch_row($stat_result_monthly);
   
  // if($stat_row_monthly[0]>$start_time && $stat_row_monthly[0]<=$e_time && $stat_row_monthly[1]==0)
   
   if(($stat_row_monthly[0]==$e_time && $stat_row_monthly[1]!=2) || ($stat_row_monthly[0]<$e_time) )
    
    {
        echo "<br>BREAKING - MONTHLY TABLE INCOMPLETE";
        break; //cannot build as the monthly table data is not complete.

    }   
   




   
   
    // creating impression backup
    $tmp_clk=0;
    $tmp_imp=0;


    if(mysql_query("update statistics_updation set status=0,e_time='$e_time' where task='yearly_referral_statistics'"))
        {//partial completion
        if(!mysql_query("delete from yearly_referral_statistics where time='$e_time'"))
            {
                echo "<br>Data clearing from yearly_referral_statistics failed<br>delete from yearly_referral_statistics where time='$e_time'";
                break;
            }
        }
    else
        {
            echo "<br>Statistics updation failed<br>update statistics_updation set status=0,e_time='$e_time' where task='yearly_referral_statistics'";
            break;
        }
    if(!$result=mysql_query("select pid,sum(adv_ref_profit),sum(pub_ref_profit) from monthly_referral_statistics WHERE (time<=$e_time and time>$start_time)  group by pid"))
        {
            echo "<br>Data retrieval failed from monthly_referral_statistics table<br>select pid,sum(adv_ref_profit),sum(pub_ref_profit) from monthly_referral_statistics WHERE (time<=$e_time and time>$start_time)  group by pid";
            break;
        }
	$num_row_inserted=0;
	$tot_entry_rows=mysql_num_rows($result);
    while($row=mysql_fetch_row($result))
    {
        if(!mysql_query("insert into yearly_referral_statistics (`id`,`pid`, `adv_ref_profit`, `pub_ref_profit`, `time`) values ('0','$row[0]','$row[1]','$row[2]',$e_time)"))
            {
                echo "<br>Data insertion to yearly_referral_statistics failed<br>insert into yearly_referral_statistics (`id`,`pid`, `adv_ref_profit`, `pub_ref_profit`, `time`) values ('0','$row[0]','$row[1]','$row[2]',$e_time)";
                break;
            }
		else
			{
			$num_row_inserted++;
			}
			//$tot_entry_rows++;


      //  $tmp_clk=$tmp_clk+$row[1];
     //   $tmp_imp=$tmp_imp+$row[2];
    }
   // $mnthly_stat=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from yearly_referral_statistics where time='$e_time'");
  //  $yearly_stat=mysql_fetch_row($mnthly_stat);
 //   if(("$tmp_clk"=="$yearly_stat[0]") && ("$tmp_imp"=="$yearly_stat[1]"))
//$tot_entry_rows=$mysql->echo_one("select count(*) from yearly_referral_statistics where time=$e_time");
    if($num_row_inserted==$tot_entry_rows)
    {
            mysql_query("update statistics_updation set status=2,e_time='$e_time' where task='yearly_referral_statistics'");//full completion
		if($e_time==$this_year_first_day)
        {
          //  mysql_query("update statistics_updation set status=1,e_time='$e_time' where task='yearly_referral_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
            //echo "<br>Partial completion";
            break;
        }
	//	else
}
echo mysql_error();
flush();
}while (1);



if(date("G",time())==0)
	{
		mysql_query("OPTIMIZE  TABLE publisher_daily_statistics,advertiser_daily_statistics,daily_referral_statistics");
		if(date("j",time())==1)
			{
				mysql_query("OPTIMIZE  TABLE publisher_monthly_statistics,publisher_yearly_statistics");
				mysql_query("OPTIMIZE  TABLE advertiser_monthly_statistics,advertiser_yearly_statistics");
				mysql_query("OPTIMIZE  TABLE monthly_referral_statistics,yearly_referral_statistics");
			}
	}

//$end_start = microtime_float();
 
//echo $end_start;

 
 
// $DIFFERENCE=$end_start - $time_start;
//echo $DIFFERENCE;
//include_once("admin.footer.inc.php");



if ($raw_data_clearing==1)
{

$data=mysql_query("select e_time,status from statistics_updation where task='advertiser_daily_statistics' or task='publisher_daily_statistics' or task='daily_referral_statistics'");
$rowcount=mysql_num_rows($data);

if ($rowcount>0)
{

//while($array=mysql_fetch_row($data));
//{
$flag=true;
$arr= array();
for($i=0;$i<$rowcount;$i++)
{

	$array=mysql_fetch_row($data);
	
		$arr[$i]=$array[0];
	
}



	$count=count($arr);
	for($j=0;$j<$count-1;$j++)
	{
		
	if($arr[$j]!=$arr[$j+1])
		{
		
			$flag=false;
			break;
		
		}

	}
	
	if($flag==true)
	{
	
		$times=$array[0];
		$clicktime=mktime(0,0,0,date("n",$times),date("j",$times)-5,date("Y",$times));
		$clicktime1=mktime(0,0,0,date("n",$times),date("j",$times)-31,date("Y",$times));
		
	//	echo $clicktime;
		mysql_query("delete from ppc_clicks where time <'".$clicktime1."'");
		mysql_query("delete from advertiser_impression_daily where time <'".$clicktime."'");
		mysql_query("delete from publisher_impression_daily where time <'".$clicktime."'");
	
	}

}
}

?>