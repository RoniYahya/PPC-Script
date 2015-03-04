<?php
/*--------------------------------------------------+
 |													 |
 | Copyright � 2006 http://www.inoutscripts.com/  
 | All Rights Reserved.								 |
 | Email: contact@inoutscripts.com                    |
 |                                                    |
 +---------------------------------------------------*/
?><?php



function includeClass($path)
{
	//include("extended-config.inc.php");
	//echo $classpath."class.".$path.".php";
	include($GLOBALS['classes_folder']."/class.".$path.".php");
}




function loadsettings($type)
{

	$resultssettings=mysql_query("select name,value from ppc_settings where section='$type'");

	while($settingsrow=mysql_fetch_row($resultssettings))
	{
		// eval('$'.$settingsrow[0].'=\''.addslashes( $settingsrow[1]).'\';');
	 $GLOBALS[$settingsrow[0]]=$settingsrow[1];
	 // echo $$settingsrow[0];
	}
	

}




function getSuggestedValue($keyword,$id,$min_click_value,$revenue_booster,$revenue_boost_level,$addata)
{





if($addata==0)
$adtypestring=" AND a.adtype='0'";
else if($addata==1)
$adtypestring=" AND a.adtype='1'";
else if($addata==2)
$adtypestring=" AND a.adtype='2'";








	if($revenue_booster==0)
	{
		$maxvalue=$min_click_value;
		//echo "select MAX(maxcv) from ppc_keywords where keyword='$keyword' AND id<>'$id'";
	//	$resultkwd=mysql_query("select MAX(maxcv) from ppc_keywords where keyword='$keyword' AND id<>'$id'");
			
		$resultkwd=mysql_query("SELECT round( MAX( maxcv ) , 2 ) FROM ppc_keywords AS k INNER JOIN ppc_ads AS a ON a.id = k.aid WHERE k.keyword='$keyword'" .$adtypestring." AND k.id<>'$id'");
		
		
		if(mysql_num_rows($resultkwd)!=0)
		{
			$rownew=mysql_fetch_row($resultkwd);
			//echo $rownew[0];
			$maxvalue=round($rownew[0],2);

			if($maxvalue==""){
				//echo $min_click_value;
				$maxvalue=$min_click_value;
				return $maxvalue;
			}
		}
		//echo $maxvalue;
		return $maxvalue;
	}
	else
	{

		$tmpmaxvalue=$min_click_value;

		if($revenue_boost_level==3)
		$tmpmaxvalue=$tmpmaxvalue+.05;

		if($revenue_boost_level==2)
		$tmpmaxvalue=$tmpmaxvalue+.03;

		if($revenue_boost_level==1)
		$tmpmaxvalue=$tmpmaxvalue+.01;


		//echo $tmpmaxvalue;
	//	$resultkwd=mysql_query("select MAX(maxcv) from ppc_keywords where keyword='$keyword' and id<>'$id'");
		
		
		$resultkwd=mysql_query("SELECT round( MAX( maxcv ) , 2 ) FROM ppc_keywords AS k INNER JOIN ppc_ads AS a ON a.id = k.aid WHERE k.keyword='$keyword'" .$adtypestring." AND k.id<>'$id'");
		
		if(mysql_num_rows($resultkwd)!=0)
		{
			$rownew=mysql_fetch_row($resultkwd);
			$maxvalue=round($rownew[0],2);

			if($maxvalue=="")
			{
				$maxvalue=$tmpmaxvalue;
			}
			else
			{
					
				if($revenue_boost_level==3)
				$maxvalue=$maxvalue+.05;

				if($revenue_boost_level==2)
				$maxvalue=$maxvalue+.03;

				if($revenue_boost_level==1)
				$maxvalue=$maxvalue+.01;

					
			}

			if($maxvalue < $tmpmaxvalue)
			$maxvalue=$tmpmaxvalue;


		}
		return $maxvalue;
	}


}


 function RemoveCharacters($urlname)
{

$hostname=$urlname;
$inhostname=substr($hostname,0,5);
if($inhostname=="http:")
{
$hostname=substr($hostname,7);

}

else if($inhostname=="https")
{
$hostname=substr($hostname,8);

}

	$hostname1=substr($hostname,0,4);
	if($hostname1=="www.")
	$hostname=substr($hostname,4);
	
	
	
	
	
	$hostnamemain1=substr($hostname,-1);
	if($hostnamemain1=="/")
	$hostname=substr($hostname,0,strlen($hostname)-1);


	
	return $hostname;


}



