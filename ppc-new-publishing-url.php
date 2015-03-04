<?php
/*include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");
$template=new Template();
$template->loadTemplate("publisher-templates/ppc-new-publishing-url.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_publishers");

if(!($user->validateUser()))
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$form=new Form("AddRestrictedSite","new-publishing-url-action.php");
$form->isNotNull("site",$template->checkmsg(6011));
$form->isNotNull("language",$template->checkmsg(9999));
$form->isNotNull("add",$template->checkmsg(9040));
$form->isDomain("site",$template->checkmsg(1030));
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{NAME}",'<input name="site" type="text" id="site" maxlength="100" size="40">');

$template->setValue("{DESCRIPTION}",'<textarea name="desc" id="desc" style="height:190px" cols="45"></textarea>');
 $client_lan=$_COOKIE['language'];
if($client_lan=="")
{
	$client_lan=$mysql->echo_one("select id from  adserver_languages where code='".$client_language."'");
}
else
{
	$client_lan=$_COOKIE['language'];
}

$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(7009)));
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


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     //
eval('?>'.$template->getPage().'<?php ');*/


include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");
$template=new Template();
$template->loadTemplate("publisher-templates/ppc-new-publishing-url.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_publishers");

if(!($user->validateUser()))
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}



$pass=false;
if(isset($_GET['pass']))
$pass="true";
else
	$pass="false";

$form=new Form("NewSite","");





$template->setValue("{PASSFLD}",$form->addHiddenField("pass",$pass));
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());





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


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     //
eval('?>'.$template->getPage().'<?php ');


?>