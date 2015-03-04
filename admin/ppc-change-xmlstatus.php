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
$url=urldecode($_GET['url']);
//echo $url;
//exit(0);


$uid=$_GET['id'];
phpsafe($uid);
$action=$_GET['action'];
$category=$_GET['category'];
if($action=="disable")
{
//echo "update ppc_users set status=0 where uid=$uid;";
mysql_query("update ppc_publishers set xmlstatus=0 where uid='$uid';");
}
if($action=="enable")
{
mysql_query("update ppc_publishers set xmlstatus=1 where uid='$uid';");

$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}
}
header("Location:$url");
exit(0);
 ?>