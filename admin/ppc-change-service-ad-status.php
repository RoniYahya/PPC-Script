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

$action = $_GET['action'];
$id = $_GET['id'];
phpsafe($id);
$url=urldecode($_GET['url']);

//echo $script_mode;
if($action=='block')
{
	if($script_mode=="demo" )
	{
 include("admin.header.inc.php"); 
		?>
		<span class="already"><br>You cannot block public service ads in demo !<br><br></span>
		<?php 
		include("admin.footer.inc.php");
		exit(0);
	}
	$result=mysql_query("update ppc_public_service_ads set status=0 where id = '$id' ");
}
else
{
$result=mysql_query("update ppc_public_service_ads set status=1 where id = '$id' ");
}
header("location:$url");
				exit(0);
 ?>


