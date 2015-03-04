<?php
/*echo "<br><br>Success";

echo "<br><br>Request Params<br>";
print_r($_REQUEST);

echo "<br><br>Get Params<br>";
print_r($_GET);

echo "<br><br>Post Params<br>";
print_r($_POST);
*/

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
//include_once("messages.".$client_language.".inc.php");	
includeClass("User");
$template=new Template();
$template->loadTemplate("ppc-templates/authorize-process.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$template->setValue("{SERVER_DIR}",$server_dir);

$user=new User("ppc_users");

$x_response_code=$_POST["x_response_code"];
$x_response_subcode=$_POST["x_response_subcode"];
$x_response_reason_code=$_POST["x_response_reason_code"];
$x_response_reason_text=$_POST["x_response_reason_text"];
$x_auth_code=$_POST["x_auth_code"];
//$x_avs_code=$_POST["x_avs_code"];
$x_trans_id=$_POST["x_trans_id"];
$x_invoice_num=$_POST["x_invoice_num"];
$x_description=$_POST["x_description"];
$x_amount=$_POST["x_amount"];
$x_method=$_POST["x_method"];
$x_type=$_POST["x_type"];


$x_cust_coupon="";
$x_cust_id=$_POST["x_cust_id"];

/**************************************************************/
//$x_cust_id='1_3';
/**************************************************************/
$x_cust_id_data=explode('_',$x_cust_id);


$x_cust_id=$x_cust_id_data[0];
if(count($x_cust_id_data) >1)
{
$x_cust_coupon=$x_cust_id_data[1];
}



$x_first_name=$_POST["x_first_name"];
$x_last_name=$_POST["x_last_name"];
$x_company=$_POST["x_company"];
$x_address=$_POST["x_address"];
$x_city=$_POST["x_city"];
$x_state=$_POST["x_state"];
$x_zip=$_POST["x_zip"];
$x_country=$_POST["x_country"];
$x_phone=$_POST["x_phone"];
$x_fax=$_POST["x_fax"];
$x_email=$_POST["x_email"];

/*$x_ship_to_first_name=$_POST["x_ship_to_first_name"];
$x_ship_to_last_name=$_POST["x_ship_to_last_name"];
$x_ship_to_company=$_POST["x_ship_to_company"];
$x_ship_to_address=$_POST["x_ship_to_address"];
$x_ship_to_city=$_POST["x_ship_to_city"];
$x_ship_to_state=$_POST["x_ship_to_state"];
$x_ship_to_zip=$_POST["x_ship_to_zip"];
$x_ship_to_country=$_POST["x_ship_to_country"];
$x_tax=$_POST["x_tax"];
$x_duty=$_POST["x_duty"];
$x_freight=$_POST["x_freight"];
$x_tax_exempt=$_POST["x_tax_exempt"];
$x_po_num=$_POST["x_po_num"];*/

$x_MD5_Hash=$_POST["x_MD5_Hash"];

//$x_cavv_response=$_POST["x_cavv_response"];

$x_test_request=$_POST["x_test_request"];

//$uid=$_POST["uid"];

//$x_method_available=$_POST["x_method_available"];
 

$string_to_hash = $authSecretCode.$authpaymentLoginid.$x_trans_id.$_POST["x_amount"];
$check_key = md5($string_to_hash);
//echo "<br>".$string_to_hash." = $check_key<br>";

/*****************************************************
$x_response_code=1;
$x_test_request=false;
$check_key=$x_MD5_Hash;
$x_amount=10;

/*****************************************************/
$tran_status="";
if($x_response_code==1)
$tran_status="Approved";
if($x_response_code==2)
$tran_status="Declined";
if($x_response_code==3)
$tran_status="Error";
if($x_response_code==4)
$tran_status="On Hold";

$result_table="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" align=\"left\">
<tr>
<td width=\"50%\">Authorize.net transaction id </td>
<td width=\"2%\"><strong>:</strong></td>
<td width=\"48%\">$x_trans_id</td>
</tr>
<tr>
<td>Invoice number </td>
<td><strong>:</strong></td>
<td>$x_invoice_num</td>
</tr>
<tr>
<td>Total Amount </td>
<td><strong>:</strong></td>
<td>$x_amount</td>
</tr>
<tr>
<td>Transaction Status </td>
<td><strong>:</strong></td>
<td>$tran_status</td>
</tr>
</table>";


$payer_username=$mysql->echo_one("select ".$user->username_field." from ".$user->user_table." where ".$user->uid_field."='$x_cust_id'");
$payer_email=$mysql->echo_one("select email from ppc_users where uid='$x_cust_id'");
if($x_test_request=="true")
{
					 $template->setValue("{MESSAGE}",$template->checkAdvMsg(2012));
					 $template->setValue("{ERROR}","");
					 $template->setValue("{RESULT}","$result_table");

}
elseif(strcasecmp(  $check_key,$x_MD5_Hash)==0)
{
	if($x_response_code==1)
	{
	
	
	$coupon_amt=0;
	if($x_cust_coupon !="" && $x_cust_coupon !=0)
	{


$count_coupon_id=$x_cust_coupon;



$tm=time();
$count_coupon_id=$mysql->echo_one("select id from gift_code where id='$count_coupon_id' and status=1 and (no_times=0 or count < no_times) and expirydate > '$tm'");

if($count_coupon_id > 0)
{
  $already_used_coupon1=$mysql->echo_one("select id from advertiser_bonus_deposit_history where aid='$x_cust_id' and couponid='$count_coupon_id' limit 0,1");
  $already_used_coupon2=$mysql->echo_one("select id from advertiser_fund_deposit_history where uid='$x_cust_id' and coupon_id ='$count_coupon_id' and (status='0' or status='1')");
	if($already_used_coupon1=='' && $already_used_coupon2=='') 
	{

		$coupon_type= $mysql->echo_one("select type from gift_code where id='$count_coupon_id'");
		if($coupon_type ==1)
		{
			$perc_coupon=$mysql->echo_one("select amount from gift_code where id='$count_coupon_id'");
			
			if($perc_coupon=="")
				$perc_coupon=0;
			
			$coupon_amt=($perc_coupon*$x_amount)/100;
		
		}
		else if($coupon_type ==2)
		{
			$coupon_amt=$mysql->echo_one("select amount from gift_code where id='$count_coupon_id'");
			if($coupon_amt=="")
				$coupon_amt=0;
			
		}

	}
}

	
	
	
	
	}
	
	
	
	
	
	
	
			$str="";
			if(mysql_query("insert into authorize_ipn values('', '$x_response_code','$x_response_subcode', '$x_response_reason_code', '$x_response_reason_text', '$x_auth_code', '$x_trans_id', '$x_invoice_num', '$x_description', '$x_amount', '$x_method','$x_type', '$x_cust_id', '$x_first_name', '$x_last_name', '$x_company', '$x_address', '$x_city', '$x_state', '$x_zip', '$x_country', '$x_phone', '$x_fax','$x_email','$x_MD5_Hash',NOW())"
			))
			{
				
				
				$coupon_lastid=mysql_insert_id(); 
				
						
				if(mysql_query("update ".$user->user_table." set accountbalance = accountbalance + ".$x_amount." where ".$user->uid_field."='$x_cust_id'"))
				{
				
				
				
				
				//*************************


$balance=mysql_query("select accountbalance from ".$user->user_table." where ".$user->uid_field."='$x_cust_id'");
$balancerow=mysql_fetch_row($balance);

if($balancerow[0]>=$advertiser_minimum_account_balance)
{
mysql_query("update ".$user->user_table." set balancestatus=0 where ".$user->uid_field."='$x_cust_id'");

}


//**************************

				
				
				
				
				
				
				
				
				
				
				
				
				
				if($coupon_amt > 0)
	            {
mysql_query("update ".$user->user_table." set bonusbalance = bonusbalance + ".$coupon_amt." where ".$user->uid_field."='$x_cust_id'");
mysql_query("insert into advertiser_bonus_deposit_history values('0','$x_cust_id','$coupon_amt',0,'Coupon Bonous',".time().",'$count_coupon_id','$coupon_lastid','au')");   //*** Bonous Coupon Type  0 ******
mysql_query("UPDATE gift_code set count = count+1 where id='$count_coupon_id'");
				
				}
				
				
				
					 $template->setValue("{MESSAGE}",$template->checkAdvMsg(2009));

					 $template->setValue("{MESSAGE1}",$template->checkAdvMsg(8920));
//					  $template->setValue("{MESSAGE2}",$mess);
					 $template->setValue("{ERROR}","");
					 $template->setValue("{RESULT}","$result_table");
				}
				else
				{
					 $str.="Note: Transaction has been recorded. But user account balance not updated yet.";
					 $template->setValue("{MESSAGE}","");
					 $template->setValue("{ERROR}",$template->checkAdvMsg(2010));
					 $template->setValue("{RESULT}","$result_table");
				}
			}
			else
			{
				$str.="Note: Payment has been received to your Authorize.net account. But transaction has not been recorded in adserver tables and user account balance not updated.";
				$template->setValue("{MESSAGE}","");
				$template->setValue("{ERROR}",$template->checkAdvMsg(2011));
				$template->setValue("{RESULT}","$result_table");
			}
			$msg = <<< EOB
Hello,

You have just received new funds for your $ppc_engine_name.

Payer Username		: $payer_username
Email 		   		: $payer_email
Amount	    	   	: $x_amount
Transaction ID		: $x_trans_id
Invoce number       : $x_invoice_num

Login to your Authorize.net account for more details.
$str

Best Regards,
$ppc_engine_name

EOB;
xMail($admin_payment_notification_email, "$ppc_engine_name - New Funds Received", $msg, $admin_general_notification_email);

		if($send_mail_after_payment==1)
		{	
			$client_email_subject=$mysql->echo_one("select email_subject from email_templates where id='12'");
			$mail=$mysql->echo_one("select email_body from email_templates where id='12'");
			$mail = str_replace("{USERNAME}",$payer_username,$mail);
			$mail = str_replace("{PAYMENT_AMOUNT}", $x_amount, $mail);       //$total
			$mail = str_replace("{BONOUS_AMOUNT}", $coupon_amt, $mail);
			$mail = str_replace("{PAYMODE}", "Authorize.net Payment", $mail);
			
			$mail = str_replace("{PAYER_EMAIL}", $email, $mail);
			$mail = str_replace("{TXN_ID}", $txn_id, $mail);
			$mail = str_replace("{ENGINE_NAME}", $ppc_engine_name, $mail);
			$mail=html_entity_decode($mail,ENT_QUOTES);
			$client_email_subject = str_replace("{ENGINE_NAME}", $ppc_engine_name, $client_email_subject);	
			include($GLOBALS['admin_folder']."/class.Email.php");
	  
			$message = new Email($payer_email, $admin_general_notification_email, $client_email_subject, '');
			$message->Cc = ''; 
			$message->Bcc = ''; 
			$message->SetHtmlContent(nl2br($mail));  
			$message->Send();
		}
		
	}
	elseif($x_response_code==4)
	{
		$msg = <<< EOB
Hello,
		
Payment ON HOLD at $ppc_engine_name

Your $ppc_engine_name has just received a Authorize.net payment. BUT THE PAYMENT IS ON HOLD. 
Please login to your Authorize.net account and check the details.

Payer Username		: $payer_username
Email 		   		: $payer_email
Amount	    	   	: $x_amount
Transaction ID		: $x_trans_id
Invoce number       : $x_invoice_num

You may add the fund to the advertiser manually when the payment is approved.

Best Regards,
$ppc_engine_name

EOB;

	
		xMail($admin_payment_notification_email, "$ppc_engine_name - UNSUCCESSFUL Payment", $msg, $admin_general_notification_email);
		//xMail("robin.paulose@nesote.com", "$ppc_engine_name - Payment ON HOLD", $msg, $admin_general_notification_email);
		
		$template->setValue("{ERROR}","");
		$template->setValue("{MESSAGE}",$template->checkAdvMsg(2013));
		$template->setValue("{RESULT}","$result_table");
	}
	else
	{
	
		$msg = <<< EOB
Hello,
		
UNSUCCESSFULL payment at $ppc_engine_name

Your $ppc_engine_name has just received a Authorize.net payment. BUT THE PAYMENT WAS NOT SUCCESSFUL. 
Please login to your Authorize.net account and check the details.

Payer Username		: $payer_username
Email 		   		: $payer_email
Amount	    	   	: $x_amount
Transaction ID		: $x_trans_id
Invoce number       : $x_invoice_num

Best Regards,
$ppc_engine_name

EOB;

	
		xMail($admin_payment_notification_email, "$ppc_engine_name - UNSUCCESSFUL Payment", $msg, $admin_general_notification_email);
		//xMail("robin.paulose@nesote.com", "$ppc_engine_name - UNSUCCESSFUL Payment", $msg, $admin_general_notification_email);
		
		$template->setValue("{MESSAGE}","");
		$template->setValue("{ERROR}",$template->checkAdvMsg(2002));
		$template->setValue("{RESULT}","$result_table");

	}
}
else 
{
    // At this point the keys do not match, so either the attempt was fraudulentor a demo order
    // This is where you would put the code or page for an unsuccessful attempt
	// echo ("<center>They do NOT match! Was this a demo order?</center>");
	//header("Location: http://www.yahoo.com");
	
		$msg = <<< EOB
Hello,
		
FRAUD payment at $ppc_engine_name

Your $ppc_engine_name has just received a Authorize.net payment. BUT THE PAYMENT WAS FRAUD. 
The transaction was not recorded.

Payer Username		: $payer_username
Email 		   		: $payer_email
Amount	    	   	: $x_amount
Transaction ID		: $x_trans_id
Invoce number       : $x_invoice_num

Best Regards,
$ppc_engine_name

EOB;

	
	xMail($admin_payment_notification_email, "$ppc_engine_name - FRAUD Payment", $msg, $admin_general_notification_email);
	//xMail("robin.paulose@nesote.com", "$ppc_engine_name - FRAUD Payment", $msg, $admin_general_notification_email);
	
	$template->setValue("{MESSAGE}","");
	$template->setValue("{ERROR}",$template->checkAdvMsg(2002));
	$template->setValue("{RESULT}","$result_table");
}
	
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));         
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>