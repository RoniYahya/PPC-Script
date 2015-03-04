<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("ppc-templates/ppc-change-password.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_users");
 $advid=$user->getUserID();
$commonid=$mysql->echo_one("select common_account_id from ppc_users where uid=$advid");
if(($commonid!=0))
{
	if($_COOKIE['io_type']==md5("Common"))
	{				
header("Location: change-password.php");
exit(0);	
	}

	
}
if($user->getUsername()=="advertiser" && $script_mode=="demo")
{
header("Location:show-message.php?id=6076");
exit(0);
}

if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}


$form=new Form("EditPassword","ppc-change-password-action.php");
$form->isNotNull("oldpass", $template->checkmsg(6016));
$form->isNotNull("newpass", $template->checkmsg(6017));
$form->isNotNull("newpass2", $template->checkmsg(6018));
$form->isNotShort("newpass",$min_user_password_length, $template->checkmsg(6019));
$form->isNotShort("newpass2",$min_user_password_length,$template->checkmsg(6020));
$form->isSame("newpass","newpass2",$template->checkmsg(6021));


$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{OLDPASS}",$form->addPassword("oldpass",40));
$template->setValue("{NEWPASS}",$form->addPassword("newpass",40));
$template->setValue("{NEWPASS2}",$form->addPassword("newpass2",40));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(7007)));

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
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
$emess=$template->checkAdvMsg(8967);
$emess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$emess);
$template->setValue("{EMESS12}",$emess1);

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
