<?php

die;
//TO DO do phpsafe wherever necessary

ob_start();

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
$user=new User("ppc_publishers");
$key=$_POST['key'];
$type=$_GET['type'];
if($key==$xml_auth_code)
{
if($type==1)//registration
{
//print_r($_POST);
$email=$_POST['email'];
$username=$_POST['username'];
$password=$_POST['password'];
$country=$_POST['country'];

if($email==""||$username==""||$password==""||$country=="")
{
	echo 1001;
	exit;
}
elseif(!$user->isValidEmail($email))
{
	echo 6009;
	exit;
}
elseif(strlen($password)<$min_user_password_length)
{
	echo 6005;
	exit;
}
else
{
	$num1=$mysql->echo_one("select count(*) from ppc_publishers where  email='$email'");
		$num=$mysql->echo_one("select count(*) from ppc_publishers where  username='$username'");
		if($num==0)
		{
			if($num1==0)
			{
	$password=md5($password);
mysql_query("insert into ppc_publishers(`username`, `password`, `email`,`country`) values('$username','$password','$email','$country')");
exit;
			}
			else
			{
				echo 6022;
				exit;
			}
	}
	else
	{
			echo 1027;
		exit;	
	}
}
}
elseif($type==2)//change password
{

	$npassword=$_POST['newpass'];
	$cookieuser=$_POST['cookieuser'];
	if($npassword<$min_user_password_length)
{
	echo 6005;
	exit;
}
else
{
	
	$npassword=md5($npassword);
mysql_query("update  ppc_publishers set password='$npassword' where username='$cookieuser'")or die("error");
exit;	
}
	
}
elseif($type==3)//edit profile
	{
		$email=$_POST['email'];
		$country=$_POST['country'];
		$user=$_POST['cookieuser'];
	if($email=="")
{
	echo 1001;
	exit;
}
elseif(!$user->isValidEmail($email))
{
	echo 6009;
	exit;
}
else
{
	$num=$mysql->echo_one("select count(*) from ppc_publishers where  email='$email' and username!='$user'");
		if($num==0)
		{
	mysql_query("update ppc_publishers set email='$email',country='$country' where username='$user'");
}
else
{
	echo 6022;
	exit;
}
		
	}
	
	}
elseif($type==4)
{
	//print_r($_POST);
	$username=$_POST['username'];
	$password=$_POST['password'];

	if($username=="")
	{
		echo 6001;
		exit;
	}
	elseif($password=="")
	{
		echo 6002;
		exit;
	}
	elseif($user->isEmailVerified($username,$password))
	{
		echo 1039;
		exit;
	}
	elseif($user->pendingAccount($username,$password))
	{
		echo 1018;
		exit;
	}
	elseif($user->blockedAccount($username,$password))
	{
		echo 1017;
		exit;
	}
	else
	{
		if(!$user->cookieUser($username,$password,"publisher"))
		{
			echo 1005;
			exit;
		}
		else
		{
		
		}
	}
}
}
ob_clean();
?>