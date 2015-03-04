<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
//includeClass("User");
//$user=new User("ppc_users");


$id=$_GET["id"];
//$username=$_GET["username"];



phpSafe($id);


$result=mysql_query("select * from  ppc_publishers where uid=$id  and status='-2'" );
$row=mysql_fetch_row($result); 

 $pub_status=$GLOBALS['pub_status'];

if($row[0]!='')
{
    mysql_query("UPDATE ppc_publishers SET status='$pub_status' WHERE uid=$id");
    $email=$row[3];
	$username=$row[1];
       //    $password=$row[2];
    $sub=$mysql->echo_one("select email_subject from email_templates where id='2'");
	$sub=str_replace("{USERNAME}",$username,$sub);
	$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
	$body=$mysql->echo_one("select email_body from email_templates where id='2'");
	$body=str_replace("{USERNAME}",$username,$body);
	//$body=str_replace("{PASSWORD}",$password,$body);
	$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
	$body=str_replace("{PUB_LOGIN_PATH}",$server_dir."ppc-publisher-login.php",$body);
    $body=html_entity_decode($body,ENT_QUOTES);
    
    
	if($script_mode!="demo")
	{

		include($GLOBALS['admin_folder']."/class.Email.php");
        
		
		$message = new Email($email, $admin_general_notification_email, $sub, '');
		$message->Cc = '';
		$message->Bcc = '';
		$message->SetHtmlContent(nl2br($body));
		$message->Send();
		//admin notification mail
	}
	
	$msg = <<< EOB

Hello,

You have a new publisher registration at $ppc_engine_name.

Username		: $username
Email		 	: $email


Login to your admin area to see further details.

Regards,
$ppc_engine_name

EOB;

//echo $msg;

if($script_mode!="demo")
xMail($admin_general_notification_email, "$ppc_engine_name - New Publisher Registration", $msg, $admin_general_notification_email, "$email_encoding");
	

	if($pub_status=='1')
	{
	//	$user->cookieUser($username,$password,"advertiser");
		header("Location:show-success.php?id=5036");
        exit(0);	
		
	}//end if($adv_status=='1')
	
	header("Location:show-success.php?id=5018");
	exit(0);
	
} 
else
{
	header("Location:show-message.php?id=1037");
	exit(0);
}

?>