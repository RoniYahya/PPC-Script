<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
includeClass("Template");
includeClass("User");
$template=new Template();
$template->loadTemplate("publisher-templates/publisher-affiliate-code.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
//print_r($publisher_message);
$user=new User("ppc_publishers");
if(!($user->validateUser()))
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
//$referral_system=1;
if($referral_system==0)
{
header("Location:publisher-show-message.php?id=6077");
exit(0);
}
$template->openLoop("BANNERS","select *   from affiliate_banners order by level ASC");
//$rdate=date("F j, Y, g:i a",reqdate);                 

$template->setLoopField("{LOOP(BANNERS)-FILENAME}","filename");
$template->setLoopField("{LOOP(BANNERS)-ID}","id");
$template->setLoopField("{LOOP(BANNERS)-X}","width");
$template->setLoopField("{LOOP(BANNERS)-Y}","height");

//$template->setLoopField("{LOOP(ADS)-SUMMARY}","summary");

$template->closeLoop();

$template->setValue("{ADMIN}",$GLOBALS['admin_folder']);
//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
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


$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8974));
$template->setValue("{ENGINE_TITLE}",$engine_title);

$msg1= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8975));
$template->setValue("{MSG1}",$msg1);


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     $template->setValue("{LINK}",$server_dir."index.php?r=".$user->getUserID());
$template->setValue("{SERVER_DIR}",$server_dir);
$template->setValue("{RID}",$user->getUserID());

eval('?>'.$template->getPage().'<?php ');

?>
