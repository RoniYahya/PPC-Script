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
$keywords=trim($_POST['keywords']);
$desc=trim($_POST['desc']);
phpSafe($keywords);		
phpSafe($desc);		
$type=$_POST['type'];
if($mysql->total("site_content","item_type=$type and item_name='meta-keywords'")==0)
{
	mysql_query("insert into site_content (item_name,item_value,item_type) values ('meta-keywords','$keywords',$type)");
}
else
{
	mysql_query("update  site_content set item_value='$keywords' where item_type=$type and item_name='meta-keywords'");
}
if($mysql->total("site_content","item_type=$type and item_name='meta-description'")==0)
{
	mysql_query("insert into site_content (item_name,item_value,item_type) values ('meta-description','$desc',$type)");
}
else
{
	mysql_query("update  site_content set item_value='$desc' where item_type=$type and item_name='meta-description'");
}
header("Location:$redir");
exit(0);
?>
