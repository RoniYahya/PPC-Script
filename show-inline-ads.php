<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");




$hostname="";
$ref=safeRead(urldecode(trim($_GET['ref'])));
$searchkeyword=urldecode(trim($_GET['search']));
if(isset($_GET['hostname']))
$hostname=safeRead(urldecode(trim($_GET['hostname'])));

$adunit_rendered=trim($_GET['r']);
$adunitid=intval($_GET['id']);




$pid=$mysql->echo_one("select pid from ppc_custom_ad_block where id='$adunitid'");
if($pid =="")
$pid=0;


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
$searchkeyword=urlencode($searchkeyword);
$hostname=urlencode($hostname);
$ref=urlencode($ref);


$url=$server_url_row[0]."show-inline-ads.php?id=".$adunitid."&r=".$adunit_rendered."&search=".$searchkeyword."&ref=".$ref."&server_path_id=".$srv_id."&hostname=".$hostname."&original_url_string=".$server_dir;
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
$searchkeyword=urlencode($searchkeyword);
$hostname=urlencode($hostname);
$ref=urlencode($ref);


$url=$server_url_row[0]."show-inline-ads.php?id=".$adunitid."&r=".$adunit_rendered."&search=".$searchkeyword."&ref=".$ref."&server_path_id=".$server_url_row[2]."&hostname=".$hostname."&original_url_string=".$server_dir;

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

$public_ip=getUserIP();

$gi = geoip_open("geo/GeoIP.dat",GEOIP_STANDARD);
$record = geoip_country_code_by_addr($gi, $public_ip);

$enc_ip=md5($public_ip);

includeClass("Cache");
$cache = new Cache($GLOBALS['cache_timeout'],$GLOBALS['cache_folder']);
$uri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],"&r"));

$uri =$uri.$record;



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

	

$cache->cache_file 		= md5($uri) . ".php";
$red_url = $cache->cache_folder . "/" . $cache->cache_file;
if( $cache->is_cached() ) 
{
	
if($pub_traffic_analysis !=1 && $u !="t")  
mysql_close();	
	
	//header("Location:$red_url?vip=".$enc_ip."&u=".$u);
	//die;
	
	include ($red_url);
    die;	
}



ob_start();

$eng_name="a_".md5($ppc_engine_name)."_";



$searchkeyword=safeRead($searchkeyword);
$c_flag=0;
//echo $searchkeyword;
if($searchkeyword=="<YOUR KEYWORDS HERE>" or $searchkeyword=="")
{
	$c_flag=1;
}

if($c_flag==0)
{

	$keywords=explode(",",$searchkeyword);
	$keywordstr="";
	$arrdatacount=count($keywords);
	for($i=0;$i<count($keywords);$i++)
	{
		$keywordstr.=" b.keyword='".$keywords[$i]."' ";
		if($i!=count($keywords)-1)
			$keywordstr.="or";
	}

	if($keywordstr != "")
		$keywordstr="( ".$keywordstr." )";

}

//echo $keywordstr;
//print_r($keywords);

function strip_punctuation( $text )
{
    $urlbrackets    = '\[\]\(\)';
    $urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
    $urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
    $urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;
 
    $specialquotes  = '\'"\*<>';
 
    $fullstop       = '\x{002E}\x{FE52}\x{FF0E}';
    $comma          = '\x{002C}\x{FE50}\x{FF0C}';
    $arabsep        = '\x{066B}\x{066C}';
    $numseparators  = $fullstop . $comma . $arabsep;
 
    $numbersign     = '\x{0023}\x{FE5F}\x{FF03}';
    $percent        = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
    $prime          = '\x{2032}\x{2033}\x{2034}\x{2057}';
    $nummodifiers   = $numbersign . $percent . $prime;
 
    return preg_replace(
        array(
        // Remove separator, control, formatting, surrogate,
        // open/close quotes.
            '/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
        // Remove other punctuation except special cases
            '/\p{Po}(?<![' . $specialquotes .
                $numseparators . $urlall . $nummodifiers . '])/u',
        // Remove non-URL open/close brackets, except URL brackets.
            '/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
        // Remove special quotes, dashes, connectors, number
        // separators, and URL characters followed by a space
            '/[' . $specialquotes . $numseparators . $urlspaceafter .
                '\p{Pd}\p{Pc}]+((?= )|$)/u',
        // Remove special quotes, connectors, and URL characters
        // preceded by a space
            '/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
        // Remove dashes preceded by a space, but not followed by a number
            '/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
        // Remove consecutive spaces
            '/ +/',
        ),
        ' ',
        $text );
}

