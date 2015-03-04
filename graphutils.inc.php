<?php 
/*--------------------------------------------------+
|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/
?><?php 
function getCurrentMonthStatisticsAdv($flag_time,$mysql,$field,$field1,$uid,$aid,$kid,$data_clk=0,$sumvar=0,$frm=0)
{
$uidstring="";
$aidstring="";
$kidstring="";

if($uid == 'NA' && $aid == 'NA' && $kid == 'NA')
{
$uidstring="";
$aidstring="";
$kidstring="";
}
else if($uid != 'NA' && $aid == 'NA' && $kid == 'NA')
{
$uidstring=" uid='$uid' and ";
$aidstring="";
$kidstring="";
}
else if($uid != 'NA' && $aid != 'NA' && $kid == 'NA')
{
$uidstring=" uid='$uid' and ";
$aidstring=" aid='$aid' and ";
$kidstring="";
}
else if($uid != 'NA' && $aid != 'NA' && $kid != 'NA')
{
$uidstring=" uid='$uid' and ";
$aidstring=" aid='$aid' and ";
$kidstring=" kid='$kid' and ";
}



$data=0;
$stringvar="";
if($sumvar ==1)
$stringvar="count";
else if($sumvar ==2)
$stringvar="sum";



$day=date("j",time());
$mont=date("n",time());
if($day ==1 && $mont ==1)
{

$year_beg=mktime(0,0,0,1,1,date("Y",time())-1);
$year_end=mktime(0,0,0,1,1,date("Y",time()));
$daily_stattime=mktime(date("H",time()),0,0,date("m",time()),date("j",time())-1,date("Y",time()));
$month_beg=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));

$year_end_new=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));

if($flag_time==2)
$data=$mysql->echo_one("select COALESCE(sum(".$field."),0) from advertiser_monthly_statistics where $uidstring $aidstring $kidstring  time > $year_beg and time <= $year_end_new");
 
 
 
 
$data=$data+$mysql->echo_one("select COALESCE(sum(".$field."),0) from advertiser_daily_statistics where $uidstring $aidstring $kidstring  time > $month_beg and time <= $year_end");
			  
if($data_clk ==1)
$data=$data+$mysql->echo_one("select COALESCE(".$stringvar."(".$field1."),0) from ppc_daily_clicks where $uidstring $aidstring $kidstring  time > $daily_stattime and time <= $year_end ");		else
$data=$data+$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where $uidstring $aidstring $kidstring  time > $daily_stattime and time <= $year_end ");	  

if($flag_time==1)
$newda=mktime(0,0,0,date("m",time()),1,date("Y",time()));
else if($flag_time==2)
$newda=$year_end;


			  
if($frm ==1)
return  $data."_".$newda;
else
return  $data;
}
else if($day ==1 && $mont !=1)
{
$month_beg=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
$month_end=mktime(0,0,0,date("m",time()),1,date("Y",time()));
$daily_stattime=mktime(date("H",time()),0,0,date("m",time()),date("j",time())-1,date("Y",time()));

$data=$mysql->echo_one("select COALESCE(sum(".$field."),0) from advertiser_daily_statistics where $uidstring $aidstring $kidstring  time > $month_beg and time <= $month_end");




if($data_clk ==1)
$data=$data+$mysql->echo_one("select COALESCE(".$stringvar."(".$field1."),0) from ppc_daily_clicks where $uidstring $aidstring $kidstring  time > $daily_stattime and time <= $month_end ");
else
$data=$data+$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where $uidstring $aidstring $kidstring  time > $daily_stattime and time <= $month_end ");


if($flag_time==1)
$newda=$month_end;
else if($flag_time==2)
$newda=mktime(0,0,0,1,1,date("Y",time())+1);


if($frm ==1)
return  $data."_".$newda;
else
return  $data;

}
else
return 0;




}
function plotAdvertiserGraphs($show,$flag_time,$beg_time,$end_time,$selected_colorcode,$uid,$id =0)
{

if($flag_time == 0)
$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
//else if($flag_time == 1)
//$minus_time=mktime(0,0,0,date("n",$end_time)-1,date("j",$end_time),date("Y",$end_time));
//else if($flag_time == 2)
//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time),date("Y",$end_time)-1);



$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits_imps=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));

$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$admin_flag=0;

$path_str=substr(getcwd(),strlen(getcwd())-5);
if("admin"==$path_str)
	{
	$admin_flag=1;
	
	}
	global $mysql;
		$ctr_flag=0;
			$money_flag=0;
$aid_str=" and aid=$id ";
	if($id==0)
		{
		$aid_str="";
		}

if($id==0)	
	$idsstr="NA";
	else
	$idsstr=$id;

		
		$uid_str=" uid=$uid "; 
		
		//$retVal=array();
	if($flag_time==0)
			  {
				$table_name="advertiser_daily_statistics";
			  }
			 else if($flag_time==2)
				{
				$table_name="advertiser_yearly_statistics";
				$beg_time=$mysql->echo_one("select min(time) from $table_name where uid=$uid");
				}
			 else if($flag_time==1)
			 {
				$table_name="advertiser_monthly_statistics";
			 }
		if($flag_time==-1)
			{
$impression_result=mysql_query("select COALESCE(sum(cnt),0), time from advertiser_impression_daily where $uid_str $aid_str and time>$beg_time group by time order by time");
$click_result=mysql_query("select COALESCE(count(id),0),COALESCE(sum(clickvalue),0),time from ppc_daily_clicks where $uid_str $aid_str and time>$beg_time and time < $endtimes  group by time order by time");



				$result_arr=array();
				while($row=mysql_fetch_row($impression_result))
				{
					$key=$row[1];
					$result_arr[$key][0]=$row[0];
					$result_arr[$key][1]=0;
				
				}
				while($row=mysql_fetch_row($click_result))
				{
					$key=$row[2];
					if($result_arr[$key][0]=="")
					$result_arr[$key][0]=0;
					$result_arr[$key][1]=$row[0];
					if($result_arr[$key][0]>0 and $result_arr[$key][1]>0)
					$ctr_flag=1;
					if($row[1]>0)
						$money_flag=1;
				}
				
			

			}
		else
			{
			
			$stat_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),time from `$table_name` where $uid_str $aid_str and time>=$beg_time group by time order by time");
			
			
$spc_imps_result=0;
$spc_clk_result=0;
$spc_cval_result=0;			
			//group by time;group by time;group by time
$spc_imps_result=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where $uid_str $aid_str and time>$spec_time_limits_imps and time <$endtimes order by time");

$spc_imps_result_spec=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where $uid_str $aid_str and time>$spec_time_limits and time <=$spec_time_limits_imps order by time");

$spc_clk_result=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where $uid_str $aid_str and time>$spec_time_limits_imps and time < $endtimes  order by time");
$spc_clk_result_spec=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where $uid_str $aid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");


$spc_cval_result=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where $uid_str $aid_str and time>$spec_time_limits_imps and time < $endtimes  order by time");
$spc_cval_result_spec=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where $uid_str $aid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");			
			}
			
		
if($beg_time==0)
$beg_time=mktime(0,0,0,1,1,date("y",time()));


		if(1==$admin_flag)
			{
		# Include FusionCharts PHP Class
			include_once('../FusionCharts/Class/FusionCharts_Gen.php');
			}
		else
			{
			include_once('FusionCharts/Class/FusionCharts_Gen.php');
			}
		
		# Create Multiseries Column3D chart object using FusionCharts PHP Class
		$FC = new FusionCharts("MSColumn3DLineDY","750","300");
		
		# Set the relative path of the swf file
		
		
	    if(1==$admin_flag)
			{
			$FC->setSWFPath("../FusionCharts/");
			}
		else
			{
			$FC->setSWFPath("FusionCharts/");
			}
		# Store chart attributes in a variable
		$strParam="caption=Clicks and Impressions;subcaption=Comparison;xAxisName=Duration;yAxisName=Statistics; pYAxisName=Count;sYAxisName=CTR (%);decimalPrecision=$no_of_decimalplaces;rotateNames=1; showvalues=0;";
		
		# Set chart attributes
		$FC->setChartParams($strParam);
		
		$curr_impr=0;
		$curr_clk=0;
		$curr_ctr=0;
		$curr_money=0;
		
		
	
		
		
		
		 if($flag_time==1)
			{
			 $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$current_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_daily_statistics` where $uid_str $aid_str and time>$month_time ");
			
	//		echo "select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_daily_statistics` where $uid_str $aid_str and time>$month_time ";
			$current_row=mysql_fetch_row($current_result);
			$curr_clk=$current_row[1]+$spc_clk_result;
			$curr_impr=$current_row[0]+$spc_imps_result;
			if($curr_impr>0)
				$curr_ctr=round(($curr_clk/$curr_impr)*100,2);
			
			if($curr_ctr>0)
				$ctr_flag=1;
			
			$curr_money=$current_row[2]+$spc_cval_result;
			if($curr_money>0)
				$money_flag=1;
		
		
		
		     $fun_imp=0;
			 $fun_clk=0;
			 $fun_money=0;
			 
		    $fun_imp=getCurrentMonthStatisticsAdv($flag_time,$mysql,"impression","",$uid,$idsstr,"NA",0,0,1);	
			if($fun_imp != 0)
			{
			$fun_imp_arr=explode('_',$fun_imp);
			
			$fun_imp=$fun_imp_arr[0];
			$minus_time=$fun_imp_arr[1];
			
			
			}
			
			$fun_clk=getCurrentMonthStatisticsAdv($flag_time,$mysql,"clk_count","id",$uid,$idsstr,"NA",1,1,1);
			if($fun_clk != 0)
			{
			$fun_clk_arr=explode('_',$fun_clk);
			
			$fun_clk=$fun_clk_arr[0];
			$minus_time=$fun_clk_arr[1];
			
			
			}
			
			
			
			
			
			
			$fun_money=getCurrentMonthStatisticsAdv($flag_time,$mysql,"money_spent","clickvalue",$uid,$idsstr,"NA",1,2,1);
		    if($fun_money != 0)
			{
			$fun_money_arr=explode('_',$fun_money);
			
			$fun_money=$fun_money_arr[0];
			$minus_time=$fun_money_arr[1];
			
			
			}
			
			
		
		    if($fun_imp>0)
			$curr_ctr1=round(($fun_clk/$fun_imp)*100,2);
			
			if($curr_ctr1>0)
			$ctr_flag=1;
			
			if($fun_money>0)
			$money_flag=1;
		
		
		
		
			}
		
				 if($flag_time==2)
			{
				    $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$current_day_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_daily_statistics` where $uid_str $aid_str and time>$month_time ");
			$current_day_row=mysql_fetch_row($current_day_result);
			
			$year_time=mktime(0,0,0,1,1,date("Y",time()));
			$current_month_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_monthly_statistics` where $uid_str $aid_str and time>$year_time ");
			
			$current_month_row=mysql_fetch_row($current_month_result);
			
			
			
			$curr_clk=$current_day_row[1]+$current_month_row[1]+$spc_clk_result;
			$curr_impr=$current_day_row[0]+$current_month_row[0]+$spc_imps_result;
			if($curr_impr>0)
				$curr_ctr=round(($curr_clk/$curr_impr)*100,2);
			
			if($curr_ctr>0)
				$ctr_flag=1;
			
			$curr_money=$current_day_row[2]+$current_month_row[2]+$spc_cval_result;
			if($curr_money>0)
				$money_flag=1;
		
		
		
		    $fun_imp=0;
			 $fun_clk=0;
			 $fun_money=0;
			 
			 
			 
		    $fun_imp=getCurrentMonthStatisticsAdv($flag_time,$mysql,"impression","",$uid,$idsstr,"NA",0,0,1);
			
			if($fun_imp != 0)
			{
			$fun_imp_arr=explode('_',$fun_imp);
			
			$fun_imp=$fun_imp_arr[0];
			$minus_time=$fun_imp_arr[1];
			
			
			}
			
			
				
			$fun_clk=getCurrentMonthStatisticsAdv($flag_time,$mysql,"clk_count","id",$uid,$idsstr,"NA",1,1,1);
			if($fun_clk != 0)
			{
			$fun_clk_arr=explode('_',$fun_clk);
			
			$fun_clk=$fun_clk_arr[0];
			$minus_time=$fun_clk_arr[1];
			
			
			}
			
			$fun_money=getCurrentMonthStatisticsAdv($flag_time,$mysql,"money_spent","clickvalue",$uid,$idsstr,"NA",1,2,1);
		    
			 if($fun_money != 0)
			{
			$fun_money_arr=explode('_',$fun_money);
			
			$fun_money=$fun_money_arr[0];
			$minus_time=$fun_money_arr[1];
			
			
			}
			
		
		    if($fun_imp>0)
			$curr_ctr1=round(($fun_clk/$fun_imp)*100,2);
			
			if($curr_ctr1>0)
			$ctr_flag=1;
			
			if($fun_money>0)
			$money_flag=1;
		
		
		
		
		
		
			}
		
		$temp_time=$beg_time;
		if($curr_impr==0)
			{
			$curr_impr='';
			}
			
			if($curr_clk==0)
			{
			$curr_clk='';
			}
			
			if($fun_imp==0)
			{
			$fun_imp='';
			}
			
			if($fun_clk==0)
			{
			$fun_clk='';
			}
			
			
			
			
			
		while($temp_time<=$end_time)
		{
	//	echo  "$temp_time=$beg_time=$end_time<br>";flush();
			if($flag_time==0)
			{
				$str=dateformat($temp_time-1,"%b %d"); 
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
				$show_duration="$str";
			}
			else if($flag_time==1)
			{
				$str=dateformat($temp_time-86400,"%b");//"M",$temp_time-86400);
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
				$show_duration="$str";
			}
			else if($flag_time==2)
			{
				$str=dateformat($temp_time-86400,"%Y");//("Y",$temp_time-86400);
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
				$show_duration="$str";
			}
			if($flag_time==-1)
			{
					$str=datetimeformat($temp_time,"%d %b %l %p");//("d M h a",$temp_time);
					$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
					$show_duration="$str";
			}
			
			
			
		//echo "$show_duration <br>";
//	 $show_duration;
//	exit;
			$FC->addCategory("$show_duration");
			if($flag_time!=-1)
			{
				
			
					if( $ctr_flag==0 || $money_flag==0)
					{
						$row=mysql_fetch_row($stat_result);
						if(($row[0]>0 && $row[1]>0) || (($spc_imps_result >0 || $spc_imps_result_spec >0) && ($spc_clk_result >0 || $spc_clk_result_spec >0)))
						{
							$ctr_flag=1;
						}
						if( $row[2]>0 || $spc_cval_result >0 || $spc_cval_result_spec >0)
						{
							$money_flag=1;
						}
					}
			}
			


			
			
			
		//echo "$show_duration";
		
		}
		
		if($flag_time==-1)
			{
			mysql_data_seek($click_result,0);
			mysql_data_seek($impression_result,0);	
			}
		else
			{	
			mysql_data_seek($stat_result,0);
			}				
		$temp_time=$beg_time;
		$FC->addDataset("Impressions","showvalues=0");
		$loop_flag=0;
		
		
		while( $temp_time<=$end_time)
		{
		
		if($flag_time==-1)
			{
			if($loop_flag==0)
				$imp_row=mysql_fetch_row($impression_result);
	//			echo "$imp_row[1]==$temp_time<br>";
			if($imp_row[1]==$temp_time)
			{
				# Add chart values for the above dataset
				$loop_flag=0;
				$FC->addChartData("$imp_row[0]");
			}
			else
			{
				$loop_flag=1;
				$FC->addChartData('');
			}
		}
		else
		{
		//echo $curr_impr;
		//echo $loop_flag;
			if($loop_flag==0)
				$row=mysql_fetch_row($stat_result);
				
				
				//echo $end_time."==".$temp_time."==".$minus_time."<br>";
				
				if($end_time==$temp_time)
				{
				 if($flag_time==1 || $flag_time==2)
					{
					    if($temp_time == $minus_time)
					    $curr_impr=$curr_impr+$fun_imp;
						else
						$curr_impr=$curr_impr+$spc_imps_result_spec;
						
						
						if($curr_impr == 0)
						$FC->addChartData('');
					    else
						$FC->addChartData("$curr_impr");
						break;
						
					}
					else
					{
					//$c_imp_data=$spc_imps_result+$row[0];
					
					if($spc_imps_result == 0)
					$FC->addChartData('');
					else
					$FC->addChartData("$spc_imps_result");
					break;
					}
				}
				
				
				
				
				
				
		
		
			
			
			if($row[3]==$temp_time)
			{
			
			
			
			if($flag_time==1 || $flag_time==2)
				{
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$da=$fun_imp+$row[0];
				$FC->addChartData("$da");
			
				}
				else
				{
				$loop_flag=0;
				$FC->addChartData("$row[0]");
						
				}
				
				}
				else if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$s_datass=$row[0]+$spc_imps_result_spec;
				$FC->addChartData("$s_datass");
				
				
				}
				else
				{				
				$loop_flag=0;
				$FC->addChartData("$row[0]");
				}
				}
				/*{
		    	$loop_flag=0;
				$FC->addChartData("$row[0]");
						
				}*/
					
	
			
			
				
			}
			else
			{		
			
			
			
			if($flag_time==1 || $flag_time==2)
				{
				//$minus_time=mktime(0,0,0,date("n",$end_time)-1,date("j",$end_time),date("Y",$end_time));
				if($temp_time == $minus_time)
				{
				
				
				
			if($fun_imp !=0)
			{	
				
			$loop_flag=1;
			$FC->addChartData("$fun_imp");
		
			}
			else
			{
			$loop_flag=1;
			$FC->addChartData('');
			
			
			}
			
				
				}
				else
				{
				$loop_flag=1;
				$FC->addChartData('');
				
				
				}
				
				}
				else if($flag_time!=1 && $flag_time!=2)
			{
			
			if($temp_time == $minus_time)
			{
			if($spc_imps_result_spec !=0)
			{
			$loop_flag=1;
			$FC->addChartData("$spc_imps_result_spec");
			}
			else
			{
			$loop_flag=1;
			$FC->addChartData('');
			
			}
			
			}
			else
			{			
				$loop_flag=1;
				$FC->addChartData('');
			}
			}
			
			
				
				
				
				
				
				/*{
				$loop_flag=1;
				$FC->addChartData('');
							
				}*/
					
			
			
			
				
			}
			
				
		}
		
		
					//echo $row[3]."==".$temp_time.$row[0]."<br>";
			if($flag_time==0)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
			}
			else if($flag_time==1)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
			}
			 else if($flag_time==2)
				{
		
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
			}
		if($flag_time==-1)
			{
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			}		
		}
	//	............
		 if($flag_time==-1)
			{
			mysql_data_seek($click_result,0);
			mysql_data_seek($impression_result,0);
			}
		else
			{	
			mysql_data_seek($stat_result,0);
			}
		$temp_time=$beg_time;
		$FC->addDataset("Clicks","showValues=0");
		$loop_flag=0;
		while( $temp_time<=$end_time)
		{
		
		if($flag_time==-1)
		{
		if($loop_flag==0)
				$clk_row=mysql_fetch_row($click_result);
			if($clk_row[2]==$temp_time)
			{
				# Add chart values for the above dataset
				$loop_flag=0;
				$FC->addChartData("$clk_row[0]");
			}
			else
			{
				$loop_flag=1;
				$FC->addChartData('');
			}
		}
		else
		{
			if($loop_flag==0)
				$row=mysql_fetch_row($stat_result);
				if($end_time==$temp_time)
				{
				 if($flag_time==1 || $flag_time==2)
					{
					
					    if($temp_time == $minus_time)
					    $curr_clk=$curr_clk+$fun_clk;
						else
						$curr_clk=$curr_clk+$spc_clk_result_spec;
					
					
					    if($curr_clk ==0)
						$FC->addChartData('');
					    else
						$FC->addChartData("$curr_clk");
						break;
					}
				
					else
					{
					
					if($spc_clk_result ==0)
					$FC->addChartData('');
					else
					$FC->addChartData("$spc_clk_result");
					break;
					
					}
					
					
					
				}
		
		
			
		if($row[3]==$temp_time)
			{
			
				# Add chart values for the above dataset
							
				if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
				
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$s_datass=$row[1]+$spc_clk_result_spec;
				$FC->addChartData("$s_datass");
				
				
				}
				else
				{				
				$loop_flag=0;
				$FC->addChartData("$row[1]");
				}
				}
				else if($flag_time==1 || $flag_time==2)
				{
				
				if($row[3] == $minus_time)
				{
				
								
				
				$loop_flag=0;
				$da=$fun_clk+$row[1];
				$FC->addChartData("$da");
						
				
				}
				else
				{
				$loop_flag=0;
				$FC->addChartData("$row[1]");
				
				
				}
					
				
				
				
				}
		
				
				
			}
			
			else
			{
			if($flag_time!=1 && $flag_time!=2)
			{
			
			if($temp_time == $minus_time)
			{
			if($spc_clk_result_spec !=0)
			{
			$loop_flag=1;
			$FC->addChartData("$spc_clk_result_spec");
			}
			else
			{
			$loop_flag=1;
			$FC->addChartData('');
			
			}
			
			}
			else
			{			
				$loop_flag=1;
				$FC->addChartData('');
			}
			}
			else if($flag_time==1 || $flag_time==2)
				{
			
			
			if($temp_time == $minus_time)
			{
			
			
			
			
			if($fun_clk !=0)
			{	
				
			
			$loop_flag=1;
			$FC->addChartData("$fun_clk");
			
			
		
			}
			else
			{
			$loop_flag=1;
			$FC->addChartData('');
			
			
			}
			
			
			
			
			
			
			
			
			
			
			
			}
			else
			{
			
			$loop_flag=1;
			$FC->addChartData('');
			}
			}
			}
		
			
			
			
			
			
			
			
		}
			
			if($flag_time==0)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
			}
			else if($flag_time==1)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
			}
		 else if($flag_time==2)
			{
		
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
			}
			if($flag_time==-1)
			{
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			}		
		}
			
		$ctr_string='0';
		 if($flag_time==-1)
			{
			mysql_data_seek($click_result,0);
			mysql_data_seek($impression_result,0);
			}
		else
			{	
			mysql_data_seek($stat_result,0);
			}
		$FC->addDataset("CTR","parentYAxis=S;color=$selected_colorcode[2];showValues=0");
		$loop_flag=0;
		$temp_time=$beg_time;
		while( $temp_time<=$end_time)
		{
		if($flag_time==-1)
			{	 
				if($ctr_flag==1)
					{
							if($result_arr[$temp_time][0]==0 || $result_arr[$temp_time][1]==0)
								$ctr=$ctr_string;
							else
							{
								$ctr=($result_arr[$temp_time][1]/$result_arr[$temp_time][0]) * 100;
								$ctr=round($ctr,2);
							}
								$FC->addChartData("$ctr");
					 }
					 else
					 {
						 $FC->addChartData("");
					 }

			}
		else
			{
			
					if($ctr_flag==1)
					{
						if($loop_flag==0)
							$row=mysql_fetch_row($stat_result);
							if($end_time==$temp_time)
									{
									 if($flag_time==1 || $flag_time==2)
										{
											
											 if($temp_time == $minus_time)
											 {
											 /*if($fun_imp == 0)
											 $fu_ctr=0;
											 else
											 $fu_ctr=round(($fun_clk/$fun_imp)*100,2);
												
											 $curr_ctr=($curr_ctr+$fu_ctr)/2;*/
											 
											 if($curr_impr == 0)
											 $curr_ctr=0;
											
											 $curr_ctr=round(($curr_clk/$curr_impr)*100,2);
																		 
											 
											 }
											 else
											 {
											/* if($spc_imps_result_spec == 0)
											 $qctr=0;
											 else
											 $qctr=round(($spc_clk_result_spec/$spc_imps_result_spec)*100,2);
											 
											 
											 $curr_ctr=($curr_ctr+$qctr)/2;*/
											 
											 if($curr_impr == 0)
											 $curr_ctr=0;
											
											 $curr_ctr=round(($curr_clk/$curr_impr)*100,2);
											 }
											 
																				
											$FC->addChartData("$curr_ctr");
											break;
										}
										
									
										
										else
										{
										
										
									    $newctr=($spc_clk_result/$spc_imps_result) * 100;
								        $newctr=round($newctr,2);
										$FC->addChartData("$newctr");
										break;
										
										
										
										
									
										
										
										
										
										
										
										
										}
									}
						
				
			
					
							if($row[3]==$temp_time)
						{
							# Add chart values for the above dataset
							$loop_flag=0;
							
							
							
							
						
							
							
				
	if($flag_time==1 || $flag_time==2)
				{
			
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$da=$fun_imp+$row[0];
				if($da==0)
				$ctr=$ctr_string;
				else
                {
                $dclk=$fun_clk+$row[1];
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);
				}		
								
				}
				else
				{
                $loop_flag=0;
				$da=$row[0];
				if($da==0)
				$ctr=$ctr_string;
                else
				{
                $dclk=$row[1];
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);

				}

				
				}
				
				}
				else if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
								
				if($row[3] == $minus_time)
				{
				
				
				
				
				
				$loop_flag=0;
				$da=$spc_imps_result_spec+$row[0];
				if($da==0)
				$ctr=$ctr_string;
				else
                {
                $dclk=$spc_clk_result_spec+$row[1];
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);
				}		
				
			
				
				
				
				}
				else
				{		
				$loop_flag=0;
				
				
				$da=$row[0];
				if($da==0)
				$ctr=$ctr_string;
				else
                                {

                                $dclk=$row[1];
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);
				
				
						
				
				
				}
				}
				
				
				
				}
				
						
							
						}
						else
						{
						
						
						
						
				if($flag_time==1 || $flag_time==2)
				{
				
				if($temp_time == $minus_time)
				{
				
				$loop_flag=0;
				$da=$fun_imp;
				if($da==0)
				{
				$loop_flag=1;
				$ctr=$ctr_string;
				}
				else
                {
                $dclk=$fun_clk;
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);
				}		
				
				
				
				
				
				
				}
				else
				{
				
				$loop_flag=1;
				$ctr=$ctr_string;
				
				
				}
				
				}
			else if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
				//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
				
				
				if($temp_time == $minus_time)
				{
				
				
				
				
				
				
				$loop_flag=0;
				$da=$spc_imps_result_spec;//$row[0]
				if($da==0)
				$ctr=$ctr_string;
				else
                                {

                                $dclk=$spc_clk_result_spec;
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);

				
				
				
				}
				}
				else
				{
				
				$loop_flag=1;
				$ctr=$ctr_string;
				
				
				}
				
				
				
				
				}
						
						
					
						
						
						
							
						}
						
						
						
							
					$FC->addChartData("$ctr");
								
					 }
					 else
					 {
						 $FC->addChartData("");
					 }
			}		 
			if($flag_time==0)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
			}
			else if($flag_time==1)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
			}
		 else if($flag_time==2)
			{
		
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
			}
		 if($flag_time==-1)
			{
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			}	
				
		}


		$retVal[0]=$FC;

		/////////////////////////Money spend
		$FD = new FusionCharts("Line","750","300");
		# set the relative path of the swf file
		
	    if(1==$admin_flag)
			{
			$FD->setSWFPath("../FusionCharts/");
			}
		else
			{
			$FD->setSWFPath("FusionCharts/");
			}
		
		# Set chart attributes
		$strParam="caption=Money spent;xAxisName=Duration;yAxisName=Money spent;numberPrefix=$currency_symbol;decimalPrecision=$no_of_decimalplaces;formatNumberScale=1;rotateNames=1;";
		$FD->setChartParams($strParam);
		 if($flag_time==-1)
			{
			mysql_data_seek($click_result,0);
			mysql_data_seek($impression_result,0);
			}
		else
			{	
			mysql_data_seek($stat_result,0);
			}
		$loop_flag=0;
		$temp_time=$beg_time;
		while( $temp_time<=$end_time)
		{
		 if($flag_time==-1)
			{
				if($money_flag==1)
					{
						if($loop_flag==0)
							$clk_row=mysql_fetch_row($click_result);
						if($clk_row[2]==$temp_time)
						{
							# Add chart values for the above dataset
							$loop_flag=0;
							$moneyspent=$clk_row[1];
						}
						else
						{
							$loop_flag=1;
							$moneyspent='0';
						}
					}
					else
					{							

						$moneyspent='';
					}	 
			}
		else
			{
					if($money_flag==1)
					{
						if($loop_flag==0)
							$row=mysql_fetch_row($stat_result);
							
		
						if($row[3]==$temp_time)
						{
							# Add chart values for the above dataset
							
				if($flag_time!=1 && $flag_time!=2)
				{
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$moneyspent=$row[2]+$spc_cval_result_spec;
				
				
				
				}
				else
				{				
				$loop_flag=0;
				$moneyspent=$row[2];
				}
				}
				else if($flag_time==1 || $flag_time==2)
				{
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$da=$fun_money+$row[2];
				$moneyspent=$da;
				
			
				
				}
				else
				{
				$loop_flag=0;
				$moneyspent=$row[2];
				
				
				}
			
		
				}
				
				
							
						}

						else
						{
						
			if($flag_time!=1 && $flag_time!=2)
			{
			
			if($temp_time == $minus_time)
			{
			if($spc_cval_result_spec !=0)
			{
			$loop_flag=1;
			$moneyspent=$spc_cval_result_spec;
			
			}
			else
			{
			$loop_flag=1;
			$moneyspent='0';
			
			}
			
			}
			else
			{			
				$loop_flag=1;
				$moneyspent='0';
			}
			}
						
			else if($flag_time==1 || $flag_time==2)
			{
			
			if($temp_time == $minus_time)
			{
			
			
			if($fun_money !=0)
			{
			$loop_flag=1;
			$moneyspent=$fun_money;
			
			}
			else
			{
			$loop_flag=1;
			$moneyspent='0';
			
			
			}
			}
			else
			{
			
			$loop_flag=1;
			$moneyspent='0';
			}
			}		
						
		}
			
		
				if($end_time==$temp_time)
							{
							 if($flag_time==1 || $flag_time==2)
								{
								
								 if($temp_time == $minus_time)
								 $curr_money=$curr_money+$fun_money;
								 else
								 $curr_money=$curr_money+$spc_cval_result_spec;
								 
								 $moneyspent=$curr_money;
								}
								
								else
								{
								$moneyspent=$spc_cval_result;
								
								}
							}
					}
					else
					{
						$moneyspent='';
					}
				}
			if($flag_time==0)
			{
				$str=dateformat($temp_time-1,"%b %d");//("M d",$temp_time-1); 
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
				$show_duration="$str";
			}
			else if($flag_time==1)
			{
				$str=dateformat($temp_time-86400,"%b");//"M",$temp_time-86400);
				
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
				$show_duration="$str";
			}
			 else if($flag_time==2)
			{
		
				$str=dateformat($temp_time-86400,"%Y");//("Y",$temp_time-86400);
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
				$show_duration="$str";
			}	
			
			 if($flag_time==-1)
			{
//			$str=dateformat($temp_time,"%d %M %h %p");//date("d M h a",$temp_time);
			$str=datetimeformat($temp_time,"%d %b %l %p");
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			$show_duration="$str";
			}	
			$FD->addChartData("$moneyspent","name=$show_duration;color=$selected_colorcode[2]");
		}

		$retVal[1]=$FD;		
		
		return $retVal;
}	




