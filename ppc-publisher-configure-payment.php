<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");
$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-configure-payment.tpl.html");
$user=new User("ppc_publishers");

if(!($user->validateUser()))
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$url=$_GET['url'];

$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");



$form=new Form("ConfigurePaymentDetails","ppc-publisher-configure-payment-action.php");
$form->isNotNull("paydetails",$template->checkmsg(6035));
$user_id=$user->getUserID();
$result=mysql_query("select paymentmode,paypalemail,payeename,address1,address2,city,state,country,zip,acc_no,routing_no,acc_type,bank_address,acc_type,bank_name,b_city,b_state,b_country,b_zip from ppc_publisher_payment_details where uid='$user_id'");
$row=mysql_fetch_row($result);
$paymode = $row[0];//$mysql->echo_one("select paymentmode from ppc_publisher_payment_details where uid =".$user_id);
$paypalemail =$row[1]; //$mysql->echo_one("select paypalemail from ppc_publisher_payment_details where uid =".$user_id);
$payeename =$row[2]; //$mysql->echo_one("select payeename from ppc_publisher_payment_details where uid =".$user_id);
$address1 =$row[3]; //$mysql->echo_one("select address1 from ppc_publisher_payment_details where uid =".$user_id);
$address2 =$row[4]; //$mysql->echo_one("select address2 from ppc_publisher_payment_details where uid =".$user_id);
$city =$row[5]; //$mysql->echo_one("select city from ppc_publisher_payment_details where uid =".$user_id);
$state =$row[6]; //$mysql->echo_one("select state from ppc_publisher_payment_details where uid =".$user_id);
$country =$row[7]; //$mysql->echo_one("select country from ppc_publisher_payment_details where uid =".$user_id);
$zip =$row[8]; //$mysql->echo_one("select zip from ppc_publisher_payment_details where uid =".$user_id);
$bank_address=$row[12];
$acc_type=$row[13];
$bank_name=$row[14];
//added on 09 November 2009
$acc_no=$row[9]; //$mysql->echo_one("select acc_no from ppc_publisher_payment_details where uid =".$user_id);
$routing_no=$row[10]; //$mysql->echo_one("select routing_no from ppc_publisher_payment_details where uid =".$user_id);
$acc_type=$row[11]; //$mysql->echo_one("select acc_type from ppc_publisher_payment_details where uid =".$user_id);
$b_city=$row[15];
$b_state=$row[16];
$b_country=$row[17];
$b_zip=$row[18];
$template->setValue("{BANKNAME}",'<input type="text" name="bank_name" value="'.$bank_name.'">');
$template->setValue("{ACCNO}",'<input type="text" name="acc_no" value="'.$acc_no.'">');
$template->setValue("{ROUTNO}",'<input type="text" name="routing_no" value="'.$routing_no.'">');
$template->setValue("{ACCTYPE}",'<input type="text" name="acc_type" value="'.$acc_type.'">');
$template->setValue("{BANKADD}",'<textarea name="bank_address" rows="6" cols="25">'.$bank_address.'</textarea>');
$template->setValue("{ACCTYPE}",'<input type="text" name="acc_type" value="'.$acc_type.'">');

$template->setValue("{BCITY}",'<input type="text" name="b_city" value="'.$b_city.'">');
$template->setValue("{BSTATE}",'<input type="text" name="b_state" value="'.$b_state.'">');
$template->setValue("{BCOUNTRY}",'<input type="text" name="b_country" value="'.$b_country.'">');
$template->setValue("{BZIP}",'<input type="text" name="b_zip" value="'.$b_zip.'">');

$sel_paypal="";
$sel_check="";
$sel_bank="";
if($paymode==1)
	$sel_paypal="checked";
else if($paymode==2)
		$sel_bank="checked";
else
	$sel_check="checked";
	
if($publisher_paypalpayment==1)
{
//		if($publisher_paypalpayment==1)
//			{
			$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" $sel_paypal onClick=\"show_paypal()\">".$template->checkPubMsg(7012)."&nbsp;";
//					  <input id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $sel_check onClick=\"show_check()\"> $publisher_message[7014]&nbsp;
//					 <input id=\"pay_radio3\"  name=\"radiobutton\" type=\"radio\" value=\"2\" $sel_bank onClick=\"show_bank()\"> $publisher_message[7032]";
			  }
