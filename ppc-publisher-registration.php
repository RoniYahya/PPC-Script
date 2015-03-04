<?php 
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("Form");
includeClass("User");
if($single_account_mode==1)
{

		header("Location: registration.php");
exit;
}
$pubreg=$mysql->echo_one("select seoname from url_updation where name='publishersignup'");
if($pubreg=="")
{
	$pubreg1="ppc-publisher-registration.php";
}
else
{
	$pubreg1=$pubreg;
}

if(isset($_POST['username']))
{
	
	
$username=trim($_POST['username']);
$password=$_POST['password'];
$password2=$_POST['password2'];
$email=$_POST['email'];
$domain=$_POST['domain'];
$country=$_POST['country'];
$paymode=$_POST['radiobutton'];
$img=$_POST['image'];
$txtid=trim($_POST['taxidentification']);

phpSafe($txtid);
$firstname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$phone_no=$_POST['phone_no'];
$address=$_POST['address'];
phpSafe($username);
phpSafe($password);
phpSafe($password2);
phpSafe($email);
phpSafe($domain);
phpSafe($country);
phpSafe($paymode);
phpSafe($img);

phpSafe($firstname);
phpSafe($lastname);
phpSafe($phone_no);
phpSafe($address);
$error_flag=0;
$err_msg=0;
//echo $username."gh".$password."gh".$password2."gh".$email.",".$country.",".$domain.",".$img.",".$firstname.",".$lastname.",".$address.",".$phone_no.",".$txtid;
if($username=="" || $password=="" || $password2=="" || $email=="" || $country=="" || $domain==""|| $img=="" || $firstname=="" || $lastname=="" || $address=="" || $phone_no=="")
{
//header("Location:publisher-show-message.php?id=8001");
//exit(0);
$err_msg=8001;
$error_flag=1;

}

if($error_flag==0)
	{
		if(!checkSpace($username))
			{
			$err_msg=1028;
			$error_flag=1;
			}
	}


if($error_flag==0)
	{
		if(!checkSpace($domain))
			{
			$err_msg=1031;
			$error_flag=1;
			}
			
	}
if($error_flag==0)
	{
		
		if(!isDomain($domain))
		{
			$err_msg=1030;
			$error_flag=1;
		}
}
	
	
if($error_flag==0)
	{
	//if(is_numeric($phone_no) !=1 ||$phone_no<0  ||  !((string)$phone_no === (string)(int)$phone_no) )
	if(!isPositiveInteger($phone_no))
		{
		$err_msg=1032;
		$error_flag=1;
		}
	}

$user=new User("ppc_publishers");

//$user->allowPassword($password,$password2,$min_user_password_length);
if($error_flag==0)
	{
	$err_msg=$user->allowPassword($password,$password2,$min_user_password_length);
	if($err_msg!="")
	$error_flag=1;
	}
	
if($error_flag==0)
		{

			if($user->userExists($username))
			{
			$err_msg=1027;
			$error_flag=1;
			//header("Location:ppc-publisher-already-exists.php?username=$username");
			//exit(0);
			}
		}


if($error_flag==0)
		{
			if($user->emailExists($email))
			{
				$err_msg=5019;
				$error_flag=1;
				//header("Location:publisher-show-message.php?id=5019");
				//exit(0);
			}
		}
if($error_flag==0)
		{
			if(!($user->isValidEmail($email)))
			{
				$err_msg=6009;
				$error_flag=1;
				//header("Location:publisher-show-message.php?id=5019");
				//exit(0);
			}
		}

if($error_flag==0)
		{
	if(md5($img)!=$_COOKIE['image_random_value']) 
		{ 
		$err_msg=1025;
		$error_flag=1;
		}
}

if($error_flag==1)
		{
		
		//include_once("messages.".$client_language.".inc.php");
		$template=new Template();
		
		$template->loadTemplate("publisher-templates/ppc-publisher-registration.tpl.html");
		$template->includePage("{TABS}","common-header.php");
		$template->includePage("{FOOTER}","common-footer.php");
		
		$form=new Form("publisherRegistration",$pubreg1);
		$form->isNotNull("username",$template->checkmsg(6003));
		$form->isWhitespacePresent("username",$template->checkmsg(1028));
		$form->isNotNull("password",$template->checkmsg(6004));
		$form->isNotShort("password",$min_user_password_length,$template->checkmsg(6005));
		$form->isNotNull("password2",$template->checkmsg(6006));
		$form->isNotShort("password2",$min_user_password_length,$template->checkmsg(6007));
		$form->isSame("password","password2",$template->checkmsg(6008));
			$form->isNotNull("firstname",$template->checkmsg(6077));
			$form->isNotNull("lastname",$template->checkmsg(6078));
			$form->isNotNull("address",$template->checkmsg(6079));
			$form->isNotNull("phone_no",$template->checkmsg(6081));
			$form->isPositiveInteger("phone_no",$template->checkmsg(1032));
		
		$form->isEmail("email",$template->checkmsg(6009));
		$form->isNotNull("country",$template->checkmsg(6010));
		$form->isNotNull("domain",$template->checkmsg(6011));
		$form->isWhitespacePresent("domain",$template->checkmsg(1031));
		$form->isDomain("domain",$template->checkmsg(1030));
//		$form->isNotNull("taxidentification",$template->checkmsg(10012));
		$form->isNotNull("image",$template->checkmsg(6060));
		
		
	
		
		$ctrstr="";
				$res=mysql_query("select *  from location_country where code NOT IN('A1','A2','AP','EU') order by name");
				//echo mysql_num_rows($res);
				$ctrstr.="<select name=\"country\" id=\"country\" onChange=\"loadState();\"  >
				<option value=\"\" selected=\"selected\">-Select Country-</option>";
					while($row=mysql_fetch_row($res))
					{
						if($country==$row[0])
							$ctrstr.="<option value=\"$row[0]\" selected>$row[1]</option>";
						else	
							$ctrstr.="<option value=\"$row[0]\">$row[1]</option>";
					} 
				$ctrstr.="</select>";
		
		$template->setValue("{USERFIELD}",'<input type="text" name="username" id="un" value='.$username.' width="30" size="25" onblur="show()">');		
		$template->setValue("{PASSFIELD}",$form->addPassword("password",25));	
		$template->setValue("{PASSFIELD2}",$form->addPassword("password2",25));		
		$template->setValue("{COUNTRYFIELD}",$ctrstr);
		$template->setValue("{DOMAINFIELD}",$form->addTextBox("domain",htmlSafe($_POST['domain']),25));	
		$template->setValue("{EMAILFIELD}",$form->addTextBox("email",htmlSafe($_POST['email']),25));	
		$template->setValue("{FNAMEFIELD}",$form->addTextBox("firstname",htmlSafe($_POST['firstname']),25));		
		$template->setValue("{LNAMEFIELD}",$form->addTextBox("lastname",htmlSafe($_POST['lastname']),25));		
		$template->setValue("{PHNOFIELD}",$form->addTextBox("phone_no",htmlSafe($_POST['phone_no']),25));		
		$template->setValue("{ADDRESSFIELD}",'<textarea name="address" id="address">'.htmlSafe($_POST['address']).'</textarea>'); 
			$template->setValue("{FORMSTART}",$form->formStart()."<br><center class=\"already\">".$template->checkmsg($err_msg)."</center>");
		$template->setValue("{FORMCLOSE}",$form->formClose());
		$template->setValue("{PASSMSG}","minimum password length is $min_user_password_length");
		 $template->setValue("{TAXIDENTIFICATION}",$form->addTextBox("taxidentification",htmlSafe($_POST['taxidentification']),25));
		$bank_checked='';
		$paypal_checked='';
		$check_checked='';
				if($paymode==0)
				$check_checked='checked';
				if($paymode==1)
				$paypal_checked='checked';
				if($paymode==2)
					$bank_checked='checked';
					
					if($publisher_paypalpayment==1)
{				
					
$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" $paypal_checked>". $template->checkPubmsg(7012);
	//				 <input id=\"pay_radio3\"  name=\"radiobutton\" type=\"radio\" value=\"2\" $sel_bank onClick=\"show_bank()\"> $publisher_message[7032]";
			  }				
					

if($publisher_checkpayment==1)
		{
		if($publisher_bankpayment==1)
			{
				if($publisher_paypalpayment==1)
		 		{
				$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" $paypal_checked>". $template->checkPubmsg(7012)."&nbsp;
					  <input id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $check_checked> ".$template->checkPubmsg(7014)."&nbsp;
					 <input id=\"pay_radio3\"  name=\"radiobutton\" type=\"radio\" value=\"2\" $bank_checked> ".$template->checkPubmsg(7032);
		 		}
		 		else
		 		{
		 		$pay=" <input id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $check_checked> ".$template->checkPubmsg(7014)."&nbsp;
					 <input id=\"pay_radio3\"  name=\"radiobutton\" type=\"radio\" value=\"2\" $bank_checked> ".$template->checkPubmsg(7032);
		 		}
		 		
		 		}
		 else
		 	{
		 		if($publisher_paypalpayment==1)
		 		{
		$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" $paypal_checked> ".$template->checkPubmsg(7012)."&nbsp;
					  <input  id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $check_checked>". $template->checkPubmsg(7014)."&nbsp;";
		 		}
		 		else
		 		{
		 		$pay="<input  id=\"pay_radio2\" name=\"radiobutton\" type=\"radio\" value=\"0\" $check_checked>". $template->checkPubmsg(7014)."&nbsp;";
		 		}
		 		
		 		
		 		}
		}	  

		 $template->setValue("{PAYMODE}",$pay);
		 $template->setValue("{PAYMODESTATUS}",$publisher_checkpayment);
		  
		$template->setValue("{IMG}",$form->addTextBox("image","",25));	
		$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=1"));           
		$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=1"));         
//		$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8970));
$template->setValue("{ENGINE_TITLE}",$engine_title);

$msg10= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8971));
$template->setValue("{MSG10}",$msg10);

