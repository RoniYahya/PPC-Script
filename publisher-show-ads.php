<?php


//echo "hai";exit;
$query_str=$_SERVER['QUERY_STRING'];

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
include_once("functions.inc.php");

$z = date('H', time());

$adunitid=intval($_GET['id']);
$adunit_ht=intval($_GET['ht']);
$blockcount=$_GET['blockcount'];
$keywordstr=urldecode(trim($_GET['search']));
$title="";
if(isset($_GET['title']))
	$title=urldecode(trim($_GET['title']));
//echo "title=".$title;
$desc="";
if(isset($_GET['desc']))
	$desc=urldecode(trim($_GET['desc']));
$keywordstr=safeRead($keywordstr);
$title=safeRead($title);
$desc=safeRead($desc);
$ref=safeRead(urldecode(trim($_GET['ref'])));
$adunit_rendered=trim($_GET['r']);



$adu_pub_id=$mysql->echo_one("select pid from ppc_custom_ad_block where id='$adunitid'");
$pub_traffic_analysis=$mysql->echo_one("select traffic_analysis from ppc_publishers where uid='$adu_pub_id'");



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






if($adu_pub_id !=0 && $adu_pub_id !="")
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
$keywordstr=urlencode($keywordstr);
$ref=urlencode($ref);
if(isset($_GET['title']) && isset($_GET['desc']))
{
$title=urlencode($title);
$desc=urlencode($desc);

$url=$server_url_row[0]."publisher-show-ads.php?id=".$adunitid."&ht=".$adunit_ht."&r=".$adunit_rendered."&blockcount=".$blockcount."&search=".$keywordstr."&title=".$title."&desc=".$desc."&ref=".$ref."&server_path_id=".$srv_id."&original_url_string=".$server_dir;
header("Location:$url");
die;

//http://localhost/workspace/adserver-5.3/publisher-show-ads.php?id=2&ht=105&r=t&blockcount=1&content_type=&search=&title=&desc=&ref=
}
else
{

$url=$server_url_row[0]."publisher-show-ads.php?id=".$adunitid."&ht=".$adunit_ht."&r=".$adunit_rendered."&blockcount=".$blockcount."&search=".$keywordstr."&ref=".$ref."&server_path_id=".$srv_id."&original_url_string=".$server_dir;
header("Location:$url");
die;
//http://localhost/workspace/adserver-5.3/publisher-show-ads.php?id=2&ht=105&r=f&blockcount=1&content_type=&search=aaa&ref=

}



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
$keywordstr=urlencode($keywordstr);
$ref=urlencode($ref);
if(isset($_GET['title']) && isset($_GET['desc']))
{
$title=urlencode($title);
$desc=urlencode($desc);

$url=$server_url_row[0]."publisher-show-ads.php?id=".$adunitid."&ht=".$adunit_ht."&r=".$adunit_rendered."&blockcount=".$blockcount."&search=".$keywordstr."&title=".$title."&desc=".$desc."&ref=".$ref."&server_path_id=".$server_url_row[2]."&original_url_string=".$server_dir;

header("Location:$url");
die;
//http://localhost/workspace/adserver-5.3/publisher-show-ads.php?id=2&ht=105&r=t&blockcount=1&content_type=&search=&title=&desc=&ref=
}
else
{

$url=$server_url_row[0]."publisher-show-ads.php?id=".$adunitid."&ht=".$adunit_ht."&r=".$adunit_rendered."&blockcount=".$blockcount."&search=".$keywordstr."&ref=".$ref."&server_path_id=".$server_url_row[2]."&original_url_string=".$server_dir;

header("Location:$url");
die;
//http://localhost/workspace/adserver-5.3/publisher-show-ads.php?id=2&ht=105&r=f&blockcount=1&content_type=&search=aaa&ref=

}




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


include_once("functions.inc.php");




$public_ip=getUserIP();
$enc_ip=md5($public_ip);



include("geo/geoip.inc");
$gi = geoip_open("geo/GeoIP.dat",GEOIP_STANDARD);
$record = geoip_country_code_by_addr($gi, $public_ip);

includeClass("Cache");

$cache = new Cache($GLOBALS['cache_timeout'],$GLOBALS['cache_folder']);
$uri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],"&ht"));
 

 
$uri =$uri.$record;

//************************5.4**************************************//

if($site_targeting==1)
{


if(isset($_GET['hostname']))
	$hostname=urldecode(trim($_GET['hostname']));
	
	$hostname=safeRead($hostname);
	
	$hostname1=substr($hostname,0,4);
	   
    if($hostname1=="www.")
     $hostname=substr($hostname,4);
 
 	$temp_hostname=explode("?", $hostname); 
 	$temp_hostname1=explode("/", $temp_hostname[0]);
	$domain= $temp_hostname1[0]; 


	//echo $domain= $_SERVER['HTTP_HOST'];
	//$domain = str_replace("www.","", $_SERVER['HTTP_HOST']);
	$siteid=$mysql->echo_one("select siteid from ppc_site_adunit where auid='$adunitid'");
	$surl=$mysql->echo_one("select url from ppc_publishing_urls where id='$siteid'");
	if($surl != "")
	{
	if($domain!=$surl)
	{
		echo "Domain name is invalid";
		exit(0);
	}
	}

}
//************************5.4**************************************//



if(substr($ref,0,6)=="http//")
{
			$ref=substr($ref,6);
			$ref="http://".$ref;
}
else if(substr($ref,0,7)=="https//")
{

            $ref=substr($ref,7);
			$ref="https://".$ref;

}
			


 
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

$u="f";
$adunit_rendered=trim($_GET['r']);
if($adunit_rendered=="f")
$u="t";
 
 
$cache->cache_file 		= md5($uri) . ".php";
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


ob_start();



$ini_error_status=ini_get('error_reporting');






$result=mysql_query("select pad.*,cad.*,pad.credit_text as padtext,cad.title_color as tcolor,cad.desc_color as dcolor,cad.url_color as ucolor,cad.credit_text as ctext,cad.template as tpl from ppc_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$adunitid'");

// "select pad.*,cad.* from ppc_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$adunitid'";

if($ini_error_status!=0)
{
	echo mysql_error();
}
$adunitrow=mysql_fetch_array($result);
$adlang=$adunitrow['adlang'];
//echo $adunitrow[47];
$singleaccount="";
$publisher_st="";
$adrotation="ORDER BY b.time ASC";
$adrotation1="ORDER BY x.time ASC";
if($ad_rotation=="0")
{$adrotation1="ORDER BY x.time ASC";
	$adrotation="ORDER BY b.time ASC";
}
elseif($ad_rotation=="1")
{$adrotation="ORDER BY b.maxcv DESC,b.time ASC";
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

if($single_account_mode==1)
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid='".$adunitrow['pid']."'");
	//$singleaccount.="AND c.common_account_id!='$commonid' AND c.parent_status!=0";
	$singleaccount.="AND ((c.common_account_id!='$commonid' AND c.parent_status ='1') OR (c.common_account_id ='0' AND c.parent_status ='0'))";
	$publisher_st.="AND ((common_account_id >'0' AND parent_status ='1') OR (common_account_id ='0' AND parent_status ='0'))";
		
	//$publisher_st.="AND parent_status!=0";
}



/*if($GLOBALS['cache_timeout']==0)
$view_names="ppc_keywords";
else*/
$view_names="view_keywords";
 

 /***********************************time********************************************/
 
 $time="";
 
 if($time_targeting==1)
 {

 $time=" AND  (IF (a.end_time < a.beg_time ,".$z.">= a.beg_time AND ".$z."> a.end_time OR  ".$z."<= a.beg_time AND ".$z."< a.end_time , ".$z.">= a.beg_time AND ".$z."< a.end_time ) OR (a.beg_time=0 AND a.end_time=0) OR (a.beg_time<= ".$z." AND a.end_time=0)) ";

 }
 
 
  /***********************************time********************************************/

$bonous_string="";
if($bonous_system_type ==1)
$bonous_string="AND c.accountbalance >= b.maxcv AND c.accountbalance >0";
else if($bonous_system_type ==0)
$bonous_string="AND (c.accountbalance >= b.maxcv OR c.bonusbalance >= b.maxcv) AND (c.accountbalance >0 OR c.bonusbalance >0)";

//*******************
$adunittype=$adunitrow['ad_type'];
$languages="";
//echo $adunittype;exit;
//$adlang=$adunitrow[61];
if($adlang==0)
{
		
	$mec_lan=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
$ab=explode(",",$mec_lan);
$ab1=explode("-", $ab[0]);
$mechine=$mysql->echo_one("select id from adserver_languages where code='$ab1[0]'") ;
$languages="AND (a.adlang='$mechine' or a.adlang='0')";
 $ps_language="AND (adlang='$mechine' or adlang='0')";
}
else
{
	$languages.="AND (a.adlang='$adlang' or a.adlang='0')";
	$ps_language="AND (adlang='$adlang' or adlang='0')";
}

