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

//filtering group

$gp_value=1;

if(isset($_REQUEST['gp']))
{

if($_REQUEST['gp']==1)
{
$gp_value=1;
}
if($_REQUEST['gp']==2)
$gp_value=2;

}

if($gp_value==1)
{
 $name="Publishers";
 $name1="pid";
 $str_gp="group by pid";
}
else 
{
 $name="IPs";
 $name1="ip";
 $str_gp="group by ip";
}

//filtering time



$flag_time=0;

//echo $_REQUEST['statistics'];
if(!isset($_REQUEST['statistics']))
{	$show="day";	}
else
{ $show=$_REQUEST['statistics']; }


if($show=="day")
{
//$showmessage="Yesterday";
$showmessage="Last 24 Hours";

//$time=mktime(0,0,0,date("m",time()),date("d",time())-1,date("y",time()));
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$flag=1;
}
else if($show=="week")
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-14,date("Y",time()));//$end_time-(14*24*60*60);
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-30,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
}
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",time())+1-12,1,date("Y",time()));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",time()));
}
else if($show=="all")
{
	$flag_time=2;
	$showmessage="All Time";
	$time=0;

}
else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-14,date("Y",time()));//$end_time-(14*24*60*60);

}

//*********************



//filtering group


$type=0;


if(isset($_REQUEST['type']) )
{
$type=$_REQUEST['type'];
phpsafe($type);
}

$str_type=" publisherfraudstatus ='$type' and ";
//filtering country


$result=mysql_query("select $name1,count($name1) as cnt  from  ppc_fraud_clicks where $str_type  clicktime>=$time  $str_gp order by cnt desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

if(mysql_num_rows($result)==0 && $pageno>1)
	{
		$pageno--;
		$result=mysql_query("select  $name1,count($name1) as cnt  from ppc_fraud_clicks where $str_type  clicktime>=$time  $str_gp order by cnt desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

	}
//echo "select  $name1,count($name1) as cnt  from ppc_fraud_clicks where $str_type  clicktime>=$time  $str_gp order by cnt desc ";die;

	$total=$mysql->echo_one("select count(distinct $name1) from ppc_fraud_clicks  where $str_type   clicktime>=$time");
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
   <td   colspan="4" scope="row" class="heading">Fraud <?php echo $name; ?>  </td>
  </tr>
</table>
<br>


<form name="ads" action="fraud-analysis.php" method="get">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
	  <td>Type of click</td>
	  <td><select name="type" id="type" >
			   <option value="0" <?php if($type=="0")echo "selected";	  ?>>Repetitive click</option>
			   <option value="1" <?php if($type=="1")echo "selected";	  ?>>Publisher fraud click</option>
			   <option value="2" <?php if($type=="2")echo "selected";	  ?>>Invalid IP click</option>
			   <option value="3" <?php if($type=="3")echo "selected";	  ?>>Proxy click</option>
			   <option value="4" <?php if($type=="4")echo "selected";	  ?>>Bot click</option>
			    <option value="5" <?php if($type=="5")echo "selected";	  ?>>Invalid GEO click</option>
			  </select>&nbsp;&nbsp;&nbsp;


</td>
<td>





  
  Period  
      
    <select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?>>Today</option>	 
        <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 days</option>
        <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 days</option>
        <option value="year"  <?php 
			  				  if($show=="year")echo "selected";			  
			  ?>>Last 12 months</option>
        <option value="all"  <?php 
			  				  if($show=="all")echo "selected";			  
			  ?>>All Time</option>
      </select>
     







</td>
    <td height="34" colspan="">Group result by</td>
    <td><select name="gp" id="gp" >
<option value="1" <?php	if($gp_value=="1")echo "selected";	  ?>>Publishers</option>
<option value="2" <?php 	if($gp_value=="2")echo "selected";	 ?>>ips</option>
			  
</select>   &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" value="Submit">
	  </td>
  </tr>
  </table> </form> 
  <br>
<?php
if($total>0)
{
 ?>




<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td height="34" colspan="2" ><?php if($total>=1) {?>   Showing <?php echo $name; ?> <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>  </td>
    <td width="50%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","fraud-analysis.php?status=$status_value&country=$country_code"); ?></td>
  </tr>
</table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable">
  <tr class="headrow">
    <td width="33%"><strong> <?php echo $name; ?></strong></td>
    <td width="34%"><strong>Country</strong></td>   
	<td width="33%"><strong>Count</strong></td>    
  </tr>
<?php
$i=1;

  	include("../geo/geoip.inc");
  	$gi = geoip_open("../geo/GeoIP.dat",GEOIP_STANDARD);

while($row=mysql_fetch_array($result))
{
?>
  <?php 
  if($gp_value==1) //publishers
  {?>
  <tr <?php if(($i-1)%2==1) { ?>class="specialrow" <?php }?>>
    <td height="28" >
	<?php if($row[0]==0) {echo "Admin"; } else {?>
	<a href="view_profile_publishers.php?id=<?php echo $row[0]; ?>"><?php echo  $mysql->echo_one("select username from ppc_publishers  where uid=$row[0] ");?></a>
	<?php }?>
	</td>
    <td height="28" ><?php echo  $mysql->echo_one("select b.name from ppc_publishers a ,location_country b where a.uid=$row[0] and b.code=a.country");?></td>
   <td ><?php	echo $row[1];	?></td> </tr>
  <?php }  else {
  
    $record = geoip_country_code_by_addr($gi, $row[0]);
  	?>
  <tr <?php if(($i-1)%2==1) { ?>class="specialrow" <?php }?>>
  <td height="28" ><?php echo $row[0];?></td>
  <td height="28" ><?php echo  $mysql->echo_one("select b.name from location_country b where  b.code='$record'");?></td>
   <td ><?php	echo $row[1];	?></td> </tr>
             <?php } ?>
  


<?php
$i++;
}

?>
</table>
  <br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td colspan="2" ><br><?php if($total>=1) {?>
Showing <?php echo $name; ?> <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
<?php } ?>
&nbsp;&nbsp; </td>
  <td width="50%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","fraud-analysis.php?status=$status_value&country=$country_code"); ?></td>
</tr>
</table>
<?php 
}
else
{
echo"<br>No Records Found<br><br>";
}
include("admin.footer.inc.php"); ?>