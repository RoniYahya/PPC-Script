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



$id=$_GET['id'];
phpsafe($id);
$aid=$_GET['aid'];
phpsafe($aid);
$url=$_GET['url'];

$num_rem_keyword=$mysql->echo_one("select count(*) from ppc_keywords where aid=$aid");
//echo $num_rem_keyword;exit(0);
if($num_rem_keyword==1)
	{
	include_once("admin.header.inc.php");
		
			?>
			<br>
			<br>
			<span class="already">You cannot delete all keywords.Atleast one keyword required to show your ad.
			</span>
		
	<strong><br><br /><br />
	<span><a href="<?php echo $url; ?>">Proceed</a></span></strong>
	<br /><br />
	<?php include("admin.footer.inc.php");
	exit(0);
	}

mysql_query("delete from ppc_keywords where id='$id' AND aid='$aid'");

header("Location:$url");
exit(0);
?>
