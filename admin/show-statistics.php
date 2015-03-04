<?php 
/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/

?><?php
set_time_limit(0);
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
?><?php include("admin.header.inc.php");


$current_time=time();
$current_year=date("Y",$current_time);
$last_clearance=mysql_query("select e_time from statistics_updation where task='last_clearance_time'");
$last_clearance=mysql_fetch_row($last_clearance);
$last_clearance=$last_clearance[0];
 ?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/status.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Verify Statistics</td>
  </tr>
</table> 



<br>
<strong>Note :</strong><span class="info"><strong> Advertiser statistics</strong>, <strong>publisher statistics</strong> and 


 <strong>referral statistics</strong> are  optimal representations of <strong>raw data</strong>. You may clear the raw data if  raw data statistics is same as advertiser/publisher/referral statistics. Raw data  cannot be restored once deleted. Raw data should be cleared in the order of time (ie, old to new)  </span><br>
<br>

<table width="100%" height="464" align="center" border="0" cellpadding="0" cellspacing="1" style="border:1px solid #999999 ">
    <tr height="25" bgcolor="#666666"><td height="25"  colspan="8" class="style1"><strong><font size="2">Yearly Statistics</font></strong>&nbsp;</td></tr>
  <!-- //////////////////////// -->
  
    <tr bgcolor="#CCCCCC">
 	<td width="14%" height="25" ><strong>Time</strong>&nbsp;</td>
 <td width="19%" height="25" ><strong>From</strong>&nbsp;</td>
    <td width="12%" height="25" ><strong>Impressions&nbsp;</strong></td>
	<td width="12%" height="25" ><strong>Clicks&nbsp;</strong></td>
	<td width="11%" height="25" ><strong>Click value </strong></td>
	<td width="10%" ><strong>Adv ref profit </strong></td>
	<td width="12%" ><strong>Pub ref profit </strong></td>
    <td width="10%" ><strong>Action</strong></td>
  </tr>
  <?php
$day_date=date("j",$current_time);
$month_date=date("n",$current_time);
  
 $year_valu=$mysql->echo_one("select min(time) from advertiser_yearly_statistics");
 
 $month_dvalue=$mysql->echo_one("select min(time) from advertiser_monthly_statistics");
 
 $day_dvalue=$mysql->echo_one("select min(time) from advertiser_daily_statistics");
 
 $hour_dvalue=$mysql->echo_one("select min(time) from advertiser_impression_daily");
 
 
 
 
 
 
 
 
$year=date("Y",$year_valu)-1;
$i=0;


