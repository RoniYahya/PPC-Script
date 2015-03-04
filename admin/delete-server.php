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
phpsafe($id);


 $srv=$mysql->echo_one("select srv_type from server_configurations where id='$id'");

    if($srv != 1)
	{
	mysql_query("delete from `server_configurations` where id=$id;");
	
	
	
	mysql_query("update ppc_publishers set server_id='0' where server_id='$id'");
	
	mysql_query("delete from `statistics_updation` where task='advertiser_impression_hourly_slave_".$id."'");
	mysql_query("delete from `statistics_updation` where task='publisher_impression_hourly_slave_".$id."'");
	mysql_query("delete from `statistics_updation` where task='visit_back_slave_".$id."'");
	mysql_query("delete from `statistics_updation` where task='visit_back_update_slave_".$id."'");
	
	
	
	
	
	}
	
	
	
	header("Location:$url");
	
?>