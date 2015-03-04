<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");


$subject=$_POST['subject'];
$body=$_POST['body'];
$name=$_POST['name'];
$emailid=$_POST['email'];
$priority=$_POST['priority'];

removeGpcPrefixedSlashes($subject);
removeGpcPrefixedSlashes($body);
removeGpcPrefixedSlashes($name);
//phpSafe($emailid);
//phpSafe($priority);
if($subject=="" || $body=="" || $name=="" || $emailid=="" )
{
	if($type==md5("publisher"))// for publisher pages
		{
		header("Location:publisher-show-message.php?id=5009");
		}
	if($type==md5("advertiser"))
		{
		header("Location:show-message.php?id=5009");
		}
		if($type==md5("Common"))
		{
		header("Location:error-message.php?id=5009");
		}		
		exit(0);
}

$type=$_COOKIE['io_type'];
if($type==md5("publisher"))
{
	    $subject="New Publisher Support Ticket ($priority) - $subject";      
		$user=new User("ppc_publishers");
		if(!($user->validateUser()))
		{
			header("Location:publisher-show-message.php?id=1006");
			exit(0);
		}
}
if($type==md5("advertiser"))
{
    $subject="New Advertiser Support Ticket ($priority) - $subject";
	$user=new User("ppc_users");
	if(!($user->validateUser()))
	{
		header("Location:show-message.php?id=1006");
		exit(0);
	}
}

if($type==md5("Common"))
{
    $subject="New User Support Ticket ($priority) - $subject";
	$user=new User("nesote_inoutscripts_users","id");
	if(!($user->validateUser()))
	{
		header("Location:error-message.php?id=1006");
		exit(0);
	}
}


include($GLOBALS['admin_folder']."/class.Email.php");
$Sender = $name." <".$emailid.">";
$message = new Email($admin_general_notification_email, $Sender, $subject, '');
$message->Cc = ''; 
$message->Bcc = ''; 

$message->SetHtmlContent(nl2br($body));  
if($message->Send())
{				
	if($type==md5("publisher"))// for publisher pages
		{
		header("Location:publisher-show-success.php?id=5008");
		}
	if($type==md5("advertiser"))
		{
		header("Location:show-success.php?id=5008");
		}
if($type==md5("Common"))
		{
		header("Location:success-message.php?id=5008");
		}	
	exit(0);
}
else
{
	if($type==md5("publisher"))// for publisher pages
		{
		header("Location:publisher-show-message.php?id=5009");
		}
	if($type==md5("advertiser"))
		{
		header("Location:show-message.php?id=5009");
		}
		if($type==md5("Common"))
		{
		header("Location:error-message.php?id=5009");
		}		
	exit(0);
}





/*
if(strcasecmp ( $email_encoding,"UTF-8")==0)
{
	$subject="=?UTF-8?B?".base64_encode($subject)."?=";
	$name="=?UTF-8?B?".base64_encode($name)."?=";
	$mime_boundary = "----adserver----".md5(time());
	
	$headers = "From: $name<$emailid>\r\n";
	$headers .= "Reply-To: $name<$emailid>\r\n";
	$headers .= "X-Mailer: PHP/" . phpversion()."\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: multipart/mixed; boundary=\"$mime_boundary\"\r\n";
	
	$message = "--$mime_boundary\r\n";
	$message .= "Content-Type: text/plain; charset=UTF-8\r\n";
	$message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
	$message .= $body."\r\n\r\n";
	$message .= "--$mime_boundary\r\n";
	
	if(mail("$admin_general_notification_email", "$subject", $message,$headers))
	{
		if($type==md5("publisher"))// for publisher pages
			{
			header("Location:publisher-show-success.php?id=5008");
			}
		if($type==md5("advertiser"))
			{
			header("Location:show-success.php?id=5008");
			}	
		exit(0);
	}
	else
	{
		if($type==md5("publisher"))// for publisher pages
			{
			header("Location:publisher-show-message.php?id=5009");
			}
		if($type==md5("advertiser"))
			{
			header("Location:show-message.php?id=5009");
			}	
		exit(0);
	}
}

if(mail("$admin_general_notification_email", "$subject", $body,
     "From: $name<$emailid>\r\n"
    ."Reply-To: $name<$emailid>\r\n"
    ."X-Mailer: PHP/" . phpversion()))
{
	if($type==md5("publisher"))// for publisher pages
		{
		header("Location:publisher-show-success.php?id=5008");
		}
	if($type==md5("advertiser"))
		{
		header("Location:show-success.php?id=5008");
		}	
	exit(0);
}
else
{
	if($type==md5("publisher"))// for publisher pages
		{
		header("Location:publisher-show-message.php?id=5009");
		}
	if($type==md5("advertiser"))
		{
		header("Location:show-message.php?id=5009");
		}	
	exit(0);
}
*/

?>
