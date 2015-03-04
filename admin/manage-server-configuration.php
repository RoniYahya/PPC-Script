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
?>
<?php include("admin.header.inc.php"); ?>
<?php
include("../publisher_statistics_utils.php");




?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/loadbalance.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Server Allotment</td>
  </tr>
</table>

<br />
 
  
   <?php
   
   
   $pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;


    $url=urlencode($_SERVER['REQUEST_URI']);
$flag_time=0;	
if(isset($_POST['statistics']))
{	$show=$_POST['statistics'];	}

else
{
	$show="week";
}
   
if($show=="week")
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}

else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
		 
  ?>
		  
		  
		  
<form name="form1" method="post" action="manage-server-configuration.php">

  <strong>Choose Duration</strong>&nbsp;&nbsp;<select name="statistics" id="statistics">
	 
        <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 days</option>
        <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 days</option>
       
      </select>	   <input type="submit" name="Submit" value="Show"> 
</form>
<br />

 <span class="inserted"><strong><?php echo $showmessage; ?> impressions of publishers (excluding last 24 hrs) sorted by impression count</strong></span><br />

<?php
//$result=mysql_query("select uid,username from ppc_publishers  where status=1 order by uid DESC");


$total=mysql_query("select p.uid,p.username,COALESCE(sum(impression),0) as imp,p.server_id from ppc_publishers p left outer join publisher_daily_statistics s on p.uid=s.uid where p.status=1 and s.time >=".$time." and s.impression <>0 group by p.uid order by imp DESC");

$result_no=mysql_num_rows($total);


$result=mysql_query("select p.uid,p.username,COALESCE(sum(impression),0) as imp,p.server_id from ppc_publishers p left outer join publisher_daily_statistics s on p.uid=s.uid where p.status=1 and s.time >=".$time." and s.impression <>0 group by p.uid order by imp DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);




if($result_no>0)
	{  
	?>
	
	
	 <table width="100%"  border="0" cellpadding="0" cellspacing="0">

	<tr>
	<td colspan="2" ><?php if($result_no>=1) {?>   Showing Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$result_no) echo $result_no; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $result_no; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td><td  colspan="2" align="right">
   
    <?php echo $paging->page($result_no,$perpagesize,"","manage-server-configuration.php?statistics=$show"); ?>
    </td>
  </tr>
  </table>
	 <?php	
	}
	


if($result_no >0)
{


?>
<form name="form1" method="post" action="new-server-configuration-action.php">
 <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">

 <tr  class="headrow">
    <td width="22%"  ><strong> Name</strong></td>
    <td width="11%" ><strong>Id</strong></td>
    <td width="13%" ><strong>Impressions</strong></td>
    <td width="18%" ><strong>Range Server</strong> </td>
    <td width="36%" ><strong>Override Server</strong></td>
    </tr>
<?php
$i=1;

$servers=mysql_query("select id,name,min_range,max_range,srv_type from  server_configurations"); // where srv_type=1 or (srv_type=2 and status='111')

$servers_no=mysql_num_rows($servers);
$url=urlencode($_SERVER['REQUEST_URI']);
$uid_strs="";
while($row=mysql_fetch_row($result))
{


mysql_data_seek($servers,0);

$serverstr="";
$serverstr.='<option value="0">--Select Server--</option>';

if($servers_no >0)
{

while($servers_row=mysql_fetch_row($servers))
{
if($servers_row[4] ==1)
$srvstr="Application Server";
else if($servers_row[4]==2)
$srvstr="Load Balancing Server";


$serverstr.='<option value="'.$servers_row[0].'"';
if($row[3]==$servers_row[0])
$serverstr.=' selected ';


$serverstr.='>'.$servers_row[1].' ('.$servers_row[2].'-'.$servers_row[3].')-'.$srvstr.'</option>';

}
}








//$total_impressions=0;
//$total_impressions=getPubAdImpressions($time,$mysql,$row[0],$flag_time);
//$row[3]

$uid_strs.=$row[0]."_";
?>
 
 <tr <?php if($i%2==1) { ?> class="specialrow" <?php } ?>>
  <td ><?php echo $row[1]; ?><input type="hidden" name="uid_<?php echo $row[0]; ?>" id="uid_<?php echo $row[0]; ?>" value="<?php echo $row[0]; ?>" /></td>
  <td ><?php echo $row[0]; ?></td>
  <td ><?php echo $row[2];?></td>
  <td ><?php $range_ser= $mysql->echo_one("select name from server_configurations where min_range <= $row[0] and max_range>=$row[0] ");//and (srv_type=1 or (srv_type=2 and status='111'))
  if($range_ser=='') $range_ser = $mysql->echo_one("select name from server_configurations where srv_type=1"); 
   echo $range_ser; ?></td>
  <td >
<select name="srv_<?php echo $row[0]; ?>" id="srv_<?php echo $row[0]; ?>">
<?php echo $serverstr; ?>
</select>  <?php if($row[3]>0) echo " ( <a href=\"reset-server.php?pid=$row[0]&url=$url\">Reset</a> ) "; ?> </td>
  </tr>
 
  <?php
  $i=$i+1;
  }
  
  ?>

  <tr <?php if($i%2==1) { ?> class="specialrow" <?php } ?>>
  <td  colspan="3"></td>
  <td align="left">&nbsp;</td>
  <td align="left"><input type="hidden" name="uid_strs" id="uid_strs" value="<?php echo $uid_strs; ?>" /><input type="submit" id="act" name="act" value="Submit" /></td> 
  </tr>  </table>
</form> 
  <?php
  
  
	}
	
	  if($result_no>0)
	{  
	?>
	
	
	 <table width="100%"  border="0" cellpadding="0" cellspacing="0">

	<tr>
	<td colspan="2" ><?php if($result_no>=1) {?>   Showing Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$result_no) echo $result_no; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $result_no; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td><td  colspan="2" align="right">
   
    <?php echo $paging->page($result_no,$perpagesize,"","manage-server-configuration.php?statistics=$show"); ?>
    </td>
  </tr>
  </table>
	 <?php	

  
  }
  else
  {
  ?>
   <br />
 No records found 
  
  <?php
  
  }
  
  
  
  
  
  ?>
  
  
 
  
 



<?php include("admin.footer.inc.php"); ?>