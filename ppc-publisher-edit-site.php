<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-edit-site.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}

$id=getSafePositiveInteger('id');
if(!mySite($id,$user->getUserID(),$mysql))
{
header("Location:publisher-show-message.php?id=5010");
exit(0);
}
$form=new Form("EditRestrictedSite","ppc-publisher-edit-restricted-site-action.php?id=$id");
//$form->isEmail("email",$message[6009]);

$name=$mysql->echo_one("select site from ppc_restricted_sites where id='$id'");
$template->setValue("{NAME}",$form->addTextBox("site",$name,40,255));


$form->isNotNull("site", $template->checkmsg(6040));
$form->isDomain("site",$template->checkmsg(1030));
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(7010)));

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  

//$publisher_message[8947]='{ENGINE_NAME} Edit restricted site!';
//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$publisher_message[8951]);
//$template->setValue("{ENGINE_TITLE}",$engine_title);
 
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
