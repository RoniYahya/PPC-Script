<?php
include_once("config.inc.php");

/*/if(!isset($_COOKIE['inout_admin']))
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
	}*/
echo "<br><br><strong>Keyword Daily</strong><br>";


do
{
	if(!$stat_result=mysql_query("select e_time,last_id,status from statistics_updation where task='keyword_daily_statistics'"))
		{
		echo "<br>Data not got from statistics_updation table<br>select e_time,last_id,status from statistics_updation where task='keyword_daily_statistics'";
		break;
		}
	$stat_row=mysql_fetch_row($stat_result);
	$update_check_ctrl=$stat_row[2];
	$start_time=$stat_row[0];//$mysql->echo_one("select e_time from statistics_updation where  task='advertiser_daily_statistics'");

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

		if($min_time_imp=mysql_query("select min(time) from advertiser_daily_statistics"))
		{					
			$min_time_imp_row=mysql_fetch_row($min_time_imp);
			$temp=$min_time_imp_row[0];
		}
		else
		{
			echo "<br>Query failed while getting minimum time from advertiser statistics table<br>select min(time) from advertiser_daily_statistics";
			break;
		}	
				
		
	
		if($temp=='')
		{
		echo "<br>No Record found";
		break;
		}
		
	
		if(	$temp!="")
		{
				
					$e_time=mktime(0,0,0,date("n",$temp),date("j",$temp)+1,date("Y",$temp));
				
		
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

	echo "<br>Currently building data for $start_time (".date("m d Y H i s",$start_time).") - $e_time (".date("m d Y H i s",$e_time).")";


	if(mysql_query("update statistics_updation set  e_time='$e_time',status=0 where task='keyword_daily_statistics'"))
	{
		if(!mysql_query("delete from keyword_daily_statistics where time='$e_time'"))
		{
			echo "<br>Data clearing from keyword_daily_statistics failed<br>delete from keyword_daily_statistics where time='$e_time'";
			break;
		}
	}
	else
	{
		echo "<br>Statistics updation failed<br>update statistics_updation set  e_time='$e_time',status=0 where task='keyword_daily_statistics'";
		break;
	}
//echo "select sum(a.impression),sum(a.clk_count),sum(a.money_spent),b.sid,a.time from advertiser_daily_statistics a join ppc_keywords b on a.kid=b.id WHERE (a.time>$start_time and a.time<=$e_time)  group by a.kid";
	if(!$result1=mysql_query("select sum(a.impression),sum(a.clk_count),sum(a.money_spent),b.sid,a.time from advertiser_daily_statistics a join ppc_keywords b on a.kid=b.id WHERE a.time>$start_time and a.time<=$e_time and b.sid>0 group by a.kid"))
	{
		echo "<br>Data retrieval failed from advertiser_daily_statistics table<br>select sum(a.impression),sum(a.clk_count),sum(a.money_spent),b.sid,a.time from advertiser_daily_statistics a join ppc_keywords b on a.kid=b.id WHERE (a.time>$start_time and a.time<=$e_time) group by a.kid";
		break;
	}
	
$num_row_inserted=0;
	 $result_count=mysql_num_rows($result1);

	while($row=mysql_fetch_row($result1))
	{
	if(mysql_query("insert into keyword_daily_statistics (`id`, `keyid`, `impressions`, `time`,`click_count`, `money_spent`) values ('0','$row[3]','$row[0]',
	    $e_time,'$row[1]','$row[2]')"))
			$num_row_inserted++;
		else
			{
				echo "<br>Data insertion to keyword_daily_statistics failed<br>insert into keyword_daily_statistics (`id`, `keyid`, `impressions`,`time`, `click_count`, `money_spent`) values ('0','$row[3]','$row[0]',
	   $e_time,'$row[1]','$row[2]')";
				break;
			}
	
	}
	

//echo "<br>".$num_row_inserted."hjgj".$result_count;

	if($num_row_inserted==$result_count)
	{
	
		if($e_time==$yesterday)
		{
			mysql_query("update statistics_updation set e_time='$e_time',status=1 where task='keyword_daily_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST DAY STATISTICS";
			//echo "<br>Partial completion";
			break;
		}
		else
		{
			
		mysql_query("update statistics_updation set e_time='$e_time',status=2 where task='keyword_daily_statistics'");//full completion
				//break;
		}
	}
	else
	{
		//remains in status 0  ==> incomplete
	}

flush();
echo mysql_error();
}while(1);


?> 

<?php


///advertiser monthly statistics

echo "<br><br><strong>Keyword Monthly</strong><br>";

do
{

	if(!$stat_result=mysql_query("select e_time,status from statistics_updation where task='keyword_monthly_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='keyword_monthly_statistics'";
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
 			if(mysql_query("delete from keyword_daily_statistics where time<'$delete_time'"))// clear the data from daily table
			{
				echo "<br>Old data deleted from keyword_daily_statistics<br>delete from keyword_daily_statistics where time<'$delete_time'";
			}
			else
			{
				echo "<br>Old data deletion from keyword_daily_statistics failed<br>delete from keyword_daily_statistics where time<'$delete_time'";
			}
		}
	

	if($start_time==0)
	{
		if($min_time_daily=mysql_query("select min(time) from keyword_daily_statistics"))
		{					
			$min_time_daily_row=mysql_fetch_row($min_time_daily);

			echo "<br>Min time = $min_time_daily_row[0] = ".date("m d Y H i s",$min_time_daily_row[0]);
			if($min_time_daily_row[0]=='')
			{
				echo "<br>No data in the keyword_daily_statistics table";
				break;
			}
			else
			{
				$e_time=mktime(0,0,0,date("n",$min_time_daily_row[0]),1,date("Y",$min_time_daily_row[0]));
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from keyword_daily_statistics table<br>select min(time) from keyword_daily_statistics";
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


	if(!$stat_result_daily=mysql_query("select e_time,status from statistics_updation where task='keyword_daily_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='keyword_daily_statistics'";
			break;
		}
	$stat_row_daily=mysql_fetch_row($stat_result_daily);
	//echo $stat_row_daily[0]."<=".$e_time;"<br>";
	if( $stat_row_daily[0]<=$e_time)
	{
		echo "<br>BREAKING - DAILY TABLE INCOMPLETE";
		break; //cannot build as the daily table data is not complete.
	}	
	
	$tmp_clk=0;
	$tmp_imp=0;

	//echo "<br>Update control flag = $update_check_ctrl";

	if(mysql_query("update statistics_updation set status=0,e_time='$e_time' where task='keyword_monthly_statistics'"))
		{//partial completion
			if(!mysql_query("delete from keyword_monthly_statistics where time='$e_time'"))
				{
					echo "<br>Data clearing from keyword_monthly_statistics failed<br>delete from keyword_monthly_statistics where time='$e_time'";
					break;
				}
		}
	else
		{
			echo "<br>Statistics updation failed<br>update statistics_updation set status=0,e_time='$e_time' where task='keyword_monthly_statistics'";
			break;
		}
		//echo "select keyid,sum(impressions),sum(click_count),sum(money_spent) from keyword_daily_statistics WHERE (time<=$e_time and time>$start_time) group by keyid";
	if(!$result=mysql_query("select keyid,sum(impressions),sum(click_count),sum(money_spent) from keyword_daily_statistics WHERE (time<=$e_time and time>$start_time) group by keyid"))
		{
			echo "<br>Data retrieval failed from keyword_daily_statistics table<br>select keyid,sum(impressions),sum(click_count),sum(money_spent) from keyword_daily_statistics WHERE (time<=$e_time and time>$start_time)  group by keyid";
			break;
		}
//echo "select keyid,sum(impressions),sum(click_count),sum(money_spent) from keyword_daily_statistics WHERE (time<=$e_time and time>$start_time)  group by keyid";

	while($row=mysql_fetch_row($result))
	{
		if(!mysql_query("insert into keyword_monthly_statistics (`id`,`keyid`, `impressions`,`click_count`, `money_spent`,`time`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time)"))
			{
				echo"<br>Data insertion to keyword_monthly_statistics failed<br>insert into keyword_monthly_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time,'$row[4]','$row[5]','$row[6]','$row[7]','$row[8]')";
				break;
				}
		$tmp_clk=$tmp_clk+$row[2];
		$tmp_imp=$tmp_imp+$row[1];
	}
	
	
	
	$mnthly_stat=mysql_query("select COALESCE(sum(impressions),0),COALESCE(sum(click_count),0) from keyword_monthly_statistics where time='$e_time'");
	$monthly_stat=mysql_fetch_row($mnthly_stat);
	
	//echo $monthly_stat[1]."        dd  ".$tmp_clk."  ---   ".$tmp_imp."ffff   ".$monthly_stat[0];
	
	
	if(($tmp_clk==$monthly_stat[1]) && ($tmp_imp==$monthly_stat[0]))
	{
			mysql_query("update statistics_updation set status=2,e_time='$e_time' where task='keyword_monthly_statistics'");//full completion
		if($e_time==$this_month_first_day)
		{
		//	mysql_query("update statistics_updation set status=1,e_time='$e_time' where task='advertiser_monthly_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST MONTH STATISTICS";
			break;
		}
	
	}

echo mysql_error();
flush();
}while(1);
?> 


<?php
//include("extended-config.inc.php");  
//include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
///advertiser yearly statistics

echo "<br><br><strong>Keyword Yearly</strong><br>";

do
{

	if(!$stat_result=mysql_query("select e_time,status from statistics_updation where task='keyword_yearly_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='keyword_yearly_statistics'";
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
				
 			if(mysql_query("delete from keyword_monthly_statistics where time<'$delete_time'"))// clear the data from monthly table
			{
				echo "<br>Old data deleted from keyword_monthly_statistics<br>delete from keyword_monthly_statistics where time<'$delete_time'";
			}
			else
			{
				echo "<br>Old data deletion from keyword_monthly_statistics failed<br>delete from keyword_monthly_statistics where time<'$delete_time'";
			}
		}


	if($start_time==0)
	{
		if($min_time_monthly=mysql_query("select min(time) from keyword_monthly_statistics"))
		{					
			$min_time_monthly_row=mysql_fetch_row($min_time_monthly);
			echo "<br>Min time = $min_time_monthly_row[0] = ".date("m d Y H i s",$min_time_monthly_row[0]);
			if($min_time_monthly_row[0]=='')
			{
				echo "<br>No data in the keyword_monthly_statistics table";
				break;
			}
			else
			{
				echo $e_time=mktime(0,0,0,1,1,date("Y",$min_time_monthly_row[0]));
			}
		}
		else
		{
			echo "<br>Query failed while getting minimum time from keyword_monthly_statistics table<br>select min(time) from keyword_monthly_statistics";
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
	
	if(!$stat_result_monthly=mysql_query("select e_time,status from statistics_updation where task='keyword_monthly_statistics'"))
		{
			echo "<br>Data not got from statistics_updation table<br>select e_time,status from statistics_updation where task='keyword_monthly_statistics'";
			break;
		}
	$stat_row_monthly=mysql_fetch_row($stat_result_monthly);
	
	
	
	
	//if($stat_row_monthly[0]>$start_time && $stat_row_monthly[0]<=$e_time && $stat_row_monthly[1]!=2)
	
	if(($stat_row_monthly[0]==$e_time && $stat_row_monthly[1]!=2) || ($stat_row_monthly[0]<$e_time) )
	{
		echo "<br>BREAKING - MONTHLY TABLE INCOMPLETE";
		break; //cannot build as the monthly table data is not complete.

	}	
	

	
	
	
	$tmp_clk=0;
	$tmp_imp=0;


	if(mysql_query("update statistics_updation set status=0,e_time='$e_time' where task='keyword_yearly_statistics'"))
		{//partial completion
		if(!mysql_query("delete from advertiser_yearly_statistics where time='$e_time'"))
			{
				echo "<br>Data clearing from keyword_yearly_statistics failed<br>delete from keyword_yearly_statistics where time='$e_time'";
				break;
			}
		}
	else
		{
			echo "<br>Statistics updation failed<br>update statistics_updation set status=0,e_time='$e_time' where task='keyword_yearly_statistics'";
			break;
		}
		//echo "select keyid,sum(impressions),sum(click_count),sum(money_spent) from  keyword_monthly_statistics WHERE (time<=$e_time and time>$start_time) group by keyid";
	if(!$result=mysql_query("select keyid,sum(impressions),sum(click_count),sum(money_spent) from  keyword_monthly_statistics WHERE (time<=$e_time and time>$start_time) group by keyid"))
		{
			echo "<br>Data retrieval failed from keyword_monthly_statistics table<br>select keyid,sum(impressions),sum(click_count),sum(money_spent) from keyword_monthly_statistics WHERE (time<=$e_time and time>$start_time)  group by keyid";
			break;
		}
	while($row=mysql_fetch_row($result))
	{
		if(!mysql_query("insert into keyword_yearly_statistics (`id`,`keyid`, `impressions`,`click_count`, `money_spent`,`time`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time)"))
			{
				echo "<br>Data insertion to keyword_yearly_statistics failed<br>insert into keyword_yearly_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`,`pub_ref_profit`,`adv_ref_profit`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time,'$row[4]','$row[5]','$row[6]','$row[7]','$row[8]')";
				break;
			}
		$tmp_clk=$tmp_clk+$row[2];
		$tmp_imp=$tmp_imp+$row[1];
		//echo "<br>insert into advertiser_yearly_statistics (`id`,`uid`, `aid`, `kid`, `impression`, `time`, `clk_count`, `money_spent`,`publisher_profit`) values ('0','$row[0]','$row[1]','$row[2]','$row[3]',$e_time,'$row[4]','$row[5]','$row[6]','$row[7]','$row[8]')";
	}
	$mnthly_stat=mysql_query("select COALESCE(sum(impressions),0),COALESCE(sum(click_count),0) from keyword_yearly_statistics where time='$e_time'");
	$yearly_stat=mysql_fetch_row($mnthly_stat);
	//echo $tmp_clk."fg".$yearly_stat[1]."fggg".$tmp_imp."fg".$yearly_stat[0];
	if(($tmp_clk==$yearly_stat[1]) && ($tmp_imp==$yearly_stat[0]))
	{
			mysql_query("update statistics_updation set status=2,e_time='$e_time' where task='keyword_yearly_statistics'");//full completion
		if($e_time==$this_year_first_day)
		{
		//	mysql_query("update statistics_updation set status=1,e_time='$e_time' where task='advertiser_yearly_statistics'");//partial completion
			echo "<br>BREAKING AFTER UPDATING LAST YEAR STATISTICS";
			//echo "<br>Partial completion";
			break;
		}
	
	
	
}
echo mysql_error();
flush();
}while (1);
?>
<?php
echo "<br><br><strong>Keyword Rating Updation</strong><br>";

	
	$time1=mktime(0,0,0,date("m",time()),date("d",time())-29,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
	
$key_result=mysql_query("select COALESCE(sum(impressions),0) from keyword_daily_statistics where  time>=$time1");

if(!$key_result)
		{
		echo "<br>Error occured while getting total impressions of past 30 days.<br>select COALESCE(sum(impressions),0) from keyword_daily_statistics where  time>=$time1";
		}
else 
{
	
	$tot_imp=mysql_fetch_row($key_result);
	
	$firstid=$mysql->echo_one("select last_id from statistics_updation where task='rate_updation'");

	//echo "select sum(a.impressions),a.keyid from keyword_daily_statistics a join system_keywords b on a.keyid=b.id where a.time>=$time1 group by a.keyid";
	$key=mysql_query("select coalesce(sum(a.impressions),0),b.id from system_keywords b left join keyword_daily_statistics a   on b.id=a.keyid where a.time>=$time1 and b.id>$firstid group by b.id order by b.id limit 0,1000");
	$sum=0;
	  $lastid=0;
	while($ans=mysql_fetch_row($key))
	{
	if($firstid==0)
		$firstid=$ans[1];
		$rate=($ans[0]/$tot_imp[0])*100;
		
		mysql_query("update system_keywords set rating='$rate' where id=$ans[1]");
		
		$lastid=$ans[1];
		$sum=$sum+1;
		
	}
	
	if($sum<1000)
	$lastid=0;
          $time=time(); 
          if($firstid==$lastid)
          echo "<br>No data to be updated";
          else
    echo "<br>Rating updated for keywords $firstid - $lastid ";      
     mysql_query("update statistics_updation set e_time=$time ,status=2 , last_id=$lastid where task='rate_updation'");  
}    
?>