if($year_valu=="" && $month_dvalue=="" && $day_dvalue=="" && $hour_dvalue=="" && $day_date ==1 && $month_date ==1)                         //if($year_valu=="")
{
?>
    <tr>
      <td  height="25"  colspan="8">No Data Found</td>
  </tr>
<?php
}
else if($year_valu=="" && $day_date !=1)                         //if($year_valu=="")
{
?>
    <tr>
      <td  height="25"  colspan="8">No Data Found</td>
  </tr>
<?php
}
else
{

//********************************************** Extra Code ******************************************
if($year_valu=="")
{
 $year_valu=mktime(0,0,0,1,1,date("Y",time()));
 $year=date("Y",$year_valu)-1;

}
//********************************************** Extra Code ******************************************


while($year<$current_year)
  	{
	
    $ver_clk_cnts=0;
	$ver_clickvalue=0;
	$ver_advref_profit=0;
	$ver_pubref_profit=0;	
	
	
	
	$special_data_var=0;
	$result="";
		$result1="";
		$result2="";	
	$row=0;
		$row1=0;
		$row2=0;
	
	 $newdata_imp_adv=0;
	$newdata_imp_pub=0;
	$newdata_clk=0;
	$newdata_clkval=0;
	$newdata_advref=0;
	$newdata_pubref=0;	
	
	
	$current_year_new=date("Y",$current_time)-1;
	
	
    $start_time=mktime(0,0,0,1,1,date("Y",$year_valu)+$i-1);
    $end_time=mktime(0,0,0,1,1,date("Y",$year_valu)+$i);


if($year != $current_year_new)
{


  
  $result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_yearly_statistics where time='$end_time'");
  $result1=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_yearly_statistics where time='$end_time'");
  
  $result2=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from yearly_referral_statistics where time='$end_time'");
	$row=mysql_fetch_row($result);  
 	$row1=mysql_fetch_row($result1);  
	$row2=mysql_fetch_row($result2);  
	
	
	
	
	//////////////////////////////////////////////
	
	    $ver_clk_cnts=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clk_cnts =="")
	    $ver_clk_cnts=0;
		
		
		$ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clickvalue =="")
	    $ver_clickvalue=0;
	
		
		
		$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_advref_profit =="")
	    $ver_advref_profit=0;
		
		
		$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_pubref_profit =="")
	    $ver_pubref_profit=0;
		
	/////////////////////////////////////////////////////
	
	
	
	
	
	
}
else
{
if($day_date ==1 && $month_date==1)
{


$new_tm_data=mktime(0,0,0,1,1,date("Y",$current_time)-1);
$new_tm_data_month=mktime(0,0,0,date("m",$current_time)-1,1,date("Y",$current_time));

 $result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_monthly_statistics where time>'$new_tm_data'  ");
 $result1=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_monthly_statistics where time>'$new_tm_data'  ");	
 $result2=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from monthly_referral_statistics where time>'$new_tm_data'");
	$row=mysql_fetch_row($result);  
 	$row1=mysql_fetch_row($result1); 
	$row2=mysql_fetch_row($result2); 





 $result_new=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_daily_statistics where time>'$new_tm_data_month'  ");
	 $result1_new=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_daily_statistics where time>'$new_tm_data_month'  ");	
	$result2_new=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where time>'$new_tm_data_month'");
	$row_new=mysql_fetch_row($result_new);  
 	$row1_new=mysql_fetch_row($result1_new); 
	$row2_new=mysql_fetch_row($result2_new); 
	
	
	
	
	$new_tm=mktime(date("H",$current_time),0,0,date("m",$current_time),date("d",$current_time)-1,date("y",$current_time));
	$new_tmend=mktime(0,0,0,date("m",$current_time),date("d",$current_time),date("y",$current_time));
	
	
	
	
	$newdata_imp_adv=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where time >$new_tm and  time <=$new_tmend");
	$newdata_imp_pub=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where time >$new_tm and  time <=$new_tmend");
	
	
	$newdata_clk=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_clkval=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_advref=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_pubref=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
	
	


///////////////////////////////////////////////////////////////////////////////////////	
	
$verify_clk_count=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
$verify_clk_count_main=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
   
   
$ver_clk_cnts=$verify_clk_count+$verify_clk_count_main;
if($ver_clk_cnts =="")
$ver_clk_cnts=0;
		
	     
	   
	   
$ver_clickvalue_main=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
$ver_clickvalue=$ver_clickvalue_main+$ver_clickvalue;
if($ver_clickvalue =="")
$ver_clickvalue=0;
	

	
$ver_advref_profit_main=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
$ver_advref_profit=$ver_advref_profit+$ver_advref_profit_main;
if($ver_advref_profit =="")
$ver_advref_profit=0;

 
	   
$ver_pubref_profit_main=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");

$ver_pubref_profit=$ver_pubref_profit+$ver_pubref_profit_main;
if($ver_pubref_profit =="")
$ver_pubref_profit=0;
//////////////////////////////////////////////////////////////////////////////////////////

	
	





$special_data_var=1;


}
else
{



 $result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_yearly_statistics where time='$end_time'");
  $result1=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_yearly_statistics where time='$end_time'");
  
  $result2=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from yearly_referral_statistics where time='$end_time'");
	$row=mysql_fetch_row($result);  
 	$row1=mysql_fetch_row($result1);  
	$row2=mysql_fetch_row($result2);  
	



///////////////////////////////////////////////////////////
	
	    $ver_clk_cnts=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clk_cnts =="")
	    $ver_clk_cnts=0;
		
		
		$ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clickvalue =="")
	    $ver_clickvalue=0;
	
		
		
		$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_advref_profit =="")
	    $ver_advref_profit=0;
		
		
		$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_pubref_profit =="")
	    $ver_pubref_profit=0;
		
	//////////////////////////////////////////////////////////////	




}


}	
	
	
	
	
	
	
	
		?>
 
   <!--<tr>
  <td colspan="8"><?php  // echo "select sum(impression),sum(clk_count),sum(money_spent) from advertiser_yearly_statistics where time='$end_time' "; ?>
  <br />
  <?php //echo "select sum(impression),sum(clk_count),sum(money_spent) from publisher_yearly_statistics where time='$end_time'"; ?>  </td>
  </tr>-->

   
  
  <tr  <?php if($year%2==1) echo 'bgcolor="#ededed"'; ?>>
  	 <td rowspan="4" ><?php echo date("Y",$start_time); ?>&nbsp;&nbsp;</td>
