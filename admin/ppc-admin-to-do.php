<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php
include("../extended-config.inc.php");
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
include_once("admin.header.inc.php");	
?>
<br />
<table width="100%">
<tr>
  <td colspan="2"class="heading" >TO DO List</td>
  <td width="0"></td>
  </tr>
 </table>
 
 <table width="100%">
 
<tr>
  <td width="2%" align="center">&nbsp;</td>
  <td width="98%" class="heading"> </td></td>
<tr>
<tr>
  <td width="2%" align="left"><strong>*</strong></td>
  <td width="98%"  >Please do the things which are in <strong>bold </strong> at the earliest.</td></td>
<tr>
<tr>
  <td width="2%" align="center">&nbsp;</td>
  <td width="98%"> </td></td>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
  <?php 
  $todo='';
  if($mysql->echo_one("select uid from ppc_users where status=-1 limit 0,1")!='') $todo='todo';
  ?>
  <a href="ppc-view-users.php?status=-1&country=-1" class="mainmenu <?php echo $todo;?>">Pending  Advertisers</a>  
   <!--(<?php //echo $mysql->echo_one("select count(*) from ppc_users where status=-1 ");?>)-->
   </td></tr>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
  <?php 
  $todo='';
  if($mysql->echo_one("select uid from ppc_publishers where status=-1 limit 0,1")!='') $todo='todo';
  ?>
  <a href="ppc-view-publishers.php?status=-1&country=-1" class="mainmenu  <?php echo $todo;?>">Pending  Publishers</a>
  <!--  (<?php //echo $mysql->echo_one("select count(*) from ppc_publishers where status=-1 ");?>)-->
  </td></tr>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
  <?php 
  $todo='';
  if($mysql->echo_one("select id from ppc_ads  where status=-1 limit 0,1")!='') $todo='todo';
  ?>
  <a href="ppc-view-ads.php?status=-1&adtype=3&device=2" class="mainmenu  <?php echo $todo;?>">Pending  Ads</a>
<!--(<?php // echo $mysql->echo_one("select count(*) from ppc_ads where status=-1 ");?>)--> 
 </td></tr>

 <?php if($site_targeting==1) { ?>
 
 <tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
  <?php 
  $todo='';
  if($mysql->echo_one("select id from ppc_publishing_urls where status=-1 limit 0,1")!='') $todo='todo';
  ?>
  <a href="manage-ppc-publishing-urls.php" class="mainmenu  <?php echo $todo;?>">Pending  publishing URLs</a>
<!--(<?php // echo $mysql->echo_one("select count(*) from ppc_ads where status=-1 ");?>)--> 
 </td></tr>
 
 <?php } ?>
 
  <tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
  <?php 
  $todo='';
  if($mysql->echo_one("select id from ad_logos_details where status=-1 limit 0,1")!='') $todo='todo';
  ?>
  <a href="manage_logos.php" class="mainmenu  <?php echo $todo;?>">Pending  Logos</a>
<!--(<?php // echo $mysql->echo_one("select count(*) from ppc_ads where status=-1 ");?>)--> 
 </td></tr>
 
<?php 
//$b_pending_approval=$mysql->echo_one("select count(*) from `advertiser_fund_deposit_history` where status=0 and pay_type =2");
  $todo='';
  if($mysql->echo_one("select id from advertiser_fund_deposit_history  where status=0 and pay_type =2 limit 0,1")!='') $todo='todo';
?>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
      <a href="ppc-admin-payment-deposit-history.php?type=b&status=0" class="mainmenu  <?php echo $todo;?>">Pending Advertiser Bank Payments</a></td>
</tr>
 
<?php 
//$b_approved=$mysql->echo_one("select count(*) from `advertiser_fund_deposit_history` where status=1 and pay_type =2");
  $todo='';
  if($mysql->echo_one("select id from advertiser_fund_deposit_history  where status=1 and pay_type =2 limit 0,1")!='') $todo='todo';
?>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
      <a href="ppc-admin-payment-deposit-history.php?type=b&status=1" class="mainmenu  <?php echo $todo;?>">Approved Advertiser Bank Payments</a></td>
</tr>
 

<?php 
//$c_pending_approval=$mysql->echo_one("select count(*) from `advertiser_fund_deposit_history` where status=0 and pay_type =1");
  $todo='';
  if($mysql->echo_one("select id from advertiser_fund_deposit_history  where status=0 and pay_type =1 limit 0,1")!='') $todo='todo';
?>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
      <a href="ppc-admin-payment-deposit-history.php?type=c&status=0" class="mainmenu  <?php echo $todo;?>">Pending Advertiser Check Payments</a></td>
</tr>
 
<?php 
//$c_approved=$mysql->echo_one("select count(*) from `advertiser_fund_deposit_history` where status=1 and pay_type =1");
  $todo='';
  if($mysql->echo_one("select id from advertiser_fund_deposit_history  where status=1 and pay_type =1 limit 0,1")!='') $todo='todo';
?>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
      <a href="ppc-admin-payment-deposit-history.php?type=c&status=1" class="mainmenu  <?php echo $todo;?>">Approved Advertiser Check Payments</a></td>
</tr>
 

<?php 
//$pending_approval=$mysql->echo_one("select count(*) from `ppc_publisher_payment_hist` where status=0");
  $todo='';
  if($mysql->echo_one("select id from ppc_publisher_payment_hist  where status=0 limit 0,1")!='') $todo='todo';
?>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
  <a href="ppc-publisher-withdrawal-history.php?status=0" class="mainmenu  <?php echo $todo;?>">Pending Publisher Withdrawal</a></td></tr>
<?php 
//$approved=$mysql->echo_one("select count(*) from `ppc_publisher_payment_hist` where status=1");
  $todo='';
  if($mysql->echo_one("select id from ppc_publisher_payment_hist  where status=1 limit 0,1")!='') $todo='todo';
?>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
  <td valign="top">
  <a href="ppc-publisher-withdrawal-history.php?status=1" class="mainmenu  <?php echo $todo;?>">Approved Publisher Withdrawal</a></td></tr>
<?php
$tot_ads=$mysql->echo_one("select count(id) from ppc_ads");
$ads_with_keyword=$mysql->echo_one("select count(distinct(aid)) from ppc_keywords where keyword='$keywords_default'");
  $todo='';
  if(($tot_ads-$ads_with_keyword)>0) $todo='todo';
  if($ad_keyword_mode==1) $todo='' ;

?>

<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle"></td>
  <td valign="top"><a href="create-keyword.php"  class="mainmenu  <?php echo $todo;?>">Generate default keyword</a>
  *</td>
</tr>

<?php 

$target_loc=$mysql->echo_one("select a.id from ppc_ads a,ad_location_mapping b where a.id =b.adid and b.adid is NULL limit 0,1");
$todo='';
if($target_loc!='') $todo='todo';

?>
<tr>
  <td valign="top" height="30"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
<td  valign="top"><a href="create_target_loc.php" class="mainmenu  <?php echo $todo;?>">Generate target location for ads</a> **</td>
</tr>


<?php
$acc_time=filemtime("../geo/GeoIP.dat");
//$time_to_update=(mktime(0,0,0,date("m",time())-3,date("d",time()),date("Y",time())));
$todo='';
if((time()-$acc_time)> (30*86400)) $todo='todo';
?>
<tr>
  <td height="30" valign="top"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
<td valign="top"><a href="http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz"  class="mainmenu  <?php echo $todo;?>">Get the latest GeoLite Country Binary Format</a> ***</td>
</tr>

<?php

$files = glob( "../".$GLOBALS['cache_folder']."/*.*" );

// Sort files by modified time, latest to earliest
// Use SORT_ASC in place of SORT_DESC for earliest to latest
array_multisort(
array_map( 'filemtime', $files ),
SORT_NUMERIC,
SORT_ASC,
$files
);

//print_r($files);
$todo='';//echo $files[0];
$seven_time=mktime(0,0,0,date("m",time()),date("d",time())-7,date("Y",time()));

if(isset($files[0]) && filemtime($files[0])<$seven_time ) $todo='todo';

?>

<tr>
  <td valign="top" height="30"><img src="images/1rightarrow.png" width="10" height="10" align="absmiddle" /></td>
<td  valign="top"><a href="clear-cache.php"  class="mainmenu  <?php echo $todo;?>">Clear old files from cache</a></td>
</tr>


       





<tr>
  <td valign="top">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td valign="top">*</td>
  <td><span class="info">When the  &quot;adserver operation mode&quot; (under &quot;Basic Settings&quot;)

 is set to &quot;Keyword based&quot; or &quot;Both&quot;, default keyword is a must for all the ads. Your current operation mode is set to "<?php
 if($ad_keyword_mode==1){echo "Keyword based";} if($ad_keyword_mode==2){echo "Keyword Independent";} if($ad_keyword_mode==3){echo "Both";}?>". <?php
 if($ad_keyword_mode==1){ ?>So you need not generate default keyword. <?php } elseif(($tot_ads-$ads_with_keyword)>0) {?>Right now you have some ads which do not have default keyword.<?php } ?></span></td>
</tr>


<tr>
  <td valign="top">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td valign="top">**</td>
  <td class="info">All ads should be targeted either to specific geographic location or worldwide. Use this link to add worldwide targeting for these ads.</td>
</tr>

<tr>
  <td valign="top">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td valign="top">***</td>
  <td class="info">Your GeoLite Country binary file (geo/GeoIP.dat) was last updated on <strong><?php echo dateFormat($acc_time); ?></strong>. We recommend upgrading this file every month from the link provided.</td>
</tr>

<tr>
  <td valign="top">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td valign="top">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
</table>

<?php include_once("admin.footer.inc.php"); ?>