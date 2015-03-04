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
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");
$template=new Template();

$template->loadTemplate("publisher-templates/publisher-terms.tpl.html");
$template->includePage("{FOOTER}","common-footer.php");
$lang_code=$client_language;
if($client_language=='')
$lang_code='en';
include_once("locale/publisher-template-".$lang_code."-inc.php");
			
//		//echo 	$publisher_message[1134];
	$template->setValue("{PUBTERM}",$publisher_message[1134]);  		

$template->setValue("{TERMS}",nl2br(str_replace("{ENGINE_NAME}",$publisher_message[0001],$mysql->echo_one("select terms from terms_and_conditions where type=1")))); 
//emplate->setValue("{ENGINE_NAME}",$ppc_engine_name);                                               
$template->setValue("{ENGINE_NAME}",$publisher_message[0001]);      
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

//$engine_title= str_replace("{ENGINE_NAME}",$publisher_message[0001],$publisher_message[8976]);
//$template->setValue("{ENGINE_TITLE}",$engine_title);

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
eval('?>'.$template->getPage().'<?php ');

?>
