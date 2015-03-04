<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_users");

if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}

$template=new Template();

$template->loadTemplate("ppc-templates/manage-ads.tpl.html");

$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$form=new Form("manage","manage-ads.php");
$sub="<input name=\"submit\"  type=\"submit\"  value=".$template->checkAdvMsg(8887).">";
$template->setValue("{SUBMIT}",$sub);
//$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(7004)));
//***********text-active-des
$urlstr="";
if($_REQUEST)
{
if(isset($_REQUEST['adtype']))	
$adtype=$_REQUEST['adtype'];
else if($_GET['adtype']!="")
$adtype=$_GET['adtype'];
else
$adtype=3;

if(isset($_REQUEST['device']))	
$device=$_REQUEST['device'];
else if($_GET['device']!="")
$device=$_GET['device'];
else
$device=2;

if(isset($_REQUEST['status']))	
$st=$_REQUEST['status'];
else if($_GET['status']!="")
$st=$_GET['status'];
else
$st=4;




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
	$urlstr="?status=1";
	
}
elseif($st=="-1")
{
$stat="and status ='-1'";	
$urlstr="?status=-1";
}
elseif($st=="0")
{
	$stat="and status ='0'";
	$urlstr="?status=0";	
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
}
else
{
	$dev="";
}
}
else
{
	$adty=" ";
	$dev=" ";
	$stat=" ";

}