function plotPublisherGraphs($show,$flag_time,$beg_time,$end_time,$selected_colorcode,$uid,$referral_system,$id =0)
{


if($flag_time == 0)
$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
//else if($flag_time == 1)
//$minus_time=mktime(0,0,0,date("n",$end_time)-1,date("j",$end_time),date("Y",$end_time));
//else if($flag_time == 2)
//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time),date("Y",$end_time)-1);




$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits_imps=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$admin_flag=0;

$path_str=substr(getcwd(),strlen(getcwd())-5);
if("admin"==$path_str)
	{
	$admin_flag=1;
	
	}

		
	
$ctr_flag=0;
		$money_flag=0;
		

	global $mysql;
	$bid_str="and bid=$id";
	if($id==0)
		{
		$bid_str="";
		}
	
	if($id==0)	
	$idsstr="NA";
	else
	$idsstr=$id;
	
	
		
		//$retVal=array();
	if($flag_time==0)
			  {
				$table_name="publisher_daily_statistics";
			  }
			 else if($flag_time==2)
				{
				$table_name="publisher_yearly_statistics";
				$beg_time=$mysql->echo_one("select min(time) from $table_name where uid=$uid");
				}
			 else if($flag_time==1)
			 {
				$table_name="publisher_monthly_statistics";
			 }
		if($flag_time==-1)
			{
			$impression_result=mysql_query("select COALESCE(sum(cnt),0), time from publisher_impression_daily where pid=$uid $bid_str and time>$beg_time group by time order by time");
		//	echo "select COALESCE(sum(cnt),0)time from publisher_impression_daily where pid=$uid $bid_str and time>=$beg_time group by time";
			
			if($uid >0)
			$click_result=mysql_query("select COALESCE(count(id),0),COALESCE(sum(	publisher_profit),0),time from ppc_daily_clicks where pid=$uid $bid_str and time>$beg_time and time < $endtimes  group by time order by time");
			else
			$click_result=mysql_query("select COALESCE(count(id),0),COALESCE(sum(	clickvalue),0),time from ppc_daily_clicks where pid=$uid $bid_str and time>$beg_time and time < $endtimes  group by time order by time");
			
		
			
				$result_arr=array();
				while($row=mysql_fetch_row($impression_result))
				{
					$key=$row[1];
					$result_arr[$key][0]=$row[0];
					$result_arr[$key][1]=0;
				
				}
				while($row=mysql_fetch_row($click_result))
				{
					$key=$row[2];
					if($result_arr[$key][0]=="")
					$result_arr[$key][0]=0;
					$result_arr[$key][1]=$row[0];
					if($result_arr[$key][0]>0 and $result_arr[$key][1]>0)
							
					$ctr_flag=1;
					if($row[1]>0)
						$money_flag=1;
				}
				
			


			}
									
			
		else
			{
			if($uid >0)	
			$stat_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0),time from `$table_name` where uid=$uid $bid_str and time>=$beg_time group by time order by time");
			else
			$stat_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),time from `$table_name` where uid=$uid $bid_str and time>=$beg_time group by time order by time");
			
			
			
			
