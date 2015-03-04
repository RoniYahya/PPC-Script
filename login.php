<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");



includeClass("Template");
includeClass("Form");
includeClass("User");
//include_once("messages.".$client_language.".inc.php");
$usr=new User("nesote_inoutscripts_users", "id");
if($usr->validateUser())
{
	header("Location:control-panel.php");
exit(0);
}
if($single_account_mode==0)
{
	

	header("location: index.php");
	exit;
	
}
if($portal_system==1)
{
	//redirect  this page to portal corrensponding page
}




$template=new Template();
$template->loadTemplate("common-templates/login.tpl.html");
$signup=$mysql->echo_one("select seoname from url_updation where name='signup'");
		if($signup=="")
		{
			$template->setValue("{COMREG}","registration.php");
		}
		else
		{
			$template->setValue("{COMREG}",$signup);
		}
		$forgot=$mysql->echo_one("select seoname from url_updation where name='forgotpassword'");
		if($forgot=="")
		{
			$forgot_pass="forgot-password.php";
		}
		else
		{
			$forgot_pass=$forgot;
		}
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$form=new Form("userLogin","login-action.php");

$form->isNotNull("username",$template->checkmsg(6001));
$form->isNotNull("password",$template->checkmsg(6002));

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
if($script_mode=="demo") 
$template->setValue("{USERFIELD}",$form->addTextBox("username","demouser"));
else
$template->setValue("{USERFIELD}",$form->addTextBox("username"));
if($script_mode=="demo") 
$template->setValue("{PASSFIELD}",$form->addPassword("password",25,"demouser"));
else
$template->setValue("{PASSFIELD}",$form->addPassword("password"));
if(($single_account_mode==1)&&($account_migration==1))
{
	$migrate="<select   name=\"radiobutton\" >".
	"<option value=\"1\" >".$template->checkComMsg(1063)."</option>".
	"<option value=\"2\"> ".$template->checkComMsg(1064)."</option>".
	"<option value=\"3\" checked> ".$template->checkComMsg(1067)."</option>".
	"</select>";
}
else
$migrate="";
$aa=str_replace("{ENAME}",$template->checkComMsg(0001),$template->checkComMsg(1097));
$template->setValue("{MESS10}",$aa);
$template->setValue("{BONUS}",$opening_bonus);
$template->setValue("{MIGRATE}",$migrate);
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkComMsg(1021)));
$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=2"));           
$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=2"));         
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
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$edtmess=$template->checkComMsg(1073);
$edtmess1=str_replace("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"",$edtmess);
$edtmess2=str_replace("{FORGOT}",$forgot_pass,$edtmess1);
$template->setValue("{EDTMESS}",$edtmess2);
$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);   
eval('?>'.$template->getPage().'<?php ');
?>