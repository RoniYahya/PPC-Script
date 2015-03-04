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

if($script_mode=="demo")
	{ 
		include("admin.header.inc.php");
		$str='<br><br><br><br><br><br><br><center><span class="already">These Operation not allowed demo!</span> <br> <br>
		<a href="javascript:history.back(-1);"><strong>Go Back</strong></a>         </center>
		';
		
		echo $str; 
		
		exit;
	}

$id =$_REQUEST['id'];
phpsafe($id);
$result=mysql_query("select * from adserver_languages where id='$id'");
$row=mysql_fetch_row($result);
$no=mysql_num_rows($result);

if($row[3]==$client_language || $no==0)
{
	header("Location:ppc-manage-language.php");
	exit(0);
}
include_once("admin.header.inc.php");
$adscount=$mysql->echo_one("select count(*) from ppc_ads where adlang='$row[0]' ");
$adbklockcount=$mysql->echo_one("select count(*) from ppc_custom_ad_block where adlang='$row[0]'");
$credit_count=$mysql->echo_one("select count(*) from ppc_publisher_credits  where language_id='$row[0]'");
if($adscount!=0)
{ ?>
	<span class="inserted"><br><?php echo "The specified language is already used for ad(s). You cannot delete this language";?></span>
	<strong><a href="ppc-manage-language.php">Manage Existing Languages</a></strong>
<br>
<?php }
elseif($adbklockcount!=0)
{ ?>
	<span class="inserted"><br><?php echo "The specified language is already used for adunit(s). You cannot delete this language";?></span>
	<strong><a href="ppc-manage-language.php">Manage Existing Languages</a></strong>
<br>
<?php }
elseif($credit_count)
{ ?>
	<span class="inserted"><br><?php echo "The specified language is already used for credit text(s). You cannot delete this language ";?></span>
	<strong><a href="ppc-manage-language.php">Manage Existing Languages</a></strong>
<br>
<?php }
else
{
$bg=mysql_query("delete from adserver_languages where id='$id'");
?> <span class="inserted"><br><?php echo "Language has been successfully deleted ! ";?></span>
<strong><a href="ppc-manage-language.php">Manage Existing Languages</a></strong>
<br> 
<?php
}
//header("location:ppc-manage-language.php");
//exit;
 ?>

<?php include("admin.footer.inc.php");?>