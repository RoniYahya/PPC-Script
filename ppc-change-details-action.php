<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");

$ini_error_status=ini_get('error_reporting');
$user=new User("ppc_users");
$advid=$user->getUserID();
$commonid=$mysql->echo_one("select common_account_id from ppc_users where uid=$advid");
if(($commonid!=0))
{
	if($_COOKIE['io_type']==md5("Common"))
	{				
header("Location: change-details.php");
exit(0);	
	}
}
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}

$email=$_POST['email'];
$country=trim($_POST['country']);
$domain=trim($_POST['domain']);
$firstname=trim($_POST['firstname']);
$lastname=trim($_POST['lastname']);
$phone_no=trim($_POST['phone_no']);
$address=trim($_POST['address']);
$taxid=trim($_POST['taxidentification']);
phpSafe($email);
phpSafe($country);
phpSafe($domain);
phpSafe($firstname);
phpSafe($lastname);
phpSafe($phone_no);
phpSafe($address);
phpSafe($taxid);
if($email=="" || $country=="" || $domain=="" || $firstname=="" || $lastname=="" || $address=="" || $phone_no=="")
{
header("Location:show-message.php?id=1001");
exit(0);
}
$uid=$user->getUserID();
if($user->emailExists($email,$uid))
{
	header("Location:show-message.php?id=5019");
	exit(0);
}
if(!($user->isValidEmail($email)))
			{
				header("Location:show-message.php?id=6009");
				exit(0);
			}
	//if(is_numeric($phone_no) !=1 ||$phone_no<=0  ||  !((string)$phone_no === (string)(int)$phone_no) )
	if(!isPositiveInteger($phone_no))
		{
		header("Location:show-message.php?id=1032");
		exit(0);
		}
	if(!checkSpace($domain))
		{
		header("Location:show-message.php?id=1031");
		exit(0);
		}
	if(!isDomain($domain))
	{
		header("Location:show-message.php?id=1030");
		exit(0);
	}	

mysql_query("update ppc_users set email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',address='$address',phone_no='$phone_no',taxidentification='$taxid' where uid='$uid'");

if($ini_error_status!=0)
{
	echo mysql_error();
}
header("Location:show-success.php?id=1007");
exit(0);
?>