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
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
	{
	header("Location:index.php");
	}


?><?php //include("admin.header.inc.php"); 


	$id=$_GET['urid'];
	
	//exit(0);
	



			mysql_query("delete from ppc_publishing_urls where id='$id'");
		
			header("Location:manage-ppc-publishing-urls.php");
			//header('location:manage-ppc-publishing-urls.php');
 ?>