<?php 







/*--------------------------------------------------+



|													 |



| Copyright � 2006 http://www.inoutscripts.com/      |



| All Rights Reserved.								 |



| Email: contact@inoutscripts.com                    |



|                                                    |



+---------------------------------------------------*/















?><?php



include("config.inc.php");



if(!isset($_COOKIE['inout_admin']))
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
}




?><?php include("admin.header.inc.php"); ?>
<?php 

include("../graphutils.inc.php");
include("../advertiser_statistics_utils.php");




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
//$end_time=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
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
	//$end_time=mktime(0,0,0,date("m",time()),date("y",time()));
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

if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	 else if($flag_time==2)
	 {
	 	$table_name="advertiser_yearly_statistics";
	 }
	 else
	 {
		$table_name="advertiser_monthly_statistics";
	 }
	// echo $end_time;
?>
<script language='javascript' src='../FusionCharts/FusionCharts.js'></script>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/status.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Overall Statistics</td>
  </tr>
</table> 



<br />
<form name="form1" method="get" action="ppc-time-profit-statistics.php">
    Show statistics as of 
        <select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?>>Today</option>
        <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 days</option>
        <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 days</option>
        <option value="year"  <?php 
			  				  if($show=="year")echo "selected";			  
			  ?>>Last 12 months</option>
        <option value="all"  <?php 
			  				  if($show=="all")echo "selected";			  
			  ?>>All Time</option>
      </select>
        <input type="submit" name="Submit" value="Show Statistics">
</form>
	<br />




    <?php 
    
$display_str="";
if($show=="day")
$display_str="last 24 hours";
if($show=="week")
$display_str="last 14 days";
if($show=="month")
$display_str="last 30 days";
if($show=="year")
$display_str="last 12 months";
if($show=="all")
$display_str="all time";
		  
    ?>
  
  
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#ededed" style="border:1px solid #999999 ">
    
    <tr>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
      <td >&nbsp;</td>
    </tr>
    <tr>
      <td >Total Impressions </td>
      <td >:</td>
      <td ><?php 




     $tot_imp = getOverallAdImpressions($time,$mysql,$flag_time);

echo numberFormat( $tot_imp,0);

    //$tot_clk_sent=28;
    ?>&nbsp;</td>
      <td >Total Clicks Sent </td>
      <td >:</td>
      <td ><?php 




     $tot_clk_sent = getOverallAdClicks($time,$mysql,$flag_time);

echo numberFormat( $tot_clk_sent,0);


    //$tot_clk_sent=28;
    ?>&nbsp;</td>
    </tr>
    <tr>
      <td width="33%" >&nbsp;</td>
      <td width="2%" >&nbsp;</td>
      <td width="13%" >&nbsp;</td>
      <td width="33%" >&nbsp;</td>
      <td width="1%" >&nbsp;</td>
      <td width="16%" >&nbsp;</td>
    </tr>
    <tr>
    <td >Total CTR </td>
    <td >: </td>
    <td ><?php
echo numberFormat( getCTR($tot_clk_sent,$tot_imp));
	
	?>%</td>
    <td >Total Click Value</td>
    <td ><strong>:</strong></td>
    <td ><?php 	


 $tot_clk_value = getOverallAdClickValue($time,$mysql,$flag_time);

echo moneyFormat($tot_clk_value);



	/*
	if($show=="day")
		{
		
			$ret=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time>$time"); 
		}
	else
		{
			$ret=$mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where time>$time");
		}
	if( $ret=="")
	$ret=0;
$ret=round($ret,2);
	echo  $ret;
*/
	?>&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td >  Click  Share For Publishers </td>
    <td > :</td>
    <td ><?php 	


$tot_clk_pub_share = getOverallAdClickPubShare($time,$mysql,$flag_time);

echo moneyFormat($tot_clk_pub_share);



/*
		if($show=="day")
		{
		
			$re=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from ppc_daily_clicks where time>$time"); 
		}
	else
		{
			$re=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where time>$time");
		}
	if( $re=="")
	$re=0;
$re=round($re,2);
	echo  $re;
*/
	?></td>
    <td >Referral Share For Publishers</td>
    <td ><strong>:</strong></td>
    <td ><?php 	



$tot_pub_ref_share1 = getOverallAdClickPubRefShare($time,$mysql,$flag_time);




$tot_pub_ref_share=round($tot_pub_ref_share1,2);


$tot_adv_ref_share1 = getOverallAdClickAdvRefShare($time,$mysql,$flag_time);



$tot_adv_ref_share=round($tot_adv_ref_share1,2);

$tot_ref_share=$tot_pub_ref_share+$tot_adv_ref_share;
	echo moneyFormat($tot_ref_share);


