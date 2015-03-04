<?php
/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/





/*echo "<table width="200" border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td>id</td>
	<td>LastAccTime</td>
    <td>ageFactor</td>
    <td>clickBid</td>
	<td>clicks</td>
	<td>impressions</td>
    <td>clickThruRate</td>
	 <td>weightage</td>
  </tr>";*/


include_once("config.inc.php");
$result1=mysql_query("select last_id from statistics_updation where task='weightage_updation'");
$row1=mysql_fetch_row($result1);

$result=mysql_query("select id,maxcv,time,aid,uid from ppc_keywords where status=1 and id>$row1[0] order by id  limit 0,1000");

$sum=0;
  $lastid=0;
while($row=mysql_fetch_row($result))
{
	$lastAccTime=$row[2];
	$currTime=time();
	$noOfDaysSinceLastAccess=($currTime-$lastAccTime)/(24*60*60);
//$noOfDaysSinceLastAccess=($currTime-$lastAccTime)/(60*60);
	$ageFactor=0;
	if($ad_ageing_factor>0)
		$ageFactor=round($noOfDaysSinceLastAccess/$ad_ageing_factor,7);	
	$clickBid=$row[1];
	$numImpressions=$mysql->echo_one("select COALESCE(sum(impression),0) from advertiser_daily_statistics where uid='$row[4]' and aid='$row[3]' and kid=$row[0]");
	$numClicks=$mysql->echo_one("select COALESCE(sum(clk_count),0) from advertiser_daily_statistics where uid='$row[4]' and aid='$row[3]' and kid=$row[0]");
	$clickThruRate=0;
	if($numImpressions!=0)
		$clickThruRate=$numClicks/$numImpressions;
	$weightage=round($clickThruRate+$clickBid+$ageFactor,2);
			/* echo "<tr>
    <td>$row[0]</td>
	<td>$lastAccTime</td>
    <td>$ageFactor</td>
    <td>$clickBid</td>
		<td>$numClicks</td>
	<td>$numImpressions</td
    <td>$clickThruRate</td>
	<td>$weightage</td>
  	</tr>";*/
	mysql_query("update ppc_keywords set weightage=$weightage where id=$row[0]");
	//mysql_query("update ppc_keywords set weightage=$weightage,time='2' where id=$row[0]");
	$lastid=$row[0];
	$sum=$sum+1;
}
//echo $sum.$lastid;
//exit;
         if($sum<1000)
           $lastid=0;
          $time=time(); 
     mysql_query("update statistics_updation set e_time=$time ,status=2 , last_id=$lastid where task='weightage_updation' ");      
      
//echo "</table>";


if($ad_rotation == 0)
$setting_value_string="order by time ASC";
else if($ad_rotation == 1)
$setting_value_string="order by maxcv desc,time ASC";
else if($ad_rotation == 2 || $ad_rotation == 3)
$setting_value_string="order by weightage desc,time ASC";


mysql_query("create or replace view view_keywords as select * from ppc_keywords ".$setting_value_string);
echo mysql_error();
?>