function strip_symbols( $text )
{
    $plus   = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
    $minus  = '\x{2012}\x{208B}\x{207B}';
 
    $units  = '\\x{00B0}\x{2103}\x{2109}\\x{23CD}';
    $units .= '\\x{32CC}-\\x{32CE}';
    $units .= '\\x{3300}-\\x{3357}';
    $units .= '\\x{3371}-\\x{33DF}';
    $units .= '\\x{33FF}';
 
    $ideo   = '\\x{2E80}-\\x{2EF3}';
    $ideo  .= '\\x{2F00}-\\x{2FD5}';
    $ideo  .= '\\x{2FF0}-\\x{2FFB}';
    $ideo  .= '\\x{3037}-\\x{303F}';
    $ideo  .= '\\x{3190}-\\x{319F}';
    $ideo  .= '\\x{31C0}-\\x{31CF}';
    $ideo  .= '\\x{32C0}-\\x{32CB}';
    $ideo  .= '\\x{3358}-\\x{3370}';
    $ideo  .= '\\x{33E0}-\\x{33FE}';
    $ideo  .= '\\x{A490}-\\x{A4C6}';
 
    return preg_replace(
        array(
        // Remove modifier and private use symbols.
            '/[\p{Sk}\p{Co}]/u',
        // Remove mathematics symbols except + - = ~ and fraction slash
            '/\p{Sm}(?<![' . $plus . $minus . '=~\x{2044}])/u',
        // Remove + - if space before, no number or currency after
            '/((?<= )|^)[' . $plus . $minus . ']+((?![\p{N}\p{Sc}])|$)/u',
        // Remove = if space before
            '/((?<= )|^)=+/u',
        // Remove + - = ~ if space after
            '/[' . $plus . $minus . '=~]+((?= )|$)/u',
        // Remove other symbols except units and ideograph parts
            '/\p{So}(?<![' . $units . $ideo . '])/u',
        // Remove consecutive white space
            '/ +/',
        ),
        ' ',
        $text );
}

function strip_numbers( $text )
{
    $urlchars      = '\.,:;\'=+\-_\*%@&\/\\\\?!#~\[\]\(\)';
    $notdelim      = '\p{L}\p{M}\p{N}\p{Pc}\p{Pd}' . $urlchars;
    $predelim      = '((?<=[^' . $notdelim . '])|^)';
    $postdelim     = '((?=[^'  . $notdelim . '])|$)';
 
    $fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
    $comma         = '\x{002C}\x{FE50}\x{FF0C}';
    $arabsep       = '\x{066B}\x{066C}';
    $numseparators = $fullstop . $comma . $arabsep;
    $plus          = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
    $minus         = '\x{2212}\x{208B}\x{207B}\p{Pd}';
    $slash         = '[\/\x{2044}]';
    $colon         = ':\x{FE55}\x{FF1A}\x{2236}';
    $units         = '%\x{FF05}\x{FE64}\x{2030}\x{2031}';
    $units        .= '\x{00B0}\x{2103}\x{2109}\x{23CD}';
    $units        .= '\x{32CC}-\x{32CE}';
    $units        .= '\x{3300}-\x{3357}';
    $units        .= '\x{3371}-\x{33DF}';
    $units        .= '\x{33FF}';
    $percents      = '%\x{FE64}\x{FF05}\x{2030}\x{2031}';
    $ampm          = '([aApP][mM])';
 
    $digits        = '[\p{N}' . $numseparators . ']+';
    $sign          = '[' . $plus . $minus . ']?';
    $exponent      = '([eE]' . $sign . $digits . ')?';
    $prenum        = $sign . '[\p{Sc}#]?' . $sign;
    $postnum       = '([\p{Sc}' . $units . $percents . ']|' . $ampm . ')?';
    $number        = $prenum . $digits . $exponent . $postnum;
    $fraction      = $number . '(' . $slash . $number . ')?';
    $numpair       = $fraction . '([' . $minus . $colon . $fullstop . ']' .
        $fraction . ')*';
 
    return preg_replace(
        array(
        // Match delimited numbers
            '/' . $predelim . $numpair . $postdelim . '/u',
        // Match consecutive white space
            '/ +/u','.$eng_name.'
        ),
        ' ',
        $text );
}

function safeRead($target)
{
	global $ad_display_char_encoding;
	
	/*
	if(strcasecmp($ad_display_char_encoding,"UTF-8")==0)
		$target = htmlspecialchars($target, ENT_QUOTES, "UTF-8");

	else
		$target = htmlspecialchars($target,ENT_QUOTES);
	*/
	if(!get_magic_quotes_gpc())	
	$target=mysql_real_escape_string($target);
	return $target;

}


$ini_error_status=ini_get('error_reporting');



/*
if($count1>0)
$underline.='new Array(';
for($i=0;$i<$count1;$i++)
{
$underline.='"'.$inlinekeywords[$i].'"';

if($i!=$count1-1)
$underline.=',';
}
$underline.=');';
*/