/*

			if($show=="day")
		{
		
			$ref_sh=$mysql->echo_one("select SUM( pub_ref_profit) from  ppc_daily_clicks where time>$time");
		}
	else
		{
		$ref_sh=$mysql->echo_one("select SUM( pub_ref_profit) from $table_name where time>$time");
		}
	if( $ref_sh=="")
	$ref_sh=0;
$ref_sh=round($ref_sh,2);
			if($show=="day")
		{
		
		$adv_ref_sh=$mysql->echo_one("select SUM(adv_ref_profit) from  ppc_daily_clicks where time>$time");
		}
	else
		{
		$adv_ref_sh=$mysql->echo_one("select SUM(adv_ref_profit) from $table_name where time>$time");
		}
	if( $adv_ref_sh=="")
	$adv_ref_sh=0;
	$adv_ref_sh=round($adv_ref_sh,2);	
	$tot_ref=$ref_sh+$adv_ref_sh;
	echo $tot_ref;
*/
?> &nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" valign="top" >Your Share <span class="info">(     
      Total Click Value
- Click  Share For Publishers
- Referral Share For Publishers )</span>     <br></td>
    <td valign="top" ><strong>:</strong></td>
    <td valign="top" ><?php 	echo moneyFormat($tot_clk_value-($tot_clk_pub_share+$tot_ref_share)); ?>&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
</table>



<br />

     <?php
      if($GLOBALS['currency_format']=="$$")
      $symb=$GLOBALS['system_currency'];
      else
       $symb=$GLOBALS['currency_symbol']; 
	# Include FusionCharts PHP Class
	include('../FusionCharts/Class/FusionCharts_Gen.php');
	
	# Create Column3D chart Object
	$FC = new FusionCharts("Column3D","700","300");
	# set the relative path of the swf file
	$FC->setSWFPath("../FusionCharts/");
	
	# Set chart attributes
	$strParam="caption=Profit statistics of $display_str;xAxisName=;yAxisName=Amount;numberPrefix=$symb;decimalPrecision=2;";
	$FC->setChartParams($strParam);
	
	# add chart values and category names
	//$FC->addChartData("$tot_clk_sent","name=Total Clicks Sent");
	$FC->addChartData("$tot_clk_value","name=Total Click Value");
	$FC->addChartData("$tot_clk_pub_share","name=Click Share For Publishers");
	$FC->addChartData("$tot_ref_share","name=Referral Share");
	//$tot_ref
	$temp=$tot_clk_value-$tot_clk_pub_share-$tot_ref_share;
	$FC->addChartData("$temp","name=Your Share");
	if($tot_clk_value!=0)
		$FC->renderChart();
?>
    
<br />


  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
      <tr class="headrow">
        <td width="20%"><span class="style2">Period</span></td>
           <td width="20%"><span class="style2">Clicks</span></td>
        <td width="20%"><span class="style2">Impressions</span></td>
        <td width="20%"><span class="style2">CTR(%) </span></td>
        <td width="20%"><span class="style2">Money Spent(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </span></td>
        
      </tr>
  <?php
  
$tablestructure=getTimeBasedAdvertiserStatistics($show,$flag_time,$beg_time,$end_time,$uid=0,$id=0);
 if(count($tablestructure) > 0)
{
$i=0;
foreach($tablestructure as $key => $value)
{
	
if($flag_time==0)
            {
               // $str=date("d/M/Y",$key-1);
$str=dateFormat($key-1,"%b %d"); 
                $show_duration="$str";
                           
            }
            else if($flag_time==1)
            {
               // $str=date("M/Y",$key-86400);
$str=dateFormat($key-86400,"%b");
                $show_duration="$str";
            }
             else if($flag_time==2)
            {
       
               // $str=date("Y",$key-86400);
$str=dateFormat($key-86400,"%Y");
                $show_duration="$str";
            }   
           
             if($flag_time==-1)
            {
           // $str=date("d/M h a",$key);
$str=dateTimeFormat($key,"%d %b %l %p");
            $show_duration="$str";
            }
		      
  
  
  ?>
  <tr <?php if($i%2==1) echo 'bgcolor="#ededed"';?> height="25">
  <td><?php echo $show_duration; ?></td>
 <td><?php echo numberFormat($value[1],0); ?></td>
 <td><?php echo numberFormat($value[0],0); ?></td>
 <td><?php echo numberFormat(getCTR($value[1],$value[0])); ?></td>
 <td><?php echo numberFormat($value[2]); ?></td>
  </tr> 
  <?php 
  
  $i++;
}
}
  ?>

  </table>


<br />


<?php include("admin.footer.inc.php"); ?>
