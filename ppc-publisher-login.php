<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");



includeClass("Template");
includeClass("Form");
includeClass("User");
//include_once("messages.".$client_language.".inc.php");
$usr=new User("ppc_publishers");
$template=new Template();
//if($single_account_mode==1)
//{
//if($_COOKIE['io_type']==md5("advertiser"))
//	{				
//header("Location: ppc-user-control-panel.php");
//exit(0);	
//	}
//	elseif($_COOKIE['io_type']==md5("publisher"))
//	{
//	header("Location: ppc-publisher-control-panel.php");
//exit(0);	
//	}
//	else
//	{
//		header("Location: login.php");
//exit(0);
//	}
//}
$template->loadTemplate("publisher-templates/ppc-publisher-login.tpl.html");
if($usr->validateUser())
{
	header("Location:ppc-publisher-control-panel.php");
exit(0);
}


$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$form=new Form("publisherLogin","ppc-publisher-login-action.php");

//$form->isNotNull("username",$template->checkmsg(6001));
//$form->isNotNull("password",$template->checkmsg(6002));
$template->setValue("{PERCENT}",$mysql->echo_one("select value from ppc_settings where name='publisher_profit'"));
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
if($script_mode=="demo") 
$template->setValue("{USERFIELD}",$form->addTextBox("username","publisher"));
else
$template->setValue("{USERFIELD}",$form->addTextBox("username"));
if($script_mode=="demo") 
$template->setValue("{PASSFIELD}",$form->addPassword("password",25,"publisher"));
else
$template->setValue("{PASSFIELD}",$form->addPassword("password"));

$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(1360)));

$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=1"));           
$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=1"));   
      

$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  

//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8959));
$template->setValue("{ENGINE_TITLE}",$engine_title);
 
$msg1= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8960));
$msg1= str_replace("{PERCENT}",$mysql->echo_one("select value from ppc_settings where name='publisher_profit'"),$msg1);
$template->setValue("{MSG1}",$msg1);
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
if($single_account_mode==1)
{
$signup=$mysql->echo_one("select seoname from url_updation where name='signup'");

		if($signup=="")
		{
			$template->setValue("{COMREG}","registration.php");
		}
		else
		{
			$template->setValue("{COMREG}",$signup);
		}
		$signin=$mysql->echo_one("select seoname from url_updation where name='signin'");
		if($signin=="")
		{
			$template->setValue("{COMLOGIN}","login.php");
			$loginvalue="login.php";
		}
		else
		{
			$template->setValue("{COMLOGIN}",$signin);
			$loginvalue=$signin;
		}
		
		
$comforgot=$mysql->echo_one("select seoname from url_updation where name='forgotpassword'");
		if($signin=="")
		{
			$template->setValue("{COMFORGOT}","forgot-password.php");
		}
		else
		{
			$template->setValue("{COMFORGOT}",$comforgot);
		}
}
else
{
$publogin=$mysql->echo_one("select seoname from url_updation where name='publishers'");
		if($publogin=="")
		{
			$template->setValue("{PUBLOGIN}","ppc-publisher-login.php");
		}
		else
		{
			$template->setValue("{PUBLOGIN}",$publogin);
		}	
		$pubforgot=$mysql->echo_one("select seoname from url_updation where name='publisherforgotpassword'");

		if($pubforgot=="")
		{
			$template->setValue("{PUBFORGOT}","ppc-publisher-forgot-password.php");
		}
		else
		{
			$template->setValue("{PUBFORGOT}",$pubforgot);
		}
	
$pubreg=$mysql->echo_one("select seoname from url_updation where name='publishersignup'");

		if($pubreg=="")
		{
			$template->setValue("{PUBREG}","ppc-publisher-registration.php");
		}
		else
		{
			$template->setValue("{PUBREG}",$pubreg);
		}	
}
	$advlogin=$mysql->echo_one("select seoname from url_updation where name='advertisers'");
		if($advlogin=="")
		{
			$template->setValue("{ADVLOGIN}","ppc-user-login.php");
		}
		else
		{
			$template->setValue("{ADVLOGIN}",$advlogin);
		}
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$edtmess= str_replace("{ENAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8985));
$template->setValue("{MSG10}",$edtmess);
 $edtmess1= str_replace("{ENAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8986));
$template->setValue("{MSG11}",$edtmess1);
 $edtmess12= str_replace("{ENAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8987));
$template->setValue("{MSG12}",$edtmess12);
 $edtmess102= str_replace("{ENAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8988));
$template->setValue("{MSG102}",$edtmess102);

$edtmess500= str_replace("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"",$template->checkPubMsg(10251));
$edtmess501= str_replace("{COMLOGIN}",$loginvalue,$edtmess500);
$template->setValue("{MSG100}",$edtmess501);


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
