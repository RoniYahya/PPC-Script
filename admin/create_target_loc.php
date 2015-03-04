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



//************************************************************* Logic For GeoTargeting *********************************************
$row=mysql_query("select id from ppc_ads");
if(mysql_num_rows($row) > 0)
{
while($rowdata=mysql_fetch_row($row))
{
$geo_cnt=$mysql->echo_one("select id from ad_location_mapping where adid='$rowdata[0]'");
if($geo_cnt =="")
mysql_query("INSERT INTO `ad_location_mapping` (`adid` , `country` , `region` , `city`) VALUES ('$rowdata[0]', '00', '00', '00')");

}
}
//************************************************************* Logic For GeoTargeting *********************************************










include_once("admin.header.inc.php");	
?>
<br>
Country targeting  has been added for all ads.<a href="ppc-admin-to-do.php"><br>
<strong>Proceed</strong></a><br><br>
<?php include_once("admin.footer.inc.php");
?>