$spc_imps_result=0;
$spc_clk_result=0;
$spc_cval_result=0;			
		
$spc_imps_result=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid=$uid $bid_str and time>$spec_time_limits_imps and time < $endtimes order by time");

$spc_imps_result_spec=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid=$uid $bid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");



$spc_clk_result=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits_imps and time < $endtimes  order by time");


$spc_clk_result_spec=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");





if($uid >0)
{
$spc_cval_result=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits_imps and time < $endtimes  order by time");			
$spc_cval_result_spec=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");			
}
else
{
$spc_cval_result=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits_imps and time < $endtimes  order by time");			
$spc_cval_result_spec=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");				
	
}



			
			}
			
		//	echo "select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0),time from `$table_name` where uid=$uid $bid_str and time>=$beg_time group by time order by time";
			
	
	
	
	
	
	if($beg_time==0)
$beg_time=mktime(0,0,0,1,1,date("y",time()));
		


		if(1==$admin_flag)
			{
		# Include FusionCharts PHP Class
			include_once('../FusionCharts/Class/FusionCharts_Gen.php');
			}
		else
			{
			include_once('FusionCharts/Class/FusionCharts_Gen.php');
			}
		
		# Create Multiseries Column3D chart object using FusionCharts PHP Class
		$FC = new FusionCharts("MSColumn3DLineDY","750","300");
		
		# Set the relative path of the swf file
		
		# Include FusionCharts PHP Class
	//	include_once('FusionCharts/Class/FusionCharts_Gen.php');
		
	
			/////////////////////////Money spend
		$FD = new FusionCharts("MSLine","750","300");
		# set the relative path of the swf file
	//	$FD->setSWFPath("FusionCharts/");
		
		
			
	    if(1==$admin_flag)
			{
			$FD->setSWFPath("../FusionCharts/");
			}
		else
			{
			$FD->setSWFPath("FusionCharts/");
			}
			
		
		# Set chart attributes
		$strParam="caption=Money gained;xAxisName=Duration;yAxisName=Money gained;numberPrefix=$currency_symbol;decimalPrecision=$no_of_decimalplaces;formatNumberScale=1;rotateNames=1;";
