<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("Template");
$template=new Template();

$template->loadTemplate("ppc-templates/flash-description.tpl.html");
$template->setValue("{MESS}" ,$template->checkAdvMsg(10155));
$template->setValue("{MESS1}" ,$template->checkAdvMsg(10156));
$template->setValue("{MESS2}" ,$template->checkAdvMsg(10157));
$template->setValue("{MESS3}" ,$template->checkAdvMsg(10158));
$template->setValue("{MESS4}" ,$template->checkAdvMsg(10159));
eval('?>'.$template->getPage().'<?php ');
?>