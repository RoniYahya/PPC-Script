<?php 



/*--------------------------------------------------+

|													 |

| Copyright ? 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/


	include("../extended-config.inc.php");
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



//print_r($_GET);
$type=$_GET['type'];
$id=$_GET['id'];

if($type==1)
{
$table="ppc_publishers";
$str="publisher";
}
if($type==2)
{
$table="ppc_users";
$str="advertiser";
}

if($type==3)
{
$table="nesote_inoutscripts_users";
$str="Common";
}

if($single_account_mode==1 && $type!=3)
{
	$cid=$mysql->echo_one("select common_account_id  from $table where uid=$id"); 
	if($cid>0)
	{
	$id=$cid;
	$table="nesote_inoutscripts_users";
	$str="Common";
	}	
	
}

if($table=="nesote_inoutscripts_users")
{
	$res=mysql_query("select username,password,email from $table where id=$id");
}
else
$res=mysql_query("select username,password,email from $table where uid=$id");
//echo "select username,password from $table where uid=$id";exit(0);
$row=mysql_fetch_row($res);
//print_r($row);
//exit(0);

//$cpath=substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],$GLOBALS['admin_folder']."/"));
if($table=="nesote_inoutscripts_users")
{
		
			setcookie("io_username",$row[0],0,'/');
			setcookie("io_password",$row[1],0,'/');
			setcookie("io_type",md5($str),0,'/');
			setcookie("io_usermail",$row[2],0,'/');
}
else
{
		setcookie("io_username",$row[0],0,'/');
			setcookie("io_password",$row[1],0,'/');	
			setcookie("io_type",md5($str),0,'/');	
			setcookie("io_usermail",$row[2],0,'/');
}

			
if($type==1)
header("Location:../ppc-publisher-control-panel.php");			

if($type==2)
header("Location:../ppc-user-control-panel.php");	
if($type==3)
header("Location:../control-panel.php");			
?>