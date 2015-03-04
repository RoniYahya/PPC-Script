<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-logo-details.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_publishers");
$pubid=$user->getUserID();

if($user->getUsername()=="publisher" && $script_mode=="demo")
{
header("Location:publisher-show-message.php?id=6076");
exit(0);
}

if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}




$uid=$mysql->echo_one("select uid from ppc_publishers where uid=$pubid");



$form=new Form("ppcpublogo","ppc-publisher-logo-details-action.php");




$form=new Form("manage","ppc-publisher-logo-details.php");
$sub="<input name=\"submit\"  type=\"submit\"  value=\"Submit\">";
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
$total=$mysql->echo_one("select count(*) from ad_logos_details  where cid='$uid'".$stat);


$p=$paging->page($total,$perpagesize,"","ppc-publisher-logo-details.php?status=$st");
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
$combined_msg=$template->checkPubMsg(8910);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{SHOW_STATISTICS}",$msg_replace2);
//echo "SELECT a.id, a.name, a.amountused, a.status, a.pausestatus, a.wapstatus, a.adtype, a.title, a.link, a.summary, a.displayurl, a.bannersize, b.language,a.contenttype
//FROM ppc_ads a JOIN adserver_languages b ON a.adlang = b.id WHERE uid='$uid' " . $adty. $stat. $dev."order by updatedtime DESC";
$template->openLoop("LOGO","SELECT a.id, a.name, a.ad_logos_name, a.status FROM ad_logos_details a  where cid='$uid' and user_status='1' " .$stat."order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$template->setLoopField("{LOOP(LOGO)-ID}","id");
$template->setLoopField("{LOOP(LOGO)-NAME}","name");
$template->setLoopField("{LOOP(LOGO)-IMAGE}","ad_logos_name");
$template->setLoopField("{LOOP(LOGO)-STATUS}","status");
$template->setValue("{PATH}",$GLOBALS['ad_logos_folder']);


$template->closeLoop();

//******************//
$url=urlencode($_SERVER['REQUEST_URI']);
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));      
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