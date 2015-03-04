<?php


include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
include_once("functions.inc.php");

include("geo/geoip.inc");

$public_ip=getUserIP();

$gi = geoip_open("geo/GeoIP.dat",GEOIP_STANDARD);
$record = geoip_country_code_by_addr($gi, $public_ip);

$enc_ip=md5($public_ip);

$original_url_string=$_GET['original_url_string'];
$serverid=$_GET['serverid'];
$direct_status=$_GET['direct_status'];
$vid=$_GET['vid'];
$send_direct_status=$_GET['send_direct_status'];
$adunitid=$_GET['ad_uid'];





$pid=$mysql->echo_one("select pid from ppc_custom_ad_block where id='$adunitid'");
$rtl_lang_id=$mysql->echo_one("select id from adserver_languages where code='ar'");

if($pid > 0)
$pub_traffic_analysis=$mysql->echo_one("select traffic_analysis from ppc_publishers where uid='$pid'");
else 
$pub_traffic_analysis=$traffic_analysis;



includeClass("Cache");

$cache = new Cache($GLOBALS['cache_timeout'],$GLOBALS['cache_folder']);
$uri = $_SERVER['REQUEST_URI'];

$uri =$uri.$record;

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


$eng_name="a-".md5($ppc_engine_name)."-";

//$public_ip=getUserIP();
//$enc_ip=md5($public_ip);


$adid=$_GET['adid'];
$kid=$_GET['kid'];
$lastAccTime=$_GET['lastAccTime'];

$cont_type=$ad_display_char_encoding;

$result=mysql_query("select pad.*,cad.*,pad.credit_text as padtext,cad.credit_text as ctext,cad.title_color as tcolor,cad.desc_color as dcolor,cad.url_color as ucolor from ppc_ad_block pad,ppc_custom_ad_block cad where  pad.id=cad.bid and cad.id='$adunitid'");
//echo "select pad.*,cad.*,site.* from ppc_ad_block pad,ppc_custom_ad_block cad left join publisher_site site on site.id=cad.sid where  pad.id=cad.bid and cad.id='$adunitid'";

//exit(0);

echo mysql_error();

$adunitrow=mysql_fetch_array($result);







$pid=$adunitrow['pid'];


if($pid >0)
$pub_traffic_analysis= $mysql->echo_one("select traffic_analysis from ppc_publishers where uid ='$pid'");
else
$pub_traffic_analysis=$traffic_analysis;
	

if($adunitrow['pid']>0)
	{
			$target="ppc-publisher-ad-click.php?pid=".$adunitrow['pid']."&";
			
$crstr=$adunitrow['padtext'];

			
	}		
			
			else
			{
			$target="ppc-ad-click.php?";
 $crstr=$adunitrow['ctext'];
			
			}
 //$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where id=\"".$crstr."\"");
//exit(0);

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
		$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		else if($ct==1)
		{
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
        $ctext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';
		
		}
		
	}
	else
	{
	
	
	    if($ct==0)
	    {
		$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($ctext=='')
		$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		}
		else if($ct==1)
		{
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($ctimage=='')
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		
		$ctext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';
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
					$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				else
					$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
					
		}
		else if($ct==1)
		{
		
		   if($clan=='en')
					$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		   else
					$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
					
			
			$ctext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
					
		
		}			
					
				
		}
		else
		{
		
		    if($ct==0)
	        {
			$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
			if($ctext=='')
				$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				
			}
			else if($ct==1)
		   {
			
			$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
			if($ctimage=='')
			$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			
			
			$ctext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
			
		   }
				
		}
		if($ctext=='')
		{
		     if($ct==0)
			 $ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			 else if($ct==1)
		     {
			 $ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			 $ctext='<img src="'.$original_url_string.'credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
			 }
			 
		}	 
			
		
}








/*
if($adunitrow['adlang']==0)  //anylanguages
{
	if($clan=='en')
	{
		$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
	}
	else
	{
	//echo "select credit from ppc_publisher_credits where parent_id=".$res['ctext']." and language_id='$lanid'";
		$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($ctext=='')
			$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
	}
}
else
{
		
		if($adunitrow['adlang']==$lanid )
		{
				if($clan=='en')
					$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				else
				{
					 $ctext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
				}
		}
		else
		{
			$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$adunitrow['adlang']."'");
			if($ctext=='')
				$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		}
		if($ctext=='')
			$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			
		
}
 
 */
 
 
 
 
 
 
 
 
 
 
