<?php



$ini_error_status=ini_get('error_reporting');

include("extended-config.inc.php");  

include($GLOBALS['admin_folder']."/config.inc.php");

//device configurations

include_once("browser.php");
$browser = new Browser();


//device configurations

/*if($server_dir!=substr($_SERVER['HTTP_REFERER'],0,strlen($server_dir)))
{

header("Location: $server_dir");

exit(0);
}
*/
//echo $_SERVER['REQUEST_URI'];

//echo "<br><br>";

//exit(0);


include("geo/geoip.inc");
$gi = geoip_open("geo/GeoIP.dat",GEOIP_STANDARD);


$fraudstatus=0;




if(isset($_GET['Flsids']))
{
$Flsids=$_GET['Flsids'];
$Flsids_array=explode('__',$Flsids);



$aid=intval($Flsids_array[0]);
$kid=explode('=',$Flsids_array[1]);
$kid=intval($kid[1]);
$bid=explode('=',$Flsids_array[2]);
$bid=intval($bid[1]);
$vid=explode('=',$Flsids_array[3]);
$vid=intval($vid[1]);
$vip=explode('=',$Flsids_array[4]);
$vip=$vip[1];
$direct_status=explode('=',$Flsids_array[5]);
$direct_status=$direct_status[1];
phpsafe($direct_status);

if($direct_status==md5(1))
$direct_status=1;
else
$direct_status=0;

phpsafe($vip);



//echo $aid."<br>".$kid."<br>".$bid."<br>".$vid."<br>".$vip;exit;

if($kid=="" || $aid =="")
{
	header("location:$server_dir");
	exit(0);
}
}
else
{

if(!isset($_GET['kid']) || !isset($_GET['id']))
{
	header("location:$server_dir");
	exit(0);
}

$kid=intval($_GET['kid']);
$aid=intval($_GET['id']);
if(isset($_GET['bid']))
$bid=intval($_GET['bid']);
else
$bid=0;
$vid=intval($_GET['vid']);
$direct_status=$_GET['direct_status'];
phpsafe($direct_status);
if($direct_status==md5(1))
$direct_status=1;
else
$direct_status=0;

$vip=$_GET['vip'];
phpsafe($vip);

}





if($kid<1||$aid<1||$bid<0||$vid<0)

{

	header("Location:$server_dir");

	exit(0);

}

$resultad=mysql_query("select uid,link,amountused,maxamount,wapstatus from ppc_ads where id='$aid' and status=1");
$adrow=mysql_fetch_array($resultad);
if(!$adrow)
{
	header("Location:$server_dir");
	exit(0);
}

$set_str_v_path=md5(substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"?")+1).$ppc_engine_name);
if($set_str_v_path!=$_COOKIE['_io_vc'])
	{
		header("Location:$adrow[link]");
		exit(0);
	}

if($GLOBALS['maintenance_mode']['enabled']==1)
{

	header("Location:$adrow[link]");
	exit(0);
}


$uid=$adrow['uid'];

$resultuser=mysql_query("select rid,bonusbalance,uid,accountbalance,balancestatus from ppc_users where uid='$uid'");


if($ini_error_status!=0)
{
	echo mysql_error();
}

$urow=mysql_fetch_array($resultuser);





$adv_rid=$urow['rid'];
$serverid=1;

$new_time=time();
$currTime=time();

$currTime=mktime(date("H",$currTime)+1,0,0,date("m",$currTime),date("d",$currTime),date("y",$currTime));//new

mysql_query("update ppc_keywords set time='".$currTime."' where id='$kid'");







if($adrow['wapstatus']==1)

$public_ip=$_SERVER['REMOTE_ADDR'];

else

$public_ip=getUserIP();



$temp=md5($ppc_engine_name);


$record = geoip_country_code_by_addr($gi, $public_ip);
            



/*

if($temp!=$_COOKIE["verify_click"])

	{

	header("Location:$adrow[link]");

	exit(0);

	}

*/


//added on 31 auguest 2009
//************************************************************* Publisher Proxy Fraud Validation Fraud Validation 1 *****************************************
if($proxy_detection==1)
{
	if(proxyDetection())
	{
		
		mysql_query("insert into ppc_fraud_clicks values ('0',$aid,0,".$new_time.",'$public_ip',3,'$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");
		
		
		
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks+1 where id='$vid'");

		
		
		
header("Location:$adrow[link]");
exit(0);	
		
	}
}
//************************************************************* Publisher Proxy Fraud Validation Fraud Validation 1 *****************************************
//added on 31 auguest 2009



