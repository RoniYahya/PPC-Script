<?php


$ini_error_status=ini_get('error_reporting');
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


/*
if($server_dir!=substr($_SERVER['HTTP_REFERER'],0,strlen($server_dir)))
{

header("Location: $server_dir");

exit(0);
}
*/
include_once("browser.php");
$browser = new Browser();
includeClass("User");
$user=new User("ppc_publishers");



   include("geo/geoip.inc");
			$gi = geoip_open("geo/GeoIP.dat",GEOIP_STANDARD);
           

$fraudstatus=0;
$publisherfraudstatus=0;

if(isset($_GET['Flsids']))
{
$Flsids=$_GET['Flsids'];
$Flsids_array=explode('__',$Flsids);



$aid=intval($Flsids_array[0]);
$kid=explode('=',$Flsids_array[1]);
$kid=intval($kid[1]);
$pid=explode('=',$Flsids_array[2]);
$pid=intval($pid[1]);

$bid=explode('=',$Flsids_array[3]);
$bid=intval($bid[1]);
$vid=explode('=',$Flsids_array[4]);
$vid=intval($vid[1]);
$vip=explode('=',$Flsids_array[5]);
$vip=$vip[1];
$direct_status=explode('=',$Flsids_array[6]);
$direct_status=$direct_status[1];
$bs=explode('=',$Flsids_array[7]);
$bs=$bs[1];


phpsafe($direct_status);

if($direct_status==md5(1))
$direct_status=1;
else
$direct_status=0;

phpsafe($vip);



//echo $aid."<br>".$kid."<br>".$bid."<br>".$vid."<br>".$vip;exit;

if($kid=="" || $aid =="" || $pid =="")
{
	header("location:$server_dir");
	exit(0);
}
}
else
{

if(!isset($_GET['kid']) || !isset($_GET['id']) || !isset($_GET['pid']))
{
	header("location:$server_dir");
	exit(0);
}

$kid=intval($_GET['kid']);
$aid=intval($_GET['id']);
$pid=intval($_GET['pid']);
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
$bs=$_GET['bs'];
}


if($kid<1||$aid<1||$pid<1||$bid<0||$vid<0)

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

if($GLOBALS['maintenance_mode']['enabled']==1)
{

	header("Location:$adrow[link]");
	exit(0);
}


$time=time();
if($adrow['wapstatus']==1)
$public_ip=$_SERVER['REMOTE_ADDR'];
else
$public_ip=getUserIP();


 $record = geoip_country_code_by_addr($gi, $public_ip);


$pub_row=$mysql->select_one_row("select rid,lastloginip,username,lastlogin,server_id from ppc_publishers where uid='$pid'");
if(!$pub_row)

{

	header("Location:$server_dir");

	exit(0);

}


$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
$serverid=1;
else
{

$serverid=$pub_row[4];
if($serverid == 0)
$serverid=$mysql->echo_one("select id from server_configurations where min_range<='$pid' and  max_range >='$pid'");

}


if($serverid == 0 || $serverid =="")
$serverid=1;






// captcha validation for publisher clicks
 
//***************************************************************** Bot Fraud Validation Fraud Validation 1 ***************************************************
$pub_captcha_status=$mysql->echo_one("SELECT captcha_status FROM `ppc_publishers` where uid=$pid");

$f_status=0;
if(isset($_GET['f_status']))
$f_status=1;

if(isset($_GET['fid']))
$fid=$_GET['fid'];
phpSafe($fid);

$img_flag=0;
if(isset($_GET['image']))
{
$img=$_GET['image'];
phpSafe($img);

if(md5($img)==$_COOKIE['image_random_value'] )
{
		$img_flag=1;
		
		$vid_boat=$mysql->echo_one("select vid from ppc_fraud_clicks where `id` = $fid ;");
		
if($serverid == 1)
{
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks-1 where id='$vid_boat'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks-1 where id='$vid_boat'");
}
		
		
		
		mysql_query("DELETE FROM `ppc_fraud_clicks` WHERE `id` = $fid ;");
		
		
		
		
		
		
}
}



if($pub_captcha_status==1 && $captcha_status==1 && $img_flag !=1)
{
	
	if($f_status==0)
	{
	mysql_query("insert into ppc_fraud_clicks values ('0','$aid','$pid',".$time.",'$public_ip',4,'$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");
	$fid=$mysql->select_last_id();
	
	
if($serverid == 1)
{
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks+1 where id='$vid'");
}
		
	
	
	}
   	include_once("captcha.php");
    exit;	
}




$bs_key=md5($aid.$kid.$bid);
if($bs != $bs_key && $captcha_status==1)
{

if($img_flag ==1)
{
	header("Location:$adrow[link]");
	exit(0);
}
	if($f_status==0)
	{
	mysql_query("insert into ppc_fraud_clicks values ('0','$aid','$pid',".$time.",'$public_ip',4,'$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");
	$fid=$mysql->select_last_id();
	
if($serverid == 1)
{
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks+1 where id='$vid'");
}
		
	
	
	}
	mysql_query("UPDATE `ppc_publishers` SET `captcha_status` = '1',`captcha_time` = '".(time()+60*60*$captcha_time)."' WHERE `uid` =$pid ") ;
	include_once("captcha.php");
    exit;
	
}

