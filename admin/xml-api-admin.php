<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/

?><?php

include("config.inc.php");

if(!isset($_COOKIE['inout_admin']))
{
	header("Location:index.php");
	exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	header("Location:index.php");
	exit(0);
}

?><?php include("admin.header.inc.php"); ?>
<?php

$auth_code=$GLOBALS['xml_auth_code'];
$api_url=$server_dir.'xml-ads.php';

?>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>

  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/admin-adunits.php"; ?> </td>
  </tr>
  
  <tr>
   <td   colspan="4" scope="row" class="heading">XML API</td>
  </tr>

</table>
<br />

  <table width="100%"  border="0" cellpadding="0" cellspacing="1" >
   
	<tr>
	<td colspan="3" align="left" ><div ><span class="inserted">Configuring XML API</span></div></td>
    </tr>

 
    <tr><td >&nbsp;</td>
      <td>&nbsp;</td>
    <td>&nbsp;</td></tr> 
    <tr><td><b>API URL</b></td>
      <td colspan="2"><b><?php echo $api_url; ?></b></td>
    </tr>
	
	 <tr><td >&nbsp;</td>
      <td>&nbsp;</td>
    <td>&nbsp;</td></tr> 
    <tr><td colspan="3"><span class="info"><strong>Note : </strong>Please do not cache the results from XML API in your server.Clicks from cached results may be treated invalid.</span></td>
      
    </tr>
	
	
    <tr><td >&nbsp;</td>
      <td>&nbsp;</td>
    <td>&nbsp;</td></tr> 
	</table>
		  
		   <table width="100%"  border="0" cellpadding="0" cellspacing="1" > 
	<tr  bgcolor="#b7b5b3" class="style1">

      <td width="15%" align="left"  height="30"><span class="style1">Parameter<br>
      </br></span></td>
		  <td width="31%" align="left">Value</td>
		  <td width="54%" align="left"><span class="style1">Description<br>
      </br></span></td>
    </tr>




<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr> 
<tr><td>user_id</td>
  <td>0&nbsp;</td>
  <td>User id of the admin </td></tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
<tr><td>auth_code</td>
  <td><?php echo $xml_auth_code; ?></td>
  <td>Authentication code is used to prevent misuse of your api &nbsp;(You can chnage this default value from 

 
    Basic Configurations &amp; Settings => Advanced Settings)</td>
</tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
<tr><td>wap</td>
  <td>1/0</td>
  <td>'1'  for Wap target  and  '0'  for Desktop & Laptop target.</td></tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
<tr><td>start_record</td>
  <td>Any non negative integer &nbsp;</td>
  <td>Indicates the record number of the first result in the set and is always numeric.</td></tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
<tr><td>count</td>
  <td> 	Any positive integer &nbsp;</td>
  <td>Indicates the record number of the last result in the set and is always numeric.</td></tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
<tr><td>keywords</td>
  <td>search keywords &nbsp;</td>
  <td>An HTML encoded text string representing what the user is searching for. The text string must be encoded according to RFC1738.</td></tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
<tr><td>user_ip</td>
  <td> 	Ip of the visitor who sees the ad. &nbsp;</td>
  <td>A text string representing the IP address of the user who initiated the search. THIS PARAMETER IS NOT THE IP ADDRESS OF YOUR SERVERS. You must include the  ip_addr parameter to receive full credit for all searches and click-throughs.</td></tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
<tr><td>url</td>
  <td>Url where ad is displayed &nbsp;</td>
  <td>This is the url of the page where the ads will be shown.  It must be the complete url, down to the page name where the ads are being shown (simply sending the domain name is not sufficient).  You may have to assemble the url from several functions or variables.  For example, creating a full url in php might involve the following server variables:

$_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI']

Similar to the keywords parameter, this value must be HTML encoded.</td></tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
  
  
  
  <tr><td>ref</td>
  <td>Referrer url&nbsp;</td>
  <td>This is the url of the page from where the visitor was directed to your ad display page.In php you can get this from $_SERVER['HTTP_REFERER'].Similar to the keywords parameter, this value must be HTML encoded. </td></tr>
<tr><td >&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td></tr>
  
  
  
  
  
  <tr><td >lang</td>
  <td>Preferred ad language </td>
  <td>
		  <table cellpadding="0" cellspacing="0" border="0" width="100%">
		  <tr>
		  <td>0</td>
		  <td>Any languages</td>
		  </tr>
		 <?php 
		 $result=mysql_query("Select * from adserver_languages where status=1 order by id asc");
		 
		 while($db=mysql_fetch_row($result))
		 
		 {
		   ?>
		  <tr><td><?php echo $db[0]; ?></td>
		  <td><?php echo $db[1]; ?></td>
		  </tr>
		<?php } ?> 
		  </table>
  
  
  </td>
  </tr>
  
 
 
<tr>
  <td colspan="3" >&nbsp;</td>
</tr>
<tr><td valign="top"><strong>Sample url : </strong></td>
  <td colspan="2" > <?php echo $api_url; ?>?user_id=0&auth_code=<?php echo $xml_auth_code; ?>&wap=0&start_record=0&count=5
  