//************************************************************* Publisher Invalid Geo Validation Fraud Validation 2 *****************************************

$count_fr=mysql_query("select country from ad_location_mapping where adid='$aid'");
$flag_location_status=0;

	while($count1_fr=mysql_fetch_row($count_fr))
	{
     if($count1_fr[0]==$record || $count1_fr[0] =='00')
     {
	  $flag_location_status=1;
	  break;
     }
	}



if($flag_location_status == 0)
{



mysql_query("insert into ppc_fraud_clicks values ('0',$aid,0,".$new_time.",'$public_ip','5','$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");


if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks+1 where id='$vid'");

		
header("Location:$adrow[link]");
exit(0);

}
//************************************************************* Publisher Invalid Geo Validation Fraud Validation 2 *****************************************



//************************************************************* Invalid IP Fraud Validation Fraud Validation 3 *****************************************
if($vip!=md5($public_ip))
{

mysql_query("insert into ppc_fraud_clicks values ('0','$aid',0,".$new_time.",'$public_ip',2,'$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");


if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks+1 where id='$vid'");

		

header("Location:$adrow[link]");
exit(0);

}
//************************************************************* Invalid IP Fraud Validation Fraud Validation 3 *****************************************




//************************************************************* Publisher Repetative Click Validation Fraud Validation 4 *****************************************
$today=$currTime-($fraud_time_interval*60*60);
//finding fraud click count from ppc_fraud_clicks table for the particular ad
$clickcount1=$mysql->echo_one("select count(*) from ppc_fraud_clicks where aid=$aid and clicktime>$today and ip='$public_ip'");
//finding click count from ppc_clicks table
$clickcount2=$mysql->echo_one("select count(*) from ppc_daily_clicks where uid='$uid' and aid=$aid and time>$today and ip='$public_ip'");
if($clickcount1>0 || $clickcount2>0)
{
	
	mysql_query("insert into ppc_fraud_clicks values ('0',$aid,0,".$new_time.",'$public_ip',0,'$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");

	if($direct_status==1)
	mysql_query("update publisher_daily_visits_statistics_master set direct_repeated_click=direct_repeated_click+1 where id='$vid'");
    else
	mysql_query("update publisher_daily_visits_statistics_master set referred_repeated_click=referred_repeated_click+1 where id='$vid'");




header("Location:$adrow[link]");
exit(0);
}


//************************************************************* Publisher Repetative Click Validation Fraud Validation 4 *****************************************











if($fraudstatus ==0 )

