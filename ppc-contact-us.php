<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");

//print_r($_POST);
if(isset($_POST['subject']))
{
if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
	 {
	 	  $langid=$_COOKIE['language'];
	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
	 	$row=mysql_fetch_row($result);
	  $langcookie=$row[0];
	 
	   
	  if(!file_exists("messages.".$langcookie.".inc.php"))
	  {
	  	  $langcookie=$GLOBALS['client_language'];
	  }
	   
	  
	   }
	 
	  else
	  {
	  $langcookie=$GLOBALS['client_language'];
	  }
$subject=$_POST['subject'];
$body=$_POST['body'];
$name=$_POST['name'];
$emailid=$_POST['email'];
//new
$img=$_POST['image'];
phpSafe($img);

//new
removeGpcPrefixedSlashes($subject);
removeGpcPrefixedSlashes($body);
removeGpcPrefixedSlashes($name);
//phpSafe($emailid);
if($subject=="" || $body=="" || $name=="" || $emailid=="" )
{
	header("Location:show-message.php?id=5009");
	exit(0);
}


if(md5($img)!=$_COOKIE['image_random_value']) 
{ 
	
	$template=new Template();
	$template->loadTemplate("common-templates/ppc-contact-us.tpl.html");
	if(isset($_REQUEST['type']))
	{
		if(trim($_REQUEST['type'])==1)//1 for publisher pages
		{
			
			$template->includePage("{TABS}","common-header.php");
		}
		else
		{
			$template->includePage("{TABS}","common-header.php");
		}
	}
	else
	{
	$template->includePage("{TABS}","common-header.php");
	}
	$template->includePage("{FOOTER}","common-footer.php");

	if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
	 {
	 	  $langid=$_COOKIE['language'];
	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
	 	$row=mysql_fetch_row($result);
	  $langcookie=$row[0];
	 
	   
	  if(!file_exists("messages.".$langcookie.".inc.php"))
	  {
	  	  $langcookie=$GLOBALS['client_language'];
	  }
	   
	  
	   }
	 
	  else
	  {
	  $langcookie=$GLOBALS['client_language'];
	  }
	  
	$contact=$mysql->echo_one("select seoname from url_updation where name='contactus'");
	if($contact=="")
	{
		$contact1="ppc-contact-us.php";
	}
	else
	{
		$contact1=$contact;
	}
	$form=new Form("newTicket",$contact1);
	
	$form->isNotNull("name",$template->checkmsg(6031));
	$form->isEmail("email",$template->checkmsg(6009));
	$form->isNotNull("subject",$template->checkmsg(6029));
	$form->isNotNull("body",$template->checkmsg(6030));
	$form->isNotNull("image",$template->checkmsg(6060));
	
	$template->setValue("{FORMSTART}",$form->formStart()."<br><center class=\"already\">".$template->checkmsg(1025)."</center>");
	$template->setValue("{FORMCLOSE}",$form->formClose());
	$template->setValue("{SUBMIT}",$form->addSubmit($template->checkComMsg(3013)));
	//$template->setValue("{HIDDENNAME}",$form->addHiddenField("username",$user->getUsername()));
	//$template->setValue("{HIDDENEMAILID}",$form->addHiddenField("email",$user->getEmailID()));
	$template->setValue("{NAME}",htmlSafe( $name));
	$template->setValue("{EMAIL}",htmlSafe($emailid));
	$template->setValue("{SUBJECT}",htmlSafe($subject));
	$template->setValue("{MESSAGE}",htmlSafe($body));
	
	//$template->setValue("{TABS}",$template->includePage($server_dir."ppc-page-header.php"));
	$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=2"));           
	$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=2"));         
	
	$template->setValue("{ENGINE_NAME}",$ppc_engine_name);                                               
	$selected_colorcode=array();
	foreach ($color_code as $key=>$value)
	 {
	  if($key==$color_theme)
	  {
	  $selected_colorcode=$value;
	  break;
	  }
	  
	}
	$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
	$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
	$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
	
	$template->setValue("{IMG}",'<input type="text" name="image" id="image" >');
	
	
	$template->setValue("{PAGEWIDTH}",$page_width);  
	$template->setValue("{ENCODING}",$ad_display_char_encoding);  
	
eval('?>'.$template->getPage().'<?php ');
	exit(0);
}

$subject="New Contact - $subject";




include($GLOBALS['admin_folder']."/class.Email.php");
$Sender = $name." <".$emailid.">";
$message = new Email($admin_general_notification_email, $Sender, $subject, '');
$message->Cc = ''; 
$message->Bcc = ''; 

$message->SetHtmlContent(nl2br($body));  
if($message->Send())
{				
	header("Location:show-success.php?id=5008");
	exit(0);
}
else
{
	header("Location:show-message.php?id=5009");
	exit(0);
}



}










$template=new Template();
$template->loadTemplate("common-templates/ppc-contact-us.tpl.html");

$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$form=new Form("newTicket",$contact1);

$form->isNotNull("name",$template->checkmsg(6031));
$form->isEmail("email",$template->checkmsg(6009));
$form->isNotNull("subject",$template->checkmsg(6029));
$form->isNotNull("body",$template->checkmsg(6030));
$form->isNotNull("image",$template->checkmsg(6060));

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkComMsg(3013)));

//$template->setValue("{HIDDENNAME}",$form->addHiddenField("username",$user->getUsername()));
//$template->setValue("{HIDDENEMAILID}",$form->addHiddenField("email",$user->getEmailID()));

	$template->setValue("{NAME}","");
	$template->setValue("{EMAIL}","");
	$template->setValue("{SUBJECT}","");
	$template->setValue("{MESSAGE}","");


//$template->setValue("{TABS}",$template->includePage($server_dir."ppc-page-header.php"));
$template->setValue("{META_KEYWORDS}",$mysql->echo_one("select item_value from site_content where item_name='meta-keywords' and item_type=0"));           
$template->setValue("{META_DESC}",$mysql->echo_one("select item_value from site_content where item_name='meta-description' and item_type=0"));         

$template->setValue("{ENGINE_NAME}",$template->checkComMsg(0001));                                                
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

$template->setValue("{IMG}",'<input type="text" name="image" id="image" >');


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
