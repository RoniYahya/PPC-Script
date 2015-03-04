<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("publisher-templates/ppc-change-publisher-password.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_publishers");

if($user->getUsername()=="publisher" && $script_mode=="demo")
{
header("Location:publisher-show-message.php?id=6076");
exit(0);
}

$pubid=$user->getUserID();
$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid=$pubid");
if(($commonid!=0))
{
if($_COOKIE['io_type']==md5("Common"))
	{				
header("Location: change-password.php");
exit(0);	
	}
}


if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
//if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
//	 {
//	 	  $langid=$_COOKIE['language'];
//	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
//	 	$row=mysql_fetch_row($result);
//	  $langcookie=$row[0];
//	 
//	   
//	  if(!file_exists("messages.".$langcookie.".inc.php"))
//	  {
//	  	  $langcookie=$GLOBALS['client_language'];
//	  }
//	   
//	  
//	   }
//	 
//	  else
//	  {
//	  $langcookie=$GLOBALS['client_language'];
//	  }
//	  if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
//	 {
//	 	  $langid=$_COOKIE['language'];
//	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
//	 	$row=mysql_fetch_row($result);
//	  $langcookie=$row[0];
//	 
//	   
//	  if(!file_exists("messages.".$langcookie.".inc.php"))
//	  {
//	  	  $langcookie=$GLOBALS['client_language'];
//	  }
//	   
//	  
//	   }
//	 
//	  else
//	  {
//	  $langcookie=$GLOBALS['client_language'];
//	  }
//	  if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
//	 {
//	 	  $langid=$_COOKIE['language'];
//	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
//	 	$row=mysql_fetch_row($result);
//	  $langcookie=$row[0];
//	 
//	   
//	  if(!file_exists("messages.".$langcookie.".inc.php"))
//	  {
//	  	  $langcookie=$GLOBALS['client_language'];
//	  }
//	   
//	  
//	   }
//	 
//	  else
//	  {
//	  $langcookie=$GLOBALS['client_language'];
//	  }
//	  if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
//	 {
//	 	  $langid=$_COOKIE['language'];
//	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
//	 	$row=mysql_fetch_row($result);
//	  $langcookie=$row[0];
//	 
//	   
//	  if(!file_exists("messages.".$langcookie.".inc.php"))
//	  {
//	  	  $langcookie=$GLOBALS['client_language'];
//	  }
//	   
//	  
//	   }
//	 
//	  else
//	  {
//	  $langcookie=$GLOBALS['client_language'];
//	  }
//	  if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
//	 {
//	 	  $langid=$_COOKIE['language'];
//	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
//	 	$row=mysql_fetch_row($result);
//	  $langcookie=$row[0];
//	 
//	   
//	  if(!file_exists("messages.".$langcookie.".inc.php"))
//	  {
//	  	  $langcookie=$GLOBALS['client_language'];
//	  }
//	   
//	  
//	   }
//	 
//	  else
//	  {
//	  $langcookie=$GLOBALS['client_language'];
//	  }
//	  if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
//	 {
//	 	  $langid=$_COOKIE['language'];
//	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
//	 	$row=mysql_fetch_row($result);
//	  $langcookie=$row[0];
//	 
//	   
//	  if(!file_exists("messages.".$langcookie.".inc.php"))
//	  {
//	  	  $langcookie=$GLOBALS['client_language'];
//	  }
//	   
//	  
//	   }
//	 
//	  else
//	  {
//	  $langcookie=$GLOBALS['client_language'];
//	  }
	  

$form=new Form("EditPassword","ppc-change-publisher-password-action.php");
$form->isNotNull("oldpass",$template->checkmsg(6016));
$form->isNotNull("newpass",$template->checkmsg(6017));
$form->isNotNull("newpass2", $template->checkmsg(6018));
$form->isNotShort("newpass",$min_user_password_length, $template->checkmsg(6019));
$form->isNotShort("newpass2",$min_user_password_length,$template->checkmsg(6020));
$form->isSame("newpass","newpass2",$template->checkmsg(6021));
//
//
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{OLDPASS}",$form->addPassword("oldpass",40));
$template->setValue("{NEWPASS}",$form->addPassword("newpass",40));
$template->setValue("{NEWPASS2}",$form->addPassword("newpass2",40));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(7007)));

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  
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


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
