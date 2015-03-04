<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");



includeClass("Template");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("ppc-templates/ppc-advertiser-faq.tpl.html");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$template->setValue("{PERCENT}",$publisher_profit);
$template->setValue("{MINDOLLAR}",$min_publisher_acc_balance);
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
$pubfaq=$mysql->echo_one("select seoname from url_updation where name='pubfaq'");
		if($pubfaq=="")
		{
			$template->setValue("{PUBFAQ}","ppc-publisher-faq.php");
		}
		else
		{
			$template->setValue("{PUBFAQ}",$pubfaq);
		}
		
		if($single_account_mode==1)
		{
			
		$advreg=$mysql->echo_one("select seoname from url_updation where name='signup'");

		if($advreg=="")
		{
			$template->setValue("{ADVREG}","registration.php");
			$adv_regurl="registration.php";
		}
		else
		{
			$template->setValue("{ADVREG}",$advreg);
			$adv_regurl=$advreg;
		}
			
			
			
		}
		else
		{
		$advreg=$mysql->echo_one("select seoname from url_updation where name='advertisersignup'");

		if($advreg=="")
		{
			$template->setValue("{ADVREG}","ppc-user-registration.php");
			$adv_regurl="ppc-user-registration.php";
		}
		else
		{
			$template->setValue("{ADVREG}",$advreg);
			$adv_regurl=$advreg;
		}
		}
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$emess=$template->checkAdvMsg(8979);
$emess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$emess);
$template->setValue("{EMESS1}",$emess1); 
$emess3=$template->checkAdvMsg(8980);
$emess4=str_replace("{ENAME}",$template->checkAdvMsg(0001),$emess3);
$template->setValue("{EMESS4}",$emess4); 
$emess30=$template->checkAdvMsg(8981);
$emess31=str_replace("{ENAME}",$template->checkAdvMsg(0001),$emess30);
$emess32=str_replace("{ADVREG}",$adv_regurl,$emess31);
$emess33=str_replace("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"",$emess32);
$template->setValue("{EMESS310}",$emess33);
$edtmess=$template->checkAdvMsg(8982);
$edtmess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$edtmess);
$template->setValue("{EDTMESS}",$edtmess1);
$edtmess5=$template->checkAdvMsg(8983);
$edtmess15=str_replace("{ENAME}",$template->checkAdvMsg(0001),$edtmess5);
$template->setValue("{EDTMESS1}",$edtmess15);
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
$template->setValue("{SINGLE}",$single_account_mode);  
eval('?>'.$template->getPage().'<?php ');

?>