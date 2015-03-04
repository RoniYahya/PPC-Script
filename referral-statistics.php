<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
includeClass("Template");
includeClass("User");
includeClass("Form");
$template=new Template();
$template->loadTemplate("publisher-templates/referral-statistics.tpl.html");
//include_once("messages.".$client_language.".inc.php");
$user=new User("ppc_publishers");
include("publisher_statistics_utils.php");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
if($referral_system==0)
{
header("Location:publisher-show-message.php?id=1037");
exit(0);
}






$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
if(!isset($_GET['statistics']))
{	$show="day";	}
else
{ $show=$_GET['statistics']; }

/*
if($show=="day")
{
$showmessage="Yesterday";
$time=mktime(0,0,0,date("m",time()),date("d",time())-1,date("y",time()));
}
else if($show=="week")
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-14,date("Y",time()));//$end_time-(14*24*60*60);
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-30,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
}
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",time())+1-12,1,date("Y",time()));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",time()));
}
else if($show=="all")
{
	$flag_time=2;
	$showmessage="All Time";
	$time=0;

}
else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-14,date("Y",time()));//$end_time-(14*24*60*60);

}


*/

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


$referral_table="";
if($flag_time==0)
	  {
$reg_start_time=mktime(0,0,0,date("m",$time),date("d",$time)-1,date("Y",$time));
	  }
	 else if($flag_time==1)
	 {
$reg_start_time=mktime(0,0,0,date("m",$time)-1,1,date("Y",$time));
	 }
	 else if($flag_time==2)
	 {
$reg_start_time=mktime(0,0,0,1,1,date("Y",$time)-1);
	 }
	 else 
	 {
$reg_start_time=$time;
	 }

//echo  date("Y m d  h i s a",$time);




if($show=="day")
{
$reg_end_time=time();
$tablename="referral_daily_visits";

}
else
{
$reg_end_time=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
$tablename="referral_visits";
}




$uid=$user->getUserID();
$referral_result= mysql_query("select COALESCE(sum(unique_hits),0), COALESCE(sum(repeated_hits),0) from $tablename where rid=$uid and time>=$time");
$referral_row=mysql_fetch_row($referral_result);
$click=$referral_row[0]; 
//if($click=="")
	//$click=0;
$uclick=$referral_row[1];  
//if($uclick=="")
	//$uclick=0;
$psigned=$mysql->total("ppc_publishers","rid=$uid and regtime>$reg_start_time and regtime<=$reg_end_time"); 
$asigned=$mysql->total("ppc_users","rid=$uid and regtime>$reg_start_time and regtime<=$reg_end_time"); 





$aearning=getAdvertiserReferralProfitOfPublisher($time,$mysql,$uid,$flag_time);
$pearning=getPublisherReferralProfitOfPublisher($time,$mysql,$uid,$flag_time);

$aearning=moneyFormat($aearning);
$pearning=moneyFormat($pearning);


$template->setValue("{SHOWSTATUS}",$showmessage);
$template->setValue("{CLICK}",numberFormat($click,0));
$template->setValue("{NET}",numberFormat($uclick,0));
$template->setValue("{PUB}",numberFormat($psigned,0));
$template->setValue("{ADV}",numberFormat($asigned,0));
$template->setValue("{AEARNING}",$aearning);
$template->setValue("{PEARNING}",$pearning);
//$template->setValue("{SHARE}",$r);
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));      
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$publisher_message[8977]);
//$template->setValue("{ENGINE_TITLE}",$engine_title);


$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding);     
eval('?>'.$template->getPage().'<?php ');
?>