$uid=$user->getUserID();
$pageno=1;
if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
$perpagesize = 10;
$total=$mysql->echo_one("select count(*) from ppc_ads  where uid='$uid' ".$adty .$stat .$dev);
//echo $total;
$p=$paging->page($total,$perpagesize,"","manage-ads.php?adtype=$adtype&device=$device&status=$st");
//echo ?adtype=$adtype&device=$device&status=$st;
$template->setValue("{HEADER}","$p");
$string=" ";
$beg=(($pageno-1)*$perpagesize+1);
if($total>=1) 
{
//$string=$template->checkAdvMsg(6058)."<span class=\"inserted\">".(($pageno-1)*$perpagesize+1)."</span>"; 
 if(($pageno*$perpagesize)<=$total) 
   $end=$pageno*$perpagesize;
 //$string.=$template->checkAdvMsg(6053)."<span class=\"inserted\">".$pageno*$perpagesize."</span>".$template->checkAdvMsg(6054)."<span class=\"inserted\">".$total."</span>"; 
 else 
  $end=$total;
 //$string.=$template->checkAdvMsg(6053)."<span class=\"inserted\">".$total."</span>".$template->checkAdvMsg(6054)."<span class=\"inserted\">".$total."</span>"; 
}
$combined_msg=$template->checkAdvMsg(8910);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{SHOW_STATISTICS}",$msg_replace2);
//echo "SELECT a.id, a.name, a.amountused, a.status, a.pausestatus, a.wapstatus, a.adtype, a.title, a.link, a.summary, a.displayurl, a.bannersize, b.language,a.contenttype
//FROM ppc_ads a JOIN adserver_languages b ON a.adlang = b.id WHERE uid='$uid' " . $adty. $stat. $dev."order by updatedtime DESC";
$template->openLoop("ADS","SELECT a.id, a.name, a.amountused, a.status, a.pausestatus, a.wapstatus, a.adtype, a.title, a.link, a.summary, a.displayurl, a.bannersize, a.adlang,a.contenttype
FROM ppc_ads a WHERE uid='$uid' " . $adty. $stat. $dev."order by updatedtime DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$template->setLoopField("{LOOP(ADS)-ID}","id");
$template->setLoopField("{LOOP(ADS)-NAME}","name");
$template->setLoopField("{LOOP(ADS)-AMOUNTUSED}","amountused");
$template->setLoopField("{LOOP(ADS)-STATUS}","status");
$template->setLoopField("{LOOP(ADS)-PSTATUS}","pausestatus");
$template->setLoopField("{LOOP(ADS)-WAPSTATUS}","wapstatus");
$template->setLoopField("{LOOP(ADS)-TYPE}","adtype");
$template->setLoopField("{LOOP(ADS)-TITLE}","title");
$template->setLoopField("{LOOP(ADS)-LINK}","link");
$template->setLoopField("{LOOP(ADS)-SUMMARY}","summary");
$template->setLoopField("{LOOP(ADS)-DISPLAYURL}","displayurl");
$template->setLoopField("{LOOP(ADS)-BANNERSIZE}","bannersize");
$template->setLoopField("{LOOP(ADS)-LANGUAGE}","adlang");
$template->setLoopField("{LOOP(ADS)-CONTENTTYPE}","contenttype");
$template->closeLoop();

function getWIDTH($width,$flag=0)
{
global $mysql;

if($flag == 0)
$wt=$mysql->echo_one("select width from banner_dimension where id='$width'");
else
$wt=$mysql->echo_one("select width from catalog_dimension where id='$width'");

return $wt;
}
function getHEIGHT($height,$flag=0)
{
global $mysql;

if($flag == 0)
$ht=$mysql->echo_one("select height from banner_dimension where id='$height'");
else
$ht=$mysql->echo_one("select height from catalog_dimension where id='$height'");

return $ht;
}
  

////*************text-active-all
////echo "select id,name,amountused,status,pausestatus from ppc_ads  where uid='$uid' and adtype='0'  and status ='1'";
//$template->openLoop("ADS2","select id,name,amountused,status,pausestatus from ppc_ads  where uid='$uid' and adtype='0'  and status ='1'  ");
//$template->setLoopField("LOOP(ADS2)-ID","id");
//$template->setLoopField("{LOOP(ADS2)-NAME}","name");
//$template->setLoopField("{LOOP(ADS2)-AMOUNTUSED}","amountused");
//$template->setLoopField("{LOOP(ADS2)-STATUS}","status");
//$template->setLoopField("{LOOP(ADS2)-PSTATUS}","pausestatus");
//
//$template->closeLoop();
////*******************
//
////text-pending-des
//
//$template->openLoop("ADSPEN","select id,name,amountused,status,pausestatus from ppc_ads  where uid='$uid' and adtype='0'  and status ='-1' and wapstatus='0' ");
//$template->setLoopField("LOOP(ADSPEN)-ID","id");
//$template->setLoopField("{LOOP(ADSPEN)-NAME}","name");
//$template->setLoopField("{LOOP(ADSPEN)-AMOUNTUSED}","amountused");
//$template->setLoopField("{LOOP(ADSPEN)-STATUS}","status");
//$template->setLoopField("{LOOP(ADSPEN)-PSTATUS}","pausestatus");
//
//$template->closeLoop();
//
//
//
////************
//
////text-pending-wap
//
//$template->openLoop("ADSPEN1","select id,name,amountused,status,pausestatus from ppc_ads  where uid='$uid' and adtype='0'  and status ='-1' and wapstatus='1' ");
//$template->setLoopField("LOOP(ADSPEN1)-ID","id");
//$template->setLoopField("{LOOP(ADSPEN1)-NAME}","name");
//$template->setLoopField("{LOOP(ADSPEN1)-AMOUNTUSED}","amountused");
//$template->setLoopField("{LOOP(ADSPEN1)-STATUS}","status");
//$template->setLoopField("{LOOP(ADSPEN1)-PSTATUS}","pausestatus");
//
//$template->closeLoop();
//
//
//
////************
////text-pending-all
//
//$template->openLoop("ADSPEN2","select id,name,amountused,status,pausestatus from ppc_ads  where uid='$uid' and adtype='0'  and status ='-1'  ");
//$template->setLoopField("LOOP(ADSPEN2)-ID","id");
//$template->setLoopField("{LOOP(ADSPEN2)-NAME}","name");
//$template->setLoopField("{LOOP(ADSPEN2)-AMOUNTUSED}","amountused");
//$template->setLoopField("{LOOP(ADSPEN2)-STATUS}","status");
//$template->setLoopField("{LOOP(ADSPEN2)-PSTATUS}","pausestatus");
//
//$template->closeLoop();
//
//
//
//
////************
////*******text-blocked-des
//
//$template->openLoop("ADSBLO","select id,name,amountused,status,pausestatus from ppc_ads  where uid='$uid' and adtype='0'  and status ='0' and wapstatus='0' ");
//$template->setLoopField("LOOP(ADSBLO)-ID","id");
//$template->setLoopField("{LOOP(ADSBLO)-NAME}","name");
//$template->setLoopField("{LOOP(ADSBLO)-AMOUNTUSED}","amountused");
//$template->setLoopField("{LOOP(ADSBLO)-STATUS}","status");
//$template->setLoopField("{LOOP(ADSBLO)-PSTATUS}","pausestatus");
//
//$template->closeLoop();
//
//
//
//
//
////****
////*text-block-wap
//
//$template->openLoop("ADSBLO1","select id,name,amountused,status,pausestatus from ppc_ads  where uid='$uid' and adtype='0'  and status ='0' and wapstatus='1' ");
//$template->setLoopField("LOOP(ADSBLO1)-ID","id");
//$template->setLoopField("{LOOP(ADSBLO1)-NAME}","name");
//$template->setLoopField("{LOOP(ADSBLO1)-AMOUNTUSED}","amountused");
//$template->setLoopField("{LOOP(ADSBLO1)-STATUS}","status");
//$template->setLoopField("{LOOP(ADSBLO1)-PSTATUS}","pausestatus");
//
//$template->closeLoop();
//
//
//
////***********
////*********text-block-all
//
//$template->openLoop("ADSBLO2","select id,name,amountused,status,pausestatus from ppc_ads  where uid='$uid' and adtype='0'  and status ='0'  ");
//$template->setLoopField("LOOP(ADSBLO2)-ID","id");
//$template->setLoopField("{LOOP(ADSBLO2)-NAME}","name");
//$template->setLoopField("{LOOP(ADSBLO2)-AMOUNTUSED}","amountused");
//$template->setLoopField("{LOOP(ADSBLO2)-STATUS}","status");
//$template->setLoopField("{LOOP(ADSBLO2)-PSTATUS}","pausestatus");
//
//$template->closeLoop();
//
//
//

//*********
//******************//
$url=urlencode($_SERVER['REQUEST_URI']);
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));      
$template->setValue("{URL}",$url);                                            
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());	
$template->setValue("{BANNERFOLDER}",$GLOBALS['banners_folder']);

$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
//echo $template->getPage();
eval('?>'.$template->getPage().'<?php ');
?>