$FD->setChartParams($strParam);



	# Create Multiseries Column3D chart object using FusionCharts PHP Class
		//////if($id==-1)
		/////	$FC = new FusionCharts("MSColumn3D","750","300");
		/////else
			$FC = new FusionCharts("MSColumn3DLineDY","750","300");
		
		# Set the relative path of the swf file
	//	$FC->setSWFPath("FusionCharts/");
		
		
		
		  if(1==$admin_flag)
			{
			$FC->setSWFPath("../FusionCharts/");
			}
		else
			{
			$FC->setSWFPath("FusionCharts/");
			}
		
		
		
		
		
		# Store chart attributes in a variable
		$strParam="caption=Clicks and Impressions;subcaption=Comparison;xAxisName=Duration;yAxisName=Statistics; pYAxisName=Count;sYAxisName=CTR (%);decimalPrecision=$no_of_decimalplaces;rotateNames=1; showvalues=0;";
		////if($id==-1)
		///	$strParam="caption=Clicks;xAxisName=Duration;yAxisName=Count;decimalPrecision=0;rotateNames=1; showvalues=0;";
		# Set chart attributes
		$FC->setChartParams($strParam);
		
		$curr_impr=0;
		$curr_clk=0;
		$curr_ctr=0;
		$curr_money=0;
		
		
	////////////////////////////////////////////	$ctr_flag=0;
	//	$money_flag=0;
		
	
		

	
		
		 if($flag_time==1)
			{
			$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));

			if($uid >0)
			$current_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time");
		    else
		    $current_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time");
		    
			
			
			
			
			
			//	echo "select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time";
			$current_row=mysql_fetch_row($current_result);
			$curr_clk=$current_row[1]+$spc_clk_result;
			$curr_impr=$current_row[0]+$spc_imps_result;
			
			
			
			if($curr_impr>0)
				$curr_ctr=round(($curr_clk/$curr_impr)*100,2);
			
		
			if($curr_ctr>0)
				$ctr_flag=1;
			
			$curr_money=$current_row[2]+$spc_cval_result;
			if($curr_money>0)
				$money_flag=1;


             $fun_imp=0;
			 $fun_clk=0;
			 $fun_money=0;				
				
				
			$fun_imp=getCurrentMonthStatistics($flag_time,$mysql,"impression","",$uid,$idsstr,0,0,1);	
			
			if($fun_imp != 0)
			{
			$fun_imp_arr=explode('_',$fun_imp);
			
			$fun_imp=$fun_imp_arr[0];
			$minus_time=$fun_imp_arr[1];
			
			
			}
			
			
			
			
			$fun_clk=getCurrentMonthStatistics($flag_time,$mysql,"clk_count","id",$uid,$idsstr,1,1,1);
			
			if($fun_clk != 0)
			{
			$fun_clk_arr=explode('_',$fun_clk);
			
			$fun_clk=$fun_clk_arr[0];
			$minus_time=$fun_clk_arr[1];
			
			
			}
			
			
			
			
			if($uid >0)	
			$fun_money=getCurrentMonthStatistics($flag_time,$mysql,"publisher_profit","publisher_profit",$uid,$idsstr,1,2,1);	
			else
			$fun_money=getCurrentMonthStatistics($flag_time,$mysql,"money_spent","clickvalue",$uid,$idsstr,1,2,1);	
			
			
			
			if($fun_money != 0)
			{
			$fun_money_arr=explode('_',$fun_money);
			
			$fun_money=$fun_money_arr[0];
			$minus_time=$fun_money_arr[1];
			
			
			}
			
			
				
				
				if($fun_imp>0)
				$curr_ctr1=round(($fun_clk/$fun_imp)*100,2);
			
			if($curr_ctr1>0)
				$ctr_flag=1;
			
			if($fun_money>0)
				$money_flag=1;
				
				
				
		
			}
		
				 if($flag_time==2)
			{
				
			$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			
			if($uid >0)
			$current_day_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time ");
			else
			$current_day_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time ");
			
			
			$current_day_row=mysql_fetch_row($current_day_result);
			
			$year_time=mktime(0,0,0,1,1,date("Y",time()));
			
			if($uid >0)
			$current_month_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from `publisher_monthly_statistics` where uid=$uid $bid_str and time>$year_time");
			else
			$current_month_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `publisher_monthly_statistics` where uid=$uid $bid_str and time>$year_time");
	
			
			
			$current_month_row=mysql_fetch_row($current_month_result);
			
			
			$curr_clk=$current_day_row[1]+$current_month_row[1]+$spc_clk_result;
			$curr_impr=$current_day_row[0]+$current_month_row[0]+$spc_imps_result;
			if($curr_impr>0)
				$curr_ctr=round(($curr_clk/$curr_impr)*100,2);
			
			if($curr_ctr>0)
				$ctr_flag=1;
			
			$curr_money=$current_day_row[2]+$current_month_row[2]+$spc_cval_result;
			if($curr_money>0)
				$money_flag=1;
		
		
		
		     $fun_imp=0;
			 $fun_clk=0;
			 $fun_money=0;		
				
		    $fun_imp=getCurrentMonthStatistics($flag_time,$mysql,"impression","",$uid,$idsstr,0,0,1);	
			
			if($fun_imp != 0)
			{
			$fun_imp_arr=explode('_',$fun_imp);
			
			$fun_imp=$fun_imp_arr[0];
			$minus_time=$fun_imp_arr[1];
			
			
			}
			
			$fun_clk=getCurrentMonthStatistics($flag_time,$mysql,"clk_count","id",$uid,$idsstr,1,1,1);	
			if($fun_clk != 0)
			{
			$fun_clk_arr=explode('_',$fun_clk);
			
			$fun_clk=$fun_clk_arr[0];
			$minus_time=$fun_clk_arr[1];
			
			
			}
			
			if($uid >0)
			$fun_money=getCurrentMonthStatistics($flag_time,$mysql,"publisher_profit","publisher_profit",$uid,$idsstr,1,2,1);	
			else
			$fun_money=getCurrentMonthStatistics($flag_time,$mysql,"money_spent","clickvalue",$uid,$idsstr,1,2,1);	
			
			
		

			
			
			
			if($fun_money != 0)
			{
			$fun_money_arr=explode('_',$fun_money);
			
			$fun_money=$fun_money_arr[0];
			$minus_time=$fun_money_arr[1];
			
			
			}
			
			
			if($fun_imp>0)
				$curr_ctr1=round(($fun_clk/$fun_imp)*100,2);
			
			if($curr_ctr1>0)
				$ctr_flag=1;
			
			if($fun_money>0)
				$money_flag=1;
			
			}
			
			if($curr_impr==0)
			{
			$curr_impr='';
			}
			
			if($curr_clk==0)
			{
			$curr_clk='';
			}
			
			if($fun_imp==0)
			{
			$fun_imp='';
			}
			
			if($fun_clk==0)
			{
			$fun_clk='';
			}
			
		
		$temp_time=$beg_time;
		while($temp_time<=$end_time)
		{
	//	echo  "$temp_time=$beg_time=$end_time<br>";flush();
			if($flag_time==0)
			{
				$str=dateformat($temp_time-1,"%b %d"); 
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
				$show_duration="$str";
			}
			else if($flag_time==1)
			{
				$str=dateformat($temp_time-86400,"%b");
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
				$show_duration="$str";
			}
			else if($flag_time==2)
			{
				$str=dateformat($temp_time-86400,"%Y");
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
				$show_duration="$str";
			}
			if($flag_time==-1)
			{
					$str=datetimeformat($temp_time,"%d %b %l %p");
					$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
					$show_duration="$str";
			}
		//	echo "$show_duration <br>";
	
			
		
			$FC->addCategory("$show_duration");
			$FD->addCategory("$show_duration");
			if($flag_time!=-1)
			{
				
					if( $ctr_flag==0 || $money_flag==0)
					{
						$row=mysql_fetch_row($stat_result);
						if(($row[0]>0 && $row[1]>0) || (($spc_imps_result >0 || $spc_imps_result_spec >0) && ($spc_clk_result >0 || $spc_clk_result_spec >0)))
						{
							$ctr_flag=1;
						}
						if( $row[2]>0 || $spc_cval_result >0 || $spc_cval_result_spec >0)
						{
							$money_flag=1;
						}
					}
			}
		//echo "$show_duration";
		
		}
