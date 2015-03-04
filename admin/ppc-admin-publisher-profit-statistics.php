<?php
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
include("../publisher_statistics_utils.php");


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

//adding ststistics table 18/08/2009
if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	 else if($flag_time==2)
	 {
	 	$table_name="publisher_yearly_statistics";
	 }
	 else
	 {
		$table_name="publisher_monthly_statistics";
	 }


	$pageno=1;
if(isset($_GET['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
$result=mysql_query("select uid,username from ppc_publishers where status=1 order by uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from ppc_publishers where status=1");
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
    <td height="53" colspan="4"  align="left"><?php include "submenus/publishers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Publisher Statistics</td>
  </tr>
</table>
<br />

  
  <form name="form1" method="get" action="ppc-admin-publisher-profit-statistics.php">
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
	if(mysql_num_rows($result)==0)
	{
	echo 'No Records Found  ';
	}
	else
	{
	
	?>
 
 
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"  ><?php if($total>=1) {?>   Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;   </td>
    <td width="50%"   align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-admin-publisher-profit-statistics.php?statistics=$show"); ?></td>
  </tr>
	</table>
	
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">     

 <tr   class="headrow">
        <td width="24%" height="30" style="padding-left:3px;">Publisher </td>
        <td> Clicks Received  </td>
        <td width="13%">Click Profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
        <td width="16%">Advertiser Referral Profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)  </td>
        <td width="16%">Publisher Referral Profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)  </td>
        <td width="11%">Net Profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)  </td>
  </tr>
	  <?php 
	  $i=1;
	  while($row=mysql_fetch_row($result)) { ?>
	
	
      <tr <?php if($i%2==1) { ?>class="specialrow" <?php }?>>
        <td   ><a href="view_profile_publishers.php?id=<?php echo $row[0]; ?>"><?php echo $row[1]; ?></a></td>
        <td  ><?php $aa=getPubAdClicks($time,$mysql,$row[0],$flag_time); echo numberFormat($aa,0);//round($mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$row[0]' and time>'$time'"),2); 
		?></td>
        <td  ><?php 	$ret=round(getPubPublisherprofit($time,$mysql,$row[0],$flag_time),2); //$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$row[0]' and time>'$time'");
	if( $ret=="")
	$ret=0;
$ret=round($ret,2);
	echo  numberFormat($ret);
	?></td>
        <td  >          <?php $ar_sh=round(getPubAdvertiserrefprofit($time,$mysql,$row[0],$flag_time),2); //$mysql->echo_one("select SUM(adv_ref_profit) from $table_name where time>$time and adv_rid='$row[0]'");
	if( $ar_sh=="")
	$ar_sh=0;
$ar_sh=round($ar_sh,2);
echo  numberFormat($ar_sh);?></td>
        <td  > <?php 	$pr_sh=round(getPubPublisherrefprofit($time,$mysql,$row[0],$flag_time),2); //$mysql->echo_one("select SUM( pub_ref_profit) from $table_name where pub_rid='$row[0]' and time>$time");
	if( $pr_sh=="")
	$pr_sh=0;
$pr_sh=round($pr_sh,2);
	echo  numberFormat($pr_sh);
	?></td>
        <td  > <?php echo numberFormat($ret+$ar_sh+$pr_sh);?></td>
        </tr>
		

	<?php  $i++; } ?>	
</table>


	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td width="50%"   ><?php if($total>=1) {?>   Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp; </td>
    <td width="50%"   align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-admin-publisher-profit-statistics.php?statistics=$show"); ?></td>
    </tr>

	</table>	
	
  <?php  } ?>	
  
  <br />

<?php include("admin.footer.inc.php"); ?>



