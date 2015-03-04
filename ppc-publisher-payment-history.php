<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

//include("messages.".$client_language.".inc.php");
includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");
$template=new Template();

$template->loadTemplate("publisher-templates/ppc-publisher-payment-history.tpl.html");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();


//$template=new Template();
//
//$template->loadTemplate("publisher-templates/ppc-publisher-payment-history.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$pageno=1;
if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
$perpagesize = 10;


$total=$mysql->echo_one("select count(*) from ppc_publisher_payment_hist  where uid='$uid'");
//echo $total;
$p=$paging->page($total,$perpagesize,"","ppc-publisher-payment-history.php");
//echo $p;
$template->setValue("{HEADER}","$p");
$string="";
$beg=(($pageno-1)*$perpagesize+1);
if($total>=1) 
{

 if(($pageno*$perpagesize)<=$total) 
  $end=$pageno*$perpagesize;
  else 
  $end=$total;

}

$combined_msg=$template->checkPubMsg(8935);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{PAY_HIST}",$msg_replace2);
$template->openLoop("ADS","select amount,currency,status,reqdate,paymode,id   from ppc_publisher_payment_hist  where uid='$uid' order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
//$rdate=date("F j, Y, g:i a",reqdate);   
$template->setLoopField("{LOOP(ADS)-AMOUNT}","amount");
//$template->setLoopField("{LOOP(ADS)-CURRENCY}","currency");
$template->setLoopField("{LOOP(ADS)-STATUS}","status");
$template->setLoopField("{LOOP(ADS)-TIME}","reqdate");
$template->setLoopField("{LOOP(ADS)-TYPE}","paymode");
$template->setLoopField("{LOOP(ADS)-ID}","id");

//$template->setLoopField("{LOOP(ADS)-SUMMARY}","summary");

$template->closeLoop();

if($currency_format=="$$")
$template->setValue("{CURRENCY}",$system_currency);
else 
$template->setValue("{CURRENCY}",$currency_symbol);
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
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
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8966));
$template->setValue("{ENGINE_TITLE}",$engine_title);


$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding);     
eval('?>'.$template->getPage().'<?php ');

?>
