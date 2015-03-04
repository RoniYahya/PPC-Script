<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
//include("messages.".$client_language.".inc.php");
$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-control-panel.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_publishers");

if(!($user->validateUser()))
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$user_id=$user->getUserID();
$last_login=$mysql->echo_one("select  lastlogin from ppc_publishers where uid='$user_id'");
$last_login_ip=$mysql->echo_one("select  lastloginip from ppc_publishers where uid='$user_id'");

$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
$serverid=1;
else
{
$serverid=$mysql->echo_one("select server_id from ppc_publishers where uid='$user_id'");
if($serverid == 0)
$serverid=$mysql->echo_one("select id from server_configurations where min_range<='$user_id' and  max_range >='$user_id'");

}

if($serverid == 0 || $serverid =="")
$serverid=1;




$fraudtime_interval=time()-($fraud_time_interval*60*60);
$public_ip=getUserIP();
//echo "$last_login<$fraudtime_interval";
if($last_login<$fraudtime_interval)
	{
	
	////////////////
	$last_login=$last_login-($fraud_time_interval*60*60);
	$fraudclicks=mysql_query("select id,clickvalue,aid,uid,ip,time,pid,publisher_profit,bid,vid,pub_rid,pub_ref_profit,adv_rid,adv_ref_profit,`current_time`,browser,platform,version,user_agent,serverid,direct_status from ppc_daily_clicks where pid=$user_id and ip='$last_login_ip'   and clickvalue>0 and time>$last_login");
$numFraudClicks=mysql_num_rows($fraudclicks);

while($row=mysql_fetch_row($fraudclicks))
		{
		
		if($serverid==1)
		{
			$direct_hits=$mysql->echo_one("select direct_hits from publisher_daily_visits_statistics_master where id=$row[9]");
			$vid=$row[9];
			if($direct_hits>0)
				{
					mysql_query("update publisher_daily_visits_statistics_master set direct_fraud_clicks=direct_fraud_clicks+1,direct_clicks=direct_clicks-1 where id='$vid'");
				}
			else{
					mysql_query("update publisher_daily_visits_statistics_master set referred_fraud_clicks=referred_fraud_clicks+1,referred_clicks=referred_clicks-1 where id='$vid'");
				}
				
		}	
				
				
			mysql_query("update ppc_users set accountbalance=accountbalance+$row[1] where uid=$row[3]");
			mysql_query("update ppc_ads set amountused=amountused-$row[1] where id=$row[2]");
			
			mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[7] where uid=$user_id");
			mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[11] where uid=$row[10]");
			mysql_query("update ppc_publishers set accountbalance=accountbalance-$row[13] where uid=$row[12]");
			
			mysql_query("insert into ppc_fraud_clicks values('0',$row[2],$row[6],$row[14],'$row[4]',1,$row[8],$row[9],$row[15],$row[16],$row[17],$row[18],$row[19],$row[20])");
			mysql_query("delete from ppc_daily_clicks where id=$row[0]");
			
		
			
		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
	}
	/////////////////
		//mysql_query("update ppc_publishers set lastlogin='".time()."',lastloginip='".$public_ip."' where uid='$user_id'");
		//echo "update ppc_publishers set lastlogin='".time()."',lastloginip='".$public_ip."' where uid='$user_id'";
	}

	
	$pub_details=mysql_query("select * from `ppc_publishers` where uid=".$user_id);

	$pub_det=mysql_fetch_array($pub_details);
	
	if($pub_det['email']=="" || $pub_det['country']=="" || $pub_det['domain']=="" || $pub_det['firstname']=="" || $pub_det['lastname']=="" || $pub_det['address']=="" || $pub_det['phone_no']=="" || $pub_det['taxidentification']=="")
	{
	$template->setValue("{MSGFLAG}",1);
	
	
	$template->setValue("{MSG}",$template->checkPubMsg(10200));
	//$mess='<a href="ppc-change-publisher-details.php">'.$template->checkPubMsg(1020).'</a>';
	//$template->setValue("{MSG1}",$mess);
	//$template->setValue("{MSG2}",$template->checkPubMsg(8888));
	}
else
	{
	$template->setValue("{MSGFLAG}",0);
	}
	if(($mysql->echo_one("select xmlstatus from `ppc_publishers` where uid=".$user_id))==1)
	{
	$template->setValue("{XMLSTATUS}",1);
	}
    else
	{
	$template->setValue("{XMLSTATUS}",0);
	}
	
$balance=$mysql->echo_one("select accountbalance from ppc_publishers where uid=".$user_id);


//added  22/08/2009
$settlement_amt=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from `ppc_daily_clicks` where  pid=".$user_id);
$adv_referral_amt=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from `ppc_daily_clicks`where  pub_rid=".$user_id);
$pub_referral_amt=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from `ppc_daily_clicks`where  adv_rid=".$user_id);
$settlement_amt=$settlement_amt+$adv_referral_amt+$pub_referral_amt;
//echo $settlement_amt,"+",$adv_referral_amt,"+",$pub_referral_amt;
$template->setValue("{SETTLEMENT}",moneyFormat($settlement_amt));
$balance=$balance-$settlement_amt;
if($balance<0)
$balance=0;
//added  22/08/2009


$balance=moneyFormat($balance);
$template->setValue("{USERNAME}",$user->getUsername());

$welcome= str_replace("{USERNAME}",$user->getUsername(),$template->checkPubMsg(8948));
$template->setValue("{WELCOME}",$welcome);

$template->setValue("{BALANCE}",$balance);

$premium_status=$mysql->echo_one("select premium_status from ppc_publishers where uid=".$user_id);
if($premium_status==1)
$template->setValue("{PREMIUM}",$template->checkPubMsg(10103));
else
$template->setValue("{PREMIUM}",$template->checkPubMsg(10104)); 

$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));   
//$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8947));
//$template->setValue("{ENGINE_TITLE}",$engine_title."1111");

$encourage= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8949));
$encourage= str_replace("{ADV_PROFIT}",$advertiser_referral_profit,$encourage);
$encourage= str_replace("{PUB_PROFIT}",$publisher_referral_profit,$encourage);
$template->setValue("{ENCOURAGE}",$encourage);

$REQUEST= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8950));
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
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

//added on 11 november 2009
//$referral_system=1;
//$advertiser_referral_profit=30;
//$publisher_referral_profit=50;
$com_account=$mysql->echo_one("select common_account_id from ppc_publishers where uid=".$user_id);
$template->setValue("{COM_ACCOUNT}",$com_account);
$template->setValue("{REFFERAL}",$referral_system);
$template->setValue("{ADV_PROFIT}",$advertiser_referral_profit);
$template->setValue("{PUB_PROFIT}",$publisher_referral_profit);

//*************************5.4***************************************//

//$site_target=$mysql->echo_one("select common_account_id from ppc_publishers where uid=".$user_id);
$template->setValue("{SITE_TARGET}",$site_targeting);

//*************************5.4***************************************//

$time=time();
$message_count=$mysql->echo_one("select count(*) from messages where messagefor='publisher' and date>'$time' and status='1' order by id DESC");

if($message_count!=0)
{
$template->openLoop("MESS","select message from messages where messagefor='publisher' and date>'$time' and status='1' order by id DESC");
$template->setLoopField("{LOOP(MESS)-MESSAG}","message");
$template->closeLoop();
}
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);
//echo   $template->getPage();
eval('?>'.$template->getPage().'<?php ');

?>
