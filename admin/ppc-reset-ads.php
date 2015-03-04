<?php
include_once("config.inc.php");

loadsettings("ppc_new");
$budget_period=$GLOBALS['budget_period'];
$result=mysql_query("select e_time from statistics_updation where task='reset_ads'");
$row=mysql_fetch_row($result);
$e_time=$row[0];


$currentmonth=mktime( 0,0,0, date("m"),1,date("y"));
$nextmonth=mktime( 0,0,0, date("m")+1,1,date("y"));

$currentday=mktime( 0,0,0, date("m"),date("d"),date("y"));
$nextday=mktime( 0,0,0, date("m"),date("d")+1,date("y"));

//$currentday=date("j",$currenttime);
if ( ( !( ($currentmonth<=$e_time)&&($e_time<$nextmonth) ) )&&($budget_period==1) )
{
	//TO DO statitics updation

	mysql_query("update ppc_ads set amountused='0';");
	mysql_query("update statistics_updation set e_time='$currentmonth' where task='reset_ads';");
}

else if ( ( !( ($currentday<=$e_time)&& ($e_time<$nextday) ) )&&($budget_period==2) )
{
	//TO DO statitics updation
	mysql_query("update ppc_ads set amountused='0';");
	mysql_query("update statistics_updation set e_time='$currentday' where task='reset_ads';");
}
?>