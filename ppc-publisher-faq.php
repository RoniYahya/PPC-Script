<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");



includeClass("Template");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-faq.tpl.html");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$form=new Form("publisherLogin","ppc-publisher-login-action.php");
$advfaq=$mysql->echo_one("select seoname from url_updation where name='advfaq'");
		if($advfaq=="")
		{
			$template->setValue("{ADVFAQ}","ppc-advertiser-faq.php");
		}
		else
		{
			$template->setValue("{ADVFAQ}",$advfaq);
		}
		if($single_account_mode==1)
		{
		$pubreg=$mysql->echo_one("select seoname from url_updation where name='signup'");

		if($pubreg=="")
		{
			$template->setValue("{PUBREG}","registration.php");
			$regval="registration.php";
		}
		else
		{
			$template->setValue("{PUBREG}",$pubreg);
			$regval=$pubreg;
		}	
		}
		else
		{
		$pubreg=$mysql->echo_one("select seoname from url_updation where name='publishersignup'");

		if($pubreg=="")
		{
			$template->setValue("{PUBREG}","ppc-publisher-registration.php");
			$regval="ppc-publisher-registration.php";
		}
		else
		{
			$template->setValue("{PUBREG}",$pubreg);
			$regval=$pubreg;
		}
		}
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$template->setValue("{PERCENT}",$publisher_profit);
$template->setValue("{MINDOLLAR}",$min_publisher_acc_balance);
$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=1"));           
$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=1"));         

$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));   
$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8952));
$template->setValue("{ENGINE_TITLE}",$engine_title);

$program= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8953));
$template->setValue("{PROGRAM}",$program);

$msg2= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8954));
$template->setValue("{MSG2}",$msg2);

$msg3= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8955));
$template->setValue("{MSG3}",$msg3);

$msg4= str_replace("{CURRENCY_SYMBOL}",$currency_symbol,$template->checkPubMsg(8956));
$msg4= str_replace("{MINDOLLAR}",$min_publisher_acc_balance,$msg4);
$template->setValue("{MSG4}",$msg4);


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
$msg30= str_replace("{PUBREG}",$regval,$template->checkPubMsg(10126));
$msg31= str_replace("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"",$msg30);
$template->setValue("{MSG30}",$msg31);

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
