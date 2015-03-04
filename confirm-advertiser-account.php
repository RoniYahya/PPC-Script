<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

if($single_account_mode==0)
{
					
header("Location: index.php");
exit(0);	
	
}
$template=new Template();

$template->loadTemplate("publisher-templates/confirm-advertiser-account.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$comid=$mysql->echo_one("select common_account_id from ppc_publishers where uid=".$user->getUserID());
if($comid!=0)
{
header("Location:ppc-publisher-control-panel.php");
exit(0);	
}

$form=new Form("userLogin","publisher-single-account.php");

$template->setValue("{USERFIELD}",$form->addTextBox("username"));
$template->setValue("{PASSFIELD}",$form->addPassword("password"));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(8889)));
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
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));    

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
eval('?>'.$template->getPage().'<?php ');

?>