//		 else
//		 	{
//			$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" checked onClick=\"show_paypal()\"> $publisher_message[7012]&nbsp;
//					  <input  id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $sel_check onClick=\"show_check()\"> $publisher_message[7014]&nbsp;";
//			}
//		}	  	
if($publisher_checkpayment==1)
		{
		if($publisher_bankpayment==1)
			{
				if($publisher_paypalpayment==1)
		 		{
				$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" $sel_paypal onClick=\"show_paypal()\"> ".$template->checkPubMsg(7012)."&nbsp;
					  <input id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $sel_check onClick=\"show_check()\"> ".$template->checkPubMsg(7014)."&nbsp;
					 <input id=\"pay_radio3\"  name=\"radiobutton\" type=\"radio\" value=\"2\" $sel_bank onClick=\"show_bank()\">".$template->checkPubMsg(7032)."&nbsp; ";
		 		}
		 		else
		 		{
		 		$pay="<input id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $sel_check onClick=\"show_check()\">".$template->checkPubMsg(7014)."&nbsp;
					 <input id=\"pay_radio3\"  name=\"radiobutton\" type=\"radio\" value=\"2\" $sel_bank onClick=\"show_bank()\">".$template->checkPubMsg(7032)."&nbsp; ";
		 		}
		 		
		 		}
		 else
		 	{
		 		if($publisher_paypalpayment==1)
		 		{
			$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" checked onClick=\"show_paypal()\"> ".$template->checkPubMsg(7012)."&nbsp;
					  <input  id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $sel_check onClick=\"show_check()\">".$template->checkPubMsg(7014)."&nbsp;";
		 		}
		 		else
		 		{
		 		$pay="<input  id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $sel_check onClick=\"show_check()\"> ".$template->checkPubMsg(7014)."&nbsp;";
		 		}
		 		
		 		
		 		}
		}	  
 else
 	{
			if($publisher_bankpayment==1)
				{
					if($publisher_paypalpayment==1)
		 		{
				$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" checked onClick=\"show_paypal()\">".$template->checkPubMsg(7012)."&nbsp;
					  	  <input  id=\"pay_radio3\"  name=\"radiobutton\" type=\"radio\" value=\"2\" $sel_bank onClick=\"show_bank()\">".$template->checkPubMsg(7032)."&nbsp; ";

		 		}
		 		else
		 		{
		 			$pay="<input  id=\"pay_radio3\"  name=\"radiobutton\" type=\"radio\" value=\"2\" $sel_bank onClick=\"show_bank()\">".$template->checkPubMsg(7032)."&nbsp; ";
		 		}
		 		
		 		}
//			else
//				{
//				$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" >$publisher_message[7012]";
//				}
	  }
	  
 $template->setValue("{PAYMODE}",$pay);
		
if($publisher_checkpayment||	$publisher_bankpayment)	
  $template->setValue("{PAYMODESTATUS}",1);
  else
  $template->setValue("{PAYMODESTATUS}",0);
//$template->setValue("{PAYDETAILS}",$form->addTextBox("paydetails",$mysql->echo_one("select paymentdetails  from ppc_publishers where uid=".$user->getUserID()),40,255));
$template->setValue("{PAYPALEMAIL}",'<input type="text" name="paypalemail" value="'.$paypalemail.'">');
$template->setValue("{PAYEENAME}",'<input type="text" name="payeename" value="'.$payeename.'">');
$template->setValue("{ADDRESS1}",'<input type="text" name="address1" value="'.$address1.'">');
$template->setValue("{ADDRESS2}",'<input type="text" name="address2" value="'.$address2.'">');
$template->setValue("{CITY}",'<input type="text" name="city" value="'.$city.'">');
$template->setValue("{STATE}",'<input type="text" name="state" value="'.$state.'">');
$template->setValue("{COUNTRY}",'<input type="text" name="country" value="'.$country.'">');
$template->setValue("{ZIP}",'<input type="text" name="zip" value="'.$zip.'">');
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(7017)));


//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  
$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8946));
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
$template->setValue("{URL}",$url);
eval('?>'.$template->getPage().'<?php ');
?>
