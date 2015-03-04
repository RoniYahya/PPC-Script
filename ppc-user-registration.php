<?php 

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("Form");
includeClass("User");
//include_once("messages.".$client_language.".inc.php");
if($single_account_mode==1)
{

		header("Location: registration.php");
exit(0);
	}

$userreg=$mysql->echo_one("select seoname from url_updation where name='advertisersignup'");
if($userreg=="")
{
	$userreg1="ppc-user-registration.php";
}
else
{
	$userreg1=$userreg;
}

if(isset($_POST['username']))
{

$username=trim($_POST['username']);
$password=trim($_POST['password']);
$password2=trim($_POST['password2']);
$email=trim($_POST['email']);
$domain=trim($_POST['domain']);
$country=$_POST['country'];
$img=$_POST['image'];

$firstname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$phone_no=$_POST['phone_no'];
$address=$_POST['address'];
$taxid=trim($_POST['taxidentification']);

phpSafe($taxid);
$coupon=trim($_POST['coupon']);
phpSafe($coupon);




phpSafe($username);
phpSafe($password);
phpSafe($password2);
phpSafe($email);
phpSafe($domain);
phpSafe($country);
phpSafe($img);

phpSafe($firstname);
phpSafe($lastname);
phpSafe($phone_no);
phpSafe($address);
$error_flag=0;
$err_msg=0;
if($username=="" || $password=="" || $password2=="" || email=="" || $country=="" || $domain=="" || $img=="" || $firstname=="" || $lastname=="" || $address=="" || $phone_no=="")
{
	//header("Location:show-message.php?id=8001");
	//exit(0);
	$err_msg=8001;
	$error_flag=1;
}


if($error_flag==0)
{
$coupon_amt=0;
if($coupon != "")
{
$tm=time();
$count_coupon_id=$mysql->echo_one("select id from gift_code where coupon_code='$coupon' and status=1 and type=2 and (no_times=0 or count < no_times) and expirydate > '$tm'");

if($count_coupon_id > 0)
{
$coupon_amt=$mysql->echo_one("select amount from gift_code where id='$count_coupon_id' and coupon_code='$coupon'");
}
else
{
$err_msg=10001;
$error_flag=1;


}

}

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
$user=new User("ppc_users");

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
	}
}

