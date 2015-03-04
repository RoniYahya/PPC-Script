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
include("../publisher_statistics_utils.php");


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

$url=urlencode($_SERVER['REQUEST_URI']);

?><?php include("admin.header.inc.php"); ?>
<?php
$show="";
$showmessage="";
$time=0;
$day_flag=0;



/*
if(!isset($_GET['warn_status']))
{	$warn_status=1;	
}
else
{ $warn_status=$_GET['warn_status']; }
*/






if(!isset($_GET['statistics']))
{	$show="day";	
}
else
{ $show=$_GET['statistics']; }


if($show=="day")
{
$flag_time=-1;
$day_flag=1;
$showmessage="Last 24 Hours";
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
}

 if($show=="week")
{
$flag_time=0;
$showmessage="Last 14 Days";
$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));
}
else if($show=="month")
{
$flag_time=0;
$showmessage="Last 30 days";
$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time()));
}



/*
$warn_str="";
if($warn_status==2)
	{
		$warn_str=" and  p.warning_status=1";
	}
elseif ($warn_status==3)
	{
		$warn_str="and p.warning_status=0";
	}
*/	
?>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/fraud.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">CTR Based Fraud Detection</td>
  </tr>
</table><br />

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    
    <td colspan="3"><form name="form1" method="get" action="ppc-ctr-fraud-detection.php">
        Show Status
            <select name="statistics" id="statistics">
            
            <option value="day" <?php 
			  				  if($show=="day") echo "selected";			  
			  ?>>Today</option>
              
              <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 Days</option>
              <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 Days</option>
            </select>
			
			<!--<select name="warn_status">
				 <option value="1" <?php 
			  				 // if($warn_status==1) echo "selected";			  
			  ?>>ALL users</option>
              
              <option value="2"  <?php 
			  				//  if($warn_status==2)echo "selected";			  
			  ?>>Warned users</option>
			   <option value="3"  <?php 
			  				 // if($warn_status==3)echo "selected";			  
			  ?>>Non Warned users</option>
			</select>-->
			
			
			
            <input type="submit" name="Submit" value="Show Statistics">
			
    </form></td>
  </tr>
  
</table>
 
 
  
