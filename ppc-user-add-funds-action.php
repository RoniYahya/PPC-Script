<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
//include_once("messages.".$client_language.".inc.php");
includeClass("Form");
$template=new Template();
$template->loadTemplate("ppc-templates/ppc-user-add-funds-action.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

 
$user=new User("ppc_users");
$paymentmode=$_POST['radiobutton'];

$pay=1;

if(!($user->validateUser()))
{
header("Location:show-message.php?id=1006");
exit(0);
}

$amount=$_POST['amount'];
phpSafe($amount);


$coupon=$_POST['coupon'];
phpSafe($coupon);

$userid=$user->getUserID();


//**********************************************************************************************************************************************

$coupon_name="";	
$coupon_amt=0;
if($coupon !="")
{

$coupon_name=$coupon;

$tm=time();
$count_coupon_id=$mysql->echo_one("select id from gift_code where coupon_code='$coupon' and status=1 and (no_times=0 or count < no_times) and expirydate > '$tm'");

if($count_coupon_id > 0)
{

$repeat_coupon=$mysql->echo_one("select count(*) from advertiser_bonus_deposit_history where couponid='$count_coupon_id' and aid='$userid'");

$repeat_coupon_one=$mysql->echo_one("select count(*) from advertiser_fund_deposit_history where uid='$userid' and coupon_id ='$count_coupon_id' and (status='0' or status='1')");



if($repeat_coupon > 0 || $repeat_coupon_one > 0)
{

header("Location:show-message.php?id=10001");
exit(0);

}




$coupon_type= $mysql->echo_one("select type from gift_code where id='$count_coupon_id'");
if($coupon_type ==1)
{
$perc_coupon=$mysql->echo_one("select amount from gift_code where id='$count_coupon_id' and coupon_code='$coupon'");

if($perc_coupon=="")
$perc_coupon=0;

$coupon_amt=($perc_coupon*$amount)/100;

}
else if($coupon_type ==2)
{
$coupon_amt=$mysql->echo_one("select amount from gift_code where id='$count_coupon_id' and coupon_code='$coupon'");
if($coupon_amt=="")
$coupon_amt=0;

}
}




if($coupon_amt == 0)
$coupon="";
else if($count_coupon_id <= 0)
$coupon="";
else if($count_coupon_id == "")
$coupon="";
else
$coupon=$count_coupon_id;




if($count_coupon_id <=0 || $count_coupon_id == "")
{
header("Location:show-message.php?id=10001");
exit(0);

}

}

//***********************************************************************************************************************************************










if($amount=='')
{
	header("Location:show-message.php?id=1001");
	exit(0);
}
if(!is_numeric($amount))
{
	header("Location:show-message.php?id=2003");
	exit(0);
}

$p_type=$_POST['p_type'];
if(($paymentmode==2 || $paymentmode==0 )&& $p_type==2)
	{
	$pay=0;
	if($amount<$min_local_currency_pay_amt)
		{
		header("Location:show-message.php?id=2004");
		exit(0);
		}
	}
else
	{
		if($amount<$min_user_transaction_amount)
		{
			header("Location:show-message.php?id=2004");
			exit(0);
		}
	}


$user_res=mysql_query("select firstname,lastname,domain,address,country,phone_no,email from ppc_users where uid=".$userid);
$urow=mysql_fetch_row($user_res);
/*$pay="<input type=\"radio\" value=\"1\" checked name=\"currency_type\"> $paypal_currency
                  <input  type=\"radio\" value=\"0\"  name=\"currency_type\">
                  Non $paypal_currency";

//$template->setValue("{CURRENCY}",$paypal_currency);
 $template->setValue("{PAYMODE}",$pay);*/

if($advertiser_authpayment==1)
{
//Authorize.net

$loginID		=  $authpaymentLoginid;
$transactionKey = $authpaymentTransactionKey;
$description 	= "Advertsier fund deposit";
$label 			= "Pay with Authorize.net"; // The is the label on the 'submit' button
$testMode		= "false";
// By default, this sample code is designed to post to our test server for
// developer accounts: https://test.authorize.net/gateway/transact.dll
// for real accounts (even in test mode), please make sure that you are
// posting to: https://secure.authorize.net/gateway/transact.dll
$url			= "https://secure.authorize.net/gateway/transact.dll";
//$url			= "https://test.authorize.net/gateway/transact.dll";
// an invoice is generated using the date and time
$invoice	= date(YmdHis);
// a sequence number is randomly generated
$sequence	= rand(1, 1000);
// a timestamp is generated
$timeStamp	= time ();

// The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
// newer have the necessary hmac function built in.  For older versions, it
// will try to use the mhash library.
if( phpversion() >= '5.1.2' )
{	$fingerprint = hash_hmac("md5", $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey); }
else 
{ $fingerprint = bin2hex(mhash(MHASH_MD5, $loginID . "^" . $sequence . "^" . $timeStamp . "^" . $amount . "^", $transactionKey)); }


$auth_form="<FORM method='post' action='".$url."' >
	<INPUT type='hidden' name='x_login' value='$loginID' />
<INPUT type='hidden' name='x_amount' value='$amount' />
<INPUT type='hidden' name='x_description' value='$description' />
<INPUT type='hidden' name='x_invoice_num' value='$invoice' />
<INPUT type='hidden' name='x_fp_sequence' value='$sequence' />
<INPUT type='hidden' name='x_fp_timestamp' value='$timeStamp' />
<INPUT type='hidden' name='x_fp_hash' value='$fingerprint' />
<INPUT type='hidden' name='x_test_request' value='$testMode' />
<INPUT type='hidden' name='x_show_form' value='PAYMENT_FORM' />
<INPUT type='hidden' name='x_relay_response' value='TRUE' />
<INPUT type='hidden' name='x_relay_url' value='".$server_dir."authorize-process.php' />";

if($coupon !="")
$auth_form.="<INPUT type='hidden' name='x_cust_id' value='".$userid."_".$coupon."' />";
else
$auth_form.="<INPUT type='hidden' name='x_cust_id' value='".$userid."' />";

$auth_form.="<INPUT type='hidden' name='x_first_name' value='".$urow[0]."' />
<INPUT type='hidden' name='x_last_name' value='".$urow[1]."' />
<INPUT type='hidden' name='x_company' value='".$urow[2]."' />
<INPUT type='hidden' name='x_country' value='".$mysql->echo_one("select name from location_country where code='".$urow[4]."'")."' />
<INPUT type='hidden' name='x_phone' value='".$urow[5]."' />
<INPUT type='hidden' name='x_email' value='".$urow[6]."' />
<input type='submit' value='$label' />
</FORM>";

//<INPUT type='hidden' name='x_address' value='".$urow[3]."' />

$template->setValue("{AUTHFORM}",$auth_form);
}

$paypal_form='<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="buy_paypal_PPC" id="buy_paypal_PPC">
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="item_name" value="'.$payapl_payment_item_escription.'">
	<input type="hidden" name="item_number" value="PPC">
	<input type="hidden" name="amount" value="'.$amount.'">
	<input type="hidden" name="currency_code" value="'.$paypal_currency.'">
	<input type="hidden" name="return" value="'.$server_dir.'show-success.php?id=2001">
	<input type="hidden" name="cancel_return" value="'.$server_dir.'show-message.php?id=2002">
	<input type="hidden" name="business" value="'.$paypal_email.'">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="no_note" value="0">';
	
if($coupon !="")	
$paypal_form.='<input type="hidden" name="custom" value="'.$user->getUserID().'_'.$coupon.'">';
else
$paypal_form.='<input type="hidden" name="custom" value="'.$user->getUserID().'">';	
	
	
	
	$paypal_form.='<input type="hidden" name="notify_url" value="'.$server_dir.'ppc-user-ipn.php">
	<button type="submit" name="submit" class="button">Pay with PayPal</button>
</form>';



$template->setValue("{AMOUNT}",$amount);


$template->setValue("{COUPON}",$coupon);                                 

if($currency_format=="$$")
{
	if($paymentmode==1)
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

$template->setValue("{PAYPALFORM}",$paypal_form);
$form=new Form("AddMoney","ppc-user-add-funds-check.php");

 

$form->isPositive("amount",$template->checkmsg(6023));
$form->isOverMin("amount",$min_user_transaction_amount,$template->checkmsg(6024));

$form->isNotNull("checkno",$template->checkmsg(6062));
$form->isNotNull("bankname",$template->checkmsg(6063));
$form->isNotNull("bankaddress",$template->checkmsg(6082));
$form->isNotNull("accountholdersname",$template->checkmsg(6064));

$form->isNotNull("swift",$template->checkmsg(6087));
$form->isNotNull("city",$template->checkmsg(6085));
$form->isNotNull("country",$template->checkmsg(6086));


// added on 25/08/2009


$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());

//added on 28/08/2009
$template->setValue("{MODE}",$paymentmode);
if($paymentmode==0)
	{
	$template->setValue("{MSG_NO}",$template->checkAdvMsg(7033));    
	$template->setValue("{TRANSFER_TYPE}",$form->addHiddenField("transfer_type",1));//check payment
	}
if($paymentmode==2)
	{
	$template->setValue("{MSG_NO}",$template->checkAdvMsg(7034));    
	$template->setValue("{TRANSFER_TYPE}",$form->addHiddenField("transfer_type",2));//bank payment
	}
$template->setValue("{CITY}",$form->addTextBox("city","",40,255));
$template->setValue("{COUNTRY}",$form->addTextBox("country","",40,255));
$template->setValue("{SWIFT}",$form->addTextBox("swift","",40,255));
$template->setValue("{COMMENT}",$form->addTextArea("comment","",46,8,''));
//added on 28/08/2009

$template->setValue("{AMOUNTFIELD}",$form->addHiddenField("amount","$amount"));





if($coupon !="")	
$template->setValue("{COUPONFIELD}",$form->addHiddenField("coupon","$coupon"));
else
$template->setValue("{COUPONFIELD}","");


$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(8881)));
$template->setValue("{CHECKNO}",$form->addTextBox("checkno","",40,255));
$template->setValue("{BANK}",$form->addTextBox("bankname","",40,255));
// added on 25/08/2009
// addTextArea($name,$display_value="",$size=25,$lines=5,$max="2500")
 $template->setValue("{BANKADD}",$form->addTextArea("bankaddress","",46));
 // added on 25/08/2009
$template->setValue("{NAME}",$form->addTextBox("accountholdersname","",40,255));
$template->setValue("{PAYMODE}",$form->addHiddenField("currency_type","$pay"));
//$template->setValue("{TABS}",$template->includePage($server_dir."ppc-page-header.php"));
$emess=$template->checkAdvMsg(8946);
$emess1=str_replace("{CURRENCY1}",$csymbol,$emess);
$emess2=str_replace("{AMOUNT1}",$amount,$emess1);
$template->setValue("{EMESS}",$emess2);
$emess5=$template->checkAdvMsg(8947);
$emess15=str_replace("{COUPEN_CODE}",$coupon_name,$emess5);
$template->setValue("{EMESS5}",$emess15);
$template->setValue("{ENGINE_NAME}",$ppc_engine_name);                                               
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


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');
?>
