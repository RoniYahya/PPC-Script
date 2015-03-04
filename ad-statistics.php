<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

//include_once("messages.".$client_language.".inc.php");
includeClass("Template");
includeClass("User");
includeClass("Form");
$template=new Template();
$template->loadTemplate("ppc-templates/ad-statistics.tpl.html");
$user=new User("ppc_users");

if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
 include("advertiser_statistics_utils.php");



$flag_time=0;

$form=new Form("stat","ad-statistics.php");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
if(!isset($_REQUEST['statistics']))
{	
$show="day";	}
else
{
	$show=$_REQUEST['statistics'];
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
$wap_name="";

if($_REQUEST)
{
$adtype=$_REQUEST['adtype'];
$device=$_REQUEST['device'];
$st=$_REQUEST['status'];

if($adtype=="0")
{
	$adty="and adtype='0'";
}
elseif($adtype=="1")
{
	$adty="and adtype='1'";
}
elseif($adtype=="2")
{
	$adty="and adtype='2'";
}
else
{
	$adty="";
	
}
if($st=="1")
{
	$stat="and status ='1'";
}
elseif($st=="-1")
{
$stat="and status ='-1'";	
}
elseif($st=="0")
{
	$stat="and status ='0'";	
}
else
{
	$stat="";
}
if($device=="0")
{
	$dev="and wapstatus='0'";
	
}
elseif($device=="1")
{
	$dev="and wapstatus='1'";
	$wap_name='Wap';
}
else
{
	$dev="";
}
}
else
{
	$adty="";
	$dev="";
	$stat="";
}
$pageno=1;
if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
$perpagesize = 10;
$uid=$user->getUserID();
$total=$mysql->echo_one("select count(*) from ppc_ads  where uid='$uid' ".$adty.$stat.$dev);

$p=$paging->page($total,$perpagesize,"","ad-statistics.php?adtype=$adtype&device=$device&status=$st&statistics=$show");
$template->setValue("{HEADER}","$p");
$string="";
$beg=(($pageno-1)*$perpagesize+1);
if($total>=1) 
{
//$string=$template->checkAdvMsg(6059). "<span class=\"inserted\">".(($pageno-1)*$perpagesize+1)."</span>"; 
 if(($pageno*$perpagesize)<=$total) 
  $end=$pageno*$perpagesize;
// $string.=$template->checkAdvMsg(6053). "<span class=\"inserted\">".$pageno*$perpagesize."</span>". $template->checkAdvMsg(6054) ."<span class=\"inserted\">".$total."</span>"; 
 else 
  $end=$total;
 //$string.=$template->checkAdvMsg(6053). "<span class=\"inserted\">".$total."</span>". $template->checkAdvMsg(6054) ."<span class=\"inserted\">".$total."</span>"; 
}
$combined_msg=$template->checkAdvMsg(8908);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);

$template->setValue("{SHOW_STATISTICS}",$msg_replace2);
//echo "select id,title,link,summary,amountused,status,pausestatus,displayurl,name from ppc_ads  where uid='$uid'" .$adty.$stat.$dev ;
$template->openLoop("ADS","select id,title,link,summary,amountused,status,pausestatus,displayurl,name,wapstatus from ppc_ads  where uid='$uid'" .$adty.$stat.$dev. " order by updatedtime DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$template->setLoopField("{LOOP(ADS)-ID}","id");
$template->setLoopField("{LOOP(ADS)-TITLE}","title");
$template->setLoopField("{LOOP(ADS)-URL}","link");
$template->setLoopField("{LOOP(ADS)-SUMMARY}","summary");
$template->setLoopField("{LOOP(ADS)-AMOUNTUSED}","amountused");
$template->setLoopField("{LOOP(ADS)-STATUS}","status");
$template->setLoopField("{LOOP(ADS)-PSTATUS}","pausestatus");
$template->setLoopField("{LOOP(ADS)-DURL}","displayurl");
$template->setLoopField("{LOOP(ADS)-NAME}","name");
$template->setLoopField("{LOOP(ADS)-WAPSTATUS}","wapstatus");
$template->closeLoop();



if($currency_format=="$$")
	{
		$template->setValue("{CURRENCY}",$system_currency);
	}
else
	{
		$template->setValue("{CURRENCY}",$currency_symbol); 
	}

loadsettings("ppc_new");
$template->setValue("{WAP_NAME}",$wap_name);


$template->setValue("{SHOWSTATUS}",$showmessage);
$template->setValue("{VIEWTYPE}",$show);
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
//$template->setValue("{TABS}",$template->includePage($server_dir."ppc-page-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));                                                                                                       
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


$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding);   

eval('?>'.$template->getPage().'<?php ');

?>