function phpSafe(&$target)
{
	global $ad_display_char_encoding;
	$target=trim($target);
	if(strcasecmp($ad_display_char_encoding,"UTF-8")==0)
	$target = htmlspecialchars($target, ENT_QUOTES, "UTF-8");
	else
	$target = htmlspecialchars($target,ENT_QUOTES);

	if(!get_magic_quotes_gpc())
	$target=mysql_real_escape_string($target);
}

function phpSafeUrl(&$target)
{
	$target=trim($target);

	$target=str_replace('<','&lt;',$target);
	$target=str_replace('>','&gt;',$target);

	if(!get_magic_quotes_gpc())
	$target=mysql_real_escape_string($target);
}

function htmlSafe($target)
{
	$target=trim($target);
	global $ad_display_char_encoding;
	if(strcasecmp($ad_display_char_encoding,"UTF-8")==0)
	$target = htmlspecialchars($target, ENT_QUOTES, "UTF-8");
	else
	$target = htmlspecialchars($target,ENT_QUOTES);
	return $target;
}


function removeGpcPrefixedSlashes(&$target)
{
	if(get_magic_quotes_gpc())
	$target=stripslashes($target);
}

function myAd($aid,$uid,$mysql)
{

	//echo "aid='$aid' AND uid='$uid'";
	//echo $mysql->total("ppc_ads","id='$aid' AND uid='$uid'");
	if($mysql->total("ppc_ads","id='$aid' AND uid='$uid'")!=0)
	return true;
	else
	return false;

}
function mySite($id,$uid,$mysql)
{

	//echo "aid='$aid' AND uid='$uid'";
	//echo $mysql->total("ppc_ads","id='$aid' AND uid='$uid'");
	if($mysql->total("ppc_restricted_sites","id='$id' AND uid='$uid'")!=0)
	return true;
	else
	return false;

}
function myAdUnit($id,$uid,$mysql)
{

	//echo "aid='$aid' AND uid='$uid'";
	//echo $mysql->total("ppc_ads","id='$aid' AND uid='$uid'");
	if($mysql->total("ppc_custom_ad_block","id='$id' AND pid='$uid'")!=0)
	return true;
	else
	return false;

}

function myKeyword($kid,$uid,$mysql)
{

	//echo "aid='$aid' AND uid='$uid'";
	//echo $mysql->total("ppc_ads","id='$aid' AND uid='$uid'");
	if($mysql->total("ppc_keywords","id='$kid' AND uid='$uid'")!=0)
	return true;
	else
	return false;

}



function xMail($to, $subj, $msg, $from="", $charset="UTF-8", $xtraheaders="")
{
	global $smtpmailer,$smtp_host,$smtp_port,$smtp_user,$smtp_pass,$smtp_secure,$ppc_engine_name;
 
	$flag_var=0;
	if($smtpmailer == 1)
	{
		if(phpversion()>=5)
		{
			
			$flag_var=1;
			if (!class_exists('phpmailer')) {
				require("PHPMailer/class.phpmailer.php");
			}
		}

	}

	if($flag_var==1)//SMTP MAIL
	{

			
				$mail = new PHPMailer(true);
				//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				$mail->IsSMTP();                                        // set mailer to use SMTP
				$mail->Host = $smtp_host; // specify SMTP mail server
				$mail->Port = $smtp_port; // specify SMTP Port
				$mail->SMTPAuth = true; // turn on SMTP authentication
				$mail->Username = $smtp_user; //Full SMTP username
				$mail->Password =$smtp_pass; //SMTP password
					
				//if($smtp_secure )
				$mail->SMTPSecure =$smtp_secure;                 // sets the prefix to the servier
					
					
				$mail->From = $from;
				$mail->FromName = $ppc_engine_name;
				
				$mail->CharSet=$charset;
					
				//$mail->Sender =$error_ret_mail;
				$mail->AddAddress($to);
			    $mail->AddReplyTo($mail->From, $mail->FromName);  
					
				//$mail->WordWrap = 50; //optional, you can delete this line
					
					
				$mail->IsHTML(false); //set email format to HTML
					
				$mail->Subject = $subj;
			    $mail->Body = $msg;  //html body
			    return $mail->Send();
	
		
		
		//*************************************
} // END OF SMTP MAIL
else
{
	$headers  = "";
	$headers .= "From: {$from}\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-Type: text/plain; charset=\"$charset\"\n";
	$headers .= "Content-Transfer-Encoding: 8bit\n";
	$headers .= $xtraheaders;

	$ret = mail ($to, $subj, $msg, $headers);
	return $ret;
}
}


