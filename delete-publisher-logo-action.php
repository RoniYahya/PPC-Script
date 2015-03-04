<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
includeClass("ImageResizer");
$template=new Template();
//$template->loadTemplate("common-templates/ppc-ad-logo-details.tpl.html");

$user=new User("ppc_publishers");
$id=$user->getUserID();

$lid=$_GET['id'];
//echo "select id from ad_logo_details where id=$lid";exit;
$adcount1=mysql_query("select id,ad_logos_name from ad_logos_details where id=$lid");
$row=mysql_fetch_row($adcount1);

//echo "select id,logo_name from ad_logo_details where id=$lid"; exit;
$adcount=mysql_num_rows($adcount1);

//$text=$_POST['text'];
//$url=urldecode( $_GET['url']);
//$subject=$_POST['subject'];
//if(get_magic_quotes_gpc())
//	{
//	$text=stripslashes($text);
//	$subject=stripslashes($subject);
//	}
//$email=$mysql->echo_one("select email from ppc_users where uid=$uid");
//
// if($script_mode!="demo")
// {
//
//include("class.Email.php");
//
//$message = new Email($email, $admin_general_notification_email, $subject, '');
//$message->Cc = ''; 
//$message->Bcc = ''; 
//
//$message->SetHtmlContent(nl2br($text));  
//$message->Send();
//
//	}



if($adcount!=0)
{


 unlink("ad_logos/".$row[1]);
mysql_query("delete from ad_logos_details where id=$lid");
}


//mysql_query("delete from ad_logo_details where id=$lid ");
	header("Location:publisher-show-success.php?id=10508&page=ppc-publisher-logo-details.php");
exit(0);
exit;
/*echo "Status of AdLogo changed.";?>
</span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>*/


//header("location: $url");
//exit(0);
?>