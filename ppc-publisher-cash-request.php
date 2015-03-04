<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-cash-request.tpl.html");
includeClass("User");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");

$user=new User("ppc_publishers");

if(!($user->validateUser()))
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$url=urlencode($_SERVER['REQUEST_URI']);

$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$user_id=$user->getUserID();
$accountbalance = $mysql->echo_one("select accountbalance from ppc_publishers where uid =".$user_id);
//added  22/08/2009
$settlement_amt=round($mysql->echo_one("select COALESCE(sum(publisher_profit),0) from `ppc_daily_clicks` where  pid=".$user_id),2);
//$template->setValue("{SETTLMENT}",$settlement_amt);
$accountbalance=$accountbalance-$settlement_amt;
//added  22/08/2009
if($accountbalance<$min_publisher_acc_balance)
{
header("Location:publisher-show-message.php?id=6044");
exit(0);
}
$paymode = $mysql->echo_one("select paymentmode from ppc_publisher_payment_details where uid =".$user_id);



if($paymode==1)
{
	$mode=$publisher_paypalpayment;
}
elseif($paymode==0)
{
	$mode=$publisher_checkpayment;
}
else
{
	$mode=$publisher_bankpayment;
}
if($mode!=1)
{
header("Location:publisher-show-message.php?id=6047");
exit(0);	
}else
{

$paypalemail= $mysql->echo_one("select paypalemail from ppc_publisher_payment_details where uid =".$user_id);
$payeename= $mysql->echo_one("select payeename from ppc_publisher_payment_details where uid =".$user_id);
//if($paymode==1)
//{
//if($paypalemail=="")
//{
//header("Location:publisher-show-message.php?id=6047");
//exit(0);
//}
//
//}
//else
//{
//if( $payeename=="")
//{
//header("Location:publisher-show-message.php?id=6047");
//exit(0);
//}
//}
$CURRENCY = $mysql->echo_one("select value from ppc_settings where name ='paypal_currency'");
$form=new Form("ConfigureRequestDetails","ppc-publisher-cash-request-action.php");
$form->isNotNull("amount",$template->checkmsg(6036));
$form->isPositive("amount",$template->checkmsg(6023));
$form->isOverMin("amount",$min_publisher_acc_balance,$template->checkmsg(6024));
$template->setValue("{AMOUNT}",'<input name="amount" type="text" size="10">');


if($paymode==1)
{
	$template->setValue("{MODE}",$template->checkPubMsg(7012));
	$template->setValue("{MINIMUM}",$min_publisher_acc_balance);
	$template->setValue("{TEXT}",$template->checkPubMsg(7013));
	$template->setValue("{DETAILS}",$paypalemail);
         $template->setValue("{ADDRESS}","");         
}
else
{
	if($paymode==2)
		{
		 $bank_info="<br>";
		$template->setValue("{MODE}",$template->checkPubMsg(7032));
		//
		$template->setValue("{MINIMUM}",$min_publisher_acc_balance);
	$template->setValue("{TEXT}",$template->checkPubMsg(7015));
	$template->setValue("{DETAILS}",$payeename);
	$raddress=mysql_query("select bank_name,bank_address,acc_type,acc_no,routing_no,b_city,b_state,b_country,b_zip from ppc_publisher_payment_details where uid=".$user_id);
	$addrow=mysql_fetch_row($raddress);
	$bank_info.="$addrow[5]"."<br>";
	$bank_info.="$addrow[6]"."<br>";
	$bank_info.="$addrow[7]"."<br>";
	$bank_info.="$addrow[8]"."<br>";
	$template->setValue("{BNAME}",$addrow[0]);
	$template->setValue("{ACTYPE}",$addrow[2]);
	$template->setValue("{ACNO}",$addrow[3]);
	$template->setValue("{RNO}",$addrow[4]);
	$addressvar="";
	
	if($addrow[1]!="")
	$addressvar.="".nl2br($addrow[1]).$bank_info."<br>";
	
	$template->setValue("{ADDRESS}","<tr>
            <td valign=\"top\">".$template->checkPubMsg(7035) ." </td>
			
            <td>".$addressvar."</td>
          </tr>");
		}
	else
		{
			$template->setValue("{MODE}",$template->checkPubMsg(7014));
			
	$template->setValue("{MINIMUM}",$min_publisher_acc_balance);
	$template->setValue("{TEXT}",$template->checkPubMsg(7015));
	$template->setValue("{DETAILS}",$payeename);
	$raddress=mysql_query("select address1,address2,city,state,country,zip from ppc_publisher_payment_details where uid=".$user_id);
	$addrow=mysql_fetch_row($raddress);
	$addressvar="";
	if($addrow[0]!="")
	$addressvar.=$addrow[0]."<br>";
	if($addrow[1]!="")
	$addressvar.=$addrow[1]."<br>";
	if($addrow[2]!="")
	$addressvar.=$addrow[2]."<br>";
	if($addrow[3]!="")
	$addressvar.=$addrow[3]."<br>";
	if($addrow[4]!="")
	$addressvar.=$addrow[4]."<br>";
	if($addrow[5]!="")
	$addressvar.=$addrow[5]."<br>";
	
	$template->setValue("{ADDRESS}","<tr>
            <td valign=\"top\">".$template->checkPubMsg(7016). "</td>
			
            <td>".$addressvar."</td>
          </tr>");
	}
	
}				  
//$template->setValue("{PAYDETAILS}",$form->addTextBox("paydetails",$mysql->echo_one("select paymentdetails  from ppc_publishers where uid=".$user_id),40,255));

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(7011)));

}
if($currency_format=="$$")
{
	if($paymode==1)
	{
$template->setValue("{CURRENCY}",$paypal_currency);
$csymbol=$paypal_currency;
	}
	else
	{
	$template->setValue("{CURRENCY}",$system_currency);	
	$csymbol=$system_currency;
	}

}
else
{
$template->setValue("{CURRENCY}",$currency_symbol);
$csymbol=$currency_symbol;
}
//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));   

$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8944));
$template->setValue("{ENGINE_TITLE}",$engine_title);

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

$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8944));
$template->setValue("{ENGINE_TITLE}",$engine_title);

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
$template->setValue("{URL}",$url);

$str_link= str_replace("{URL}",$url,$template->checkPubMsg(8945));
$str_link= str_replace("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"",$str_link);
$template->setValue("{STR_LINK}",$str_link);
$engine_title12= str_replace("{CURRENCY1}",$csymbol,$template->checkPubMsg(8989));
$engine_title102= str_replace("{MINMUM1}",$min_publisher_acc_balance,$engine_title12);
$template->setValue("{MSG102}",$engine_title102);

eval('?>'.$template->getPage().'<?php ');

?>
