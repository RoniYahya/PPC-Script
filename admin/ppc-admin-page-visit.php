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
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;


if(isset($_POST['statistics']))
{	$show=$_POST['statistics'];	}
else if(isset($_GET['statistics']))
{ $show=$_GET['statistics']; }
else
{
	$show="all";
}
$url=$_SERVER['REQUEST_URI'];

  //$string = $url;
 // if(stristr($string, 'statistics') === FALSE) {
   // $url=$url."&statistics=$show";
 // }
$url=urlencode($url);


include("admin.header.inc.php"); ?>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/admin-adunits.php"; ?> </td>
  </tr>
  
  <tr>
   <td   colspan="4" scope="row" class="heading">Admin's traffic analysis</td>
  </tr>

 </table><br />

<table border="0" cellpadding="0" cellspacing="0" width="100%">


      <tr>
      <td colspan="3" align="left">&nbsp;</td>
      <td colspan="3" align="right">&nbsp;</td>
    </tr>
<?php

$visit_time=mktime(0,0,0,date("m",time()),date("d",time())-30,date("Y",time()));
$tmp_time=$mysql->echo_one("select e_time from `statistics_updation`  where task='click_backup'");
$tmp_time=$tmp_time-(24*60*60);

$res=mysql_query("SELECT page_url,pid,sum(direct_hits),sum(referred_hits),sum(direct_clicks),sum(referred_clicks),sum(direct_impressions),sum(referred_impressions),sum(direct_invalid_clicks),sum(referred_invalid_clicks),sum(direct_fraud_clicks),sum(referred_fraud_clicks),sum(direct_repeated_click),sum(referred_repeated_click), sum(`direct_hits`)+sum(`referred_hits`) 
as a FROM  publisher_visits_statistics where pid=0  group by `page_url`  order by a desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$num_rows=$mysql->echo_one("select  count(distinct(page_url)) from publisher_visits_statistics where pid=0 ");
  
  //$num_rows=0;
	if($num_rows>=1) {?>  
		<tr>
		<td colspan="3" align="left"> Admin Traffic Analysis <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
			<span class="inserted"><?php if((($pageno)*$perpagesize)>$num_rows) echo $num_rows; else echo ($pageno)*$perpagesize; ?></span>&nbsp;of 
			<span class="inserted"><?php echo $num_rows; ?></span>&nbsp;&nbsp;&nbsp;<br>
			<br>
		</td>
		<td colspan="3" align="right">
		<?php echo $paging->page($num_rows,$perpagesize,"","ppc-admin-page-visit.php?statistics=$show"); ?></td>
		</tr>
	

	<tr>
	<td colspan="6">
	
	<table width="100%" cellpadding="0" cellspacing="0" class="datatable">
	   <tr class="headrow">
        
        <td width="29%"><span class="style1"><strong>Ad Pages </strong></span></td>
        <td width="10%"><span class="style1"><strong>Visits</strong></span></td>
        <td width="3%">&nbsp;</td>
        <td width="8%">&nbsp;</td>
        <td width="12%"><span class="style1"><strong>Total impressions</strong></span></td>
        <td width="12%"><span class="style1"><strong>Valid Clicks </strong></span></td>
        <td width="12%"><span class="style1"><strong>Repeated Clicks </strong></span></td>
        <td width="14%"><span class="style1"><strong>Invalid Clicks</strong></span></td>
	  </tr>
   

 
	<?php
	$pidstr=" pid=0 and ";
	$i=1;


	 while($row=mysql_fetch_row($res))
      {
	 // $subquery="select id from publisher_visits_statistics where page_url='$row[0]' and (referred_hits=0)";
	 // $subquery_null="select id from publisher_visits_statistics where page_url='$row[0]' and (referred_hits=0)";
	   ?>
	  <tr  <?php if($i%2==1) { ?> class="specialrow" <?php } ?>>
	       <td rowspan="2"  style="border-bottom: 1px solid #b7b5b3;"><?php
        if($row[0]=="")//unknown url
            echo "Unknown";
        else
			echo wordwrap($row[0], 35, "<br>", 1);
             ?></td>
          <td  style="border-left:1px solid #CCCCCC;">Direct  </td>
        <td ><strong>:</strong></td>
        <td ><?php 
		
		echo $row[2];
		 ?></td>
		<td ><?php 
		echo numberFormat($row[6],0);
		 ?></td>
		<td ><?php 
		echo numberFormat($row[4],0);
				?></td>
		<td ><?php
		echo numberFormat($row[12],0);
	  ?></td>        
        <td ><?php 
		echo numberFormat($row[8],0);
		?></td>
		</tr>
		 
		 <tr  <?php if($i%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
		   <td     style="border-left:1px solid #CCCCCC;">Referred  </td>
        <td   ><strong>:</strong></td>
        <td   ><?php 
		echo $row[3];
		 ?></td>
				<td    ><?php
				echo numberFormat($row[7],0);
				?></td>
				<td    ><?php 
		echo numberFormat($row[5],0);		
		?></td>
		 <td   ><?php
		echo numberFormat($row[13],0);
		  ?></td>       
        <td   ><?php 
		echo numberFormat($row[9],0);
		?></td>
        </tr>
		<?php  
		$i++;
		}

	
	  ?>
	 </table>
	</td>
	</tr>

		<tr>
		<td colspan="3" align="left"> Admin Traffic Analysis <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
			<span class="inserted"><?php if((($pageno)*$perpagesize)>$num_rows) echo $num_rows; else echo ($pageno)*$perpagesize; ?></span>&nbsp;of 
			<span class="inserted"><?php echo $num_rows; ?></span>&nbsp;&nbsp;&nbsp;<br>
			<br>
		</td>
		<td colspan="3" align="right">
		<?php echo $paging->page($num_rows,$perpagesize,"","ppc-admin-page-visit.php?statistics=$show"); ?></td>
		</tr>
	<?php } ?>
	
</table>
<?php
if($num_rows==0)
{
echo "<br>No record found<br>";
}
else
{
	 ?>
	<br />
	<strong>Note 1:</strong><span class="info"> Traffic analysis for a maximum of 30 days will be stored in db.  Last 24 hours data are not included in the statistics. If you would like to clear your traffic analysis data now <a href="ppc-delete-page-visits.php?&pid=0&url=<?php echo $url; ?>">click here</a>.</span>
	<br />
	<br />
	
	<strong>Note 2:</strong>
	 <span class="info">This page shows the traffic analysis of admin's ad display pages ; It gives details like direct traffic, referred traffic, ad impressions and clicks . You can enable/disable the traffic analysis from <strong>Basic settings</strong></span>
	<br />
	<br />
	
	<strong>Note 3:</strong>
	 <span class="info">Invalid clicks include proxy clicks,bot clicks,clicks from invalid ips and locations.</strong></span>
	<br />
	<br />
	
	<?php if($adserver_upgradation_date!=0)
	 {?>
	<p><strong>Note 4:</strong><span class="info"> Impressions and Click Through Rates are available from : <?php echo dateFormat($adserver_upgradation_date);?>.</span> </p>
	<?php 
	}

}?>
<br />


 <?php include("admin.footer.inc.php"); ?>