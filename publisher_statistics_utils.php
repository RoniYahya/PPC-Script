<?php

function getPubAdClicks($time,$mysql,$uid,$flag_time)
{

$day=date("j",time());
$mont=date("n",time());


$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid'  and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
	 $table_name="publisher_yearly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid'  and time>=$time");
			  
			  if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
        	  $year_time=mktime(0,0,0,1,1,date("Y",time()));
			  
			  $temp2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_monthly_statistics where uid='$uid'  and time>$year_time");
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  
			  $temp3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
			  $temp=$temp1+$temp2+$temp3;
			  
			  $temp=$temp+getPubAdClicks($spec_time_limits,$mysql,$uid,$spec_flag_limits);
			  
			  
			 if($temp=="")
				return 0;
			else
				return $temp;
	 	}
	 else
	 {
		$table_name="publisher_monthly_statistics";
			  $temp1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_monthly_statistics where uid='$uid'  and time>=$time");
			  
			  if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			  $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			  
			  
			  $temp2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
			  $temp=$temp1+$temp2;
			  
			   $temp=$temp+getPubAdClicks($spec_time_limits,$mysql,$uid,$spec_flag_limits);
			  
			  
			 if($temp=="")
				return 0;
			else
				return $temp;
	 }
	  $temp=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid'  and time>=$time");
	  
	   $temp=$temp+getPubAdClicks($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	   
	 if($temp=="")
		return 0;
	else
		return $temp;
	//return round($mysql->total("ppc_clicks","uid='$uid'  and time>$time"),2);

}

