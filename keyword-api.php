<?php
ob_start();
header('Content-Type: text/xml; charset='.$ad_display_char_encoding);
include("extended-config.inc.php");
include($GLOBALS['admin_folder']."/config.inc.php");
$xml_start='<?xml version="1.0" encoding="'.$ad_display_char_encoding.'"?><keywords>';
$xml_end='</keywords>';


$key=$_GET['key'];
if($xml_auth_code!=$key)
{
echo $xml_start.$xml_end;
exit(0);
}
else
{
	$flagtime=$_GET['flagtime'];
	$limit=$_GET['limit'];
	phpSafe($flagtime);
	phpSafe($limit);
	if($limit=="")
	{
		$lim="";
	}
	else
	{
		$lim="LIMIT 0,$limit";
	}

	$currtemp=time();
if($flagtime=="week")  //last 14 days
{
	$time=mktime(0,0,0,date("m",$currtemp),date("d",$currtemp)-13,date("Y",$currtemp));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",$currtemp),date("d",$currtemp),date("y",$currtemp));
$beg_time=$time;	
}
elseif($flagtime=="month")   //Last 30 days
{
	

$time=mktime(0,0,0,date("m",$currtemp),date("d",$currtemp)-29,date("Y",$currtemp)); //mktime(0,0,0,date("m",$temp),1,date("y",$temp));
	$end_time=mktime(0,0,0,date("m",$currtemp)+1,date("d",$currtemp),date("y",$currtemp));
$beg_time=$time;
	
}
else
{
echo $xml_start.$xml_end;
exit(0);		
}
//echo "select a.keyid,b.keyword,COALESCE(sum(a.impressions),0),sum(a.click_count),sum(a.money_spent) from keyword_daily_statistics a join system_keywords b on b.id=a.keyid  where  a.time>=$beg_time group by a.keyid order by sum(a.money_spent) DESC $lim";
	$temp=mysql_query("select a.keyid,b.keyword,COALESCE(sum(a.impressions),0),sum(a.click_count),sum(a.money_spent) from keyword_daily_statistics a join system_keywords b on b.id=a.keyid  where  a.time>=$beg_time group by a.keyid order by sum(a.money_spent) DESC $lim");

	while($ans=mysql_fetch_row($temp))
	{
		 $textkeyblock.="\n".'<keyword>';	
				    
		$textkeyblock.="\n".'<text><![CDATA['.$ans[1].']]></text>';
		$textkeyblock.="\n".'<impressions><![CDATA['.$ans[2].']]></impressions>';
		$textkeyblock.="\n".'<clicks><![CDATA['.$ans[3].']]></clicks>';
		$textkeyblock.="\n".'<moneyspent><![CDATA['.numberformat($ans[4]).']]></moneyspent>';
       		           $textkeyblock.="\n".'</keyword>';
	
	
	}
}	
	echo $xml_start.$textkeyblock."\n".$xml_end;


?>