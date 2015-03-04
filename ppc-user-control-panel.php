<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
//include("messages.".$client_language.".inc.php");
$template=new Template();
$template->loadTemplate("ppc-templates/ppc-user-control-panel.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_users");

if(!($user->validateUser()))
{

header("Location:show-message.php?id=1006");
exit(0);
}
$user_id=$user->getUserID();
	$template->setValue("{MSG}",$template->checkmsg(1034));
	$pub_details=mysql_query("select * from `ppc_users` where uid=".$user_id);

	$pub_det=mysql_fetch_array($pub_details);
	
	if($pub_det['email']=="" || $pub_det['country']=="" || $pub_det['domain']=="" || $pub_det['firstname']=="" || $pub_det['lastname']=="" || $pub_det['address']=="" || $pub_det['phone_no']=="" || $pub_det['taxidentification']=="")
	{
	$template->setValue("{MSGFLAG}",1);
	}
else
	{
	$template->setValue("{MSGFLAG}",0);
	}
	
$template->setValue("{USERNAME}",$user->getUsername());
$accbal=moneyFormat($mysql->echo_one("select accountbalance from ppc_users where uid=".$user_id));

$template->setValue("{BALANCE}",$accbal);
$com_account=$mysql->echo_one("select common_account_id from ppc_users where uid=".$user_id);
$template->setValue("{COM_ACCOUNT}",$com_account);
//added  22/08/2009
$settlement_amt=moneyFormat($mysql->echo_one("select sum(clickvalue) from `ppc_daily_clicks` where  uid=".$user_id));
$template->setValue("{SETTLEMENT}",$settlement_amt);
//added  22/08/2009
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));  

$REQUEST= str_replace("{ENGINE_NAME}",$template->checkAdvMsg(0001),$template->checkAdvMsg(9993));
$template->setValue("{REQUEST}",$REQUEST);
                                             
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
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
$time=time();
$message_count=$mysql->echo_one("select count(*) from messages where messagefor='advertiser' and date>'$time' and status='1' order by id DESC");

if($message_count!=0)
{
//echo "select message from messages where messagefor='advertiser' and date>'$time' and status='1' order by id DESC";
$template->openLoop("MESS","select message from messages where messagefor='advertiser' and date>'$time' and status='1' order by id DESC");
$template->setLoopField("{LOOP(MESS)-MESSAG}","message");
$template->closeLoop();
}
$e_mess=$template->checkAdvMsg(8934);
$e_mess1=str_replace("{UNAME}",$user->getUsername(),$e_mess);
$template->setValue("{USER1}",$e_mess1);
$template->setValue("{MODE}",$advertiser_checkpayment);
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$money_form=moneyFormat($min_user_transaction_amount);
if($advertiser_checkpayment==1)
{
$combine_mess=$template->checkAdvMsg(8938);
$combine_mess1=str_replace("{MFORMAT}",$money_form,$combine_mess);
$template->setValue("{COMBINEMESS}",$combine_mess1);	
}
else
{
$combine_mess=$template->checkAdvMsg(8939);
$combine_mess1=str_replace("{MFORMAT}",$money_form,$combine_mess);
$template->setValue("{COMBINEMESS}",$combine_mess1);	
}
$template->setValue("{BONUS}",moneyFormat($mysql->echo_one("select bonusbalance from ppc_users where uid=".$user_id)));
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
$e_messa=$template->checkAdvMsg(8935);
$e_messa1=str_replace("{BALANCE1}",$accbal,$e_messa);
$template->setValue("{BALANCE_AMOUNT}",$e_messa1);
$e_messa5=$template->checkAdvMsg(8936);
$e_messa6=str_replace("{SETTLE}",$settlement_amt,$e_messa5);
$template->setValue("{SETTLE_AMOUNT}",$e_messa6);
$e_messa10=$template->checkAdvMsg(8937);
$e_messa11=str_replace("{BONUS1}",moneyFormat($mysql->echo_one("select bonusbalance from ppc_users where uid=".$user_id)),$e_messa10);
$template->setValue("{BONUS_AMOUNT}",$e_messa11);
$e_message=$template->checkAdvMsg(8940);
$e_message1=str_replace("{ENAME}",$template->checkAdvMsg(0001),$e_message);
$template->setValue("{SUPPORTTICKET}",$e_message1);
eval('?>'.$template->getPage().'<?php ');

?>