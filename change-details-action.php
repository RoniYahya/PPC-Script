<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
$template=new Template();


if($single_account_mode==0)
{
	header("Location: index.php");
exit(0);
}
if($_COOKIE['io_type']==md5("advertiser"))
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_users where username='".$_COOKIE['io_username']."'");
	
}
if($_COOKIE['io_type']==md5("publisher"))
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where username='".$_COOKIE['io_username']."'");

}
if($commonid==0)
{
if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-change-details.php");
exit(0);	
	}
	elseif($_COOKIE['io_type']==md5("publisher"))
	{
	header("Location: ppc-change-publisher-details.php");
exit(0);	
	}
}
$ini_error_status=ini_get('error_reporting');
$user=new User("nesote_inoutscripts_users", "id");
if(!$user->validateUser())
{
header("Location:error-message.php?id=1006");
exit(0);
}

$email=trim($_POST['email']);
$country=trim($_POST['country']);
$domain=trim($_POST['domain']);
$firstname=trim($_POST['firstname']);
$lastname=trim($_POST['lastname']);
$phone_no=trim($_POST['phone_no']);
$address=trim($_POST['address']);
$taxid=trim($_POST['taxidentification']);
phpSafe($taxid);
phpSafe($email);
phpSafe($country);
phpSafe($domain);

phpSafe($firstname);
phpSafe($lastname);
phpSafe($phone_no);
phpSafe($address);

if($email==""|| $country=="" || $domain=="" || $firstname=="" || $lastname=="" || $address=="" || $phone_no=="") 
{
header("Location:error-message.php?id=1001");
exit(0);
}
$uid=$user->getUserID();
if($user->emailExists($email,$uid))
{
	header("Location:error-message.php?id=5019");
	exit(0);
}
if(!($user->isValidEmail($email)))
			{
				header("Location:error-message.php?id=6009");
				exit(0);
			}

	//if(is_numeric($phone_no) !=1 ||$phone_no<=0  ||  !((string)$phone_no === (string)(int)$phone_no) )
	if(!isPositiveInteger($phone_no))
	{
	header("Location:error-message.php?id=1032");
	exit(0);
	}


if(!checkSpace($domain))
	{
	header("Location:error-message.php?id=1031");
	exit(0);
	}

if(!isDomain($domain))
	{
		header("Location:error-message.php?id=1030");
		exit(0);
	}
	
	$usercount=$mysql->echo_one("select count(*) from ppc_users where email='$email' and common_account_id!=$uid");
	$publishercount=$mysql->echo_one("select count(*) from ppc_publishers where email='$email' and common_account_id!=$uid");
	if(($usercount==0) && ($publishercount==0))
	{
	$fname=$firstname." ".$lastname;
	mysql_query("BEGIN");
if(!mysql_query("update ppc_publishers set email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',address='$address',phone_no='$phone_no',taxidentification='$taxid' where common_account_id='$uid'"))
{
	$flag=1;
}
if(!mysql_query("update ppc_users set email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',address='$address',phone_no='$phone_no',taxidentification='$taxid' where common_account_id='$uid'"))
{
	$flag=1;
}
if(!mysql_query("update nesote_inoutscripts_users set  name='$fname',email='$email' where id='$uid'"))
{
	$flag=1;
}
if($flag==1)
{
	mysql_query("ROLLBACK");
	header("Location:error-message.php?id=1004");
				exit(0);
}
else
{
mysql_query("COMMIT");
	header("Location:success-message.php?id=1007");
exit(0);
}
	}
	else
	{
		header("Location:error-message.php?id=5019");
	exit(0);
	}
//if($ini_error_status!=0)
//{
//	echo mysql_error();
//}


?>