$msg11=str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(1017));
$template->setValue("{MSG11}",$msg11);

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
		
		
		exit(0);
}



$public_ip=getUserIP();
$rid=0;
//print_r($_COOKIE);exit(0);
if(isset($_COOKIE['io_ads_ref']))
{
	$tmp=trim($_COOKIE['io_ads_ref']);
	if($mysql->total("ppc_publishers","uid='$tmp' and status=1")==1)
	{
		$rid=$tmp;
	}
}
 
if(mysql_query("insert into ppc_publishers values('0','$username','".md5($password)."','$email','-2','$country','$domain','".time()."','0','0','0','$public_ip','$rid','0','0','$firstname','$lastname','$phone_no','$address','0','0','0','0','0','$txtid','0')"))
{
//$user->cookieUser($username,$password,"publisher");
$new_uid=$mysql->echo_one("select uid from ppc_publishers where username='$username'");
mysql_query("insert into ppc_publisher_payment_details (id,uid,paymentmode) values('0','$new_uid','$paymode')");


// CONFIRM EMAIL ID

$sub=$mysql->echo_one("select email_subject from email_templates where id='20'");
$sub=str_replace("{USERNAME}",$username,$sub);
$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
$body=$mysql->echo_one("select email_body from email_templates where id='20'");
$body=str_replace("{USERNAME}",$username,$body);
$body=str_replace("{PASSWORD}",$password,$body);
$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
$path=$server_dir."ppc-publisher-confirm-email.php"."?id=".$new_uid;
$body=str_replace("{PUB_CONFIRM_PATH}",$path,$body);
$body=html_entity_decode($body,ENT_QUOTES);

if($script_mode!="demo")
{
  include($GLOBALS['admin_folder']."/class.Email.php");
  
	  $message = new Email($email, $admin_general_notification_email, $sub, '');
	  $message->Cc = ''; 
	  $message->Bcc = ''; 
	  $message->SetHtmlContent(nl2br($body));  
	  $message->Send();
}	

// END CONFIRM EMAIL ID
		

header("Location:show-success.php?id=5034");
	exit(0);
}
else
{
header("Location:publisher-show-message.php?id=1004");
exit(0);
}
	
	
	
	
	
	
	
	
	
	die;
	
	
	
	
	
	
}
$template=new Template();

