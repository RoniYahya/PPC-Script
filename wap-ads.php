<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


if(isset($_GET['pid']))
$pid=$_GET['pid'];
else
$pid=0;

if($pid=="")
$pid=0;

$public_ip=safeRead($_GET['ip']);
$adunitid=intval($_GET['id']);
$keywordstr=urldecode(trim($_GET['search']));
$useragent=urldecode(trim($_GET['useragent']));
$url_datas=urldecode(trim($_GET['url']));
$keywordstr=safeRead($keywordstr);
$ref=safeRead(urldecode(trim($_GET['ref'])));
$adunit_rendered=trim($_GET['r']);



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




$adu_pub_id=$mysql->echo_one("select pid from ppc_custom_ad_block where id='$adunitid'");

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
$useragent=urlencode($useragent);
$url_datas=urlencode($url_datas);
$ref=urlencode($ref);


$url=$server_url_row[0]."wap-ads.php?id=".$adunitid."&r=".$adunit_rendered."&search=".$keywordstr."&ref=".$ref."&server_path_id=".$srv_id."&pid=".$pid."&ip=".$public_ip."&useragent=".$useragent."&url=".$url_datas."&original_url_string=".$server_dir;
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
$keywordstr=urlencode($keywordstr);
$useragent=urlencode($useragent);
$url_datas=urlencode($url_datas);
$ref=urlencode($ref);


$url=$server_url_row[0]."wap-ads.php?id=".$adunitid."&r=".$adunit_rendered."&search=".$keywordstr."&ref=".$ref."&server_path_id=".$server_url_row[2]."&pid=".$pid."&ip=".$public_ip."&useragent=".$useragent."&url=".$url_datas."&original_url_string=".$server_dir;

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





include_once("functions.inc.php");
include("geo/geoip.inc");

$z = date('H', time());



$enc_ip=md5($public_ip);



$gi = geoip_open("geo/GeoIP.dat",GEOIP_STANDARD);
$record = geoip_country_code_by_addr($gi, $public_ip);

includeClass("Cache");
$cache = new Cache($GLOBALS['cache_timeout'],$GLOBALS['cache_folder']);
$uri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],"&ht"));

$uri =$uri.$record;

$u="f";
if($adunit_rendered=="f")
$u="t";

$cache->cache_file 		= md5($uri) . ".php";
$red_url = $cache->cache_folder . "/" . $cache->cache_file;
if( $cache->is_cached() ) 
{
		if($maintenance_mode['enabled']!=1)
{
if($u !="t")  
mysql_close();	

	//header("Location:$red_url?vip=".$enc_ip."&u=".$u);
	//die;
	
	
include ($red_url);
die;
}
}



ob_start();



$ini_error_status=ini_get('error_reporting');



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




$result=mysql_query("select pad.*,cad.*,pad.credit_text as padtext,cad.title_color as tcolor,cad.desc_color as dcolor,cad.url_color as ucolor,cad.credit_text as ctext from wap_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$adunitid' and cad.wapstatus='1'");

//echo "select pad.*,cad.* from wap_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$adunitid' and cad.wapstatus='1'";

if($ini_error_status!=0)
{
	echo mysql_error();
}
$adunitrow=mysql_fetch_array($result);
$adlang=$adunitrow['adlang'];
if($adlang==0)
{
	$mec_lan=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
$ab=explode(",",$mec_lan);
$ab1=explode("-", $ab[0]);
$mechine=$mysql->echo_one("select id from adserver_languages where code='$ab1[0]'") ;
		$languages="AND (a.adlang='$mechine' or a.adlang='0')";
$ps_lang="AND (adlang='$mechine' or adlang='0')";
}
else
{
	$languages.="AND (a.adlang='$adlang' or a.adlang='0')";
	$ps_lang="AND (adlang='$adlang' or adlang='0')";
}
if(!$adunitrow)
{
	exit(0);
}
/*if($adunit_ht!=($adunitrow[2]+15))
{
	echo $message[1026];
	exit(0);
}*/
//******************************************
$adunittype=$adunitrow['ad_type'];


//************************************5.4******************************************//

if($adunitrow[adult_status]==0)
{
	$adult_string=" and a.adult_status=0 ";
}
else if($adunitrow[adult_status]==1)
{
	$adult_string="";
}

//************************************5.4******************************************//



//************************5.4**************************************//

if($pid != 0)
{
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
		//$domain= $_SERVER['HTTP_HOST'];
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
}
//************************5.4**************************************//


if($maintenance_mode['enabled']!=1)
{




$special_height_flag=0;
/*if($adunit_ht ==($adunitrow['height']+15))
{
$special_height_flag=1;
}*/



$singleaccount="";
$publisher_st="";

if($pid != 0)
{
if($single_account_mode==1)
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid='".$adunitrow['pid']."'");
	//$singleaccount.="AND c.common_account_id!='$commonid' AND c.parent_status!=0";
	
	$singleaccount.="AND ((c.common_account_id!='$commonid' AND c.parent_status ='1') OR (c.common_account_id ='0' AND c.parent_status ='0'))";
	$publisher_st.="AND ((common_account_id >'0' AND parent_status ='1') OR (common_account_id ='0' AND parent_status ='0'))";
	//$publisher_st.="AND parent_status!=0";
}
}


