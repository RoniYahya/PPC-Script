<?php 
/*--------------------------------------------------+
|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/
?><?php 
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
/*if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
	 {
	 	  $langid=$_COOKIE['language'];
	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
	 	$row=mysql_fetch_row($result);
	  $langcookie=$row[0];
	 
	   
	  if(!file_exists("locale/messages.".$langcookie.".inc.php"))
	  {
	  	  $langcookie=$GLOBALS['client_language'];
	  }
	   
	  
	   }
	 
	  else
	  {
	  $langcookie=$GLOBALS['client_language'];
	  }*/
//include_once("messages.".$client_language.".inc.php");
$template=new Template();
$template->loadTemplate("publisher-templates/show-message.tpl.html");

$user=new User("ppc_publishers");
$flag=FALSE;
if($user->validateUser())
		{
			$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
			$flag=TRUE;
		}
else
		{
			$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");
			
		}

if($flag)
{
			$template->setValue("{HOMELINK}","ppc-publisher-control-panel.php");
}
else
{
			$template->setValue("{HOMELINK}","ppc-publisher-login.php");
}
if(!isset($_GET['id']))
$passmid=$messageid;
else
$passmid=$_GET['id'];
$template->setValue("{MESSAGE}",$template->checkmsg($passmid));
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
$edtmess=$template->checkPubMsg(8990);
$edtmess1=str_replace("{ENAME}",$edtmess,$template->checkPubMsg(0001));
$template->setValue("{MSG10}",$edtmess1);
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>