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



$url=urldecode($_GET['url']);
$id=$_GET['id'];
$action=$_GET['action'];
phpsafe($id);








if($action=="block")
{
	mysql_query("update gift_code set status=2 where id=$id;");
}
if($action=="activate")
{
	mysql_query("update gift_code set status=1 where id=$id;");
}





	header("Location: $url");
	exit(0);
?>