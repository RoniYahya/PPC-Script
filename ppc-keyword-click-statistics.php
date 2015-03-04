<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");
$template=new Template();
$template->loadTemplate("ppc-templates/ppc-keyword-click-statistics.tpl.html");

//if($ad_keyword_mode==2){ 
//header("Location:ppc-ad-click-statistics.php");
//exit(0);
//}
include("graphutils.inc.php");
 include("advertiser_statistics_utils.php");



$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$id=getSafePositiveInteger('id','g');

$uid=$user->getUserID();
if(!myAd($id,$uid,$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}

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


//////////////////wap

if(isset($_GET['wap']))
{
$wap_flag=$_GET['wap'];
}
else 
{
	$wap_flag=0;
}
phpsafe($wap_flag);



if($wap_flag==1)
{
	$wap_string='and wapstatus=1';
	$wap_name='Wap';
	
}

else
{
	$wap_string='and wapstatus=0';
	$wap_name='';
    
	
}
$template->setValue("{WAP_FLAG}",$wap_flag);
$template->setValue("{WAP_NAME}",$wap_name);

$emess=$template->checkAdvMsg(8957);
$emess1=str_replace("{WAP1}",$wap_name,$emess);
$template->setValue("{EMESS1}",$emess1);

//////////////////////////////////////wap


	
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
}


$returnVal=plotAdvertiserGraphs($show,$flag_time,$beg_time,$end_time,$selected_colorcode,$uid,$id );

$FC=$returnVal[0];
$FD=$returnVal[1];


$template->setValue("{ID}",$id);
if($ad_keyword_mode!=2){ 
$template->openLoop("KWD","select id,keyword from ppc_keywords where aid=$id");
$template->setLoopField("{LOOP(KWD)-KEYWORD}","keyword");
$template->setLoopField("{LOOP(KWD)-ID}","id");
$template->closeLoop();
}

$template->openLoop("STATISTICS",getTimeBasedAdvertiserStatistics($show,$flag_time,$beg_time,$end_time,$uid,$id));
$template->setLoopField("{LOOP(STATISTICS)-ID}","id");
$template->setLoopField("{LOOP(STATISTICS)-IMP}","impressions");
$template->setLoopField("{LOOP(STATISTICS)-CLK}","clicks");
$template->setLoopField("{LOOP(STATISTICS)-MONEY}","clickvalue");
$template->closeLoop();

$result=mysql_query("select title,link,summary,displayurl,status,amountused,pausestatus,adtype,bannersize,contenttype,hardcodelinks from ppc_ads where id='$id' $wap_string");
$row=mysql_fetch_row($result);
$template->setValue("{DURL}",$row[3]);

if($currency_format=="$$")
$template->setValue("{CURRENCY_SYMBOL}",$system_currency); 
else
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 

$template->setValue("{TITLE}",$row[0]);
$template->setValue("{URL}",$row[1]);
$template->setValue("{SUMMARY}",$row[2]);
$template->setValue("{DURL}",$row[3]);
$template->setValue("{STATUS}",$row[4]);
$template->setValue("{AMOUNTUSED}",$row[5]);
$template->setValue("{PSTATUS}",$row[6]);
$money_fmt=moneyFormat($row[5]);
//$template->setValue("{MONTH}",date("F"));

   loadsettings("ppc_new");
$budget_period=$GLOBALS['budget_period'];



//************************************************** Changes For Flash Ads *************************************

if(($row[7]==1 || $row[7]==2) && $row[9]=="swf")
{
$template->setValue("{CONTENTTYPE}",$row[9]);
$strHardLinks="";

if($row[10] >0)
{
for($i=0;$i<$row[10];$i++)
{
$strHardLinks.="flashvars.alink".($i+1)."='".$row[1]."';";
$strHardLinks.="flashvars.atar".($i+1)."='_blank';";
}
$template->setValue("{HARDLINKS}",$strHardLinks);
}
else
{
$template->setValue("{HARDLINKS}",$strHardLinks);
}


if($row[7]==1)
{
$resht=$mysql->echo_one("select height from banner_dimension where id='$row[8]'");
$reswt=$mysql->echo_one("select width from banner_dimension where id='$row[8]'");
}
else
{
$resht=$mysql->echo_one("select height from catalog_dimension where id='$row[8]'"); 
$reswt=$mysql->echo_one("select width from catalog_dimension where id='$row[8]'");
}




$template->setValue("{RESHT}",$resht);
$template->setValue("{RESWT}",$reswt);
}
else
{
$template->setValue("{CONTENTTYPE}","");
$template->setValue("{HARDLINKS}","");
$template->setValue("{RESHT}","");
$template->setValue("{RESWT}","");

}
//************************************************** Changes For Flash Ads *************************************







if($budget_period==1)
{
	$x=strftime("%B",time());
	$PERIOD=$template->checkAdvMsg(6090)." ".$x;
	$template->setValue("{PERIOD}",$PERIOD);
	
}
else if($budget_period==2)
{
	$PERIOD=$template->checkAdvMsg(7041);
	$template->setValue("{PERIOD}",$PERIOD);
}
$emess12=$template->checkAdvMsg(8954);
$emess102=str_replace("{AMOUNT1}",$money_fmt,$emess12);
$emess105=str_replace("{PERIOD1}",$PERIOD,$emess102);
$template->setValue("{EMESS105}",$emess105);
//$tablestructure=getTimeBasedAdvertiserStatistics($show,$flag_time,$beg_time,$end_time,$uid,$id);
//print_r($tablestructure);
$upgradation=dateTimeFormat($adserver_upgradation_date);
$edit=$template->checkAdvMsg(8960);
$edit1=str_replace("{DATE1}",$upgradation,$edit);
$template->setValue("{EDITMESS}",$edit1);
 $template->setValue("{ADTYPE}",$row[7]);
$template->setValue("{SHOWSTATUS}",$showmessage);

 $template->setValue("{BSIZE}",$row[8]);
 $catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row[8]'");
 $template->setValue("{CAT_WT}",$catalog_width);
$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row[8]'"); 
 $template->setValue("{CAT_HT}",$catalog_height);

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));                                           

$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding);     
$template->setValue("{BANNERS}",$GLOBALS['banners_folder']);
//echo $template->getPage();die;
		
eval('?>'.$template->getPage().'<?php ');

?>