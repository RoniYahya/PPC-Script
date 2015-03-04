<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

//includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

if($single_account_mode==1)
{
	header("Location: forgot-password.php");
exit(0);
}
$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-forgot-password.tpl.html");
//$user=new User("ppc_users");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");



$form=new Form("EditPassword","ppc-publisher-forgot-password-action.php");

$form->isNotNull("username",$template->checkmsg(6001));

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{USERFIELD}",$form->addTextBox("username","",40,255));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(8881)));

//$template->setValue("{TABS}",$template->includePage($server_dir."ppc-page-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  
//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8957));

$template->setValue("{ENGINE_TITLE}",$engine_title); 

$msg1= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8958));
$template->setValue("{MSG1}",$msg1);
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