//if($id!=-1)		
//{
		if($flag_time==-1)
			{
			mysql_data_seek($click_result,0);
			mysql_data_seek($impression_result,0);
			}
		else
			{	
			mysql_data_seek($stat_result,0);
			}				
		$temp_time=$beg_time;
		$FC->addDataset("Impressions","showvalues=0");
		$loop_flag=0;
		while( $temp_time<=$end_time)
		{
		//echo " $temp_time<=$end_time<br>";
		if($flag_time==-1)
		{
			if($loop_flag==0)
				$imp_row=mysql_fetch_row($impression_result);
	//			echo "$imp_row[1]==$temp_time<br>";
			if($imp_row[1]==$temp_time)
			{
				# Add chart values for the above dataset
				$loop_flag=0;
				$FC->addChartData("$imp_row[0]");
			}
			else
			{
				$loop_flag=1;
				$FC->addChartData('');
			}
		}
		else
		{
		
//	$minus_time=mktime(0,0,0,date("n",$end_time)-1,date("j",$end_time),date("Y",$end_time));
			if($loop_flag==0)
				$row=mysql_fetch_row($stat_result);
				
				
				

				if($end_time==$temp_time)
				{
				 if($flag_time==1 || $flag_time==2)
					{
					    if($temp_time == $minus_time)
					    $curr_impr=$curr_impr+$fun_imp;
						else
						$curr_impr=$curr_impr+$spc_imps_result_spec;
						
						
						
						
						if($curr_impr == 0)
						$FC->addChartData('');
						else
						$FC->addChartData("$curr_impr");
						break;
					}
					else
					{
					
					if($spc_imps_result == 0)
					$FC->addChartData('');
					else
					$FC->addChartData("$spc_imps_result");
					break;
					
					
					
					
					}
				}
				
				
			
		//	if($temp_time != $minus_time)
		//	{	
		
		
            
		
			
			if($row[3]==$temp_time)
			{
			
			
			
			if($flag_time==1 || $flag_time==2)
				{
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$da=$fun_imp+$row[0];
				$FC->addChartData("$da");
			
				}
				else
				{
				$loop_flag=0;
				$FC->addChartData("$row[0]");
				
				
				}
				
				}
				else if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$s_datass=$row[0]+$spc_imps_result_spec;
				$FC->addChartData("$s_datass");
				
				
				}
				else
				{				
				$loop_flag=0;
				$FC->addChartData("$row[0]");
				}
				}
				
				
				
				
				
				/*else
				{
				
				$loop_flag=0;
				$FC->addChartData("$row[0]");
				
				
				}*/
					
			
			
			
			
			
			
			
			
			
			
			
			
				
			}
			else
			{
			
			
			if($flag_time==1 || $flag_time==2)
				{
				
				if($temp_time == $minus_time)
				{
				
				
				
				if($fun_imp !=0)
			{	
				
			$loop_flag=1;
			$FC->addChartData("$fun_imp");
		
			}
			else
			{
			$loop_flag=1;
			$FC->addChartData('');
			
			
			}
			
				
				
			
			
			
			
			
						
				
				}
				else
				{
				$loop_flag=1;
				$FC->addChartData('');
				
				
				}
				
				}
				else if($flag_time!=1 && $flag_time!=2)
			{
			
			if($temp_time == $minus_time)
			{
			if($spc_imps_result_spec !=0)
			{
			$loop_flag=1;
			$FC->addChartData("$spc_imps_result_spec");
			}
			else
			{
			$loop_flag=1;
			$FC->addChartData('');
			
			}
			
			}
			else
			{			
				$loop_flag=1;
				$FC->addChartData('');
			}
			}
				
				
				
				
				/*else
				{
				
				$loop_flag=1;
				$FC->addChartData('');
				
				
				}*/
					
			
			
			
				
			}
			
		//	}
			
			
				
		}	
					//echo $row[3]."==".$temp_time.$row[0]."<br>";
			if($flag_time==0)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
			}
			else if($flag_time==1)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
			}
			 else if($flag_time==2)
				{
		
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
			}
	if($flag_time==-1)
			{
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			}		
		}