//***************************************************************** Bot Fraud Validation Fraud Validation 1 ***************************************************



//added on 31 auguest 2009
//************************************************************* Publisher Proxy Fraud Validation Fraud Validation 2 *****************************************
if($proxy_detection==1)
{
	if(proxyDetection())
	{
	
	mysql_query("insert into ppc_fraud_clicks values ('0',$aid,$pid,".$time.",'$public_ip',3,'$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");
	
	
	
if($serverid == 1)
{
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks+1 where id='$vid'");
}
		


	header("Location:$adrow[link]");
	exit(0);
	}
}

//************************************************************* Publisher Proxy Fraud Validation Fraud Validation 2 *****************************************
//added on 31 auguest 2009




//************************************************************* Publisher Invalid Geo Validation Fraud Validation 3 *****************************************

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




mysql_query("insert into ppc_fraud_clicks values ('0',$aid,$pid,".$time.",'$public_ip','5','$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");


if($serverid == 1)
{
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks+1 where id='$vid'");
}
		







	header("Location:$adrow[link]");
	exit(0);




}













//************************************************************* Publisher Invalid Geo Validation Fraud Validation 3 *****************************************







$set_str_v_path=md5(substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"?")+1).$ppc_engine_name);

if($set_str_v_path!=$_COOKIE['_io_vc'])
	{
		header("Location: $adrow[link]");
		exit(0);
	}



$uid=$adrow['uid'];

$resultuser=mysql_query("select uid,rid,accountbalance,balancestatus,bonusbalance from ppc_users where uid='$uid'");

if($ini_error_status!=0)
{
	echo mysql_error();
}

$urow=mysql_fetch_array($resultuser);

$adv_rid=$urow['rid'];

$cookie=$_COOKIE['_rct'];//repetitive click tracking
$data_expiry=$time + ($fraud_time_interval*60*60);

$cookie_new="";


$cookie=explode(",",$cookie);
$cnt_cookie=count($cookie);
$flag_clk=0;
$ad_found=0;

for($i=0;$i<$cnt_cookie;$i++)
{

       $exploded_data=explode("-",$cookie[$i]);
       if($exploded_data[0]==$aid)
       {
               if($time<$exploded_data[1])
               {
                       $flag_clk=1;
                       $cookie_new.=$cookie[$i].",";
               }
               else
               {
                       $cookie_new.=$exploded_data[0]."-".$data_expiry.",";
               }
               $ad_found=1;
       }
       else
       {
               if($time<$exploded_data[1])
               {
                       $cookie_new.=$cookie[$i].",";
               }

       }
}

if($fraud_time_interval==0)
$flag_clk=0;

if($ad_found==0)

$cookie_new.=$aid."-".$data_expiry.",";
$cookie_new=substr($cookie_new,0,-1);
setcookie("_rct",$cookie_new);


//die;

$pub_rid=$pub_row[0];
$pub_lastlogintime=$pub_row[3];
$currTime=time();
$currTime=mktime(date("H",$currTime)+1,0,0,date("m",$currTime),date("d",$currTime),date("y",$currTime));//new
mysql_query("update ppc_keywords set time='".$currTime."' where id='$kid'");



$temp=md5($ppc_engine_name);
         

/*

if($temp!=$_COOKIE["verify_click"])

	{

	header("Location:$adrow[link]");

	exit(0);

	}

*/



//************************************************************* Invalid IP Fraud Validation Fraud Validation 4 *****************************************
if($vip!=md5($public_ip))
{

mysql_query("insert into ppc_fraud_clicks values ('0','$aid','$pid',".$time.",'$public_ip',2,'$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");

if($serverid == 1)
{
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_invalid_clicks=direct_invalid_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_invalid_clicks=referred_invalid_clicks+1 where id='$vid'");
}
		

header("Location:$adrow[link]");
exit(0);

}


//************************************************************* Invalid IP Fraud Validation Fraud Validation 4 *****************************************


//************************************************************* Publisher Fraud Validation Fraud Validation 5 *****************************************
$today=$currTime-($fraud_time_interval*60*60);
if($pub_lastlogintime>$today)
{
	$publisher_ip=$pub_row[1];
	if($publisher_ip== $public_ip)
	{
		
	mysql_query("insert into ppc_fraud_clicks values ('0',$aid,$pid,".$time.",'$public_ip','1','$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");

if($serverid == 1)
{
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_fraud_clicks=direct_fraud_clicks+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_fraud_clicks=referred_fraud_clicks+1 where id='$vid'");
}
		

	$user->sendFraudAlert($pub_row[2],$admin_general_notification_email,$ppc_engine_name);

	header("Location:$adrow[link]");

	exit(0);

		

	}

} 

//************************************************************* Publisher Fraud Validation Fraud Validation 5 *****************************************


//************************************************************* Publisher Repetative Click Validation Fraud Validation 6 *****************************************


