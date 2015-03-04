<?php
//print_r($_POST);
include("config.inc.php");
if(!isset($_COOKIE['inout_admin']))
{
	header("Location:index.php");
	exit(0);
}
include("../geo/geoip.inc");

			$gi = geoip_open("../geo/GeoIP.dat",GEOIP_STANDARD);

   
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	header("Location:index.php");
	exit(0);
}
include_once("admin.header.inc.php");


if(!isset($_REQUEST['statistics']))
{	
$show="day";
}
else
{
	$show=$_REQUEST['statistics'];
}
if(!isset($_REQUEST['status_clicks']))
{	
$clickbased="valid";
}
else
{
	$clickbased=$_REQUEST['status_clicks'];
}
 $adv_id=$_REQUEST['advertisers'];
 if(!isset($_REQUEST['advertisers']))
 {
 $adv_id=0;
 }
if($adv_id==0)
{	
$advertiserid="";
$advertiserid1="";
}
else
{ 
	$advertiserid="uid='$adv_id' and ";
	$advertiserid1="b.uid='$adv_id' and ";
 }
$pub_id=$_REQUEST['publishers'];
if(!isset($_REQUEST['publishers']))
 {
 $pub_id=-1;
 }
if($pub_id==-1)
{	
$publisherid="";
$publisherid1="";
}
else
{ 
	$publisherid=" and pid='$pub_id'";
	$publisherid1=" and a.pid='$pub_id'";
}
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
$flag_time=0;	
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="month")
{
$flag_time=0;	
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}



if($flag_time==-1)
{
	$table="ppc_daily_clicks";
}
else
{
	$table="ppc_clicks";
}
	


//echo $_REQUEST['status_clicks'];

$last_hour_end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));

$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
//echo "select id,clickvalue,ip,time,country from $table  where $advertiserid time>=$beg_time $publisherid order by time DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;
//if($_REQUEST['status_clicks']=="valid")
if($clickbased=="valid")
{
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));	

