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
include("../extended-config.inc.php");  
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



$id=$_POST['id'];
phpsafe($id);

$text=$_POST['text'];
$url=urldecode($_POST['url']);
$send_mail=$_POST['send_mail'];
$subject=$_POST['subject'];
if(get_magic_quotes_gpc())
	{
	$text=stripslashes($text);
	$subject=stripslashes($subject);
	}
$email=$mysql->echo_one("select b.email from ppc_ads a,ppc_users b where a.uid=b.uid and a.id='$id'");
$uid=$mysql->echo_one("select b.uid from ppc_ads a,ppc_users b where a.uid=b.uid and a.id='$id'");
//$email=$mysql->echo_one("select email from ppc_publishers where uid=$uid");

  if($script_mode!="demo")
 {

if($send_mail==1)
	{
	
	include("class.Email.php");
	$message = new Email($email, $admin_general_notification_email, $subject, '');
	$message->Cc = ''; 
	$message->Bcc = ''; 
	$message->SetHtmlContent(nl2br($text));  
	$message->Send();
	}
}	
mysql_query("delete from ppc_ads where uid='$uid' AND id='$id'");
mysql_query("delete from ppc_keywords where uid='$uid' AND aid='$id'");
mysql_query("delete from `ad_location_mapping` where adid='$id'");
			$mydir = "../".$GLOBALS['banners_folder']."/$id/"; 
		$d = dir($mydir); 
		if($d)
		{
		while($entry = $d->read()) { 
		 if ($entry!= "." && $entry!= "..") { 
		 
		 unlink("../".$GLOBALS['banners_folder']."/$id/".$entry); 
		 } 
		} 
		$d->close(); 
		rmdir($mydir); 
		}

header("Location:$url");
exit(0);
?>