<td height="25">Advertiser statistics&nbsp;</td>
  <td height="25" ><?php echo numberFormat($row[0]+$row_new[0]+$newdata_imp_adv,0); ?>&nbsp;</td>
   <td height="25" ><?php echo numberFormat($row[1]+$row_new[1]+$newdata_clk,0); ?>&nbsp;</td>
    <td height="25" ><?php echo round($row[2]+$row_new[2]+$newdata_clkval,2); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row[3]+$row_new[3]+$newdata_advref); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row[4]+$row_new[4]+$newdata_pubref); ?>&nbsp;</td>
     <td>&nbsp;&nbsp;</td>
  </tr>
   
   
  <tr <?php if($year%2==1) echo 'bgcolor="#ededed"'; ?>>
    <td height="25" >Publisher statistics&nbsp;</td>
  <td height="25" ><?php echo numberFormat($row1[0]+$row1_new[0]+$newdata_imp_pub,0); ?>&nbsp;</td>
   <td height="25" ><?php echo numberFormat($row1[1]+$row1_new[1]+$newdata_clk,0); ?>&nbsp;</td>
    <td height="25" ><?php echo round($row1[2]+$row1_new[2]+$newdata_clkval,2); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row1[3]+$row1_new[3]+$newdata_advref); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row1[4]+$row1_new[4]+$newdata_pubref); ?>&nbsp;</td>
     <td>&nbsp;</td>
  </tr>
   
   
        <tr  <?php if($year%2==1) echo 'bgcolor="#ededed"'; ?>>
          <td height="25" >Referral statistics </td>
          <td height="25" >N/A</td>
          <td height="25" >N/A</td>
          <td height="25" >N/A</td>
          <td ><?php echo numberFormat($row2[0]+$row2_new[0]+$newdata_advref); ?>&nbsp;</td>
          <td ><?php echo numberFormat($row2[1]+$row2_new[1]+$newdata_pubref); ?>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr <?php if($year%2==1) echo 'bgcolor="#ededed"'; ?>>
	 <td height="25" >Raw data&nbsp; </td>
  <td height="25" ><?php 
  //if($last_clearance<=$start_time)
 // 	{
  $imp=$mysql->echo_one("select sum(cnt) from advertiser_impression_daily where time<='$end_time' and time>'$start_time'"); 
  if($imp=="")
  	echo "0";
else
	echo $imp;
	//}
