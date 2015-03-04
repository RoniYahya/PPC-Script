<?php
include_once("functions.inc.php");
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
$xml_start='<?xml version="1.0" encoding="'.$ad_display_char_encoding.'"?><ads>';
$xml_end='</ads>';




$ref=safeRead(urldecode(trim($_GET['ref'])));
$user_ip=urldecode($_GET['user_ip']);
$uid_get=trim($_GET['user_id']);
if($uid_get=="")
$uid_get=0;

$start_limit=trim($_GET['start_record']);
$end_limit=trim($_GET['count']);
$keywords=urldecode($_GET['keywords']);
$url_datas=urldecode($_GET['url']);
$ps_ads=0;
if(isset($_GET['ps']))
$ps_ads=urldecode($_GET['ps']);
$authority_get=trim($_GET['auth_code']);
if(isset($_GET['wap']))
$wap_flag=intval($_GET['wap']);
else
$wap_flag=0;

if($wap_flag=="")
$wap_flag=0;


if(isset($_GET['lang']))
$lang=trim($_GET['lang']);
else
$lang=0;

if($lang=="")
$lang=0;

$adunit_rendered=trim($_GET['r']);

//phpSafe($user_ip);
phpSafe($uid_get);
phpSafe($start_limit);
phpSafe($end_limit);
phpSafe($keywords);
phpSafe($url_datas);
phpSafe($authority_get);
phpSafe($wap_flag);
phpSafe($lang);
phpSafe($ref);

$pid=$uid_get;




if($pid == 0)
$pub_traffic_analysis=$traffic_analysis;
else 
$pub_traffic_analysis=$mysql->echo_one("select traffic_analysis from ppc_publishers where uid='$pid'");




if($pid == 0)
{
$original_url_string=$server_dir;


$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow >=1)
$serverid=$mysql->echo_one("select id from server_configurations where srv_type='1'");
else
$serverid=1;

}
else
{

$serverid=0;
if(isset($_GET['server_path_id']) && isset($_GET['original_url_string']))
{

$original_url_string=urldecode(trim($_GET['original_url_string']));
$serverid=$_GET['server_path_id'];


}
else if(!isset($_GET['server_path_id']) || !isset($_GET['original_url_string']))
{
$crow=$mysql->echo_one("select count(id) from server_configurations");
if($crow <=0)
{

$original_url_string=$server_dir;
$serverid=1;

}
else
{



$adu_pub_id=$pid;

if($adu_pub_id !=0)
{
$srv_id=$mysql->echo_one("select server_id from ppc_publishers where uid='$adu_pub_id'");

$srvcounts=$mysql->echo_one("select count(id) from server_configurations where id='$srv_id'");
if($srvcounts >0)
$srv_id=$srv_id;
else
$srv_id=0;


if($srv_id !=0)
{
$server_url=mysql_query("select server_url,srv_type from server_configurations where id='$srv_id'");
$server_url_row=mysql_fetch_row($server_url);
if($server_url_row[1] !=1)
{

$server_dir=urlencode($server_dir);
$keywords=urlencode($keywords);
$user_ip=urlencode($user_ip);
$url_datas=urlencode($url_datas);
$ps_ads=urlencode($ps_ads);
$ref=urlencode($ref);

$url=$server_url_row[0]."xml-ads.php?user_ip=".$user_ip."&r=".$adunit_rendered."&user_id=".$uid_get."&ref=".$ref."&keywords=".$keywords."&ps=".$ps_ads."&server_path_id=".$srv_id."&url=".$url_datas."&original_url_string=".$server_dir."&auth_code=".$authority_get."&wap=".$wap_flag."&lang=".$lang."&start_record=".$start_limit."&count=".$end_limit;
header("Location:$url");
die;






}
else
{
$original_url_string=$server_dir;
$serverid=$srv_id;

}



}
else
{
$server_url=mysql_query("select server_url,srv_type,id from server_configurations where min_range<='$adu_pub_id' and  max_range >='$adu_pub_id'");

if(mysql_num_rows($server_url) >0)
{


$server_url_row=mysql_fetch_row($server_url);
if($server_url_row[1] !=1)
{
$server_dir=urlencode($server_dir);
$keywords=urlencode($keywords);
$user_ip=urlencode($user_ip);
$url_datas=urlencode($url_datas);
$ps_ads=urlencode($ps_ads);
$ref=urlencode($ref);

$url=$server_url_row[0]."xml-ads.php?user_ip=".$user_ip."&r=".$adunit_rendered."&user_id=".$uid_get."&ref=".$ref."&keywords=".$keywords."&ps=".$ps_ads."&server_path_id=".$server_url_row[2]."&url=".$url_datas."&original_url_string=".$server_dir."&auth_code=".$authority_get."&wap=".$wap_flag."&lang=".$lang."&start_record=".$start_limit."&count=".$end_limit;

header("Location:$url");
die;







}
else
{
$original_url_string=$server_dir;
$serverid=$server_url_row[2];

}





}
else
{

$original_url_string=$server_dir;
$serverid=1;

}







}


}
else
{
$original_url_string=$server_dir;
$serverid=1;
}
}



}






}









