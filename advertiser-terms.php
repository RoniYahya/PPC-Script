<?php 
/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/
?><?php 
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


includeClass("Template");
//include_once("messages.".$client_language.".inc.php");
$template=new Template();

$template->loadTemplate("ppc-templates/advertiser-terms.tpl.html");
$template->includePage("{FOOTER}","common-footer.php");
$lang_code=$client_language;
if($client_language=='')
$lang_code='en';
include_once("locale/advertiser-template-".$lang_code."-inc.php");

$aa=$mysql->echo_one("select terms from terms_and_conditions where type=0");
$aa=str_replace("{ENGINE_NAME}",$advertiser_message[0001],$aa);
$aa=str_replace("{ADV_LOGIN_PATH}",$server_dir."ppc-user-login.php",$aa);
$template->setValue("{TERMS}",nl2br($aa)); 
$template->setValue("{ENGINE_NAME}",$advertiser_message[0001]);                                                     
$template->setValue("{COND}",$advertiser_message[1288]);                                               
  
//$template->setValue("{ADV_LOGIN_PATH}",$server_dir."ppc-user-login.php",$aa);      
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


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);         
eval('?>'.$template->getPage().'<?php ');

?>