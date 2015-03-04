<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
//includeClass("User");
//$user=new User("ppc_users");


$id=$_GET["id"];




phpSafe($id);


$result=mysql_query("select * from  nesote_inoutscripts_users where id=$id and status='-2' ");
if($row=mysql_fetch_row($result)) 
{
	$adv_status=$GLOBALS['adv_status'];
$pub_status=$GLOBALS['pub_status'];

   $username=$row[1]; 
    mysql_query("BEGIN"); 
    
   if($portal_system==0)
   {
       if(!mysql_query("UPDATE nesote_inoutscripts_users SET status='1' WHERE id=$id and status='-2'"))
        {
          $flag1=1;
         }
   }
if(!mysql_query("UPDATE ppc_users SET status='$adv_status' WHERE common_account_id=$id and status='-2'"))
{
	$flag1=1;
}
if(!mysql_query("UPDATE ppc_publishers SET status='$pub_status' WHERE common_account_id=$id and status='-2'"))
{
	$flag1=1;
}

if($flag1==1)
{
		mysql_query("ROLLBACK");
		header("Location:error-message.php?id=1004");
	exit(0);
}
else
{
	mysql_query("COMMIT");




    $email=$row[4];
      //   $password=$row[2];
       if($adv_status==1)
       {
       	$astatus="active";
       	
       }
       else
       {
       		$astatus="pending";
       }
       if($pub_status==1)
       {
       		$pstatus="active";
       }
       else
       {
       	$pstatus="pending";
       }
      
    $sub=$mysql->echo_one("select email_subject from email_templates where id='24'");
	$sub=str_replace("{USERNAME}",$username,$sub);
	$sub1=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
	$sub2=str_replace("{ADV_STATUS}",$astatus,$sub1);
	$sub3=str_replace("{PUB_STATUS}",$pstatus,$sub2);
	$body=$mysql->echo_one("select email_body from email_templates where id='24'");
	$body=str_replace("{USERNAME}",$username,$body);
	//$body=str_replace("{PASSWORD}",$password,$body);
	$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
	$body=str_replace("{ADV_LOGIN_PATH}",$server_dir."login.php",$body);
	$body=str_replace("{ADV_STATUS}",$astatus,$body);
	$body=str_replace("{PUB_STATUS}",$pstatus,$body);
    $body=html_entity_decode($body,ENT_QUOTES);
   
    
	if($script_mode!="demo")
	{

		include($GLOBALS['admin_folder']."/class.Email.php");
        
		
		$message = new Email($email, $admin_general_notification_email, $sub3, '');
		$message->Cc = '';
		$message->Bcc = '';
		$message->SetHtmlContent(nl2br($body));
		$message->Send();
		//admin notification mail
	}
	
	$msg = <<< EOB

	Hello,

	You have a new registration at $ppc_engine_name.

	Username		: $username
	Email		 	: $email


	Login to your admin area to see further details.

	Regards,
	$ppc_engine_name
EOB;

	//echo $msg;
	if($script_mode!="demo")
	xMail($admin_general_notification_email, "$ppc_engine_name - New Registration", $msg, $admin_general_notification_email, $email_encoding);

	if($adv_status=='1')
	{
	//	$user->cookieUser($username,$password,"advertiser");
		header("Location:success-message.php?id=10016");
        exit(0);	
		
	}//end if($adv_status=='1')
	header("Location:success-message.php?id=5018");
	exit(0);
}
} 
else
{
	header("Location:error-message.php?id=1037");
	exit(0);
}

?>