function isValidEmail($email)
{
	$result = TRUE;
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
	{
		$result = FALSE;
	}
	return $result;
}
function checkSpace($mystring)
{

	$findme   = ' ';
	$pos = strpos($mystring, $findme);
	if ($pos === false)
	{
		return true;
	}
	else {
		return false;
	}
}

function isPositiveInteger($mystring)
{
	if($mystring<=0)
	return false;
	for ( $i = 0; $i < strlen($mystring); $i++)
	{
		$ch = $mystring{$i};
			
		if ($ch < "0" || $ch > "9")
		{
			return false;
		}
	}
	return true;
}

function isDomain($mystring)
{

	$domain=$mystring;
	if(strcasecmp(substr($mystring,0,7),"http://")==0)
	$domain = substr($mystring,7);
	if(strcasecmp(substr($mystring,0,8),"https://")==0)
	$domain = substr($mystring,8);


	$tld_list = array(
'com','net','org','biz','coop','info','museum','name',
'pro','edu','gov','int','mil','ac','ad','ae','af','ag',
'ai','al','am','an','ao','aq','ar','as','at','au','aw',
'az','ba','bb','bd','be','bf','bg','bh','bi','bj','bm',
'bn','bo','br','bs','bt','bv','bw','by','bz','ca','cc',
'cd','cf','cg','ch','ci','ck','cl','cm','cn','co','cr',
'cu','cv','cx','cy','cz','de','dj','dk','dm','do','dz',
'ec','ee','eg','eh','er','es','et','fi','fj','fk','fm',
'fo','fr','ga','gd','ge','gf','gg','gh','gi','gl','gm',
'gn','gp','gq','gr','gs','gt','gu','gv','gy','hk','hm',
'hn','hr','ht','hu','id','ie','il','im','in','io','iq',
'ir','is','it','je','jm','jo','jp','ke','kg','kh','ki',
'km','kn','kp','kr','kw','ky','kz','la','lb','lc','li',
'lk','lr','ls','lt','lu','lv','ly','ma','mc','md','mg',
'mh','mk','ml','mm','mn','mo','mp','mq','mr','ms','mt',
'mu','mv','mw','mx','my','mz','na','nc','ne','nf','ng',
'ni','nl','no','np','nr','nu','nz','om','pa','pe','pf',
'pg','ph','pk','pl','pm','pn','pr','ps','pt','pw','py',
'qa','re','ro','rw','ru','sa','sb','sc','sd','se','sg',
'sh','si','sj','sk','sl','sm','sn','so','sr','st','sv',
'sy','sz','tc','td','tf','tg','th','tj','tk','tm','tn',
'to','tp','tr','tt','tv','tw','tz','ua','ug','uk','um',
'us','uy','uz','va','vc','ve','vg','vi','vn','vu','ws',
'wf','ye','yt','yu','za','zm','zw','eu','mobi','travel',
'aero','arpa','asia','cat','jobs','tel','me');

	$label = '[\\w][\\w\\.\\-]{0,61}[\\w]';
	$tld = '[\\w]+';

	if( preg_match( "/^($label)\\.($tld)$/", $domain, $match ) && in_array( $match[2], $tld_list ))
	{
		return true;
	}
	else
	{
		return false;
	}

}

function getSafePositiveInteger($var,$global="r",$default=1)
{
	if($global=="g")
	{
		if(isset($_GET[$var]))
		$pageno=$_GET[$var];
		else
		return $default;
	}
	if($global=="p")
	{
		if(isset($_POST[$var]))
		$pageno=$_POST[$var];
		else
		return $default;
	}
	if($global=="c")
	{
		if(isset($_COOKIE[$var]))
		$pageno=$_COOKIE[$var];
		else
		return $default;
	}
	if($global=="r")
	{
		if(isset($_REQUEST[$var]))
		$pageno=$_REQUEST[$var];
		else
		return $default;
	}
	$pageno=intval($pageno);
	if($pageno<1)
	$pageno=$default;
	return trim($pageno);
}