if($maintenance_mode['enabled']!=1)
{
//$adlang=$adunitrow[61];

if($adlang==0)
{
$lang_code=$client_language;
}
else
{
$lang_code=$mysql->echo_one("select code from adserver_languages where id='$adlang'");
}

//$lang_code=$mysql->echo_one("select code from adserver_languages where id='$adlang'");
//

if(!$adunitrow)
{
	exit(0);
}


$special_height_flag=0;
if($adunit_ht ==($adunitrow['height']+15))
{
$special_height_flag=1;
}



if($adunit_ht!=($adunitrow['height']+15) && $adunit_ht!=($adunitrow['height']))
{
	echo "Unable to render ads. Do not change ad unit dimensions.";
	exit(0);
}


//************************************5.4******************************************//

if($adunitrow[adult_status]==0)
{
	$adult_string=" AND a.adult_status=0 ";
}
else if($adunitrow[adult_status]==1)
{
	$adult_string="";
}

//************************************5.4******************************************//


$pid=$adunitrow['pid'];
//echo "select status,traffic_analysis from ppc_publishers where uid='$pid'".$publisher_st;
$publisher=mysql_query("select status,traffic_analysis from ppc_publishers where uid='$pid'".$publisher_st);
$publisher_row=mysql_fetch_row($publisher);
$pub_status=$publisher_row[0];
if($pub_status==0)
{
	exit(0);
}

$cont_type=$ad_display_char_encoding;
//$override=$publisher_row[1];
//if($override==1)
//	$cont_type=$_GET['content_type'];
//if($cont_type=="")
//	 $cont_type =$ad_display_char_encoding;


$adtpl=$adunitrow['tpl'];
$tplname=$mysql->echo_one("select filename from ppc_ad_templates where id=".$adtpl." and status='1'"); 

$keywords=explode(",",$keywordstr);

$title=explode(" ",$title);
$desc=explode(" ",$desc);

$array1 = array_merge ($keywords, $title,$desc);
$array3 =explode(" ",$ignoreList);
$keyword = array_diff ($array1, $array3);
$count=count($array1);//we should take count of array before finding diff.



$keywordstr=" ( b.keyword='".$keywords_default."'";
for($i=0;$i<$count;$i++)
{
	$keyword[$i]=trim( $keyword[$i]);
	if($keyword[$i]!="")
	{
		$keywordstr.="or b.keyword='$keyword[$i]' ";
	}
}
$keywordstr.=" ) ";

$restrictedsiteids=$adunitrow['ppc_restricted_sites'];
$restrictedsites=mysql_query("select site from ppc_restricted_sites  where id in (".$restrictedsiteids.")");
$sqlstr="";
while($site=mysql_fetch_row($restrictedsites))
	{
		if($site[0]!="")
			$sqlstr.=" and a.link not like '%".$site[0]."%' ";
	}	

$textadcount=0;
if(isset($_COOKIE['textadcount']))
	{
		$textadcount=intval($_COOKIE['textadcount']);
	}

$imageadcount="";
if(isset($_COOKIE['imageadcount']))
	{
		$imageadcount=safeRead($_COOKIE['imageadcount']);
	}
$catalogadcount="";
	if(isset($_COOKIE['catalogadcount']))
		{
			$catalogadcount=safeRead($_COOKIE['catalogadcount']);
		}



$geo_condition="  (";

if($record!="")
{
	$geo_condition.=" (d.country='$record' and d.region='' and d.city='') or ";
}

$geo_condition.=" (d.country='00' and d.region='00' and d.city='00') ) ";


//$geo_condition.=" (d.country is null) ) ";

//$geo_condition.=" (d.country='00' and d.region='00' and d.city='00') ) ";


//$geo_condition1.=" (d.country is null) ) ";

$pub_traffic_analysis= $publisher_row[1];
$cur_page=mysql_real_escape_string($cur_page);
if($blockcount==1)
{
$verify_click=md5($ppc_engine_name);
$expire=time()+60*60;
setcookie("verify_click", $verify_click, $expire);

	$textadcount=0;
	$imageadcount="";
	$catalogadcount="";
	$catalog_cook=mysql_query("SELECT id FROM `catalog_dimension` ORDER BY id ");
	$catalog_str="";
	echo mysql_error();
	$j=0;
	$k=1;
	$tot_cat=mysql_num_rows($catalog_cook);
	while($catalog_cookie=mysql_fetch_row($catalog_cook))
	{
		if($k==$tot_cat)	
		$catalog_str.=$catalog_cookie[0].",".$j;
		else
		$catalog_str.=$catalog_cookie[0].",".$j.",";	
	$k++;
	}
	
	$catalogadcount=$catalog_str;
	
	
	
	if($pub_traffic_analysis==1)
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

}
else
{
	if($pub_traffic_analysis==1)
	{
	
	
	if($mysql_server_type==1)
	{
	$lastid=mysql_query("SELECT id from publisher_daily_visits_statistics_master  where pid='$pid' and time='$visit_time' and page_url='$cur_page' order by id desc limit 0,1");
	if($ini_error_status!=0)
	{
	echo mysql_error();
	}
	$vid_row=mysql_fetch_row($lastid);	
	}
	else if($mysql_server_type==2)
	{
	
	$lastid=mysql_query("SELECT id from publisher_daily_visits_statistics_slave  where pid='$pid' and time='$visit_time' and page_url='$cur_page' order by id desc limit 0,1");
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

$imageadstr="";
if($imageadcount!="")
{
	$imageadstr=" AND a.id not in(".$imageadcount.")";
}

$adunittype=$adunitrow['ad_type'];
$adscount=$adunitrow['no_of_text_ads'];
$catalog_adscount=$adunitrow['no_of_catalog_ads'];


 //        *************New Code For Text Ads********************


if($adunitrow['scroll_ad']==0)
{

  $textresultsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.displayurl, a.uid, b.time,a.adlang
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
AND a.time_status=1 
".$time." 
".$bonous_string." 
".$adult_string." 
".$sqlstr." 
".$languages."
".$singleaccount."
GROUP BY a.id 
".$adrotation."
LIMIT $textadcount,".($adscount+1);

}
else if($adunitrow['scroll_ad']==1)
{
	
$textresultsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.displayurl, a.uid, b.time,a.adlang
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
AND a.time_status=1 
".$time." 
".$bonous_string." 
".$adult_string." 
".$sqlstr." 
".$languages."
".$singleaccount."
GROUP BY a.id 
".$adrotation."
LIMIT $textadcount,".($adscount);	
	
	
	
	
	
	
}



        //          *************New Code For Text Ads**********************

		//          *************New Code For Banner Ads********************


 $imageresultsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.uid, b.time,a.contenttype,a.hardcodelinks,a.adlang  
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
AND a.adtype =1
AND ".$keywordstr."
AND c.status =1 
AND b.status =1 
AND a.bannersize ='".$adunitrow['max_size']."' 
AND a.time_status=1 
".$time." 
".$bonous_string." 
".$adult_string." 
".$sqlstr." 
".$imageadstr."
".$languages."
".$singleaccount."
GROUP BY a.id 
".$adrotation."
LIMIT 0,1";







			//          *************New Code For Banner Ads********************






				$catalog_ad_count=0;
				$val=explode(",",$catalogadcount);
				for($j=0;$j<count($val);$j++)
				{
						if($j%2==0)
						{
						if($val[$j]==$adunitrow['catalog_size'])
						$catalog_ad_count=$val[$j+1];
						}
				}
			//echo $catalogadcount;
//print_r($val);



$catalogsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.displayurl, a.uid, b.time,a.contenttype,a.hardcodelinks,a.adlang  
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
AND a.adtype =2
AND ".$keywordstr."
AND c.status =1 
AND b.status =1 
AND a.bannersize='".$adunitrow['catalog_size']."' 
AND a.time_status=1 
".$time." 
".$bonous_string." 
".$adult_string." 
".$sqlstr." 
".$languages."
".$singleaccount."
GROUP BY a.id 
".$adrotation."
LIMIT $catalog_ad_count,".($catalog_adscount+1);







//echo $catalogsql;
if($adunittype==1)
	 $resultsql=$textresultsql;
if($adunittype==2)
	 $resultsql=$imageresultsql;
if($adunittype==4)
	 $resultsql=$catalogsql;

if($adunittype==3)
{
	 $adunittype=1;
	 $resultsql=$textresultsql;
	

if(isset($_COOKIE['view_kwd_id_coo']))	
$view_kwd_id=$_COOKIE['view_kwd_id_coo'];
else
$view_kwd_id=0;

if($view_kwd_id=="")
$view_kwd_id=0;

$newQuery="SELECT a.adtype
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
AND ((a.adtype =0 AND (a.bannersize is null) AND b.id > '$view_kwd_id') OR (a.adtype =1 AND a.bannersize ='".$adunitrow['max_size']."'))
AND ".$keywordstr."
AND c.status =1 
AND b.status =1 
AND a.time_status=1 
".$time." 
".$bonous_string."  
".$adult_string." 
".$sqlstr." 
".$imageadstr."
".$languages."
".$singleaccount."
GROUP BY a.id 
".$adrotation."
LIMIT 0,1";



$newQueryResult=$mysql->echo_one($newQuery);

if(!is_numeric($newQueryResult))
{
 $currenttime=time();
 $randomizer=$currenttime%2;
 if($randomizer==1)
 {
 $resultsql=$imageresultsql;
 $adunittype=2;
 }

}
else if($newQueryResult == 0)
{
     $adunittype=1;
	 $resultsql=$textresultsql;
}
else if($newQueryResult == 1)
{
     $adunittype=2;
	 $resultsql=$imageresultsql;
}
/*else
{
     $currenttime=time();
     $randomizer=$currenttime%2;
  	 if($randomizer==1)
	  {
	 $resultsql=$imageresultsql;
	 $adunittype=2;
	  }
}*/


if($ini_error_status!=0)
{
	echo mysql_error();
}
	

	
}

$ad_cnt=0;
$adresult=mysql_query($resultsql);
$ad_cnt=mysql_num_rows($adresult);

//echo $resultsql;




$next_set=0;
if($adunittype==1 || $adunittype==4)
{

if($adunittype==1)
{
	if(mysql_num_rows($adresult)>$adscount )
	{
	$next_set=2;	
	
	}
}
if($adunittype==4)
{
	if(mysql_num_rows($adresult)>$catalog_adscount )
	{
	$next_set=2;
	
	}
}


$paginglinks="";
if($next_set>0)
{
//width:50px;  

$paginglinks="<div class='inout-credit-next'><a id='nex' style='' href='JavaScript:void(0);' onClick='paging($next_set);return true'>&raquo;</a></div>";

}


}





if($ini_error_status!=0)
{
	echo mysql_error();
}

}


//echo $ps_language;
//echo $adunittype;
if($adunittype==1)
	$psdbadcount=$mysql->total("ppc_public_service_ads","adtype=0 AND status=1 AND wapstatus=0 $ps_language");
if($adunittype==2)
		$psdbadcount=$mysql->total("ppc_public_service_ads","adtype=1 AND status=1 AND bannersize='".$adunitrow['max_size']."'  AND wapstatus=0 $ps_language");
	if($adunittype==4)
	$psdbadcount=$mysql->total("ppc_public_service_ads","adtype=2 AND status=1 AND bannersize='".$adunitrow['catalog_size']."'  AND wapstatus=0 $ps_language");
if($ini_error_status!=0)
{
	echo mysql_error();
}

//$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where id='".$adunitrow['padtext']."'");


$clan=$mysql->echo_one("select value from ppc_settings where name='client_language'");
if($clan=="")
{
	$clan='en';
	$lanid=$mysql->echo_one("select id from adserver_languages where code='en'");
}
else
{
	$lanid=$mysql->echo_one("select id from adserver_languages where code='$clan'");
}



$crstr=$adunitrow['padtext'];

$ct=$mysql->echo_one("select credittype from ppc_publisher_credits where id='".$crstr."'");

if($ct==1)
$bordertype=1;
else
{
if($adunitrow['allow_bordor_type'] == 0)
$bordertype=$adunitrow['border_type'];
else
$bordertype=$adunitrow['bordor_type'];
}



if($adunitrow['adlang']==0)  //anylanguages
{
	if($clan=='en')
	{   
	    if($ct==0)
		$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		else if($ct==1)
		{
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
        $credittext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';
		
		}
		
	}
	else
	{
	
	
	    if($ct==0)
	    {
		$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($credittext=='')
		$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		}
		else if($ct==1)
		{
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($ctimage=='')
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		
		$credittext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';
		}
		
		
	}
}
else
{
	
		if($adunitrow['adlang']==$lanid )
		{
		
		
		if($ct==0)
	    {
				if($clan=='en')
					$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				else
					$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
					
		}
		else if($ct==1)
		{
		
		   if($clan=='en')
					$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		   else
					$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
					
			
			$credittext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
					
		
		}			
					
				
		}
		else
		{
		
		    if($ct==0)
	        {
			$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
			if($credittext=='')
				$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				
			}
			else if($ct==1)
		   {
			
			$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
			if($ctimage=='')
			$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			
			
			$credittext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
			
		   }
				
		}
		if($credittext=='')
		{
		     if($ct==0)
			 $credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			 else if($ct==1)
		     {
			 $ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			 $credittext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
			 }
			 
		}	 
			
		
}










/*
if($adunitrow['adlang']==0)  //anylanguages
{
	if($clan=='en')
	{
		$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$adunitrow['padtext']."' ");
	}
	else
	{
	//echo "select credit from ppc_publisher_credits where parent_id=".$res['ctext']." and language_id='$lanid'";
		$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$adunitrow['padtext']." and language_id='$lanid'");
		if($credittext=='')
			$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$adunitrow['padtext']."' ");
	}
}
else
{
		
		if($adunitrow['adlang']==$lanid )
		{
				if($clan=='en')
					$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$adunitrow['padtext']."' ");
				else
				{
					 $credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$adunitrow['padtext']." and language_id='".$adunitrow['adlang']."'");
				}
		}
		else
		{
			$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$adunitrow['padtext']." and language_id='".$adunitrow['adlang']."'");
			if($credittext=='')
				$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$adunitrow['padtext']." and language_id='$lanid'");
		}
		if($credittext=='')
			$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$adunitrow['padtext']."' ");
			
		
}
*/











if($ini_error_status!=0)
{
	echo mysql_error();
}

$res=mysql_query("select * from ppc_credittext_bordercolor where id='".$adunitrow['credit_color']."'");

if($ini_error_status!=0)
{
	echo mysql_error();
}
$r1=mysql_fetch_row($res);


$impression_id_str="";
$impression_id_str_pub="";

$rtl_lang_id=$mysql->echo_one("select id from adserver_languages where code='ar'");

if(mysql_num_rows($adresult) > 0 || ($psdbadcount > 0))
{

?><html><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cont_type; ?>" /><title></title>
<style type="text/css">
.top, .bottom {display:block; background:transparent; font-size:1px;width:<?php  echo $adunitrow['width']; ?>;}
.tb1, .tb2, .tb3, .tb4 {display:block; overflow:hidden;}
.tb1, .tb2, .tb3 {height:1px;}
.tb2, .tb3, .tb4 {

<?php if($adtpl!=-1) { ?>
<?php 
if($tplname!='')
{
 ?> 


background:url("<?php echo "$original_url_string".$GLOBALS['admin_folder']."/ad-templates/".$adtpl."/".$tplname.""; ?>" );

<?php }  } 
else
{
?>

background:<?php if($adunitrow['credit_text_positioning']==1 && $adunitrow['padtext'] !=0) echo $r1[2]; else echo $adunitrow['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; } ?>;}
.tb1 {margin:0 5px; <?php if($adtpl!=-1) { ?> background:0px; <?php } else {?> background:<?php echo $r1[2]; ?>; <?php } ?> }
.tb2 {margin:0 3px; border-width:0 2px;}
.tb3 {margin:0 2px;}
.tb4 {
<?php if($adtpl!=-1) { ?> height:0px; <?php } else {?> height:2px;<?php } ?>


 margin:0 1px;}
.bb1, .bb2, .bb3, .bb4 {display:block; overflow:hidden;}
.bb1, .bb2, .bb3 {height:1px;}
.bb2, .bb3, .bb4 {background:<?php if($adunitrow['credit_text_positioning']==0 && $adunitrow['padtext'] !=0) echo $r1[2]; else echo $adunitrow['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>;}
.bb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.bb2 {margin:0 3px; border-width:0 2px;}
.bb3 {margin:0 2px;}
.bb4 {height:2px; margin:0 1px;}
.parenttable {display:block; background:#FFFFFF; border-style:solid; border-color: <?php echo $r1[2]; ?>; border-width:0 1px; padding: 0px;margin:0 0px;}


body{

padding:0;
margin:0;
style=background-color:transparent;
<?php
if($adlang==$rtl_lang_id)
{
?>
direction:rtl;

<?php } ?>
}
.inout-table{

<?php 
if($adunittype==2)
{
	?>
	border-width: 0px;
	<?php 
}
else
{
		if($bordertype==0){ ?>
		
		<?php if($adtpl!=-1) { ?> border-left: 0px solid  
	border-right: 0px solid <?php } else { ?>
		
		
		
	border-left: 1px solid  <?php echo $r1[2]; ?>;
	border-right: 1px solid  <?php echo $r1[2]; ?>;
	<?php } ?>
	border-top-width: 0px;
	border-bottom-width: 0px;
	<?php }else {?>
	border: 1px solid  <?php echo $r1[2]; ?>;
	<?php }
	
}	
 ?>
background:<?php if($adunittype==2) echo "#FFFFFF";  else echo  $adunitrow['bg_color']; ?>;
padding:0px 0px;
margin:0px 0px;
table-layout:fixed;
overflow:hidden;
line-height: <?php echo $adunitrow['line_height']; ?>px;


<?php if($adtpl!=-1) { ?>
<?php 
if($tplname!='')
{
 ?> 


background:url("<?php echo "$original_url_string".$GLOBALS['admin_folder']."/ad-templates/".$adtpl."/".$tplname.""; ?>" );

<?php }  } ?>

}
<?php
if($adunittype!=2)
{
?>
.inout-table td
{
<?php if($adunitrow['scroll_ad']==1) { ?>
 padding-left:2px;
<?php } ?>
}
<?php
}
?>
.marqueetable
{
padding:0 0;
margin:0 0;
table-layout:fixed;
overflow:hidden;
line-height: <?php echo $adunitrow['line_height']; ?>px;
}

<?php 
if($adunitrow['ad_type']!=2)
{
?>
		.inout-title a:link,.inout-title a:visited,.inout-title a:hover,.inout-title a:active,.inout-title a:focus
		{
		font-family:<?php echo $adunitrow['title_font'];?>;
		font-size:<?php echo $adunitrow['title_size'];?>;
		color:<?php echo $adunitrow['tcolor'];?>;
		
		<?php
		if($adunitrow['ad_font_weight']==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($adunitrow['ad_title_decoration']==3)
		$deco="blink";
		elseif($adunitrow['ad_title_decoration']==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		padding:0px;
		}
		
		
		.inout-desc 
		{
		font-family:<?php echo $adunitrow['desc_font'];?>;
		font-size:<?php echo $adunitrow['desc_size'];?>;
		color:<?php echo $adunitrow['dcolor'];?>;
		<?php
		if($adunitrow['ad_desc_font_weight']==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($adunitrow['ad_desc_decoration']==3)
		$deco="blink";
		elseif($adunitrow['ad_desc_decoration']==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		padding:0px;
		}
		
		
		.inout-url a:link,.inout-url a:visited,.inout-url a:hover,.inout-url a:active,.inout-url a:focus
		{
		font-family:<?php echo $adunitrow['url_font'];?>;
		font-size:<?php echo $adunitrow['url_size'];?>;
		color:<?php echo $adunitrow['ucolor'];?>;
		<?php
		if($adunitrow['ad_disp_url_font_weight']==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($adunitrow['ad_disp_url_decoration']==3)
		$deco="blink";
		elseif($adunitrow['ad_disp_url_decoration']==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		 white-space:nowrap;
		padding:0px;
		}

<?php 
}
?>

.inout_credit_over
{
font-size:12px;
height:15px;
/*background-image:url(images/tabs.png);*/
background-color:<?php echo $r1[2]; ?>;
background-repeat:repeat;
color:<?php echo $r1[1]; ?>;

<?php
if($adunitrow['credit_text_positioning']==1)  // Top
{
?>
position:absolute;
top:0px;
<?php
}
?>
<?php
if($adunitrow['credit_text_positioning']==0)  // Bottom
{
?>
position:absolute;
bottom:0px;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==1)  // Right
{
?>
right:0px;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==0)  // Left
{
?>
left:0px;
<?php
}
?>


}
.inout_credit_data
{
overflow:hidden;
max-width:<?php echo $adunitrow['width']; ?>px;
<?php
if($adunitrow['credit_text_positioning']==1)  // Top
{
?>
position:absolute;
top:0px;
<?php
}
?>
<?php
if($adunitrow['credit_text_positioning']==0)  // Bottom
{
?>
position:absolute;
bottom:0px;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==1)  // Right
{
?>
right:0px;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==0)  // Left
{
?>
left:0px;
<?php
}
?>

}










.inout-credit
{

height:15px;


<?php
if($ct ==0 && $adunitrow['credit_text_positioning']!=2 && $adunitrow['credit_text_positioning']!=3)
{
?>
width:auto;
background-color:<?php echo $r1[2]; ?>;


<?php 
if($adunittype ==1 || $adunittype ==4)
{
?>
<?php
if($adunitrow['credit_text_alignment']==0)
{
?>
padding-left:5px;
padding-right:0px;
padding-bottom:0px;
padding-top:0px;


<?php
}
else
{
?>
padding-left:0px;
padding-right:5px;
padding-bottom:0px;
padding-top:0px;



<?php
}
}
else
{
?>
padding:0 5;
<?php
}
}
else if($ct ==1 && $adunitrow['credit_text_positioning']!=2 && $adunitrow['credit_text_positioning']!=3)
{
?>
width:auto;
padding:0 0;
<?php
}
?>

<?php
if($ct ==0 && ($adunitrow['credit_text_positioning']==2 || $adunitrow['credit_text_positioning']==3))
{
?>


background-color:<?php echo $r1[2]; ?>;
width:<?php echo $singlewidth; ?>px;
padding:0 5;
overflow:hidden;
<?php
}
else if($ct ==1 && ($adunitrow['credit_text_positioning']==2 || $adunitrow['credit_text_positioning']==3))
{
?>
width:<?php echo $singlewidth; ?>px;
overflow:hidden;
padding:0 0;
<?php
}
?>








white-space:nowrap;

<?php
if($adunitrow['credit_text_alignment']==0)
$t_align="left";
else
$t_align="right";	
?>
text-align:<?php echo $t_align; ?>;
}

.inout-credit-next
{
height:15px;
/*background-color:<?php echo $r1[2]; ?>;
padding:0 5;*/
white-space:nowrap;
overflow:hidden;

/*border:1px solid red;*/
width:40px;


<?php
if($adunitrow['credit_text_alignment']==0)
{
?>
padding-right:0px;
<?php
}
else
{
?>
padding-right:10px;
<?php
}
?>



<?php
if($adunitrow['credit_text_alignment']==0)
{
$te_align="right";
$t_align="right";
}
else
{
$te_align="left";
$t_align="left";
}	
?>
text-align:<?php echo $te_align; ?>;
float:<?php echo $t_align; ?>;
color:<?php echo $r1[1]; ?>;



}
#cred
{

<?php
if($adunitrow['credit_text_alignment']==0)
$t_align="left";
else
$t_align="right";	
?>

float:<?php echo $t_align; ?>;


width:<?php  echo $adunitrow['width']-60; ?>px;
overflow:hidden;

}

#nex 
{
color:<?php echo $r1[1]; ?>;
font-size:22px;
font-family:serif;
background-color:<?php echo $r1[2]; ?>;
padding:0 3px;

}
#nex1 
{
color:<?php echo $r1[1]; ?>;
font-size:22px;
font-family:serif;
background-color:<?php echo $r1[2]; ?>;
padding:0 3px;


}
#nex2 
{
color:<?php echo $r1[1]; ?>;
font-size:22px;
font-family:serif;
background-color:<?php echo $r1[2]; ?>;

padding:0 3px;

}





/*
.inout-credit-next a:link,.inout-credit-next a:visited,.inout-credit-next a:hover,.inout-credit-next a:active,.inout-credit-next a:focus
{

font-family:<?php echo $adunitrow['credit_font'];?>;


<?php
if($adunitrow['credit_text_font_weight']==2)
$f_weight="bold";
else
$f_weight="normal";	
?>
font-weight:<?php echo $f_weight; ?>;

<?php 
if($adunitrow['credit_text_decoration']==3)
$deco="blink";
elseif($adunitrow['credit_text_decoration']==1)
$deco="none";	
else
$deco="underline";		
?>
text-decoration:<?php echo $deco; ?>;


}
*/
.inout-credit a:link,.inout-credit a:visited,.inout-credit a:hover,.inout-credit a:active,.inout-credit a:focus
{
color:<?php echo $r1[1]; ?>;
font-family:<?php echo $adunitrow['credit_font'];?>;
font-size:12px;
line-height: 15px;
<?php
if($adunitrow['credit_text_font_weight']==2)
$f_weight="bold";
else
$f_weight="normal";	
?>
font-weight:<?php echo $f_weight; ?>;
<?php 
if($adunitrow['credit_text_decoration']==3)
$deco="blink";
elseif($adunitrow['credit_text_decoration']==1)
$deco="none";	
else
$deco="underline";		
?>
text-decoration:<?php echo $deco; ?>;
}


</style></head><body><div id="set_1" style="width:<?php  echo $adunitrow['width']; ?>"><?php  
if($adunittype==2)
{
    $bs=md5(rand(0,10));
	$dummy_str='<a style="display: none;" target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img height="'. $adunitrow['height'].'"  width="'.$adunitrow['width'].'" src="'.$original_url_string.'images/cbl.png" border="0"></a>';

		?><table height="<?php if($adunitrow['padtext']==0  || $special_height_flag ==0) echo $adunitrow['height']; else if($adunitrow['padtext']!=0 && $special_height_flag ==1) echo $adunitrow['height']+15; ?>"  width="<?php  echo $adunitrow['width']; ?>" cellpadding="0" cellspacing="0" class="inout-table"><?php
		if($special_height_flag==1)
		{
		if($adunitrow['credit_text_positioning']==1 && $credittext!="")
			echo "<tr ><td class=\"inout-credit\"><a target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";
		}	
			
		if($adunitrow['scroll_ad']==1)
		{
		?><tr><td><table cellpadding="0" cellspacing="0" border="0"><?php
		}
		?><tr ><td align="center"><?php
		echo $dummy_str;
		if($adrow=mysql_fetch_row($adresult))
		{
				$bs=md5($adrow[0].$adrow[4].$adunitid);
				if($imageadcount!="")
				{
					$imageadcount.=",";
				}
				$imageadcount.=$adrow[0];
				updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$pid,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid);
				
				
		 if($adrow[8] != "swf")
		  {
				
				if($special_height_flag !=1)
		        {
				
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")  
				{
				?><div id="idiv_<?php echo $adrow[0]; ?>" class="inout_credit_over" onMouseOver="LoadCreditText(<?php echo $adrow[0]; ?>)">(i)</div><div class="inout_credit_data" id="crd_<?php echo $adrow[0]; ?>" style="display:none;" onMouseOut="DisableCreditText(<?php echo $adrow[0]; ?>)" ><table cellpadding="0" cellspacing="0"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $original_url_string.'index.php?r='.$pid; ?>"><?php echo $credittext;?></a></td></tr></table></div><?php
				}
				}
				?><a target="_blank" href="<?php  echo "{$original_url_string}ppc-publisher-ad-click.php?id=$adrow[0]&kid=$adrow[4]&pid=$pid&bid=$adunitid&vid=$vid_row[0]&vip={ENC_IP}&direct_status=".$send_direct_status.'&bs='.$bs;?>"><img height="<?php echo $adunitrow['height']; ?>"  width="<?php  echo $adunitrow['width']; ?>" src="<?php echo "$original_url_string".$GLOBALS['banners_folder']."/$adrow[0]/$adrow[3]";?>" border="0"></a><?php
				if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==0 && $credittext!="")                //***Bottom Right Credit Text ***************    
				{
				
				?><div id="idiv_<?php echo $adrow[0]; ?>" class="inout_credit_over" onMouseOver="LoadCreditText(<?php echo $adrow[0]; ?>)">(i)</div><div class="inout_credit_data" id="crd_<?php echo $adrow[0]; ?>" style="display:none;" onMouseOut="DisableCreditText(<?php echo $adrow[0]; ?>)" ><table cellpadding="0" cellspacing="0"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $original_url_string.'index.php?r='.$pid; ?>"><?php echo $credittext;?></a></td></tr></table></div><?php
				
				
				}
				}
				
				
			}
		else
		{
		
				$alinkdemo=$original_url_string."ppc-publisher-ad-click.php?Flsids=$adrow[0]&kid=$adrow[4]&pid=$pid&bid=$adunitid&vid=".$vid_row[0]."&vip={ENC_IP}&direct_status=".$send_direct_status.'&bs='.$bs;
				$alinkdemo=str_replace('&','__',$alinkdemo);
				
				
				
							
				
$strHardLinks="";				
if($adrow[9] >0)
{
for($i=0;$i<$adrow[9];$i++)
{
$strHardLinks.="flashvars.alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars.atar".($i+1)."='_blank';";
}

}
				
//	echo $strHardLinks;			
				?>
	<script  language="javascript" type="text/javascript" src="<?php echo $server_dir; ?>swfobject.js"></script>			
	<script type="text/javascript">
	      var flashvars = {};
		  var params = {};
		  var attributes = {};
		  
		   flashvars.clickTag = "<?php  echo $alinkdemo;?>";
		  
		  flashvars.clickTAG = "<?php  echo $alinkdemo;?>";
		  flashvars.clickTARGET = "_blank";
		  
         // flashvars.alink1 = "<?php  //echo $alinkdemo;?>";
		 // flashvars.atar1 = "_blank";
		 
		 <?php echo $strHardLinks; ?>
		   params.wmode="transparent";
		   
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[3];?>", "myFlashDiv", "<?php  echo $adunitrow[1]; ?>", "<?php echo $adunitrow[2]; ?>", "9.0.0", "",flashvars,params,attributes);
</script>





<?php 
                if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")  
				{
				?><div id="idiv_<?php echo $adrow[0]; ?>" class="inout_credit_over" onMouseOver="LoadCreditText(<?php echo $adrow[0]; ?>)">(i)</div><div class="inout_credit_data" id="crd_<?php echo $adrow[0]; ?>" style="display:none;" onMouseOut="DisableCreditText(<?php echo $adrow[0]; ?>)" ><table cellpadding="0" cellspacing="0"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $original_url_string.'index.php?r='.$pid; ?>"><?php echo $credittext;?></a></td></tr></table></div><?php
				}
				}
				?><div id="myFlashDiv"></div><?php
				if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==0 && $credittext!="")                //***Bottom Right Credit Text ***************    
				{
				
				?><div id="idiv_<?php echo $adrow[0]; ?>" class="inout_credit_over" onMouseOver="LoadCreditText(<?php echo $adrow[0]; ?>)">(i)</div><div class="inout_credit_data" id="crd_<?php echo $adrow[0]; ?>" style="display:none;" onMouseOut="DisableCreditText(<?php echo $adrow[0]; ?>)" ><table cellpadding="0" cellspacing="0"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $original_url_string.'index.php?r='.$pid; ?>"><?php echo $credittext;?></a></td></tr></table></div><?php
				
				}
				}
					
		
		
		}	
				
				
				
		}
		else
		{
				$publicresults=mysql_query("select id, link, title, summary, displayurl,contenttype,hardcodelinks,adlang from ppc_public_service_ads where adtype=1 AND status=1 AND bannersize =".$adunitrow['max_size']."  AND wapstatus=0 $ps_language order by lastacesstime ASC LIMIT 0,1");	
				

if($ini_error_status!=0)
{
	echo mysql_error();
}
				if($rowpsad=mysql_fetch_row($publicresults))
				{
					mysql_query("update ppc_public_service_ads set lastacesstime='".time()."' where id='$rowpsad[0]'");
					

if($ini_error_status!=0)
{
	echo mysql_error();
}
				
				if($rowpsad[5] != "swf")
				{
				?>
					
					
					
					
					
					
				<?php 
				if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")  
				{
				?><div id="idiv_<?php echo $rowpsad[0]; ?>" class="inout_credit_over" onMouseOver="LoadCreditText(<?php echo $rowpsad[0]; ?>)">(i)</div><div class="inout_credit_data" id="crd_<?php echo $rowpsad[0]; ?>" style="display:none;" onMouseOut="DisableCreditText(<?php echo $rowpsad[0]; ?>)" ><table cellpadding="0" cellspacing="0"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $original_url_string.'index.php?r='.$pid; ?>"><?php echo $credittext;?></a></td></tr></table></div><?php
				}
				}
				?><a target="_blank" href="<?php  echo "{$original_url_string}ppc-ps-ad-click.php?id=$rowpsad[0]";?>"><img height="<?php echo $adunitrow['height']; ?>"  width="<?php  echo $adunitrow['width']; ?>" src="<?php echo "$original_url_string".$GLOBALS['admin_folder']."/".$GLOBALS['service_banners_folder']."/$rowpsad[0]/$rowpsad[3]";?>" border="0"></a><?php
				if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==0 && $credittext!="")                //***Bottom Right Credit Text ***************    
				{
				
				?><div id="idiv_<?php echo $rowpsad[0]; ?>" class="inout_credit_over" onMouseOver="LoadCreditText(<?php echo $rowpsad[0]; ?>)">(i)</div><div class="inout_credit_data" id="crd_<?php echo $rowpsad[0]; ?>" style="display:none;" onMouseOut="DisableCreditText(<?php echo $rowpsad[0]; ?>)" ><table cellpadding="0" cellspacing="0"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $original_url_string.'index.php?r='.$pid; ?>"><?php echo $credittext;?></a></td></tr></table></div><?php
				}
				}
				
					 }
				else
				{
				
				$alinkdemo=$original_url_string."ppc-ps-ad-click.php?id=$rowpsad[0]";
				
										
$strHardLinksPs="";				
if($rowpsad[6] >0)
{
for($i=0;$i<$rowpsad[6];$i++)
{
$strHardLinksPs.="flashvars.alink".($i+1)."='".$alinkdemo."';";
$strHardLinksPs.="flashvars.atar".($i+1)."='_blank';";
}

}		
				
				
				
				?>
	<script  language="javascript" type="text/javascript" src="<?php echo $server_dir; ?>swfobject.js"></script>			
	<script type="text/javascript">
	      var flashvars = {};
		  var params = {};
		  var attributes = {};
		  
		  flashvars.clickTag = "<?php  echo $alinkdemo;?>";
		  
		  flashvars.clickTAG = "<?php  echo $alinkdemo;?>";
		  flashvars.clickTARGET = "_blank";
		  
         // flashvars.alink1 = "<?php  //echo $alinkdemo;?>";
		 // flashvars.atar1 = "_blank";
		 
		 <?php  echo $strHardLinksPs;?>
		  params.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[3];?>", "myFlashDivPub", "<?php  echo $adunitrow[1]; ?>", "<?php echo $adunitrow[2]; ?>", "9.0.0", "",flashvars,params,attributes);
</script>





<?php           if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")  
				{
				?><div id="idiv_<?php echo $rowpsad[0]; ?>" class="inout_credit_over" onMouseOver="LoadCreditText(<?php echo $rowpsad[0]; ?>)">(i)</div><div class="inout_credit_data" id="crd_<?php echo $rowpsad[0]; ?>" style="display:none;" onMouseOut="DisableCreditText(<?php echo $rowpsad[0]; ?>)" ><table cellpadding="0" cellspacing="0"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $original_url_string.'index.php?r='.$pid; ?>"><?php echo $credittext;?></a></td></tr></table></div><?php
				}
				}
				?><div id="myFlashDivPub"></div><?php
				if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==0 && $credittext!="")                //***Bottom Right Credit Text ***************    
				{
				
				?><div id="idiv_<?php echo $rowpsad[0]; ?>" class="inout_credit_over" onMouseOver="LoadCreditText(<?php echo $rowpsad[0]; ?>)">(i)</div><div class="inout_credit_data" id="crd_<?php echo $rowpsad[0]; ?>" style="display:none;" onMouseOut="DisableCreditText(<?php echo $rowpsad[0]; ?>)" ><table cellpadding="0" cellspacing="0"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $original_url_string.'index.php?r='.$pid; ?>"><?php echo $credittext;?></a></td></tr></table></div><?php
				}
				}
				
					
				
				
				
				
				}
					
					
					
					
				}
		}
		?></td></tr><?php	
		if($adunitrow['scroll_ad']==1)
		{
		?></table></td></tr><?php
		}
		
		if($special_height_flag==1)
		{
	if($adunitrow['credit_text_positioning']==0 && $credittext!="")
			echo "<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";
		}	
		?></table><?php			
}
else if($adunittype==4)
{

?>
<script  language="javascript" type="text/javascript" src="<?php echo $server_dir; ?>swfobject.js"></script>			
<?php 

$blockheight= $adunitrow['height'];

		if($bordertype==0)
		{?>
		<b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b>
		<?php
		$blockheight= $adunitrow['height']-10;
		}
		?>
		<table height="<?php  if($adunitrow['padtext']==0  || $special_height_flag ==0) echo $blockheight; else if($adunitrow['padtext']!=0 && $special_height_flag ==1) echo $blockheight+15; ?>"  width="<?php  echo  $adunitrow['width']; ?>" cellpadding="0" cellspacing="0" class="inout-table">
		
		<?php 
		
		$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='".$adunitrow['catalog_size']."'");
		$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='".$adunitrow['catalog_size']."'");
	//	echo $catalog_width."dddddddd".$adunitrow[42];
	$currentadcount=0;		
	$catalogadblock="";
	$currentcatalogadcount=0;
		if($adunitrow['orientaion']==2)//horizontal
			{
			//echo $catalog_width."ssss";
			//echo "here";
				
			//if($adunitrow['credit_text_positioning']==1 && $adunitrow['padtext'] !=0)
			//$catalogadblock.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\"><a target=\"_blank\" href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";

	
if($adunitrow['credit_text_positioning']==1 && $adunitrow['padtext'] !=0)
$catalogadblock.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\">".$paginglinks."<div id=\"cred\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></div></td></tr>";
					
					
				
		
					
			
			
			
				$catalogadblock.="<tr>";
				$cat_ad_cnt=mysql_num_rows($adresult);
				$i=0;
				while($currentadcount< $catalog_adscount && $adrow=mysql_fetch_row($adresult))
					{
					
					$currentadcount+=1;
					$currentcatalogadcount+=1;
					//echo $currentcatalogadcount."ddd";
					
					updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$pid,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid);
					
					$ad_display_url=$adrow[6];
					if($ad_display_url=="")
					$ad_display_url=$adrow[1];
					if($currentcatalogadcount==1)
					$catalogadblock.='<td width="0" style="display: none;">';
					else
					$catalogadblock.='<td width="{WIDTH}">';
				
				    if($adunitrow['catalog_alignment']==1) 
				    {
				    				  	
if($currentcatalogadcount==1)
{
	$bs=md5(rand(0,10));
	$catalogadblock.='<table width="0" cellpadding="0" height="0" cellspacing="5" border=0  style="display: none;" >';
	$catalogadblock.='<tr>';
    $catalogadblock.='<td align="center" style="height:'.$catalog_height.'px;vertical-align:top;" ><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
	$catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.'/images/cbl.png'.'" border="0"></a> </div></td> </tr><tr><td valign="top">';
	$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" target="_blank">'.$adrow[6].'</a></div>';
	$catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
	$catalogadblock.='</td><td width="{WIDTH}">';
}


$dir_str='';
if($rtl_lang_id==$adrow[11])
$dir_str='direction:rtl;';


$bs=md5($adrow[0].$adrow[4].$adunitid);
						 $catalogadblock.='<table width="100%" cellpadding="0" cellspacing="5" height="100%" border="0"  style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden; '.$dir_str;
						  if($adunitrow['catalog_line_seperator']==1 )
						  { 
						  if( $cat_ad_cnt==$adunitrow['no_of_catalog_ads'] )
							{
								if( $i < $cat_ad_cnt-1 )
						  	 	 $catalogadblock.='border-right:solid 1px '. $r1[2];
							}
						  elseif( $psdbadcount > 0)	
						  	$catalogadblock.='border-right:solid 1px '. $r1[2];
						  elseif( $i < $cat_ad_cnt-1 )	
						  	$catalogadblock.='border-right:solid 1px '. $r1[2];
						  } 
						  
						   $catalogadblock.='">';
						   $catalogadblock.='<tr><td align="center" style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
							
							
                         if($adrow[9] != "swf")
                           {							
							$catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td>';
							
							
						if($dir_str == "")
						$catalogadblock.='<td align="left" valign="top">';
						else
						$catalogadblock.='<td valign="top">';
							
							
							
                           }
                         else
                         {


$tmr=time();		  

                         $alinkdemo=$original_url_string."ppc-publisher-ad-click.php?Flsids=$adrow[0]&kid=$adrow[4]&pid=$pid&bid=$adunitid&vid=".$vid_row[0]."&vip=".$enc_ip."&direct_status=".$send_direct_status.'&bs='.$bs;
                         $alinkdemo=str_replace('&','__',$alinkdemo);
				
				
$strHardLinks="";				
if($adrow[10] >0)
{
for($i=0;$i<$adrow[10];$i++)
{
$strHardLinks.="flashvars_".$adrow[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$adrow[0].".atar".($i+1)."='_blank';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $adrow[0]; ?> = {};
var params_<?php echo $adrow[0]; ?> = {};
var attributes_<?php echo $adrow[0]; ?> = {};
flashvars_<?php echo $adrow[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTARGET = "_blank";
<?php echo $strHardLinks; ?>

params_<?php echo $adrow[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2];?>", "myFlashDiv_<?php echo $adrow[0].$tmr; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $adrow[0]; ?>,params_<?php echo $adrow[0]; ?>,attributes_<?php echo $adrow[0]; ?>);
</script>
		

	<?php	  
	$catalogadblock.='<div id="myFlashDiv_'.$adrow[0].$tmr.'"></div>';
	$catalogadblock.='</div></td>';
	
	
	                    if($dir_str == "")
						$catalogadblock.='<td align="left" valign="top">';
						else
						$catalogadblock.='<td valign="top">';




}							
							
							
							
							
						$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" target="_blank">'.$adrow[6].'</a></div>';
						 $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
				 }
				  else 
				  { 
				  	
if($currentcatalogadcount==1)
{
	$bs=md5(rand(0,10));
	$catalogadblock.='<table width="100%" cellpadding="0" height="100%" cellspacing="5" border=0  style="display: none;" >';
	$catalogadblock.='<tr>';
    $catalogadblock.='<td align="center" style="height:'.$catalog_height.'px;vertical-align:top;" ><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
    $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.'/images/cbl.png'.'" border="0"></a> </div></td> </tr><tr><td valign="top">';
	$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" target="_blank">'.$adrow[6].'</a></div>';
	$catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
	$catalogadblock.='</td><td width="{WIDTH}">';	
}

$bs=md5($adrow[0].$adrow[4].$adunitid);
	$dir_str='';
					if($rtl_lang_id==$adrow[11])
						$dir_str='direction:rtl';			  	
							   $catalogadblock.='<table width="100%" cellpadding="0" height="100%" cellspacing="5" border=0  style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden;'.$dir_str;
								
								if($adunitrow['catalog_line_seperator']==1 ) 
								{
						  if( $cat_ad_cnt==$adunitrow['no_of_catalog_ads'] )
						  {
							if( $i < $cat_ad_cnt-1 )
						  	  $catalogadblock.='border-right:solid 1px '. $r1[2];
						  }
						  elseif( $psdbadcount > 0)	
						  	$catalogadblock.='border-right:solid 1px '. $r1[2];
						  elseif( $i < $cat_ad_cnt-1 )	
						  	$catalogadblock.='border-right:solid 1px '. $r1[2];

								}
							   $catalogadblock.='">';
							 $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;" ><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
							 
							 
							 
							 
						
if($adrow[9] != "swf")
{								 
 $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td> </tr><tr><td valign="top">';
							  
}
else // flash ad
{


$tmr=time();		  

$alinkdemo=$original_url_string."ppc-publisher-ad-click.php?Flsids=$adrow[0]&kid=$adrow[4]&pid=$pid&bid=$adunitid&vid=".$vid_row[0]."&vip=".$enc_ip."&direct_status=".$send_direct_status.'&bs='.$bs;
$alinkdemo=str_replace('&','__',$alinkdemo);
				
				
$strHardLinks="";				
if($adrow[10] >0)
{
for($i=0;$i<$adrow[10];$i++)
{
$strHardLinks.="flashvars_".$adrow[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$adrow[0].".atar".($i+1)."='_blank';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $adrow[0]; ?> = {};
var params_<?php echo $adrow[0]; ?> = {};
var attributes_<?php echo $adrow[0]; ?> = {};
flashvars_<?php echo $adrow[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTARGET = "_blank";
<?php echo $strHardLinks; ?>

params_<?php echo $adrow[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2];?>", "myFlashDiv_<?php echo $adrow[0].$tmr; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $adrow[0]; ?>,params_<?php echo $adrow[0]; ?>,attributes_<?php echo $adrow[0]; ?>);
</script>
		

	<?php	  
	$catalogadblock.='<div id="myFlashDiv_'.$adrow[0].$tmr.'"></div>';
	$catalogadblock.='</div></td> </tr><tr><td valign="top">';




}	//end of flash ad												  
							  
							  
							  
							  
							  
							
							$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" target="_blank">'.$adrow[6].'</a></div>';
							 $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
	 } 		
			 $catalogadblock.='</td>';
			 $i++;
			 
			 }				
		
				//echo $currentadcount."<".$catalog_adscount ;
					if($currentadcount<$catalog_adscount )
				{
					$publicresults=mysql_query("select a.id, link, title, summary, displayurl,contenttype,hardcodelinks,a.adlang from ppc_public_service_ads a where  a.bannersize='".$adunitrow['catalog_size']."'  and adtype=2 AND status=1  AND wapstatus=0 $ps_language order by lastacesstime ASC  LIMIT 0,".($catalog_adscount-$currentadcount));
if($ini_error_status!=0)
{
	echo mysql_error();
}
					$i=0;
					while($rowpsad=mysql_fetch_row($publicresults))
					{
						mysql_query("update ppc_public_service_ads set lastacesstime='".time()."' where id='$rowpsad[0]'");
//					echo "ddd";	

if($ini_error_status!=0)
{
	echo mysql_error();
}
						$disurl=$rowpsad[4];
						if($disurl=="")
						$disurl=$rowpsad[1];
								
				$catalogadblock.='<td width="{WIDTH}">';		
					
			 if($adunitrow['catalog_alignment']==1)
			  { 			  
			  $catalogadblock.='<table width="100%" cellpadding="0" height="100%" cellspacing="5" border=0  style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden;';
			//  echo $psdbadcount;
				if($adunitrow['catalog_line_seperator']==1) 
				{
						if( $i < $psdbadcount-1)	
						 	$catalogadblock.='border-right:solid 1px '. $r1[2];				
				}
				if($rowpsad[7]==$rtl_lang_id)
				$catalogadblock.=';direction:rtl';
				$catalogadblock.='">';
				$catalogadblock.='<tr><td align="center" style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
							 
		
		
		
		if($rowpsad[5] != "swf")
			{						 
							 
		 $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2].'" border="0"></a> </div></td>';
		 
		 
		                if($dir_str == "")
						$catalogadblock.='<td align="left" valign="top">';
						else
						$catalogadblock.='<td valign="top">';
							 
			}
			else
			{
			
			
			$tmrp=time();
			
			
			
$alinkdemo=$original_url_string."ppc-ps-ad-click.php?id=$rowpsad[0]";
			
				
$strHardLinks="";				
if($rowpsad[6] >0)
{
for($i=0;$i<$rowpsad[6];$i++)
{
$strHardLinks.="flashvars_".$rowpsad[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$rowpsad[0].".atar".($i+1)."='_blank';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $rowpsad[0]; ?> = {};
var params_<?php echo $rowpsad[0]; ?> = {};
var attributes_<?php echo $rowpsad[0]; ?> = {};
flashvars_<?php echo $rowpsad[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $rowpsad[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $rowpsad[0]; ?>.clickTARGET = "_blank";
<?php echo $strHardLinks; ?>

params_<?php echo $rowpsad[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2];?>", "myFlashDivPub_<?php echo $rowpsad[0].$tmrp; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $rowpsad[0]; ?>,params_<?php echo $rowpsad[0]; ?>,attributes_<?php echo $rowpsad[0]; ?>);
</script>
		

	<?php	  
	$catalogadblock.='<div id="myFlashDivPub_'.$rowpsad[0].$tmrp.'"></div>';
	$catalogadblock.='</div></td>';
	
	
	                    if($dir_str == "")
						$catalogadblock.='<td align="left" valign="top">';
						else
						$catalogadblock.='<td valign="top">';
	
	
			
			
			
			
			
			
			}				 
					
					
					
							 
							 
							 
							 
				$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'" target="_blank">'.$rowpsad[4].'</a></div>';
				 $catalogadblock.='<div  class="inout-desc">'.$rowpsad[3].'</div></td></tr></table>';
							 
				 }
				  else
				   { 
				   
				    $catalogadblock.='<table width="100%" cellpadding="0" height="100%" cellspacing="5" border=0  style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden;';
								
								if($adunitrow['catalog_line_seperator']==1) 
								{
									if( $i < $psdbadcount-1)	
										$catalogadblock.='border-right:solid 1px '. $r1[2];				
								}
								if($rowpsad[7]==$rtl_lang_id)
				$catalogadblock.=';direction:rtl';
							   $catalogadblock.='">';
							 $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
							 
							 
				
				
			if($rowpsad[5] != "swf")
			{				 
			 $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2].'" border="0"></a> </div></td></tr>	<tr> <td valign="top">';
			 
		}
			else
			{
			
			
			
			$tmrp=time();
			
			
$alinkdemo=$original_url_string."ppc-ps-ad-click.php?id=$rowpsad[0]";
			
				
$strHardLinks="";				
if($rowpsad[6] >0)
{
for($i=0;$i<$rowpsad[6];$i++)
{
$strHardLinks.="flashvars_".$rowpsad[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$rowpsad[0].".atar".($i+1)."='_blank';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $rowpsad[0]; ?> = {};
var params_<?php echo $rowpsad[0]; ?> = {};
var attributes_<?php echo $rowpsad[0]; ?> = {};
flashvars_<?php echo $rowpsad[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $rowpsad[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $rowpsad[0]; ?>.clickTARGET = "_blank";
<?php echo $strHardLinks; ?>

params_<?php echo $rowpsad[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2];?>", "myFlashDivPub_<?php echo $rowpsad[0].$tmrp; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $rowpsad[0]; ?>,params_<?php echo $rowpsad[0]; ?>,attributes_<?php echo $rowpsad[0]; ?>);
</script>
		

	<?php	  
	$catalogadblock.='<div id="myFlashDivPub_'.$rowpsad[0].$tmrp.'"></div>';
	$catalogadblock.='</div></td></tr>	<tr> <td valign="top">';
	
	
			
			
			
			
			
			
			}				 	 
			 
			 
			 
			 
			 
							
							 		$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'" target="_blank">'.$rowpsad[4].'</a></div>';
							 $catalogadblock.='<div class="inout-desc">'.$rowpsad[3].'</div></td></tr></table>';
			
				 } 
				  $catalogadblock.='</td>';	
				
					$currentadcount+=1;
					$i++;
					}
				}
					
		  $catalogadblock.='</tr>';	
		  
			//	if($adunitrow['credit_text_positioning']==0 && $adunitrow['padtext'] !=0)
			//	$catalogadblock.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\"><a target=\"_blank\" href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";


	
if($adunitrow['credit_text_positioning']==0 && $adunitrow['padtext'] !=0)
$catalogadblock.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\">".$paginglinks."<div id=\"cred\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></div></td></tr>";
					
					
				
		
		
		

			}
		else //vertical
			{
				//echo "here1";
				//if($adunitrow['credit_text_positioning']==1 && $adunitrow['padtext'] !=0)
				//$catalogadblock.= "<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";


   					
						
			if($adunitrow['credit_text_positioning']==1 && $adunitrow['padtext'] !=0)
			$catalogadblock.= "<tr ><td class=\"inout-credit\">".$paginglinks."<div id=\"cred\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></div></td></tr>";
					
					
				
						
						


				$catalogadblock.='<tr ><td>';
								
				$catalogadblock.= "<table cellpadding=\"0\" cellspacing=\"0\" class=\"innertable\"  height=100% width=".$adunitrow['width'].">";

					$cat_ad_cnt=mysql_num_rows($adresult);
				$i=0;
				while($currentadcount< $catalog_adscount && $adrow=mysql_fetch_row($adresult))
				{
				
						$currentadcount+=1;
						$currentcatalogadcount+=1;
						
					updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$pid,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid);
						
						$ad_display_url=$adrow[6];
						if($ad_display_url=="")
						$ad_display_url=$adrow[1];

						if($currentcatalogadcount==1)
							$catalogadblock.='<tr height="0" style="display: none;"><td>';
						else
						$catalogadblock.='<tr height="{HEIGHT}"><td>';
						 if($adunitrow['catalog_alignment']==1) 
						 {
						 	
if($currentcatalogadcount==1)
{
	$bs=md5(rand(0,10));
    $catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0" height="100%" cellspacing="5" border=0  style="display: none;"  >';
	$catalogadblock.='<tr><td align="center"  style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
    $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.'/images/cbl.png'.'" border="0"></a> </div></td> <td align="left" valign="top">';
	$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" target="_blank">'.$adrow[6].'</a></div>';
	$catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
	$catalogadblock.='</td></tr><tr height="{HEIGHT}"><td>';
	
}

$bs=md5($adrow[0].$adrow[4].$adunitid);		
$dir_str='';
					if($rtl_lang_id==$adrow[11])
						$dir_str='direction:rtl';
		
								$catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0" height="100%" cellspacing="5" border=0 style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden;'.$dir_str;
								if($adunitrow['catalog_line_seperator']==1 ) 
								{
						  if( $cat_ad_cnt==$adunitrow['no_of_catalog_ads'] )
						 	{
							if( $i < $cat_ad_cnt-1 )
						  	  $catalogadblock.='border-bottom:solid 1px '. $r1[2];
							}
						  elseif( $psdbadcount > 0)	
						  	$catalogadblock.='border-bottom:solid 1px '. $r1[2];
						  elseif( $i < $cat_ad_cnt-1 )	
						  	$catalogadblock.='border-bottom:solid 1px '. $r1[2];
								}
								$catalogadblock.='">';
								$catalogadblock.='<tr><td align="center"  style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
								

if($adrow[9] != "swf")
{									
								
							 $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td>';
							 
							 
						if($dir_str == "")
						$catalogadblock.='<td align="left" valign="top">';
						else
						$catalogadblock.='<td valign="top">';
							 
	}
else
{
		  
$tmr=time();		  

$alinkdemo=$original_url_string."ppc-publisher-ad-click.php?Flsids=$adrow[0]&kid=$adrow[4]&pid=$pid&bid=$adunitid&vid=".$vid_row[0]."&vip=".$enc_ip."&direct_status=".$send_direct_status.'&bs='.$bs;
$alinkdemo=str_replace('&','__',$alinkdemo);
				
				
$strHardLinks="";				
if($adrow[10] >0)
{
for($i=0;$i<$adrow[10];$i++)
{
$strHardLinks.="flashvars_".$adrow[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$adrow[0].".atar".($i+1)."='_blank';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $adrow[0]; ?> = {};
var params_<?php echo $adrow[0]; ?> = {};
var attributes_<?php echo $adrow[0]; ?> = {};
flashvars_<?php echo $adrow[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTARGET = "_blank";
<?php echo $strHardLinks; ?>

params_<?php echo $adrow[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2];?>", "myFlashDiv_<?php echo $adrow[0].$tmr; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $adrow[0]; ?>,params_<?php echo $adrow[0]; ?>,attributes_<?php echo $adrow[0]; ?>);
</script>
		

	<?php	  
	$catalogadblock.='<div id="myFlashDiv_'.$adrow[0].$tmr.'"></div>';
	$catalogadblock.='</div></td>';



                        if($dir_str == "")
						$catalogadblock.='<td align="left" valign="top">';
						else
						$catalogadblock.='<td valign="top">';


}													  
							  
							  
							  							 
							 
							 
							 
						
						 $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" target="_blank">'.$adrow[6].'</a></div>';
							 $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
						 }
					  else
					   {
					   	
if($currentcatalogadcount==1)
{
	$bs=md5(rand(0,10));
	$catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0"  height="100%"  cellspacing="5" border=0 style="display: none;"  >';
    $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
    $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.'/images/cbl.png'.'" border="0"></a> </div></td> <td align="left" valign="top">';
	$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" target="_blank">'.$adrow[6].'</a></div>';
	$catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
	$catalogadblock.='</td></tr><tr height="{HEIGHT}"><td>';
}

$bs=md5($adrow[0].$adrow[4].$adunitid);	
$dir_str='';
					if($rtl_lang_id==$adrow[11])
						$dir_str='direction:rtl';				
					   	
					   	
									 $catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0"  height="100%"  cellspacing="5" border=0 style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden;'.$dir_str;
												
									if($adunitrow['catalog_line_seperator']==1 ) 
									{
						  if( $cat_ad_cnt==$adunitrow['no_of_catalog_ads'] )
							{
								if( $i < $cat_ad_cnt-1 )
						  	 	 $catalogadblock.='border-bottom:solid 1px '. $r1[2];
							}	
						  elseif( $psdbadcount > 0)	
						  	$catalogadblock.='border-bottom:solid 1px '. $r1[2];
						  elseif( $i < $cat_ad_cnt-1 )	
						  	$catalogadblock.='border-bottom:solid 1px '. $r1[2];
									}
									   $catalogadblock.='">';
									 $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
									 
									 
									 


if($adrow[9] != "swf")
{										 
									 
					  $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td> </tr><tr><td valign="top">';
					  
}
else
{


$tmr=time();		  


$alinkdemo=$original_url_string."ppc-publisher-ad-click.php?Flsids=$adrow[0]&kid=$adrow[4]&pid=$pid&bid=$adunitid&vid=".$vid_row[0]."&vip=".$enc_ip."&direct_status=".$send_direct_status.'&bs='.$bs;
$alinkdemo=str_replace('&','__',$alinkdemo);
				
				
$strHardLinks="";				
if($adrow[10] >0)
{
for($i=0;$i<$adrow[10];$i++)
{
$strHardLinks.="flashvars_".$adrow[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$adrow[0].".atar".($i+1)."='_blank';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $adrow[0]; ?> = {};
var params_<?php echo $adrow[0]; ?> = {};
var attributes_<?php echo $adrow[0]; ?> = {};
flashvars_<?php echo $adrow[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTARGET = "_blank";
<?php echo $strHardLinks; ?>

params_<?php echo $adrow[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2];?>", "myFlashDiv_<?php echo $adrow[0].$tmr; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $adrow[0]; ?>,params_<?php echo $adrow[0]; ?>,attributes_<?php echo $adrow[0]; ?>);
</script>
		

	<?php	  
	$catalogadblock.='<div id="myFlashDiv_'.$adrow[0].$tmr.'"></div>';
	$catalogadblock.='</div></td> </tr><tr><td valign="top">';




}													  
									  
					  
					  
					  
					  
					  
							
					 $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" target="_blank">'.$adrow[6].'</a></div>';
									$catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
						}	
						$catalogadblock.='</td></tr>';
				}
				
				if($currentadcount<$catalog_adscount )
				{
					$publicresults=mysql_query("select a.id, link, title, summary, displayurl,contenttype,hardcodelinks,a.adlang from ppc_public_service_ads a where  a.bannersize='".$adunitrow['catalog_size']."' and adtype=2 AND status=1  AND wapstatus=0 $ps_language order by lastacesstime ASC  LIMIT 0,".($catalog_adscount-$currentadcount));
					if($ini_error_status!=0)
					{
						echo mysql_error();
					}
					$i=0;// echo  $psdbadcount;
					while($rowpsad=mysql_fetch_row($publicresults))
					{
					
				$dir_str='';
				if($rtl_lang_id==$rowpsad[7])
				$dir_str='direction:rtl;';
					
					
					
						mysql_query("update ppc_public_service_ads set lastacesstime='".time()."' where id='$rowpsad[0]'");

						if($ini_error_status!=0)
						{
							echo mysql_error();
						}
						$disurl=$rowpsad[4];
						if($disurl=="")
						$disurl=$rowpsad[1];
						
						$catalogadblock.='<tr height="{HEIGHT}"><td> ';	
						 if($adunitrow['catalog_alignment']==1)
						  { 
								$catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0" height="100%" cellspacing="5" border=0 style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden;';
								if($adunitrow['catalog_line_seperator']==1 ) 
								{
										if( $i < $psdbadcount-1)	
											$catalogadblock.='border-bottom:solid 1px '. $r1[2];				
				
								}
								if($rowpsad[7]==$rtl_lang_id)
				$catalogadblock.=';direction:rtl';
								$catalogadblock.='">';
								$catalogadblock.='<tr><td align="center" style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
								
								
								
								
								
								
				if($rowpsad[5] != "swf")
			{					
								
		 $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2].'" border="0"></a> </div></td>';
		 
		 
		                if($dir_str == "")
						$catalogadblock.='<td align="left" valign="top">';
						else
						$catalogadblock.='<td valign="top">';
		 
											 
						}
			else
			{
			
			
			$tmrp=time();
			
			
			
$alinkdemo=$original_url_string."ppc-ps-ad-click.php?id=$rowpsad[0]";
			
				
$strHardLinks="";				
if($rowpsad[6] >0)
{
for($i=0;$i<$rowpsad[6];$i++)
{
$strHardLinks.="flashvars_".$rowpsad[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$rowpsad[0].".atar".($i+1)."='_blank';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $rowpsad[0]; ?> = {};
var params_<?php echo $rowpsad[0]; ?> = {};
var attributes_<?php echo $rowpsad[0]; ?> = {};
flashvars_<?php echo $rowpsad[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $rowpsad[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $rowpsad[0]; ?>.clickTARGET = "_blank";
<?php echo $strHardLinks; ?>

params_<?php echo $rowpsad[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2];?>", "myFlashDivPub_<?php echo $rowpsad[0].$tmrp; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $rowpsad[0]; ?>,params_<?php echo $rowpsad[0]; ?>,attributes_<?php echo $rowpsad[0]; ?>);
</script>
		

	<?php	  
	$catalogadblock.='<div id="myFlashDivPub_'.$rowpsad[0].$tmrp.'"></div>';
	$catalogadblock.='</div></td>';
	
	
	                    if($dir_str == "")
						$catalogadblock.='<td align="left" valign="top">';
						else
						$catalogadblock.='<td valign="top">';
	
	
			
			
			
			
			
			
			}				 	 
			 					 
											 
											 
											 
											 
											 
											 
								  $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'" target="_blank">'.$rowpsad[4].'</a></div>';
								 $catalogadblock.='<div class="inout-desc">'.$rowpsad[3].'</div></td></tr></table>';
						 } 
						 else
						  {
				  
									$catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0" height="100%" cellspacing="5" border=0 style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden;';
												
									if($adunitrow['catalog_line_seperator']==1 ) 
									{
												if( $i < $psdbadcount-1)	
												$catalogadblock.='border-bottom:solid 1px '. $r1[2];				
									}
									if($rowpsad[7]==$rtl_lang_id)
				$catalogadblock.=';direction:rtl';
								   $catalogadblock.='">';
								 $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
								 
								 
								 
					
								
				if($rowpsad[5] != "swf")
			{				
							 
		  $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2].'" border="0"></a> </div></td></tr>	<tr> <td valign="top">';
								  
			}
			else
			{
			
			
	$tmrp=time();		
			
			
			
$alinkdemo=$original_url_string."ppc-ps-ad-click.php?id=$rowpsad[0]";
			
				
$strHardLinks="";				
if($rowpsad[6] >0)
{
for($i=0;$i<$rowpsad[6];$i++)
{
$strHardLinks.="flashvars_".$rowpsad[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$rowpsad[0].".atar".($i+1)."='_blank';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $rowpsad[0]; ?> = {};
var params_<?php echo $rowpsad[0]; ?> = {};
var attributes_<?php echo $rowpsad[0]; ?> = {};
flashvars_<?php echo $rowpsad[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $rowpsad[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $rowpsad[0]; ?>.clickTARGET = "_blank";
<?php echo $strHardLinks; ?>

params_<?php echo $rowpsad[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2];?>", "myFlashDivPub_<?php echo $rowpsad[0].$tmrp; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $rowpsad[0]; ?>,params_<?php echo $rowpsad[0]; ?>,attributes_<?php echo $rowpsad[0]; ?>);
</script>
		

	<?php	  
	$catalogadblock.='<div id="myFlashDivPub_'.$rowpsad[0].$tmrp.'"></div>';
	$catalogadblock.='</div></td></tr>	<tr> <td valign="top">';
	
	
			
			
			
			
			
			
			}				 	 
			 					 					  
								  
								  
								  
								  
								  
								  
								
								$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'" target="_blank">'.$rowpsad[4].'</a></div>';
								 $catalogadblock.='<div class="inout-desc">'.$rowpsad[3].'</div></td></tr></table>';
			
						 } 
					 	 $catalogadblock.='</td></tr>';	
						$currentadcount=$currentadcount+1;
					}
				}				
				$catalogadblock.= "</table>";
				$catalogadblock.= "</td></tr>";

				//if($adunitrow['credit_text_positioning']==0 && $adunitrow['padtext'] !=0)
				//$catalogadblock.= "<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";


				
			if($adunitrow['credit_text_positioning']==0 && $adunitrow['padtext'] !=0)
			$catalogadblock.= "<tr ><td class=\"inout-credit\">".$paginglinks."<div id=\"cred\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></div></td></tr>";
					
					
				
			
					

			}
		//	echo $currentcatalogadcount;
			//$test=$currentcatalogadcount;
			if($currentadcount>=1)
		{
			$horizontalwidth=100/$currentadcount;
			$verticalheight= 100/$currentadcount;
//	echo $adunitrow[5];
			if($adunitrow['orientaion']==2)
				{
					$catalogadblock=str_replace("{COLSPAN}",$currentadcount,$catalogadblock);
					$catalogadblock=str_replace("{WIDTH}",$horizontalwidth."%",$catalogadblock);
				}
			else
				{
					$catalogadblock=str_replace("{HEIGHT}",$verticalheight."%",$catalogadblock);
				}
		}	
		
			echo $catalogadblock;	
			
			?>
		  </table>
		  <?php
		if($bordertype==0)
		{?><b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b><?php
		}
//echo $currentcatalogadcount;
}

else
{

$singleflag=0;
	if($adunitrow['ad_type']==1 && $adunitrow['text_ad_type'] ==2)
				{
				$singleflag=1;
				$singlewidth=$adunitrow['width']/($adunitrow['no_of_text_ads']+1);
				
				}



        $specials_ids=0;
		$blockheight= $adunitrow['height'];
		if($bordertype==0)
		{
			?><b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b><?php
			$blockheight= $adunitrow['height']-10;
		}
		$marqueeheight=$blockheight;
		?><table height="<?php if($adunitrow['padtext']==0  || $special_height_flag ==0)  echo $blockheight; else if($adunitrow['padtext']!=0 && $special_height_flag ==1) echo $blockheight+15; ?>"  width="<?php  echo $adunitrow['width']; ?>" cellpadding="<?php // if($adunitrow['scroll_ad']==0) echo "5"; else echo "0";  ?>0" cellspacing="0" class="inout-table"><?php 
		$textadblock="";
		$textadblockstart="";
		$textadblockend="";
		$currentadcount=0;		
		if($adunitrow['orientaion']==2)//horizontal
			{
			//if($adunitrow['credit_text_positioning']==1 && $credittext!="")
			//$textadblockstart.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";

				
				
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")
				$textadblockstart.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\">".$paginglinks."<div id=\"cred\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></div></td></tr>";
					
					

				if($adunitrow['scroll_ad']==1)
				{
					$textadblockstart.="<tr><td  colspan=\"{COLSPAN}\">";
					$textadblock.= "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\"  height=$marqueeheight width=".$adunitrow['width']." class=\"marqueetable\">";
				}
				
				$textadblock.="<tr>";
				
				if($singleflag==1 && $adunitrow['credit_text_positioning']==2 && $credittext!="")
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$original_url_string.'index.php?r='.$pid.'">'.$credittext.'</a></td>';
				
				
				while($currentadcount < $adscount && $adrow=mysql_fetch_row($adresult))
				{
					$textadcount+=1;
					$currentadcount+=1;
					updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$pid,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid);
					$ad_display_url=$adrow[6];
					if($ad_display_url=="")
						$ad_display_url=$adrow[1];
						
						if($adunitrow['text_ad_type']!=1)
						{
						$ad_display_url=""; 
						}
					
					if($adunitrow['text_ad_type']!=2)
						{
						 $d_description=$adrow[3]; 
					 	}
					else
						{
						$d_description="";
						}
					
				    if($currentadcount==1)
					{
					$bs=md5(rand(0,10));
					$textadblock.='<td style="display: none;" width="0" valign="top">';
					$textadblock.='<div class="inout-title"><a target="_blank"   href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$adrow[2].'</a></div>';
					$textadblock.='<div class="inout-desc">'.$d_description.'</div>';					
					$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$ad_display_url.'</a></div>';
					$textadblock.='</td>';
					

					 
					}
					
					$dir_str='';
					if($rtl_lang_id==$adrow[9])
						$dir_str='style="direction:rtl"';
					
					 $bs=md5($adrow[0].$adrow[4].$adunitid);
					 $textadblock.='<td  width="{WIDTH}" valign="top" '.$dir_str.'>';
					 $textadblock.='<div class="inout-title"><a target="_blank"   href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$adrow[2].'</a></div>';
					 $textadblock.='<div class="inout-desc">'.$d_description.'</div>';					
					 $textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$ad_display_url.'</a></div>';
					 $textadblock.='</td>';
						
					
					 $specials_ids=$adrow[0];
				}

$expire=time()+60*60;
setcookie('view_kwd_id_coo',$specials_ids,$expire);


				if($currentadcount<$adscount )
				{
					$publicresults=mysql_query("select id, link, title, summary, displayurl,adlang from ppc_public_service_ads where adtype=0 AND status=1  AND wapstatus=0 $ps_language order by lastacesstime ASC  LIMIT 0,".($adscount-$currentadcount));
					

if($ini_error_status!=0)
{
	echo mysql_error();
}
					while($rowpsad=mysql_fetch_row($publicresults))
					{
						mysql_query("update ppc_public_service_ads set lastacesstime='".time()."' where id='$rowpsad[0]'");
						

if($ini_error_status!=0)
{
	echo mysql_error();
}
						$disurl=$rowpsad[4];
						if($disurl=="")
						$disurl=$rowpsad[1];
						
						if($adunitrow['text_ad_type']!=1)
						{
						$disurl=""; 
						}
					
					if($adunitrow['text_ad_type']!=2)
						{
						 $d_description=$rowpsad[3]; 
					 	}
					else
						{
						$d_description="";
						}
$dir_str='';
					if($rtl_lang_id==$rowpsad[5])
						$dir_str='style="direction:rtl"';
						$textadblock.='<td width="{WIDTH}" valign="top"'.$dir_str.'><div class="inout-title"><a target="_blank"   href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'">'.$rowpsad[2].'</a></div>';
							$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
							$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'">'.$disurl.'</a></div>';
						$textadblock.='</td>';
						$currentadcount+=1;
					}
				}
				
				if($singleflag==1 && $adunitrow['credit_text_positioning']==3 && $credittext!="")
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$original_url_string.'index.php?r='.$pid.'">'.$credittext.'</a></td>';
				
				
				
				$textadblock.="</tr>";
				if($adunitrow['scroll_ad']==1)
				{
					$textadblock.= "</table>";
					$textadblockend="</td></tr>";
				}
			//if($adunitrow['credit_text_positioning']==0 && $credittext!="")
			//$textadblockend.="<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";

	
					if($adunitrow['credit_text_positioning']==0 && $credittext!="")
					$textadblockend.="<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\">".$paginglinks."<div id=\"cred\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></div></td></tr>";
					
					
					

			}
		else //vertical
			{
				
				//if($adunitrow['credit_text_positioning']==1 && $credittext!="")
				//$textadblockstart.="<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";


    					
		if($adunitrow['credit_text_positioning']==1 && $credittext!="")
		$textadblockstart.= "<tr ><td  class=\"inout-credit\">".$paginglinks."<div id=\"cred\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></div></td></tr>";
					
					
				
							
					



				if($adunitrow['scroll_ad']==1)
				{
					$textadblockstart.="<tr><td>";
				$textadblock.= "<table cellpadding=\"5\" cellspacing=\"0\"  height=$marqueeheight width=".$adunitrow['width']." class=\"marqueetable\">";
				}
				while($currentadcount < $adscount && $adrow=mysql_fetch_row($adresult))
				{
					$textadcount=$textadcount+1;
					$currentadcount=$currentadcount+1;
					updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$pid,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid);
					$ad_display_url=$adrow[6];
					if($ad_display_url=="")
						$ad_display_url=$adrow[1];
						if($adunitrow['text_ad_type']!=1)
						{
						$ad_display_url=""; 
						}
					
					if($adunitrow['text_ad_type']!=2)
						{
						 $d_description=$adrow[3]; 
					 	}
					else
						{
						$d_description="";
						}
					if($currentadcount==1)
					{
					 $bs=md5(rand(0,10));
					$textadblock.='<tr height="0" style="display: none;" >';
					$textadblock.='<td><div class="inout-title"><a target="_blank"   href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$adrow[2].'</a></div>';
					$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
					$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$ad_display_url.'</a></div>';
					$textadblock.= '</td></tr>'; 	
				
					}
					$dir_str='';
					if($rtl_lang_id==$adrow[9])
						$dir_str='style="direction:rtl"';
					$bs=md5($adrow[0].$adrow[4].$adunitid);
					$textadblock.='<tr height="{HEIGHT}"'.$dir_str.'>';
					$textadblock.='<td><div class="inout-title"><a target="_blank"   href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$adrow[2].'</a></div>';
					$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
					$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.'ppc-publisher-ad-click.php?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$ad_display_url.'</a></div>';
					$textadblock.= '</td></tr>'; 
					
					$specials_ids=$adrow[0];
				}

$expire=time()+60*60;
setcookie('view_kwd_id_coo',$specials_ids,$expire);

				if($currentadcount<$adscount )
				{
					$publicresults=mysql_query("select id, link, title, summary, displayurl,adlang from ppc_public_service_ads where adtype=0 AND status=1  AND wapstatus=0 $ps_language order by lastacesstime ASC  LIMIT 0,".($adscount-$currentadcount));
					

if($ini_error_status!=0)
{
	echo mysql_error();
}
					while($rowpsad=mysql_fetch_row($publicresults))
					{
						mysql_query("update ppc_public_service_ads set lastacesstime='".time()."' where id='$rowpsad[0]'");

if($ini_error_status!=0)
{
	echo mysql_error();
}
						$disurl=$rowpsad[4];
						if($disurl=="")
						$disurl=$rowpsad[1];
						
						if($adunitrow['text_ad_type']!=1)
						{
						$disurl=""; 
						}
					
					if($adunitrow['text_ad_type']!=2)
						{
						 $d_description=$rowpsad[3]; 
					 	}
					else
						{
						$d_description="";
						}
					$dir_str='';
					if($rtl_lang_id==$rowpsad[5])
						$dir_str='style="direction:rtl"';	
						$textadblock.='<tr height="{HEIGHT}"'.$dir_str.'>';
						$textadblock.='<td ><div class="inout-title"><a target="_blank"   href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'">'.$rowpsad[2].'</a></div>';
							$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
							$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'">'.$disurl.'</a></div>';
						$textadblock.= '</td></tr>'; 
						$currentadcount=$currentadcount+1;
					}
				}
				if($adunitrow['scroll_ad']==1)
				{
					$textadblock.= "</table>";
					$textadblockend.= "</td></tr>";
				}

				//if($adunitrow['credit_text_positioning']==0 && $credittext!="")
				//$textadblockend.="<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></td></tr>";
   
	if($adunitrow['credit_text_positioning']==0 && $credittext!="")
	$textadblockend.="<tr ><td class=\"inout-credit\">".$paginglinks."<div id=\"cred\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php?r=$pid\">$credittext</a></div></td></tr>";
				

         



			}
			
		if($currentadcount>=1)
		{
			$horizontalwidth=100/($currentadcount+1);
			$verticalheight=100/$currentadcount;
	
			if($adunitrow['orientaion']==2)
				{
					$textadblockstart=str_replace("{COLSPAN}",$currentadcount,$textadblockstart);
					$textadblockend=str_replace("{COLSPAN}",$currentadcount,$textadblockend);
					$textadblock=str_replace("{WIDTH}",$horizontalwidth."%",$textadblock);
				}
			else
				{
					$textadblock=str_replace("{HEIGHT}",$verticalheight."%",$textadblock);
				}
		}	
if($adunitrow['scroll_ad']==1)
{
	echo $textadblockstart;
?><script language="JavaScript1.2">

/*
Cross browser Marquee script- ? Dynamic Drive (www.dynamicdrive.com)
For full source code, 100's more DHTML scripts, and Terms Of Use, visit http://www.dynamicdrive.com
Modified by jscheuer1 for continuous content. Credit MUST stay intact
*/

//Specify the marquee's width (in pixels)
var marqueewidth="<?php echo $adunitrow['width']; ?>px"
//Specify the marquee's height
var marqueeheight="<?php echo $marqueeheight-2 ; ?>px"
//Specify the marquee's marquee speed (larger is faster 1-10)
var marqueespeed=1
//Specify initial pause before scrolling in milliseconds
var initPause=0
//Specify start with Full(1)or Empty(0) Marquee
var full=0
//configure background color:
var marqueebgcolor="<?php if($adunittype==2) echo "#FFFFFF";  else echo  $adunitrow['bg_color']; ?>"
//Pause marquee onMousever (0=no. 1=yes)?
var pauseit=1

//Specify the marquee's content (don't delete <nobr> tag)
//Keep all content on ONE line, and backslash any single quotations (ie: that\'s great):

var marqueecontent='<nobr><?php echo $textadblock;	?></nobr>'
</script><?php
	if($adunitrow['orientaion']==2)//horizontal
	{
	
?><script language="JavaScript1.2">	
////NO NEED TO EDIT BELOW THIS LINE////////////
var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var iedom=document.all||document.getElementById
if (iedom)
document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px">'+marqueecontent+'</span>')
var actualwidth=''
var cross_marquee, cross_marquee2, ns_marquee
function populate(){
if (iedom){
var initFill=(full==1)? '8px' : parseInt(marqueewidth)+8+"px"
actualwidth=document.all? temp.offsetWidth : document.getElementById("temp").offsetWidth
cross_marquee=document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee
cross_marquee.style.left=initFill
cross_marquee2=document.getElementById? document.getElementById("iemarquee2") : document.all.iemarquee2
cross_marquee2.innerHTML=cross_marquee.innerHTML=marqueecontent
cross_marquee2.style.left=(parseInt(cross_marquee.style.left)+actualwidth+8)+"px" //indicates following #1
}
else if (document.layers){
ns_marquee=document.ns_marquee.document.ns_marquee2
ns_marquee.left=parseInt(marqueewidth)+8
ns_marquee.document.write(marqueecontent)
ns_marquee.document.close()
actualwidth=ns_marquee.document.width
}
setTimeout('lefttime=setInterval("scrollmarquee()",30)',initPause)
}
window.onload=populate

function scrollmarquee(){
if (iedom){
if (parseInt(cross_marquee.style.left)<(actualwidth*(-1)+8))
cross_marquee.style.left=(parseInt(cross_marquee2.style.left)+actualwidth+8)+"px"
if (parseInt(cross_marquee2.style.left)<(actualwidth*(-1)+8))
cross_marquee2.style.left=(parseInt(cross_marquee.style.left)+actualwidth+8)+"px"
cross_marquee2.style.left=parseInt(cross_marquee2.style.left)-copyspeed+"px"
cross_marquee.style.left=parseInt(cross_marquee.style.left)-copyspeed+"px"
}
else if (document.layers){
if (ns_marquee.left>(actualwidth*(-1)+8))
ns_marquee.left-=copyspeed
else
ns_marquee.left=parseInt(marqueewidth)+8
}
}

</script><?php
			}
			else
			{
?><script language="JavaScript1.2">

////NO NEED TO EDIT BELOW THIS LINE////////////
var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var iedom=document.all||document.getElementById
if (iedom)
document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px">'+marqueecontent+'</span>')
var actualheight=''
var cross_marquee, cross_marquee2, ns_marquee
function populate(){
if (iedom){
var initFill=(full==1)? '8px' : parseInt(marqueeheight)+8+"px"
actualheight=document.all? temp.offsetHeight : document.getElementById("temp").offsetHeight
cross_marquee=document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee
cross_marquee.style.top=initFill
cross_marquee2=document.getElementById? document.getElementById("iemarquee2") : document.all.iemarquee2
cross_marquee2.innerHTML=cross_marquee.innerHTML=marqueecontent
cross_marquee2.style.top=(parseInt(cross_marquee.style.top)+actualheight+8)+"px" //indicates following #1
}
else if (document.layers){
ns_marquee=document.ns_marquee.document.ns_marquee2
ns_marquee.top=parseInt(marqueeheight)+8
ns_marquee.document.write(marqueecontent)
ns_marquee.document.close()
actualheight=ns_marquee.document.height
}
setTimeout('lefttime=setInterval("scrollmarquee()",30)',initPause)
}
window.onload=populate

function scrollmarquee(){
if (iedom){
if (parseInt(cross_marquee.style.top)<(actualheight*(-1)+8))
cross_marquee.style.top=(parseInt(cross_marquee2.style.top)+actualheight+8)+"px"
if (parseInt(cross_marquee2.style.top)<(actualheight*(-1)+8))
cross_marquee2.style.top=(parseInt(cross_marquee.style.top)+actualheight+8)+"px"
cross_marquee2.style.top=parseInt(cross_marquee2.style.top)-copyspeed+"px"
cross_marquee.style.top=parseInt(cross_marquee.style.top)-copyspeed+"px"
}
else if (document.layers){
if (ns_marquee.top>(actualheight*(-1)+8))
ns_marquee.top-=copyspeed
else
ns_marquee.top=parseInt(marqueeheight)+8
}
}

</script><?php			
			}?><script language="JavaScript1.2">
			
			if (iedom||document.layers){
with (document){
document.write('<table border="0" cellspacing="0" cellpadding="0" ><td>')
if (iedom){
write('<div style="position:relative;width:'+marqueewidth+';height:'+marqueeheight+';overflow:hidden">')
write('<div style="position:absolute;width:'+marqueewidth+';height:'+marqueeheight+';background-color:'+marqueebgcolor+'" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">')
write('<div id="iemarquee" style="position:absolute;left:0px;top:0px;display:inline;"></div>')
write('<div id="iemarquee2" style="position:absolute;left:0px;top:0px;display:inline;"></div>')
write('</div></div>')
}
else if (document.layers){
write('<ilayer width='+marqueewidth+' height='+marqueeheight+' name="ns_marquee" bgColor='+marqueebgcolor+'>')
write('<layer name="ns_marquee2" left=0 top=0 onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed"></layer>')
write('</ilayer>')
}
document.write('</td></table>')
}
}</script><?php
			echo $textadblockend; 
		}
		else
			echo $textadblockstart.$textadblock.$textadblockend;	
		?></table><?php  
		if($bordertype==0)
		{
			?><b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b><?php
		}
	
}
?></div><div id='set_2' style='display:none;width:<?php  echo $adunitrow['width']; ?>' ></div><div id='set_3' style='display:none;width:<?php  echo $adunitrow['width']; ?>' ></div><div id='loading' style='display:none;width: 100%;height: 100%;text-align: center;vertical-align: middle;' ><img src="<?php echo $original_url_string; ?>images/loaddata.gif"></div></body></html><?php
}
?>

<script language="javascript" type="text/javascript">
function LoadCreditText(idcredit)
{
document.getElementById("idiv_"+idcredit).style.display="none";
document.getElementById("crd_"+idcredit).style.display="";

}
function DisableCreditText(idcredits)
{
document.getElementById("idiv_"+idcredits).style.display="";
document.getElementById("crd_"+idcredits).style.display="none";

}
</script>
<?php


$expire=time()+60*60;
setcookie("textadcount",$textadcount,$expire);
setcookie("imageadcount",$imageadcount,$expire);				

 if($adunittype==4)
 {
				$k=1;
				$str_cat="";
				$val=explode(",",$catalogadcount);
				for($j=0;$j<count($val);$j++,$k++)
				{
						if($j%2==0)
						{
							if($val[$j]==$adunitrow['catalog_size'])
							{
						
								$k++;
							if($k==count($val))
							$str_cat.=$val[$j].",".($val[$j+1]+$currentcatalogadcount);
							else
							$str_cat.=$val[$j].",".($val[$j+1]+$currentcatalogadcount).",";
							$j++;
							
							}	
								else
							{
								if($k==count($val))
								$str_cat.=$val[$j];
								else
								$str_cat.=$val[$j].",";
							}						
							
						}
						
						else
						{
							if($k==count($val))
							$str_cat.=$val[$j];
							else
							$str_cat.=$val[$j].",";
						}		
						
						
				}
		
$catalogadcount=$str_cat;
//echo $catalogadcount."ddd";
}
setcookie("catalogadcount",$catalogadcount,$expire);

function updateAgeingAndImpression ($mysql,$adunittype,$row,$ad_ageing_factor,$pid,$adunitid,$vid,$public_ip,$fraud_time_interval,$direct_status,$serverid)
{   
    global $mysql_server_type;
	global $impression_id_str;
	global $impression_id_str_pub;
	
	
	$ini_error_status=ini_get('error_reporting');
	$lastAccTime=0;
	$uid=0;
	if($adunittype==1||$adunittype==4)
	{
		$uid=$row[7];
		$lastAccTime=$row[8];
	}
	else
	{
		$uid=$row[6];
		$lastAccTime=$row[7];
	}
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
	
		//mysql_query("insert into ppc_daily_impressions value('0','$uid','$row[0]','$row[4]','$public_ip','".$currTime."','$pid','$adunitid','1')");
		
		//echo "insert into advertiser_impression_hourly_master(`id`,`uid`,`aid`,`kid`,`time`,`cnt`,`server_id`) value('0','$uid','$row[0]','$row[4]','".$currTime."','1','$serverid')";
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


?>
<script type="text/javascript" language="javascript">

set_flag=1;
function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}
function stateChanged()
{//alert(xmlhttp.readyState);
if (xmlhttp.readyState==4)
{

	if(set_flag==1)	
	{
document.getElementById("set_1").innerHTML=xmlhttp.responseText;
evalScript(xmlhttp.responseText);
document.getElementById("set_1").style.display="block";
document.getElementById("set_2").style.display="none";
document.getElementById("set_3").style.display="none";
   }
   if(set_flag==2)	
	{
document.getElementById("set_2").innerHTML=xmlhttp.responseText;
evalScript(xmlhttp.responseText);
document.getElementById("set_2").style.display="block";
document.getElementById("set_1").style.display="none";
document.getElementById("set_3").style.display="none";
   }
  if(set_flag==3)	
	{
	
	//alert(xmlhttp.responseText);
	
document.getElementById("set_3").innerHTML=xmlhttp.responseText;
evalScript(xmlhttp.responseText);
document.getElementById("set_3").style.display="block";
document.getElementById("set_2").style.display="none";
document.getElementById("set_1").style.display="none";



   }
   document.getElementById("loading").style.display="none";
  
}
}

function paging(set)
{


if(document.getElementById("set_"+set).innerHTML !="")
{
//alert(set);
	if(set==1)	
		{
                  //alert(set);
	document.getElementById("set_1").style.display="block";
	document.getElementById("set_2").style.display="none";
	document.getElementById("set_3").style.display="none";

	   }

	   if(set==2)	
		{
                //alert(set);
	document.getElementById("set_2").style.display="block";
	document.getElementById("set_1").style.display="none";
	document.getElementById("set_3").style.display="none";
 
	   }
	  
	   if(set==3)	
		{
        
	document.getElementById("set_3").style.display="block";
	document.getElementById("set_2").style.display="none";
	document.getElementById("set_1").style.display="none";
         }
          	    
	   return;
}


document.getElementById("set_"+set_flag).style.display="none";
document.getElementById("loading").style.display="block";
set_flag=set;

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Browser does not support HTTP Request");
  return;
  }

var url="<?php echo $server_dir; ?>publisher-show-ads-paging.php?"+"set="+set+"&<?php echo $query_str; ?>&server_path_id=<?php echo $serverid; ?>&original_url_string=<?php echo urlencode($original_url_string);?>";








//alert(url);

xmlhttp.onreadystatechange=stateChanged;
xmlhttp.open("GET",encodeURI(url),true);
xmlhttp.send(null);




}



function evalScript(scripts)
{	
try
	{	if(scripts != '')	
		{	var script = "";
			scripts = scripts.replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, function(){
	       	                         if (scripts !== null) script += arguments[1] + '\n';
 	        	                        return '';});
			
			//alert(script);
			if(script) (window.execScript) ? window.execScript(script) : window.setTimeout(script, 0);
		}
		return false;
	}
	catch(e)
	{	alert(e)
	}
}
	

</script>
<?php
$ret = ob_get_contents();	

if($GLOBALS['cache_timeout']>0 && !$cache->is_cached() ) 
{
$cache_template='<?php
ob_start();
$impression_str="'.$impression_id_str.'";
$impression_str_pub="'.$impression_id_str_pub.'";

$pub_traffic_analysis="'.$pub_traffic_analysis.'";
$ad_cnt="'.$ad_cnt.'";
$pid="'.$pid.'";


//include_once("extended-config.inc.php");  



//$u_flag=$_GET[\'u\'];

$u_flag=$u;






if($pub_traffic_analysis==1)
{
 



$cur_page=mysql_real_escape_string($cur_page);

  
if($blockcount==1)
{

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
		
}
else
{

if($mysql_server_type==1)
	{
	$lastid=mysql_query("SELECT id from publisher_daily_visits_statistics_master  where pid=\'$pid\' and time=\'$visit_time\' and page_url=\'$cur_page\' order by id desc limit 0,1");
	if($ini_error_status!=0)
		{
			echo mysql_error();
		}
	$vid_row=mysql_fetch_row($lastid);					
	}
	else if($mysql_server_type==2)
	{
	
	$lastid=mysql_query("SELECT id from publisher_daily_visits_statistics_slave  where pid=\'$pid\' and time=\'$visit_time\' and page_url=\'$cur_page\' order by id desc limit 0,1");
	if($ini_error_status!=0)
		{
			echo mysql_error();
		}
	$vid_row=mysql_fetch_row($lastid);					
	
	
	}
	
	
	
	
	
} //finding vid end 
 
 
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
	
	
	


	//mysql_query("update ppc_daily_impressions set cnt=cnt+1 where id in ($impression_str)");
	
	
	
	
	//TO DO : update data in visit table

	ob_clean();
}
?>'.$ret.'<?php
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