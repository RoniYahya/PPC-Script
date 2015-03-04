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

$redir=urldecode(trim($_POST['redir']));
//print_r($_POST);
$sub=trim($_POST['email_sub']);
phpSafe($sub);		

$body=trim($_POST['email_body']);
phpSafe($body);		

if($script_mode=="demo")
{
include("admin.header.inc.php");
?>
<br>
<span class="already">You cannot do this in demo. <a href="javascript:history.back(-1);">Go Back</a></span>
<br>
<br>
<?php
include("admin.footer.inc.php");
exit(0);
}


if($sub=="" || $body=="")
{
include("admin.header.inc.php");
?>
<br>
<span class="already">Please fill both subject and body. <a href="javascript:history.back(-1);">Go Back</a></span>
<br>
<br>
<?php
include("admin.footer.inc.php");
exit(0);
}

$type=$_POST['type'];
if($mysql->total("email_templates","id='$type'")==0)
{
	mysql_query("insert into email_templates (id,email_subject,email_body) values ('$type','$sub','$body')");
}
else
{
	mysql_query("update  email_templates set email_subject='$sub',email_body='$body' where id='$type'");
}

header("Location:".$redir);
exit(0);
?>
