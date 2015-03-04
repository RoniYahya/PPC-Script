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

if($script_mode=="demo")	
{
include("admin.header.inc.php");
?>
<br><br>
<span class="already">You cannot do this in demo. <a href="javascript:history.back(-1)">Click here</a> to go back.</span>
<br><br>
<?php 
include("admin.footer.inc.php");	
exit(0);
}
$redir=urldecode(trim($_POST['redir']));
//print_r($_POST);
$terms=trim($_POST['terms']);
if(!get_magic_quotes_gpc() )
 	$terms=mysql_real_escape_string($terms);		
$type=$_POST['type'];
if($mysql->total("terms_and_conditions","type=$type")==0)
{
	mysql_query("insert into terms_and_conditions (terms,type) values ('$terms',$type)");
}
else
{
	mysql_query("update  terms_and_conditions set terms='$terms' where type=$type");
}
header("Location:$redir");
exit(0);
?>
