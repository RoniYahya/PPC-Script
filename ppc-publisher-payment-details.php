<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");

$user=new User("ppc_publishers");
$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-payment-details.tpl.html");
if(!($user->validateUser()))
{
header("Location: publisher-show-message.php?id=1006");
exit(0);
}

$id=getSafePositiveInteger('id');

$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$uid=$user->getUserID();

$val1=mysql_query("select * from ppc_publisher_payment_hist  where uid =".$uid." AND id='$id'");
$val=mysql_fetch_array($val1);
if(!$val)
{
header("Location: publisher-show-message.php?id=1020");
exit(0);
}
//$CURRENCY = $mysql->echo_one("select value from ppc_settings where name ='paypal_currency'");
//$form=new Form("ConfigureRequestDetails","ppc-publisher-cash-request-action.php");

$paymode = $val['paymode']; 
$tid= $val['txid']; 
$details= $val['payeedetails'];  
$payer= $val['payerdetails']; 
$date=$val['processdate'];  
$pdate="";
if($date!=0)
{
$pdate=dateTimeFormat($date);
}
$rdate1=$val['reqdate']; 
$rdate=dateTimeFormat($rdate1);
$amount= $val['amount']; 
$currency= $val['currency'];  
$status= $val['status'];  

$s="";
if($status==0)
{
$s=$template->checkPubMsg(8923);
}
else if($status==1)
{
$s=$template->checkPubMsg(8922);
}
else if($status==2)
{
$s=$template->checkPubMsg(8924);
}
else if($status==-2)
{
$s=$template->checkPubMsg(8925);
}
else if($status==-3)
{
$s=$template->checkPubMsg(9001);
}
else
{
$s=$template->checkPubMsg(8926);
}

$template->setValue("{RDATE}",$rdate);
$template->setValue("{AMOUNT}",moneyFormat($amount));

$template->setValue("{DETAILS}",$details);

if($tid=="")  
{
	$tid=$template->checkPubMsg(9000);   
}
$template->setValue("{TXID}",$tid);


if($payer=="")
{
	$payer=$template->checkPubMsg(9000);   
}
$template->setValue("{NAME}",$payer);

$template->setValue("{STATUS}",$s);  
if($pdate=="")
{
	$pdate=$template->checkPubMsg(9000);   
}
$template->setValue("{DATE}",$pdate);


if($paymode==1) //paypal
{
     
	 $template->setValue("{PAY}",$template->checkPubMsg(7012));
	 
	 $template->setValue("{TEXT}",$template->checkPubMsg(7013));
 
     $template->setValue("{ID}",$template->checkPubMsg(8996));  
	
	 $template->setValue("{PAYER}",$template->checkPubMsg(8998));
	
                   
}
else if($paymode==2)
{
//echo "select bank_name,bank_address,acc_no,routing_no,acc_type,b_city,b_state,b_country,b_zip,	payeedetails from ppc_publisher_payment_hist where uid=".$uid ." AND id='$id'";
//$raddress=mysql_query("select bank_name,bank_address,acc_no,routing_no,acc_type,b_city,b_state,b_country,b_zip,	payeedetails from ppc_publisher_payment_hist where uid=".$uid ." AND id='$id'");
	$addrow=$val;
	//print_r($addrow);
	$bank_info="";
	$bank_info.=$addrow['b_city']."<br>";
	$bank_info.=$addrow['b_state']."<br>";
	$bank_info.=$addrow['b_country']."<br>";
	$bank_info.=$addrow['b_zip']."<br>";
    $addressvar="";
	if($addrow['bank_address']!="")
	$addressvar.=nl2br($addrow['bank_address'])."<br>";
	
	
	if($addrow['acc_no']=="")
	{
		$template->setValue("{ACNO}",$template->checkPubMsg(8999));  
	}
	else
		$template->setValue("{ACNO}",$addrow['acc_no']); 
	if($addrow['bank_name']=="")	 
	{
		$template->setValue("{BNAME}",$template->checkPubMsg(8999));  
	}
	else
		$template->setValue("{BNAME}",$addrow['bank_name']); 
	
	if($addrow['routing_no']=="")
	{
		$template->setValue("{RNO}",$template->checkPubMsg(8999));  
	}
	else
		$template->setValue("{RNO}",$addrow['routing_no']); 
	if($addrow['acc_type']=="")
	{
		$template->setValue("{ACTYPE}",$template->checkPubMsg(8999));  
	}
	else
		$template->setValue("{ACTYPE}",$addrow['acc_type']);  
	if($addrow['b_city']=="" && $addrow['b_state']=="" && $addrow['b_country']=="" && $addrow['b_zip']=="")
	{
		$template->setValue("{BANK_ADDRESS}", $template->checkPubMsg(8999) ); 
	}
	else
	{
		$template->setValue("{BANK_ADDRESS}", $addressvar.$bank_info ); 
	}
	

	$template->setValue("{PAY}",$template->checkPubMsg(8927)); 
	     
	$template->setValue("{TEXT}",$template->checkPubMsg(1274));
	
	$template->setValue("{ID}",$template->checkPubMsg(8996));   
	
	$template->setValue("{PAYER}",$template->checkPubMsg(8997));
                  
}

else if($paymode==0)
{ 
//$raddress=mysql_query("select address1,address2,city,state,country,zip from ppc_publisher_payment_hist where uid=".$uid." AND id='$id'");
	$addrow=$val;//mysql_fetch_row($raddress);
	$addressvar="";
	if($addrow['address1']!="")
	$addressvar.=$addrow['address1']."<br>";
	if($addrow['address2']!="")
	$addressvar.=$addrow['address2']."<br>";
	if($addrow['city']!="")
	$addressvar.=$addrow['city']."<br>";
	if($addrow['state']!="")
	$addressvar.=$addrow['state']."<br>";
	if($addrow['country']!="")
	$addressvar.=$addrow['country']."<br>";
	if($addrow['zip']!="")
	$addressvar.=$addrow['zip']."<br>";
	$template->setValue("{PAYEE_ADDRESS}", $addressvar );
 

	$template->setValue("{PAY}",$template->checkPubMsg(7014)); 
	
	$template->setValue("{TEXT}",$template->checkPubMsg(1274));

    $template->setValue("{ID}",$template->checkPubMsg(8996));  

	$template->setValue("{PAYER}",$template->checkPubMsg(8997));

}

else if($paymode==3)	
{
	 $template->setValue("{PAY}",$template->checkPubMsg(8995)); 
}		  
//$template->setValue("{PAYDETAILS}",$form->addTextBox("paydetails",$mysql->echo_one("select paymentdetails  from ppc_publishers where uid=".$uid),40,255));




//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));   
//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8965));
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


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