<?php if($show !="day")
 {
?>
<br />
<!--Note:Last 24 hours data are not included in the detailed statistics<br />-->

<?php
}

	     $perpagesize=30;
         $pageno=1;
         if(isset($_GET['page']))
	     $pageno=getSafePositiveInteger('page');
		 
  $spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));		 
  $endtimes=mktime(date("H",time())+1,0,0,date("m",time()),date("d",time()),date("y",time()));
		 
  if($day_flag==0)	
  {
  //$countno=mysql_query( "select a.uid  from publisher_daily_statistics a, ppc_publishers p where a.uid=p.uid and a.uid<>0 and a.time>'$time'  group by a.uid ");
  
  $countno=mysql_query("select uids from (select a.uid as uids  from publisher_daily_statistics a  where a.uid<>0 and a.time>'$time'  group by a.uid UNION select a.pid as uids from ppc_daily_clicks a where  a.pid<>0 and a.time>'$spec_time_limits' and a.time <'$endtimes' GROUP BY a.pid)x group by uids");
  
  
  $total= mysql_num_rows($countno);
  
  
 // $countnonew=mysql_query( "select a.pid from ppc_daily_clicks a, ppc_publishers p where a.pid=p.uid and  a.pid<>0 and a.time>'$spec_time_limits' and a.time <'$endtimes' GROUP BY a.pid");
 // $totalnew= mysql_num_rows($countnonew);
 // $total=$total+$totalnew;
  
  
  
  }
  else	
  {
  $countno=mysql_query( "select a.pid from ppc_daily_clicks a, ppc_publishers p where a.pid=p.uid and  a.pid<>0 and a.time>'$time' and a.time <'$endtimes'  GROUP BY a.pid");
  $total= mysql_num_rows($countno);
  }
	
  if($total>0)
  {
	 
	
     
$norow=0;
if($day_flag==0)	
{


$norow=mysql_query("select uids,sum(c) from (select a.uid as uids,sum(a.clk_count) as  c  from publisher_daily_statistics a  where a.uid<>0 and a.time>'$time'  group by a.uid UNION select a.pid as uids,count(a.id) as c from ppc_daily_clicks a where  a.pid<>0 and a.time>'$spec_time_limits' and a.time <'$endtimes' GROUP BY a.pid)x group by uids order by c desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);





   //$norow=mysql_query("select a.uid ,sum(a.clk_count) as  c, sum(a.impression) as i from publisher_daily_statistics a , ppc_publishers p where a.uid=p.uid and a.uid<>0 and a.time>'$time'   group by a.uid order by c desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	
	
	
	
	
	
	
	
}
else	
{
  	$norow=mysql_query("select p.uid , count(a.id) as c from ppc_daily_clicks a, ppc_publishers p where a.pid=p.uid and   a.pid<>0 and a.time>'$time' and a.time <'$endtimes'  GROUP BY p.uid order by c desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
}

  //echo mysql_error(); 
 
 
 // if(mysql_num_rows($norow)>0)
 
 
 if($total>0)
  {
  
  ?>
  <br />
<span class="note">
<strong>Note : </strong>The clicks shows here are <strong>not</strong> detected as fraud by the system. The data is displayed in the descending order of click count.  If you feel that the CTR of any publisher  is abnormal, you may warn/block him.</span><br />

  
 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  <tr>
    <td colspan="2" scope="row"><?php if($total>=1) {?>
Showing clicks <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
<?php } ?></td>
    <td width="50%" align="right" scope="row"><?php echo $paging->page($total,$perpagesize,"","ppc-ctr-fraud-detection.php?statistics=$show"); ?></td>
   </tr>
 </table>


		  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
      
        <tr class="headrow">
        <td>Publisher Name</td>
        <td>Already Warned?</td>
        <td width="14%" >No of Impressions </td>
		<td width="13%">No Of Clicks</td>
		<td width="6%">CTR (%) </td>
		<td>Actions</td>
        </tr>
		  
  <?php
  $i=0;
  	while($idrow=mysql_fetch_row($norow))
		{
			$impression= 0;
			$clciks=0;
			$ctr=0;
			$warn=0;
			$uname=0;
			$uid=0;
//echo "SELECT username,warning_status FROM  ppc_publishers uid='$idrow[0]' ";
$res=mysql_query("SELECT username,warning_status FROM  ppc_publishers where uid='$idrow[0]' ");
$row=mysql_fetch_row($res);
$warn=$row[1];
$uname=$row[0];
$uid=$idrow[0];

if($day_flag==0)	
{
	
//$impression= $idrow[2];
$clciks=$idrow[1];
$impression=getPubAdImpressions($time,$mysql,$idrow[0],$flag_time);
//$clciks=getPubAdClicks($time,$mysql,$idrow[0],$flag_time);



if($impression>=0 || $impression=="")
$ctr=$clciks/$impression;
else
$ctr=0;
}
else
{

		$impression= $mysql->echo_one("select COALESCE(sum(cnt),0) from publisher_impression_daily where pid='$idrow[0]' and time>='$time'");//$row[1];
		$clciks=$idrow[1];
		if($impression>=0 || $impression=="")
		$ctr=$clciks/$impression;
			
}



  
  
  
  
		
       
		 
		
			 
		 ?>
        
        <tr <?php if($i%2==1)  { ?>class="specialrow" <?php }?>>
          <td><a href="view_profile_publishers.php?id=<?php echo $idrow[0]; ?>"><?php echo $uname;?></a></td>
		  <td><?php if ($warn==1) echo "Yes"; else echo "No";?></td>
		  <td><?php echo numberFormat($impression,0); ?></td>
		  <td><?php echo numberFormat($clciks,0);?></td>
		  <td><?php echo  numberFormat(($ctr*100)) ;?></td>
          <td width="20%" ><a href="ppc-warn-publisher-ad.php?type=7&id=<?php echo $uid; ?>&url=<?php echo $url; ?>	">Warn</a> | 
		  <a href="ppc-block-publisher-ad.php?type=8&id=<?php echo  $uid; ?>&url=<?php echo $url; ?>	">Block</a></td>
        </tr>
       
    
   <?php  $i++; } ?>
   </table>
   
   
<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td colspan="2"><?php if($total>=1) {?>
Showing clicks <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
<?php } ?></td>
    <td width="50%" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-ctr-fraud-detection.php?statistics=$show"); ?></td>
  </tr>
  
  
 <!-- <tr>
    <td colspan="2" class="info"><br />Note :Clicks from XML API are not counted !&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr> -->
</table>
  <?php 
  }
  ?>
  <?php
}

?>
<?php
if($total==0)
	echo"<br> No Records Found <br><br>";

?>






<?php include("admin.footer.inc.php"); ?>
