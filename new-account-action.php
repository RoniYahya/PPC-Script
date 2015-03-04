<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Template");
includeClass("Form");
$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();
$comid=$mysql->echo_one("select common_account_id from ppc_users where uid='$uid'");
if($comid!=0)
{
header("Location:ppc-user-control-panel.php");
exit(0);	
}
$uname= $_COOKIE['io_username'];
$pass=$_COOKIE['io_password'];
phpsafe($uname);
phpsafe($pass);

$uid=$user->getUserID();
$username=$_POST['username'];
$password=$_POST['password'];
phpSafe($username);
phpSafe($password);
if($username==""||$password=="")
{
header("Location:show-message.php?id=1001");
exit(0);	
}
elseif(strlen($password)<$min_user_password_length)
			{
				header("Location:show-message.php?id=1003");
exit(0);
				
			}
else
{

	$password=md5($password);
$lastid="";
	$main1=mysql_query("select * from nesote_inoutscripts_users where username='$username' ");
	$main=mysql_num_rows($main1);
	$publisher1=mysql_query("select * from ppc_publishers where username='$username' ");
	$publisher=mysql_num_rows($publisher1);
	$adve1=mysql_query("select * from ppc_users where username='$username' ");
	$adve1=mysql_num_rows($adve1);
	$ad=mysql_query("select * from ppc_users where username='$uname'and password='$pass' ");
	$adve=mysql_fetch_row($ad);
	$mainemail=$mysql->echo_one("select count(*) from nesote_inoutscripts_users where email='$adve[3]' ");
	$adve_email=$mysql->echo_one("select count(*) from ppc_publishers where email='$adve[3]'");
	if(($main==0) && ($publisher==0) && ($adve1==0))
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
	mysql_query("BEGIN");
		if(!mysql_query("insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$username','$password','$name','$adve[3]','$adve[7]','1')"))
		{
			$flag=1;
		}
		 $lastid=mysql_insert_id();
		if(!mysql_query("insert into ppc_publishers (`username`, `password`,`email`, `status`,`country`,`domain`,`regtime`,`lastlogin`, `firstname`,`lastname`,`phone_no`,`address`,`common_account_id`,`parent_status`,`taxidentification`) values ('$username','$password','$adve[3]','$pstatus','$adve[5]','$adve[6]','$adve[7]','$adve[8]','$adve[12]','$adve[13]','$adve[14]','$adve[15]','$lastid','1','$adve[19]')"))
		{
			$flag=1;
		}
	if(!mysql_query("update ppc_users set common_account_id='$lastid',parent_status='1',username='$username',password='$password' where uid='$uid'"))
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
	
	setcookie("io_username",$username,0,'/');
				setcookie("io_password",$password,0,'/');
				setcookie("io_type",md5("Common"),0,'/');
	 $sub=$mysql->echo_one("select email_subject from email_templates where id='2'");
	$sub=str_replace("{USERNAME}",$username,$sub);
	$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
	$body=$mysql->echo_one("select email_body from email_templates where id='2'");
	$body=str_replace("{USERNAME}",$username,$body);
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

if($pub_status==0)
{
   header("Location:show-success.php?id=9996&page=control-panel.php");
	exit(0);  
	
	}
	else
	{
		header("Location:show-success.php?id=9995&page=control-panel.php");
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
		header("Location:show-message.php?id=1027");
exit(0);	
	}
}
?>