//else
	//echo "Data cleared";
  ?>
  &nbsp;</td>
   <td height="25" ><?php  
 
	   /*$clk=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'");
		if($clk=="")
		echo "0";
	else
		echo $clk;*/
		
		
		  echo $ver_clk_cnts;  
		
		
	

	
    ?>&nbsp;</td>
    <td height="25" ><?php  
	
	//echo round($mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"),2); 
	echo round($ver_clickvalue,2);
	
	?>&nbsp;</td>
	 <td ><?php  
 
	//echo round($mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"),3); 
	echo numberFormat($ver_advref_profit);
		
	?>&nbsp;</td>
	 <td ><?php  

	//echo round($mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"),3); 
	echo numberFormat($ver_pubref_profit);
	
	?>&nbsp;</td>
     <td><?php	 
  if($last_clearance<$end_time)
	{ 
	
if($special_data_var == 0)
{	
?><a href="clear-statistics.php?tab=55&end_time=<?php echo $end_time; ?>&s_time=<?php echo $start_time;?>">Clear data</a>
 <?php
}
 
	 }
	 else
		echo "Data cleared till ".dateFormat($last_clearance);
	 ?></td>
  </tr>
    <?php
    
  $i++;
	$year++;
	
}
}
 ?>
    <!-- //////////////////////// -->
    <tr bgcolor="#666666"><td height="25"  colspan="8" class="style1" ><strong><font size="2">Monthly Statistics </font></strong>&nbsp;</td>
    </tr>
  <!-- //////////////////////// -->
  
    <tr bgcolor="#cccccc">
 	<td height="25"  ><strong>Time</strong>&nbsp;</td>
 <td height="25" ><strong>From</strong>&nbsp;</td>
    <td height="25" ><strong>Impressions</strong></td>
	<td height="25" ><strong>Clicks</strong></td>
	<td width="11%" height="25" bgcolor="#CCCCCC" ><strong>Click value </strong></td>
	<td ><strong>Adv ref profit </strong></td>
	<td ><strong>Pub ref profit</strong></td>
    <td ><strong>Action</strong></td>
  </tr>
  <?php
  $current_time=time();
$current_month=date("m",$current_time);


$day_date=date("j",$current_time);
$month=0;
 