function getPubAdImpressions($time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());
//$spec_time_limits=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
		$table_name="publisher_impression_daily";
		 $temp=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid'  and time>$time");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		$temp1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid'  and time>=$time");
		
		if($day ==1 && $mont ==1)
		$year_time=mktime(0,0,0,1,1,date("Y",time())-1);
		else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		
		$temp2=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_monthly_statistics where uid='$uid'  and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$temp3=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$temp=$temp1+$temp2+$temp3;
		
		 $temp=$temp+getPubAdImpressions($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		 
		 
	 if($temp=="")
	 	return 0;
	else
		return $temp;
	 	}
	 else
	 {
		//$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$temp1=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_monthly_statistics where uid='$uid'  and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$temp2=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$temp=$temp1+$temp2;
		
		 $temp=$temp+getPubAdImpressions($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		 
	 if($temp=="")
	 	return 0;
	else
		return $temp;
	 }
	 $temp=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid'  and time>=$time");
	 
	  $temp=$temp+getPubAdImpressions($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	  
	 if($temp=="")
	 	return 0;
	else
		return $temp;
}

function getPubAdClickrate($time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

//$spec_time_limits_imps=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));

$spec_time_limits_imps=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
		$table_name="publisher_impression_daily";
		 $d_clicks=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid'  and time>$time and time < $endtimes ");
		 $d_impr=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid'  and time>$time");
		 if($d_impr==0)
		 	return 0;
		else
			return round($d_clicks/$d_impr*100,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ad_impr1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid'  and time>=$time");
		$ad_clk1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid'  and time>=$time");
		
		
		if($day ==1 && $mont ==1)
		$year_time=mktime(0,0,0,1,1,date("Y",time())-1);
		else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		
		
		$ad_impr2=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_monthly_statistics where uid='$uid'  and time>$year_time");
		$ad_clk2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_monthly_statistics where uid='$uid'  and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ad_impr3=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ad_clk3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ad_impr=$ad_impr1+$ad_impr2+$ad_impr3;
		$ad_clk=$ad_clk1+$ad_clk2+$ad_clk3;
		
		
		
		$d_clicks_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid'  and time>=$spec_time_limits and time < $endtimes ");
		$d_impr_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid'  and time>=$spec_time_limits_imps");
		
		$ad_impr=$ad_impr+$d_impr_new;
		$ad_clk=$ad_clk+$d_clicks_new;
		
		
	if($ad_impr==0)
	{
		return 0;
	}
	else
	{
		//return round($mysql->total("ppc_clicks","uid='$uid'  and time>$time")/$ad_impr,2)*100;
		return round($ad_clk/$ad_impr,2)*100;
	}
		
	 	}
	 else
	 {
		$table_name="publisher_monthly_statistics";
		$ad_impr1=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_monthly_statistics where uid='$uid'  and time>=$time");
		$ad_clk1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_monthly_statistics where uid='$uid'  and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ad_impr2=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ad_clk2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ad_impr=$ad_impr1+$ad_impr2;
		$ad_clk=$ad_clk1+$ad_clk2;
		
		
		$d_clicks_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid'  and time>=$spec_time_limits and time < $endtimes ");
		$d_impr_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid'  and time>=$spec_time_limits_imps");
		
		$ad_impr=$ad_impr+$d_impr_new;
		$ad_clk=$ad_clk+$d_clicks_new;
		
		
	if($ad_impr==0)
	{
		return 0;
	}
	else
	{
		//return round($mysql->total("ppc_clicks","uid='$uid'  and time>$time")/$ad_impr,2)*100;
		return round($ad_clk/$ad_impr,2)*100;
	}
		
	 }
	$ad_impr=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid'  and time>=$time");
	$ad_clk=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid'  and time>=$time");
	
	
	
	    $d_clicks_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid'  and time>=$spec_time_limits and time < $endtimes ");
		$d_impr_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid'  and time>=$spec_time_limits_imps");
		
		$ad_impr=$ad_impr+$d_impr_new;
		$ad_clk=$ad_clk+$d_clicks_new;
	
	
	if($ad_impr==0)
	{
		return 0;
	}
	else
	{
		//return round($mysql->total("ppc_clicks","uid='$uid'  and time>$time")/$ad_impr,2)*100;
		return round($ad_clk/$ad_impr,2)*100;
	}
}

function getPubAdMoneySpent($time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where pid='$uid'  and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return round($temp,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid='$uid'  and time>=$time");
		
		if($day ==1 && $mont ==1)
		$year_time=mktime(0,0,0,1,1,date("Y",time())-1);
		else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		
		$ret2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from publisher_monthly_statistics where uid='$uid'  and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(money_spent),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		
		$ret=$ret+getPubAdMoneySpent($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 	}
	 else
	 {
		//$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from publisher_monthly_statistics where uid='$uid'  and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPubAdMoneySpent($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 }
	//$ret=$mysql->echo_one("select SUM(clickvalue) from ppc_clicks where uid='$uid'  and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid='$uid'  and time>=$time");
	
	$ret=$ret+getPubAdMoneySpent($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	

}

function getPubPublisherprofit($time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where pid='$uid'  and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return $temp;
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid'  and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		
		
		$ret2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from publisher_monthly_statistics where uid='$uid'  and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getPubPublisherprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
		
	if( $ret=="")
	$ret=0;
	return $ret;
	 	}
	 else
	 {
		//$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from publisher_monthly_statistics where uid='$uid'  and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPubPublisherprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return $ret;
	 }
	//$ret=$mysql->echo_one("select SUM(publisher_profit) from ppc_clicks where uid='$uid'  and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid'  and time>=$time");
	
	$ret=$ret+getPubPublisherprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	
	if( $ret=="")
	$ret=0;
	return $ret;
	

}
function getPubPublisherrefprofit($time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where pub_rid='$uid'  and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return round($temp,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$uid'  and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from publisher_monthly_statistics where uid='$uid'  and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getPubPublisherrefprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 	}
	 else
	 {
		//$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from publisher_monthly_statistics where uid='$uid'  and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPubPublisherrefprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 }
	//$ret=$mysql->echo_one("select SUM(pub_ref_profit) from ppc_clicks where uid='$uid'  and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$uid'  and time>=$time");
	
	$ret=$ret+getPubPublisherrefprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	

}
function getPubAdvertiserrefprofit($time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		$table_name="ppc_daily_clicks";
		 $temp=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where adv_rid='$uid'  and time>$time and time < $endtimes ");
		  if($temp=="")
				return 0;
			else
				return round($temp,2);
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$uid'  and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from publisher_monthly_statistics where uid='$uid'  and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getPubAdvertiserrefprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 	}
	 else
	 {
		//$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from publisher_monthly_statistics where uid='$uid'  and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from publisher_daily_statistics where uid='$uid'  and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPubAdvertiserrefprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
		
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	 }
	//$ret=$mysql->echo_one("select SUM(adv_ref_profit) from ppc_clicks where uid='$uid'  and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$uid'  and time>=$time");
	
	$ret=$ret+getPubAdvertiserrefprofit($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	
	if( $ret=="")
	$ret=0;
	return round($ret,2);
	

}

function getAdvertiserReferralProfitOfPublisher($time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==-1)
	{
		$ret=$mysql->echo_one("select COALESCE(SUM(adv_ref_profit),0) from ppc_daily_clicks where adv_rid=$uid and time>$time and time < $endtimes ");
		
	}
	else if($flag_time==0)
	{
		$ret=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from daily_referral_statistics where pid='$uid' and time>=$time");
		$ret=$ret+getAdvertiserReferralProfitOfPublisher($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	}
	else if($flag_time==1)
	{
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from monthly_referral_statistics where pid='$uid' and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from daily_referral_statistics where pid='$uid' and time>$month_time");
		$ret=$ret1+$ret2;
		$ret=$ret+getAdvertiserReferralProfitOfPublisher($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	}
	else
	{
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from yearly_referral_statistics where pid='$uid' and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from monthly_referral_statistics where pid='$uid' and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from daily_referral_statistics where pid='$uid' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		$ret=$ret+getAdvertiserReferralProfitOfPublisher($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	}
	
	
	
	
	if($ret=="")
		$ret=0;
	return round($ret,2);
}

function getPublisherReferralProfitOfPublisher($time,$mysql,$uid,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==-1)
	{
		$ret=$mysql->echo_one("select COALESCE(SUM(pub_ref_profit),0) from ppc_daily_clicks where pub_rid=$uid and time>$time and time < $endtimes ");
	}
	else if($flag_time==0)
	{
		$ret=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where pid='$uid' and time>=$time");
		$ret=$ret+getPublisherReferralProfitOfPublisher($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	}
	else if($flag_time==1)
	{
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from monthly_referral_statistics where pid='$uid' and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where pid='$uid' and time>$month_time");
		$ret=$ret1+$ret2;
		$ret=$ret+getPublisherReferralProfitOfPublisher($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	}
	else
	{
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from yearly_referral_statistics where pid='$uid' and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from monthly_referral_statistics where pid='$uid' and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where pid='$uid' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		$ret=$ret+getPublisherReferralProfitOfPublisher($spec_time_limits,$mysql,$uid,$spec_flag_limits);
	}
	
	
	
	
		
	if($ret=="")
		$ret=0;
	return round($ret,2);
}

/*******************
ADD FUNCTIONS FOR GETTING PUBLISHER REFERRAL EARNING TO BE USED IN PUBLISHERPROFIT STATISTICS PAGE
UPDATE ADMIN PAGE ALSO
*********************/

function getPubBlockClicks($time,$mysql,$uid,$id,$flag_time)
{

$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	   else if($flag_time==-1)
	 	{
		
	 	return $mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>$time and time < $endtimes ");
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$res_val1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$res_val2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$res_val3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		
		$res_val1=$res_val1+getPubBlockClicks($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
		if($res_val1=="" && $res_val3=="" && $res_val2=="")
	 		return 0;
		else
			return $res_val1+$res_val2+$res_val3;
	 	}
	 else
	 {
		//$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$res_val1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$res_val2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		
		$res_val1=$res_val1+getPubBlockClicks($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
		
		if($res_val1=="" && $res_val2=="")
	 		return 0;
		else
			return $res_val1+$res_val2;
	 }
	  $res_val=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
	  
	  $res_val=$res_val+getPubBlockClicks($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
	  
	 if($res_val=="")
	 	return 0;
	else
		return $res_val;	
	//return round($mysql->total("ppc_clicks","uid='$uid' and bid='$id' and time>$time"),2);

}

function getPubBlockImpressions($time,$mysql,$uid,$id,$flag_time)
{    
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time())); 
//$spec_time_limits=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));

$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
     // getPubBlockImpressions($time,$mysql,0,$row[1],$flag_time);
	if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		 $res_val1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
		 
		  if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		 $year_time=mktime(0,0,0,1,1,date("Y",time()));
		 $res_val2=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>$year_time");
		 if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		 $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		 $res_val3=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		 
		 $res_val1=$res_val1+getPubBlockImpressions($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		 
		 if($res_val1=="" && $res_val3=="" && $res_val2=="")
	 		return 0;
		else
			return $res_val1+$res_val2+$res_val3;	
		
		
		
	 	}
	 else if($flag_time==-1)
	 	{
		
		
	 	return $mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid' and bid='$id' and time>$time");
	 	}
	 else
	 {
//		$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$res_val1=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		 $month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		 $res_val2=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		 
		 $res_val1=$res_val1+getPubBlockImpressions($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		 
		 
		 if($res_val1=="" && $res_val2=="")
	 		return 0;
		else
			return $res_val1+$res_val2;	
	 }
	 	 $res_val=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
		 
		 $res_val=$res_val+getPubBlockImpressions($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		 
	 if($res_val=="")
	 	return 0;
	else
		return $res_val;	
}

function getPubBlockClickRate($id,$time,$mysql,$uid,$id,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

//$spec_time_limits_imps=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits_imps=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
	$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
	$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	$imp_count=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid' and bid='$id' and time>$time");
		$clk_count=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>$time and time < $endtimes ");
		if($imp_count==0)
			return 0;
		else
			{
			return round(($clk_count/$imp_count)*100,2);
			}
	 	}
	 else if($flag_time==2)
	 	{
			$table_name="publisher_yearly_statistics";
			
			$ad_impr1=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
			$ad_clk1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
			
			
			 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
			$year_time=mktime(0,0,0,1,1,date("Y",time()));
			$ad_impr2=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>$year_time");
			$ad_clk2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>$year_time");
			if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$ad_impr3=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
			$ad_clk3=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
			$ad_clk=$ad_clk1+$ad_clk2+$ad_clk3;
			$ad_impr=$ad_impr1+$ad_impr2+$ad_impr3;
			
			
			$imp_count_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid' and bid='$id' and time>=$spec_time_limits_imps");
		    $clk_count_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>=$spec_time_limits and time < $endtimes ");
			
			$ad_clk=$ad_clk+$clk_count_new;
			$ad_impr=$ad_impr+$imp_count_new;
			
			
			
			
			
			
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
		//$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
			$ad_impr1=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>=$time");
			$ad_clk1=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>=$time");
			if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
			$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			$ad_impr2=$mysql->echo_one("select COALESCE(sum(impression),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
			$ad_clk2=$mysql->echo_one("select COALESCE(sum(clk_count),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
			$ad_clk=$ad_clk1+$ad_clk2;
			$ad_impr=$ad_impr1+$ad_impr2;
			
			
			$imp_count_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid' and bid='$id' and time>=$spec_time_limits_imps");
		    $clk_count_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>=$spec_time_limits and time < $endtimes ");
			
			$ad_clk=$ad_clk+$clk_count_new;
			$ad_impr=$ad_impr+$imp_count_new;
			
			
			
			if($ad_impr==0)
			{
				return 0;
			}
			else
			{
				return round($ad_clk/$ad_impr,2)*100;
			}
		
	 }
	$ad_impr=$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
	$ad_clk=$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
	
	
	        $imp_count_new=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$uid' and bid='$id' and time>=$spec_time_limits_imps");
		    $clk_count_new=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>=$spec_time_limits and time < $endtimes ");
			
			$ad_clk=$ad_clk+$clk_count_new;
			$ad_impr=$ad_impr+$imp_count_new;
	
	
	
	
	if($ad_impr==0)
	{
		return 0;
	}
	else
	{
		return round($ad_clk/$ad_impr,2)*100;
	}
	
	
}

function getPubBlockMoneySpent($time,$mysql,$uid,$id,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	return round($mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>$time and time < $endtimes "),2);
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(money_spent),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		
		$ret=$ret+getPubBlockMoneySpent($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	
	 	}
	 else
	 {
		//$table_name="publisher_monthly_statistics";
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(money_spent),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(money_spent),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPubBlockMoneySpent($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	 }
	//$ret=$mysql->echo_one("select SUM(clickvalue) from ppc_clicks where uid='$uid' and aid='$id' and time>$time");
	$ret=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
	$ret=$ret+getPubBlockMoneySpent($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
	
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
//	
//	$ret=$mysql->echo_one("select SUM(clickvalue) from ppc_clicks where uid='$uid' and bid='$id' and time>$time");
//	if( $ret=="")
//	$ret=0;
//	return round($ret,2);
	

}

function getPubBlockPublisherprofit($time,$mysql,$uid,$id,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{

	 	return $mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>$time and time < $endtimes ");
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getPubBlockPublisherprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
		
	if( $ret=="")
		$ret=0;
	else
		$ret=$ret;
	return $ret;	
	
	 	}
	 else
	 {
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPubBlockPublisherprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
	if( $ret=="")
		$ret=0;
	else
		$ret=$ret;
	return $ret;	
	 }
	
	$ret=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
	
	$ret=$ret+getPubBlockPublisherprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
	
	if( $ret=="")
		$ret=0;
	else
		$ret=$ret;
	return $ret;	

	

}
function getPubBlockPublisherrefprofit($time,$mysql,$uid,$id,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	return round($mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>$time and time < $endtimes "),2);
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getPubBlockPublisherrefprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	
	 	}
	 else
	 {
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPubBlockPublisherrefprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	 }
	
	$ret=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
	
	$ret=$ret+getPubBlockPublisherrefprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
	
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	

	

}
function getPubBlockAdvrefprofit($time,$mysql,$uid,$id,$flag_time)
{
$day=date("j",time());
$mont=date("n",time());

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
$spec_flag_limits=-1;
	if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	  else if($flag_time==-1)
	 	{
	 	return round($mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where pid='$uid' and bid='$id' and time>$time and time < $endtimes "),2);
	 	}
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
		
		 if($day ==1 && $mont ==1)
			  $year_time=mktime(0,0,0,1,1,date("Y",time())-1);
			  else
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>$year_time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret3=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		$ret=$ret1+$ret2+$ret3;
		
		$ret=$ret+getPubBlockAdvrefprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	
	 	}
	 else
	 {
		
		$year_time=mktime(0,0,0,1,1,date("Y",time()));
		$ret1=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from publisher_monthly_statistics where uid='$uid' and bid='$id' and time>=$time");
		if($day ==1)
			  $month_time=mktime(0,0,0,date("m",time())-1,1,date("Y",time()));
			  else
		$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
		$ret2=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from publisher_daily_statistics where uid='$uid' and bid='$id' and time>$month_time");
		$ret=$ret1+$ret2;
		
		$ret=$ret+getPubBlockAdvrefprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
		
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	
	 }
	
	$ret=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$uid' and bid='$id' and time>=$time");
	
	$ret=$ret+getPubBlockAdvrefprofit($spec_time_limits,$mysql,$uid,$id,$spec_flag_limits);
	
	if( $ret=="")
		$ret=0;
	else
		$ret=round($ret,2);
	return $ret;	

	

}

function getTimeBasedPublisherStatistics($show,$flag_time,$beg_time,$end_time,$uid,$id)
{
	
if($flag_time == 0)
$minus_time=mktime(0,0,0,date("n",$end_time),date("j",$end_time)-1,date("Y",$end_time));




$spec_time_limits_imps=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
	$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
	$endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
	$spec_flag_limits=-1;
	global $mysql;
if($beg_time==0)
$beg_time=$mysql->echo_one("select min(time) from publisher_yearly_statistics");
$main_array=array();
$temp_time=$beg_time;



	

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
			//echo "select COALESCE(sum(cnt),0)time from publisher_impression_daily where pid=$uid $bid_str and time>=$beg_time group by time";
			if($uid>0)
			$click_result=mysql_query("select COALESCE(count(id),0),COALESCE(sum(	publisher_profit),0),time from ppc_daily_clicks where pid=$uid $bid_str and time>$beg_time and time < $endtimes  group by time order by time");
			else
			$click_result=mysql_query("select COALESCE(count(id),0),COALESCE(sum(	clickvalue),0),time from ppc_daily_clicks where pid=$uid $bid_str and time>$beg_time and time < $endtimes  group by time order by time");
			//echo "select COALESCE(count(id),0),COALESCE(sum(	publisher_profit),0),time from ppc_daily_clicks where pid=$uid $bid_str and time>=$beg_time and time < $endtimes  group by time order by time";
			
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
			if($uid >0)	
			$stat_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0),time from `$table_name` where uid=$uid $bid_str and time>=$beg_time group by time order by time");
			else
			$stat_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),time from `$table_name` where uid=$uid $bid_str and time>=$beg_time group by time order by time");
			
			
$spc_imps_result=0;
$spc_clk_result=0;
$spc_cval_result=0;			
			
$spc_imps_result=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid=$uid $bid_str and time>$spec_time_limits_imps and time < $endtimes order by time");

$spc_imps_result_spec=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid=$uid $bid_str and time>$spec_time_limits and time <= $spec_time_limits_imps order by time");


$spc_clk_result=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits_imps and time < $endtimes  order by time");

$spc_clk_result_spec=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");


if($uid>0)
{
$spc_cval_result=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits_imps and time < $endtimes order by time");			
			
$spc_cval_result_spec=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");						
}
else
{
	$spc_cval_result=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits_imps and time < $endtimes order by time");			
			
$spc_cval_result_spec=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where pid=$uid $bid_str and time>$spec_time_limits and time <= $spec_time_limits_imps  order by time");									
}
			
			
			
			}
			
	
	
		
	
		
		$curr_impr=0;
		$curr_clk=0;
		$curr_ctr=0;
		$curr_money=0;
		
		
	

		
		
		
		 if($flag_time==1)
			{
			
			
			$month_time=mktime(0,0,0,date("m",time()),1,date("Y",time()));
			
			if($uid>0)
			$current_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time");
		else
		$current_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time");
		
			
			//	echo "select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time";
			$current_row=mysql_fetch_row($current_result);
			$curr_clk=$current_row[1]+$spc_clk_result;
			$curr_impr=$current_row[0]+$spc_imps_result;
			$curr_money=$current_row[2]+$spc_cval_result;
			
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
		
		if($uid>0)
		$fun_money=getCurrentMonthStatistics($flag_time,$mysql,"publisher_profit","publisher_profit",$uid,$idsstr,1,2,1);
		else
		$fun_money=getCurrentMonthStatistics($flag_time,$mysql,"money_spent","clickvalue",$uid,$idsstr,1,2,1);
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
			if($uid>0)
			$current_day_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time ");
			else
			$current_day_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `publisher_daily_statistics` where uid=$uid  $bid_str and time>$month_time ");
			
			$current_day_row=mysql_fetch_row($current_day_result);
			
			$year_time=mktime(0,0,0,1,1,date("Y",time()));
			if($uid>0)
			$current_month_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from `publisher_monthly_statistics` where uid=$uid $bid_str and time>$year_time");
			else
			 $current_month_result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0) from `publisher_monthly_statistics` where uid=$uid $bid_str and time>$year_time");
			
			$current_month_row=mysql_fetch_row($current_month_result);
			$curr_clk=$current_day_row[1]+$current_month_row[1]+$spc_clk_result;
			$curr_impr=$current_day_row[0]+$current_month_row[0]+$spc_imps_result;
			$curr_money=$current_day_row[2]+$current_month_row[2]+$spc_cval_result;
	
		
		
		
//		echo $current_month_row[0];
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
		
		if($uid>0)
		$fun_money=getCurrentMonthStatistics($flag_time,$mysql,"publisher_profit","publisher_profit",$uid,$idsstr,1,2,1);
		else
		$fun_money=getCurrentMonthStatistics($flag_time,$mysql,"money_spent","clickvalue",$uid,$idsstr,1,2,1);
		
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