if($pid==0)
$credittextpreview="<tr class=\"{$eng_name}inout-inline-credit\" width=100%><td  ><a target=\"_blank\" href=\"$original_url_string"."index.php\" {ONCLICK}>$ctext</a></td></tr>";
else
$credittextpreview="<tr class=\"{$eng_name}inout-inline-credit\" width=100%><td  ><a target=\"_blank\" href=\"$original_url_string"."index.php?r=$pid\" {ONCLICK}>$ctext</a></td></tr>";


 $adunittype=$adunitrow['ad_type'];


		
			
			

$catalogdimension=mysql_query("select * from catalog_dimension limit 0,1");
 $catalogcount=mysql_num_rows( $catalogdimension);
 if($catalogcount>0)
 {
 $catalog=mysql_fetch_row($catalogdimension);
 
 }
 
 $res=mysql_query("select * from ppc_credittext_bordercolor where id=\"".$adunitrow['credit_color']."\"");
$r1=mysql_fetch_row($res);


?><html><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo $cont_type; ?>" /><title></title>
<style type="text/css">

body
{
margin:0px;
padding:0px;
style=background-color:transparent;
<?php
if($adunitrow['adlang']!=0)
{
?>
dir:ltr;
	<?php } ?>
}


.<?php echo $eng_name; ?>top, .<?php echo $eng_name; ?>bottom {display:block; background:transparent; font-size:1px;width:<?php  echo $adunitrow['width']; ?>;}
.<?php echo $eng_name; ?>tb1, .<?php echo $eng_name; ?>tb2, .<?php echo $eng_name; ?>tb3, .<?php echo $eng_name; ?>tb4 {display:block; overflow:hidden;}
.<?php echo $eng_name; ?>tb1, .<?php echo $eng_name; ?>tb2, .<?php echo $eng_name; ?>tb3 {height:1px;}
.<?php echo $eng_name; ?>tb2, .<?php echo $eng_name; ?>tb3, .<?php echo $eng_name; ?>tb4 {background:<?php if($adunitrow['credit_text_positioning']==1 && $crstr!=0) echo $r1[2]; else echo $adunitrow['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>}
.<?php echo $eng_name; ?>tb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.<?php echo $eng_name; ?>tb2 {margin:0 3px; border-width:0 2px;}
.<?php echo $eng_name; ?>tb3 {margin:0 2px;}
.<?php echo $eng_name; ?>tb4 {height:2px; margin:0 1px;}
.<?php echo $eng_name; ?>bb1, .<?php echo $eng_name; ?>bb2, .<?php echo $eng_name; ?>bb3, .<?php echo $eng_name; ?>bb4 {display:block; overflow:hidden;}
.<?php echo $eng_name; ?>bb1, .<?php echo $eng_name; ?>bb2, .<?php echo $eng_name; ?>bb3 {height:1px;}
.<?php echo $eng_name; ?>bb2, .<?php echo $eng_name; ?>bb3, .<?php echo $eng_name; ?>bb4 {background:<?php if($adunitrow['credit_text_positioning']==0 && $crstr !=0) echo $r1[2];  else echo  $adunitrow['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>}
.<?php echo $eng_name; ?>bb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.<?php echo $eng_name; ?>bb2 {margin:0 3px; border-width:0 2px;}
.<?php echo $eng_name; ?>bb3 {margin:0 2px;}
.<?php echo $eng_name; ?>bb4 {height:1px; margin:0 1px;}
.<?php echo $eng_name; ?>parenttable {display:block; background:#FFFFFF; border-style:solid; border-color: <?php echo $r1[2]; ?>; border-width:0 1px; padding: 0px;margin:0 0px;}




.<?php echo $eng_name; ?>inout-inline-innertable

{

padding:0px;

 padding-left:0px;

margin:0px 0px;

table-layout:fixed;

overflow:hidden;

line-height: <?php echo $adunitrow['line_height']; ?>px;



	border: 1px solid  <?php echo $r1[2]; ?>;
	<?php 	if($bordertype==0 && $adunitrow['credit_text_positioning']==1 && $crstr!=0) { ?>
	
		border-bottom:0px;
		<?php } 
		 if($bordertype==0 && $adunitrow['credit_text_positioning']==0 && $crstr!=0) { ?>
		border-top:0px;
		<?php } 
		if($crstr==0 && $bordertype==0)
		{
		?>
		border-bottom:0px;
		border-top:0px;
		<?php } ?>
background:<?php if($adunittype==2) echo "#FFFFFF";  else echo  $adunitrow['bg_color']; ?>;





}
.<?php echo $eng_name; ?>inout-inline-innertable td
{
vertical-align:middle;
padding:3px;
}

	.<?php echo $eng_name; ?>inout-title a:link,.<?php echo $eng_name; ?>inout-title a:visited,.<?php echo $eng_name; ?>inout-title a:hover,.<?php echo $eng_name; ?>inout-title a:active,.<?php echo $eng_name; ?>inout-title a:focus
		{
		font-family:<?php echo $adunitrow['title_font'];?>;
		font-size:<?php echo $adunitrow['title_size'];?>;
		color:<?php echo $adunitrow['title_color'];?>;
		
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
		


		

		

		.<?php echo $eng_name; ?>inout-inner-desc 

		{

		font-family:<?php echo $adunitrow['desc_font'];?>;

		font-size:<?php echo $adunitrow['desc_size'];?>px;

		color:<?php echo $adunitrow['desc_color'];?>;

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
		margin:0px;

		}

		

		

		.<?php echo $eng_name; ?>inout-inline-url a:link,.<?php echo $eng_name; ?>inout-inline-url a:visited,.<?php echo $eng_name; ?>inout-inline-url a:hover,.<?php echo $eng_name; ?>inout-inline-url a:active,.<?php echo $eng_name; ?>inout-inline-url a:focus

		{

		font-family:<?php echo $adunitrow['url_font'];?>;

		font-size:<?php echo $adunitrow['url_size'];?>px;

		color:<?php echo $adunitrow['url_color'];?>;

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
		margin:0px;
		}





.<?php echo $eng_name; ?>inout-inline-credit

{





	
	


width:<?php echo $adunitrow['width']; ?>px ;

<?php
if($ct ==0)
{
?>
background-color:<?php echo $r1[2]; ?>;

<?php
 } ?>

margin:0px;

white-space:nowrap;

padding:0px 0px;

<?php

if($adunitrow['credit_text_alignment']==0)

$t_align="left";

else

$t_align="right";	

?>



text-align:<?php echo $t_align; ?>;
table-layout:fixed;
overflow:hidden;


}

.<?php echo $eng_name; ?>inout-inline-credit td
{
/*border:1px solid red;*/
height:15px;

padding:0px;
margin:0px;


}


.<?php echo $eng_name; ?>inout-inline-credit a:link,.<?php echo $eng_name; ?>inout-inline-credit a:visited,.<?php echo $eng_name; ?>inout-inline-credit a:hover,.<?php echo $eng_name; ?>inout-inline-credit a:active,.<?php echo $eng_name; ?>inout-inline-credit a:focus

{
padding:0px;
margin:0px;
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



</style>

</head>
<body>



<?php

$impression_id_str="";
$impression_id_str_pub="";
function updateAgeingAndImpression ($mysql,$row,$ad_ageing_factor,$adunitid,$vid,$public_ip,$fraud_time_interval,$record,$pid,$kid,$lastAccTime,$serverid)
		{
		
	
            global $mysql_server_type;
			global $impression_id_str;
			global $impression_id_str_pub;
			
			
			
			$ini_error_status=ini_get('error_reporting');
		//	$lastAccTime=0; *********************************************************************
			$uid=0;
	
			$uid=$row[5];


			//$lastAccTime=$mysql->echo_one("Select time from ppc_keywords where id='$kid'");


			$incrAccTime=($ad_ageing_factor/3)*24*60*60;
			$lastAccTime=$lastAccTime+$incrAccTime;

			$currTime=time();
			//$currTime=mktime(date("H",$currTime)+1,0,0,date("m",$currTime),date("d",$currTime),date("y",$currTime));//new

			//if($lastAccTime>$currTime)
				
				
if($incrAccTime == 0)
$lastAccTime=$currTime;
				
			$cid="";
			$cid=$kid;
		
		
		    if($GLOBALS['cache_timeout']==0)
	        mysql_query("update view_keywords set time='$lastAccTime' where id='$cid'");		
		
			mysql_query("update ppc_keywords set time='$lastAccTime' where id='$cid'");		
            $currTime=mktime(date("H",$currTime)+1,0,0,date("m",$currTime),date("d",$currTime),date("y",$currTime));//new
			
			if($ini_error_status!=0)
			{
			echo mysql_error();
			}


			$today=$currTime-($fraud_time_interval*60*60);
		
		
		
		if($mysql_server_type==1)
		mysql_query("insert into advertiser_impression_hourly_master(`id`,`uid`,`aid`,`kid`,`time`,`cnt`,`server_id`) value('0','$uid','$row[0]','$cid','".$currTime."','1','$serverid')");
		else if($mysql_server_type==2)
		mysql_query("insert into advertiser_impression_hourly_slave(`id`,`uid`,`aid`,`kid`,`time`,`cnt`,`server_id`) value('0','$uid','$row[0]','$cid','".$currTime."','1','$serverid')");
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
	

	
  $textsql="SELECT a.id, a.link, a.title, a.summary,a.displayurl, a.uid,a.bannersize,a.contenttype,a.hardcodelinks,a.adlang  FROM  ppc_ads a where id='$adid'";

  $resultsql=$textsql;
 


$ad_cnt=0;
$adresult=mysql_query( $resultsql);
$ad_cnt=mysql_num_rows($adresult);

//$inlinecount=mysql_num_rows($adresult);








$adrow=mysql_fetch_row($adresult);


if($maintenance_mode['enabled']!=1)
{
updateAgeingAndImpression ($mysql,$adrow,$ad_ageing_factor,$adunitid,$vid,$public_ip,$fraud_time_interval,$record,$adunitrow['pid'],$kid,$lastAccTime,$serverid);


}
//updateAgeingAndImpression ($mysql,$adrow,$ad_ageing_factor,$adunitid,$vid_row[0],$public_ip,$fraud_time_interval,$record,$adunitrow[46]);          







	
	$blockheight= $adunitrow['height'];

	
 		//$catalog_width=$inline_width;
		//$catalog_height=$inline_height;

	$currentadcount=0;		
	$currentcatalogadcount=0;
	
	
						$ad_display_url=$adrow[4];
						if($ad_display_url=="")
						$ad_display_url=$adrow[1];
//	echo $adunittype;
 if($adunittype==6 )
 {
	
	
		
						
						
				if($bordertype==0) //rounded corner uses 10px of block height, 5 at top and 5 at bottom
				{
					$inlineblock.= '<b class="'.$eng_name.'top"><b class="'.$eng_name.'tb1"></b><b class="'.$eng_name.'tb2"></b><b class="'.$eng_name.'tb3"></b><b class="'.$eng_name.'tb4"></b></b>';
					$blockheight= $adunitrow['height']-10;
				}
	
						$inlineblock.= '<table height="'. ($blockheight-16).'px"  border="0" cellpadding="0" cellspacing="0" class="'.$eng_name.'inout-inline-innertable" width="'.$adunitrow['width'].'px" ';
						if($rtl_lang_id==$adrow[9])
						{
							$inlineblock.= 'style="direction:rtl;"';
						}
						$inlineblock.='>';
						if($adunitrow['credit_text_positioning']==1 && $crstr !=0)
						$inlineblock.= $credittextpreview;	
			

						$inlineblock.='<tr><td ><span class="'.$eng_name.'inout-title"><a href="'.$original_url_string.$target.'id='.$adrow[0].'&kid='.$kid.'&bid='.$adunitid.'&vid='.$vid.'&vip={ENC_IP}&send_direct_status='.$send_direct_status.'" 		target="_blank" onclick="hideimage('.$adrow[0].')">'.$adrow[2].'</a></span>';
						if($adunitrow['text_ad_type']!=2)// not title only
						{
						$inlineblock.='<br><span class="'.$eng_name.'inout-inner-desc">'.$adrow[3].'</span>';
						}
						if($adunitrow['text_ad_type']==1) // title/desc/display url
						{
						$inlineblock.='<br><span class="'.$eng_name.'inout-inline-url"><a href="'.$original_url_string.$target.'id='.$adrow[0].'&kid='.$kid.'&bid='.$adunitid.'&vid='.$vid.'&vip={ENC_IP}&send_direct_status='.$send_direct_status.'" 		target="_blank" onclick="hideimage('.$adrow[0].')">'.$ad_display_url.'</a>                                        </span>';
						}
						$inlineblock.='</td></tr>';
						
						if($adunitrow['credit_text_positioning']==0 && $crstr !=0)
						$inlineblock.= $credittextpreview;	
					
						$inlineblock.= '</table>';
					
					
					if($bordertype==0)
					{
					$inlineblock.= '<b class="'.$eng_name.'bottom"><b class="'.$eng_name.'bb4"></b><b class="'.$eng_name.'bb3"></b><b class="'.$eng_name.'bb2"></b><b class="'.$eng_name.'bb1"></b></b>';
					}
			
						
			}
								
					//	$inlineblock.='</div>';			
					//	$inlineblock= str_replace("{ENC_IP}",$enc_ip,$inlineblock);


					
					//	$i++;
		

			
					
					
					
					
					

					
										
						
		     
					
				
					
				//$funct_string="onclick=\"hideimage(".$adrow[0].")\"";
				//$inlineblock= str_replace("{ONCLICK}",$funct_string,$inlineblock);
				//$inlineblock= str_replace("{ENC_IP}",$enc_ip,$inlineblock);
			
		
	
		
		

if($adunittype==7 )
 {
?>
<script  language="javascript" type="text/javascript" src="<?php echo $server_dir; ?>swfobject.js"></script>			
<?php 	

 	 $catalog_width=$catalog[2];
					 $catalog_height=$catalog[1];
		




	//	if($adunitrow[5]==1)//  This code is vertical
		//	{
	if($bordertype==0) //rounded corner uses 10px of block height, 5 at top and 5 at bottom
				{
					$inlineblock.= '<b class="'.$eng_name.'top"><b class="'.$eng_name.'tb1"></b><b class="'.$eng_name.'tb2"></b><b class="'.$eng_name.'tb3"></b><b class="'.$eng_name.'tb4"></b></b>';
					$blockheight= $adunitrow['height']-10;
				}
		


				$inlineblock.= '<table height="'.($blockheight-16).'px" border="0" cellpadding="0" cellspacing="0" class="'.$eng_name.'inout-inline-innertable" width="'.$adunitrow['width'].'px"  >';

	if($adunitrow['credit_text_positioning']==1 && $crstr!=0)
				$inlineblock.= $credittextpreview;	
			


					$inlineblock.='<tr height="100%"><td >';
						$inlineblock.='<table width="100%" cellpadding="0" height="100%" cellspacing="5" border=0 style="line-height:'.$adunitrow['line_height'].'px;table-layout:fixed;overflow:hidden;';
						if($rtl_lang_id==$adrow[9])
						{
						$inlineblock.='direction:rtl;';
						}
						$inlineblock.='">';
						$inlineblock.='<tr><td align="center"  valign="top" style="width:'.$catalog_width.'px;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;">';
  						
						
						
						
if($adrow[7] != "swf")
{					
						
						$inlineblock.='<a target="_blank" href="'.$original_url_string.$target.'id='.$adrow[0].'&kid='.$kid.'&bid='.$adunitid.'&vid='.$vid.'&vip={ENC_IP}&send_direct_status='.$send_direct_status.'" onclick="hideimage('.$adrow[0].')"><img  height="'.$catalog_height.'"  width="'.$catalog_width.'"  src="'.$original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2].'" border="0"></a> </div></td>';
						
						
						if($rtl_lang_id==$adrow[9])
						$inlineblock.='<td valign="top">';
						else
						$inlineblock.='<td align="left" valign="top">';
						
						
						
}
else
{

if($adunitrow['pid']>0)
$url_inline_data=$original_url_string."ppc-publisher-ad-click.php?Flsids=$adrow[0]&kid=".$kid."&pid=".$adunitrow['pid']."&bid=$adunitid&vid=".$vid."&vip={ENC_IP}&direct_status=".$send_direct_status;
else
$url_inline_data=$original_url_string."ppc-ad-click.php?Flsids=$adrow[0]&kid=".$kid."&bid=$adunitid&vid=".$vid."&vip={ENC_IP}&direct_status=".$send_direct_status;

		
$url_inline_data=str_replace('&','__',$url_inline_data);






//$alinkdemo=str_replace('&','__',$alinkdemo);

$alinkdemo="javascript:hideimagenew(".$adrow[0].")";
				
				
$strHardLinks="";				
if($adrow[8] >0)
{
for($i=0;$i<$adrow[8];$i++)
{
$strHardLinks.="flashvars_".$adrow[0].".alink".($i+1)."='".$alinkdemo."';";
$strHardLinks.="flashvars_".$adrow[0].".atar".($i+1)."='_self';";
}

}
				
	


?>
	
<script type="text/javascript">
var flashvars_<?php echo $adrow[0]; ?> = {};
var params_<?php echo $adrow[0]; ?> = {};
var attributes_<?php echo $adrow[0]; ?> = {};
flashvars_<?php echo $adrow[0]; ?>.clickTag = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTAG = "<?php  echo $alinkdemo;?>";
flashvars_<?php echo $adrow[0]; ?>.clickTARGET = "_self";
<?php echo $strHardLinks; ?>

params_<?php echo $adrow[0]; ?>.wmode="transparent";
		  
	      swfobject.embedSWF("<?php echo $original_url_string.$GLOBALS['banners_folder'].'/'.$adrow[0].'/'.$adrow[2];?>", "myFlashDiv_<?php echo $adrow[0]; ?>", "<?php  echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $adrow[0]; ?>,params_<?php echo $adrow[0]; ?>,attributes_<?php echo $adrow[0]; ?>);
</script>
		

	<?php	  
	$inlineblock.='<div id="myFlashDiv_'.$adrow[0].'"></div>';
	$inlineblock.='</div></td>';



                        if($rtl_lang_id==$adrow[9])
						$inlineblock.='<td valign="top">';
						else
						$inlineblock.='<td align="left" valign="top">';
















}						
						
						
						
						
						
						
						
						$inlineblock.='<span class="'.$eng_name.'inout-title"><a href="'.$original_url_string.$target.'id='.$adrow[0].'&kid='.$kid.'&bid='.$adunitid.'&vid='.$vid.'&vip={ENC_IP}&send_direct_status='.$send_direct_status.'" 		target="_blank" onclick="hideimage('.$adrow[0].')">'.$adrow[4].'</a></span><br>';
						$inlineblock.='<span class="'.$eng_name.'inout-inner-desc">'.$adrow[3].'</span></td></tr></table>';
						$inlineblock.='</td></tr>';
						if($adunitrow['credit_text_positioning']==0 && $crstr !=0)
										$inlineblock.= $credittextpreview;	

						
									$inlineblock.='</table>';
										if($bordertype==0)
		{
		$inlineblock.= '<b class="'.$eng_name.'bottom"><b class="'.$eng_name.'bb4"></b><b class="'.$eng_name.'bb3"></b><b class="'.$eng_name.'bb2"></b><b class="'.$eng_name.'bb1"></b></b>';
		}

										

						

		        //	$inlineblock.='</div>';			
				//	$i++;

				}	

				$funct_string="onclick=\"hideimage(".$adrow[0].")\"";
				$inlineblock= str_replace("{ONCLICK}",$funct_string,$inlineblock);
				$inlineblock= str_replace("{ENC_IP}",$enc_ip,$inlineblock);

		
	
		
echo $inlineblock;




?>
<script language="javascript" type="text/javascript">
function hideimage(id)
{
parent.window.location.hash ="inouts_"+id;
}

function hideimagenew(id)
{
parent.window.location.hash ="inouts_"+id;
parent.window.open('<?php echo $url_inline_data; ?>','popup');

}



</script>

<?php

				



	
$ret = ob_get_contents();	

if( !$cache->is_cached() ) 
{
$cache_template='<?php
ob_start();
$impression_str="'.$impression_id_str.'";
$impression_str_pub="'.$impression_id_str_pub.'";




$pub_traffic_analysis="'.$pub_traffic_analysis.'";
$ad_cnt="'.$ad_cnt.'";
$pid="'.$pid.'";



$u_flag=$u;

//$u_flag=$_GET[\'u\'];




if($pub_traffic_analysis==1)
{

 
 if( $u_flag=="t")
 {
 if($direct_status==1)
		{
		if($mysql_server_type==1)      
		mysql_query("update publisher_daily_visits_statistics_master set direct_impressions=direct_impressions+$ad_cnt where id=\'$vid\'");
		else if($mysql_server_type==2)
		mysql_query("update publisher_daily_visits_statistics_slave set direct_impressions=direct_impressions+$ad_cnt where id=\'$vid\'");
		}
		else
		{
		if($mysql_server_type==1)     
		mysql_query("update publisher_daily_visits_statistics_master set referred_impressions=referred_impressions+$ad_cnt where id=\'$vid\'");
        else if($mysql_server_type==2)
		mysql_query("update publisher_daily_visits_statistics_slave set referred_impressions=referred_impressions+$ad_cnt where id=\'$vid\'");
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
</body>
</html>