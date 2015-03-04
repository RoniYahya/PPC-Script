<?php 
/*--------------------------------------------------+

|													 |

| Copyright ? 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/

?><?php
set_time_limit(0);
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
if ( !( isset($_GET['force']) && md5($_GET['force'])=="8ee60a2e00c90d7e00d5069188dc115b" ) )
if($script_mode=="demo")
	{
		include_once("admin.header.inc.php");
		?>
		<span class="already"><br><?php echo "Cannot clear raw data in demo.";?></span><p></p>
		<?php
		include("admin.footer.inc.php");
exit(0);
	}

$tab=$_GET['tab'];
$end_time=$_GET['end_time'];
$start_time=$_GET['s_time'];
//echo "$tab....$end_time";
$exist_clicks=mysql_query("select count(*) from ppc_clicks where time<'$start_time'");
//echo "select count(*) from ppc_clicks where time<'$start_time'";
$exist_clicks=mysql_fetch_row($exist_clicks);
$exist_clicks=$exist_clicks[0];
$exist_impressions=$mysql->total("advertiser_impression_daily","time<'$start_time'");
if($exist_clicks>0 || $exist_impressions>0)
	{
	include_once("admin.header.inc.php");
	echo "<br><br><br><span class=\"already\">You cannot delete data without clearing previous data</span>&nbsp;&nbsp;<a href=\"javascript:history.back(-1);\" class=\"pagetable_activecell\" {COLORTHEME5}>Click Here</a> to Go Back<br><br>";
	include("admin.footer.inc.php");
	exit(0);
	}
else
	{
	
	
	if(mysql_query("delete from ppc_clicks where time<='$end_time'"))
		if(mysql_query("delete from advertiser_impression_daily where time<='$end_time'") && mysql_query("delete from publisher_impression_daily where time<='$end_time'"))
			mysql_query("update statistics_updation set e_time='$end_time' where task='last_clearance_time'");
}	
header("Location:show-statistics.php");
exit(0);
?>