if($error_flag==0)
{
	if($user->emailExists($email))
	{
		$err_msg=5019;
		$error_flag=1;
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
	//if(is_numeric($phone_no) !=1 ||$phone_no<0  ||  !((string)$phone_no === (string)(int)$phone_no) )
	if(!isPositiveInteger($phone_no))
	{
		$err_msg=1032;
		$error_flag=1;
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
$rid=0;
if(isset($_COOKIE['io_ads_ref']))
{
	$tmp=trim($_COOKIE['io_ads_ref']);
	if($mysql->total("ppc_publishers","uid='$tmp' and status=1")==1)
	{
		$rid=$tmp;
	}
}

if($error_flag==1)
{
	
	//includeClass("Form");
//	include_once("messages.".$client_language.".inc.php");
	$template=new Template();
	
	$template->loadTemplate("ppc-templates/ppc-user-registration.tpl.html");
	$template->includePage("{TABS}","common-header.php");
	$template->includePage("{FOOTER}","common-footer.php");
	

	$form=new Form("userRegistration",$userreg1);
	$form->isNotNull("username",$template->checkmsg(6003));
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
	$form->isDomain("domain",$template->checkmsg(1030));
//	$form->isNotNull("taxidentification",$template->checkmsg(10012));
	$form->isNotNull("image",$template->checkmsg(6060));

	$template->setValue("{FORMSTART}",$form->formStart()."<br><center class=\"already\">".$template->checkmsg($err_msg)."</center>");
	$template->setValue("{FORMCLOSE}",$form->formClose());


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
		
	$template->setValue("{USERFIELD}",'<input type="text" name="username" id="username" width="30" size="25" value='.$username.' onblur="show()">');
	$template->setValue("{PASSFIELD}",$form->addPassword("password",25));
	$template->setValue("{PASSFIELD2}",$form->addPassword("password2",25));
	$template->setValue("{COUNTRYFIELD}",$ctrstr);
	$template->setValue("{DOMAINFIELD}",$form->addTextBox("domain",htmlSafe($_POST['domain']),25));
	$template->setValue("{EMAILFIELD}",$form->addTextBox("email",htmlSafe($_POST['email']),25));

	$template->setValue("{TERMSAGREE}",'<input name="terms" type="checkbox" id="terms">');
	$template->setValue("{IMG}",$form->addTextBox("image","",25));
	$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=0"));
	$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=0"));
	$template->setValue("{FNAMEFIELD}",$form->addTextBox("firstname",htmlSafe($_POST['firstname']),25));
	$template->setValue("{LNAMEFIELD}",$form->addTextBox("lastname",htmlSafe($_POST['lastname']),25));
	$template->setValue("{PHNOFIELD}",$form->addTextBox("phone_no",htmlSafe($_POST['phone_no']),25));
	$template->setValue("{ADDRESSFIELD}",'<textarea name="address" id="address">'.htmlSafe($_POST['address']).'</textarea>');
$template->setValue("{TAXIDENTIFICATION}",$form->addTextBox("taxidentification",htmlSafe($_POST['taxidentification']),25));
	$template->setValue("{PASSMSG}","minimum password length is $min_user_password_length");
	
	 $commess=$template->checkAdvMsg(10154); 
$commess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$commess);
$commes1=str_replace("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"",$commess1);
//echo $commes1;
$template->setValue("{COMMES}",$commes1); 
$hmess=$template->checkAdvMsg(8924);  
$hmess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$hmess); 
$template->setValue("{HMESS}",$hmess1);  
$mess2=$template->checkAdvMsg(8925);  
$hmess3=str_replace("{ENAME}",$template->checkAdvMsg(0001),$mess2); 
$template->setValue("{HMESS3}",$hmess3); 

$passlength=str_replace("{LENGTH}",$min_user_password_length,$template->checkAdvMsg(0002)); 
$template->setValue("{PASSMSG}",$passlength); 


$template->setValue("{COUPONCODE}",$form->addTextBox("coupon",htmlSafe($_POST['coupon']),25));
	
	

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

$commess=$template->checkAdvMsg(8923);  
$commess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$commess);
     $template->setValue("{COMMES}",$commess1); 
    $hmess=$template->checkAdvMsg(8924);  
$hmess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$hmess); 
      $template->setValue("{HMESS}",$hmess1);  
 $mess2=$template->checkAdvMsg(8925);  
$hmess3=str_replace("{ENAME}",$template->checkAdvMsg(0001),$mess2); 
      $template->setValue("{HMESS3}",$hmess3);  
      
	$template->setValue("{PAGEWIDTH}",$page_width);
	$template->setValue("{ENCODING}",$ad_display_char_encoding);

	eval('?>'.$template->getPage().'<?php ');







	//header("Location:user-registration-repeat.php?username=$username&email=$email&domain=$domain&country=$country");
	exit(0);
}
//echo "insert into ppc_users values('0','$username','".md5($password)."','$email','1','$country','$domain','".time()."','0','0','$rid','$opening_bonus','$firstname','$lastname','$phone_no','$address')";











if(mysql_query("insert into ppc_users values('0','$username','".md5($password)."','$email',-2,'$country','$domain','".time()."','0','0','$rid','$coupon_amt','$firstname','$lastname','$phone_no','$address','0','0','0','$taxid')"))
{
	
	$aid=$user->getUserID($username);
	//if($opening_bonus!=0)
	//mysql_query("insert into advertiser_bonus_deposit_history values('0','$aid','$opening_bonus',0,'Account Opening Bonus',".time().",0)");
	
	
	if($coupon_amt > 0)
	{
	mysql_query("insert into advertiser_bonus_deposit_history values('0','$aid','$coupon_amt',0,'Coupon Bonous',".time().",'$count_coupon_id','0','0')");   //***** Bonous Coupon Type  0 ******
	
	
	mysql_query("UPDATE gift_code set count = count+1 where id='$count_coupon_id'");
	}
	
	
   
// CONFIRMATION OF EMAIL-ID	
	
	
	$sub=$mysql->echo_one("select email_subject from email_templates where id='19'");
	$sub=str_replace("{USERNAME}",$username,$sub);
	$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
	$body=$mysql->echo_one("select email_body from email_templates where id='19'");
	$body=str_replace("{USERNAME}",$username,$body);
	$body=str_replace("{PASSWORD}",$password,$body);
	$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
	$path=$server_dir."ppc-user-confirm-email.php"."?id=".$aid;
	$body=str_replace("{ADV_CONFIRM_PATH}",$path,$body);
    $body=html_entity_decode($body,ENT_QUOTES);
	if($script_mode!="demo")
	{

		include($GLOBALS['admin_folder']."/class.Email.php");

		$message = new Email($email, $admin_general_notification_email, $sub, '');
		$message->Cc = '';
		$message->Bcc = '';
		$message->SetHtmlContent(nl2br($body));
		$message->Send();
		
	}// CONFIRMATION OF EMAIL-ID	
	
	
	
	
	header("Location:show-success.php?id=5034");
	exit(0);
	
}
else
{
	header("Location:show-message.php?id=1004");
	exit(0);
}	
	
	
	
	
	
	
}
$template=new Template();



