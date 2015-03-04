<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php

include("config.inc.php");

if(!isset($_COOKIE['inout_admin']))
{
	header("Location:index.php");
	exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	header("Location:index.php");
	exit(0);
}


?><?php //include("admin.header.inc.php"); ?>
<?php

$uid=$_POST['uid'];
$url=urldecode( $_POST['url']);
$text=$_POST['text'];
if(get_magic_quotes_gpc())
	$text=stripslashes($text);
	
$email=$mysql->echo_one("select email from ppc_publishers where uid=$uid");


$subject=$_POST['subject'];
if(get_magic_quotes_gpc())
	{
	$text=stripslashes($text);
	$subject=stripslashes($subject);
	}
	
		  if($script_mode!="demo")
 {

  include("class.Email.php");
  
	  $message = new Email($email, $admin_general_notification_email, $subject, '');
	  $message->Cc = ''; 
	  $message->Bcc = ''; 
	  $message->SetHtmlContent(nl2br($text));  
	$message->Send()	;
	
}
	
				mysql_query("update ppc_publishers set status=0 where uid=$uid;");
					header("location:$url");
				exit(0);
				
				?>