$hostname1="";





if($hostname!="" &&  $c_flag==1)
{

	if (function_exists('curl_init')) 
	{      
	
		$ch = curl_init();     
		curl_setopt($ch, CURLOPT_URL, "$hostname"); 
		curl_setopt($ch, CURLOPT_HEADER, 0);       
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$inline_data = curl_exec($ch);       
		curl_close($ch);
	}   
	elseif($fp_inline=fopen("$hostname","r")) 
	{
		while(!feof($fp_inline))
			$inline_data.=fgetc($fp_inline);
		fclose($fp_inline);
	}


	$inhostname=substr($hostname,0,5);
	if($inhostname=="http:")
	{
		$hostname=substr($hostname,7);
		$pos=strpos($hostname,'/');
		$hostname=substr($hostname,0,$pos);
	}
	else if($inhostname=="https")
	{
		$hostname=substr($hostname,8);
		$pos=strpos($hostname,'/');
		$hostname=substr($hostname,0,$pos);
	}


	$sitecontent= strip_tags($inline_data);
	
	$sitecontent = html_entity_decode( $sitecontent, ENT_QUOTES, "utf-8" );
	$sitecontent = strip_punctuation( $sitecontent );
	$sitecontent = strip_symbols( $sitecontent );
	$sitecontent = strip_numbers( $sitecontent );
	
	$sitecontent=str_replace('\r\n'," ",$sitecontent);
	$sitecontent=str_replace('\r'," ",$sitecontent);
	$sitecontent=str_replace('\n'," ",$sitecontent);
	$sitecontent=str_replace('\t'," ",$sitecontent);
	$sitecontent=mysql_real_escape_string($sitecontent);
	$a=explode(" ",$sitecontent);
	$array3 =explode(" ",$ignoreList);
	$keywordCounts = array_diff ($a, $array3);
	$keywordCounts = array_count_values( $keywordCounts );
	arsort( $keywordCounts, SORT_NUMERIC );


	$result_arr=array();
	$i=0;
	foreach ($keywordCounts as $value=>$key)
	{
		$result_arr[$i]=$value;
		$i++;
	}

	if(count($result_arr)>10)
		$arrdatacount=10;
	else
		$arrdatacount=count($result_arr);
  
	$keywordstr="";
	
	for($i=0;$i<$arrdatacount;$i++)
	{
		$keywordstr.=" b.keyword='".$result_arr[$i]."' ";
		if($i!=$arrdatacount-1)
			$keywordstr.="or";
	}

	if($keywordstr != "")
		$keywordstr="( ".$keywordstr." )";
}	

	


$result=mysql_query("select pad.*,cad.* from ppc_ad_block pad,ppc_custom_ad_block cad where  pad.id=cad.bid and cad.id='$adunitid'");

//echo "select pad.*,cad.* from ppc_ad_block pad,ppc_custom_ad_block cad where  pad.id=cad.bid and cad.id='$adunitid'";
//exit(0);


echo mysql_error();

if(mysql_num_rows($result)==0)
{
	exit(0);
}
else
{
	$adunitrow=mysql_fetch_array($result);
}
$languages="";
$adlang=$adunitrow['adlang'];
if($adlang==0)
{
	$mec_lan=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
$ab=explode(",",$mec_lan);
$ab1=explode("-", $ab[0]);
$mechine=$mysql->echo_one("select id from adserver_languages where code='$ab1[0]'") ;

		$languages="AND (a.adlang='$mechine' or a.adlang='0')";

}
else
{
	$languages.="AND (a.adlang='$adlang' or a.adlang=0)";
}

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
 
 
 
/***********************************time********************************************/
 
 $time="";
 
 if($time_targeting==1)
 {

 $time=" AND  (IF (a.end_time < a.beg_time ,".$z.">= a.beg_time AND ".$z."> a.end_time OR  ".$z."<= a.beg_time AND ".$z."< a.end_time , ".$z.">= a.beg_time AND ".$z."< a.end_time ) OR (a.beg_time=0 AND a.end_time=0) OR (a.beg_time<= ".$z." AND a.end_time=0)) ";

 }
 
 
/***********************************time********************************************/
 
 


if($pid > 0)
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
if($adunitrow['ad_type'] !=6 && $adunitrow['ad_type']!=7)
{
	exit(0);
	
}
//$pid=$adunitrow[47];



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



if($pid > 0)
{
$publisher=mysql_query("select status,traffic_analysis from ppc_publishers where uid='".$adunitrow['pid']."'".$publisher_st);
$publisher_row=mysql_fetch_row($publisher);
$pub_status=$publisher_row[0];
if($pub_status==0)
{
	exit(0);
}

}


