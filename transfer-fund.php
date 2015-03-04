<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");

if($single_account_mode==0)
{
	if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-user-control-panel.php");
exit(0);	
	}
	else
	{
	header("Location: ppc-publisher-control-panel.php");
exit(0);	
	}
}

$template=new Template();
$template->loadTemplate("publisher-templates/transfer-fund.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$user=new User("ppc_publishers");
$uid=$user->getUserID();
$comid=$mysql->echo_one("select common_account_id from ppc_publishers where uid='$uid'");
if($single_account_mode==1 && $comid==0)
{
	if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-user-control-panel.php");
exit(0);	
	}
	else
	{
	header("Location: ppc-publisher-control-panel.php");
exit(0);	
	}
}
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}


$form=new Form("transfer","transfer-fund-action.php");
$form->isNotNull("amount",$template->checkmsg(6036));
$form->isPositive("amount",$template->checkmsg(6023));
$template->setValue("{AMOUNT}",'<input name="amount" type="text" size="10">');

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(8895)));

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
$account_bal=$mysql->echo_one("select accountbalance from ppc_publishers where uid='$uid'");
$account_bal=moneyFormat($account_bal);
$msg50= str_replace("{ACCOUNT}",$account_bal,$template->checkPubMsg(10130));
$template->setValue("{MSG500}",$msg50);
if($currency_format=="$$")
{
$template->setValue("{CURRENCY}",$system_currency);	
$csymbol=$system_currency;
}
else
{
$template->setValue("{CURRENCY}",$currency_symbol);	
	$csymbol=$system_currency;
}
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$engine_title12= str_replace("{CURRENCY1}",$csymbol,$template->checkPubMsg(8989));
$engine_title102= str_replace("{MINMUM1}",$min_publisher_acc_balance,$engine_title12);
$template->setValue("{MSG102}",$engine_title102);

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$publisher_message[8980]);
//$template->setValue("{ENGINE_TITLE}",$engine_title);

eval('?>'.$template->getPage().'<?php ');
?>