if($flag_time==0)
{
	//echo "select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from (select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from $table  where $advertiserid time>=$beg_time $publisherid UNION select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from ppc_daily_clicks  where $advertiserid time>=$spec_time_limits $publisherid )x order by `current_time` DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;
$result=mysql_query("select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from (select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from $table  where $advertiserid time>=$beg_time and time <='$last_hour_end_time'  $publisherid UNION select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from ppc_daily_clicks  where $advertiserid time>=$spec_time_limits and time <='$last_hour_end_time'  $publisherid )x order by `current_time` DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);


}
else
{
//echo "select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from $table  where $advertiserid time>=$beg_time $publisherid  order by time DESC";

$result=mysql_query("select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from $table  where $advertiserid time>=$beg_time  and time <='$last_hour_end_time' $publisherid  order by time DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
}


if($flag_time==0)
{
$total=$mysql->echo_one("SELECT count(id) FROM (SELECT * FROM $table WHERE $advertiserid time>=$beg_time  and time <='$last_hour_end_time' $publisherid UNION SELECT *  FROM ppc_daily_clicks WHERE $advertiserid time>=$spec_time_limits  and time <='$last_hour_end_time' $publisherid)x");

}
else
{
$total=$mysql->echo_one("select count(*) from $table  where $advertiserid time>=$beg_time and time <='$last_hour_end_time'  $publisherid");
}
}
else
{
	//echo "select a.id,b.clickvalue,a.ip,a.current_time,a.publisherfraudstatus,a.browser,a.platform,a.version,a.user_agent from ppc_fraud_clicks a join ppc_ads b on b.id=a.aid  where $advertiserid1 a.clicktime>=$beg_time $publisherid1  order by time DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;
$result=mysql_query("select a.id,a.ip,b.uid,a.pid,a.clicktime,a.publisherfraudstatus,a.browser,a.platform,a.version,a.user_agent from ppc_fraud_clicks a join ppc_ads b on b.id=a.aid  where $advertiserid1 a.clicktime>=$beg_time and a.clicktime <='$last_hour_end_time'  $publisherid1  order by a.clicktime DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from ppc_fraud_clicks a join ppc_ads b on b.id=a.aid  where $advertiserid1 a.clicktime>=$beg_time and a.clicktime <='$last_hour_end_time'  $publisherid1  order by a.clicktime");	
}
//if(mysql_num_rows($result)==0 && $pageno>1)
//	{
//		$pageno--;
//		$result=mysql_query("select uid,username,status from nesote_inoutscripts_users  order by uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
//
//	}


?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/fraud.php"; ?> </td>
  </tr>

  <tr>
   <td   colspan="4" scope="row" class="heading">Click Analysis</td>
  </tr>
</table>
<br />
<form name="form1" method="get" action="click-analysis.php">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
  <td colspan="5" class="style1">&nbsp;</td>
  </tr>

  <tr>
    <td width="47%" >Period</td
			  >
   	<td width="47%" ><select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?>>Today</option>
        <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 days</option>
        <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 days</option>
      </select>	  </td
			  >
   	<td width="25%">Type</td>
   	<td width="25%">
            <select  name="status_clicks" id="status_clicks">
			 <option value="valid"  <?php 
			  				  if($_REQUEST['status_clicks']=="valid")echo "selected";			  
			  ?>>Valid Clicks</option>
			<option value="fraud"  <?php 
			  				  if($_REQUEST['status_clicks']=="fraud")echo "selected";			  
			  ?>>Fraud Clicks</option>
			</select>			</td>
			<td>
			</td>
	</tr>
			<tr>
			  <td>Advertiser</td>
			<td><select  name="advertisers" id="advertisers">
			 <?php		  
			  
			  $ppcusers=mysql_query("select uid,username from ppc_users  where status='1' order by username");

while($row=mysql_fetch_row($ppcusers))
	{ ?>
		
		<option value="<?php echo $row[0]; ?>" <?php if($adv_id==$row[0]) { ?> selected="selected" <?php } ?> ><?php echo  $row[1]; ?></option>
		
	<?php }
	?>
			  
			  
			  <option value="0"  <?php 
			  				  if($adv_id=="0")echo "selected";			  
			  ?>>All</option>
			</select>			</td>
			<td>Publisher</td>
			<td><select  name="publishers" id="publishers">
			 <?php		  
			  
			  $ppcpub=mysql_query("select uid,username from ppc_publishers  where status='1' order by username");

while($publist=mysql_fetch_row($ppcpub))
	{ ?>
		
		<option value="<?php echo $publist[0]; ?>" <?php if($pub_id==$publist[0]) { ?> selected="selected" <?php } ?> ><?php echo  $publist[1]; ?></option>
		
	<?php }
	?>
			  
			  
			  <option value="0"  <?php 			  				  if($pub_id=="0")echo "selected";			  			  ?>>Admin</option>			  
			  <option value="-1"  <?php 			  				  if($pub_id=="-1")echo "selected";			  			  ?>>All</option>
			</select>			</td>
			<td width="28%">
            <input type="submit" name="Submit" value="Show Statistics">    </td>
  </tr>
</table>
</form><br />

  <?php

   if($total!=0)
  {?>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
   
    <td width="50%"   ><?php if($total>=1) {?> Showing Click Details <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br />
    <br/>    </td>
    <td width="50%"   align="right"><?php echo $paging->page($total,$perpagesize,"","click-analysis.php?statistics=$show&status_clicks=$clickbased&advertisers=$adv_id&publishers=$pub_id"); ?></td>
  </tr>
  
  </table>
  
  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="datatable">

  <?php if($_REQUEST['status_clicks']!="fraud")
	{ ?>
  <tr class="headrow">
  <td width="6%">No </td>
  <td width="7%">IP</td>
  <td width="10%">Country</td>
  <td width="13%">Click time</td>
  <td width="7%">Click value</td>
   <td width="10%">Browser (Version)</td>
   <td width="8%">Platform</td>
   <td width="11%">User agent</td>
    <td width="14%">Advertiser</td>
   <td width="14%">Publisher</td>
  </tr>
 
   <?php 
   }
   else 
   { ?>
  <tr class="headrow">
  <td width="6%">No </td>
  <td width="7%">IP</td>
  <td width="10%">Country</td>
  <td width="13%">Click time</td>
   <td width="7%">Type</td>
   <td width="10%">Browser (Version)</td>
   <td width="8%">Platform</td>
   <td width="11%">User agent</td> 
   <td width="14%">Advertiser</td>
   <td width="14%">Publisher</td>
  </tr>
   <?php } ?>
   

  <?php 
  if($pageno!=1)
  {
  $i=($pageno-1)*$perpagesize+1;
  }
  else
  $i=1;
 while($ans=mysql_fetch_row($result))
{ 
	if($clickbased=="valid")
	{
		$ips=$ans[2];
		$ctime=$ans[3];
		$advid1=$ans[5];
		$pubid1=$ans[6];
		$record = geoip_country_code_by_addr($gi, $ans[2]);
	}
	else
	{
		$ips=$ans[1];
		$ctime=$ans[4];
		$advid1=$ans[2];
		$pubid1=$ans[3];
		$record = geoip_country_code_by_addr($gi,$ans[1]);
	}
	?>
	
	 <?php if($_REQUEST['status_clicks']!="fraud")
	{ ?>
	<tr >
	<td ><?php echo $i; ?></td>
	<td ><?php echo $ips; ?></td>
	<td ><?php $country_name=$mysql->echo_one("select name from location_country where code='$record'");   echo  $country_name; ?></td>
	<td ><?php echo dateTimeFormat($ctime); ?></td>
	
	<td ><?php echo $ans[1]; ?></td>
	<td ><?php echo $ans[7]."(".$ans[9].")"; ?></td>
	<td ><?php echo $ans[8]; ?></td>	
	<td ><?php echo $ans[10]; ?></td>
	<td ><?php  $advname=$mysql->echo_one("select username from ppc_users where uid='$advid1'"); echo $advname; ?></td>
	<td ><?php if($pubid1==0){$pubname="Admin";} else{$pubname=$mysql->echo_one("select username from ppc_publishers where uid='$pubid1' ");} echo $pubname?></td>
	</tr>
	 
	<?php }else { ?>
	<tr >
	<td ><?php echo $i; ?></td>
	<td ><?php echo $ips; ?></td>
	<td ><?php $country_name=$mysql->echo_one("select name from location_country where code='$record'");   echo  $country_name; ?></td>
	<td ><?php echo dateTimeFormat($ctime); ?></td>
 
    <td ><?php if($ans[5]==1){ echo "Publisher Fraud"; }elseif($ans[5]==0) { echo "Repetitive Click"; }elseif($ans[5]==2){ echo "Invalid IP"; }elseif($ans[5]==3){ echo "Proxy Click"; }elseif($ans[5]==4){ echo "Bot Click"; }elseif($ans[5]==5){ echo "Invalid Geo"; }?></td> 
    
	<td ><?php echo $ans[6]."(".$ans[8].")"; ?></td>
	<td ><?php echo $ans[7]; ?></td>	
	<td ><?php echo $ans[9]; ?></td>
	<td ><?php  $advname=$mysql->echo_one("select username from ppc_users where uid='$advid1'"); echo $advname; ?></td>
	<td ><?php if($pubid1==0){$pubname="Admin";} else{$pubname=$mysql->echo_one("select username from ppc_publishers where uid='$pubid1' ");} echo $pubname?></td>
	</tr>
   
	<?php } ?>
	<?php
	$i++;
}
 ?>
 </table>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
   
    <td width="50%"   ><?php if($total>=1) {?> Showing Click Details <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br />
    <br/>    </td>
    <td width="50%"   align="right"><?php echo $paging->page($total,$perpagesize,"","click-analysis.php?statistics=$show&status_clicks=$clickbased&advertisers=$adv_id&publishers=$pub_id"); ?></td>
  </tr>
  </table>
 <?php

  }
  else
  {  ?>
  	<br />
No Records Found.<br />

  	<?php  } ?>

  <?php
   include_once("admin.footer.inc.php");
?>