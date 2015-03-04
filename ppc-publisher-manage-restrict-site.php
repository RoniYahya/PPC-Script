<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

//include_once("messages.".$client_language.".inc.php");
includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");
$template=new Template();

$template->loadTemplate("publisher-templates/ppc-publisher-manage-restrict-site.tpl.html");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}




$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$uid=$user->getUserID();
$total=$mysql->echo_one("select count(*) from ppc_restricted_sites where uid='$uid' ");
$template->openLoop("ADS","select site,id   from ppc_restricted_sites  where uid='$uid' order by id DESC");
//$rdate=date("F j, Y, g:i a",reqdate);                 

$template->setLoopField("{LOOP(ADS)-SQL}","site");
$template->setLoopField("{LOOP(ADS)-ID}","id");

//$template->setLoopField("{LOOP(ADS)-SUMMARY}","summary");

$template->closeLoop();


//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  

//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8963));
$template->setValue("{ENGINE_TITLE}",$engine_title); 
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


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     //
eval('?>'.$template->getPage().'<?php ');

?>