//}		
		if($flag_time==-1)
			{
			mysql_data_seek($click_result,0);
			mysql_data_seek($impression_result,0);
			}
		else
			{	
			mysql_data_seek($stat_result,0);
			}
		$temp_time=$beg_time;
		$FC->addDataset("Clicks","showValues=0");
		$loop_flag=0;
		while( $temp_time<=$end_time)
		{
		
		if($flag_time==-1)
		{
		if($loop_flag==0)
				$clk_row=mysql_fetch_row($click_result);
				
			if($clk_row[2]==$temp_time)
			{
				# Add chart values for the above dataset
				$loop_flag=0;
				$FC->addChartData("$clk_row[0]");
			}
			else
			{
				$loop_flag=1;
				$FC->addChartData('');
			}
		}
		else
		{
		
		
		
			if($loop_flag==0)
				$row=mysql_fetch_row($stat_result);
		
		
		
				if($end_time==$temp_time)
				{
				
				 if($flag_time==1 || $flag_time==2)
					{
			
					    if($temp_time == $minus_time)
					    $curr_clk=$curr_clk+$fun_clk;
						else
						$curr_clk=$curr_clk+$spc_clk_result_spec;
						
						
						if($curr_clk == 0)
						$FC->addChartData('');
						else
						$FC->addChartData("$curr_clk");
						break;
					}
					
					else
					{
					
					
					if($spc_clk_result == 0)
					$FC->addChartData('');
					else
					$FC->addChartData("$spc_clk_result");
					break;
					
					}
				}
			
			//if($temp_time != $minus_time)
			//{		
			

			
			
			if($row[3]==$temp_time)
			{
			
				# Add chart values for the above dataset
							
				if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
				//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
				
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$s_datass=$row[1]+$spc_clk_result_spec;
				$FC->addChartData("$s_datass");
				
				
				}
				else
				{				
				$loop_flag=0;
				$FC->addChartData("$row[1]");
				}
				}
				else if($flag_time==1 || $flag_time==2)
				{
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$da=$fun_clk+$row[1];
				$FC->addChartData("$da");
				
				
			
				
				}
				else
				{
				$loop_flag=0;
				$FC->addChartData("$row[1]");
				
				
				}
					
					
			
		
				
				}
		
				
				
			}
			
			else
			{
			if($flag_time!=1 && $flag_time!=2)
			{
			//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
			if($temp_time == $minus_time)
			{
			if($spc_clk_result_spec !=0)
			{
			$loop_flag=1;
			$FC->addChartData("$spc_clk_result_spec");
			}
			else
			{
			$loop_flag=1;
			$FC->addChartData('');
			
			}
			
			}
			else
			{			
				$loop_flag=1;
				$FC->addChartData('');
			}
			}
			else if($flag_time==1 || $flag_time==2)
				{
			
		
			if($temp_time == $minus_time)
			{
			
			
			if($fun_clk !=0)
			{
			$loop_flag=1;
			$FC->addChartData("$fun_clk");
			}
			else
			{
			$loop_flag=1;
			$FC->addChartData('');
			
			
			}
			
				
			
			
			}
			else
			{
			
			$loop_flag=1;
			$FC->addChartData('');
			}
			}
			}
			
			//}
			
			
		}
			
		//	echo "$temp_time<=$end_time **$row[3] && $curr_clk<br>";
			if($flag_time==0)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
			}
			else if($flag_time==1)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
			}
		 else if($flag_time==2)
			{
		
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
			}
			if($flag_time==-1)
			{
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			}		
		}
			
		$ctr_string='0';
//if($id!=-1)
//{
		if($flag_time==-1)
			{
			mysql_data_seek($click_result,0);
			mysql_data_seek($impression_result,0);
			}
		else
			{	
			mysql_data_seek($stat_result,0);
			}
		$FC->addDataset("CTR","parentYAxis=S;color=$selected_colorcode[2];showValues=0");
		$loop_flag=0;
		$temp_time=$beg_time;
		while( $temp_time<=$end_time)
		{
		if($flag_time==-1)
			{	 
			
		//	echo $ctr_flag;
			
			
						if($ctr_flag==1)
					{
							if($result_arr[$temp_time][0]==0 || $result_arr[$temp_time][1]==0)
								$ctr=$ctr_string;
							else
							{
								$ctr=($result_arr[$temp_time][1]/$result_arr[$temp_time][0]) * 100;
								$ctr=round($ctr,2);
							}
								$FC->addChartData("$ctr");
					 }
					 else
					 {
						 $FC->addChartData("");
					 }

			
			
		}
		else
			{
		
					if($ctr_flag==1)
					{
						if($loop_flag==0)
							$row=mysql_fetch_row($stat_result);
							if($end_time==$temp_time)
									{
									 if($flag_time==1 || $flag_time==2)
										{
											
											 if($temp_time == $minus_time)
											 {
											/* 
											 if($fun_imp == 0)
											 $fu_ctr=0;
											 else
											 $fu_ctr=round(($fun_clk/$fun_imp)*100,2);
											 $curr_ctr=($curr_ctr+$fu_ctr)/2;
											*/	
											
											 if($curr_impr == 0)
											 $curr_ctr=0;
											
											 $curr_ctr=round(($curr_clk/$curr_impr)*100,2);						 
											 
											 }
										     else
											 {
											 if($curr_impr == 0)
											 $curr_ctr=0;
											
											 $curr_ctr=round(($curr_clk/$curr_impr)*100,2);
											 
										
											 
											 }
											 
																				
											$FC->addChartData("$curr_ctr");
											break;
										}
														
										
										else
										{
										
										$newctr=($spc_clk_result/$spc_imps_result) * 100;
								        $newctr=round($newctr,2);
										$FC->addChartData("$newctr");
										break;
										
										
										
										}
									}
							
							
							
				
							
							
						if($row[3]==$temp_time)
						{
							# Add chart values for the above dataset
							$loop_flag=0;
							
							
							
					
							
							
				
	if($flag_time==1 || $flag_time==2)
				{
				
				if($row[3] == $minus_time)
				{
				
				$loop_flag=0;
				$da=$fun_imp+$row[0];
				if($da==0)
				$ctr=$ctr_string;
				else
                {
                $dclk=$fun_clk+$row[1];
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);
				}		
								
				
				
				}
				else
				{

                $loop_flag=0;
				$da=$row[0];
				if($da==0)
				$ctr=$ctr_string;
                else
				{
                $dclk=$row[1];
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);

				}


				
			
				
				
				}
				
				}
				else if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
				//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
				
				
				if($row[3] == $minus_time)
				{
				
				
				
				
				$loop_flag=0;
				$da=$spc_imps_result_spec+$row[0];
				if($da==0)
				$ctr=$ctr_string;
				else
                                {

                                $dclk=$spc_clk_result_spec+$row[1];
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);



				}		
				
				
				
				
				
				
				
				
				
				
				
				
				
				}
				else
				{		
				$loop_flag=0;
				
				
				$da=$row[0];
				if($da==0)
				$ctr=$ctr_string;
				else
                                {

                                $dclk=$row[1];
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);
				
				
						
				
				
				}
				}
				
				
				
				}
				
		
							
							
							
						}
						else
						{
						
						
						
						
						if($flag_time==1 || $flag_time==2)
				{
				
				if($temp_time == $minus_time)
				{
				
				
				$loop_flag=0;
				$da=$fun_imp;
				if($da==0)
				{
				$loop_flag=1;
				$ctr=$ctr_string;
				}
				else
                {
                $dclk=$fun_clk;
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);
				}		
				
			
				
				
				}
				else
				{
				
				$loop_flag=1;
				$ctr=$ctr_string;
				
				
				}
				
				}
			else if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
				//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
				
				
				if($temp_time == $minus_time)
				{
				
				
				
				
				
				
				$loop_flag=0;
				$da=$spc_imps_result_spec;//$row[0]
				if($da==0)
				$ctr=$ctr_string;
				else
                                {

                                $dclk=$spc_clk_result_spec;
				$ctr=($dclk/$da) * 100;
				$ctr=round($ctr,2);

				
				
				
				}
				}
				else
				{
				
				$loop_flag=1;
				$ctr=$ctr_string;
				
				
				}
				
				
				
				
				}
						
						
						
						
						
						
						
						
						
						
						
						
						
							//$loop_flag=1;
							//$ctr=$ctr_string;
							
						}
						$FC->addChartData("$ctr");
						
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
					 }
					 else
					 {
						 $FC->addChartData("");
					 }
			}		 
			
			if($flag_time==0)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
			}
			else if($flag_time==1)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
			}
		 else if($flag_time==2)
			{
		
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
			}
		if($flag_time==-1)
			{
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			}	
				
		}

