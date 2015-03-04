<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

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



$uid=$_POST['uid'];
//echo $uid;
$action=$_POST['action'];

$text=$_POST['text'];
$url=urldecode( $_POST['url']);
$subject=$_POST['subject'];
if(get_magic_quotes_gpc())
	{
	$text=stripslashes($text);
	$subject=stripslashes($subject);
	}
$email=$mysql->echo_one("select email from ppc_publishers where uid=$uid");


  if($script_mode!="demo")
 {

include("class.Email.php");

$message = new Email($email, $admin_general_notification_email, $subject, '');
$message->Cc = ''; 
$message->Bcc = ''; 

$message->SetHtmlContent(nl2br($text));  
$message->Send();

	}

if($action=="block")
{
	//echo "update ppc_users set status=0 where uid=$uid;";
	mysql_query("update ppc_publishers set status=0 where uid=$uid;");
}
if($action=="activate")
{//echo "UPDATE `ppc_publishers` SET `status` = '1' WHERE `uid`='$uid'";
	mysql_query("UPDATE `ppc_publishers` SET `status` = '1' WHERE `uid`='$uid'");
}
header("location:$url");
exit(0);
?>