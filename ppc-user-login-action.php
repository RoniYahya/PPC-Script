<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");



includeClass("User");
includeClass("Form");
$user=new User("ppc_users");
$username=$_POST['username'];
$password=$_POST['password'];
phpSafe($username);
phpSafe($password);
//echo 
if($single_account_mode==1)
{
if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-user-control-panel.php");
exit(0);	
	}
	elseif($_COOKIE['io_type']==md5("publisher"))
	{
	header("Location: ppc-publisher-control-panel.php");
exit(0);	
	}
	else
	{
		header("Location: login.php");
exit(0);
	}
}





if($user->isEmailVerified($username,$password))
{
header("Location:show-message.php?id=1039");
exit(0);
}

if($user->pendingAccount($username,$password))
{
header("Location:show-message.php?id=1018");
exit(0);
}


if($user->blockedAccount($username,$password))
{
header("Location:show-message.php?id=1017");
exit(0);
}


if(!$user->cookieUser($username,$password,"advertiser"))
{
header("Location:show-message.php?id=1005");
exit(0);
}
//echo "update ppc_users set lastlogin =".time()."  where uid=".$user->getUserID($username);die;
mysql_query("update ppc_users set lastlogin =".time()."  where uid=".$user->getUserID($username));
header("Location:ppc-user-control-panel.php");
exit(0);

?>