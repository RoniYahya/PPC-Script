<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

//includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("common-templates/forgot-password.tpl.html");
//$user=new User("ppc_users");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");
if($single_account_mode==0)
{
	

	header("location: index.php");
	exit;
	
}
if($portal_system==1)
{
	//redirect  this page to portal corrensponding page
}

$form=new Form("EditPassword","forgot-password-action.php");

$form->isNotNull("username",$template->checkmsg(6001));
 
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{USERFIELD}",$form->addTextBox("username","",40,255));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkComMsg(1040)));
if(($single_account_mode==1)&&($account_migration==1))
{
	$migrate="<input id=\"account_radio\" name=\"radiobutton\" type=\"radio\" value=\"1\" >".$template->checkComMsg(1083)."&nbsp;
					    <input name=\"radiobutton\" type=\"radio\" value=\"2\"> ".$template->checkComMsg(1084)."&nbsp;<input name=\"radiobutton\" type=\"radio\" value=\"3\" checked> ".$template->checkComMsg(1067);
}
else
$migrate=" ";
//$template->setValue("{TABS}",$template->includePage($server_dir."ppc-page-header.php"));
$template->setValue("{MIGRATE}",$migrate);
$template->setValue("{MIGRATE_STATUS}",$account_migration);
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
$edtmess=$template->checkComMsg(1072);
$edtmess1=str_replace("{ENAME}",$template->checkComMsg(0001),$edtmess);
$template->setValue("{EDTMESS}",$edtmess1);
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>