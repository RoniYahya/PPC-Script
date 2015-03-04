<?php 



/*--------------------------------------------------+

|													 |

| Copyright ? 2006 http://www.inoutscripts.com/      |

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
	$type=$_GET['type'];
	
//	exit(0);
	
	if($type==1)
	{

			//mysql_query("delete from ppc_publishing_urls where id='$id'");
			
			mysql_query("UPDATE ppc_publishing_urls SET `status` = '1' WHERE `ppc_publishing_urls`.`id`='$id'");

		
			header("Location:manage-ppc-publishing-urls.php");
			//header('location:manage-ppc-publishing-urls.php');
			exit;
	}
	

   else if($type==2)
	{

			//mysql_query("delete from ppc_publishing_urls where id='$id'");
			
			mysql_query("UPDATE `ppc_publishing_urls` SET `status` = '2' WHERE `ppc_publishing_urls`.`id`='$id'");

		
			header("Location:manage-ppc-publishing-urls.php");
			//header('location:manage-ppc-publishing-urls.php');
			exit;
	}
	else
	{
			header("Location:manage-ppc-publishing-urls.php");
			//header('location:manage-ppc-publishing-urls.php');
			exit;
	}
 ?>