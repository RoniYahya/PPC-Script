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

$template->loadTemplate("ppc-templates/confirm-publisher-account.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();
$comid=$mysql->echo_one("select common_account_id from ppc_users where uid='$uid'");
if($comid!=0)
{
header("Location:ppc-user-control-panel.php");
exit(0);	
}
$form=new Form("userLogin","ppc-single-account.php");


$template->setValue("{USERFIELD}",'<input type="text" name="username" id="un" onblur="show()">');
$template->setValue("{PASSFIELD}",$form->addPassword("password"));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(8883)));


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
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));  

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
eval('?>'.$template->getPage().'<?php ');

?>