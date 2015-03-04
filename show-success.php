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
$template->loadTemplate("ppc-templates/show-success.tpl.html");

$user=new User("ppc_users");
$flag=FALSE;
if($user->validateUser())
		{
			$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");			$flag=TRUE;
		}
else
		{
			$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");
			
		}

if($flag)
{
			$template->setValue("{HOMELINK}","ppc-user-control-panel.php");
}
else
{
			$template->setValue("{HOMELINK}","ppc-user-login.php");
}
if(!isset($_GET['id']))
$passmid=$messageid;
else
$passmid=$_GET['id'];

$template->setValue("{MESSAGE}",$template->checkmsg($passmid));

$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));                                               

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

//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$publisher_message[8978]);
//$template->setValue("{ENGINE_TITLE}",$engine_title);

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding); 

if(isset($_REQUEST['page']))
	{
$template->setValue("{PAGE}","<a href=\"".urldecode($_REQUEST['page'])."\" class=\"pagetable_activecell\">Click here</a> to proceed.");

}
else
$template->setValue("{PAGE}","");

eval('?>'.$template->getPage().'<?php ');

?>
