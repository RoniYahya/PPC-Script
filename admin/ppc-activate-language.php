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
$id =$_REQUEST['id'];
phpsafe($id);
$result=mysql_query("select * from adserver_languages where id='$id'");
$row=mysql_fetch_row($result);
$no=mysql_num_rows($result);

if($row[3]=="en" || $no==0)
{
	header("Location:ppc-manage-language.php");
	exit(0);
}
include_once("admin.header.inc.php");

$bg=mysql_query("update adserver_languages set status='1' where id='$id'");
//header("location:ppc-manage-language.php");
//exit;
 ?>
<span class="inserted"><br><?php echo "Language has been successfully activated ! ";?></span>
<strong> <a href="ppc-manage-language.php?">Manage Existing Languages</a></strong>
<br>
<?php include("admin.footer.inc.php");?>