<?php
ob_start();
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");



includeClass("Template");
includeClass("Form");
$template=new Template();



$template->loadTemplate("publisher-templates/captcha.tpl.html");

if(isset($_GET['Flsids']))
{
	$flag_flsids=0;	
	$template->setValue("{FLSIDS}",$Flsids);
}
else
{
	$flag_flsids=1;
	$template->setValue("{AID}",$aid);
	$template->setValue("{KID}",$kid);
	$template->setValue("{VID}",$_GET['vid']);
	$template->setValue("{BID}",$bid);
	$template->setValue("{DIRECT_STATUS}",$_GET['direct_status']);
	$template->setValue("{VIP}",$_GET['vip']);
	$template->setValue("{BS}",$_GET['bs']);
	$template->setValue("{PID}",$_GET['pid']);

}

	$template->setValue("{FID}",$fid);
	

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
eval('?>'.$template->getPage().'<?php ');


?>