{

	$resultkwdcv=$mysql->echo_one("select maxcv from ppc_keywords where id='$kid' and aid='$aid' and status=1");

	

	if($resultkwdcv>0)

	{

		

		$newaccountbalance=$urow['bonusbalance']-$resultkwdcv;

		$newamoutused=$adrow['amountused']+$resultkwdcv;

		if($newaccountbalance>=0 && $newamoutused<=$adrow['maxamount'])

		{

			$adv_ref_share=0;

			if($referral_system==1)

			{

			if($adv_rid!=0)	

				$adv_ref_share=$resultkwdcv*$advertiser_referral_profit/100;

			}	

			

			if(mysql_query("insert into ppc_daily_clicks (uid,aid,kid,clickvalue,ip,time,adv_rid,adv_ref_profit,bid,vid,country,`current_time`,`browser`,`platform`,`version`,`user_agent`,`serverid`,`direct_status`) values('$urow[uid]','$aid','$kid','$resultkwdcv','".$public_ip."','".$currTime."','$adv_rid','$adv_ref_share','$bid','$vid','$record','$new_time','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')"))

			{

				mysql_query("update ppc_ads set amountused='$newamoutused' where id='$aid'");

				



				if($ini_error_status!=0)

					{

					echo mysql_error();

					}

				mysql_query("UPDATE ppc_users set bonusbalance='$newaccountbalance' where uid='$urow[uid]'");	

				if($adv_rid!=0)	

					mysql_query("UPDATE ppc_publishers set accountbalance=accountbalance+'$adv_ref_share' where uid='$adv_rid'");	

				



				if($ini_error_status!=0)

				{

					echo mysql_error();

				}

			}

			else

			{

				



				if($ini_error_status!=0)

				{

					echo mysql_error();

				}

			}

		}

		else

		{

			$newaccountbalance=$urow['accountbalance']-$resultkwdcv;

			$newamoutused=$adrow['amountused']+$resultkwdcv;

			if($newaccountbalance>=0 && $newamoutused<=$adrow['maxamount'])

			{

				

				$adv_ref_share=0;

				if($referral_system==1)

				{

				if($adv_rid!=0)	

					$adv_ref_share=$resultkwdcv*$advertiser_referral_profit/100;

				}	
				
				
				if(mysql_query("insert into ppc_daily_clicks (uid,aid,kid,clickvalue,ip,time,adv_rid,adv_ref_profit,bid,vid,country,`current_time`,`browser`,`platform`,`version`,`user_agent`,`serverid`,`direct_status`) values('$urow[uid]','$aid','$kid','$resultkwdcv','".$public_ip."','".$currTime."','$adv_rid','$adv_ref_share','$bid','$vid','$record','$new_time','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')"))

				{

					mysql_query("update ppc_ads set amountused='$newamoutused' where id='$aid'");

					



if($ini_error_status!=0)

{

	echo mysql_error();

}

					mysql_query("UPDATE ppc_users set accountbalance='$newaccountbalance' where uid='$urow[uid]'");	

					if($adv_rid!=0)	

						mysql_query("UPDATE ppc_publishers set accountbalance=accountbalance+'$adv_ref_share' where uid='$adv_rid'");	

					



if($ini_error_status!=0)

{

	echo mysql_error();

}

				}

				else

				{

					



if($ini_error_status!=0)

{

	echo mysql_error();

}

				}

			}

			

			//$newaccountbalance=$mysql->echo_one("select accountbalance from  `ppc_users`  where uid='$urow[uid]'");

			if($newaccountbalance<=$advertiser_minimum_account_balance and $urow['balancestatus']==0)

			{

				$user_details=mysql_query("select email,username from `ppc_users`  where uid='$uid'");

				$user_row=mysql_fetch_row($user_details);

				$user_email=$user_row[0];

				$user_name=$user_row[1];

				$type=15;

				$email_details=mysql_query("select email_subject,email_body from email_templates where id='$type'");

				$email_row=mysql_fetch_row($email_details);

				$sub=$email_row[0];			

				$body=$email_row[1];

				$body=str_replace("{USERNAME}",$user_name,$body);

				$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
				$body=html_entity_decode($body,ENT_QUOTES);

				$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);

				if($script_mode!="demo")

					xMail($user_email, "$sub", $body, $admin_general_notification_email, "$email_encoding");



mysql_query("update `ppc_users` set balancestatus=1 where uid='$uid'");

//*******************************



			}

		}

		$max_keyword_cv=$mysql->echo_one("select max(maxcv) from ppc_keywords where aid='$aid'");

		if( ($adrow['maxamount']-$newamoutused)  < $max_keyword_cv )

		{

			$user_details=mysql_query("select email,username from `ppc_users`  where uid='$uid'");

			$user_row=mysql_fetch_row($user_details);

			$user_email=$user_row[0];

			$user_name=$user_row[1];

			$type=16;

			$email_details=mysql_query("select email_subject,email_body from email_templates where id='$type'");

			$email_row=mysql_fetch_row($email_details);

			$sub=$email_row[0];			

			$body=$email_row[1];

			$body=str_replace("{USERNAME}",$user_name,$body);

			$body=str_replace("{AD_ID}",$aid,$body);

			$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
			$body=html_entity_decode($body,ENT_QUOTES);
			$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);

			if($script_mode!="demo")

				xMail($user_email, "$sub", $body, $admin_general_notification_email, "$email_encoding");

		}



	}	


	if($direct_status==1)
	mysql_query("update publisher_daily_visits_statistics_master set direct_clicks=direct_clicks+1 where id='$vid'");
	else
    mysql_query("update publisher_daily_visits_statistics_master set referred_clicks=referred_clicks+1 where id='$vid'");

		

}

header("Location:$adrow[link]");

exit(0);



?>