//}

		$retVal[0]=$FC;

		if($flag_time==-1)
			{
			mysql_data_seek($click_result,0);
			mysql_data_seek($impression_result,0);
			}
		else
			{	
			mysql_data_seek($stat_result,0);
			}
		$loop_flag=0;
		$temp_time=$beg_time;
		$FD->addDataset("Click share","showvalues=0");
		while( $temp_time<=$end_time)
		{
		
		if($flag_time==-1)
			{
		//	echo $money_flag;
				if($money_flag==1)
					{
						if($loop_flag==0)
							$clk_row=mysql_fetch_row($click_result);
						if($clk_row[2]==$temp_time)
						{
							# Add chart values for the above dataset
							$loop_flag=0;
							$moneyspent=$clk_row[1];
						}
						else
						{
							$loop_flag=1;
							$moneyspent='0';
						}
					}
					else
					{
						$moneyspent='';
					}	 
			}
		else
			{
					if($money_flag==1)
					{
						if($loop_flag==0)
							$row=mysql_fetch_row($stat_result);
							
		
						if($row[3]==$temp_time)
						{
							# Add chart values for the above dataset
							
				if($flag_time!=1 && $flag_time!=2)
				{
				
				if($row[3] == $minus_time)
				{
				$loop_flag=0;
				$moneyspent=$row[2]+$spc_cval_result_spec;
				
				
				
				}
				else
				{				
				$loop_flag=0;
				$moneyspent=$row[2];
				}
				}
				else if($flag_time==1 || $flag_time==2)
				{
				
				if($row[3] == $minus_time)
				{
				
				$loop_flag=0;
				$da=$fun_money+$row[2];
				$moneyspent=$da;
				
			
			
				
				
						
				
				}
				else
				{
				$loop_flag=0;
				$moneyspent=$row[2];
				
				
				}
			
		
				}
				
				
							
						}

						else
						{
						
			if($flag_time!=1 && $flag_time!=2)
			{
			
			if($temp_time == $minus_time)
			{
			if($spc_cval_result_spec !=0)
			{
			$loop_flag=1;
			$moneyspent=$spc_cval_result_spec;
			
			}
			else
			{
			$loop_flag=1;
			$moneyspent='0';
			
			}
			
			}
			else
			{			
				$loop_flag=1;
				$moneyspent='0';
			}
			}
						
			else if($flag_time==1 || $flag_time==2)
			{
			
			if($temp_time == $minus_time)
			{
			
			
			if($fun_money !=0)
			{
			
			$loop_flag=1;
			$moneyspent=$fun_money;
			
			
			
			}
			else
			{
			$loop_flag=1;
			$moneyspent='0';
			
			
			}
			}
			else
			{
			
			$loop_flag=1;
			$moneyspent='0';
			}
			}		
						
		}
			
		
				if($end_time==$temp_time)
							{
							 if($flag_time==1 || $flag_time==2)
								{
								
								 if($temp_time == $minus_time)
								 $curr_money=$curr_money+$fun_money;
								 else
								 $curr_money=$curr_money+$spc_cval_result_spec;
								 
								 $moneyspent=$curr_money;
								}
								else
								{
								$moneyspent=$spc_cval_result;
								
								}
							}
					}
					else
					{
						$moneyspent='';
					}
				}
			if($flag_time==0)
			{
				$str=dateformat($temp_time-1,"%b %d"); 
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
				$show_duration="$str";
			}
			else if($flag_time==1)
			{
				$str=dateformat($temp_time-86400,"%b");
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
				$show_duration="$str";
			}
			 else if($flag_time==2)
			{
		
				$str=dateformat($temp_time-86400,"%Y");
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
				$show_duration="$str";
			}	
			
			if($flag_time==-1)
			{
			$str=datetimeformat($temp_time,"%d %b %l %p");
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			$show_duration="$str";
			}	
			
			//$FD->addChartData("$moneyspent","name=$show_duration;color=$selected_colorcode[2]");
		//			echo "..$temp_time....$end_time...<br>";
$FD->addChartData($moneyspent);
			//	$FD->addChartData("$moneyspent","name=$show_duration;color=$selected_colorcode[2]");
		//	 echo "$moneyspent..$show_duration..<br>";
		}

		$retVal[1]=$FD;		

		
		
		
if($referral_system==1 && $id==0 )
	{
	if($flag_time==0)
	  {
		$referral_table="daily_referral_statistics";
	  }
	 else if($flag_time==1)
	 	{
		$referral_table="monthly_referral_statistics";
		
	 	}
	 else  if($flag_time==2)
	 {
		$referral_table="yearly_referral_statistics";
				
		$beg_time=$mysql->echo_one("select min(time) from $table_name where uid=$uid");
		
	 }


if($beg_time==0)
$beg_time=mktime(0,0,0,1,1,date("y",time()));

	 
		$ref_flag=0;
		$curr_ref=0;

 if($flag_time==1)
			{
			$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$current_result=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from `daily_referral_statistics` where pid=$uid and time>$month_time ");
			$current_row=mysql_fetch_row($current_result);
			$curr_ref=$current_row[1]+$current_row[0];
			
			
			if($curr_ref>0)
				$ref_flag=1;
		
		
		$data_adv_ref=0;
		$data_adv_ref=getAdvRefMonthStatistics($flag_time,$mysql,$uid,1);
		
		
		if($data_adv_ref != 0)
			{
			$data_adv_ref_arr=explode('_',$data_adv_ref);
			
			$data_adv_ref=$data_adv_ref_arr[0];
			$minus_time=$data_adv_ref_arr[1];
			
			
			}
			
		if($data_adv_ref>0)
				$ref_flag=1;
		
		
		
		$data_pub_ref=0;
		$data_pub_ref=getPubRefMonthStatistics($flag_time,$mysql,$uid,1);
		
		
		if($data_pub_ref != 0)
			{
			$data_pub_ref_arr=explode('_',$data_pub_ref);
			
			$data_pub_ref=$data_pub_ref_arr[0];
			$minus_time=$data_pub_ref_arr[1];
			
			
			}
			
		if($data_pub_ref>0)
				$ref_flag=1;
		
		
		
		
		
		
			}
		
				 if($flag_time==2)
			{
			
			
			 $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$current_day_result=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from `daily_referral_statistics` where pid=$uid  and time>$month_time ");
			
			$current_day_row=mysql_fetch_row($current_day_result);
			$year_time=mktime(0,0,0,1,1,date("Y",time()));
			$current_month_result=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from `monthly_referral_statistics` where pid=$uid  and time>$year_time");
			$current_month_row=mysql_fetch_row($current_month_result);
			$curr_ref=$current_day_row[1]+$current_month_row[1]+$current_day_row[0]+$current_month_row[0];
		//	echo "select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from `daily_referral_statistics` where pid=$uid  and time>$month_time "."select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from `monthly_referral_statistics` where pid=$uid  and time>$year_time";
	//	echo $curr_ref;
			if($curr_ref>0)
					$ref_flag=1;
		
		
		
		
		$data_adv_ref=0;
		$data_adv_ref=getAdvRefMonthStatistics($flag_time,$mysql,$uid,1);
		
		
		if($data_adv_ref != 0)
			{
			$data_adv_ref_arr=explode('_',$data_adv_ref);
			
			$data_adv_ref=$data_adv_ref_arr[0];
			$minus_time=$data_adv_ref_arr[1];
			
			
			}
			
		if($data_adv_ref>0)
				$ref_flag=1;
		
		
		
		
		$data_pub_ref=0;
		$data_pub_ref=getPubRefMonthStatistics($flag_time,$mysql,$uid,1);
		
		
		if($data_pub_ref != 0)
			{
			$data_pub_ref_arr=explode('_',$data_pub_ref);
			
			$data_pub_ref=$data_pub_ref_arr[0];
			$minus_time=$data_pub_ref_arr[1];
			
			
			}
			
		if($data_pub_ref>0)
				$ref_flag=1;
		
		
			}


		 if($flag_time==-1)
			{
			$ref_tot=0;
			$pub_ref_result=mysql_query("select COALESCE(sum(pub_ref_profit ),0),time from ppc_daily_clicks where pub_rid=$uid and time>$beg_time and time < $endtimes  group by time order by time");
			$adv_ref_result=mysql_query("select COALESCE(sum(adv_ref_profit),0),time from ppc_daily_clicks where adv_rid=$uid and time>$beg_time and time < $endtimes  group by time order by time");
			
		//	echo "select COALESCE(sum(pub_ref_profit ),0),time from ppc_daily_clicks where pub_rid=$uid and time>=$beg_time and time < $endtimes  group by time order by time";
		//	echo "select COALESCE(sum(adv_ref_profit),0),time from ppc_daily_clicks where adv_rid=$uid and time>=$beg_time and time < $endtimes  group by time order by time";
			
			$result_arr=array();
				while($row=mysql_fetch_row($pub_ref_result))
				{
					$key=$row[1];
					$result_arr[$key][0]=$row[0];
						$result_arr[$key][1]=0;
					$ref_tot+=$row[0];			
				}
				
				while($row=mysql_fetch_row($adv_ref_result))
				{
					$key=$row[1];
					if($result_arr[$key][0]=="")
					$result_arr[$key][0]=0;
					$result_arr[$key][1]=$row[0];				
					$ref_tot+=$row[0];			
				}
				
				
		if($ref_tot>0)
			$ref_flag=1;
			
			}
			else
			{
					$stat_result=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0),time from `$referral_table` where pid=$uid and time>=$beg_time  group by time order by time");
					
				if(mysql_num_rows(	$stat_result)>0)
				$ref_flag=1;
				
				
				
			$spc_pub_ref_result=0;
			$spc_adv_ref_result=0;	
				
$spc_pub_ref_result=$mysql->echo_one("select COALESCE(sum(pub_ref_profit ),0) from ppc_daily_clicks where pub_rid=$uid and time>$spec_time_limits_imps and time < $endtimes order by time");
$spc_pub_ref_result_new=$mysql->echo_one("select COALESCE(sum(pub_ref_profit ),0) from ppc_daily_clicks where pub_rid=$uid and time>$spec_time_limits and time <= $spec_time_limits_imps   order by time");



$spc_adv_ref_result=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where adv_rid=$uid and time>$spec_time_limits_imps and time < $endtimes order by time");	
$spc_adv_ref_result_new=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where adv_rid=$uid and time>$spec_time_limits and time <= $spec_time_limits_imps   order by time");	

				
				if($spc_pub_ref_result >0 || $spc_adv_ref_result >0 || $spc_pub_ref_result_new >0 || $spc_adv_ref_result_new >0)
				$ref_flag=1;
				
				
				
				
				
				
				
				
				
					
				
				
				
				
				
				
				
				
					
	}
	
	
	
	
	if($curr_ref >0)
	$ref_flag=1;
	
			
		//	echo "select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0),time from `$referral_table` where pid=$uid and time>=$beg_time  group by time order by time";
			

		$ref_string='0';
			
		/// Referral gain 
		# Set chart attributes
		$FD->addDataset("Referral share","showvalues=0");
		$loop_flag=0;
		$temp_time=$beg_time;
		while( $temp_time<=$end_time)
		{
//echo "$end_time==$temp_time<br>";
			 if($flag_time==-1)
			{	 
				if($ref_flag==1)
					{		
						if($result_arr[$temp_time][0]==0  && $result_arr[$temp_time][1]==0 )
							{
									$referralamount=$ref_string;	
							}
							else
							{
								$referralamount=round( ($result_arr[$temp_time][0]+$result_arr[$temp_time][1] ) ,2);
							}
						}
					
				else
					 {
						$referralamount='';
					 }
		
			}
			else
			{
			//echo $ref_flag;
				if($ref_flag==1)
					{
						if($loop_flag==0)
							$row=mysql_fetch_row($stat_result);
						if($row[2]==$temp_time)
						{
							# Add chart values for the above dataset
							
							
				if($flag_time==1 || $flag_time==2)
				{
				
				if($row[2] == $minus_time)
				{
				$loop_flag=0;
				$da=$row[0]+$row[1];
				$da1=$data_adv_ref+$data_pub_ref;
				
				if($da==0 && $da1==0)
				$referralamount=$ref_string;	
				else
				$referralamount=round($da+$da1,2);
			
				
				}
				else
				{
				$loop_flag=0;
				if($row[0]+$row[1]==0)
				$referralamount=$ref_string;	
				else
				$referralamount=round($row[0]+$row[1],2);
				
				}
					
					
			
		
				
				}
				else if($flag_time!=1 && $flag_time!=2)
				{
				$s_datass=0;
							
				
				if($row[2] == $minus_time)
				{
				$loop_flag=0;
				$da=$row[0]+$row[1];
				$da1=$spc_pub_ref_result_new+$spc_adv_ref_result_new;
				
				if($da==0 && $da1==0)
				$referralamount=$ref_string;	
				else
				$referralamount=round($da+$da1,2);
				
				
				
				}
				else
				{				
				$loop_flag=0;
				if($row[0]+$row[1]==0)
				$referralamount=$ref_string;	
				else
				$referralamount=round($row[0]+$row[1],2);
				}
				}
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
						}
						else
						{
			
						
			if($flag_time!=1 && $flag_time!=2)
			{
			
			$ds=$spc_pub_ref_result_new+$spc_adv_ref_result_new;			
			
			
			if($temp_time == $minus_time)
			{
			if($ds !=0)
			{
			$loop_flag=1;
			$referralamount=$ds;
			}
			else
			{
			$loop_flag=1;
			$referralamount=$ref_string;
			
			}
			
			}
			else
			{			
				$loop_flag=1;
				$referralamount=$ref_string;
			}
			}
			else if($flag_time==1 || $flag_time==2)
				{
			$ds=$data_adv_ref+$data_pub_ref;
		
			if($temp_time == $minus_time)
			{
			
			
			if($ds !=0)
			{
			$loop_flag=1;
			$referralamount=$ds;
			}
			else
			{
			$loop_flag=1;
			$referralamount=$ref_string;
			
			
			}
			
				
			
			
			}
			else
			{
			
			$loop_flag=1;
			$referralamount=$ref_string;
			}
			}
			
						
						
			
						
						
							
						}
						if($end_time==$temp_time)
								{
								 if($flag_time==1 || $flag_time==2)
									{
									if($temp_time == $minus_time)
									$curr_ref=$curr_ref+$data_adv_ref+$data_pub_ref;
									
									
									
									$referralamount=$curr_ref;
									}
									else
									{
									
									$curr_ref=$spc_pub_ref_result+$spc_adv_ref_result;
									$referralamount=$curr_ref;////////////////////////////////////////////////////////////////////////////////
									
									
									
									
									
									}
								}
		
					 }
					 else
					 {
						$referralamount='';
					 }
				}
			if($flag_time==0)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
			}
			else if($flag_time==1)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
			}
			else if($flag_time==2)
			{
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
			}
			 if($flag_time==-1)
			{
			$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
			}	
			
			$FD->addChartData("$referralamount");
		//	echo $referralamount." ";
		 
		}
			 $retVal[1]=$FD;	
}

	
		return $retVal;	
		
}	