if($adunitrow['pid']>0)
$target="ppc-publisher-ad-click.php?pid=".$adunitrow['pid']."&";
else
$target="ppc-ad-click.php?";
	




$bonous_string="";
if($adunitrow['pid'] > 0)
{
if($bonous_system_type ==1)
$bonous_string="AND c.accountbalance >= b.maxcv AND c.accountbalance >0";
else if($bonous_system_type ==0)
$bonous_string="AND (c.accountbalance >= b.maxcv OR c.bonusbalance >= b.maxcv) AND (c.accountbalance >0 OR c.bonusbalance >0)";
}
else
$bonous_string="AND (c.accountbalance >= b.maxcv OR c.bonusbalance >= b.maxcv) AND (c.accountbalance >0 OR c.bonusbalance >0)";





if($pid > 0)
{
$restrictedsiteids=$adunitrow['ppc_restricted_sites'];
$sqlstr="";
if($restrictedsiteids !="")
{
	$restrictedsites=mysql_query("select site from ppc_restricted_sites  where id in (".$restrictedsiteids.")");

	while($site=mysql_fetch_row($restrictedsites))
	{
		$sqlstr.="and a.link not like '%".$site[0]."%' ";
	}	

}

}
else
$sqlstr="";



////////////////////////////////////////////////////////////////////////////////////$ref=saferead($_GET['ref']);

////////////////////////////////////////////////////////////////////////////////////$cur_page=$_SERVER['HTTP_REFERER'];


$gi = geoip_open("geo/GeoIP.dat",GEOIP_STANDARD);
$record = geoip_country_code_by_addr($gi, $public_ip);
$geo_condition="  (";



if($record!="")
{
	$geo_condition.=" (d.country='$record' and d.region='' and d.city='') or ";
}
$geo_condition.=" (d.country='00' and d.region='00' and d.city='00') ) ";

//$geo_condition.=" (d.country is null) ) ";


$adunittype=$adunitrow['ad_type'];
 
if($adunittype==6)
{
	
	
$textsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.displayurl, a.uid, b.time,a.bannersize,b.keyword
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
LIMIT 0,$arrdatacount";


	
	$resultsql=$textsql;
 
}
 
if($adunittype==7)
{
	
	


$catalogsql="SELECT a.id, a.link, a.title, a.summary, b.id, b.maxcv, a.displayurl, a.uid, b.time,a.bannersize,b.keyword 
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
AND a.time_status=1 
".$time." 
AND a.bannersize='".$adunitrow['catalog_size']."' 
".$bonous_string."  
".$sqlstr." 
".$adult_string." 
".$languages."
".$singleaccount."
GROUP BY a.id 
".$adrotation."
LIMIT 0,$arrdatacount";






	$resultsql=$catalogsql;

}
 


$adresult=mysql_query( $resultsql);

if($ini_error_status!=0)

{
	echo mysql_error();
}

							
//$inlineblock="";

