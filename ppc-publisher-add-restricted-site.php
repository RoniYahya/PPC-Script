<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-add-restricted-site.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}

$form=new Form("AddRestrictedSite","ppc-publisher-add-restricted-site-action.php");
//$form->isEmail("email",$message[6009]);

$template->setValue("{NAME}",'<input name="email" type="text" id="email" maxlength="100">');
$form->isNotNull("email",$template->checkmsg(6040));
$form->isDomain("email",$template->checkmsg(1030));
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(7009)));

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));   

$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8943));
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


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
if(isset($_GET['customid']))
$template->setValue("{CUSTOMID}",getSafePositiveInteger('customid','g'));
else
$template->setValue("{CUSTOMID}","");


eval('?>'.$template->getPage().'<?php ');

?>
