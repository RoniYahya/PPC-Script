<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();

$template->loadTemplate("ppc-templates/click-analysis.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();
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
$flag_time=0;	
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
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

$pageno=1;
if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
$perpagesize = 10;
$uid=$user->getUserID();

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));	
$last_hour_end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));	
if($flag_time==0)
{




$total=$mysql->echo_one("SELECT count(id) FROM (SELECT * FROM $table WHERE uid='$uid' AND time>='$beg_time' and time <='$last_hour_end_time' UNION SELECT *  FROM ppc_daily_clicks
WHERE uid='$uid' AND time>='$spec_time_limits' and time <='$last_hour_end_time')x");


}
else
{
$total=$mysql->echo_one("select count(*) from $table  where uid='$uid' and time>=$beg_time and time <='$last_hour_end_time'");
}

$p=$paging->page($total,$perpagesize,"","click-analysis.php?statistics=$show");
$template->setValue("{HEADER}","$p");
$string="";
$beg=(($pageno-1)*$perpagesize+1);
if($total>=1) 
{

 if(($pageno*$perpagesize)<=$total) 
 $end=$pageno*$perpagesize;
 else 
 $end=$total;
  
}
$combined_msg=$template->checkAdvMsg(8907);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);


$template->setValue("{SHOW_CLICK}",$msg_replace2);

if($flag_time==0)
{




$template->openLoop("CLICK","select id,clickvalue,ip,`current_time`,name,browser,platform,version from(select a.id as id,a.clickvalue as clickvalue,a.ip as ip,a.current_time as `current_time`,a.country as name,a.browser as browser,a.platform as platform,a.version as version from $table a  where a.uid='$uid'  and a.time>=$beg_time and a.time <='$last_hour_end_time'  UNION  select a.id as id,a.clickvalue as clickvalue,a.ip as ip,a.current_time as `current_time`,a.country as name,a.browser as browser,a.platform as platform,a.version as version from ppc_daily_clicks a  where a.uid='$uid'  and a.time>=$spec_time_limits and a.time <='$last_hour_end_time' )x order by `current_time` DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);



//$template->openLoop("CLICK","select a.id,a.clickvalue,a.ip,a.current_time,b.name,a.browser,a.platform,a.version from $table a join location_country b ON b.code = a.country where a.uid='$uid'  and a.time>=$beg_time order by time DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);




$template->setLoopField("{LOOP(CLICK)-ID}","id");
$template->setLoopField("{LOOP(CLICK)-CLICKVALUE}","clickvalue");
$template->setLoopField("{LOOP(CLICK)-IP}","ip");
$template->setLoopField("{LOOP(CLICK)-TIME}","current_time");
$template->setLoopField("{LOOP(CLICK)-COUNTRY}","name");

$template->setLoopField("{LOOP(CLICK)-BR}","browser");
$template->setLoopField("{LOOP(CLICK)-VR}","version");
$template->setLoopField("{LOOP(CLICK)-OS}","platform");
$template->closeLoop();

}
else
{
//echo "select a.id,a.clickvalue,a.ip,a.current_time,b.name,a.browser,a.platform,a.version from $table a where a.uid='$uid'  and a.time>=$beg_time order by time DESC";
$template->openLoop("CLICK","select a.id,a.clickvalue,a.ip,a.current_time,a.country,a.browser,a.platform,a.version from $table a where a.uid='$uid'  and a.time>=$beg_time and a.time <='$last_hour_end_time' order by time DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

$template->setLoopField("{LOOP(CLICK)-ID}","id");
$template->setLoopField("{LOOP(CLICK)-CLICKVALUE}","clickvalue");
$template->setLoopField("{LOOP(CLICK)-IP}","ip");
$template->setLoopField("{LOOP(CLICK)-TIME}","current_time");
$template->setLoopField("{LOOP(CLICK)-COUNTRY}","country");

$template->setLoopField("{LOOP(CLICK)-BR}","browser");
$template->setLoopField("{LOOP(CLICK)-VR}","version");
$template->setLoopField("{LOOP(CLICK)-OS}","platform");
$template->closeLoop();

}





$form=new Form("analysis","click-analysis.php");
if($pageno!=1)
{
	$loopno=($pageno-1)*$perpagesize+1;
}
else
{
	$loopno=1;
}
$template->setValue("{LOOPNO}",$loopno);
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
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
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));  

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding); 
 
eval('?>'.$template->getPage().'<?php ');
?>