function getPubRefMonthStatistics($flag_time,$mysql,$pid,$frm=0)
{

$data=0;
$day=date("j",time());
$mont=date("n",time());
if($day ==1 && $mont ==1)
{
$year_beg=mktime(0,0,0,1,1,date("Y",time())-1);
$year_end=mktime(0,0,0,1,1,date("Y",time()));
$daily_stattime=mktime(date("H",time()),0,0,date("m",time()),date("j",time())-1,date("Y",time()));
$month_beg=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
$year_end_new=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));

if($flag_time==2)
$data=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from monthly_referral_statistics where pid=$pid and time>$year_beg and time <= $year_end_new order by time");

$data=$data+$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where pid=$pid and time>$month_beg and time <= $year_end order by time");
$data=$data+$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where pub_rid=$pid and time>$daily_stattime and time <= $year_end  order by time");


if($flag_time==1)
$newda=mktime(0,0,0,date("m",time()),1,date("Y",time()));
else if($flag_time==2)
$newda=$year_end;



if($frm ==1)
return  $data."_".$newda;
else
return  $data;

}
else if($day ==1 && $mont !=1)
{
$month_beg=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
$month_end=mktime(0,0,0,date("m",time()),1,date("Y",time()));
$daily_stattime=mktime(date("H",time()),0,0,date("m",time()),date("j",time())-1,date("Y",time()));


$data=$data+$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where pid=$pid and time>$month_beg and time <= $month_end order by time");
$data=$data+$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where pub_rid=$pid and time>$daily_stattime and time <= $month_end  order by time");


if($flag_time==1)
$newda=$month_end;
else if($flag_time==2)
$newda=mktime(0,0,0,1,1,date("Y",time())+1);


if($frm ==1)
return  $data."_".$newda;
else
return  $data;

}
else
return 0;




}
function getAdvRefMonthStatistics($flag_time,$mysql,$pid,$frm=0)
{
$data=0;
$day=date("j",time());
$mont=date("n",time());
if($day ==1 && $mont ==1)
{
$year_beg=mktime(0,0,0,1,1,date("Y",time())-1);
$year_end=mktime(0,0,0,1,1,date("Y",time()));
$daily_stattime=mktime(date("H",time()),0,0,date("m",time()),date("j",time())-1,date("Y",time()));
$month_beg=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
$year_end_new=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));

if($flag_time==2)
$data=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from monthly_referral_statistics where pid=$pid and time>$year_beg and time <= $year_end_new order by time");

$data=$data+$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from daily_referral_statistics where pid=$pid and time>$month_beg and time <= $year_end order by time");
$data=$data+$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where adv_rid=$pid and time>$daily_stattime and time <= $year_end  order by time");


if($flag_time==1)
$newda=mktime(0,0,0,date("m",time()),1,date("Y",time()));
else if($flag_time==2)
$newda=$year_end;



if($frm ==1)
return  $data."_".$newda;
else
return  $data;

}
else if($day ==1 && $mont !=1)
{
$month_beg=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
$month_end=mktime(0,0,0,date("m",time()),1,date("Y",time()));
$daily_stattime=mktime(date("H",time()),0,0,date("m",time()),date("j",time())-1,date("Y",time()));


$data=$data+$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from daily_referral_statistics where pid=$pid and time>$month_beg and time <= $month_end order by time");
$data=$data+$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where adv_rid=$pid and time>$daily_stattime and time <= $month_end  order by time");


if($flag_time==1)
$newda=$month_end;
else if($flag_time==2)
$newda=mktime(0,0,0,1,1,date("Y",time())+1);


if($frm ==1)
return  $data."_".$newda;
else
return  $data;

}
else
return 0;

}
function getCurrentMonthStatistics($flag_time,$mysql,$field,$field1,$pid,$bid,$data_clk=0,$sumvar=0,$frm=0)
{
$bidstring="";
if($bid != 'NA')
$bidstring=" bid='$bid' and ";
else
$bidstring="";


$data=0;
$stringvar="";
if($sumvar ==1)
$stringvar="count";
else if($sumvar ==2)
$stringvar="sum";



$day=date("j",time());
$mont=date("n",time());
if($day ==1 && $mont ==1)
{

$year_beg=mktime(0,0,0,1,1,date("Y",time())-1);
$year_end=mktime(0,0,0,1,1,date("Y",time()));
$daily_stattime=mktime(date("H",time()),0,0,date("m",time()),date("j",time())-1,date("Y",time()));
$month_beg=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));


$year_end_new=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));


 if($flag_time==2)
 $data=$mysql->echo_one("select COALESCE(sum(".$field."),0) from publisher_monthly_statistics where uid='$pid' and $bidstring time > $year_beg and time <= $year_end_new");
 
 
 $data=$data+$mysql->echo_one("select COALESCE(sum(".$field."),0) from publisher_daily_statistics where uid='$pid' and $bidstring time > $month_beg and time <= $year_end");
			  
if($data_clk ==1)
$data=$data+$mysql->echo_one("select COALESCE(".$stringvar."(".$field1."),0) from ppc_daily_clicks where pid='$pid' and $bidstring time > $daily_stattime and time <= $year_end ");		  
else
$data=$data+$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$pid' and $bidstring time > $daily_stattime and time <= $year_end ");				  
			  
			  
if($flag_time==1)
$newda=mktime(0,0,0,date("m",time()),1,date("Y",time()));
else if($flag_time==2)
$newda=$year_end;


if($frm ==1)
return  $data."_".$newda;
else
return  $data;
}
else if($day ==1 && $mont !=1)
{
$month_beg=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
$month_end=mktime(0,0,0,date("m",time()),1,date("Y",time()));
$daily_stattime=mktime(date("H",time()),0,0,date("m",time()),date("j",time())-1,date("Y",time()));

$data=$mysql->echo_one("select COALESCE(sum(".$field."),0) from publisher_daily_statistics where uid='$pid' and $bidstring time > $month_beg and time <= $month_end");




if($data_clk ==1)
$data=$data+$mysql->echo_one("select COALESCE(".$stringvar."(".$field1."),0) from ppc_daily_clicks where pid='$pid' and $bidstring time > $daily_stattime and time <= $month_end ");
else
$data=$data+$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$pid' and $bidstring time > $daily_stattime and time <= $month_end ");		


if($flag_time==1)
$newda=$month_end;
else if($flag_time==2)
$newda=mktime(0,0,0,1,1,date("Y",time())+1);


if($frm ==1)
return  $data."_".$newda;
else
return  $data;
}
else
return 0;




}

?>