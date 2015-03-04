<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");


$user=new User("nesote_inoutscripts_users", "id");
if($single_account_mode==0)
{
	header("Location: index.php");
exit(0);
}
if($_COOKIE['io_type']==md5("advertiser"))
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_users where username='".$_COOKIE['io_username']."'");
	
}
if($_COOKIE['io_type']==md5("publisher"))
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where username='".$_COOKIE['io_username']."'");

}
if($commonid==0)
{
if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-user-control-panel.php");
exit(0);	
	}
	elseif($_COOKIE['io_type']==md5("publisher"))
	{
	header("Location: ppc-publisher-control-panel.php");
exit(0);	
	}
}
$advstatus=$mysql->echo_one("select status from ppc_users where common_account_id=".$user->getUserID());
$pubstatus=$mysql->echo_one("select status from ppc_publishers where common_account_id=".$user->getUserID());
//echo "select status from ppc_users where common_account_id=".$user->getUserID();
if(!($user->validateUser()))
{
header("Location:error-message.php?id=1006");
exit(0);
}
$template=new Template();
$template->loadTemplate("common-templates/control-panel.tpl.html");
$template->includePage("{TABS}","common-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
if($advstatus==0)
$stat=$template->checkComMsg(1093);
elseif($advstatus==1)
$stat=$template->checkComMsg(1092);
else
$stat=$template->checkComMsg(1094);
//$template->setValue("{ASTATUS}",$stat);

if($pubstatus==0)
$pstat=$template->checkComMsg(1093);
elseif($pubstatus==1)
$pstat=$template->checkComMsg(1092);
else
$pstat=$template->checkComMsg(1094);
$template->setValue("{ASTATUS}",$stat);
$template->setValue("{PSTATUS}",$pstat);

$welcome= str_replace("{USERNAME}",$user->getUsername(),$template->checkComMsg(1085));
$template->setValue("{WELCOME}",$welcome);

//echo "select message from messages where messagefor='both' and status='1'";
$time=time();
$message_count=$mysql->echo_one("select count(*) from messages where messagefor='common' and date>'$time' and status='1' order by id DESC");

if($message_count!=0)
{
$template->openLoop("MESS","select message from messages where messagefor='common' and date>'$time' and status='1' order by id DESC");
$template->setLoopField("{LOOP(MESS)-MESSAG}","message");
$template->closeLoop();
}
$template->setValue("{ENGINE_NAME}",$template->checkComMsg(0001)); 
$template->setValue("{PAGEWIDTH}",$page_width); 

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

if($GLOBALS['direction']=="ltr")
{
$template->setValue("{PIC1}","ctl");
$template->setValue("{PIC2}","ctr");
$template->setValue("{PIC3}","cbl");
$template->setValue("{PIC4}","cbr");
}
else
{
$template->setValue("{PIC1}","ctr");
$template->setValue("{PIC2}","ctl");
$template->setValue("{PIC3}","cbr");
$template->setValue("{PIC4}","cbl");	
}

eval('?>'.$template->getPage().'<?php ');
?>