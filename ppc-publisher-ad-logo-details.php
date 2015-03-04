<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-ad-logo-details.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_publishers");

/*if($user->getUsername()=="publisher" && $script_mode=="demo")
{
header("Location:publisher-show-message.php?id=6076");
exit(0);
}
*/
$pubid=$user->getUserID();
$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid=$pubid");



if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$form=new Form("ppcpublogo","ppc-publisher-ad-logo-details-action.php");




$name="<input name=\"name\"  type=\"text\"  id=\"name\" >";
$logo="<input name=\"ad_logos\"  type=\"file\"  id=\"ad_logos\" >";
$sub="<input name=\"submit\" id=\"submit\" type=\"submit\"  value=\"Submit\">";

$template->setValue("{SUBMIT}",$sub);
$template->setValue("{NAME}",$name);
$template->setValue("{LOGO}",$logo);
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());

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


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>