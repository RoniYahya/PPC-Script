<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");

$template=new Template();
$template->loadTemplate("publisher-templates/xml-api.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

include("graphutils.inc.php");

$user=new User("ppc_publishers");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}

$uid=$user->getUserID();
if(($mysql->echo_one("select xmlstatus from `ppc_publishers` where uid=".$uid))!=1)
{
header("Location:publisher-show-message.php?id=7044");
exit(0);
}
$template->setValue("{UID}",$uid);

$authority=md5($uid);
$a=strlen($authority);
$a=$a-6;
$authority=substr($authority,0,-$a);

$template->setValue("{AUTHORITY}",$authority);

$api_url=$server_dir.'xml-ads.php';

$template->setValue("{APIURL}",$api_url);

$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
}
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$template->setValue("{BID}",$bid);      
$template->openLoop("LANG","Select * from adserver_languages where status=1 order by id asc");
	$template->setLoopField("{LOOP(LANG)-ID}","id");
	$template->setLoopField("{LOOP(LANG)-LANGUAGE}","language");
	
	$template->closeLoop();                
//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));                                                

$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{IP}",$public_ip); 


$template->setValue("{PUBPROFIT}",$publisher_profit);
$xml_string=$xml_string." fclose($fp_test);";
 $xml_string=$xml_string."echo $lic_data;";
$xml_string=$xml_string."}";

$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8981));
$template->setValue("{ENGINE_TITLE}",$engine_title);
$edtmess= str_replace("{PUBPROFIT1}",$publisher_profit,$template->checkPubMsg(8983));
$template->setValue("{MSG1}",$edtmess);
$template->setValue("{SAMPLE_CODE}",$xml_string); 

$template->setValue("{PAGEWIDTH}",$page_width);     
$template->setValue("{ENCODING}",$ad_display_char_encoding);     
eval('?>'.$template->getPage().'<?php ');

?>