<?php 







/*--------------------------------------------------+



|													 |



| Copyright © 2006 http://www.inoutscripts.com/      |



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



$flag_time=0;


if(!isset($_GET['statistics']))
{	$show="day";	}
else
{ $show=$_GET['statistics']; }


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


$reg_start_time=mktime(0,0,0,date("m",$time),date("d",$time)-1,date("Y",$time));
$reg_end_time=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));

//$result=mysql_query("select * from ppc_publishers order by uid");
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
$result=mysql_query("select * from ppc_publishers where status=1 order by uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from ppc_publishers where status=1");


?>
<style type="text/css">
<!--
.style1 {
	color: #006600;
	font-weight: bold;
}
.style1 {color: white;
	font-weight: bold;}
-->
</style>


<table   border="0" width="100%"><tr><td   height="65" colspan="4" scope="row" class="heading">
  Referral Statistics</td></tr></table>
  
  <table width="100%"   border="0" cellspacing="0" cellpadding="0"  style="border-color:#CCCCCC; border-width:1px; border-top-width:1px; border-style:solid; ">
    <tr>
      <td  valign="top" >




<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus" >
  <tr height="30px">
<td width="50%" align="center"  id="index1_li_1"  style="background:url(images/li_bgselect.jpg) repeat-x" class="statistics">Referral Statistics</td>
    <td  align="center" id="index1_li_2" ><a href="referral-traffic-sources.php" >Traffic Statistics</a></td>
    </tr>
</table>



</td>
    </tr>
  

    <tr >
      <td width="100%" valign="top" class="container" >	

<table width="100%"  border="0" cellspacing="0" cellpadding="0">



  <tr>
    <td height="19" colspan="3" scope="row"><div ></div></td>
  </tr>

  <tr>
    <td width="718"><form name="form1" method="get" action="ppc-referral-statistics.php">
  Show statistics as of  
      
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
      <input type="submit" name="Submit" value="Show Statistics">
    </form></td>
    </tr>
  
  <?php
   if($show!="day")
   {
   ?>
    <!--<tr>
    <td>&nbsp;</td>
    <td><strong>Note:</strong><span class="info"> Last 24 hours data are not included in the statistics details.</span><br>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>-->
   
  <?php
  }
  ?>
  </table>
  	<?php 
	if(mysql_num_rows($result)==0)
	{
	?><br /> No Records Found <br /><br />

	 <?php 
	}
	else
	{
	
	?>
  

	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">

		  <tr>
    <td colspan="3" ><?php if($total>=1) {?>   Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp; .   </td>
    <td colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-referral-statistics.php?statistics=$show"); ?></td>
  </tr>
  </table>
  	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">


         
        <tr class="headrow">
        <td width="21%" style="padding-left:3px;"><strong>Publisher </strong></td>
        <td><strong>Unique Hits</strong></td>
        <td><strong>Repeated Hits</strong></td>
        <td><strong>Advertiser Signups </strong></td>
        <td><strong>Publisher Signups </strong></td>
        <td>&nbsp;</td>
        </tr>
	  <?php
		$i=1;
	  while($row=mysql_fetch_row($result)) { 

if($flag==1)
{
$tablename="referral_daily_visits";
}
else
{
$tablename="referral_visits";
}
	$referral_result=mysql_fetch_row( mysql_query("select COALESCE(sum(unique_hits),0), COALESCE(sum(repeated_hits),0) from $tablename where rid=$row[0] and time>=$time "));

//echo "select COALESCE(sum(unique_hits),0), COALESCE(sum(repeated_hits),0) from $tablename where rid=$row[0] and time>=$time ";



	?>
      <tr <?php if($row[4]!=1)   echo 'bgcolor="#F8B9AF"';  elseif($i%2==1) { ?>class="specialrow" <?php } ?> height="28">
        <td style="border-bottom: 1px solid #b7b5b3; "><a href="view_profile_publishers.php?id=<?php echo $row[0] ; ?>"><?php echo $row[1]; ?></a></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php echo $referral_result[0];    ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php echo $referral_result[1];    ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php echo $mysql->total("ppc_users","rid=$row[0] and regtime>=$reg_start_time and regtime<=$reg_end_time");  	?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php echo $mysql->total("ppc_publishers","rid=$row[0] and regtime>$reg_start_time and regtime<=$reg_end_time"); 	?></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><a href="referral-traffic-sources.php?pid=<?php echo $row[0]; ?>&statistics=<?php echo $show;?>">Traffic sources</a></td>
        </tr>

	<?php $i++; } ?>	
		  </table>

		  	<table width="100%"  border="0" cellspacing="0" cellpadding="0">

		  <tr>
    <td colspan="3" ><?php if($total>=1) {?>   Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp; .   </td>
    <td colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-referral-statistics.php?statistics=$show"); ?></td>
  </tr>
	</table>	


  <?php  } ?>	
</td>
</tr>
</table>	<br />

<?php include("admin.footer.inc.php"); ?>