//finding fraud click count from ppc_fraud_clicks table for the particular ad
$clickcount1=$mysql->echo_one("select count(*) from ppc_fraud_clicks where aid=$aid and clicktime>$today and ip='$public_ip'");
//finding click count from ppc_clicks table
$clickcount2=$mysql->echo_one("select count(*) from ppc_daily_clicks where uid='$uid' and aid=$aid and time>$today and ip='$public_ip'");
if($clickcount1>0 || $clickcount2>0 || $flag_clk==1)
{

	

		mysql_query("insert into ppc_fraud_clicks values ('0',$aid,$pid,".$time.",'$public_ip','0','$bid','$vid','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')");


if($serverid == 1)
{
if($direct_status==1)
mysql_query("update publisher_daily_visits_statistics_master set direct_repeated_click=direct_repeated_click+1 where id='$vid'");
else
mysql_query("update publisher_daily_visits_statistics_master set referred_repeated_click=referred_repeated_click+1 where id='$vid'");
}    

	header("Location:$adrow[link]");
	exit(0);



}


//************************************************************* Publisher Repetative Click Validation Fraud Validation 6 *****************************************














if($fraudstatus==0 && $publisherfraudstatus==0)

{

	$resultkwdcv=$mysql->echo_one("select maxcv from ppc_keywords where id='$kid' and aid='$aid' and status=1");

	if($resultkwdcv>0)//$krow=mysql_fetch_array($resultkwd))

	{

//****************************************************** New Logic ***************************************************************************

    $balance_data_flag=0;
	if($bonous_system_type ==1)
	{
	$newaccountbalance=$urow['accountbalance']-$resultkwdcv;
	}
	else if($bonous_system_type ==0)
	{
	$newaccountbalance=$urow['bonusbalance']-$resultkwdcv;
	if($newaccountbalance < 0)
	{
	$balance_data_flag=1;
	$newaccountbalance=$urow['accountbalance']-$resultkwdcv;
	}
	}
	
//****************************************************** New Logic ***************************************************************************	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

		//$newaccountbalance=$urow['accountbalance']-$resultkwdcv;

		$newamoutused=$adrow['amountused']+$resultkwdcv;

		if($newaccountbalance>=0 && $newamoutused<=$adrow['maxamount'])

		{
$premium=$mysql->echo_one("select premium_status from ppc_publishers where uid=$pid");
			
if($premium==1)
{
	$pre_profit=$premium_profit;
}
else
$pre_profit=$publisher_profit;


			$newamountbalance=$resultkwdcv*$pre_profit/100;

			$pub_ref_share=0;

			$adv_ref_share=0;

			if($referral_system==1)

			{

			if($pub_rid!=0)

				$pub_ref_share=$resultkwdcv*$publisher_referral_profit/100;

			$adv_ref_share=0;

			if($adv_rid!=0)	

				$adv_ref_share=$resultkwdcv*$advertiser_referral_profit/100;

			}
			if(mysql_query("insert into ppc_daily_clicks (uid,aid,kid,clickvalue,ip,time,pid,publisher_profit,pub_rid,pub_ref_profit,adv_rid,adv_ref_profit,bid,vid,country,`current_time`,`browser`,`platform`,`version`,`user_agent`,`serverid`,`direct_status`) values('$urow[uid]','$aid','$kid','$resultkwdcv','".$public_ip."','".$currTime."','$pid','$newamountbalance','$pub_rid','$pub_ref_share','$adv_rid','$adv_ref_share','$bid','$vid','$record','$time','".$browser->getBrowser()."','".$browser->getPlatform()."','".$browser->getVersion()."','".$browser->getUserAgent()."','$serverid','$direct_status')"))
	{

				mysql_query("update ppc_ads set amountused='$newamoutused' where id='$aid'");

				

if($ini_error_status!=0)

{

	echo mysql_error();

}






        //****************************************************** New Logic ***************************************************************************
                if($bonous_system_type ==1 || $balance_data_flag == 1)
				mysql_query("UPDATE ppc_users set accountbalance='$newaccountbalance' where uid='$urow[uid]'");	
				else
				mysql_query("UPDATE ppc_users set bonusbalance='$newaccountbalance' where uid='$urow[uid]'");	
				
		//****************************************************** New Logic ***************************************************************************		
				
				
				
				

				mysql_query("UPDATE ppc_publishers set accountbalance=accountbalance+'$newamountbalance' where uid='$pid'");	

				if($pub_rid!=0)

					mysql_query("UPDATE ppc_publishers set accountbalance=accountbalance+'$pub_ref_share' where uid='$pub_rid'");	

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

			



 if($bonous_system_type ==1 || $balance_data_flag == 1)                                        //****************************** New Logic *****************************
 {

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


			}
			
}			                                                                        //****************************** New Logic *****************************
			
			
			
			
			
			
			
			

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

	}

if($serverid == 1)
{
		if($direct_status==1)
		mysql_query("update publisher_daily_visits_statistics_master set direct_clicks=direct_clicks+1 where id='$vid'");
     	else
		mysql_query("update publisher_daily_visits_statistics_master set referred_clicks=referred_clicks+1 where id='$vid'");
}
		

}	



header("Location:$adrow[link]");

exit(0);



?>