$template->loadTemplate("publisher-templates/ppc-publisher-registration.tpl.html");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$form=new Form("publisherRegistration",$pubreg1);
$form->isNotNull("username",$template->checkmsg(6031));
//$form->isNotNull("username",$template->checkmsg(6003));
$form->isWhitespacePresent("username",$template->checkmsg(1028));
$form->isNotNull("password",$template->checkmsg(6004));
$form->isNotShort("password",$min_user_password_length,$template->checkmsg(6005));
$form->isNotNull("password2",$template->checkmsg(6006));
$form->isNotShort("password2",$min_user_password_length,$template->checkmsg(6007));
$form->isSame("password","password2",$template->checkmsg(6008));
	$form->isNotNull("firstname",$template->checkmsg(6077));
	$form->isNotNull("lastname",$template->checkmsg(6078));
	$form->isNotNull("phone_no",$template->checkmsg(6081));
	$form->isPositiveInteger("phone_no",$template->checkmsg(1032));
	$form->isNotNull("address",$template->checkmsg(6079));
$form->isEmail("email",$template->checkmsg(6009));
$form->isNotNull("country",$template->checkmsg(6010));
$form->isNotNull("domain",$template->checkmsg(6011));
$form->isWhitespacePresent("domain",$template->checkmsg(1031));
//$form->isDomain("domain",$template->checkmsg(1030));
//$form->isNotNull("taxidentification",$template->checkmsg(10012));
$form->isNotNull("image",$template->checkmsg(6060));


