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

 if($ad_keyword_mode==1)
{
include_once("admin.header.inc.php");	
?>
<br>Your adserver operation mode is now set to "Keyword based". <br>You cannot generate default keyword in this mode. <a href="javascript:history.back(-1)"><br><strong>Go Back</strong></a><br><br>
<?php include_once("admin.footer.inc.php");
exit(0);
} 
else
{
 if($keywords_default=="")
{
include_once("admin.header.inc.php");	
?>
<br><br><span class="already">You cannot generate default keyword. Please enter a valid default keyword and retry default keyword generation.</span><a href="javascript:history.back(-1)"><br><strong>Go Back</strong></a><br><br>
<?php include_once("admin.footer.inc.php");
exit(0);
} 
	$tot_ads=$mysql->echo_one("select count(id) from ppc_ads");
	$ads_with_keyword=$mysql->echo_one("select count(distinct(aid)) from ppc_keywords where keyword='$keywords_default'");
	if( $tot_ads-$ads_with_keyword==0)
	{
		include_once("admin.header.inc.php");	
		?>
		<br>There are zero ads which do not have default keyword now. <a href="javascript:history.back(-1)"><br><strong>Go Back</strong></a><br><br>
		<?php include_once("admin.footer.inc.php");
		exit(0);
	}
}
//echo  "NA"; else echo; 
//
$tot_ads=mysql_query("select id,uid from ppc_ads");
while($row=mysql_fetch_row($tot_ads))
	{
		$default_key_id=$mysql->echo_one("select id from system_keywords where keyword='$keywords_default'");
	$is_default_keyword=$mysql->echo_one("select count(*) from ppc_keywords where aid='$row[0]' and keyword='$keywords_default'");
	if($is_default_keyword==0)
		{
		mysql_query("insert into ppc_keywords values('0','$row[0]','$row[1]','$keywords_default','$min_click_value','$auto_keyword_approve','".time()."','$min_click_value','$default_key_id');");
		}
	}
	
include_once("admin.header.inc.php");	
?>
<br>Default keyword has been added for all ads.<a href="ppc-admin-to-do.php"><br><strong>Proceed</strong></a><br><br>
<?php include_once("admin.footer.inc.php");
?>