<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Template");
includeClass("Form");
if($single_account_mode!=1)
{
	header("Location: index.php");
exit(0);	
	
}
$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}

$uname= $_COOKIE['io_username'];
$pass=$_COOKIE['io_password'];
phpsafe($uname);
phpsafe($pass);
$uid=$user->getUserID();
$publish=mysql_query("select * from ppc_publishers where username='$uname' and password='$pass'");
$pub=mysql_fetch_row($publish);
if($pub[19]!=0)
{
	header("Location:ppc-publisher-control-panel.php");
exit(0);
}

if($_POST['radiobutton']!=1) //no
{

$lastid="";
	$condition="username='$uname' and password='$pass'";
	
	$main1=mysql_query("select * from nesote_inoutscripts_users where username='$uname' ");
	$main=mysql_num_rows($main1);
	$adve1=mysql_query("select * from ppc_users where username='$uname'");
	$adve=mysql_num_rows($adve1);
		$mainemail=$mysql->echo_one("select count(*) from nesote_inoutscripts_users where email='$pub[3]' ");
	$adve_email=$mysql->echo_one("select count(*) from ppc_users where email='$pub[3]'");
	
//echo $main." ".$adve;
//exit;
	if(($main==0) && ($adve==0))
	{
		if(($mainemail==0) && ($adve_email==0))
		{
		if($adv_status==1)
		{
			$astatus=1;
		}
		else
		{
			$astatus=-1;
		}
		$name=$pub[15]." ".$pub[16];
//		echo "insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$uname','$pass','$name','$adve[3]','$adve[7]','1')')";
//		exit;
		mysql_query("BEGIN");
		if(!mysql_query("insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$uname','$pass','$name','$pub[3]','$pub[7]','1')"))
		{
			$flag=1;
		}
		 $lastid=mysql_insert_id();
		if(!mysql_query("insert into ppc_users (`username`, `password`,`email`, `status`,`country`,`domain`,`regtime`,`lastlogin`,`firstname`,`lastname`,`phone_no`,`address`,`common_account_id`,`parent_status`,`taxidentification`) values ('$uname','$pass','$pub[3]','$astatus','$pub[5]','$pub[6]','$pub[7]','$pub[8]','$pub[15]','$pub[16]','$pub[17]','$pub[18]','$lastid','1','$pub[24]')"))
		{
			$flag=1;
		}
	if(!mysql_query("update ppc_publishers set  common_account_id='$lastid',parent_status='1' where uid='$uid'"))
{
	$flag=1;
}

	if($flag==1)
{
		mysql_query("ROLLBACK");
		header("Location:publisher-show-message.php?id=1004");
	exit(0);
}
else
{
	mysql_query("COMMIT");
	
		setcookie("io_type",md5("Common"),0,'/');
	 $sub=$mysql->echo_one("select email_subject from email_templates where id='1'");
	$sub=str_replace("{USERNAME}",$uname,$sub);
	$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
	$body=$mysql->echo_one("select email_body from email_templates where id='1'");
	$body=str_replace("{USERNAME}",$uname,$body);
	//$body=str_replace("{PASSWORD}",$password,$body);
	$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
	$body=str_replace("{ADV_LOGIN_PATH}",$server_dir."login.php",$body);
    $body=html_entity_decode($body,ENT_QUOTES);
    
    $body=html_entity_decode($body,ENT_QUOTES);

if($script_mode!="demo")
{
  include($GLOBALS['admin_folder']."/class.Email.php");
  
	  $message = new Email($pub[3], $admin_general_notification_email, $sub, '');
	  $message->Cc = ''; 
	  $message->Bcc = ''; 
	  $message->SetHtmlContent(nl2br($body));  
	  $message->Send();
	  
	 
}	
if($astatus==-1)
{
   header("Location:publisher-show-success.php?id=9993&page=control-panel.php");
	exit(0);  
}
else
{
	header("Location:publisher-show-success.php?id=9994&page=control-panel.php");
	exit(0);  
}
}
		}
		else
		{
			header("Location:publisher-show-message.php?id=8896");
	exit(0);
		}
	}
	else
	{
		$template=new Template();
		$template->loadTemplate("publisher-templates/confirm-advertiser-account-action.tpl.html");
	$form=new Form("userLogin","new-publisher-account-action.php");	
	$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
		
$form->isNotNull("username",$template->checkmsg(6001));
$form->isNotNull("password",$template->checkmsg(6002));
$form->isWhitespacePresent("username",$template->checkmsg(1028));
$form->isNotShort("password",$min_user_password_length,$template->checkmsg(6005));
//$template->setValue("{SUBMIT}",$form->addSubmit($template->checkMsg(8883)));
$template->setValue("{USERFIELD}",'<input type="text" name="username" id="un" onblur="show()">');
$template->setValue("{PASSFIELD}",$form->addPassword("password"));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(8895)));
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
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
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
eval('?>'.$template->getPage().'<?php ');
	
		
	}
	
}
?>