include("geo/geoip.inc");





$impression_id_str="";
$impression_id_str_pub="";

$public_ip=$user_ip;

$gi = geoip_open("geo/GeoIP.dat",GEOIP_STANDARD);
$record = geoip_country_code_by_addr($gi, $public_ip);

$enc_ip=md5($public_ip);
includeClass("Cache");
$cache = new Cache($GLOBALS['cache_timeout'],$GLOBALS['cache_folder']);
 $uri = $_SERVER['REQUEST_URI'];

$uri =$uri.$record;
$u="f";
$adunit_rendered=trim($_GET['r']);
if($adunit_rendered=="f")
$u="t";


$ref_status=0;
$direct_status=0;
if($ref=="")
	{
	
	$ref_status=0;
	$direct_status=1;
	}
else
	{
	$ref_status=1;
	$direct_status=0;
	}
$send_direct_status=md5($direct_status);
//$cur_page=mysql_real_escape_string( $_SERVER['HTTP_REFERER']);

$cur_page=$_SERVER['HTTP_REFERER'];

$visit_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("Y",time()));











$cache->cache_file = md5($uri) . ".php";
$red_url = $cache->cache_folder . "/" . $cache->cache_file;
if( $cache->is_cached() ) 
{
		if($maintenance_mode['enabled']!=1)
{
if($pub_traffic_analysis !=1 && $u !="t")  
mysql_close();
	

	//header("Location:$red_url?vip=".$enc_ip."&u=".$u);
	//die;
	
	include ($red_url);
    die;
}
}


if($maintenance_mode['enabled']==1)
{
$ps_ads=2;
}




ob_start();
header('Content-Type: text/xml; charset='.$ad_display_char_encoding);


$singleaccount="";
$publisher_st=" ";
$adrotation="ORDER BY b.time ASC";
$adrotation1="ORDER BY x.time ASC";
if($ad_rotation=="0")
{
	$adrotation="ORDER BY b.time ASC";
	$adrotation1="ORDER BY x.time ASC";
}
elseif($ad_rotation=="1")
{
	$adrotation="ORDER BY b.maxcv DESC,b.time ASC";
	$adrotation1="ORDER BY x.maxcv DESC,x.time ASC";
}
elseif($ad_rotation=="2")
{
	$adrotation="ORDER BY b.weightage DESC,b.time ASC";
	$adrotation1="ORDER BY x.weightage DESC,x.time ASC";
}
elseif($ad_rotation=="3")
{
	$adrotation="ORDER BY b.weightage DESC,b.time ASC";
	$adrotation1="ORDER BY x.weightage DESC,x.time ASC";
}


/*if($GLOBALS['cache_timeout']==0)
$view_names="ppc_keywords";
else*/
$view_names="view_keywords";
 





if($pid != 0)
{

if($single_account_mode==1)
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid='$uid_get'");
	//$singleaccount.="AND c.common_account_id!='$commonid' AND c.parent_status!=0";
	
	$singleaccount.="AND ((c.common_account_id!='$commonid' AND c.parent_status ='1') OR (c.common_account_id ='0' AND c.parent_status ='0'))";
	$publisher_st.="AND ((common_account_id >'0' AND parent_status ='1') OR (common_account_id ='0' AND parent_status ='0'))";
	//$publisher_st.="AND parent_status!=0";
}

}


