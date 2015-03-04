<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");
$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
if($comid!=0)
{
header("Location:ppc-publisher-control-panel.php");
exit(0);	
}
$type=$_POST['radconf'];
$aid=$_POST['pubid'];
$email=trim($_POST['email']);
$country=trim($_POST['country']);
$domain=trim($_POST['domain']);
$firstname=trim($_POST['firstname']);
$lastname=trim($_POST['lastname']);
$phone_no=trim($_POST['phone_no']);
$address=trim($_POST['address']);
$username=trim($_POST['username']);
$taxid=trim($_POST['']);
phpSafe($type);
phpSafe($aid);
phpSafe($email);
phpSafe($country);
phpSafe($domain);
phpSafe($firstname);
phpSafe($lastname);
phpSafe($phone_no);
phpSafe($address);
phpSafe($taxid);

if($email=="" || $country=="" || $domain=="" || $firstname=="" || $lastname=="" || $address=="" || $phone_no==""||$username=="")
{
header("Location:publisher-show-message.php?id=1001");
exit(0);
}
$uid=$user->getUserID();
if($user->emailExists($email,$uid))
{
	header("Location:publisher-show-message.php?id=5019");
	exit(0);
}
if(!($user->isValidEmail($email)))
{
				header("Location:publisher-show-message.php?id=6009");
				exit(0);
}
			$commonid=$mysql->echo_one("select common_account_id from ppc_users where uid='$aid'");
			if($commonid!=0)
			{
				header("Location:publisher-show-message.php?id=8894");
		exit(0);
			}
		if(!isPositiveInteger($phone_no))
		{
		header("Location:publisher-show-message.php?id=1032");
		exit(0);
		}
	if(!checkSpace($domain))
		{
		header("Location:publisher-show-message.php?id=1031");
		exit(0);
		}
	if(!isDomain($domain))
	{
		header("Location:publisher-show-message.php?id=1030");
		exit(0);
	}
	
	$main1=mysql_query("select * from nesote_inoutscripts_users where username='$username' ");
	$main=mysql_num_rows($main1);	
	$memail1=mysql_query("select * from nesote_inoutscripts_users where email='$email'");
	$memail=mysql_num_rows($memail1);	
	
if($type==2) //ppc-users
{

$advertiser=mysql_query("select * from ppc_users where uid='$aid'");
$adve=mysql_fetch_row($advertiser);
 $password=$adve[2];

	$publisher1=mysql_query("select * from ppc_publishers where username='$username' and uid!='$uid'");
	$publisher=mysql_num_rows($publisher1);
	$adver1=mysql_query("select * from ppc_users where username='$username' and uid!='$aid'");
$adver=mysql_num_rows($adver1);
	if(($adver==0) &&($main==0)&&($publisher==0))
	{
$pemail1=mysql_query("select * from ppc_publishers where email='$email' and uid!='$uid'");
	$pemail=mysql_num_rows($pemail1);
	$aemail1=mysql_query("select * from ppc_users where email='$email' and uid!='$aid'");
$aemail=mysql_num_rows($aemail1);	

if(($aemail==0)&&($pemail==0)&&($memail==0))
{
$name=$firstname." ".$lastname;
	
	mysql_query("BEGIN");
		if(!mysql_query("insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$username','$password','$name','$adve[3]','$adve[7]','1')"))
		{
			$flag=1;
		}
		 $lastid=mysql_insert_id();
		if(!mysql_query("update ppc_publishers set username='$username',password='$password',email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',phone_no='$phone_no',address='$address',common_account_id='$lastid',parent_status='1',taxidentification='$taxid' where uid='$uid'"))
		{
			$flag=1;
		}
	if(!mysql_query("update ppc_users set username='$username',password='$password',email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',phone_no='$phone_no',address='$address',common_account_id='$lastid',parent_status='1',username='$username',taxidentification='$taxid' where uid='$aid'"))
	
{
	$flag=1;
}

	if($flag==1)
{
		mysql_query("ROLLBACK");
			header("Location:publisher-show-message.php?id=1004");
	exit(0);
}
else
{
	mysql_query("COMMIT");
	setcookie("io_username",$username,0,'/');
		setcookie("io_password",$password,0,'/');
	setcookie("io_type",md5("Common"),0,'/');
$advrid=$mysql->echo_one("select rid from ppc_users where uid='$aid'");
if($advrid==$uid)
{
	mysql_query("update ppc_users set rid='0' where uid='$aid'");
}
   header("Location:publisher-show-success?id=9992&page=control-panel.php");
	exit(0);  
}

}
else  //already existed emailid
{
header("location:publisher-show-message.php?id=5019");
exit;	
}
	}else ///already existed username
	{
	header("location:publisher-show-message.php?id=1027");
exit;
	}
	}	
else  //type==1 for ppc-publisher
{
$advertiser=mysql_query("select * from ppc_users where username='$username' and uid!='$aid' ");
$adve=mysql_num_rows($advertiser);
$publisher1=mysql_query("select * from ppc_publishers where uid='$uid'");
	$publisher=mysql_fetch_row($publisher1);
$password=$publisher[2];

$pub=mysql_query("select * from ppc_publishers where username='$username' and  uid!='$uid'");
	$publ=mysql_num_rows($pub);
	if(($adve==0) &&($main==0)&&($publ==0))
	{
		$pemail1=mysql_query("select * from ppc_publishers where email='$email' and uid!='$uid'");
 $pemail=mysql_num_rows($pemail1);
	$aemail1=mysql_query("select * from ppc_users where email='$email' and uid!='$aid'");
$aemail=mysql_num_rows($aemail1);	

if(($aemail==0)&&($pemail==0)&&($memail==0))
{
	
	$name=$firstname." ".$lastname;
mysql_query("BEGIN");
		if(!mysql_query("insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$username','$publisher[2]','$name','$publisher[3]','$publisher[7]','1')"))
		{
			$flag=1;
		}
		 $lastid=mysql_insert_id();
if(!mysql_query("update ppc_publishers set username='$username',password='$password',email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',phone_no='$phone_no',address='$address',common_account_id='$lastid',parent_status='1',username='$username',taxidentification='$taxid' where uid='$uid'"))
		{
			$flag=1;
		}
if(!mysql_query("update ppc_users set email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',address='$address',phone_no='$phone_no',username='$username',password='$password',regtime='$publisher[7]',common_account_id='$lastid',parent_status='1',taxidentification='$taxid' where uid='$aid'"))	
{
$flag=1;
}

if($flag==1)
{
		mysql_query("ROLLBACK");
			header("Location:publisher-show-message.php?id=1004");
	exit(0);
}
else
{
	mysql_query("COMMIT");
	setcookie("io_username",$username,0,'/');
	setcookie("io_password",$password,0,'/');
	setcookie("io_type",md5("Common"),0,'/');
$advrid=$mysql->echo_one("select rid from ppc_users where uid='$aid'");
if($advrid==$publisher[0])
{
	mysql_query("update ppc_users set rid='0' where uid='$aid'");
}
	 header("Location:publisher-show-success?id=9992&page=control-panel.php");
	exit(0); 
}

	}
	else  //already existed emailid
	{
		header("location:publisher-show-message.php?id=5019");
exit;
	}
	}
else  //already existed username
{
header("location:publisher-show-message.php?id=1027");
exit;

}	

	}
	

eval('?>'.$template->getPage().'<?php ');

?>