&keywords=web+hosting&user_ip=59%3A49%3A59%3A69&url=


 http%3A%2F%2Fyoursite%3Acom&amp;lang=0&ref=http%3A%2F%2Fothersite%3Acom</td>
</tr>
<tr>
  <td colspan="3" >&nbsp;</td>
</tr>
<tr>
  <td colspan="3" >&nbsp;</td>
</tr>
<tr>
      <td colspan="3" ><strong>Sample output</strong>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" ><textarea cols="85" rows="12" style="background-color:#CCCCCC">
&lt;ads&gt;
&lt;ad&gt;
&lt;title&gt;Ad title&lt;/title&gt;
&lt;description&gt;This is test ad&lt;/description&gt;
&lt;displayurl&gt;www.adurl.com&lt;/displayurl&gt;
&lt;targeturl&gt;
http://www.yoursite.com/adserver/xml-ad-click.php?id=837&kid=1674&pid=0&vid=0&vip=d41d8cd98f00b204e9800998ecf8427e&direct_status=c4ca4238a0b923820dcc509a6f75849b&wap=1
&lt;/targeturl&gt;
&lt;clickbid&gt;0.5&lt;/clickbid&gt;
&lt;/ad&gt;
&lt;/ads&gt;
	</textarea>&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<tr>
    <td colspan="3"><strong>Note:</strong></td>
  </tr>
  <tr>
    <td>title</td>
    <td colspan="2"> : Title of the ad  </td>
  </tr>
  <tr>
    <td>displayurl</td>
    <td colspan="2"> : Tisplay-url of the ad  </td>
  </tr>
  <tr>
    <td>targeturl</td>
    <td colspan="2"> : The url to which visitor has to be redirected</td>
  </tr>
  <tr>
    <td>clickbid</td>
    <td colspan="2"> : The total click value of the ad</td>
  </tr>
  <tr>
    <td></td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="3" >&nbsp;</td>
  </tr>
  <tr>
      <td colspan="3" ><strong>Sample PHP code </strong>&nbsp;</td>
  </tr>
    <tr>
    <td colspan="3" ><textarea cols="85" rows="18" style="background-color:#CCCCCC">
&lt;?php

$kewords='&lt;YOUR KEYWORDS&gt;';  // replace "&lt;YOUR KEYWORDS&gt;" with your keywords  

$start_record=0;             // specify the starting number to be applied while querying the ads

$count=3;                    // specify the number of ads to be returned

$wap=0;                      //0 for desktop and 1 for wap


$kewords=urlencode($kewords);
$user_ip=urlencode($_SERVER['REMOTE_ADDR']);
$xml_auth_code=urlencode(<?php echo $xml_auth_code; ?>);

$xmldata="";
if($fp=fopen("<?php echo $api_url; ?>?user_id=0&auth_code=".$xml_auth_code."&start_record=".$start_record."&count=".$count."&keywords=".$kewords."&user_ip=".$user_ip."&wap=".$wap,"r")) 
{
	while(!feof($fp))
	$xmldata.=fgetc($fp);

 fclose($fp);
 
}


///parsing  xml ads to array//////
$feed_result=array();        

if($xmldata!="")
{
	preg_match_all("/&lt;ad&gt;&lt;title&gt;(.+?)&lt;\/title&gt;&lt;description&gt;(.+?)&lt;\/description&gt;&lt;displayurl&gt;(.+?)&lt;\/displayurl&gt;&lt;targeturl&gt;(.+?)&lt;\/targeturl&gt;&lt;clickbid&gt;(.+?)&lt;\/clickbid&gt;&lt;\/ad&gt;/i",$xmldata,$resultset);

	$j=count($resultset[1]);
	
	for($i=0;$i&lt;$j;$i++)
	{
		$feed_result["title"][$i]=substr(preg_replace("/(&lt;\!\[CDATA\[)(.+?)(\]\]&gt;)/","$2",$resultset[1][$i]),0,35);
		$feed_result["desc"][$i]=substr(preg_replace("/(&lt;\!\[CDATA\[)(.+?)(\]\]&gt;)/","$2",$resultset[2][$i]),0,70);
		$feed_result["dispurl"][$i]=substr(preg_replace("/(&lt;\!\[CDATA\[)(.+?)(\]\]&gt;)/","$2",$resultset[3][$i]),0,35);
		$feed_result["clkrurl"][$i]=preg_replace("/(&lt;\!\[CDATA\[)(.+?)(\]\]&gt;)/","$2",$resultset[4][$i]);
		$feed_result["bid"][$i]=preg_replace("/(&lt;\!\[CDATA\[)(.+?)(\]\]&gt;)/","$2",$resultset[5][$i]);
	}
	
	// $feed_result contains the ads in an array format.
	
	for($i=0;$i&lt;$j;$i++)
	{
		//display the ad as you wish
	}

}


?&gt; 	

	</textarea>&nbsp;</td>
  </tr>
 
  <tr>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
      <td colspan="3" ><span class="info">Note: Only text ads will be available using XML api.</span>&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  </table>

<?php include("admin.footer.inc.php"); ?>
