<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
include("messages.".$client_language.".inc.php");
$template=new Template();
$template->loadTemplate("common-templates/ppc-ad-logo-details.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_users");

if(!($user->validateUser()))
{

header("Location:show-message.php?id=1006");
exit(0);
}

if($portal_system==1)
{
	//redirect  this page to portal corrensponding page
}


$user=new User("nesote_inoutscripts_users", "id");
if($user->getUsername()=="demouser" && $script_mode=="demo")
{
header("Location:error-message.php?id=6076");
exit(0);
}

$form=new Form("ppcNewAd","ppc-ad-logo-details-action.php");




$name="<input name=\"name\"  type=\"text\"  id=\"name\" value=".$template->checkAdvMsg(8887).">";
$logo="<input name=\"ad_logos\"  type=\"file\"  id=\"ad_logos\" value=".$template->checkAdvMsg(8887).">";
$sub="<input name=\"submit\" id=\"submit\" type=\"submit\"  value=\"Submit\">";

$template->setValue("{SUBMIT}",$sub);
$template->setValue("{NAME}",$name);
$template->setValue("{LOGO}",$logo);
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkComMsg(0001));  
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