<?php



include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


// include("functions.inc.php");

includeClass("Template");

includeClass("User");

includeClass("Form");


//include_once("messages.".$client_language.".inc.php");




	$user=new User("ppc_users");
	if(!$user->validateUser())
	{
	header("Location:show-message.php?id=1006");
	exit(0);
	}







//$adid=$_GET['id'];
$aduid=$user->getUserID();


$condition="uid='$aduid'";


$template=new Template();
$template->loadTemplate("ppc-templates/geographical-distribution.tpl.html");


$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");




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
$tablename="ppc_daily_clicks";
$tablename1="ppc_daily_ impressions";

//echo date("d M h a",$end_time);
}
else if($show=="week")
{
$flag_time=0;	
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}
else if($show=="month")
{
$flag_time=0;	
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}

else
{
$flag_time=0;	
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}

$last_hour_end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));	

$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));	
if($flag_time ==0)
{

$country=mysql_query("SELECT sum(ids),cds FROM (SELECT count(id) as ids,country as cds  FROM $tablename WHERE $condition AND time>='$beg_time' and time <='$last_hour_end_time' and country <>'' group by country UNION SELECT count(id) as ids,country as cds FROM ppc_daily_clicks WHERE $condition AND time>='$spec_time_limits' and time <='$last_hour_end_time' and country <>'' group by country)x group by x.cds");
}
else
{


$country=mysql_query("select count(id) ,country from  $tablename where $condition and country <>'' and time>='$beg_time' and time <='$last_hour_end_time' group by country ");
//echo "select count(id) no,country from  $tablename where $condition and country <>'' and time>='$beg_time' group by country ORDER BY no DESC";
}

while($row=mysql_fetch_row($country))
{
$cname=$mysql->echo_one("select name from location_country  where code='$row[1]'");  
 $map.='<area title="'.$cname.'"  mc_name="'.$row[1].'" value="'.$row[0].'"></area>';
}




 $map='<map map_file="maps/world.swf" tl_long="-168.49" tl_lat="83.63" br_long="190.3" br_lat="-55.58"><areas>'. $map
 .'</areas><labels><label x="0" y="50" width="100%" align="center" text_size="16" color="#999999"><text><![CDATA[<b>Geographical Clicks Distribution]]></text><description><![CDATA[]]></description></label></labels></map>';
 
// $map=htmlspecialchars("<map map_file=".$map);

$pageno=1;
if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
$perpagesize = 10;




if($flag_time ==0)
{

$total1=mysql_query("SELECT sum(ids) as ids,cod 
FROM (select count(id) as ids,country as cod from $tablename  where $condition and country <>'' and time>='$beg_time' and time <='$last_hour_end_time' group by country UNION SELECT count(id) as ids,country as cod FROM ppc_daily_clicks WHERE $condition and country <>'' and time>='$spec_time_limits' and time <='$last_hour_end_time' group by country )x group by x.cod order by ids desc");


}
else
{
$total1=mysql_query("select count(*) from  $tablename where $condition and country <>'' and time>='$beg_time' and time <='$last_hour_end_time' group by country");


}

$total=mysql_num_rows($total1);


//echo "select count(*) from  $tablename where $condition and country <>'' and time>='$beg_time' group by country";
$p=$paging->page($total,$perpagesize,"","geographical-distribution.php?statistics=$show");
$template->setValue("{HEADER}","$p");
$beg=(($pageno-1)*$perpagesize+1);
$string="";
if($total>=1) 
{
//$string=$template->checkAdvMsg(8905) ."<span class=\"inserted\"> ".(($pageno-1)*$perpagesize+1)." </span>"; 
 if(($pageno*$perpagesize)<=$total) 
  $end=$pageno*$perpagesize;
 //$string.=$template->checkAdvMsg(6053) ."<span class=\"inserted\"> ".$pageno*$perpagesize ." </span> ".$template->checkAdvMsg(6054) ." <span class=\"inserted\"> ".$total." </span>"; 
 else 
 $end=$total;
 //$string.=$template->checkAdvMsg(6053) ."<span class=\"inserted\"> ".$total." </span> ".$template->checkAdvMsg(6054)." <span class=\"inserted\"> ".$total."</span>"; 
}

$combined_msg=$template->checkAdvMsg(8909);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{SHOW_CLICK}",$msg_replace2);


if($flag_time ==0)
{

$template->openLoop("TAB","SELECT sum(ids) as ids,cod 
FROM (select count(id) as ids,country as cod from $tablename  where $condition and country <>'' and time>='$beg_time' and time <='$last_hour_end_time' group by country UNION SELECT count(id) as ids,country as cod FROM ppc_daily_clicks WHERE $condition and country <>'' and time>='$spec_time_limits' and time <='$last_hour_end_time' group by country )x group by x.cod order by ids desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);



$template->setLoopField("{LOOP(TAB)-NO}","ids");
$template->setLoopField("{LOOP(TAB)-COUNTRY}","cod");
$template->closeLoop();




}
else
{

$template->openLoop("TAB","select count(id) no,country from  $tablename where $condition and country <>'' and time>='$beg_time' and time <='$last_hour_end_time' group by country ORDER BY no DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
//echo "select id,clickvalue,ip,time,country from $table  where uid='$uid'  and time>=$beg_time";
$template->setLoopField("{LOOP(TAB)-NO}","no");
$template->setLoopField("{LOOP(TAB)-COUNTRY}","country");
$template->closeLoop();



}

if($pageno!=1)
{
	$loopno=($pageno-1)*$perpagesize+1;
}
else
{
	$loopno=1;
}
$template->setValue("{LOOPNO}",$loopno);
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





$template->setValue("{PAGEWIDTH}",$page_width);  

$template->setValue("{ENCODING}",$ad_display_char_encoding);  


eval('?>'.$template->getPage().'<?php ');

?>