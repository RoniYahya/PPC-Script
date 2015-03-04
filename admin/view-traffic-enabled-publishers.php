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
	$url=$_SERVER['REQUEST_URI'];
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;

//filtering status 

$status_value=5;

if(isset($_REQUEST['status']))
{

if($_REQUEST['status']==1)
$status_value=1;
if($_REQUEST['status']==-1)
$status_value=-1;
if($_REQUEST['status']==-2)
$status_value=-2;
if($_REQUEST['status']==0)
$status_value=0;

}

$str_status="";
if($status_value==1)
{
 $str_status="and a.status=1";
}
else if($status_value==-2)
{
 $str_status="and a.status=-2";
}
else if($status_value==-1)
{
 $str_status="and a.status=-1";
}
else if($status_value==0)
{
 $str_status="and a.status=0";
}


//filtering status


//filtering country

$country_code=-1;
$str_country="";

if(isset($_REQUEST['country']) && $_REQUEST['country'] !=-1)
{
$country_code=$_REQUEST['country'];
$str_country="and a.country='$country_code'";
}
//filtering country

// filtering by mode

$mode=4;
$str_mode="and (a.captcha_status='1' or a.traffic_analysis='1' or a.warning_status=1)";
if(isset($_REQUEST['mode']))
{
$mode=$_REQUEST['mode'];
if($mode==1)
$str_mode="and a.traffic_analysis='1'";
else if($mode==2)
$str_mode="and a.warning_status='1'";
else if($mode==3)
$str_mode="and a.captcha_status='1'";
else
{
	$mode=4;
$str_mode="and (a.captcha_status='1' or a.traffic_analysis='1' or a.warning_status=1)";
}

}
// filtering by mode ends

