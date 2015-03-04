<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

$user=new User("ppc_users");
if($single_account_mode==1)
{
	header("Location: forgot-password.php");
exit(0);
}
$template=new Template();
$template->loadTemplate("ppc-templates/ppc-forgot-password.tpl.html");

$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");



$form=new Form("EditPassword","ppc-forgot-password-action.php");

$form->isNotNull("username",$template->checkmsg(6001));
 
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{USERFIELD}",$form->addTextBox("username","",40,255));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(9500)));
$eng_replace=$template->checkAdvMsg(8958);
$eng_replace1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$eng_replace);
$template->setValue("{EDITMESS}",$eng_replace1);
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
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
