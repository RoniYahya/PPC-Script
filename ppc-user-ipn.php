<?php
include("extended-config.inc.php"); 
require($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
$user=new User("ppc_users");
$coupon_lastid=0;
echo "IPN received at: " . date("r",time()) . "\n";

// Testing mode


	$pp_domain = "www.paypal.com";
	$pp_validatorurl = "/cgi-bin/webscr";
	$req = 'cmd=_notify-validate';
	
	/*
    $pp_domain = "localhost";
	$pp_validatorurl = "/projects/Sandbox/validator.php";
	$req = 'verified=$_POST[verified]';
	*/
	$t_ipns ="inout_ppc_ipns";

// Read the post from PayPal system and add 'cmd'
$fullipnA = array();
foreach ($_POST as $key => $value)
{
	$fullipnA[$key] = $value;

	$encodedvalue = urlencode(stripslashes($value));
	$req .= "&$key=$encodedvalue";
	
}
$fullipn = Array2Str(" : ", "\n", $fullipnA);




// Post back to PayPal system to validate
$header  = "POST $pp_validatorurl HTTP/1.0\r\n";
$header .= "Host: $pp_domain\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ($pp_domain, 80, $errno, $errstr, 30);


// Assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['business'];
$payer_email = $_POST['payer_email'];
$txn_type = $_POST['txn_type'];
$pending_reason = $_POST['pending_reason'];
$payment_type = $_POST['payment_type'];






$paypal_coupon="";

$userid = $_POST['custom'];
$paypal_id_data=explode('_',$userid);


$userid=$paypal_id_data[0];
if(count($paypal_id_data) >1)
{
$paypal_coupon=$paypal_id_data[1];
}










//$username=$_COOKIE['io_username'];
$payer_username=$mysql->echo_one("select ".$user->username_field." from ".$user->user_table." where ".$user->uid_field."='$userid'");
//echo $user->getUsername($userid).$userid;

$sql = "SELECT * FROM ppc_users WHERE uid = $userid";
$user_row = mysql_fetch_array(mysql_query($sql));



// Check parameters

if (!$fp)
{
	// HTTP error
	LogTrans("HTTP Error, can't connect to Paypal");
	StopProcess();
}
else
{
	$ret = "";
	fputs ($fp, $header . $req);
	while (!feof($fp)) $ret = fgets ($fp, 1024); 
	fclose ($fp);
//$ret="VERIFIED";
	if (strcmp ($ret, "VERIFIED") == 0)
	{
		// check the payment_status is Completed
		if ($payment_status != "Completed")
		{
			LogTrans("Incomplete Payment - Payment Status: $payment_status");
			StopProcess();
		}

		// check that receiver_email is your Primary PayPal email
		if ($receiver_email != $paypal_email)
		{
			LogTrans("Wrong Receiver Email - $item_number");
			StopProcess();
		}
		
		
		// check that txn_id has not been previously processed
		$sql = "SELECT txnid FROM $t_ipns WHERE txnid = '$txn_id' AND result = '1'";
		$res = mysql_query($sql) or die($sql.mysql_error());
		if (mysql_num_rows($res) || !$txn_id)
		{
			// Entry present
			LogTrans("Invalid/Duplicate Transaction - $txn_id");
			StopProcess();
		}


		if ($payment_amount < $min_user_transaction_amount)
		{
			LogTrans("Amount Less than Minimum Amount - Received: $payment_amount $payment_currency; Minimum Amount: $min_user_transaction_amount $paypal_currency");
			StopProcess();
		}
		
		if ($payment_currency != $paypal_currency)
		{
			LogTrans("Wrong Currency - Received: $payment_currency; Expected: $paypal_currency");
			StopProcess();
		}


		// Mail user the file
		/*$filename = "ppc-payment-mail.tpl";
		$bare_filename = strpos($filename, "/")!==FALSE ? 
							substr($filename, strrpos($filename, "/")+1) : 
							$filename;
		$filesize = filesize($filename);
		$filecontents = base64_encode(file_get_contents($filename));
		$fileencoding = "base64";
		
		$mime_boundary = "<<<-=-=-[".md5(time())."]-=-=->>>";
		$mailheaders  = "";
		$mailheaders .= "MIME-Version: 1.0\n";
		$mailheaders .= "Content-Type: multipart/mixed;\n";
		$mailheaders .= " boundary=\"".$mime_boundary."\"\n";
		

		$mailheaders .= "From: $admin_payment_notification_email";*/
        //$mail = file_get_contents("ppc-payment-mail.tpl");
		
		
		//$mail = str_replace("{PROD_NAME}", "$prodinfo[NAME] $prodinfo[VER]", $mail);
		//$mail = str_replace("{PAYMENT_CURRENCY}", $payment_currency, $mail);
		//$mail = str_replace("{PAYMENT_AMOUNT}", $payment_amount, $mail);
		//$mail = str_replace("{PAYER_EMAIL}", $payer_email, $mail);
		//$mail = str_replace("{TXN_ID}", $txn_id, $mail);
		//$mail = str_replace("{PPCENGINE}", $ppc_engine_name, $mail);
		//$mail = str_replace("{PAYMENT_TIME}", date("r"), $mail);
		//$mail = str_replace("{ATTACH_FILENAME}", $bare_filename, $mail);
		
		
		
	
	
		
		
		
		
		
		
		if($send_mail_after_payment==1)
		{	

			$client_email_subject=$mysql->echo_one("select email_subject from email_templates where id='12'");
			$mail=$mysql->echo_one("select email_body from email_templates where id='12'");
			$mail = str_replace("{USERNAME}",$payer_username,$mail);
			$mail = str_replace("{PAYMENT_CURRENCY}", $payment_currency, $mail);
			$mail = str_replace("{PAYMENT_AMOUNT}", $payment_amount, $mail);
			
			
			$mail = str_replace("{BONOUS_AMOUNT}", $coupon_amt, $mail);
			$mail = str_replace("{PAYMODE}", "PayPal Payment", $mail);
			
			
			
			
			
			$mail = str_replace("{PAYER_EMAIL}", $payer_email, $mail);
			$mail = str_replace("{TXN_ID}", $txn_id, $mail);
			$mail = str_replace("{PAYMENT_TIME}", dateTimeFormat(time()), $mail);
			$mail = str_replace("{ENGINE_NAME}", $ppc_engine_name, $mail);
			$mail=html_entity_decode($mail,ENT_QUOTES);
			$client_email_subject = str_replace("{ENGINE_NAME}",$ppc_engine_name, $client_email_subject);	
			include($GLOBALS['admin_folder']."/class.Email.php");
	  
	//  xMail($admin_payment_notification_email, "bbllaa",'hhh'.$userid.'kkk', $admin_general_notification_email, $email_encoding);
			$message = new Email($user_row['email'], $admin_general_notification_email, $client_email_subject, '');
			$message->Cc = ''; 
			$message->Bcc = ''; 
			$message->SetHtmlContent(nl2br($mail));  
			$message->Send();
		}


		/*$fullmsg  = "";
		$fullmsg .= "This is a multi-part message in MIME format.\n";
		$fullmsg .= "\r\n";
		$fullmsg .= "--".$mime_boundary."\r\n";

		$fullmsg .= "Content-Type: text/plain; charset=\"ISO-8859-1\"\n";
		$fullmsg .= "Content-Transfer-Encoding: 8bit\n";
		$fullmsg .= "\r\n";
		$fullmsg .= $mail;
		$fullmsg .= "\r\n";
		$fullmsg .= "--".$mime_boundary."\r\n";

		$fullmsg .= "Content-Type: application/octet-stream; name=\"".basename($filename)."\"\n";
		$fullmsg .= "Content-Transfer-Encoding: ".$fileencoding."\n";
		$fullmsg .= "Content-Disposition: attachment; filename=\"".basename($filename)."\"\n";
		$fullmsg .= "\r\n";
		$fullmsg .= $filecontents;
		$fullmsg .= "\r\n";
		$fullmsg .= "--".$mime_boundary."--\r\n";


		if($send_mail_after_payment==1)
		{
		mail($user_row['email'], "$client_email_subject", $fullmsg, $mailheaders);
		}*/

		$user=new User("ppc_users");
		//$payer_username=$user->getUsername($userid);
		mysql_query("update ".$user->user_table." set accountbalance = accountbalance + ".$payment_amount." where ".$user->uid_field."='$userid'");
//		update ppc_users set accountbalance=accountbalance+100 where uid=2;






//*************************


$balance=mysql_query("select accountbalance from ".$user->user_table." where ".$user->uid_field."='$userid'");
$balancerow=mysql_fetch_row($balance);

if($balancerow[0]>=$advertiser_minimum_account_balance)
{
mysql_query("update ".$user->user_table." set balancestatus=0 where ".$user->uid_field."='$userid'");

}


//**************************



		LogTrans("Success");
		




$coupon_amt=0;
	if($paypal_coupon !="" && $paypal_coupon !=0)
	{


$count_coupon_id=$paypal_coupon;

$tm=time();
$count_coupon_id=$mysql->echo_one("select id from gift_code where id='$count_coupon_id' and status=1 and (no_times=0 or count < no_times) and expirydate > '$tm'");

if($count_coupon_id > 0)
{
	$already_used_coupon1=$mysql->echo_one("select id from advertiser_bonus_deposit_history where aid='$userid' and couponid='$count_coupon_id' limit 0,1");
	$already_used_coupon2=$mysql->echo_one("select id from advertiser_fund_deposit_history where uid='$userid' and coupon_id ='$count_coupon_id' and (status='0' or status='1')");
	if($already_used_coupon1=='' && $already_used_coupon2=='') 
	{
		$coupon_type= $mysql->echo_one("select type from gift_code where id='$count_coupon_id'");
		if($coupon_type ==1)
		{
			$perc_coupon=$mysql->echo_one("select amount from gift_code where id='$count_coupon_id'");
			
			if($perc_coupon=="")
			$perc_coupon=0;
			
			$coupon_amt=($perc_coupon*$payment_amount)/100;
			
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
	
if($coupon_amt > 0)
	            {
mysql_query("update ".$user->user_table." set bonusbalance = bonusbalance + ".$coupon_amt." where ".$user->uid_field."='$userid'");
mysql_query("insert into advertiser_bonus_deposit_history values('0','$userid','$coupon_amt',0,'Coupon Bonous',".time().",'$count_coupon_id','$coupon_lastid','pp')");   //*** Bonous Coupon Type  0 ******
mysql_query("UPDATE gift_code set count = count+1 where id='$count_coupon_id'");
				
				}
				







		
		
		$msg = <<< EOB

Hello,

You have just received new funds for your $ppc_engine_name.

Payer Username		: $payer_username
Paypal Email 		: $payer_email
Amount	    	   	: $payment_amount
Transaction ID		: $txn_id

Login to your paypal account for more details.

Thanks again for using $ppc_engine_name.

Best Regards,
$ppc_engine_name

EOB;

//echo $msg;


		//$msg .= Array2Str(": ", "\n", $fullipnA);
		xMail($admin_payment_notification_email, "$ppc_engine_name - New Funds Received", $msg, $admin_general_notification_email, $email_encoding);

		// Send a mail to Jacob

		StopProcess();

	}
	else // if (strcmp ($ret, "INVALID") == 0)
	{
		LogTrans("Invalid Transaction - $ret");
		StopProcess();
	}
}


function LogTrans($ecode,$sendcc="")
{
	global $site_email, $site_name, $t_ipns, $fullipn, $fullipnA,$payer_username,$payer_email,$ppc_engine_name,$admin_payment_notification_email,$admin_general_notification_email, $email_encoding;
	
	global $coupon_lastid;
	
	//include("extended-config.inc.php");  
//include($GLOBALS['admin_folder']."/config.inc.php");
	
	//$payer_username=$mysql->echo_one("select ".$user->username_field." from ".$user->user_table." where ".$user->uid_field."='$userid'");
		
	
	// Mail admin
	if ($ecode != "Success")

	{
	
	
	
		$msg = <<< EOB
UNSUCCESSFULL IPN at $ppc_engine_name

Your $ppc_engine_name has just received an IPN. BUT THE PAYMENT IS NOT SUCCESSFUL. 
Please login to your paypal account and check the details.

Payer     : $GLOBALS[payer_email]
Txn ID    : $GLOBALS[txn_id]
Username  : $payer_username
Amount    : $GLOBALS[payment_currency] $GLOBALS[payment_amount]

Result    : $ecode

-----------------------------------------------------------------------
Available details from Paypal about the transaction:


EOB;

		$msg .= Array2Str(": ", "\n", $fullipnA);
		xMail($admin_payment_notification_email, "$ppc_engine_name - UNSUCCESSFUL Payment", $msg, $admin_general_notification_email, $email_encoding);
		

	}

	echo "\n".$msg;


	// Log IPN
	$result = ($ecode=="Success"?1:0);
	$sql = <<< EOB
	INSERT INTO $t_ipns 
	SET	txnid = '$GLOBALS[txn_id]',
		userid = '$GLOBALS[userid]',
		result = '$result',
		resultdetails = '$ecode',
		itemname = '$GLOBALS[item_name]',
		itemnumber = '$GLOBALS[item_number]',
		amount = '$GLOBALS[payment_amount]',
		currency = '$GLOBALS[payment_currency]',
		payeremail = '$GLOBALS[payer_email]',
		paymenttype = '$GLOBALS[txn_type]',
		verified = '$GLOBALS[ret]',
		status = '$GLOBALS[payment_status]',
		pendingreason = '$GLOBALS[pending_reason]',
		fullipn = '$fullipn',
		receivedat = NOW()
EOB;

	//mysql_query($sql) or FailInsert("Error recording ipn\n\n".mysql_error()."\n\n$sql");
	
	if(mysql_query($sql))
	$coupon_lastid=mysql_insert_id();
	else
	FailInsert("Error recording ipn\n\n".mysql_error()."\n\n$sql"); 

}

function StopProcess()
{
	if($fp)
	{
		fclose($fp);
		unset($fp);
	}
	exit;
}

function FailInsert($s)
{
	global $site_name, $site_email;
	global $payment_currency, $payment_amount, $payer_email, $item_name, $item_number, $uadid, $fullipnA, $fullipn, $payer_email,$ppc_engine_name,$admin_payment_notification_email,$admin_general_notification_email, $email_encoding;

	// Send mail abt this and die
	$msg = <<< EOB
IMPORTANT! UNRECORDED TRANSACTION at $ppc_engine_name

You have just received a paypal transaction. 
BUT THE TRANSACTION COULD NOT BE RECORDED IN DATABASE.
Please login to your paypal account and check the details.

Payer     : $GLOBALS[payer_email]
Txn ID    : $GLOBALS[txn_id]
Username  : $payer_email
Amount    : $GLOBALS[payment_currency] $GLOBALS[payment_amount]

-----------------------------------------------------------------------
MySQL response:

$s

-----------------------------------------------------------------------
Available details from Paypal about the transaction:


EOB;

	$msg .= Array2Str(": ", "\n", $fullipnA);
	//include("extended-config.inc.php");  
//include($GLOBALS['admin_folder']."/config.inc.php");
	xMail($admin_payment_notification_email, "$ppc_engine_name - IMPORTANT! UNRECORDED TRANSACTION!", $msg, $admin_general_notification_email, $email_encoding);

	echo "\n".$msg;
	//StopProcess();

}

function Array2Str($kvsep, $entrysep, $a)
{
	$str = "";
	foreach ($a as $k=>$v)
	{
		$str .= "{$k}{$kvsep}{$v}{$entrysep}";
	}
	return $str;
}

?>