$cont_type=$ad_display_char_encoding;
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
 
 /***********************************time********************************************/
 
 $time="";
 
 if($time_targeting==1)
 {

 $time=" AND  (IF (a.end_time < a.beg_time ,".$z.">= a.beg_time AND ".$z."> a.end_time OR  ".$z."<= a.beg_time AND ".$z."< a.end_time , ".$z.">= a.beg_time AND ".$z."< a.end_time ) OR (a.beg_time=0 AND a.end_time=0) OR (a.beg_time<= ".$z." AND a.end_time=0)) ";

 }
 
 
  /***********************************time********************************************/



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


if($pid != 0)
{
$restrictedsiteids=$adunitrow['ppc_restricted_sites'];
$restrictedsites=mysql_query("select site from ppc_restricted_sites  where id in (".$restrictedsiteids.")");
$sqlstr="";
while($site=mysql_fetch_row($restrictedsites))
	{
		if($site[0]!="")
			$sqlstr.=" and a.link not like '%".$site[0]."%' ";
	}	
}
else
$sqlstr="";
	
	
if($pid != 0)
{
	
$publisher=mysql_query("select status,traffic_analysis from ppc_publishers where uid='".$adunitrow['pid']."'".$publisher_st);
$publisher_row=mysql_fetch_row($publisher);
$pub_status=$publisher_row[0];
if($pub_status==0)
{
	exit(0);
}

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

//$enc_ref=urlencode($ref);//$enc_ref=$_GET['ref'];
$cur_page=mysql_real_escape_string( $_SERVER['HTTP_REFERER']);


$geo_condition="  (";



if($record!="")
{
	$geo_condition.=" (d.country='$record' and d.region='' and d.city='') or ";
}
$geo_condition.=" (d.country='00' and d.region='00' and d.city='00') ) ";

//$geo_condition.=" (d.country is null) ) ";

$vid_row=array();
$visit_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("Y",time()));

if($pid==0)
{
	$t_page="ppc-ad-click.php";
	$crstr=$adunitrow['ctext'];
}
else
{
	$crstr=$adunitrow['padtext'];
	$t_page="ppc-publisher-ad-click.php";
	
}	
	
	
	
	
	
$blockcount=1;	
if($blockcount==1)
{

$expire=time()+60*60;


	
	
	
	
if($pid !=0)
$pub_traffic_analysis= $publisher_row[1];
else
$pub_traffic_analysis=$traffic_analysis;
	
	


	
		
}


if($pub_traffic_analysis!=1)
{
	$vid_row=array(0);
}





$adscount=$adunitrow['no_of_text_ads'];
$catalog_adscount=$adunitrow['no_of_catalog_ads'];






           //        *************New Code For Text Ads********************

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
AND a.wapstatus =1 
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
LIMIT 0,$adscount";



            //          *************New Code For Text Ads********************


                         //          *************New Code For Banner Ads********************


$imageresultsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.uid, b.time,a.adlang 
FROM ppc_users c, ".$view_names." b, ppc_ads a, ad_location_mapping d 
WHERE a.uid = c.uid 
AND a.id = b.aid
AND c.uid = b.uid
and a.id = d.adid
AND ".$geo_condition." 
AND a.maxamount > a.amountused AND (a.maxamount - a.amountused) >= b.maxcv
AND a.status =1
AND a.pausestatus =0
AND a.wapstatus =1 
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
".$languages."
".$singleaccount."
GROUP BY a.id 
".$adrotation."
LIMIT 0,1";


			//          *************New Code For Banner Ads********************







				
				
			



$catalogsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.displayurl, a.uid, b.time,a.adlang 
FROM ppc_users c, ".$view_names." b, ppc_ads a, ad_location_mapping d 
WHERE a.uid = c.uid 
AND a.id = b.aid
AND c.uid = b.uid
and a.id = d.adid
AND ".$geo_condition." 
AND a.maxamount > a.amountused AND (a.maxamount - a.amountused) >= b.maxcv
AND a.status =1
AND a.pausestatus =0
AND a.wapstatus =1 
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
LIMIT 0,$catalog_adscount";



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
AND a.wapstatus =1 
AND ((a.adtype =0 AND (a.bannersize is null)) OR (a.adtype =1 AND a.bannersize ='".$adunitrow['max_size']."'))
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
}
*/


if($ini_error_status!=0)
{
	echo mysql_error();
}
	
	
}

//echo $resultsql;
$adresult=mysql_query($resultsql);


if($ini_error_status!=0)
{
	echo mysql_error();
}

}










if($adunittype==1)
	$psdbadcount=$mysql->total("ppc_public_service_ads","adtype=0 AND status=1 AND wapstatus=1  $ps_lang");
if($adunittype==2)
		$psdbadcount=$mysql->total("ppc_public_service_ads","adtype=1 AND status=1 AND bannersize='".$adunitrow['max_size']."'  AND wapstatus=1 $ps_lang");
	if($adunittype==4)
	$psdbadcount=$mysql->total("ppc_public_service_ads","adtype=2 AND status=1 AND bannersize='".$adunitrow['catalog_size']."'  AND wapstatus=1 $ps_lang");
