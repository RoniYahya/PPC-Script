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


$url=urldecode($_GET['url']);
$id=$_GET['id'];
phpsafe($id);
$aid=$_GET['aid'];
phpsafe($aid);
$action=$_GET['action'];
phpsafe($action);
//echo "$aid";
//exit(0);
$num_rem_keyword=$mysql->echo_one("select count(*) from ppc_keywords where aid=$aid and status=1");
//echo $num_rem_keyword;exit(0);
if($num_rem_keyword==1 && $action=="block")
	{
	include_once("admin.header.inc.php");
		
			?>
			<br>
			<br>
			<span class="already">You cannot block all keywords.Atleast one keyword required to show your ad.
			</span>
		
	<strong><br><br /><br />
	<span><a href="<?php echo $url; ?>">Proceed</a></span></strong>
	<br /><br />
	<?php include("admin.footer.inc.php");
	exit(0);
	}

if($action=="block")
{
//echo "update ppc_users set status=0 where uid=$uid;";
mysql_query("update ppc_keywords set status=0 where id=$id;");
}
if($action=="activate")
{
mysql_query("update ppc_keywords set status=1 where id=$id;");
}
header("Location:$url");
exit(0);
?>
