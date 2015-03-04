<?php

include("extended-config.inc.php");  

include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");
$template=new Template();
$template->loadTemplate("ppc-templates/ppc-user-add-funds.tpl.html");

$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$user=new User("ppc_users");

if(!($user->validateUser()))
{
header("Location:show-message.php?id=1006");
exit(0);
}

$form=new Form("AddMoney","ppc-user-add-funds-action.php");

$check=$advertiser_checkpayment; //$mysql->echo_one("select value from ppc_settings where name='advertiser_checkpayment'");
$name=$checkpayment_payeename;//$mysql->echo_one("select value from ppc_settings where name='checkpayment_payeename'");
$address=$checkpayment_payeeaddress;//$mysql->echo_one("select value from ppc_settings where name='checkpayment_payeeaddress'");
$bank=$advertiser_bankpayment;
$pay="";


 $template->setValue("{PAYEENAME}",$name);
 $emess500=$template->checkAdvMsg(8989);
$emess1500=str_replace("{PAYNAME1}",$name,$emess500);
$template->setValue("{EMESS501}",$emess1500);

if($advertiser_authpayment==1)
{
		$pay.="<input name=\"radiobutton\" id=\"pay_radio\" type=\"radio\" value=\"3\" checked onClick=\"show_Auth()\"> ".$template->checkAdvMsg(7036)."&nbsp;";
}
		if($advertiser_paypalpayment==1)
		
$pay.="<input  name=\"radiobutton\" type=\"radio\" value=\"1\"  onClick=\"show_paypal()\"> ".$template->checkAdvMsg(7012)."&nbsp;";

if($advertiser_checkpayment==1)
		$pay.="<input name=\"radiobutton\" type=\"radio\" value=\"0\" onClick=\"show_check()\"> ".$template->checkAdvMsg(7014)."&nbsp;";
if($advertiser_bankpayment==1)
		$pay.="<input name=\"radiobutton\" type=\"radio\" value=\"2\" onClick=\"show_bank()\"> ".$template->checkAdvMsg(7032)."&nbsp;";



 $template->setValue("{PAYMODE}",$pay);
// $msg="";
$min_msg="<span id=\"sys\" style=\"display:\">".$template->checkmsg(2006)." <br>(".$template->checkmsg(6083).")</span>";
if($local_currency_pay==1)
	{
	//$msg.=" ";
	$min_msg.="<span id=\"loc\" style=\"display:none\">".$template->checkmsg(2007)."<br>(".$template->checkmsg(6084).")</span>";
	}
$form->isPositive("amount",$template->checkmsg(6023));
//added on 28 auguest  2009
$template->setValue("{BANK_NAME}",$mysql->echo_one("select `value` from `admin_payment_details` where name='bank_name'"));
$template->setValue("{ACC_NO}",$mysql->echo_one("select `value` from `admin_payment_details` where name='bank_account_number'"));
$template->setValue("{BENEFICIARY_NAME}",$mysql->echo_one("select `value` from `admin_payment_details` where name='bank_beneficiaryname'"));
$template->setValue("{ROUTING_NO}",$mysql->echo_one("select `value` from `admin_payment_details` where name='routing_number'"));
$template->setValue("{ACCOUNT_TYPE}",$mysql->echo_one("select `value` from `admin_payment_details` where name='account_type'"));
$check_address=nl2br($mysql->echo_one("select `value` from `admin_payment_details` where name='bank_address'"));
$template->setValue("{BANK_ADDRESS}",$check_address);
$template->setValue("{BANK_CITY}",$mysql->echo_one("select `value` from `admin_payment_details` where name='bank_city'"));
$template->setValue("{BANK_PROVINCE}",$mysql->echo_one("select `value` from `admin_payment_details` where name='bank_province'"));
$template->setValue("{BANK_COUNTRY}",$mysql->echo_one("select `value` from `admin_payment_details` where name='bank_country'"));

$template->setValue("{PASSMESSAGE}",$template->checkmsg(8004));
//added on 28 auguest  2009

//$form->isOverMin("amount",$min_user_transaction_amount,$message[6024]);
$template->setValue("{MIN_MSG}",$min_msg);
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{AMOUNTFIELD}",$form->addTextBox("amount","",5,7));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(8881)));
$template->setValue("{MINAMOUNT}",$min_user_transaction_amount);

if($currency_format=="$$")
	  {
	  	
	  	$template->setValue("{CURRENCY}",$system_currency); 
	
	  }
	  else
	  {
	$template->setValue("{CURRENCY}",$currency_symbol); 
	
	  }

$template->setValue("{CHECKNO}",$form->addTextBox("checkno","",40,255));
$template->setValue("{MSG}",$msg);
$template->setValue("{SYSTEM}",$system_currency);

$template->setValue("{COUPONCODE}",$form->addTextBox("coupon","",25));


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
$emess5=$template->checkAdvMsg(8948);
$emess15=str_replace("{CURRENCY1}",$system_currency,$emess5);
$template->setValue("{EMESS5}",$emess15);
$emess50=$template->checkAdvMsg(8949);
$emess150=str_replace("{CURRENCY1}",$system_currency,$emess50);
$emess151=str_replace("{MINAMOUNT1}",$min_user_transaction_amount,$emess150);
$template->setValue("{EMESS51}",$emess151);
$template->setValue("{CHECKPAYMENT}",$advertiser_checkpayment);  

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
