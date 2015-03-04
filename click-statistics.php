<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

//include_once("messages.".$client_language.".inc.php");
includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");
$template=new Template();

$template->loadTemplate("publisher-templates/click-statistics.tpl.html");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$flag_time=0;

$wap=0;
$wapstr="";


if(isset($_POST['device']))
{	
$wap=$_POST['device'];	}
else if($_GET['device']!="")
$wap=$_GET['device'];
else
{ 
	$wap=0;
}


if($_POST['device']==1)
	{
		
		$wapstr="and wapstatus=1";
	}
else
	{
		
		$wapstr="and wapstatus=0";
	}
	

include("publisher_statistics_utils.php");

$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$temp=time();
if(isset($_POST['statistics']))
{	
$show=$_POST['statistics'];	}
else if($_GET['statistics']!="")
$show=$_GET['statistics'];
else
{ 
	$show="day";
}


if($show=="day")
{
$flag_time=-1;	
$showmessage="Today";
//$time=mktime(date("H",$temp)-24,0,0,date("m",$temp),date("d",$temp),date("y",$temp));
$time=mktime(0,0,0,date("m",$temp),date("d",$temp),date("y",$temp));
$end_time=mktime(date("H",$temp)+1,0,0,date("m",$temp),date("d",$temp),date("y",$temp));

$beg_time=$time;
//echo date("d M h a",$end_time);
}
else if($show=="week")
{
$showmessage="Last 14 Days";
	//$time=mktime(0,0,0,date("m",$temp),date("d",$temp)-13,date("Y",$temp));//$end_time-(14*24*60*60);
	$time=mktime(0,0,0,date("m",$temp),date("d",$temp)-12,date("Y",$temp));
	$end_time=mktime(0,0,0,date("m",$temp),date("d",$temp),date("y",$temp));
$beg_time=$time;
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	//$time=mktime(0,0,0,date("m",$temp),date("d",$temp)-29,date("Y",$temp)); //mktime(0,0,0,date("m",$temp),1,date("y",$temp));
	$time=mktime(0,0,0,date("m",$temp),date("d",$temp)-28,date("Y",$temp)); 
	$end_time=mktime(0,0,0,date("m",$temp)+1,date("d",$temp),date("y",$temp));
$beg_time=$time;
}
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",$temp)+1-11,1,date("Y",$temp));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",$temp));
	$end_time=mktime(0,0,0,date("m",$temp),date("y",$temp));
$beg_time=$time;
}
else if($show=="all")
{
	$flag_time=2;
	$showmessage="All Time";
	$time=0;
	$end_time=mktime(0,0,0,1,1,date("y",$temp)+1);
$beg_time=$time;

}
else
{
$showmessage="Last 14 Days";
	//$time=mktime(0,0,0,date("m",$temp),date("d",$temp)-13,date("Y",$temp));//$end_time-(14*24*60*60);
	$time=mktime(0,0,0,date("m",$temp),date("d",$temp)-12,date("Y",$temp));
$end_time=mktime(0,0,0,date("m",$temp),date("d",$temp),date("y",$temp));
$beg_time=$time;
}


//echo  date("Y m d  h i s a",$time);


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
$uid=$user->getUserID();
$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
$total=$mysql->echo_one("select count(*) from ppc_custom_ad_block where pid='$uid' and status='1' $wapstr");
$total_temp=$total;
if($adserver_upgradation_date!=0)
	$total_temp=$mysql->echo_one("select count(*) from ppc_custom_ad_block where pid='$uid' and status='1' $wapstr")+1;

$lastpage=floor($total/$perpagesize);
if($total%$perpagesize!=0)
	$lastpage+=1;


$p=$paging->page($total,$perpagesize,"","click-statistics.php?statistics=".$show."&device=".$wap);
//echo $p;
$template->setValue("{HEADER}","$p");
$string="";


$lastrow=$pageno*$perpagesize;
if($lastrow==$total && $adserver_upgradation_date!=0)
$lastrow=$total_temp;
$beg=(($pageno-1)*$perpagesize+1);
$string="";
if($total>=1) 
{
//$string=$template->checkPubMsg(6072) ."<span class=\"inserted\">".(($pageno-1)*$perpagesize+1)."</span>"; 
 if(($pageno*$perpagesize)<=$total) 
// $string.=$template->checkPubMsg(6053) ."<span class=\"inserted\">".$lastrow."</span>".$template->checkPubMsg(6054)."<span class=\"inserted\">".$total_temp."</span>"; 
  $end=$pageno*$perpagesize;
 else 
  $end=$total;
// $string.=$template->checkPubMsg(6053)."<span class=\"inserted\">".$total_temp."</span>".$template->checkPubMsg(6054)."<span class=\"inserted\">".$total_temp."</span>"; 
}
$combined_msg=$template->checkPubMsg(8934);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{SHOW_STATISTICS}",$msg_replace2);

if($currency_format=="$$")
	{
		$template->setValue("{CURRENCY}",$system_currency);
	}
else
	{
		$template->setValue("{CURRENCY}",$currency_symbol);
	 }

$template->setValue("{TAB_NAME}",$table_name);
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$template->setValue("{UMESS}",$showmessage);
$template->setValue("{SHOWSTATUS}",$showmessage);
$template->setValue("{VIEWTYPE}",$show);
$template->openLoop("ADS","select name,id  from ppc_custom_ad_block where pid='$uid' and status='1' $wapstr order by id desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
//echo "select name,id  from ppc_custom_ad_block where pid='$uid' and status='1' $wapstr order by id 	 DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;
$template->setLoopField("{LOOP(ADS)-NAME}","name");
$template->setLoopField("{LOOP(ADS)-ID}","id");

$template->closeLoop();
if($adserver_upgradation_date!=0)
{
$result=mysql_query("select COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from $table_name where uid='$uid' and bid=0 and time>$time");
$row=mysql_fetch_row($result);
$ro=mysql_num_rows($result);
$aa=getPubBlockClicks($time,$mysql,$uid,0,$flag_time);
$template->setValue("{DCLICK}",numberFormat($aa,0));
$template->setValue("{DPROFIT}",round(getPubBlockPublisherprofit($time,$mysql,$uid,0,$flag_time),2));

$total_impressions=getPubBlockImpressions($time,$mysql,$uid,0,$flag_time); //$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$uid' and bid=0 and time>$time");

	if($total_impressions==0)
		{
		$ctr=0;
		}
	else
		{
		$ctr=($row[0]/$total_impressions) * 100;
		$ctr=round($ctr,2);
		}
$template->setValue("{IMP}",numberFormat($total_impressions,0));
$template->setValue("{CTR}",numberFormat($ctr));


}

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
$template->setValue("{TOT}",$total);
$str= str_replace("{DATE}",dateTimeFormat($adserver_upgradation_date),$template->checkPubMsg(8937));
$template->setValue("{DATE}",$str);
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8982));
$template->setValue("{ENGINE_TITLE}",$engine_title);

$template->setValue("{BLANK}",""); 
$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding);     
//echo $template->getPage();
eval('?>'.$template->getPage().'<?php ');
?>