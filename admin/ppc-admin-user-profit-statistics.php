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




?><?php include("admin.header.inc.php"); 
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
	$end_time=mktime(0,0,0,date("m",time()),date("y",time()));
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
//adding ststistics table 14/08/2009
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
//adding ststistics table 14/08/2009

	$pageno=1;
if(isset($_GET['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
$result=mysql_query("select uid,username from ppc_users where status=1 order by uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from ppc_users where status=1");

?>
<style type="text/css">
<!--
.style1 {
	color: #006600;
	font-weight: bold;
}
.style1 {color: white;
	font-weight: bold;}
-->
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/advertisers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Advertiser Statistics</td>
  </tr>
</table>
<br />

  
  

<form name="form1" method="get" action="ppc-admin-user-profit-statistics.php">
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


  

 
 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">

		
	  <tr>
    <td width="50%"   ><?php if($total>=1) {?>   Advertisers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;   </td>
    <td width="50%"   align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-admin-user-profit-statistics.php?statistics=$show"); ?></td>
  </tr>


  <tr>
    <td colspan="2">
	

 	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
     <tr class="headrow">

       <td style="padding-left:3px;"><strong>Username</strong></td>
       <td><strong> Clicks Received </strong></td>
       <td><strong>Click Value(
         <?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </strong></td>
       <td width="16%"><strong>Publisher Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </strong></td>
       <td width="17%"><strong>Referral share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </strong></td>
       <td width="15%"><strong>Your Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</strong></td>
      </tr>
	  <?php
		$i=1;
	  while($row=mysql_fetch_row($result))
	   { ?>
	
      <tr  <?php if($i%2==1){?>class="specialrow" <?php }?>>

        <td  ><a href="view_profile.php?id=<?php echo  $row[0];?>"><?php echo $row[1]; ?></a></td>
        <td  ><?php echo round(getAdClicks(0,$time,$mysql,$row[0],$flag_time),2); ?></td>
        <td >&nbsp;<?php 	$ret=getAdMoneySpent(0,$time,$mysql,$row[0],$flag_time);
		if( $ret=="")
			$ret=0;
		$ret=round($ret,2);
		echo  numberFormat($ret);
	?></td>
        <td  >&nbsp;          <?php 	$ret1=getPublisherprofit(0,$time,$mysql,$row[0],$flag_time);
		if( $ret1=="")
			$ret1=0;
		$ret1=round($ret1,2);
		echo  numberFormat($ret1);
	?></td>
        <td  >&nbsp;           <?php 	$ret2=getPublisherrefprofit(0,$time,$mysql,$row[0],$flag_time);
		if( $ret2=="")
			$ret2=0;
		$ret2=round($ret2,2);
		$adv_ref_sh=getAdvertiserrefprofit(0,$time,$mysql,$row[0],$flag_time);
		if( $adv_ref_sh=="")
		$adv_ref_sh=0;
		$adv_ref_sh=round($adv_ref_sh,2);	
		$tot_ref=$ret2+$adv_ref_sh;
		echo  numberFormat($tot_ref);
	?></td>
        <td  >&nbsp; <?php echo  numberFormat($ret-($ret1+$tot_ref));?></td>
      </tr>

	<?php $i++; } ?>	
	  </table>
	  </td>
	  </tr>
	  
	  <tr>
    <td   ><?php if($total>=1) {?>   Advertisers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;   </td>
    <td   align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-admin-user-profit-statistics.php?statistics=$show"); ?></td>
  </tr>






</table>


  <?php 
	if(mysql_num_rows($result)==0)
	{
	echo '  No Records Found ';
	}

	?>


<?php include("admin.footer.inc.php"); ?>