while($month<$current_month-1)
  	{
	
	$ver_clk_cnts=0;
	$ver_clickvalue=0;
	$ver_advref_profit=0;
	$ver_pubref_profit=0;
	
	
	
	
$special_data_var=0;	
	
	$result="";
		$result1="";
		$result2="";	
		$row=0;
		$row1=0;
		$row2=0;
		
    $newdata_imp_adv=0;
	$newdata_imp_pub=0;
	$newdata_clk=0;
	$newdata_clkval=0;
	$newdata_advref=0;
	$newdata_pubref=0;	
	
	
	

	$start_time=mktime(0,0,0,1+$month,1,date("Y",$current_time));
    $end_time=mktime(0,0,0,2+$month,1,date("Y",$current_time));
	
	if($month != $current_month-2)
	{
	
	
	 $result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_monthly_statistics where time='$end_time'  ");
	 $result1=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_monthly_statistics where time='$end_time'  ");	
	$result2=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from monthly_referral_statistics where time='$end_time'");
	$row=mysql_fetch_row($result);  
 	$row1=mysql_fetch_row($result1); 
	$row2=mysql_fetch_row($result2); 
	
	
	
	//////////////////////////////////////////////
	
	    $ver_clk_cnts=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clk_cnts =="")
	    $ver_clk_cnts=0;
		
		
		$ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clickvalue =="")
	    $ver_clickvalue=0;
	
		
		
		$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_advref_profit =="")
	    $ver_advref_profit=0;
		
		
		$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_pubref_profit =="")
	    $ver_pubref_profit=0;
		
	/////////////////////////////////////////////////////
	
	
	}
	else
	{    
	if($day_date ==1)
	{

	
	$new_tm_data_month=mktime(0,0,0,date("m",$current_time)-1,1,date("Y",$current_time));
	
	 $result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_daily_statistics where time>'$new_tm_data_month'  ");
	 $result1=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_daily_statistics where time>'$new_tm_data_month'  ");	
	$result2=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where time>'$new_tm_data_month'");
	$row=mysql_fetch_row($result);  
 	$row1=mysql_fetch_row($result1); 
	$row2=mysql_fetch_row($result2); 
	
	
	
	
	$new_tm=mktime(date("H",$current_time),0,0,date("m",$current_time),date("d",$current_time)-1,date("y",$current_time));
	$new_tmend=mktime(0,0,0,date("m",$current_time),date("d",$current_time),date("y",$current_time));
	
	
	
	
	$newdata_imp_adv=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where time >$new_tm and  time <=$new_tmend");
	$newdata_imp_pub=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where time >$new_tm and  time <=$new_tmend");
	
	
	$newdata_clk=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_clkval=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_advref=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_pubref=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
	
	
	
///////////////////////////////////////////////////////////////////////////////////////	
	
$verify_clk_count=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
$verify_clk_count_main=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
   
   
$ver_clk_cnts=$verify_clk_count+$verify_clk_count_main;
if($ver_clk_cnts =="")
$ver_clk_cnts=0;
		
	     
	   
	   
$ver_clickvalue_main=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
$ver_clickvalue=$ver_clickvalue_main+$ver_clickvalue;
if($ver_clickvalue =="")
$ver_clickvalue=0;
	

	
$ver_advref_profit_main=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
$ver_advref_profit=$ver_advref_profit+$ver_advref_profit_main;
if($ver_advref_profit =="")
$ver_advref_profit=0;

 
	   
$ver_pubref_profit_main=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");

$ver_pubref_profit=$ver_pubref_profit+$ver_pubref_profit_main;
if($ver_pubref_profit =="")
$ver_pubref_profit=0;
//////////////////////////////////////////////////////////////////////////////////////////

	
	
	
	
	
	
	
	
	
	
	
	
	
	//include year=1,month=1 logic
	
	$special_data_var=1;
	
	}
	else
	{
	
	
	
	 $result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_monthly_statistics where time='$end_time'  ");
	 $result1=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_monthly_statistics where time='$end_time'  ");	
	$result2=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from monthly_referral_statistics where time='$end_time'");
	$row=mysql_fetch_row($result);  
 	$row1=mysql_fetch_row($result1); 
	$row2=mysql_fetch_row($result2); 
	
	
	
	
	
	///////////////////////////////////////////////////////////
	
	    $ver_clk_cnts=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clk_cnts =="")
	    $ver_clk_cnts=0;
		
		
		$ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clickvalue =="")
	    $ver_clickvalue=0;
	
		
		
		$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_advref_profit =="")
	    $ver_advref_profit=0;
		
		
		$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_pubref_profit =="")
	    $ver_pubref_profit=0;
		
	//////////////////////////////////////////////////////////////	
	
	}
	
	
	
	
	
	
	
	
	
	}
   ?>
   
     
  <tr <?php if($month%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
  	 <td rowspan="4" ><?php echo dateFormat($start_time,"%b %Y"); ?>&nbsp;</td>
	 <!--<td rowspan="4" ><?php //echo date("m/d/Y",$start_time); ?>&nbsp;</td>-->
	 
<td height="20" >Advertiser statistics&nbsp;</td>
  <td height="20" ><?php echo numberFormat($row[0]+$newdata_imp_adv,0); ?>&nbsp;</td>
   <td height="20" ><?php echo numberFormat($row[1]+$newdata_clk,0); ?>&nbsp;</td>
    <td height="20" ><?php echo round($row[2]+$newdata_clkval,2); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row[3]+$newdata_advref); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row[4]+$newdata_pubref); ?>&nbsp;</td>
     <td>&nbsp;</td>
  </tr>
  <tr <?php if($month%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
    <td height="20" >Publisher statistics&nbsp;</td>
  <td height="20" ><?php echo numberFormat($row1[0]+$newdata_imp_pub,0); ?>&nbsp;</td>
   <td height="20" ><?php echo numberFormat($row1[1]+$newdata_clk,0); ?>&nbsp;</td>
    <td height="20" ><?php echo round($row1[2]+$newdata_clkval,2); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row1[3]+$newdata_advref); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row1[4]+$newdata_pubref); ?>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
   
   
        <tr <?php if($month%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
          <td height="20" >Referral statistics </td>
          <td height="20" >N/A</td>
          <td height="20" >N/A</td>
          <td height="20" >N/A</td>
          <td ><?php echo numberFormat($row2[0]+$newdata_advref); ?>&nbsp;</td>
          <td ><?php echo numberFormat($row2[1]+$newdata_pubref); ?>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr <?php if($month%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
	 <td height="20" >Raw data &nbsp;</td>
  <td height="20" ><?php 
 // if($last_clearance<=$start_time)
  	//{
  echo $mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where time<='$end_time' and time>'$start_time'"); 
//  }
//  else
	//echo "Data cleared";
  ?>&nbsp;</td>
   <td height="20" ><?php  

	//   echo $mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
	   
	    echo $ver_clk_cnts;  
	   


	
   
   ?>&nbsp;</td>
    <td height="20" ><?php 
 
		//echo round($mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"),2); 
echo round($ver_clickvalue,2);
		
	?>&nbsp;</td>
	 <td ><?php  

//	echo numberFormat($mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'")); 
	echo numberFormat($ver_advref_profit);
	
	?>&nbsp;</td>
	 <td ><?php  
 
	//echo numberFormat($mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'")); 
	echo numberFormat($ver_pubref_profit);
	
	?>&nbsp;</td>
	<td><?php	 
  if($last_clearance<$end_time)
	{ 
	
	if($special_data_var==0)
	{
?>
	<a href="clear-statistics.php?tab=55&end_time=<?php echo $end_time; ?>&s_time=<?php echo $start_time;?>">Clear data</a>
	<?php } 
	
	}
	 else
	echo "Data cleared till ".dateFormat($last_clearance);
	?>	</td>
  </tr>
    <?php
    
	$month++;
	
}
 ?>
     <!-- //////////////////////// -->
	    <tr bgcolor="#666666"><td height="25"  colspan="8" class="style1"><strong><font size="2">Daily Statistics </font></strong>&nbsp;</td>
	    </tr>
  <!-- //////////////////////// -->
  
    <tr bgcolor="#CCCCCC">
 	<td height="25" ><strong>Time</strong>&nbsp;</td>
 <td height="25" ><strong>From</strong>&nbsp;</td>
    <td height="25" bgcolor="#cccccc" ><strong>Impressions</strong></td>
    <td height="25" bgcolor="#cccccc" ><strong>Clicks</strong></td>
    <td height="25" ><strong>Click value </strong></td>
    <td ><strong>Adv ref profit </strong></td>
	<td ><strong>Pub ref profit</strong></td>
    <td ><strong>Action</strong></td>
  </tr>
  <?php
    $current_time=time();
  $day_val=0;
  $current_month=date("m",$current_time);
  $current_day=date("d",$current_time);

while($day_val<$current_day)    //while($day_val<$current_day-1)
			{
			
	$ver_clk_cnts=0;
	$ver_clickvalue=0;
	$ver_advref_profit=0;
	$ver_pubref_profit=0;		
			
			
		$special_data_var=0;
		
		$result="";
		$result1="";
		$result2="";	
		
		$row=0;
		$row1=0;
		$row2=0;
			
    $newdata_imp_adv=0;
	$newdata_imp_pub=0;
	$newdata_clk=0;
	$newdata_clkval=0;
	$newdata_advref=0;
	$newdata_pubref=0;				
			
			
			
		 $start_time=mktime(0,0,0,$current_month,1+$day_val,date("Y",$current_time));
		$end_time=mktime(0,0,0,$current_month,2+$day_val,date("Y",$current_time));
		
		
		if($day_val != $current_day-1)
		{
		
    	if($day_val == $current_day-2)
		{
	
	  $result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_daily_statistics where time='$end_time'  ");
	 $result1=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_daily_statistics where time='$end_time'  ");	
	$result2=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where time='$end_time'");
	$row=mysql_fetch_row($result);  
 	$row1=mysql_fetch_row($result1); 
	$row2=mysql_fetch_row($result2);  
	
	
	$new_tm=mktime(date("H",$current_time),0,0,date("m",$current_time),date("d",$current_time)-1,date("y",$current_time));
	$new_tmend=mktime(0,0,0,date("m",$current_time),date("d",$current_time),date("y",$current_time));
	
	
	
	$newdata_imp_adv=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where time >$new_tm and  time <=$new_tmend");
	$newdata_imp_pub=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where time >$new_tm and  time <=$new_tmend");
	
	
	$newdata_clk=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_clkval=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_advref=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	$newdata_pubref=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
	   
	   $special_data_var=1;
	   
	   
	   
$verify_clk_count=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
$verify_clk_count_main=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
   
   
$ver_clk_cnts=$verify_clk_count+$verify_clk_count_main;
if($ver_clk_cnts =="")
$ver_clk_cnts=0;
		
	     
	   
	   
$ver_clickvalue_main=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
$ver_clickvalue=$ver_clickvalue_main+$ver_clickvalue;
if($ver_clickvalue =="")
$ver_clickvalue=0;
	

	
$ver_advref_profit_main=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");
	
$ver_advref_profit=$ver_advref_profit+$ver_advref_profit_main;
if($ver_advref_profit =="")
$ver_advref_profit=0;

 
	   
$ver_pubref_profit_main=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <=$new_tmend");

$ver_pubref_profit=$ver_pubref_profit+$ver_pubref_profit_main;
if($ver_pubref_profit =="")
$ver_pubref_profit=0;


	

	
	   
	   
	   
	   
	    }
		else
		{
		
		
		  $result=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from advertiser_daily_statistics where time='$end_time'  ");
	 $result1=mysql_query("select COALESCE(sum(impression),0),COALESCE(sum(clk_count),0),COALESCE(sum(money_spent),0),COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from publisher_daily_statistics where time='$end_time'  ");	
	$result2=mysql_query("select COALESCE(sum(adv_ref_profit),0),COALESCE(sum(pub_ref_profit),0) from daily_referral_statistics where time='$end_time'");
	$row=mysql_fetch_row($result);  
 	$row1=mysql_fetch_row($result1); 
	$row2=mysql_fetch_row($result2);  
		
		
		
		
		
		$ver_clk_cnts=$mysql->echo_one("select count(*) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clk_cnts =="")
	    $ver_clk_cnts=0;
		
		
		$ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_clickvalue =="")
	    $ver_clickvalue=0;
	
		
		
		$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_advref_profit =="")
	    $ver_advref_profit=0;
		
		
		$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_clicks where time<='$end_time' and time>'$start_time'"); 
		if($ver_pubref_profit =="")
	    $ver_pubref_profit=0;
		
		
		
		}
	
	    }
		else
		{
		
		
		
		
		
	$new_tm=mktime(0,0,0,date("m",$current_time),date("d",$current_time),date("y",$current_time));
	$new_tmend=mktime(date("H",$current_time)+1,0,0,date("m",$current_time),date("d",$current_time),date("y",$current_time));
		
		
	$newdata_imp_adv=$mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where time >$new_tm and  time <$new_tmend");
	$newdata_imp_pub=$mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where time >$new_tm and  time <$new_tmend");
	
	
	$newdata_clk=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where time >$new_tm and  time <$new_tmend");
	
	
	
	
	
	
	
	$newdata_clkval=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time >$new_tm and  time <$new_tmend");
	$newdata_advref=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <$new_tmend");
	$newdata_pubref=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <$new_tmend");
		
		
		 $special_data_var=1;
		 
		 
		 
		 
		 
		 
		 
	$ver_clk_cnts=$mysql->echo_one("select COALESCE(count(id),0) from ppc_daily_clicks where time >$new_tm and  time <$new_tmend");
    if($ver_clk_cnts =="")
	$ver_clk_cnts=0;
   
   
    $ver_clickvalue=$mysql->echo_one("select COALESCE(sum(clickvalue),0) from ppc_daily_clicks where time >$new_tm and  time <$new_tmend");
	if($ver_clickvalue =="")
	$ver_clickvalue=0;
	
	
	
	
	$ver_advref_profit=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <$new_tmend");
	if($ver_advref_profit =="")
	$ver_advref_profit=0;
		
		
	$ver_pubref_profit=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from ppc_daily_clicks where time >$new_tm and  time <$new_tmend");	
	if($ver_pubref_profit =="")
	$ver_pubref_profit=0;	
		
		
		
		
		}
	
	
   ?>
   
  <tr <?php if($day_val%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
  
  
 	 <td rowspan="4" ><?php echo dateFormat($start_time,"%d %b %Y");; ?>&nbsp;</td>
	 
	<!-- <td rowspan="4" ><?php echo date("d/m/Y",$start_time); ?>&nbsp;</td>-->
	 
	 
 <td height="25" >Advertiser statistics&nbsp;</td>
  <td height="25" ><?php echo numberFormat($row[0]+$newdata_imp_adv,0); ?>&nbsp;</td>
   <td height="25" ><?php echo numberFormat($row[1]+$newdata_clk,0); ?>&nbsp;</td>
    <td height="25" ><?php echo round($row[2]+$newdata_clkval,2); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row[3]+$newdata_advref); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row[4]+$newdata_pubref); ?>&nbsp;</td>
     <td>&nbsp;</td>
  </tr>

   <tr <?php if($day_val%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
	 <td height="25" >Publisher statistics&nbsp;</td>
  <td height="25" ><?php echo numberFormat($row1[0]+$newdata_imp_pub,0); ?>&nbsp;</td>
   <td height="25" ><?php echo numberFormat($row1[1]+$newdata_clk,0); ?>&nbsp;</td>
    <td height="25" ><?php echo round($row1[2]+$newdata_clkval,2); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row1[3]+$newdata_advref); ?>&nbsp;</td>
	 <td ><?php echo numberFormat($row1[4]+$newdata_pubref); ?>&nbsp;</td>
      <td>&nbsp;</td>
  </tr>
   
   
       <tr <?php if($day_val%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
         <td height="25" >Referral statistics </td>
         <td height="25" >N/A</td>
         <td height="25" >N/A</td>
         <td height="25" >N/A</td>
         <td ><?php echo numberFormat($row2[0]+$newdata_advref); ?>&nbsp;</td>
         <td ><?php echo numberFormat($row2[1]+$newdata_pubref); ?>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
       <tr <?php if($day_val%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
	 <td height="25" >Raw data &nbsp;</td>
  <td height="25" ><?php 
  
	  echo $mysql->echo_one("select COALESCE(sum(cnt),0) from advertiser_impression_daily where time<='$end_time' and time>'$start_time'"); 
	  


  ?>&nbsp;</td>
   <td height="25" ><?php  
 
  
  
 
   
   
   echo $ver_clk_cnts;
   
   
   

   ?>&nbsp;</td>
    <td height="25" ><?php  

	
	
	
	
	echo round($ver_clickvalue,2);
	
	

	?>&nbsp;</td>
	 <td ><?php  

	
	
	
	echo numberFormat($ver_advref_profit);
	
	
	
	
	?>&nbsp;</td>
	 <td ><?php  

	
	
	echo numberFormat($ver_pubref_profit);
	
	
	
	?>&nbsp;</td>
	  <td><?php	 
  	$day_val++;
	if($day_val<$current_day-1)
	{
		  if($last_clearance<$end_time )
			{ 
			
		if($special_data_var==0)
		{
			
		?><a href="clear-statistics.php?tab=55&end_time=<?php echo $end_time; ?>&s_time=<?php echo $start_time;?>">Clear data</a>
		<?php } 
		}
		else
				echo "Data cleared till ".dateFormat($last_clearance);
	}
	else
		echo "-";
?>
&nbsp;</td>
  </tr>
    <?php
   	}
    ?>
    <!-- //////////////////////// -->
</table>

<p>&nbsp;</p>
<?php include("admin.footer.inc.php"); ?>