$result=mysql_query("select a.uid,a.username,b.name,a.domain,a.status,a.traffic_analysis,a.warning_status ,a.captcha_status  from ppc_publishers a,location_country b  where a.country=b.code $str_mode $str_status  $str_country  order by a.uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
if(mysql_num_rows($result)==0 && $pageno>1)
	{
		$pageno--;
		$result=mysql_query("select  a.uid,a.username,b.name,a.domain,a.status,a.traffic_analysis,a.warning_status ,a.captcha_status  from ppc_publishers a,location_country b  where a.country=b.code $str_mode  $str_status  $str_country  order by a.uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

	}
$total=$mysql->echo_one("select count(*) from ppc_publishers  a ,location_country b  where a.country=b.code  $str_mode  $str_status $str_country ");
$url=urlencode($_SERVER['REQUEST_URI']);
?><?php include("admin.header.inc.php");?>
<style type="text/css">
<!--
.style4 {font-size: 20px}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/fraud.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Suspicious Publishers <br> <br></td>
  </tr>
</table>


<form name="ads" action="view-traffic-enabled-publishers.php" method="get">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td height="34" colspan="">  Status</td>
    <td><select name="status" id="status" >
<option value="5" <?php	if($status_value=="5")echo "selected";	  ?>>All</option>
<option value="1"  <?php 	if($status_value=="1")echo "selected";	 ?>>Active</option>
<option value="-1" <?php 			  				  if($status_value=="-1")echo "selected";			  			  ?> >Pending Approval</option>
			  
<option value="-2" <?php 			  				  if($status_value=="-2")echo "selected";			  			  ?> >Email not verified</option>
<option value="0" <?php 			  				  if($status_value=="0")echo "selected";			  			  ?>>Blocked</option>
			  
</select>
	  </td>
	  <td>Country</td>
	  <td><select name="country" id="country" >
			  
			  
			   <option value="-1" <?php 			  				  if($country_code=="-1")echo "selected";			  			  ?>>All</option>
			  
			  <?php
			  $ctrstr="";
		$res=mysql_query("select code,name  from  location_country where code not in ('A1','A2','AP','EU') order by name ");
		
		//echo mysql_num_rows($res);
			while($row=mysql_fetch_row($res))
			{
			    $c_str="";
				if($country_code==$row[0]) 
				$c_str="selected";
				
				$ctrstr.="<option value=\"$row[0]\" $c_str >$row[1]</option>";
			} 
			echo $ctrstr;
			?>

       

</select></td></tr>

<tr><td>Fraud control </td><td><select name="mode">
<option value="1" <?php if($mode==1) echo 'selected';  ?> >Traffic Analysis Enabled</option>
<option value="3" <?php if($mode==3) echo 'selected';  ?> >Captcha Verification Enabled</option>
<option value="2" <?php if($mode==2) echo 'selected';  ?> >Already warned</option>
<option value="4" <?php if($mode==4) echo 'selected';  ?> >Any </option>
</select>
</td><td ><input type="submit" name="Submit" value="Submit"></td>
  </tr>
  </table> </form> 
  <br>
<?php
if($total>0)
{
 ?>




<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td height="34" colspan="2" ><?php if($total>=1) {?>   Showing Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>  </td>
    <td width="50%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","view-traffic-enabled-publishers.php?status=$status_value&country=$country_code"); ?></td>
  </tr>
</table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable">
  <tr class="headrow">
    <td width="20%"  ><strong> Name</strong></td>
    <td width="16%"><strong>Country</strong></td>   
	<td width="25%"><strong>Status</strong></td>
	<td width="13%"><strong>Traffic Analysis</strong></td>
	<td width="13%"><strong>Warnig Status</strong></td>
	<td width="13%"><strong>Captcha Verification</strong></td>
    
  </tr>
<?php
$i=1;


while($row=mysql_fetch_array($result))
{
?>
  <tr <?php if(($i-1)%2==1) { ?>class="specialrow" <?php }?>>
    <td   ><a href="view_profile_publishers.php?id=<?php echo $row[0]; ?>&tab=6"><?php echo $row[1];?></a></td>
    <td ><?php 
	if($row[2]=="")
		echo "&nbsp;";
	else
		echo $row[2];
	?></td>
   
	 <td>
	
	 <?php
	 
	 
	$str="";
	if($row[4]==1)
	{
	echo "Active";
	
	$str.='<a href="member-login.php?type=1&id='.$row[0].'">Login</a>&nbsp;|&nbsp;';  
	$str.='<a href="ppc-change-publisher-status.php?action=block&id='.$row[0].'&url='.urlencode($url).'">Block</a>&nbsp;|&nbsp;';  
	$str.= '<a href="ppc-send-mail.php?type=1&id='.$row[0].'&url='.urlencode($url).'">Mail</a>';
	
	}
   if($row[4]==-2)
	{
	echo "Email not verified";
	
	$str.='<a href="ppc-approve-publisher.php?id='.$row[0].'&url='.urlencode($url).'">Approve</a>&nbsp;|&nbsp;<a href="ppc-disapprove-publisher.php?id='.$row[0].'&url='.urlencode($url).'">Reject</a>&nbsp;|&nbsp;<a href="ppc-send-mail.php?type=1&id='.$row[0].'&url='.urlencode($url).'">Mail</a>'; 
	
	
	}
	if($row[4]==-1)
	{
	echo "Pending";
	
	$str.='<a href="ppc-approve-publisher.php?id='.$row[0].'&url='.urlencode($url).'">Approve</a>&nbsp;|&nbsp;<a href="ppc-disapprove-publisher.php?id='.$row[0].'&url='.urlencode($url).'">Reject</a>&nbsp;|&nbsp;<a href="ppc-send-mail.php?type=1&id='.$row[0].'&url='.urlencode($url).'">Mail</a>'; 
	
	}
	if($row[4]==0)
	{
	echo "Blocked";
	
	$str.='<a href="ppc-change-publisher-status.php?action=activate&id='.$row[0].'&url='.urlencode($url).'">Activate</a>&nbsp;|&nbsp;<a href="ppc-send-mail.php?type=1&id='.$row[0].'&url='.urlencode($url).'">Mail</a>';
	
	}
	?>	</td>

	 <td>
   <?php
    if($row[5]==1) echo 'On (<a href="ppc-change-publisher-traffic-analysis-status.php?action=disable&m=1&id='.$row[0].'&url='.$url.'">Disable</a> )' ;
    else echo 'Off (<a href="ppc-change-publisher-traffic-analysis-status.php?action=enable&m=1&id='.$row[0].'&url='.$url.'">Enable</a> )' 
   ?>
</td>
<td>
   <?php
    if($row[6]==1) echo 'Warned';// (<a href="ppc-change-publisher-traffic-analysis-status.php?action=disable&m=2&id='.$row[0].'&url='.$url.'">disable</a> )' ;
    else echo 'Not Warned';// (<a href="ppc-change-publisher-traffic-analysis-status.php?action=enable&m=2&id='.$row[0].'&url='.$url.'">enable</a> )' 
   ?>
</td>
<td>
   <?php
    if($row[7]==1) echo 'On (<a href="ppc-change-publisher-traffic-analysis-status.php?action=disable&m=3&id='.$row[0].'&url='.$url.'">Disable</a> )' ;
    else echo 'Off (<a href="ppc-change-publisher-traffic-analysis-status.php?action=enable&m=3&id='.$row[0].'&url='.$url.'">Enable</a> )' 
   ?>
</td>
  </tr>


<?php
$i++;
}

?>
</table>
  <br>
<table border="0" width="100%">
<tr>
  <td colspan="2" ><br><?php if($total>=1) {?>
Showing Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
<?php } ?>
&nbsp;&nbsp; </td>
  <td width="50%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","view-traffic-enabled-publishers.php?status=$status_value&country=$country_code"); ?></td>
</tr>
</table>
<?php 
}
else
{
echo"<br>No Records Found<br><br>";
}
include("admin.footer.inc.php"); ?>