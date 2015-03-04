<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
include_once 'advertiser_statistics_utils.php';


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
$tablename="ppc_daily_clicks";
$tablename1="ppc_daily_ impressions";

//echo date("d M h a",$end_time);
}
else if($show=="week")
{
$flag_time=0;	
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}
else if($show=="month")
{
$flag_time=0;	
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}

else
{
$flag_time=0;	
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));	


$key=md5($username.$password);
$getkey=$_GET['key'];



?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<style type="text/css">
body { margin:0; padding:0; font-family:Arial, Helvetica, sans-serif; background:#ddd}
<!--
.style1 {color: #FF0000}
-->
.datatable {
    border: 1px solid #CCCCCC;
}
.datatable tr {
    height: 25px;
}
.datatable td {
    border-bottom: 1px solid #CCCCCC;
    font-size: 11px;
    padding: 0 2px;
}
.datatable .headrow {
    background-color: #CCCCCC;
}
.datatable .headrow td {
    font-weight: bold;
}
.datatable .specialrow {
    background-color: #EFEFEF;
}
</style>

</head>

<body bgcolor="#CCCCCC" style="padding:2px 4px;">



<div style="padding:2px 0px;">
<form name="form1" method="get" action="statistics.php">
		  
           
  
				
				<select name="statistics" id="statistics">
  
              <option value="day" <?php if($show=="day") { ?>  selected  <?php } ?> >Today &nbsp;</option>
  
              <option value="week"  <?php if($show=="week") { ?>  selected  <?php } ?> >Last 14 days&nbsp;</option>
  
              <option value="month"  <?php if($show=="month") { ?>  selected <?php } ?> >Last 30 days&nbsp;</option>
  
            
              </select>
			  
    <input type="hidden" name="key" value="<?php echo $getkey;?>">
    <input type="submit" name="Submit" value="Submit">
 
 
   
        

</form>
</div>
<table      border="0"  cellpadding="0" cellspacing="0" width="100%"   style="background-color:#FFFFFF" class="datatable">

 <?php
if( $key==$getkey )
{
?>


<tr>

 <td width="48%" height="20">Impressions</td>
 <td width="1%">:</td>
 <td width="51%"><?php  $imp= getOverallAdImpressions($time,$mysql,$flag_time);
 echo numberFormat($imp,0); ?></td>
  </tr>
  
<tr>
 <td height="20">Clicks</td>
 <td>:</td>
 <td><?php  
    $clk=  getOverallAdClicks($time,$mysql,$flag_time);
	 echo numberFormat($clk,0);
	 ?></td>
  </tr>
  
<tr>
 <td height="20">CTR</td>
 <td>:</td>
 <td><?php  
    echo  numberFormat(getCTR($clk,$imp)); ?> % </td>
  </tr>
<tr>
 <td height="20">Click Value</td>
 <td>:</td>
 <td><?php  
    echo  moneyFormat(getOverallAdClickValue($time,$mysql,$flag_time)); ?></td>
  </tr>
<tr>
 <td height="20">Publisher click share</td>
 <td>:</td>
 <td><?php  
	echo moneyFormat(getOverallAdClickPubShare($time,$mysql,$flag_time));
 ?></td>
  </tr>
<tr>
 <td height="20">Publisher referral share</td>
 <td>:</td>
 <td><?php  
	
$tot_pub_ref_share1 = getOverallAdClickPubRefShare($time,$mysql,$flag_time);

$tot_adv_ref_share2 = getOverallAdClickAdvRefShare($time,$mysql,$flag_time);

$tot_ref_share=$tot_pub_ref_share1+$tot_adv_ref_share2;
	echo moneyFormat($tot_ref_share);
 ?></td>
  </tr>
  <?php }?>
</table>


</body>
</html>