$textadblock=""; 

$xmlstatus=$mysql->echo_one("select xmlstatus from `ppc_publishers` where uid='$uid_get'".$publisher_st);
if(($xmlstatus!=1) && ($uid_get!=0))
{	

echo $xml_start.$textadblock.$xml_end;
exit(0);
}



$lan=mysql_query("select count(*) from adserver_languages where id='$lang'");
$res1=mysql_num_rows($lan);
//$rr=mysql_fetch_row($lan);
	



if($res1==0)
{
	$lang=0;
}

$authority=md5($uid_get);
$a=strlen($authority);
$a=$a-6;
$authority=substr($authority,0,-$a);
if($lang!=0)
{
	$language="AND (a.adlang='$lang' or a.adlang='0')";
	$ps_lang="AND (adlang='$lang' or adlang='0')";
}
else
{
	/*$mec_lan=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
$ab=explode(",",$mec_lan);
$ab1=explode("-", $ab[0]);
$mechine=$mysql->echo_one("select id from adserver_languages where code='$ab1[0]'") ;
	$language="AND (a.adlang='$mechine' or a.adlang='0')";
	$ps_lang="AND (adlang='$mechine' or adlang='0')";
	//$language="";
	*/
	$language="";
	$ps_lang="";
	

}


if($uid_get==0)
{
	$authority=$GLOBALS['xml_auth_code'];
}

if($authority_get!=$authority)
{
echo $xml_start.$xml_end;
exit(0);
}







$bonous_string="";
if($pid != 0)
{
if($bonous_system_type ==1)
$bonous_string="AND c.accountbalance >= b.maxcv AND c.accountbalance >0";
else if($bonous_system_type ==0)
$bonous_string="AND (c.accountbalance >= b.maxcv OR c.bonusbalance >= b.maxcv) AND (c.accountbalance >0 OR c.bonusbalance >0)";
}
else
$bonous_string="AND (c.accountbalance >= b.maxcv OR c.bonusbalance >= b.maxcv) AND (c.accountbalance >0 OR c.bonusbalance >0)";









$ini_error_status=ini_get('error_reporting');


//echo "hai";
//exit();
if($pid!=0)
{
$publisher=mysql_query("select status,traffic_analysis from ppc_publishers where uid='$uid_get'".$publisher_st);
$publisher_row=mysql_fetch_row($publisher);
$pub_status=$publisher_row[0];
if($pub_status==0)
{
	echo $xml_start.$xml_end;
	exit(0);
}
}

$cont_type=$ad_display_char_encoding;


$keywords=explode(",",$keywords);


$array3 =explode(" ",$ignoreList);
$keyword = array_diff ($keywords, $array3);
$count=count($keyword);//we should take count of array before finding diff.



$keywordstr=" ( b.keyword=' '";

for($i=0;$i<$count;$i++)
{
	$keyword[$i]=trim( $keyword[$i]);
	if($keyword[$i]!="")
	{
		$keywordstr.="or b.keyword='$keyword[$i]' ";
	}
}
$keywordstr.=" ) ";



$geo_condition="  (";

if($record!="")
{
	$geo_condition.=" (d.country='$record' and d.region='' and d.city='') or ";
}
$geo_condition.=" (d.country='00' and d.region='00' and d.city='00') ) ";


//$geo_condition.=" (d.country is null) ) ";

//$geo_condition.=" (d.country='00' and d.region='00' and d.city='00') ) ";


//$geo_condition1.=" (d.country is null) ) ";

if($pid !=0)
$pub_traffic_analysis= $publisher_row[1];
else
$pub_traffic_analysis=$traffic_analysis;