function getSafeInteger($var,$global="r",$default=1)
{
	if($global=="g")
	{
		if(isset($_GET[$var]))
		$pageno=$_GET[$var];
		else
		return $default;
	}
	if($global=="p")
	{
		if(isset($_POST[$var]))
		$pageno=$_POST[$var];
		else
		return $default;
	}
	if($global=="c")
	{
		if(isset($_COOKIE[$var]))
		$pageno=$_COOKIE[$var];
		else
		return $default;
	}
	if($global=="r")
	{
		if(isset($_REQUEST[$var]))
		$pageno=$_REQUEST[$var];
		else
		return $default;
	}
	$pageno=intval($pageno);
	return trim($pageno);
}

function getUserIP() {
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $ip = checkIP($_SERVER['HTTP_X_FORWARDED_FOR']))
		return $ip;
		if (isset($_SERVER['HTTP_CLIENT_IP']) && $ip = checkIP($_SERVER['HTTP_CLIENT_IP']))
		return $ip;
		return $_SERVER['REMOTE_ADDR'];
	}
	if ($ip = checkIP(getenv('HTTP_X_FORWARDED_FOR')))
	return $ip;
	if ($ip = checkIP(getenv('HTTP_CLIENT_IP')))
	return $ip;
	return getenv('REMOTE_ADDR');
}

/**
 * Call as: $ip = checkIP($ip);
 */
function checkIP($ip) {
	if (empty($ip) ||
	($ip >= '10.0.0.0' && $ip <= '10.255.255.255') ||
	($ip >= '172.16.0.0' && $ip <= '172.31.255.255') ||
	($ip >= '192.168.0.0' && $ip <= '192.168.255.255') ||
	($ip >= '169.254.0.0' && $ip <= '169.254.255.255'))
	return false;
	return $ip;
}

function proxyDetection()
{
	if (
	$_SERVER['HTTP_X_FORWARDED_FOR']
	|| $_SERVER['HTTP_X_FORWARDED']
	|| $_SERVER['HTTP_FORWARDED_FOR']
	|| $_SERVER['HTTP_CLIENT_IP']
	|| $_SERVER['HTTP_VIA']
	|| in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554))
	|| @fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 2))
	{
		return true;
		//Proxy detected'
	}

	else
	{
		return false;
		//No Proxy detected
	}



}

//browser detection
function browserDetection( $which_test )
{
	// initialize variables
	$browser_name = '';
	$browser_number = '';
	// get userAgent string
	$browser_user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';
	//pack browser array
	// values [0]= user agent identifier, lowercase, [1] = dom browser, [2] = shorthand for browser,
	$a_browser_types[] = array('opera', true, 'op' );
	$a_browser_types[] = array('msie', true, 'ie' );
	$a_browser_types[] = array('konqueror', true, 'konq' );
	$a_browser_types[] = array('safari', true, 'saf' );
	$a_browser_types[] = array('gecko', true, 'moz' );
	$a_browser_types[] = array('mozilla/4', false, 'ns4' );
	# this will set a default 'unknown' value
	$a_browser_types[] = array('other', false, 'other' );

	$i_count = count($a_browser_types);
	for ($i = 0; $i < $i_count; $i++)
	{
		$s_browser = $a_browser_types[$i][0];
		$b_dom = $a_browser_types[$i][1];
		$browser_name = $a_browser_types[$i][2];
		// if the string identifier is found in the string
		if (stristr($browser_user_agent, $s_browser))
		{
			// we are in this case actually searching for the 'rv' string, not the gecko string
			// this test will fail on Galeon, since it has no rv number. You can change this to
			// searching for 'gecko' if you want, that will return the release date of the browser
			if ( $browser_name == 'moz' )
			{
				$s_browser = 'rv';
			}
			$browser_number = browserVersion( $browser_user_agent, $s_browser );
			break;
		}
	}
	// which variable to return
	if ( $which_test == 'browser' )
	{
		return $browser_name;
	}
	elseif ( $which_test == 'number' )
	{
		# this will be null for default other category, so make sure to test for null
		return $browser_number;
	}

	/* this returns both values, then you only have to call the function once, and get
	 the information from the variable you have put it into when you called the function */
	elseif ( $which_test == 'full' )
	{
		$a_browser_info = array( $browser_name, $browser_number );
		return $a_browser_info;
	}
}