$template->loadTemplate("ppc-templates/ppc-user-registration.tpl.html");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$form1=new Form("userRegistration",$userreg1);
$form1->isNotNull("username",$template->checkmsg(6003));
$form1->isWhitespacePresent("username",$template->checkmsg(1028));
$form1->isNotNull("password",$template->checkmsg(6004));
$form1->isNotShort("password",$min_user_password_length,$template->checkmsg(6005));
$form1->isNotNull("password2",$template->checkmsg(6006));
$form1->isNotShort("password2",$min_user_password_length,$template->checkmsg(6007));
$form1->isSame("password","password2",$template->checkmsg(6008));
$form1->isNotNull("firstname",$template->checkmsg(6077));
$form1->isNotNull("lastname",$template->checkmsg(6078));
$form1->isNotNull("address",$template->checkmsg(6079));
$form1->isNotNull("phone_no",$template->checkmsg(6081));
$form1->isPositiveInteger("phone_no",$template->checkmsg(1032));

$form1->isEmail("email",$template->checkmsg(6009));
$form1->isNotNull("country",$template->checkmsg(6010));
$form1->isNotNull("domain",$template->checkmsg(6011));
$form1->isWhitespacePresent("domain",$template->checkmsg(1031));
$form1->isDomain("domain",$template->checkmsg(1030));
//$form1->isNotNull("taxidentification",$template->checkmsg(10012));
$form1->isNotNull("image",$template->checkmsg(6060));

$template->setValue("{FORMSTART}",$form1->formStart());
$template->setValue("{FORMCLOSE}",$form1->formClose());


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
		
$template->setValue("{USERFIELD}",'<input type="text" name="username" id="username" width="30" size="25" onblur="show()">');
$template->setValue("{PASSFIELD}",$form1->addPassword("password",25));

$template->setValue("{PASSFIELD2}",$form1->addPassword("password2",25));
$template->setValue("{FNAMEFIELD}",$form1->addTextBox("firstname","",25));
$template->setValue("{LNAMEFIELD}",$form1->addTextBox("lastname","",25));
$template->setValue("{PHNOFIELD}",$form1->addTextBox("phone_no","",25));
$template->setValue("{ADDRESSFIELD}",'<textarea name="address" id="address"></textarea>');
$template->setValue("{COUNTRYFIELD}",$ctrstr);
$template->setValue("{DOMAINFIELD}",$form1->addTextBox("domain","",25));
$template->setValue("{EMAILFIELD}",$form1->addTextBox("email","",25));
$template->setValue("{TERMSAGREE}",'<input name="terms" type="checkbox" id="terms">');
$template->setValue("{TAXIDENTIFICATION}",$form1->addTextBox("taxidentification","",25));
$template->setValue("{IMG}",$form1->addTextBox("image","",25));
$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=0"));           
$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=0"));         



$template->setValue("{COUPONCODE}",$form1->addTextBox("coupon","",25));

$passlength=str_replace("{LENGTH}",$min_user_password_length,$template->checkAdvMsg(0002)); 
$template->setValue("{PASSMSG}",$passlength); 

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
$commess=$template->checkAdvMsg(10154); 
$commess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$commess);
$commes1=str_replace("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"",$commess1);
//echo $commes1;
$template->setValue("{COMMES}",$commes1); 
    $hmess=$template->checkAdvMsg(8924);  
$hmess1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$hmess); 
      $template->setValue("{HMESS}",$hmess1);  
 $mess2=$template->checkAdvMsg(8925);  
$hmess3=str_replace("{ENAME}",$template->checkAdvMsg(0001),$mess2); 
      $template->setValue("{HMESS3}",$hmess3);  
      
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>