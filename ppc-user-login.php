<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");



includeClass("Template");

includeClass("Form");

includeClass("User");
//include_once("messages.".$client_language.".inc.php");
$usr=new User("ppc_users");
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
if($usr->validateUser())
{
	header("Location:ppc-user-control-panel.php");
exit(0);
}

$template=new Template();
$template->loadTemplate("ppc-templates/ppc-user-login.tpl.html");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$form=new Form("userLogin","ppc-user-login-action.php");

$form->isNotNull("username",$template->checkmsg(6001));
$form->isNotNull("password",$template->checkmsg(6002));

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
if($script_mode=="demo") 
$template->setValue("{USERFIELD}",$form->addTextBox("username","advertiser"));
else
$template->setValue("{USERFIELD}",$form->addTextBox("username"));
if($script_mode=="demo") 
$template->setValue("{PASSFIELD}",$form->addPassword("password",25,"advertiser"));
else
$template->setValue("{PASSFIELD}",$form->addPassword("password"));
$template->setValue("{BONUS}",$opening_bonus);
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(9999)));
$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=0"));           
$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=0"));         
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
		}
		else
		{
			$template->setValue("{COMLOGIN}",$signin);
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
$advreg=$mysql->echo_one("select seoname from url_updation where name='advertisersignup'");

		if($advreg=="")
		{
			$template->setValue("{ADVREG}","ppc-user-registration.php");
		}
		else
		{
			$template->setValue("{ADVREG}",$advreg);
		}
			
		$advforgot=$mysql->echo_one("select seoname from url_updation where name='advertiserforgotpassword'");

		if($advforgot=="")
		{
			$template->setValue("{ADVFORGOT}","ppc-forgot-password.php");
		}
		else
		{
			$template->setValue("{ADVFORGOT}",$advforgot);
		}
}		
$advlogin=$mysql->echo_one("select seoname from url_updation where name='publishers'");
		if($advlogin=="")
		{
			$template->setValue("{PUBLOGIN}","ppc-publisher-login.php");
		}
		else
		{
			$template->setValue("{PUBLOGIN}",$advlogin);
		}
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
    $hmess=$template->checkAdvMsg(8928);  
$hmess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$hmess); 
      $template->setValue("{HMESS}",$hmess1);
$hmess5=$template->checkAdvMsg(8929);  
$hmess6=str_replace("{ENAME}",$template->checkAdvMsg(0001),$hmess5); 
      $template->setValue("{HMESS6}",$hmess6);
      $hmess8=$template->checkAdvMsg(8930);  
$hmess9=str_replace("{ENAME}",$template->checkAdvMsg(0001),$hmess8); 
      $template->setValue("{HMESS9}",$hmess9);
$template->setValue("{PAGEWIDTH}",$page_width);  
   $template->setValue("{ENCODING}",$ad_display_char_encoding);   
 $hmess10=$template->checkAdvMsg(8931);  
$hmess11=str_replace("{CURRENCY1}",$currency_symbol,$hmess10); 
$hmess12=str_replace("{AMOUNT1}",$opening_bonus,$hmess11); 
      $template->setValue("{HMESS11}",$hmess12);
          $hmess15=$template->checkAdvMsg(8932);  
$hmess16=str_replace("{ENAME}",$template->checkAdvMsg(0001),$hmess15); 
      $template->setValue("{HMESS16}",$hmess16);

      
       $hmess20=$template->checkAdvMsg(8933);  
$hmess21=str_replace("{ENAME}",$template->checkAdvMsg(0001),$hmess20); 
      $template->setValue("{HMESS21}",$hmess21);
eval('?>'.$template->getPage().'<?php ');
?>