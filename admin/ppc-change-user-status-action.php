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
?>
<?php //include("admin.header.inc.php"); ?>
<?php

$uid=$_POST['uid'];
$action=$_POST['action'];
$text=$_POST['text'];
//echo $_POST['url'];exit;
$url=urldecode($_POST['url']);
//echo $url;exit;
//echo $action;
if(get_magic_quotes_gpc())
	$text=stripslashes($text);

$email=$mysql->echo_one("select email from ppc_users where uid=$uid");

$subject=$_POST['subject'];
if(get_magic_quotes_gpc())
	$subject=stripslashes($subject);
include("class.Email.php");

  if($script_mode!="demo")
 {

$message = new Email($email, $admin_general_notification_email, $subject, '');
$message->Cc = ''; 
$message->Bcc = ''; 

$message->SetHtmlContent(nl2br($text));  
$message->Send();

	}


if($action=="block")
{
	//echo "update ppc_users set status=0 where uid=$uid;";
	mysql_query("update ppc_users set status=0 where uid=$uid;");
}
if($action=="activate")
{
	mysql_query("update ppc_users set status=1 where uid=$uid;");
//if(($portal_system!=1))
//				{
//					$mainid=$mysql->echo_one("select common_account_id from ppc_users where uid=$uid");
//					mysql_query("update nesote_inoutscripts_users set status=1 where id=$mainid;");
//				}
}
if($action=="approve")
{
	mysql_query("update ppc_users set status=1 where uid=$uid;");
if(($portal_system!=1))
				{
					$mainid=$mysql->echo_one("select common_account_id from ppc_users where uid=$uid");
					mysql_query("update nesote_inoutscripts_users set status=1 where id=$mainid;");
				}
}
if($action=="reject")
{
	//echo "update ppc_users set status=0 where uid=$uid;";
	mysql_query("update ppc_users set status=0 where uid=$uid;");
}
//echo $url;exit;

header("location:$url");
exit(0);
?>


