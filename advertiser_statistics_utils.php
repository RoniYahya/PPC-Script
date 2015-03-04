<?php

//********************new code******************************

function getOverallAdImpressions($time,$mysql,$flag_time){
	$day=date("j",time());
	$mont=date("n",time());

	//$spec_time_limits=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
	$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
	$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
	$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		  $temp=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where  time>=$time");
		  $temp=$temp+getOverallAdImpressions($spec_time_limits,$mysql,$spec_flag_limits);
		  
		 if($temp=="")
			return 0;
		else
			return $temp;
	  }
	   else if($flag_time==-1)
	 	{
		 $temp=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where  time>$time");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
		$table_name="advertiser_yearly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where  time>=$time");
			  
			  
			       if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
        	 	  $year_time=mktime(0,0,0,1,1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where  time>$year_time");
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp3=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2+$temp3;
			  
			  $temp=$temp+getOverallAdImpressions($spec_time_limits,$mysql,$spec_flag_limits);
			  
			 if($temp=="")
				return 0;
			else
				return $temp;
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where time>=$time");
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2;
			  
			  $temp=$temp+getOverallAdImpressions($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 }
}

function getOverallAdClicks($time,$mysql,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		  $temp=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where  time>=$time");
		  $temp=$temp+getOverallAdClicks($spec_time_limits,$mysql,$spec_flag_limits);
		  
		 if($temp=="")
			return 0;
		else
			return $temp;
	  }
	   else if($flag_time==-1)
	 	{
		 $temp=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where  time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
$table_name="advertiser_yearly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where  time>=$time");
			  
			  
			  if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
        	 	  $year_time=mktime(0,0,0,1,1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where  time>$year_time");
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2+$temp3;
			  $temp=$temp+getOverallAdClicks($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where time>=$time");
			  
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2;
			  $temp=$temp+getOverallAdClicks($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 }
}

function getOverallAdClickValue($time,$mysql,$flag_time)

{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		  $temp=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_daily_statistics where time>$time ");
		  $temp=$temp+getOverallAdClickValue($spec_time_limits,$mysql,$spec_flag_limits);
		 if($temp=="")
			return 0;
		else
			return $temp;
	  }
	   else if($flag_time==-1)
	 	{
		 $temp=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
$table_name="advertiser_yearly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where  time>=$time");
			  
			  if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
			  
        	 	  $year_time=mktime(0,0,0,1,1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_monthly_statistics where  time>$year_time");
			  
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp3=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2+$temp3;
			  $temp=$temp+getOverallAdClickValue($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_monthly_statistics where time>=$time");
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2;
			  
			  $temp=$temp+getOverallAdClickValue($spec_time_limits,$mysql,$spec_flag_limits);
			  
			 if($temp=="")
				return 0;
			else
				return $temp;
	 }
}

function getOverallAdClickPubShare($time,$mysql,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		  $temp=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_daily_statistics where time>$time ");
		  
		  $temp=$temp+getOverallAdClickPubShare($spec_time_limits,$mysql,$spec_flag_limits);
		  
		 if($temp=="")
			return 0;
		else
			return $temp;
	  }
	   else if($flag_time==-1)
	 	{
		 $temp=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
$table_name="advertiser_yearly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where  time>=$time");
			  
			  if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
			  
        	 	  $year_time=mktime(0,0,0,1,1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_monthly_statistics where  time>$year_time");
			  
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp3=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2+$temp3;
			  $temp=$temp+getOverallAdClickPubShare($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_monthly_statistics where time>=$time");
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2;
			  $temp=$temp+getOverallAdClickPubShare($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 }
}

function getOverallAdClickPubRefShare($time,$mysql,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		  $temp=$mysql->echo_one("select COALESCE(SUM(pub_ref_profit),0) from advertiser_daily_statistics where time>$time ");
		  
		  $temp=$temp+getOverallAdClickPubRefShare($spec_time_limits,$mysql,$spec_flag_limits);
		 if($temp=="")
			return 0;
		else
			return $temp;
	  }
	   else if($flag_time==-1)
	 	{
		 $temp=$mysql->echo_one("select COALESCE(SUM(pub_ref_profit),0) from ppc_daily_clicks where time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
$table_name="advertiser_yearly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(SUM(pub_ref_profit),0) from $table_name where  time>=$time");
			  
			  if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
			  
			  
        	 	  $year_time=mktime(0,0,0,1,1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(SUM(pub_ref_profit),0) from advertiser_monthly_statistics where  time>$year_time");
			  
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp3=$mysql->echo_one("select COALESCE(SUM(pub_ref_profit),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2+$temp3;
			  $temp=$temp+getOverallAdClickPubRefShare($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(SUM(pub_ref_profit),0) from advertiser_monthly_statistics where time>=$time");
			  
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(SUM(pub_ref_profit),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2;
			  $temp=$temp+getOverallAdClickPubRefShare($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 }
}

function getOverallAdClickAdvRefShare($time,$mysql,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		  $temp=$mysql->echo_one("select COALESCE(SUM(adv_ref_profit),0) from advertiser_daily_statistics where time>$time ");
		  $temp=$temp+getOverallAdClickAdvRefShare($spec_time_limits,$mysql,$spec_flag_limits);
		  
		 if($temp=="")
			return 0;
		else
			return $temp;
	  }
	   else if($flag_time==-1)
	 	{
		 $temp=$mysql->echo_one("select COALESCE(SUM(adv_ref_profit),0) from ppc_daily_clicks where time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
$table_name="advertiser_yearly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(SUM(adv_ref_profit),0) from $table_name where  time>=$time");
			  
			  
			  if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
			  
        	 	  $year_time=mktime(0,0,0,1,1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(SUM(adv_ref_profit),0) from advertiser_monthly_statistics where  time>$year_time");
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp3=$mysql->echo_one("select COALESCE(SUM(adv_ref_profit),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2+$temp3;
			  $temp=$temp+getOverallAdClickAdvRefShare($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(SUM(adv_ref_profit),0) from advertiser_monthly_statistics where time>=$time");
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(SUM(adv_ref_profit),0) from advertiser_daily_statistics where time>$month_time");
			  $temp=$temp1+$temp2;
			  $temp=$temp+getOverallAdClickAdvRefShare($spec_time_limits,$mysql,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 }
}

//**********************************************************

function getAdClicks($id,$time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
$aid_string="and aid='$id'";
if(0==$id)
	$aid_string=" ";

//echo "$id,$time,$uid,$flag_time";
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
	 $table_name="advertiser_yearly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' $aid_string and time>=$time");
			  
			  if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
			  
        	  $year_time=mktime(0,0,0,1,1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>$year_time");
			  
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
			  $temp=$temp1+$temp2+$temp3;
			  
			  $temp=$temp+getAdClicks($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
			  
			 if($temp=="")
				return 0;
			else
				return $temp;
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>=$time");
			  
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  $temp2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
			  $temp=$temp1+$temp2;
			   $temp=$temp+getAdClicks($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
			 if($temp=="")
				return 0;
			else
				return $temp;
	 }
	  $temp=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' $aid_string and time>=$time");
	   $temp=$temp+getAdClicks($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
	 if($temp=="")
		return 0;
	else
		return $temp;
	//return round($mysql->total("ppc_clicks","uid='$uid' $aid_string and time>$time"),2);

}

function getAdImpressions($id,$time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

//$spec_time_limits=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
$aid_string="and aid='$id'";

if(0==$id)
	$aid_string=" ";
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
		$table_name="advertiser_impression_daily";
		//echo "select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' $aid_string and time>=$time";
		 $temp=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' $aid_string and time>$time");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		$temp1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$temp2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>$year_time");
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$temp3=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$temp=$temp1+$temp2+$temp3;
		
		 $temp=$temp+getAdImpressions($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		 
	 if($temp=="")
	 	return 0;
	else
		return $temp;
	 	}
	 else
	 {
		//$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$temp1=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>=$time");
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$temp2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$temp=$temp1+$temp2;
		$temp=$temp+getAdImpressions($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
	 if($temp=="")
	 	return 0;
	else
		return $temp;
	 }
	 $temp=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' $aid_string and time>=$time");
	 $temp=$temp+getAdImpressions($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
	 if($temp=="")
	 	return 0;
	else
		return $temp;
}

function getAdClickrate($id,$time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
//$spec_time_limits_imps=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits_imps=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));

$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
$aid_string="and aid='$id'";
if(0==$id)
	$aid_string=" ";
	
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
		$table_name="advertiser_impression_daily";
		  $d_clicks=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$time and time < $endtimes ");
		  $d_impr=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' $aid_string and time>$time");
		 if($d_impr==0)
		 	return 0;
		else 
		
			return round($d_clicks/$d_impr*100,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ad_impr1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' $aid_string and time>=$time");
		$ad_clk1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ad_impr2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>$year_time");
		$ad_clk2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>$year_time");
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ad_impr3=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ad_clk3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ad_impr=$ad_impr1+$ad_impr2+$ad_impr3;
		$ad_clk=$ad_clk1+$ad_clk2+$ad_clk3;
		
		
		
		 $d_clicks_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$spec_time_limits and time < $endtimes ");
		 $d_impr_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' $aid_string and time>$spec_time_limits_imps");
		 
		 $ad_clk=$ad_clk+$d_clicks_new;
		 $ad_impr=$ad_impr+$d_impr_new;
		 
		 
		
		
		
		
	if($ad_impr==0)
	{
		return 0;
	}
	else
	{
		//return round($mysql->total("ppc_clicks","uid='$uid' $aid_string and time>$time")/$ad_impr,2)*100;
		return round($ad_clk/$ad_impr,2)*100;
	}
		
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
		$ad_impr1=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>=$time");
		$ad_clk1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>=$time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ad_impr2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ad_clk2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ad_impr=$ad_impr1+$ad_impr2;
		$ad_clk=$ad_clk1+$ad_clk2;
		
		 $d_clicks_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$spec_time_limits and time < $endtimes ");
		 $d_impr_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' $aid_string and time>$spec_time_limits_imps");
		 
		 $ad_clk=$ad_clk+$d_clicks_new;
		  $ad_impr=$ad_impr+$d_impr_new;
		
	if($ad_impr==0)
	{
		return 0;
	}
	else
	{
		//return round($mysql->total("ppc_clicks","uid='$uid' $aid_string and time>$time")/$ad_impr,2)*100;
		return round($ad_clk/$ad_impr,2)*100;
	}
		
	 }
	$ad_impr=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' $aid_string and time>=$time");
	$ad_clk=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' $aid_string and time>=$time");
	
	$d_clicks_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$spec_time_limits and time < $endtimes ");
	$d_impr_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' $aid_string and time>$spec_time_limits_imps");
		 
		  $ad_clk=$ad_clk+$d_clicks_new;
		  $ad_impr=$ad_impr+$d_impr_new;
	
	
	if($ad_impr==0)
	{
		return 0;
	}
	else
	{
		
		return round($ad_clk/$ad_impr,2)*100;
	}
}

function getAdMoneySpent($id,$time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
$aid_string="and aid='$id'";
if(0==$id)
	$aid_string=" ";
if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return round($temp,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>$year_time");
		
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getAdMoneySpent($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 	}
	 else
	 {
		//$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getAdMoneySpent($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 }
	//$ret=$mysql->echo_one("select SUM(clickvalue) from ppc_clicks where uid='$uid' $aid_string and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid='$uid' $aid_string and time>=$time");
	
	$ret=$ret+getAdMoneySpent($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
	
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	

}

function getPublisherprofit($id,$time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
$aid_string="and aid='$id'";
if(0==$id)
	$aid_string=" ";
if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return round($temp,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>$year_time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getPublisherprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 	}
	 else
	 {
		//$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPublisherprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 }
	//$ret=$mysql->echo_one("select SUM(publisher_profit) from ppc_clicks where uid='$uid' $aid_string and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid' $aid_string and time>=$time");
	
	$ret=$ret+getPublisherprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
	
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	

}
function getPublisherrefprofit($id,$time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
$aid_string="and aid='$id'";
if(0==$id)
	$aid_string=" ";
if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return round($temp,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>$year_time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getPublisherrefprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 	}
	 else
	 {
		//$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPublisherrefprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 }
	//$ret=$mysql->echo_one("select SUM(pub_ref_profit) from ppc_clicks where uid='$uid' $aid_string and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$uid' $aid_string and time>=$time");
	
	$ret=$ret+getPublisherrefprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
	
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	

}
function getAdvertiserrefprofit($id,$time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
$aid_string="and aid='$id'";
if(0==$id)
	$aid_string=" ";
if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where uid='$uid' $aid_string and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return round($temp,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$uid' $aid_string and time>=$time");
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>$year_time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getAdvertiserrefprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 	}
	 else
	 {
		//$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from advertiser_monthly_statistics where uid='$uid' $aid_string and time>=$time");
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from advertiser_daily_statistics where uid='$uid' $aid_string and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getAdvertiserrefprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 }
	//$ret=$mysql->echo_one("select SUM(adv_ref_profit) from ppc_clicks where uid='$uid' $aid_string and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$uid' $aid_string and time>=$time");
	
	$ret=$ret+getAdvertiserrefprofit($id,$spec_time_limits,$mysql,$uid,$spec_flag_limits);
	
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	

}

function getKeywordClicks($id,$time,$mysql,$uid,$aid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
	 	return $mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>$time and time < $endtimes ");
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$res_val1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$res_val2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$year_time");
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$res_val3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		
		$res_val1=$res_val1+getKeywordClicks($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
		if($res_val1=="" && $res_val3=="" && $res_val2=="")
	 		return 0;
		else
			return $res_val1+$res_val2+$res_val3;
	 	}
	 else
	 {
		//$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$res_val1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$res_val2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		
		$res_val1=$res_val1+getKeywordClicks($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
		if($res_val1=="" && $res_val2=="")
	 		return 0;
		else
			return $res_val1+$res_val2;
	 }
	  $res_val=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
	  
	  $res_val=$res_val+getKeywordClicks($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
	  
	 if($res_val=="")
	 	return 0;
	else
		return $res_val;	
	//return round($mysql->total("ppc_clicks","uid='$uid' and aid='$aid' and kid='$id' and time>$time"),2);

}

function getKeywordImpressions($id,$time,$mysql,$uid,$aid,$flag_time)
{
	
$day=date("j",time());
$mont=date("n",time());

//$spec_time_limits=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		 $res_val1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		 
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		 $year_time=mktime(0,0,0,1,1,date("Y",time()));
		 $res_val2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$year_time");
		 
		 if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		 $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		 $res_val3=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		 
		 $res_val1=$res_val1+getKeywordImpressions($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		 
		 if($res_val1=="" && $res_val3=="" && $res_val2=="")
	 		return 0;
		else
			return $res_val1+$res_val2+$res_val3;	
		
		
		
	 	}
	 else if($flag_time==-1)
	 	{
	 		//echo "select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' and aid='$aid' and kid='$id' and time>$time";
	 	return $mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' and aid='$aid' and kid='$id' and time>$time");
	 	}
	 else
	 {
//		$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$res_val1=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		 $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		 $res_val2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		 
		 $res_val1=$res_val1+getKeywordImpressions($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		 
		 if($res_val1=="" && $res_val2=="")
	 		return 0;
		else
			return $res_val1+$res_val2;	
	 }
	// echo "select COALESCE(sum(impression),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time";
	 $res_val=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
	 $res_val=$res_val+getKeywordImpressions($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
	 
	 if($res_val=="")
	 	return 0;
	else
		return $res_val;	
}

function getKeywordClickRate($id,$time,$mysql,$uid,$aid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
//$spec_time_limits_imps=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits_imps=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));

	$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
	$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	$imp_count=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' and aid='$aid' and kid='$id' and time>$time");
		$clk_count=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>$time and time < $endtimes ");
		if($imp_count==0)
			return 0;
		else
			{
			return round(($clk_count/$imp_count)*100,2);
			}
	 	}
	 else if($flag_time==2)
	 	{
			$table_name="advertiser_yearly_statistics";
			
			$ad_impr1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
			$ad_clk1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
			
			
			
			if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
			$year_time=mktime(0,0,0,1,1,date("Y",time()));
			$ad_impr2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$year_time");
			$ad_clk2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$year_time");
			
			if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$ad_impr3=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
			$ad_clk3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
			$ad_clk=$ad_clk1+$ad_clk2+$ad_clk3;
			$ad_impr=$ad_impr1+$ad_impr2+$ad_impr3;
			
			
			$imp_count_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' and aid='$aid' and kid='$id' and time>=$spec_time_limits_imps");
		    $clk_count_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>=$spec_time_limits and time < $endtimes ");
			
			$ad_impr=$ad_impr+$imp_count_new;
			$ad_clk=$ad_clk+$clk_count_new;
			
			
			if($ad_impr==0)
			{
				return 0;
			}
			else
			{
				return round($ad_clk/$ad_impr,2)*100;
			}
			
	 	}
	 else
	 {
		//$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
			$ad_impr1=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
			$ad_clk1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
			
			
			if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$ad_impr2=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
			$ad_clk2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
			$ad_clk=$ad_clk1+$ad_clk2;
			$ad_impr=$ad_impr1+$ad_impr2;
			
			
			$imp_count_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' and aid='$aid' and kid='$id' and time>=$spec_time_limits_imps");
		    $clk_count_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>=$spec_time_limits and time < $endtimes ");
			
			$ad_impr=$ad_impr+$imp_count_new;
			$ad_clk=$ad_clk+$clk_count_new;
			
			if($ad_impr==0)
			{
				return 0;
			}
			else
			{
				return round($ad_clk/$ad_impr,2)*100;
			}
		
	 }
	$ad_impr=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
	$ad_clk=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
	
	
	
	$imp_count_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where uid='$uid' and aid='$aid' and kid='$id' and time>=$spec_time_limits_imps");
	$clk_count_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>=$spec_time_limits and time < $endtimes ");
			
			$ad_impr=$ad_impr+$imp_count_new;
			$ad_clk=$ad_clk+$clk_count_new;
	
	
	
	
	
	if($ad_impr==0)
	{
		return 0;
	}
	else
	{
		return round($ad_clk/$ad_impr,2)*100;
	}
	
	
}

function getKeywordMoneySpent($id,$time,$mysql,$uid,$aid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	return round($mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>$time and time < $endtimes "),2);
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$year_time");
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getKeywordMoneySpent($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	
	 	}
	 else
	 {
		//$table_name="advertiser_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getKeywordMoneySpent($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	 }
	//$ret=$mysql->echo_one("select SUM(clickvalue) from ppc_clicks where uid='$uid' and aid='$id' and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
	
	$ret=$ret+getKeywordMoneySpent($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
	
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
//	
//	$ret=$mysql->echo_one("select SUM(clickvalue) from ppc_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>$time");
//	if( $ret=="")
//	$ret=0;
//	return round($ret,2);
	

}

function getKeywordPublisherprofit($id,$time,$mysql,$uid,$aid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	return round($mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>$time and time < $endtimes "),2);
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$year_time");
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getKeywordPublisherprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	
	 	}
	 else
	 {
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getKeywordPublisherprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	 }
	
	$ret=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
	
	$ret=$ret+getKeywordPublisherprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
	
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	

	

}
function getKeywordPublisherrefprofit($id,$time,$mysql,$uid,$aid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	return round($mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>$time and time < $endtimes "),2);
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$year_time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getKeywordPublisherrefprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	
	 	}
	 else
	 {
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getKeywordPublisherrefprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	 }
	
	$ret=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
	
	$ret=$ret+getKeywordPublisherrefprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
	
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	

	

}
function getKeywordAdvrefprofit($id,$time,$mysql,$uid,$aid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	return round($mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where uid='$uid' and aid='$aid' and kid='$id' and time>$time and time < $endtimes "),2);
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$year_time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getKeywordAdvrefprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	
	 	}
	 else
	 {
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from advertiser_monthly_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
		
		
		
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from advertiser_daily_statistics where uid='$uid' and aid='$aid' and kid='$id' and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getKeywordAdvrefprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	 }
	
	$ret=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$uid' and aid='$aid' and kid='$id' and time>=$time");
	
	$ret=$ret+getKeywordAdvrefprofit($id,$spec_time_limits,$mysql,$uid,$aid,$spec_flag_limits);
	
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	

	

}

function getTimeBasedAdvertiserStatistics($show,$flag_time,$beg_time,$end_time,$uid,$id=0)
{

//echo $show.",".$flag_time.",".$beg_time.",".$end_time.",".$uid.",".$id;
if($flag_time == 0)
$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));


$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits_imps=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));

$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	
	global $mysql;	
if($beg_time==0)
$beg_time=$mysql->echo_one("select min(time) from advertiser_yearly_statistics");
$main_array=array();
$temp_time=$beg_time;



		
			
$aid_str=" and aid=$id ";
	if($id==0)
		{
		$aid_str="";
		}
		
	if($id==0)	
	$idsstr="NA";
	else
	$idsstr=$id;
		
		
		$timestr="and";
		$uid_str=" uid=$uid "; 
		if($uid==0)
		{
			$uid_str="";
			$timestr="";
		}
		
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
			//	echo "select COALESCE(sum(cnt),0), time from advertiser_impression_daily where $uid_str $aid_str $timestr time>$beg_time group by time order by time";
			$impression_result=mysql_query("select COALESCE(sum(cnt),0), time from advertiser_impression_daily where $uid_str $aid_str $timestr time>$beg_time group by time order by time");
		
			$click_result=mysql_query("select COALESCE(count(id),0),COALESCE(sum(clickvalue),0),time from ppc_daily_clicks where $uid_str $aid_str $timestr time>$beg_time and time < $endtimes  group by time order by time");



				$result_arr=array();
				while($row=mysql_fetch_row($impression_result))
				{
					$key=$row[1];
					$result_arr[$key][0]=$row[0];
					$result_arr[$key][1]=0;
					$result_arr[$key][2]=0;
				
				}
				while($row=mysql_fetch_row($click_result))
				{
					$key=$row[2];
					if($result_arr[$key][0]=="")
					$result_arr[$key][0]=0;
					$result_arr[$key][1]=$row[0];
					$result_arr[$key][2]=$row[1];
					
					
						
				}
				
			

			}
		else
			{
			
		//echo "select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),time from `$table_name` where $uid_str $aid_str $timestr time<=$end_time group by time order by time";
			$stat_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),time from `$table_name` where $uid_str $aid_str $timestr time<=$end_time group by time order by time");
			
			
			
$spc_imps_result=0;
$spc_clk_result=0;
$spc_cval_result=0;			
			
$spc_imps_result=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where $uid_str $aid_str $timestr time>$spec_time_limits_imps and time < $endtimes order by time");

$spc_imps_result_spec=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where $uid_str $aid_str $timestr time>$spec_time_limits and time <= $spec_time_limits_imps order by time");


$spc_clk_result=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where $uid_str $aid_str $timestr time>$spec_time_limits_imps and time < $endtimes  order by time");

$spc_clk_result_spec=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where $uid_str $aid_str $timestr time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");





$spc_cval_result=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where $uid_str $aid_str $timestr time>$spec_time_limits_imps and time < $endtimes  order by time");
			
$spc_cval_result_spec=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where $uid_str $aid_str $timestr time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");
			
			}
			
		

$curr_impr=0;
		$curr_clk=0;
		$curr_ctr=0;
		$curr_money=0;

		
		
	
		
		
		
		 if($flag_time==1)
			{
			 $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		////	 echo "select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_daily_statistics` where $uid_str $aid_str and time>$month_time ";
			$current_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_daily_statistics` where $uid_str $aid_str $timestr time>$month_time ");
			
		//echo "select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_daily_statistics` where $uid_str $aid_str and time>$month_time ";
			$current_row=mysql_fetch_row($current_result);
			$curr_clk=$current_row[1]+$spc_clk_result;
			$curr_impr=$current_row[0]+$spc_imps_result;
			$curr_money=$current_row[2]+$spc_cval_result;
			
	
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
	
	
	


	
	
				
		
			}
		
				 if($flag_time==2)
			{
				    $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$current_day_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_daily_statistics` where $uid_str $aid_str $timestr time>$month_time ");
			$current_day_row=mysql_fetch_row($current_day_result);
			//echo "select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_daily_statistics` where $uid_str $aid_str $timestr time>$month_time ";
			$year_time=mktime(0,0,0,1,1,date("Y",time()));
			$current_month_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `advertiser_monthly_statistics` where $uid_str $aid_str $timestr time>$year_time ");
			
			$current_month_row=mysql_fetch_row($current_month_result);
			$curr_clk=$current_day_row[1]+$current_month_row[1]+$spc_clk_result;
			$curr_impr=$current_day_row[0]+$current_month_row[0]+$spc_imps_result;
			$curr_money=$current_day_row[2]+$current_month_row[2]+$spc_cval_result;
		
				
		
		
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
	
	
	
		
		
		
			}
			
			
				while($temp_time<=$end_time)
		{
		
		
		$temp_time_main=$temp_time;
		
	//	echo  "$temp_time=$beg_time=$end_time<br>";flush();
			if($flag_time==0)
			{
				$str=dateFormat($temp_time-1,"%b %d"); 
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time)+1,date("Y",$temp_time));
				$show_duration="$str";
			}
			else if($flag_time==1)
			{
				$str=dateFormat($temp_time-86400,"%b");//"M",$temp_time-86400);
				$temp_time=mktime(0,0,0,date("n",$temp_time)+1,date("j",$temp_time),date("Y",$temp_time));
				$show_duration="$str";
			}
			else if($flag_time==2)
			{
				$str=dateFormat($temp_time-86400,"%Y");//("Y",$temp_time-86400);
				$temp_time=mktime(0,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time)+1);
				$show_duration="$str";
			}
			if($flag_time==-1)
			{
					$str=dateTimeFormat($temp_time,"%d %b %l %p");//("d M h a",$temp_time);
					$temp_time=mktime(date("H",$temp_time)+1,0,0,date("n",$temp_time),date("j",$temp_time),date("Y",$temp_time));
					$show_duration="$str";
			}
			
			
			  
                if($flag_time==-1)
                {
           
               
               
                        if($result_arr[$temp_time_main])//$clk_row[2]==$temp_time_main
                        {
                           
                          
                           
                           
        $main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$result_arr[$temp_time_main][0];
        $main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$result_arr[$temp_time_main][1];       
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$result_arr[$temp_time_main][2];   
       
       
                       
                           
               
                           
                       
                           
                       

                           
                        }
                        else
                        {
                       
        $main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=0;
        $main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=0;       
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=0;   
       
       
       
       
                        $imp_cnt_last=0;
                        $clk_cnt_last=0;
                        $money_cnt_last=0;
                       
           
                       
                           
                           
                       
                       
               
                        }
                       
                           
                       
                       
           
                }
            else
            {
            if($loop_flag==0)
                        $row=mysql_fetch_row($stat_result);
              
                    if($row[3]==$temp_time_main)
                    {
                        $loop_flag=0;
                   
                       
        
		
		
		
		if($flag_time!=1 && $flag_time!=2)
		{
			
		//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
		if($row[3] == $minus_time)
				{
				
				$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$row[0]+$spc_imps_result_spec;
				$main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$row[1]+$spc_clk_result_spec;       
                $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$row[2]+$spc_cval_result_spec;       
				
				}
				else
				{
				$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$row[0];
				$main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$row[1];       
                $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$row[2];               
				
				}
		}
		else
		{	
		   if($row[3] == $minus_time)
				{
				$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$row[0]+$fun_imp;
				$main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$row[1]+$fun_clk;       
                $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$row[2]+$fun_money; 
				
				
				}
				else
				{
		
			
		$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$row[0];
        $main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$row[1];       
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$row[2]; 
		
		        }              
        }              
               
                    }
                    else
                    {
					
					
					
					
					
                        $loop_flag=1;
                        $imp_cnt=0;
                        $clk_cnt=0;
                        $money_cnt=0;
                       
                    if($end_time !=$temp_time_main)
                    {
        
		
		
		
		if($flag_time!=1 && $flag_time!=2)
			{
			//$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));
			if($temp_time_main == $minus_time)
			{
		
		$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$imp_cnt+$spc_imps_result_spec;
        $main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$clk_cnt+$spc_clk_result_spec;        
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$money_cnt+$spc_cval_result_spec;                 
            }
			else
			{
			
		$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$imp_cnt;	
		$main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$clk_cnt;       
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$money_cnt;           
			
			}   
                }
				else
				{
				
				if($temp_time_main == $minus_time)
			    {
				$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$fun_imp;
				$main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$fun_clk;       
                $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$fun_money; 
				
				
				}
				else
				{
				
				$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$imp_cnt;	
				$main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$clk_cnt;       
                $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$money_cnt;   
				
				
				
				}
				
				
				
				}
				
				
				
				
				
				
				    }       
                       

                    }

                    if($end_time==$temp_time_main)
                        {
           
		   
		              
                         if($flag_time==1 || $flag_time==2)
                            {
                            if($temp_time_main == $minus_time)
							{
		$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$curr_impr+$fun_imp;
        $main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$curr_clk+$fun_clk;      
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$curr_money+$fun_money;         
							
							
							}
							else
							{
                               
        $main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$curr_impr+$spc_imps_result_spec;     //new change
        $main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$curr_clk+$spc_clk_result_spec;            //new change
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$curr_money+$spc_cval_result_spec;     //new change
		
		
		
		
		
		
				      
  
		
		
		
		
		
		
		              
                           
                            }
                            }
                            else
                            {
                             
                               
        /*$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$row[0];
        $main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$row[1];        
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$row[2];           */                    
                               
							   
							   
							   
							   
							   
							   
		$main_array[$temp_time_main]['impressions']=$main_array[$temp_time_main][0]=$spc_imps_result;
        $main_array[$temp_time_main]['clicks']=$main_array[$temp_time_main][1]=$spc_clk_result;        
        $main_array[$temp_time_main]['clickvalue']=$main_array[$temp_time_main][2]=$spc_cval_result;      
                           
                               
                            }
                        }
           
              }
           
     
           
               
}


//print_r($main_array);
return $main_array;
}