if($ini_error_status!=0)
{
	echo mysql_error();
}

//$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where id='".$crstr."'");


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



$ct=$mysql->echo_one("select credittype from ppc_publisher_credits where id='".$crstr."'");



if($ct==1)
$bordertype=1;
else
{
if($pid >0)
{
if($adunitrow['allow_bordor_type'] == 0)
$bordertype=$adunitrow['border_type'];
else
$bordertype=$adunitrow['bordor_type'];
}
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





if($pid==0)
$credittextpreview="<tr class=\"inout-credit\" width=100%><td  ><a target=\"_blank\" href=\"$original_url_string"."index.php\" >$credittext</a></td></tr>";
else
$credittextpreview="<tr class=\"inout-credit\" width=100%><td  ><a target=\"_blank\" href=\"$original_url_string"."index.php?r=$pid\" >$credittext</a></td></tr>";




/*

if($adunitrow['adlang']==0)  //anylanguages
{
	if($clan=='en')
	{
		$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
	}
	else
	{
	//echo "select credit from ppc_publisher_credits where parent_id=".$res['ctext']." and language_id='$lanid'";
		$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($credittext=='')
			$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
	}
}
else
{
		
		if($adunitrow['adlang']==$lanid )
		{
				if($clan=='en')
					$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				else
				{
					 $credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
				}
		}
		else
		{
			$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
			if($credittext=='')
				$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		}
		if($credittext=='')
			$credittext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			
		
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

?><!--<html><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cont_type; ?>" /><title></title>-->
<style type="text/css">
.top, .bottom {display:block; background:transparent; font-size:1px;width:<?php  echo $adunitrow['width']; ?>px;}
.tb1, .tb2, .tb3, .tb4 {display:block; overflow:hidden;}
.tb1, .tb2, .tb3 {height:1px;}
.tb2, .tb3, .tb4 {background:<?php if($adunitrow['credit_text_positioning']==1 && $crstr !=0) echo $r1[2]; else echo $adunitrow['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>;}
.tb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.tb2 {margin:0 3px; border-width:0 2px;}
.tb3 {margin:0 2px;}
.tb4 {height:2px; margin:0 1px;}
.bb1, .bb2, .bb3, .bb4 {display:block; overflow:hidden;}
.bb1, .bb2, .bb3 {height:1px;}
.bb2, .bb3, .bb4 {background:<?php if($adunitrow['credit_text_positioning']==0 && $crstr !=0) echo $r1[2]; else echo $adunitrow['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>;}
.bb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.bb2 {margin:0 3px; border-width:0 2px;}
.bb3 {margin:0 2px;}
.bb4 {height:2px; margin:0 1px;}
.parenttable {display:block; background:#FFFFFF; border-style:solid; border-color: <?php echo $r1[2]; ?>; border-width:0 1px; padding: 0px;margin:0 0px;}

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
	border-left: 1px solid  <?php echo $r1[2]; ?>;
	border-right: 1px solid  <?php echo $r1[2]; ?>;
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
.catalogtable
{
table-layout:fixed;
overflow:hidden;
line-height: <?php echo $adunitrow['line_height']; ?>px;
}
.catalogtable td
{
padding :0px 0px;
}
<?php 
if($adunitrow['ad_type']!=2)
{
?>
		.inout-title a:link,.inout-title a:visited,.inout-title a:hover,.inout-title a:active,.inout-title a:focus
		{
		font-family:<?php echo $adunitrow['title_font'];?>;
		font-size:<?php echo $adunitrow['title_size'];?>px;
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
		font-size:<?php echo $adunitrow['desc_size'];?>px;
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
		font-size:<?php echo $adunitrow['url_size'];?>px;
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


height:15px;
font-size:12px;
/*background-image:url(images/tabs.png);*/
background-color:<?php echo $r1[2]; ?>;
background-repeat:repeat;
color:<?php echo $r1[1]; ?>;
width:15px;
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
top:-15px;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==1)  // Right
{
?>

right:0px;
float:right;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==0)  // Left
{
?>

left:0px;
float:left;

<?php
}
?>

}

.liclass 
{
/*height:15px;*/

margin:0;
padding:0 0;
list-style-type:none ;
list-style-position:outside;
<?php
if($adunitrow['credit_text_positioning']==1)  // Top
{
?>
position:relative;
top:0px;
<?php
}
?>
<?php
if($adunitrow['credit_text_positioning']==0)  // Bottom
{
?>
position:relative;
top:0px;


<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==1)  // Right
{
?>
text-align:right;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==0)  // Left
{
?>
text-align:left;


<?php
}
?>


}

.liclass  #crd{display:none;}
li.liclass:hover #crd {
display:block;


}



.inout_credit_data
{
overflow:hidden;
z-index:1000px;
max-width:<?php  echo $adunitrow['width']; ?>px;

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
top:-15px;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==1)  // Right
{
?>
right:0px;
float:right;
<?php
}
?>
<?php
if($adunitrow['credit_text_alignment']==0)  // Left
{
?>
left:0px;
float:left;
<?php
}
?>




}
.inout-before-credit
{
table-layout:fixed;
overflow:hidden;
/*width:<?php  echo $adunitrow['width']; ?>px;*/


}





