$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());

$ctrstr="";
		$res=mysql_query("select *  from location_country where code NOT IN('A1','A2','AP','EU') order by name");
		//echo mysql_num_rows($res);
		$ctrstr.="<select name=\"country\" id=\"country\" onChange=\"loadState();\" dir='ltr' >
		<option value=\"\" selected=\"selected\">-Select Country-</option>";
			while($row=mysql_fetch_row($res))
			{
				$ctrstr.="<option value=\"$row[0]\">$row[1]</option>";
			} 
		$ctrstr.="</select>";

$template->setValue("{USERFIELD}",'<input type="text" name="username" id="un" width="30"  size="25" onblur="show()">');
$template->setValue("{PASSFIELD}",$form->addPassword("password",25));
$template->setValue("{PASSFIELD2}",$form->addPassword("password2",25));
$template->setValue("{COUNTRYFIELD}",$ctrstr);
$template->setValue("{DOMAINFIELD}",$form->addTextBox("domain","",25));
$template->setValue("{EMAILFIELD}",$form->addTextBox("email","",25));
$template->setValue("{FNAMEFIELD}",$form->addTextBox("firstname","",25));
$template->setValue("{LNAMEFIELD}",$form->addTextBox("lastname","",25));
$template->setValue("{PHNOFIELD}",$form->addTextBox("phone_no","",25));
$template->setValue("{ADDRESSFIELD}",'<textarea name="address" id="address"></textarea>');

$msg12= str_replace("{MIN_PASS}",$min_user_password_length,$template->checkPubMsg(8882));


$template->setValue("{PASSMSG}",$msg12); 
$template->setValue("{TAXIDENTIFICATION}",$form->addTextBox("taxidentification","",25));


if($publisher_paypalpayment==1)
{


			$pay="<input id=\"pay_radio1\" name=\"radiobutton\" type=\"radio\" value=\"1\" $sel_paypal onClick=\"show_paypal()\">".$template->checkPubMsg(7012)."&nbsp;";

  	
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

	  }

}



















 $template->setValue("{PAYMODE}",$pay);
 $template->setValue("{PAYMODESTATUS}",$publisher_checkpayment);
  
$template->setValue("{IMG}",$form->addTextBox("image","",25));
$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=1"));           
$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=1"));         

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


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8970));
$template->setValue("{ENGINE_TITLE}",$engine_title);

$msg10= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8971));
$template->setValue("{MSG10}",$msg10);

$msg11= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(1017));
$template->setValue("{MSG11}",$msg11);

eval('?>'.$template->getPage().'<?php ');
?>