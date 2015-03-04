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


$type=intval($_GET['type']);
$id=intval($_GET['id']);
$level=$mysql->echo_one("select level from affiliate_banners where id='$id'");

if($type==1)
{
mysql_query("update affiliate_banners set level=$level where level=".($level+1)."");
mysql_query("update affiliate_banners set level=".($level+1)." where id=$id");
}
else
{
mysql_query("update affiliate_banners set level=$level where level=".($level-1)."");
mysql_query("update affiliate_banners set level=".($level-1)." where id=$id");
}
echo mysql_error();
header("Location:affiliate-banners.php");
?>


