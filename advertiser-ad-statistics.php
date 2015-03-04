<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");

$template=new Template();
$template->loadTemplate("ppc-templates/advertiser-ad-statistics.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

include("graphutils.inc.php");
include("advertiser_statistics_utils.php");

$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();

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
	//$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
	
$beg_time=$time;
}
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",time())+1-11,1,date("Y",time()));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",time()));
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


$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
}

$returnVal=plotAdvertiserGraphs($show,$flag_time,$beg_time,$end_time,$selected_colorcode,$uid);

$FC=$returnVal[0];
//$template->setValue("{FUSIONCHART1}",$FC->renderChart());

$FD=$returnVal[1];
//$template->setValue("{FUSIONCHART2}",$FD->renderChart());


$template->openLoop("STATISTICS",getTimeBasedAdvertiserStatistics($show,$flag_time,$beg_time,$end_time,$uid));
$template->setLoopField("{LOOP(STATISTICS)-ID}","id");
$template->setLoopField("{LOOP(STATISTICS)-IMP}","impressions");
$template->setLoopField("{LOOP(STATISTICS)-CLK}","clicks");
$template->setLoopField("{LOOP(STATISTICS)-MONEY}","clickvalue");
$template->closeLoop();



$click=getAdClicks(0,$time,$mysql,$uid,$flag_time);
$total_impressions=getAdImpressions(0,$time,$mysql,$uid,$flag_time);
if($total_impressions==0)
	{
	$ctr=0;
	}
else
	{
	$ctr=($click/$total_impressions) * 100;
	$ctr=numberFormat($ctr);
	}
$tot= getAdMoneySpent(0,$time,$mysql,$uid,$flag_time);

$template->setValue("{CLK}",numberFormat($click,0));
$template->setValue("{IMPR}",numberFormat($total_impressions,0));
$template->setValue("{CTR}",$ctr);
$template->setValue("{MONEY}",$tot);


$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));         
if($currency_format=="$$")
	{$template->setValue("{CURRENCY}",$system_currency);}
else
	{$template->setValue("{CURRENCY}",$currency_symbol); }


$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");



$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
//echo $template->getPage();die;
   
eval('?>'.$template->getPage().'<?php ');

?>