$inlinecount=mysql_num_rows($adresult);
if($inlinecount >0)
{











if($pid >0)
$pub_traffic_analysis= $publisher_row[1];
else
$pub_traffic_analysis=$traffic_analysis;
	
	
$cur_page=mysql_real_escape_string($cur_page);

	
	
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

if($pub_traffic_analysis!=1)
{
	$vid_row=array(0);
}











	ob_clean();
	

	?>




	var alreadyrunflag=0 //flag to indicate whether target function has already been run
	var index=10;
	var index=10;
	var engine="<?php echo $eng_name; ?>";

	
	var window_obj = window;
	var ie=document.all && !window.opera;
	var iebody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body ;
	var scr_ht=(ie)? iebody.clientHeight: window.innerHeight ;//screen.height
	var scr_wt=(ie)? iebody.clientWidth : window.innerWidth ;//screen.width
	var op = 0;
	if (window_obj.navigator.userAgent.indexOf("Opera")!=-1)
		op = 1;

	var a_prm = "#27A91F";
	var inline_words ;
	var inline_words_ids ;
	var inline_urls ;
	var inline_evt_names = new Array("onmouseover","onmouseout","onclick")

	var curWord = "";
	var slct_wrd_count = 0;
	var wrd_count;
	var hgl_interv;
	var load_interv;
	
	var inlineadoffsetheight=<?php echo $adunitrow['height']; ?>;
	var inlineadoffsetwidth =<?php echo $adunitrow['width']; ?>;

	var direction;

	
	



	function startupInline()
	{
			//alert('started');
		var divImgTag = document.createElement("div");
		divImgTag.id = "<?php echo $eng_name.'inimage';?>";
		divImgTag.setAttribute("style","<?php echo 'display:none; width:35px; height:35px;z-index:11; position:absolute;'; ?>");
		divImgTag.style.display='none';
		divImgTag.style.position='absolute';
		//divImgTag.style.border='1px solid red';
		divImgTag.innerHTML='<img src="<?php echo $original_url_string;?>images/loaddata.gif">';
		document.getElementsByTagName('body')[0].appendChild(divImgTag);           
	<?php
	$keywordid="";
	$underline="";


	$i=0;
	
	$underline1=array();	
	$keywordid=array();
	$urlarray=array();

	while($adrow=mysql_fetch_row($adresult))
	{
		if($i==$inlinecount)
			break;
			
		if (!in_array($adrow[10], $underline1)) 
		{
			$underline1[$i]=$adrow[10];
			$keywordid[$i]=$adrow[0];
			$urlarray[$i]=$original_url_string.$target."id=$adrow[0]&kid=$adrow[4]&bid=$adunitid&vid=$vid_row[0]&vip={ENC_IP}";
			$urlarray[$i]= str_replace("{ENC_IP}",$enc_ip,$urlarray[$i]);	
		
		}
		
				

		?>
		divTag = document.createElement("div");
		divTag.id = "<?php echo $eng_name.'inline'.$adrow[0];?>";
		divTag.setAttribute("style","<?php echo 'width:'.$adunitrow['width'].'px;display:none;z-index:10; position:absolute;overflow:hidden;padding:0px;margin:0px;height:'.($adunitrow['height']).'px;'; ?>");
		divTag.innerHTML='<?php echo '<iframe id="'.$eng_name.'inlinef'.$adrow[0]. '" width="'.$adunitrow['width'].'" allowtransparency="true" height="'.($adunitrow['height']).'"  frameborder="0" src="'.$server_dir.'load-inline-ads.php?ad_uid='.$adunitid.'&adid='.$adrow[0].'&kid='.$adrow[4].'&lastAccTime='.$adrow[8].'&original_url_string='.$original_url_string.'&serverid='.$serverid.'&direct_status='.$direct_status.'&vid='.$vid_row[0].'&send_direct_status='.$send_direct_status.'"></iframe>'; ?>';
		divTag.style.display='none';
		//divTag.style.border='1px solid red';
		//alert(divTag.style.display);
		document.getElementsByTagName('body')[0].appendChild(divTag);  
		<?php
		
		
		      
		
		$i++;	
	}
		
		
	$underline1=implode('","',$underline1);
	$keywordid=implode('","',$keywordid);
	$urlarray1=implode('\',\'',$urlarray);

	//$urlarray=explode(',',$urlarray);
	$underline1='"'.$underline1.'"';
	$keywordid='"'.$keywordid.'"';
	$urlarray1='\''.$urlarray1.'\'';
			
			
	?>

		inline_words =new Array(<?php echo $underline1; ?>);
		inline_words_ids = new Array(<?php echo $keywordid; ?>);
		inline_urls=new Array(<?php echo $urlarray1; ?>);
		TriggerInlineAds()
	}


	function AttachDivEvtHandlers()
	{
		//alert(inline_words_ids);
		for(i=0; i < inline_words_ids.length;i++)
		{
			AddInlineEventHandlers(GetObject(engine+"inline"+inline_words_ids[i]),  new Array("onmouseover","onmouseout"),  new Array(Show, Hide) );   // events for ad displaying div tags
		}		
	}
	function AddInlineEventHandlers(target_obj, evt_arr, evt_handler_array)
	{
		var i;
		if(!target_obj || !evt_arr || !evt_handler_array || evt_arr.length!=evt_handler_array.length) return;
		for (i=0;i < evt_arr.length;i++)
			if(ie && !op) 
				target_obj.attachEvent(evt_arr[i],evt_handler_array[i]);
			else
				target_obj.addEventListener(evt_arr[i].replace("on",""),evt_handler_array[i],true);
	}

	function GetObject(s_id)
	{
		//alert(s_id);
		return document.getElementById(s_id);
	}
	
	function Show(e)  // keep showing the div
	{
		if (!e) var e = window.event;
		relTarg = e.srcElement ? e.srcElement : e.target;
		relTargid=relTarg.id;
		relTargid=relTargid.replace('inlinef','inline');
		InlineHideCancel(relTargid);
	}
	
	function InlineHideCancel(idstr) // cancel timer initiated for hiding the div
	{
		//alert(idstr);
		if(eval('window.tmr'+idstr)) // ******************************************** commented for  IE
			eval('window_obj.clearTimeout(window.tmr'+idstr+');');
	}
	
	function Hide(e)  // initiate hiding the div
	{
		doc_el = document.documentElement; 
		scrl_top = doc_el.scrollTop ? doc_el.scrollTop : document.body.scrollTop; 
		if (!e) var e = window.event;
		relTarg = e.srcElement ? e.srcElement : e.target;
		relTargid=relTarg.id;
		relTargid=relTargid.replace('inlinef','inline');
		if(((e.clientX<=relTarg.offsetLeft) || (e.clientX>=(relTarg.offsetLeft+inlineadoffsetwidth))) || (((e.clientY+scrl_top)>=(relTarg.offsetTop+inlineadoffsetheight)) || ((e.clientY+scrl_top)<=relTarg.offsetTop)))
		{
			eval('window.tmr'+relTargid+'=window_obj.setTimeout("InlineHide(\''+relTargid+'\')",1000);');
		}
	}

	function InlineHide(x)  // hide the div immediately
	{	
		//alert(x);
		var msg_div = GetObject(x);
		if (msg_div) 
			msg_div.style.display = "none";
	}


	function TriggerInlineAds()
	{	
		AttachDivEvtHandlers()
		hgl_interv = window_obj.setInterval("InlineHighlight()",10);
		//alert(1);
	}
	
	//TriggerInlineAds()


	function InlineHighlight()
	{
		if (slct_wrd_count == inline_words.length) 
			window_obj.clearInterval(hgl_interv);
		else 
		{
			try 
			{ 
				wrd_count=0;
				inline_words[slct_wrd_count] = decodeURI(inline_words[slct_wrd_count]);		
				inline_words[slct_wrd_count] = inline_words[slct_wrd_count].replace(/^\s+/g,"");//remove heading whitespace
				inline_words[slct_wrd_count] =inline_words[slct_wrd_count].replace(/\s+$/g,"");//remove trailing whitespace
				InlineRecursive(document.body, inline_words[slct_wrd_count]);
			} 
			catch(e) 
			{	
				//alert(e); 
			}
			slct_wrd_count++;
		}
	}
	

	function InlineRecursive(main_nd, srch_word)
	{
		if(srch_word==curWord) return;
		var o_chld_nd, o_pr_nd, s_nd_nm, tmp_nd_val;
		var i,j,k,m, srch_word_cnt = 0;
		var hi_txt, hi_txt_nd, hi_txt_nd_hold, s_txt;
		var evHandlers = new Array(InlineShow, InlineHideDelay, InlineClick);
		if(main_nd.childNodes && main_nd.childNodes.length && srch_word.length)
		{
			s_txt = ie ? main_nd.innerText : main_nd.textContent;
			if (s_txt.toLowerCase().indexOf(srch_word.toLowerCase()) == -1)
				return;
			for (i=0; i < main_nd.childNodes.length && srch_word_cnt < 4; i++)
			{
				o_chld_nd = main_nd.childNodes[i];
				o_pr_nd = o_chld_nd.parentNode;		
				if (!o_pr_nd || o_pr_nd.getAttribute("tpi"))
					return;
				s_nd_nm = o_pr_nd.nodeName;
				if (s_nd_nm.indexOf("H")==0 || s_nd_nm=="SCRIPT" || s_nd_nm=="STYLE" || s_nd_nm=="A" || s_nd_nm=="TEXTAREA" || s_nd_nm=="INPUT")
					return; 
				if (o_chld_nd.nodeValue && o_chld_nd.nodeValue.length && o_chld_nd.nodeType == 3) 
				{			  	
					tmp_nd_val = o_chld_nd.nodeValue.toLowerCase();
					ni = GetInlineWord(tmp_nd_val,srch_word); 
					if (ni>-1)
					{ 
						wrd_count++; 
						if (wrd_count%2!=0) 
						{ 
							m=1; 
							while(ni>-1 && curWord!=srch_word) 
							{ 
								nv = o_chld_nd.nodeValue; 
								before = document.createTextNode(nv.substr(0,ni)); 
								hi_txt = nv.substr(ni,srch_word.length); 
								after = document.createTextNode(nv.substr(ni+srch_word.length)); 
								hi_txt_nd = document.createTextNode(hi_txt); 
								hi_txt_nd_hold = document.createElement("A"); 
								with(hi_txt_nd_hold) 
								{ 
									style.cssText = "position:relative;padding:0px 0px 1px 0px;border-bottom:1px solid "+a_prm+"; color:"+a_prm+"; text-decoration:underline; cursor:pointer; ";
									setAttribute("tpi","1"); 
									setAttribute("id",engine+'inlinex'+inline_words_ids[slct_wrd_count]); 
									setAttribute("href",inline_urls[slct_wrd_count]); 
									setAttribute("target","_blank"); 
									//	this.setAttribute("value","inimage"); 
									if (op)
										setAttribute("onmouseover","InlineShow(event)"); 
									appendChild(hi_txt_nd); 
								} 
								AddInlineEventHandlers(hi_txt_nd_hold,inline_evt_names,evHandlers);							
								curWord=srch_word;
								with(o_pr_nd) 
								{
									insertBefore(before,o_chld_nd);
									insertBefore(hi_txt_nd_hold,o_chld_nd);
									insertBefore(after,o_chld_nd);
									removeChild(o_chld_nd);
								}
								o_chld_nd = after;
								tmp_nd_val = o_chld_nd.nodeValue.toLowerCase();
								ni = GetInlineWord(tmp_nd_val,srch_word);
								m++;
								srch_word_cnt++;
							}
							i += m;
						}
					}
				}			
				InlineRecursive(o_chld_nd,srch_word);
			}
		}
		else
			return;
	}


	function GetInlineWord(text_data, word)
	{	
		var srch_word = new RegExp(word, "ig");
		var signs = "\n\ ,.!?\"";
		var chr_before, chr_after, do_ret = 0;
		var tmp=0, add=0, val1=0, val2=text_data.length-1;
		try 
		{
			while (!do_ret) 
			{
				do_ret = 1;//alert(text_data);
				tmp = text_data.search(srch_word);	
				if (tmp > -1) 
				{									
					//if (!ie) 
					{
						val1 = 2;
						val2 = text_data.length - 2;
					}
					if (tmp > val1) 
					{
						chr_before = text_data.charAt(tmp-1);
						if (signs.indexOf(chr_before)==-1) 
							do_ret = 0;           //alert(chr_before+'%'+do_ret);
					}	
					if ((tmp + word.length) <= val2) 
					{			
						chr_after = text_data.charAt(tmp+word.length);
						if (signs.indexOf(chr_after)==-1) 
							do_ret = 0;				//alert(chr_after+'%'+do_ret);
					}
				}
				else
					add = 0;
				if (!do_ret) 
				{
					add += tmp+word.length;
					text_data = text_data.substring(tmp+word.length,text_data.length);
				}

			}//alert(tmp);
			return (tmp + add);
		} 
		catch(e) 
		{ 
				return -1; 
		}
	}
	
	

	function InlineShow(event)
	{	
		var o_child, over, word, tt_data_ref, doc_el;
		var elemOfsX = elemOfsY = y_co = scrl_top = 0;
		var ev = event;
		if (!ev) return;
		var elem1 = elem = ev.srcElement ? ev.srcElement : ev.target;
		var do_renew = 0;
		var o_nd_type = ie ? 1 : elem1.ELEMENT_NODE;
		idstring=elem1.id
		idstring=idstring.replace('inlinex','inline');
		InlineHideCancel(idstring);
		while(elem1.nodeType != o_nd_type)
			elem1 = elem1.parentNode;
		if (elem1.offsetParent)
			while (elem1.offsetParent) 
			{
				elemOfsX += elem1.offsetLeft;
				elemOfsY += elem1.offsetTop;
				elem1 = elem1.offsetParent;
			}
	
		//alert(GetObject('ifr'+idstring).src);
		msg_div=GetObject(engine+'inimage');
		//alert(msg_div);
		GetObject(idstring).style.zIndex=++index;
		
		if(GetObject(idstring).style.display.equals("none"))
		{
			doc_el = document.documentElement; 
			scrl_top = doc_el.scrollTop ? doc_el.scrollTop : document.body.scrollTop; 
			over = (elemOfsX + inlineadoffsetwidth) - document.body.offsetWidth + 10; 
			if(scr_wt>document.body.offsetWidth)	
				over = (elemOfsX + inlineadoffsetwidth) - scr_wt + 10; 
			elemOfsX1=elemOfsX;
			if (over > 0) 
				elemOfsX -= inlineadoffsetwidth; 
			if (elemOfsY - scrl_top < inlineadoffsetheight + 10) 
			{
				y_co = elemOfsY + elem.offsetHeight -2; 
				y_co1=elemOfsY+ elem.offsetHeight;
				direction='down';
			}
			else 
			{
				y_co = elemOfsY- inlineadoffsetheight; 
				y_co1=elemOfsY-25;
				direction='up';
			}
			msg_div.style.left = (elemOfsX1 + "px");
			msg_div.style.top = (y_co1 + "px");
			msg_div.style.display="";
			eval('window.height'+idstring+'=0;');
			eval('window.loadimage'+idstring +'=window_obj.setTimeout("ShowInlineAd(\''+idstring+'\',\''+elemOfsX+'\',\''+y_co+'\')",500);');
		}
	}
	
	
	
	function ShowInlineAd(idstring,elemOfsX,y_co)
	{
		msg_div=GetObject(idstring);
		image=GetObject(engine+'inimage');
		msg_div.style.left = (elemOfsX + "px");
		msg_div.style.top = (y_co + "px");
		msg_div.style.display="block";
		msg_div.style.position='absolute';
		image.style.display="none";
		if(direction=='down')
		{
			if(eval('window.height'+idstring)==0)
				eval('window.loadimage'+idstring +'=window_obj.setInterval("ShowInlineAd(\''+idstring+'\',\''+elemOfsX+'\',\''+y_co+'\')",20);');
			eval('window.height'+idstring+'+=5;');
			if(eval('window.height'+idstring) <= inlineadoffsetheight+5)
				msg_div.style.height = eval('window.height'+idstring)+"px";
			else	
			{
				// AddInlineEventHandlers(msg_div,  new Array("onmouseover","onmouseout"),  new Array(Show, Hide) );							
				eval('window_obj.clearInterval(window.loadimage'+idstring+');');
				eval('window.loadimage'+idstring+'=null;');
			}
			//alert(msg_div.style.height);
		}
		else
		{
			if(eval('window.height'+idstring)==0)
			{
				s=y_co+inlineadoffsetheight-5;
				// msg_div.style.top=s+'px'; *********************************************
				eval('window.loadimage'+idstring +'=window_obj.setInterval("ShowInlineAd(\''+idstring+'\',\''+elemOfsX+'\',\''+y_co+'\')",20);');
			}
			eval('window.height'+idstring+'+=5;');
			if(eval('window.height'+idstring) <= inlineadoffsetheight+5)
			{
				msg_div.style.height =eval('window.height'+idstring)+"px";
				temp=eval(y_co+'+'+inlineadoffsetheight+'-'+eval('window.height'+idstring));
				//  msg_div.style.top=temp+'px';*****************************************************
			}
			else	
			{
				// AddInlineEventHandlers(msg_div,  new Array("onmouseover","onmouseout"),  new Array(Show, Hide) );		
				eval('window_obj.clearInterval(window.loadimage'+idstring+');');
				eval('window.loadimage'+idstring+'=null;');
			}
		}
	}
	

	function InlineHideDelay(event)
	{
		if (!event) var event = window.event;
		var hEv = event.srcElement ? event.srcElement : event.target;
		idstring=hEv.id;
		idstring=idstring.replace('inlinex','inline');
		eval('window.tmr'+idstring +'=window_obj.setTimeout("InlineHide(\''+idstring+'\')",1500);');
	}
	
	function InlineClick(event)
	{
		if (!event) var event = window.event;
		clTarg = event.srcElement ? event.srcElement : event.target;
		idstring=clTarg.id;
		idstring=idstring.replace('inlinex','inline');
		InlineHide(idstring);
	}
	
	
	
	var hash_old="";
	var hash_new=window.location.hash;
	
	setInterval(function()
	{
		hash_old=hash_new;
		hash_new=window.location.hash;
		//alert(hash_new.substr(1,7));
		if(hash_new.substr(1,7).equals("inouts_"))
		{
			InlineHide(engine+"inline"+hash_new.substr(8))
			window.location.hash=hash_old;
		}
	}, 100);
	
	//alert(1);	


	if ( document.addEventListener ) // Mozilla, Opera and webkit nightlies currently support this event
	{
		// Use the handy event callback
		document.addEventListener( "DOMContentLoaded", function(){
		if (!alreadyrunflag){alreadyrunflag=1; startupInline(); }
		}, false );
	
	}
	else if ( document.attachEvent )  // If IE event model is used
	{
        // ensure firing before onload, maybe late but safe also for iframes
        document.attachEvent("onreadystatechange", function()
		{
                if ( document.readyState === "complete" ) 
				{
                        document.detachEvent( "onreadystatechange", arguments.callee );
						if (!alreadyrunflag){alreadyrunflag=1; startupInline(); }      
                }
        });

        // If IE and not an iframe, continually check to see if the document is ready
        if ( document.documentElement.doScroll && window == window.top ) 
		(function()
		{
			if ( alreadyrunflag )
				return;
			try 
			{
					// If IE is used, use the trick by Diego Perini
					// http://javascript.nwbox.com/IEContentLoaded/
					document.documentElement.doScroll("left");
			} 
			catch( error ) 
			{
					setTimeout( arguments.callee, 0 );
					return;
			}

			// and execute any waiting functions
			if (!alreadyrunflag){alreadyrunflag=1; startupInline(); }
		})();
	}
	
	window.onload=function()
	{
		setTimeout("if (!alreadyrunflag){ alreadyrunflag=1;startupInline()}", 0)
	}

	<?php
	//		echo  $inlineblock;
	//	exit(0);			
									
				
}					
							

	
$ret = ob_get_contents();	

if($GLOBALS['cache_timeout']>0 && !$cache->is_cached() ) 
{
$cache_template='<?php
ob_start();


$pub_traffic_analysis="'.$pub_traffic_analysis.'";
$pid="'.$pid.'";




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