.inout-credit
{
/*width:auto;*/
height:15px;


<?php
if($ct ==0 && $adunitrow['credit_text_positioning']!=2 && $adunitrow['credit_text_positioning']!=3)
{
?>
max-width:<?php  echo $adunitrow['width']-10; ?>px;
background-color:<?php echo $r1[2]; ?>;
padding:0 5;
<?php
}
else if($ct ==1 && $adunitrow['credit_text_positioning']!=2 && $adunitrow['credit_text_positioning']!=3)
{
?>
max-width:<?php  echo $adunitrow['width']; ?>px;
padding:0 0;
<?php
}
?>

<?php
if($ct ==0 && ($adunitrow['credit_text_positioning']==2 || $adunitrow['credit_text_positioning']==3))
{
?>


background-color:<?php echo $r1[2]; ?>;
width:<?php echo $singlewidth-10; ?>px;
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
.inout-credit a:link,.inout-credit a:visited,.inout-credit a:hover,.inout-credit a:active,.inout-credit a:focus
{
color:<?php echo $r1[1]; ?>;
font-family:<?php echo $adunitrow['credit_font'];?>;
font-size:10px;
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
</style></head><body><div  <?php
if($adlang==$rtl_lang_id)
{
?>style="direction:rtl;"<?php
 } ?>><?php  
if($adunittype==2)
{
	$bs=md5(rand(0,10));	
	$dummy_str='<a style="display: none;" target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&kid='.$adrow[4].'&pid='.$pid.'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"><img height="'. $adunitrow['height'].'"  width="'.$adunitrow['width'].'" src="'.$original_url_string.'images/cbl.png" border="0"></a>';
		?><table height="<?php if($crstr==0  || $special_height_flag ==0) echo $adunitrow['height']; else if($crstr!=0 && $special_height_flag ==1) echo $adunitrow['height']+15; ?>"  width="<?php  echo $adunitrow['width']; ?>" cellpadding="0" cellspacing="0" class="inout-table"><?php
		
		
		//if($adunitrow['credit_text_positioning']==1 && $credittext!="")
		//	echo "<tr ><td class=\"inout-credit\"><a target=\"_blank\"  href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
			
			
			
			
			
			
		if($adunitrow['scroll_ad']==1)
		{
		?><tr><td><table cellpadding="0" cellspacing="0" border="0"><?php
		}
		?><tr><td align="center"><?php
		echo $dummy_str;
		if($adrow=mysql_fetch_row($adresult))
		{
				
	$bs=md5($adrow[0].$adrow[4].$adunitid);
				
				updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid,$pid);
				if($special_height_flag !=1)
		        {
				
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")  
				{
				?><li class="liclass"><div id="idiv" class="inout_credit_over">(i)</div><div class="inout_credit_data"   id="crd"  ><table cellpadding="0" cellspacing="0" class="inout-before-credit"><?php echo $credittextpreview;?></table></div></li><?php
				}
				}
				?><a target="_blank" href="<?php  echo "{$original_url_string}$t_page?id=$adrow[0]&pid=$pid&kid=$adrow[4]&bid=$adunitid&vid=".$vid_row[0]."&vip={ENC_IP}&direct_status=".$send_direct_status;?>&bs=<?php echo $bs; ?>"><img  height="<?php echo $adunitrow['height']; ?>"  width="<?php  echo $adunitrow['width']; ?>"  src="<?php echo "$original_url_string".$GLOBALS['banners_folder']."/$adrow[0]/$adrow[3]";?>" border="0"></a><?php
				if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==0 && $credittext!="")                //***Bottom Right Credit Text ***************    
				{
				
				?><li class="liclass"><div id="idiv" class="inout_credit_over">(i)</div><div class="inout_credit_data"   id="crd"  ><table cellpadding="0" cellspacing="0" class="inout-before-credit"><?php echo $credittextpreview;?></table></div></li><?php
				
				
				}
				}
		}
		else
		{
				$publicresults=mysql_query("select id, link, title, summary, displayurl from ppc_public_service_ads where adtype=1 AND status=1 AND bannersize =".$adunitrow['max_size']."  AND wapstatus=1 $ps_lang order by lastacesstime ASC LIMIT 0,1");	
				

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
				if($special_height_flag !=1)
		        {
				
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")  
				{
				?><li class="liclass"><div id="idiv" class="inout_credit_over">(i)</div><div class="inout_credit_data"   id="crd"  ><table cellpadding="0" cellspacing="0" class="inout-before-credit"><?php echo $credittextpreview;?></table></div></li><?php
				}
				}
				?><a target="_blank" href="<?php  echo "{$original_url_string}ppc-ps-ad-click.php?id=$rowpsad[0]";?>"><img height="<?php echo $adunitrow['height']; ?>"  width="<?php  echo $adunitrow['width']; ?>" src="<?php echo "$original_url_string".$GLOBALS['admin_folder']."/".$GLOBALS['service_banners_folder']."/$rowpsad[0]/$rowpsad[3]";?>" border="0"></a><?php
				if($special_height_flag !=1)
		        {
				if($adunitrow['credit_text_positioning']==0 && $credittext!="")                //***Bottom Right Credit Text ***************    
				{
				
				?><li class="liclass"><div id="idiv" class="inout_credit_over">(i)</div><div class="inout_credit_data"   id="crd"  ><table cellpadding="0" cellspacing="0" class="inout-before-credit"><?php echo $credittextpreview;?></table></div></li><?php
				
				
				}
				}
				}
		}
		?></td></tr><?php	
		if($adunitrow['scroll_ad']==1)
		{
		?></table></td></tr><?php
		}
		//if($adunitrow['credit_text_positioning']==0 && $credittext!="")
		//	echo "<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
		?></table><?php			
}
else if($adunittype==4)
{
$blockheight= $adunitrow['height'];

		if($bordertype==0)
		{?>
		<b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b>
		<?php
		$blockheight= $adunitrow['height']-10;
		}
		?>
		<table height="<?php  if($crstr==0  || $special_height_flag ==0) echo $blockheight; else if($crstr!=0 && $special_height_flag ==1) echo $blockheight+15; ?>"  width="<?php  echo  $adunitrow['width']; ?>" cellpadding="0" cellspacing="0" class="inout-table">
		
		<?php 
		
		$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='".$adunitrow['catalog_size']."'");
		$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='".$adunitrow['catalog_size']."'");
	//	echo $catalog_width."dddddddd".$adunitrow[42];
	$currentadcount=0;		
	$catalogadblock="";
	$currentcatalogadcount=0;
		if($adunitrow['orientaion']==2)//horizontal
			{

				
				if($adunitrow['credit_text_positioning']==1 && $crstr !=0)
					$catalogadblock.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\"><a target=\"_blank\" href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
				$catalogadblock.="<tr>";
				$cat_ad_cnt=mysql_num_rows($adresult);
				$i=0;
				while($adrow=mysql_fetch_row($adresult))
					{
					
					$currentadcount+=1;
					$currentcatalogadcount+=1;
					//echo $currentcatalogadcount."ddd";
					
					updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid,$pid);
					
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
				 		 $catalogadblock.='<table width="0" style="display: none;">';
				 		 $catalogadblock.='<tr><td align="center" style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
						 $catalogadblock.='<a target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" ><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td> <td align="left" valign="top">';
						 $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"  target="_blank">'.$adrow[6].'</a></div>';
						 $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
						 $catalogadblock.='</td><td width="{WIDTH}">';
				 	}
				 	     $bs=md5($adrow[0].$adrow[4].$adunitid);				 	
						 $catalogadblock.='<table width="100%" cellpadding="0" cellspacing="5" height="100%" border="0"  class="catalogtable" style="';
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
						 
if($adrow[9]==$rtl_lang_id)
{
$catalogadblock.=';direction:rtl';
}
						   
						 $catalogadblock.='">';
						 $catalogadblock.='<tr><td align="center" style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
						 $catalogadblock.='<a target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" ><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td>';
						 
						 
						if($adrow[9]==$rtl_lang_id) 
						$catalogadblock.='<td valign="top">';
						else
						$catalogadblock.='<td align="left" valign="top">'; 
						 
						 
						 
						 $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"  target="_blank">'.$adrow[6].'</a></div>';
						 $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
				 }
				  else 
				  { 
				  	if($currentcatalogadcount==1)
				 	{
				 		 $bs=md5(rand(0,10));
				 		 $catalogadblock.='<table width="0" style="display: none;">';
				 		 $catalogadblock.='<tr><td align="center" style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
						 $catalogadblock.='<a target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" ><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td> <td align="left" valign="top">';
						 $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"  target="_blank">'.$adrow[6].'</a></div>';
						 $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
						 $catalogadblock.='</td><td width="{WIDTH}">';
				 	}
				    $bs=md5($adrow[0].$adrow[4].$adunitid);	
				  	
							   $catalogadblock.='<table width="100%" cellpadding="0" height="100%" cellspacing="5" border=0  class="catalogtable" style="';
								
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
				  if($adrow[9]==$rtl_lang_id)
{
$catalogadblock.='; direction:rtl;';
}
							  $catalogadblock.='">';
							  $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;" ><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
							  $catalogadblock.='<a target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" ><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td> </tr><tr><td valign="top">';
							  $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"  target="_blank">'.$adrow[6].'</a></div>';
							  $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
	 } 		
			 $catalogadblock.='</td>';
			 $i++;
			 
			 }				
		
				//echo $currentadcount."<".$catalog_adscount ;
					if($currentadcount<$catalog_adscount )
				{
					$publicresults=mysql_query("select a.id, link, title, summary, displayurl,a.adlang from ppc_public_service_ads a where  a.bannersize='".$adunitrow['catalog_size']."'  and adtype=2 AND status=1   AND wapstatus=1 $ps_lang order by lastacesstime ASC  LIMIT 0,".($catalog_adscount-$currentadcount));
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
			  $catalogadblock.='<table width="100%" cellpadding="0" height="100%" cellspacing="5" border=0  class="catalogtable" style="';
			//  echo $psdbadcount;
				if($adunitrow['catalog_line_seperator']==1) 
				{
						if( $i < $psdbadcount-1)	
						 	$catalogadblock.='border-right:solid 1px '. $r1[2];				
				}
			  				  if($rowpsad[5]==$rtl_lang_id)
{
$catalogadblock.='; direction:rtl;';
}
				$catalogadblock.='">';
				$catalogadblock.='<tr><td align="center" style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
							 $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2].'" border="0"></a> </div></td>';
							 
							 
						if($rowpsad[5]==$rtl_lang_id)
						$catalogadblock.='<td valign="top">';
						else
						$catalogadblock.='<td align="left" valign="top">'; 	 
							 
							 
				$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'" target="_blank">'.$rowpsad[4].'</a></div>';
				 $catalogadblock.='<div  class="inout-desc">'.$rowpsad[3].'</div></td></tr></table>';
							 
				 }
				  else
				   { 
				   
				    $catalogadblock.='<table width="100%" cellpadding="0" height="100%" cellspacing="5" border=0  class="catalogtable" style="';
								
								if($adunitrow['catalog_line_seperator']==1) 
								{
									if( $i < $psdbadcount-1)	
										$catalogadblock.='border-right:solid 1px '. $r1[2];				
								}
				   				  if($rowpsad[5]==$rtl_lang_id)
{
$catalogadblock.='; direction:rtl;';
}
							   $catalogadblock.='">';
							 $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
							  $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2].'" border="0"></a> </div></td></tr>	<tr> <td valign="top">';
							
							 		$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'" target="_blank">'.$rowpsad[4].'</a></div>';
							 $catalogadblock.='<div class="inout-desc">'.$rowpsad[3].'</div></td></tr></table>';
			
				 } 
				  $catalogadblock.='</td>';	
				
					$currentadcount+=1;
					$i++;
					}
				}
					
		  $catalogadblock.='</tr>';	
		  
				if($adunitrow['credit_text_positioning']==0 && $crstr !=0)
					$catalogadblock.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\"><a target=\"_blank\" href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
					
			}
		else //vertical
			{
				//echo "here1";
				if($adunitrow['credit_text_positioning']==1 && $crstr !=0)
						$catalogadblock.= "<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
				$catalogadblock.='<tr ><td>';
								
				$catalogadblock.= "<table cellpadding=\"0\" cellspacing=\"0\" class=\"innertable\"  height=100% width=".$adunitrow['width'].">";

					$cat_ad_cnt=mysql_num_rows($adresult);
				$i=0;
				while($adrow=mysql_fetch_row($adresult))
				{
				
						$currentadcount+=1;
						$currentcatalogadcount+=1;
						
						updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid,$pid);
						
						$ad_display_url=$adrow[6];
						if($ad_display_url=="")
						$ad_display_url=$adrow[1];
						
						if($currentcatalogadcount==1)
						$catalogadblock.='<tr height="0"  style="display: none;"><td>';
                        else
						$catalogadblock.='<tr height="{HEIGHT}"><td>';
						
						 if($adunitrow['catalog_alignment']==1) 
						 {

						 	if($currentcatalogadcount==1)
						 	{   
						 		$bs=md5(rand(0,10));
						 	    $catalogadblock.='<table width="0" cellpadding="0" height="0"  style="display: none;">"';
						 		$catalogadblock.='<tr><td align="center"  style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
								$catalogadblock.='<a target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" ><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td> <td align="left" valign="top">';
								$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"  target="_blank">'.$adrow[6].'</a></div>';
							    $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
							    $catalogadblock.='</td></tr><tr height="{HEIGHT}"><td>';
						 	}
		                 $bs=md5($adrow[0].$adrow[4].$adunitid);
		                 
						 $catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0" height="100%" cellspacing="5" border=0 class="catalogtable" style="';
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
						 if($adrow[9]==$rtl_lang_id)
{
$catalogadblock.=';direction:rtl';
}
								$catalogadblock.='">';
								$catalogadblock.='<tr><td align="center"  style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
								$catalogadblock.='<a target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" ><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td>';
								
						if($adrow[9]==$rtl_lang_id) 
						$catalogadblock.='<td valign="top">';
						else
						$catalogadblock.='<td align="left" valign="top">'; 
								
								
								$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"  target="_blank">'.$adrow[6].'</a></div>';
							    $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
								 
						 }
					  else
					   {
					   	  if($currentcatalogadcount==1)
						 	{ 
						 		$bs=md5(rand(0,10));
						 	   $catalogadblock.='<table width="0" cellpadding="0"  height="0%"  style="display: none;">" ';	
						 	   $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
							   $catalogadblock.='<a target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" ><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td> </tr><tr><td valign="top">';
							   $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"  target="_blank">'.$adrow[6].'</a></div>';
							   $catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
							   $catalogadblock.='</td></tr><tr height="{HEIGHT}"><td>';
						 	}
						 	$bs=md5($adrow[0].$adrow[4].$adunitid);
						 	
					   	
									 $catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0"  height="100%"  cellspacing="5" border=0 class="catalogtable" style="';
												
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
					   if($adrow[9]==$rtl_lang_id)
{
$catalogadblock.=';direction:rtl';
}
									   $catalogadblock.='">';
									 $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
									 $catalogadblock.='<a target="_blank" href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'" ><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td> </tr><tr><td valign="top">';
									$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip='.$enc_ip.'&direct_status='.$send_direct_status.'&bs='.$bs.'"  target="_blank">'.$adrow[6].'</a></div>';
									$catalogadblock.='<div class="inout-desc">'.$adrow[3].'</div></td></tr></table>';
						}	
						$catalogadblock.='</td></tr>';
				}
				
				if($currentadcount<$catalog_adscount )
				{
					$publicresults=mysql_query("select a.id, link, title, summary, displayurl,a.adlang from ppc_public_service_ads a where  a.bannersize='".$adunitrow['catalog_size']."' and adtype=2 AND status=1  AND wapstatus=1 $ps_lang order by lastacesstime ASC  LIMIT 0,".($catalog_adscount-$currentadcount));
					if($ini_error_status!=0)
					{
						echo mysql_error();
					}
					$i=0;// echo  $psdbadcount;
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
						
						$catalogadblock.='<tr height="{HEIGHT}"><td> ';	
						 if($adunitrow['catalog_alignment']==1)
						  { 
								$catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0" height="100%" cellspacing="5" border=0 class="catalogtable" style="';
								if($adunitrow['catalog_line_seperator']==1 ) 
								{
										if( $i < $psdbadcount-1)	
											$catalogadblock.='border-bottom:solid 1px '. $r1[2];				
				
								}
  if($rowpsad[5]==$rtl_lang_id)
{
$catalogadblock.=';direction:rtl;';
}
								$catalogadblock.='">';
								$catalogadblock.='<tr><td align="center" style="width:'.$catalog_width.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
											 $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2].'" border="0"></a> </div></td>';
											 
											 
						if($rowpsad[5]==$rtl_lang_id)
						$catalogadblock.='<td valign="top">';
						else
						$catalogadblock.='<td align="left" valign="top">'; 				 
											 
											 
											 
											 
								  $catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'" target="_blank">'.$rowpsad[4].'</a></div>';
								 $catalogadblock.='<div class="inout-desc">'.$rowpsad[3].'</div></td></tr></table>';
						 } 
						 else
						  {
				  
									$catalogadblock.='<table width="'.$adunitrow['width'].'" cellpadding="0" height="100%" cellspacing="5" border=0 class="catalogtable" style="';
												
									if($adunitrow['catalog_line_seperator']==1 ) 
									{
												if( $i < $psdbadcount-1)	
												$catalogadblock.='border-bottom:solid 1px '. $r1[2];				
									}
						  				  if($rowpsad[5]==$rtl_lang_id)
{
$catalogadblock.=';direction:rtl;';
}
								   $catalogadblock.='">';
								 $catalogadblock.='<tr><td align="center" style="height:'.$catalog_height.'px;vertical-align:top;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
								  $catalogadblock.='<a target="_blank" href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['admin_folder'].'/'.$GLOBALS['service_banners_folder'].'/'.$rowpsad[0].'/'.$rowpsad[2].'" border="0"></a> </div></td></tr>	<tr> <td valign="top">';
								
								$catalogadblock.='<div class="inout-title"><a href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'" target="_blank">'.$rowpsad[4].'</a></div>';
								 $catalogadblock.='<div class="inout-desc">'.$rowpsad[3].'</div></td></tr></table>';
			
						 } 
					 	 $catalogadblock.='</td></tr>';	
						$currentadcount=$currentadcount+1;
					}
				}				
				$catalogadblock.= "</table>";
				$catalogadblock.= "</td></tr>";

				if($adunitrow['credit_text_positioning']==0 && $crstr !=0)
					$catalogadblock.= "<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
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


       
		$blockheight= $adunitrow['height'];
		if($bordertype==0)
		{
			?><b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b><?php
			$blockheight= $adunitrow['height']-10;
		}
		$marqueeheight=$blockheight;
		?><table height="<?php if($crstr==0  || $special_height_flag ==0)  echo $blockheight; else if($crstr!=0 && $special_height_flag ==1) echo $blockheight+15; ?>"  width="<?php  echo $adunitrow['width']; ?>" cellpadding="<?php  if($adunitrow['scroll_ad']==0) echo "5"; else echo "0";  ?>" cellspacing="0" class="inout-table"><?php 
		$textadblock="";
		$textadblockstart="";
		$textadblockend="";
		$currentadcount=0;		
		if($adunitrow['orientaion']==2)//horizontal
			{
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")
					$textadblockstart.= "<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
				if($adunitrow['scroll_ad']==1)
				{
					$textadblockstart.="<tr><td  colspan=\"{COLSPAN}\">";
					$textadblock.= "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\"  height=$marqueeheight width=".$adunitrow['width']." class=\"marqueetable\">";
				}
				
				$textadblock.="<tr>";
				
				
				if($singleflag==1 && $adunitrow['credit_text_positioning']==2 && $credittext!="")
				{
				if($pid==0)
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$original_url_string.'index.php">'.$credittext.'</a></td>';
				else
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$original_url_string.'index.php?r='.$pid.'">'.$credittext.'</a></td>';
				
				
				}
				
				while($adrow=mysql_fetch_row($adresult))
				{
					
					$currentadcount+=1;
					updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid,$pid);
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
					$textadblock.='<td  width="0" style="display: none;">';
					$textadblock.='<div class="inout-title"><a target="_blank"   href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$adrow[2].'</a></div>';
					$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
					$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$ad_display_url.'</a></div>';
					$textadblock.='</td>';
					}
					$bs=md5($adrow[0].$adrow[4].$adunitid);	
				if($adrow[9]==$rtl_lang_id)
{
$dir_str='style=direction:rtl';
}
					$textadblock.='<td  width="{WIDTH}" valign="top"'.$dir_str.'>';
					$textadblock.='<div class="inout-title"><a target="_blank"   href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$adrow[2].'</a></div>';
					$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
					$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$ad_display_url.'</a></div>';
					$textadblock.='</td>';
					 
					 
					  
				}



				if($currentadcount<$adscount )
				{
					$publicresults=mysql_query("select id, link, title, summary, displayurl,adlang from ppc_public_service_ads where adtype=0 AND status=1  AND wapstatus=1 $ps_lang order by lastacesstime ASC  LIMIT 0,".($adscount-$currentadcount));
					

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
									if($rowpsad[5]==$rtl_lang_id)
{
$dir_str='style=direction:rtl';
}
						$textadblock.='<td width="{WIDTH}" valign="top"'.$dir_str.'><div class="inout-title"><a target="_blank"   href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'">'.$rowpsad[2].'</a></div>';
							$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
							$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.'ppc-ps-ad-click.php?id='.$rowpsad[0].'">'.$disurl.'</a></div>';
						$textadblock.='</td>';
						$currentadcount+=1;
					}
				}
				
				
				if($singleflag==1 && $adunitrow['credit_text_positioning']==3 && $credittext!="")
				{
				if($pid==0)
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$original_url_string.'index.php">'.$credittext.'</a></td>';
				else
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$original_url_string.'index.php?r='.$pid.'">'.$credittext.'</a></td>';
				
				
				}
				
				
				$textadblock.="</tr>";
				if($adunitrow['scroll_ad']==1)
				{
					$textadblock.= "</table>";
					$textadblockend="</td></tr>";
				}
				if($adunitrow['credit_text_positioning']==0 && $credittext!="")
					$textadblockend.="<tr ><td  colspan=\"{COLSPAN}\" class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
			}
		else //vertical
			{
				
				if($adunitrow['credit_text_positioning']==1 && $credittext!="")
					$textadblockstart.="<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
				if($adunitrow['scroll_ad']==1)
				{
					$textadblockstart.="<tr><td>";
				$textadblock.= "<table cellpadding=\"5\" cellspacing=\"0\"   height=$marqueeheight width=".$adunitrow['width']." class=\"marqueetable\">";
				}
				while($adrow=mysql_fetch_row($adresult))
				{
					
					$currentadcount=$currentadcount+1;
					updateAgeingAndImpression ($mysql,$adunittype,$adrow,$ad_ageing_factor,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$direct_status,$serverid,$pid);
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
					$textadblock.='<td ><div class="inout-title"><a target="_blank"   href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$adrow[2].'</a></div>';
					$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
					$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$ad_display_url.'</a></div>';
					$textadblock.= '</td></tr>';
					}
				if($adrow[9]==$rtl_lang_id)
{
$dir_str='style=direction:rtl';
}
					$bs=md5($adrow[0].$adrow[4].$adunitid);	
					$textadblock.='<tr height="{HEIGHT}"'.$dir_str.'>';
					$textadblock.='<td ><div class="inout-title"><a target="_blank"   href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$adrow[2].'</a></div>';
					$textadblock.='<div class="inout-desc">'.$d_description.'</div>';
					$textadblock.='<div class="inout-url"><a  target="_blank"  href="'.$original_url_string.$t_page.'?id='.$adrow[0].'&pid='.$pid.'&kid='.$adrow[4].'&bid='.$adunitid.'&vid='.$vid_row[0].'&vip={ENC_IP}&direct_status='.$send_direct_status.'&bs='.$bs.'" >'.$ad_display_url.'</a></div>';
					$textadblock.= '</td></tr>'; 
					
					
				}
				


				

				if($currentadcount<$adscount )
				{
					$publicresults=mysql_query("select id, link, title, summary, displayurl,adlang from ppc_public_service_ads where adtype=0 AND status=1  AND wapstatus=1  $ps_lang order by lastacesstime ASC  LIMIT 0,".($adscount-$currentadcount));
					

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
					if($rowpsad[5]==$rtl_lang_id)
{
$dir_str='style=direction:rtl';
}
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
				if($adunitrow['credit_text_positioning']==0 && $credittext!="")//credit text at bottom
				{
					$textadblockend.="<tr ><td class=\"inout-credit\"><a  target=\"_blank\"  href=\"$original_url_string"."index.php\">$credittext</a></td></tr>";
				}	
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

?><!--</div></body></html>--><?php
}
$expire=time()+60*60;

				
 



				
function updateAgeingAndImpression ($mysql,$adunittype,$row,$ad_ageing_factor,$adunitid,$vid,$public_ip,$fraud_time_interval,$direct_status,$serverid,$pid)
{

//echo $public_ip;

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
ob_start();
$impression_str="'.$impression_id_str.'";
$impression_str_pub="'.$impression_id_str_pub.'";

//$u_flag=$_GET[\'u\'];
$u_flag=$u;

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