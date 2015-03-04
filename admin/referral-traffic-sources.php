<?php 







/*--------------------------------------------------+



|													 |



| Copyright © 2006 http://www.inoutscripts.com/      |



| All Rights Reserved.								 |



| Email: contact@inoutscripts.com                    |



|                                                    |



+---------------------------------------------------*/

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


include("admin.header.inc.php"); 

$pidstr="";
$ridstr="";
$userid=0;
if(isset($_GET['pid']))
{ 
	$userid=intval($_GET['pid']);
	if($mysql->total("ppc_publishers","uid='$userid'")==0)
	{
		echo '<br><br><span class="already"> - No Such Publisher -  </span><a href="javascript:history.back(-1)"><strong>Go Back</strong></a><br><br>';
		include("admin.footer.inc.php");
		exit(0);
	}
	$pidstr=" and rid= ".$userid;
	$ridstr='&pid='.$userid;
}

$flag_time=0;

if(!isset($_GET['statistics']))
{	$show="day";	}
else
{ $show=$_GET['statistics']; }


if($show=="day")
{
//$showmessage="Yesterday";
$showmessage="Last 24 Hours";
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$flag=1;
//$time=mktime(0,0,0,date("m",time()),date("d",time())-1,date("y",time()));
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


$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20; 


if($flag==1)
{
$tablename="referral_daily_visits";
}
else
{
$tablename="referral_visits";
}



$result=mysql_query("SELECT ref_url,host_name, sum( unique_hits ) as cnt , sum( repeated_hits ) FROM $tablename where time>=$time $pidstr GROUP BY ref_url ORDER BY cnt desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);


//echo "SELECT ref_url,host_name, sum( unique_hits ) as cnt , sum( repeated_hits ) FROM $tablename where time>=$time $pidstr GROUP BY ref_url ORDER BY cnt desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;


$total=$mysql->echo_one("select count(distinct ref_url ) from $tablename where time>=$time $pidstr");
//if($mysql->echo_one("select count( ref_url ) from referral_visits where time>$time $pidstr")<$mysql->echo_one("select count( id ) from referral_visits where time>$time $pidstr"))
	//$total+=1;
?>
<style type="text/css">
<!--
.style1 {
	color: #006600;
	font-weight: bold;
}
.style1 {color:black;
	font-weight: bold;}

-->
</style>


<table   border="0" width="100%" cellpadding="0" cellspacing="0">
<tr><td   height="65" colspan="4" scope="row" class="heading">
  Referral Statistics</td></tr>
  </table>
  
  <table width="100%"   border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #CCCCCC;  ">
    <tr>
      <td  valign="top" >

<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus" >  <tr height="30px">
    <td width="50%" align="center"  id="index1_li_1" ><a href="ppc-referral-statistics.php">Referral Statistics</a></td>
    <td  align="center" id="index1_li_2"   style="background:url(images/li_bgselect.jpg) repeat-x" class="statistics">Traffic Statistics</td>
    </tr>
</table>


</td>
    </tr>
  

    <tr >
      <td width="100%" valign="top" class="container" >	

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">



  <tr>
    <td height="19" colspan="3" scope="row"><div align="center"></div></td>
  </tr>
  <tr>



    <td width="723"><span class="inserted"><?php
	echo "Referral traffic  statistics ";
	if(isset($_GET['pid']))
	{ 
	//echo "xc";
		$pub_id= $userid;
		$pub_name=$mysql->echo_one("select username from ppc_publishers where uid='$pub_id'");
		//if($pub_name!="")
			echo "of  ";
			?>
			<a href=view_profile_publishers.php?id=<?php echo $userid; ?>&statistics=<?php echo $show; ?>>
			<?php
			echo "$pub_name";
	}
	else
	{
		echo " of  all publishers";
	}
 
	?></a>
	<?php if(isset($_GET['pid']))
 		 {?>
	. To view  statistics of all publishers, <a href="referral-traffic-sources.php?statistics=<?php echo $show;?>">click here</a> </span>
  
  <?php }?>
	
	</td>
    </tr>



  <tr>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <td><form name="form1" method="get" action="referral-traffic-sources.php">
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
	    <?php if(isset($_GET['pid']))
  {?>
	  <input type="hidden" name="pid" value="<?php echo $userid; ?>">
	  <?php }?>
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
   <tr>
    <td>&nbsp;</td>
    </tr>
  <?php
  }
  ?>
  </table>
  	<?php 
	if(mysql_num_rows($result)==0)
	{
	?>
	   <br /> No Records Found  <br /><br />


	 <?php 
	}
	else
	{
	
	?>



<table width="100%"  border="0" cellspacing="0" cellpadding="0" >
  <tr >
    <td >  <?php if($total>=1) {?>     Referring URLs<span class="inserted"> <?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
       &nbsp;&nbsp; . <?php } ?>	   </td>
    <td colspan="3" align="right" ><?php echo $paging->page($total,$perpagesize,"","referral-traffic-sources.php?statistics=$show{$ridstr}"); ?></td>
    </tr>
	</table>

 
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">

 
      <tr class="headrow">
        <td width="46%" style="padding-left:3px;"><strong>URL</strong></td>
        <td><strong>Hostname </strong></td>
        <td><strong>Unique Hits </strong></td>
        <td><strong>Repeated Hits </strong></td>
        </tr>
	  <?php
	  $i=1;
	  while($row=mysql_fetch_row($result)) { ?>
	
	
      
      <tr <?php if($i%2==1) { ?>class="specialrow" <?php } ?>  height="28">
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><?php 
		if($row[0]=="")//unknown url
			echo "Unknown";
		else
		echo(wordwrap($row[0], 40, "<br>", 1));
			 ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><div style="padding:0px 5px 0px 5px;"><?php 
		if($row[0]=="")//unknown url => unknown host
			echo "Unknown";
		else
			echo $row[1]; ?></div></td>
        <td style="border-bottom: 1px solid #b7b5b3;" align="center"><?php 
		//if($row[0]=="")//unknown url => unknown host
		//{
				//echo $mysql->total("referral_visits", "ref_url is null or ref_url='' and time>$time $pidstr");
		//}
		//else
			echo  $row[2]; ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" align="center"><?php
		//if($row[0]=="")//unknown url => unknown host
		//{
				//echo $mysql->echo_one("select count(id) from referral_visits where ref_url is null or ref_url='' and duplicate_status=0 and time>$time $pidstr");
		//}
		//else
		  //  echo $mysql->echo_one("select count(id) from referral_visits where ref_url='$row[0]' and duplicate_status=0 and time>$time $pidstr");
		echo  $row[3]; 
	?></td>
        </tr>
		

	<?php  $i++; } ?>	
</table>

	<table width="100%"  border="0" cellspacing="0" cellpadding="0"  >
    <tr>
    <td ><?php if($total>=1) {?>         Referring URLs <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp; .   </td>
    <td colspan="3" align="right" ><?php echo $paging->page($total,$perpagesize,"","referral-traffic-sources.php?statistics=$show{$ridstr}"); ?></td>
    </tr>
	</table>	
	
  <?php  } ?>	
   
  
 </td>
 </tr>
 </table> 
<br />

<?php include("admin.footer.inc.php"); ?>