$cur_page=mysql_real_escape_string($cur_page);

	if($pub_traffic_analysis==1 )
	{
	
	
	
	
	if($mysql_server_type==1)
	{
	$visit_query=mysql_query("select id from publisher_daily_visits_statistics_master where pid='$pid' and time='$visit_time' and page_url='$cur_page'");
	if($vid=mysql_fetch_row($visit_query))
	{
	$vid_row[0]=$vid[0];
	mysql_query("update publisher_daily_visits_statistics_master set direct_hits=direct_hits+$direct_status,referred_hits=referred_hits+$ref_status where  id='$vid[0]'");
    }
	else
	{
	mysql_query("	INSERT INTO `publisher_daily_visits_statistics_master` (`id`, `pid`, `page_url`, `direct_hits`, `referred_hits`, `direct_clicks`, `referred_clicks`, `direct_impressions`, `referred_impressions`, `direct_invalid_clicks`, `referred_invalid_clicks`, `direct_fraud_clicks`, `referred_fraud_clicks`, `time`,`serverid`) VALUES
('0', '$pid', '$cur_page', '$direct_status','$ref_status', 0, 0, 0, 0, 0, 0, 0, 0, '$visit_time','$serverid')");
	$lastid=mysql_query("SELECT LAST_INSERT_ID() ");
	if($ini_error_status!=0)
	{
	echo mysql_error();
	}
	$vid_row=mysql_fetch_row($lastid);
	}
	
    }
	else if($mysql_server_type==2)
	{
	
	
	$visit_query=mysql_query("select id from publisher_daily_visits_statistics_slave where pid='$pid' and time='$visit_time' and page_url='$cur_page'");
	if($vid=mysql_fetch_row($visit_query))
	{
	$vid_row[0]=$vid[0];
	mysql_query("update publisher_daily_visits_statistics_slave set direct_hits=direct_hits+$direct_status,referred_hits=referred_hits+$ref_status where  id='$vid[0]'");
    }
	else
	{
	mysql_query("	INSERT INTO `publisher_daily_visits_statistics_slave` (`id`, `pid`, `page_url`, `direct_hits`, `referred_hits`, `direct_clicks`, `referred_clicks`, `direct_impressions`, `referred_impressions`, `direct_invalid_clicks`, `referred_invalid_clicks`, `direct_fraud_clicks`, `referred_fraud_clicks`, `time`,`serverid`) VALUES
('0', '$pid', '$cur_page', '$direct_status','$ref_status', 0, 0, 0, 0, 0, 0, 0, 0, '$visit_time','$serverid')");
	$lastid=mysql_query("SELECT LAST_INSERT_ID() ");
	if($ini_error_status!=0)
	{
	echo mysql_error();
	}
	$vid_row=mysql_fetch_row($lastid);
	}
	
    
	
	
	
	}
	







	}	


if($pub_traffic_analysis!=1)
{
	$vid_row=array(0);
}


 //        *************New Code For Text Ads********************

if($ps_ads==2)
{
	//echo "select id, link, title, summary, displayurl from ppc_public_service_ads where adtype=0 AND status=1 and wapstatus = $wap_flag $ps_lang order by lastacesstime ASC  LIMIT $start_limit,$end_limit ";
$publicresults=mysql_query("select id, link, title, summary, displayurl from ppc_public_service_ads where adtype=0 AND status=1 and wapstatus = $wap_flag $ps_lang order by lastacesstime ASC  LIMIT $start_limit,$end_limit ");
}
else
{

$textresultsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.displayurl, a.uid, b.time
FROM ppc_users c, ".$view_names." b, ppc_ads a, ad_location_mapping d 
WHERE a.uid = c.uid 
AND a.id = b.aid
AND c.uid = b.uid
and a.id = d.adid
AND ".$geo_condition." 
AND a.maxamount > a.amountused AND (a.maxamount - a.amountused) >= b.maxcv
AND a.status =1
AND a.pausestatus =0
AND a.wapstatus =0
AND a.adtype =0
AND ".$keywordstr."
AND c.status =1 
AND b.status =1 
".$bonous_string." 
AND a.wapstatus=".$wap_flag."
".$language."
".$singleaccount."
GROUP BY a.id 
".$adrotation."
LIMIT $start_limit,$end_limit";




    $adresult=mysql_query($textresultsql);
 }   
   


$ad_cnt=0;
$ad_cnt=mysql_num_rows($adresult);

if($ps_ads==2)
{

if(mysql_num_rows($publicresults) > 0 )
{



				while($adrow=mysql_fetch_row($publicresults))
				{
					
					
									
mysql_query("update ppc_public_service_ads set lastacesstime='".time()."' where id='$adrow[0]'");
					$ad_display_url=$adrow[4];
					
					
					if($ad_display_url=="")
					{
						
						$ad_display_url=$adrow[1];
						
					}	
						 $d_description=$adrow[3]; 
					 
				    
				    $textadblock.=  '<ad>'	;	
				    
		$textadblock.='<title><![CDATA['.$adrow[2].']]></title>';
		$textadblock.='<description><![CDATA['.$d_description.']]></description>';
		$textadblock.='<displayurl><![CDATA['.$ad_display_url.']]></displayurl>';
		$textadblock.='<targeturl><![CDATA['.$original_url_string.'ppc-ps-ad-click.php?id='.$adrow[0].']]></targeturl>';
        $textadblock.='<clickbid><![CDATA[0]]></clickbid>';         
		           $textadblock.='</ad>';
				    
				    
				}

}


}
else
{
if(mysql_num_rows($adresult) > 0 )
{



				while($adrow=mysql_fetch_row($adresult))
				{
					
					$textadcount+=1;
					$currentadcount+=1;
					updateAgeingAndImpression ($mysql,$adrow,$ad_ageing_factor,$pid,-1,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid);
					$ad_display_url=$adrow[6];
					
					
					if($ad_display_url=="")
					{
						
						$ad_display_url=$adrow[1];
						
					}	
						 $d_description=$adrow[3]; 
					 
				    
				    $textadblock.=  '<ad>'	;	
				    
		$textadblock.='<title><![CDATA['.$adrow[2].']]></title>';
		$textadblock.='<description><![CDATA['.$d_description.']]></description>';
		$textadblock.='<displayurl><![CDATA['.$ad_display_url.']]></displayurl>';
		$textadblock.='<targeturl><![CDATA['.$original_url_string.'xml-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&wap='.$wap_flag.']]></targeturl>';
        $textadblock.='<clickbid><![CDATA['.$adrow[5].']]></clickbid>';         
		           $textadblock.='</ad>';
				    
				    
				}

}
}
echo $xml_start.$textadblock.$xml_end;

function updateAgeingAndImpression ($mysql,$row,$ad_ageing_factor,$pid,$adunitid,$vid,$public_ip,$fraud_time_interval,$direct_status,$serverid)
{
    global $mysql_server_type;
	global $impression_id_str;
	global $impression_id_str_pub;
	
	
	$ini_error_status=ini_get('error_reporting');
	$lastAccTime=0;
	$uid=0;
	
	
		$uid=$row[7];
		$lastAccTime=$row[8];
	
	
	$incrAccTime=($ad_ageing_factor/3)*24*60*60;
	$lastAccTime=$lastAccTime+$incrAccTime;
	$currTime=time();
	//$currTime=mktime(date("H",$currTime)+1,0,0,date("m",$currTime),date("d",$currTime),date("y",$currTime));//new
	//if($lastAccTime>$currTime)
	
	
if($incrAccTime == 0)
$lastAccTime=$currTime;
		
	if($GLOBALS['cache_timeout']==0)
	mysql_query("update view_keywords set time='$lastAccTime' where id='$row[4]'");		

	mysql_query("update ppc_keywords set time='$lastAccTime' where id='$row[4]'");		
	$currTime=mktime(date("H",$currTime)+1,0,0,date("m",$currTime),date("d",$currTime),date("y",$currTime));//new

	if($ini_error_status!=0)
	{
		echo mysql_error();
	}
	
	$today=$currTime-($fraud_time_interval*60*60);
	
	
		if($mysql_server_type==1)
		mysql_query("insert into advertiser_impression_hourly_master(`id`,`uid`,`aid`,`kid`,`time`,`cnt`,`server_id`) value('0','$uid','$row[0]','$row[4]','".$currTime."','1','$serverid')");
		else if($mysql_server_type==2)
		mysql_query("insert into advertiser_impression_hourly_slave(`id`,`uid`,`aid`,`kid`,`time`,`cnt`,`server_id`) value('0','$uid','$row[0]','$row[4]','".$currTime."','1','$serverid')");
		$last_id_res=mysql_query("SELECT LAST_INSERT_ID() ");
		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
		$last_id_row=mysql_fetch_row($last_id_res);
		$last_id=$last_id_row[0];
		$impression_id_str.=$last_id_row[0].",";
		
		
		if($mysql_server_type==1)
		mysql_query("insert into publisher_impression_hourly_master(`id`,`pid`,`bid`,`time`,`cnt`,`server_id`) value('0','$pid','$adunitid','".$currTime."','1','$serverid')");
		else if($mysql_server_type==2)
		mysql_query("insert into publisher_impression_hourly_slave(`id`,`pid`,`bid`,`time`,`cnt`,`server_id`) value('0','$pid','$adunitid','".$currTime."','1','$serverid')");
		
		$last_id_res_pub=mysql_query("SELECT LAST_INSERT_ID() ");
		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
		$last_id_row_pub=mysql_fetch_row($last_id_res_pub);
		$last_id_pub=$last_id_row_pub[0];
		$impression_id_str_pub.=$last_id_row_pub[0].",";
		
		
		
	
	if($direct_status==1)
		{
		
		if($mysql_server_type==1)
		mysql_query("update publisher_daily_visits_statistics_master set direct_impressions=direct_impressions+1 where id='$vid'");
		else if($mysql_server_type==2)
		mysql_query("update publisher_daily_visits_statistics_slave set direct_impressions=direct_impressions+1 where id='$vid'");
		
		}
		else
		{
		if($mysql_server_type==1)
		mysql_query("update publisher_daily_visits_statistics_master set referred_impressions=referred_impressions+1 where id='$vid'");
		else if($mysql_server_type==2)
		mysql_query("update publisher_daily_visits_statistics_slave set referred_impressions=referred_impressions+1 where id='$vid'");
		
		}
	
	
	
	
	


	if($ini_error_status!=0)
	{
		echo mysql_error();
	}

}
function safeRead($target)
{
	global $ad_display_char_encoding;
	if(strcasecmp($ad_display_char_encoding,"UTF-8")==0)
		$target = htmlspecialchars($target, ENT_QUOTES, "UTF-8");
	else
		$target = htmlspecialchars($target,ENT_QUOTES);

	if(!get_magic_quotes_gpc())	
	$target=mysql_real_escape_string($target);

	return $target;
}


$ret = ob_get_contents();	


if($GLOBALS['cache_timeout']>0 && !$cache->is_cached() ) 
{
$cache_template='<?php
header(\'Content-Type: text/xml; charset='.$ad_display_char_encoding.'\');
ob_start();
$impression_str="'.$impression_id_str.'";
$impression_str_pub="'.$impression_id_str_pub.'";


$pub_traffic_analysis="'.$pub_traffic_analysis.'";
$ad_cnt="'.$ad_cnt.'";
$pid="'.$pid.'";


//$u_flag=$_GET[\'u\'];
$u_flag=$u;







if($pub_traffic_analysis==1)
{
 

  
$cur_page=mysql_real_escape_string($cur_page);

if($mysql_server_type==1)
	{
	
$visit_query=mysql_query("select id from publisher_daily_visits_statistics_master where pid=\'$pid\' and time=\'$visit_time\' and  page_url=\'$cur_page\'");
if($vid=mysql_fetch_row($visit_query))
			{
			$vid_row[0]=$vid[0];
			if( $u_flag=="t")
			mysql_query("update publisher_daily_visits_statistics_master set direct_hits=direct_hits+$direct_status,referred_hits=referred_hits+$ref_status where  id=\'$vid[0]\'");
			}
		else
			{
mysql_query("	INSERT INTO `publisher_daily_visits_statistics_master` (`id`, `pid`, `page_url`, `direct_hits`, `referred_hits`, `direct_clicks`, `referred_clicks`, `direct_impressions`, `referred_impressions`, `direct_invalid_clicks`, `referred_invalid_clicks`, `direct_fraud_clicks`, `referred_fraud_clicks`, `time`,`serverid`) VALUES
(\'0\', \'$pid\', \'$cur_page\', \'$direct_status\',\'$ref_status\', 0, 0, 0, 0, 0, 0, 0, 0, \'$visit_time\',\'$serverid\')");
			$lastid=mysql_query("SELECT LAST_INSERT_ID() ");
			if($ini_error_status!=0)
			{
				echo mysql_error();
			}
		$vid_row=mysql_fetch_row($lastid);
			}
     }
else if($mysql_server_type==2)
	{
	
	
$visit_query=mysql_query("select id from publisher_daily_visits_statistics_slave where pid=\'$pid\' and time=\'$visit_time\' and  page_url=\'$cur_page\'");
if($vid=mysql_fetch_row($visit_query))
			{
			$vid_row[0]=$vid[0];
			if( $u_flag=="t")
			mysql_query("update publisher_daily_visits_statistics_slave set direct_hits=direct_hits+$direct_status,referred_hits=referred_hits+$ref_status where  id=\'$vid[0]\'");
			}
		else
			{
mysql_query("	INSERT INTO `publisher_daily_visits_statistics_slave` (`id`, `pid`, `page_url`, `direct_hits`, `referred_hits`, `direct_clicks`, `referred_clicks`, `direct_impressions`, `referred_impressions`, `direct_invalid_clicks`, `referred_invalid_clicks`, `direct_fraud_clicks`, `referred_fraud_clicks`, `time`,`serverid`) VALUES
(\'0\', \'$pid\', \'$cur_page\', \'$direct_status\',\'$ref_status\', 0, 0, 0, 0, 0, 0, 0, 0, \'$visit_time\',\'$serverid\')");
			$lastid=mysql_query("SELECT LAST_INSERT_ID() ");
			if($ini_error_status!=0)
			{
				echo mysql_error();
			}
		$vid_row=mysql_fetch_row($lastid);
			}
     
	
	
	
	}
		

 //finding vid end 
 
 
 if( $u_flag=="t")
 {
 if($direct_status==1)
		{
		if($mysql_server_type==1)      
		mysql_query("update publisher_daily_visits_statistics_master set direct_impressions=direct_impressions+$ad_cnt where id=\'$vid_row[0]\'");
		else if($mysql_server_type==2)
		mysql_query("update publisher_daily_visits_statistics_slave set direct_impressions=direct_impressions+$ad_cnt where id=\'$vid_row[0]\'");
		}
		else
		{
		if($mysql_server_type==1)     
		mysql_query("update publisher_daily_visits_statistics_master set referred_impressions=referred_impressions+$ad_cnt where id=\'$vid_row[0]\'");
        else if($mysql_server_type==2)
		mysql_query("update publisher_daily_visits_statistics_slave set referred_impressions=referred_impressions+$ad_cnt where id=\'$vid_row[0]\'");
		}
 }		
 
 }








if($u_flag=="t")
{





	if($impression_str!="")
	{
		$impression_str=substr($impression_str,0,-1);
	if($mysql_server_type==1)
	mysql_query("update advertiser_impression_hourly_master set cnt=cnt+1 where id in ($impression_str)");
	else if($mysql_server_type==2)
	mysql_query("update advertiser_impression_hourly_slave set cnt=cnt+1 where id in ($impression_str)");
	
    }
	if($impression_str_pub!="")
	{
		$impression_str_pub=substr($impression_str_pub,0,-1);
		
	if($mysql_server_type==1)
	mysql_query("update publisher_impression_hourly_master set cnt=cnt+1 where id in ($impression_str_pub)");
	else if($mysql_server_type==2)
	mysql_query("update publisher_impression_hourly_slave set cnt=cnt+1 where id in ($impression_str_pub)");
		
		
	}
	
	
	


	//TO DO : update data in visit table
	
	ob_clean();
}
echo \''.$xml_start.'\';
?>'.$textadblock.'<?php
echo \''.$xml_end.'\';


//$encr_ip=$_GET[\'vip\'];
$encr_ip=$enc_ip;

$buf = ob_get_contents();	
$buf = str_replace("{ENC_IP}",$encr_ip, $buf);
ob_clean();
echo $buf;
?>';	
file_put_contents($red_url,$cache_template);
}

$ret = str_replace("{ENC_IP}",$enc_ip, $ret);
ob_clean();
echo $ret;
//ob_end_flush();


?>