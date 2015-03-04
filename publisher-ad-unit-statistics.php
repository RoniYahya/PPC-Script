<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");

$template=new Template();
$template->loadTemplate("publisher-templates/publisher-ad-unit-statistics.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


include("graphutils.inc.php");
include("publisher_statistics_utils.php");

$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
if(isset($_GET['bid']))
	$bid=$_GET['bid'];
else
	$bid=$_POST['bid'];
phpsafe($bid);
$uid=$user->getUserID();
if($bid==-1)
{
if(($mysql->echo_one("select xmlstatus from `ppc_publishers` where uid=".$uid))!=1)
{
header("Location:publisher-show-message.php?id=7045");
exit(0);
}
}
if($bid!=0 && $bid!=-1)
{
	if(!myAdUnit($bid,$uid,$mysql))
	{
	header("Location:publisher-show-message.php?id=5010");
	exit(0);
	}
}

$flag_time=0;
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



$returnVal=plotPublisherGraphs($show,$flag_time,$beg_time,$end_time,$selected_colorcode,$uid,$referral_system,$bid );

$FC=$returnVal[0];
$FD=$returnVal[1];

   //////////////////////////////

$click=getPubBlockClicks($time,$mysql,$uid,$bid,$flag_time);
$total_impressions=getPubBlockImpressions($time,$mysql,$uid,$bid,$flag_time);
if($total_impressions==0)
{
	$ctr=0;
}
else
{
	$ctr=($click/$total_impressions) * 100;
	$ctr=numberFormat($ctr);
}
$re= getPubBlockPublisherprofit($time,$mysql,$uid,$bid,$flag_time);

//$tablestructure=getTimeBasedPublisherStatistics($show,$flag_time,$beg_time,$end_time,$uid,$bid);
$aearning=getPubBlockPublisherrefprofit($time,$mysql,$uid,$bid,$flag_time);
$pearning=getPubBlockAdvrefprofit($time,$mysql,$uid,$bid,$flag_time);
$tot=$pearning+$aearning+$re;

$template->setValue("{CLICK}",numberFormat($click,0));
$template->setValue("{IMP}",numberFormat($total_impressions,0));
$template->setValue("{CTR}",$ctr);
$template->setValue("{NET}",$re);

$template->setValue("{AEARNING}",$aearning);
$template->setValue("{PEARNING}",$pearning);
$template->setValue("{TOT}",$tot);

   /////////////////////////////

/*if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		$beg_time=$mysql->echo_one("select min(time) from $table_name where uid=$uid");
	 	}
	 else
	 {
		$table_name="publisher_monthly_statistics";
	 }
$stat_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0),time from `$table_name` where uid=$uid and bid=$bid and time>=$beg_time group by time");

$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
}
# Include FusionCharts PHP Class
include('FusionCharts/Class/FusionCharts_Gen.php');

# Create Multiseries Column3D chart object using FusionCharts PHP Class
$FC = new FusionCharts("MSColumn3DLineDY","750","300");

# Set the relative path of the swf file
$FC->setSWFPath("FusionCharts/");

# Store chart attributes in a variable
$strParam="caption=Clicks and Impressions;subcaption=Comparison;xAxisName=Duration;yAxisName=Statistics; pYAxisName=Count;sYAxisName=CTR (%);decimalPrecision=0;rotateNames=1; showvalues=0;";

# Set chart attributes
$FC->setChartParams($strParam);

$FD = new FusionCharts("MSLine","750","300");
# set the relative path of the swf file
$FD->setSWFPath("FusionCharts/");
# Store chart attributes in a variable
$strParam="caption=Money gained;xAxisName=Duration;yAxisName=Money gained;numberPrefix=$currency_symbol;decimalPrecision=2;formatNumberScale=1;rotateNames=1;";
$FD->setChartParams($strParam);

$ctr_flag=0;
$money_flag=0;
$temp_time=$beg_time;
while($temp_time<=$end_time)
{
	if($flag_time==0)
	{
		$str=date("M d",$temp_time-1); 
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
		$show_duration="$str";
	}
	else if($flag_time==1)
	{
		$str=date("M",$temp_time-86400);
		$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
		$show_duration="$str";
	}
	else
	{
		$str=date("Y",$temp_time-86400);
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
		$show_duration="$str";
	}
	//echo "$show_duration <br>";
	$FC->addCategory("$show_duration");
	$FD->addCategory("$show_duration");
	if( $ctr_flag==0 || $money_flag==0)
	{
			$row=mysql_fetch_row($stat_result);
			if($row[0]>0 && $row[1]>0)
			{
				$ctr_flag=1;
			}
			if( $row[1]>0)
			{
				$money_flag=1;
			}
	}
//echo "$show_duration";

}

mysql_data_seek($stat_result,0);				
$temp_time=$beg_time;
$FC->addDataset("Impressions","showvalues=0");
$loop_flag=0;
while( $temp_time<=$end_time)
{
	if($loop_flag==0)
		$row=mysql_fetch_row($stat_result);
	if($row[3]==$temp_time)
	{
		$loop_flag=0;
		$FC->addChartData("$row[0]");
	}
	else
	{
		$loop_flag=1;
		$FC->addChartData('');
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
	else
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
	}

}

mysql_data_seek($stat_result,0);
$temp_time=$beg_time;
$FC->addDataset("Clicks","showValues=0");
$loop_flag=0;
while( $temp_time<=$end_time)
{

	if($loop_flag==0)
		$row=mysql_fetch_row($stat_result);
	if($row[3]==$temp_time)
	{
		# Add chart values for the above dataset
		$loop_flag=0;
		$FC->addChartData("$row[1]");
	}
	else
	{
		$loop_flag=1;
		$FC->addChartData('');
	}
	
	if($flag_time==0)
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
	}
	else if($flag_time==1)
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
	}
	else
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
	}
		
}
	
$ctr_string='0';
mysql_data_seek($stat_result,0);
$FC->addDataset("CTR","parentYAxis=S;color=$selected_colorcode[2];showValues=0");
$loop_flag=0;
$temp_time=$beg_time;
while( $temp_time<=$end_time)
{
	 
	if($ctr_flag==1)
	{
		if($loop_flag==0)
			$row=mysql_fetch_row($stat_result);
		if($row[3]==$temp_time)
		{
			# Add chart values for the above dataset
			$loop_flag=0;
			if($row[0]==0)
				$ctr=$ctr_string;
			else
			{
				$ctr=($row[1]/$row[0]) * 100;
				$ctr=round($ctr,2);
			}
		}
		else
		{
			$loop_flag=1;
			$ctr=$ctr_string;
		}

		$FC->addChartData("$ctr");
	 }
	 else
	 {
		 $FC->addChartData("");
	 }
	if($flag_time==0)
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
	}
	else if($flag_time==1)
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
	}
	else
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
	}
		
}


/////////////////////////Money spend
$money_string='0';
# Set chart attributes
$FD->addDataset("Click profit","showvalues=0");
mysql_data_seek($stat_result,0);
$loop_flag=0;
$temp_time=$beg_time;
while( $temp_time<=$end_time)
{
	if($money_flag==1)
	{
	 
		if($loop_flag==0)
			$row=mysql_fetch_row($stat_result);
		if($row[3]==$temp_time)
		{
			# Add chart values for the above dataset
			$loop_flag=0;
			if($row[2]==0)
				$moneygained=$money_string;	
			else
			$moneygained=$row[2];
		}
		else
		{
			$loop_flag=1;
			$moneygained='';
		}
	 }
	 else
	 {
		$moneygained='';
	 }
	if($flag_time==0)
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
	}
	else if($flag_time==1)
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
	}
	else
	{
		$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
	}
	
	$FD->addChartData($moneygained);
	 
}*/
$template->openLoop("STATISTICS",getTimeBasedPublisherStatistics($show,$flag_time,$beg_time,$end_time,$uid,$bid));
$template->setLoopField("{LOOP(STATISTICS)-ID}","id");
$template->setLoopField("{LOOP(STATISTICS)-IMP}","impressions");
$template->setLoopField("{LOOP(STATISTICS)-CLK}","clicks");
$template->setLoopField("{LOOP(STATISTICS)-MONEY}","clickvalue");
$template->closeLoop();
if($bid==-1)
{
	$template->setValue("{BID_TYPE}",$template->checkPubMsg(7043));
	$template->setValue("{BID_NAME}",$template->checkPubMsg(8883));	
}
else
{
	$template->setValue("{BID_TYPE}",$template->checkPubMsg(7042));
	$template->setValue("{BID_NAME}",$template->checkPubMsg(8884));
}
if($currency_format=="$$")
$template->setValue("{CURRENCY}",$system_currency); 
else 
$template->setValue("{CURRENCY}",$currency_symbol);
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$template->setValue("{BID}",$bid);                      
//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));                                           

$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8973));
//$template->setValue("{ENGINE_TITLE}",$engine_title);

//$publisher_message[8972]='<b>{publisher_msg:1122}</b> {publisher_msg:8905} . {fn:dateTimeFormat($adserver_upgradation_date)}';
//$msg11= str_replace("{DATE1}",dateTimeFormat($adserver_upgradation_date),$publisher_message[8969]);
//$template->setValue("{MSG11}",$msg11);
$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding);     
eval('?>'.$template->getPage().'<?php ');

?>
