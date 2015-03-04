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

$url=urldecode($_POST['url']);
?><?php include("admin.header.inc.php"); ?>
<?php

$email=$_POST['email'];

$text=$_POST['text'];
$subject=$_POST['subject'];

if(get_magic_quotes_gpc())
{
	$text=stripslashes($text);
	$subject=stripslashes($subject);
}

  include("class.Email.php");
  
	  $message = new Email($email, $admin_general_notification_email, $subject, '');
	  $message->Cc = ''; 
	  $message->Bcc = ''; 
	  $message->SetHtmlContent(nl2br($text));  
	if($message->Send())
				{
				?>
				<br><br><span class="inserted">Email has been sent.</span><br><br>
<?php 
}
else
{
?>
				<br><br><span class="already">Email sending failed.</span><br><br>
<?php 
}?>
				<a href="<?php echo $url; ?>">Click here to go back to the page you were viewing</a> <br>
<br>
<?php include("admin.footer.inc.php"); ?>