// function returns browser number or gecko rv number
// this function is called by above function, no need to mess with it unless you want to add more features
function browserVersion( $browser_user_agent, $search_string )
{
	$string_length = 8;// this is the maximum  length to search for a version number
	//initialize browser number, will return '' if not found
	$browser_number = '';

	// which parameter is calling it determines what is returned
	$start_pos = strpos( $browser_user_agent, $search_string );

	// start the substring slice 1 space after the search string
	$start_pos += strlen( $search_string ) + 1;

	// slice out the largest piece that is numeric, going down to zero, if zero, function returns ''.
	for ( $i = $string_length; $i > 0 ; $i-- )
	{
		// is numeric makes sure that the whole substring is a number
		if ( is_numeric( substr( $browser_user_agent, $start_pos, $i ) ) )
		{
			$browser_number = substr( $browser_user_agent, $start_pos, $i );
			break;
		}
	}
	return $browser_number;
}

function removeNumbers($string) {
  		$vowels = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
  		$string = str_replace($vowels, '', $string);
  		return $string;
  	}
  	
  	
  	
  	
  	function dateFormat($timeval,$format="1")
  	{
  		
  	 if($format!="1")
  		{
  			return strftime($format,$timeval);
  		}
  		 $d=$GLOBALS['day_of_week']	;
  		
  		if($d!="0")
  		{
  			$dofw=$GLOBALS['day_of_week'];
  		}
  		else
  		{
  			$dofw="";
  		}
  			if($GLOBALS['date_separators']=="sp")
  			{
  				$dsp=" ";
  			}
  			else
  			{
  				$dsp=$GLOBALS['date_separators'];
  			}
  			if($GLOBALS['datefield_format']=="dayamonth")
  			{
  				$b=$dofw." ".$GLOBALS['month'].$dsp.$GLOBALS['day_of_month'].$dsp.$GLOBALS['year'];
  				$dateform=strftime($b,$timeval);
  			}
  			else
  			{
  				
  				$b=$dofw." ".$GLOBALS['day_of_month'].$dsp.$GLOBALS['month'].$dsp.$GLOBALS['year'];
  				$dateform=strftime($b,$timeval);
  			}
  			  		
  		return $dateform;
  		
  	}
  	
 	function timeFormat($timeval,$format="")
  	{
  	if($GLOBALS['clock_type']=="24hour")
  				{
  					$ctype="%H";
  				}
  				else
  				{
  					$ctype="%I";
  					$am="%p";
  				}
  				
  				$b=$ctype." ".$GLOBALS['minute']." ".$GLOBALS['seconds']." ".$am;
  				$dateform=strftime($b,$timeval);
  			
  			  		
  		return $dateform;
  		
  	}
  	function dateTimeFormat($timeval,$format="1")
  	{

  	     if($format!="1")
  		{
  			return strftime($format,$timeval);
  		}
  		$d=$GLOBALS['day_of_week']	;
  		$am=" ";
  		if($d!="0")
  		{
  			$dofw=$GLOBALS['day_of_week'];
  		}
  		else
  		{
  			$dofw="";
  		}
  			if($GLOBALS['date_separators']=="sp")
  			{
  				$dsp=" ";
  			}
  			else
  			{
  				$dsp=$GLOBALS['date_separators'];
  			}
  			if($GLOBALS['time_separator']=="sp")
  			{
  				$tsp=" ";
  			}
  			else
  			{
  				$tsp=$GLOBALS['time_separator'];
  			}
  			
  			if($GLOBALS['clock_type']=="24hour")
  				{
  					$ctype="%H";
  				}
  				else
  				{
  					$ctype="%I";
  					$am="%p";
  				}
  				$x=$GLOBALS['datefield_format'];
  			if($x=="dayamonth") 
  			{
  				
            $b=$dofw." ".$GLOBALS['month'].$dsp.$GLOBALS['day_of_month'].$dsp.$GLOBALS['year']." ".$ctype.$tsp.$GLOBALS['minute'].$tsp.$GLOBALS['seconds']." ".$am;
			$dateform=strftime($b,$timeval);
  				
  			}
  			else
  			{
  				
  				$b=$dofw." ".$GLOBALS['day_of_month'].$dsp.$GLOBALS['month'].$dsp.$GLOBALS['year']." ".$ctype.$tsp.$GLOBALS['minute'].$tsp.$GLOBALS['seconds']." ".$am;
  				$dateform=strftime($b,$timeval);
  				
  			}
  			  		
  		return $dateform;
  		
  		
  		
  	}
  	function numberFormat($value,$format=1)
  	
  	{
  		
  		$th=$GLOBALS['thousand_separator'];
  		$des=$GLOBALS['decimal_separator'];
  		$nodes=$GLOBALS['no_of_decimalplaces'];
  		if($format==0)
		{
		$value=sprintf("%d",round($value,$nodes));
  		return number_format ($value, 0, $des,$th);
		}
  		else
		{
		$value=sprintf("%0.".$nodes."f",round($value,$nodes));
  		return number_format ($value, $nodes, $des,$th);
		}
		
		
  		
  		
  	}
  	function moneyFormat($value,$format="")
  	{
  		
  		$rnumber=numberFormat($value);
  		$nul="0".$GLOBALS['decimal_separator']."0";
//  		if($rnumber==$nul)
//  		{
//  			$rnumber=0;
//  		}
  		if($GLOBALS['currency_format']=="$")
  		{
  			$curren=$GLOBALS['currency_symbol'];
  		}
  		else
  		{
  			$curren=$GLOBALS['system_currency'];
  		}
  		if($GLOBALS['currency_position']=="1")  //suffix
  		{
  			$num=$rnumber." ".$curren;
  		}
  		else //prefix
  		{
  			$num=$curren." ".$rnumber;
  		}
  		return $num;
  	}
  	
  	
 function getCTR($clk,$imp)
 {
 	return $imp>0?($clk/$imp)*100:0;
 }
 
  function getECPM($earnigs,$imp)
 {
 	return $imp>0?($earnigs/$imp)*1000:0;
 }
 
 function getSerialNum($page,$perpage,$rownum)
 {
	return (($page-1)*$perpage)+$rownum+1;
 
 }
 function wordwrap1($text)
 {
 	return wordwrap($text, 37, "<br>", 1);
 }
 function getkeywordtable($beg_time,$end_time,$flagtime,$type,$order)
{
	if($type=="imp")
	{
		$cond="sum(impressions)";
	}
	elseif($type=="clk")
	{
		$cond="sum(click_count)";
	}
	else
	{
		$cond="sum(money_spent)";
	}
	if($order=="asc")
	{
		$as="ASC";
	}
	else
	{
		$as="DESC";
	}
if($flag_time==0)
	  {
		  $temp=mysql_query("select keyid,COALESCE(sum(impressions),0),sum(click_count),sum(money_spent) from keyword_daily_statistics where  time>=$beg_time group by keyid order by $cond $as ");
		
	  }
//	   else if($flag_time==-1)
//	 	{
//		 $temp=mysql_query("select keyid,sum(impressions),sum(click_count),sum(money_spent) from keyword_daily_statistics where  time>=$beg_time group by keyid order by $cond $as ");
//		 
//	  }
	 // echo "select keyid,sum(impressions),sum(click_count),sum(money_spent) from keyword_daily_statistics where  time>=$beg_time  group by keyid order by $cond $as";
	  while($newresult=mysql_fetch_row($temp))
	  {$key=$newresult[0];
	  	$newtable[$key][0]=$newresult[0];
	  	$newtable[$key][1]=$newresult[1];
	  	$newtable[$key][2]=$newresult[2];	
	  	$newtable[$key][3]=$newresult[3];	
	  }
	  return($newtable);
	  //return($newtable);
}
function getCountry($countryid)
{
	
if($countryid=="")
return "-";
else
{
	$cname=mysql_query("select name from location_country where code='$countryid'");
	$cname1=mysql_fetch_row($cname);
	return $cname1[0];
}
}
function getLANG($langid)
{
	
if($langid=="")
{
return "0";
}
elseif($langid==0)
{
	return "Any Languages";
}
else
{
	$cname=mysql_query("select language from adserver_languages where id='$langid'");
	$cname1=mysql_fetch_row($cname);
	return $cname1[0];
}
}

function selfURL() 
{ 
return (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
 }
 
 //************************5.4**************************************//
 
 function myPubSite($id,$uid,$mysql)
{

	if($mysql->total("ppc_publishing_urls","id='$id' AND pid='$uid'")!=0)
	return true;
	else
	return false;

}
 
 //************************5.4**************************************//
?>