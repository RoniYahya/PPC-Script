<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Template");
includeClass("Form");
$user=new User("ppc_users");
if($single_account_mode==0)
{
	header("Location: index.php");
exit(0);
}
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}

$uname= $_COOKIE['io_username'];
$pass=$_COOKIE['io_password'];
phpsafe($uname);
phpsafe($pass);

$uid=$user->getUserID();
$advertiser=mysql_query("select * from ppc_users where username='$uname' and password='$pass'");
$adve=mysql_fetch_row($advertiser);
if($adve[17]!=0)
{
	header("Location:ppc-user-control-panel.php");
exit(0);
}

if($_POST['radiobutton']==1) //yes
{
//	$username=$_POST['username'];
//	$password=$_POST['password'];
//phpSafe($username);
//phpSafe($password);
//if($username==""||$password=="")
//{
//header("Location:show-message.php?id=1001");
//exit(0);	
//}
//else
//
//{
//	$password=md5($password);
//	
//	$publisher1=mysql_query("select * from ppc_publishers where username='$username' and password='$password'");
//	$publisher=mysql_num_rows($publisher1);
//	if($publisher==0)
//	{
//		echo "please enter correct username and password";
//		exit;
//	}
//	else
//	{
//		header("Location:ppc-single-account.php");
//		exit;
//	}
//}

}
else //no
{
	
	
$lastid="";
	$condition="username='$uname' and password='$pass'";
	
	$main1=mysql_query("select * from nesote_inoutscripts_users where username='$uname' ");
	$main=mysql_num_rows($main1);
	$publisher1=mysql_query("select * from ppc_publishers where username='$uname'");
	$publisher=mysql_num_rows($publisher1);
	$mainemail=$mysql->echo_one("select count(*) from nesote_inoutscripts_users where email='$adve[3]' ");
	$adve_email=$mysql->echo_one("select count(*) from ppc_publishers where email='$adve[3]'");
	if(($main==0) && ($publisher==0))
	{
		if(($mainemail==0) && ($adve_email==0))
		{
		if($pub_status==1)
		{
			$pstatus=1;
		}
		else
		{
			$pstatus=-1;
		}
		$name=$adve[12]." ".$adve[13];
//		echo "insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$uname','$pass','$name','$adve[3]','$adve[7]','1')')";
//		exit;
		mysql_query("BEGIN");
		if(!mysql_query("insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$uname','$pass','$name','$adve[3]','$adve[7]','1')"))
		{
			$flag=1;
		}
		 $lastid=mysql_insert_id();
		if(!mysql_query("insert into ppc_publishers (`username`, `password`,`email`, `status`,`country`,`domain`,`regtime`,`lastlogin`,`firstname`,`lastname`,`phone_no`,`address`,`common_account_id`,`parent_status`,`taxidentification`) values ('$uname','$pass','$adve[3]','$pstatus','$adve[5]','$adve[6]','$adve[7]','$adve[8]','$adve[12]','$adve[13]','$adve[14]','$adve[15]','$lastid','1','$adve[19]')"))
		{
			$flag=1;
		}
	if(!mysql_query("update ppc_users set  common_account_id='$lastid',parent_status='1' where uid='$uid'"))
{
	$flag=1;
}

	if($flag==1)
{
		mysql_query("ROLLBACK");
		header("Location:show-message.php?id=1004");
	exit(0);
}
else
{
	mysql_query("COMMIT");
	
	setcookie("io_type",md5("Common"),0,'/');
	 $sub=$mysql->echo_one("select email_subject from email_templates where id='2'");
	$sub=str_replace("{USERNAME}",$uname,$sub);
	$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
	$body=$mysql->echo_one("select email_body from email_templates where id='2'");
	$body=str_replace("{USERNAME}",$uname,$body);
	//$body=str_replace("{PASSWORD}",$password,$body);
	$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
	$body=str_replace("{PUB_LOGIN_PATH}",$server_dir."login.php",$body);
    $body=html_entity_decode($body,ENT_QUOTES);
    
    $body=html_entity_decode($body,ENT_QUOTES);

if($script_mode!="demo")
{
  include($GLOBALS['admin_folder']."/class.Email.php");
  
	  $message = new Email($adve[3], $admin_general_notification_email, $sub, '');
	  $message->Cc = ''; 
	  $message->Bcc = ''; 
	  $message->SetHtmlContent(nl2br($body));  
	  $message->Send();
	  
	 
}	
if($pstatus==-1)
{
   header("Location:show-success.php?id=9995&page=control-panel.php");
	exit(0);  
	
	}
	else
	{
		header("Location:show-success.php?id=9996&page=control-panel.php");
	exit(0);  
	}
}
	}
	else
	{
		header("Location:show-message.php?id=8896");
	exit(0);
	}
}
	else
	{
		$template=new Template();
		$template->loadTemplate("ppc-templates/confirm-publisher-account-action.tpl.html");
	$form=new Form("userLogin","new-account-action.php");	
	$template->includePage("{TABS}","advertiser-loggedin-header.php");	
	$template->includePage("{FOOTER}","common-footer.php");	
		
$form->isNotNull("username",$template->checkmsg(6001));
$form->isNotNull("password",$template->checkmsg(6002));
$form->isWhitespacePresent("username",$template->checkmsg(1028));
$form->isNotShort("password",$min_user_password_length,$template->checkmsg(6005));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(8883)));
$template->setValue("{USERFIELD}",'<input type="text" name="username" id="un" onblur="show()">');
$template->setValue("{PASSFIELD}",$form->addPassword("password"));

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
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));  

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
eval('?>'.$template->getPage().'<?php ');
	
		
	}
	
}
?>