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
include_once("../graphutils.inc.php");
	if(!isset($_GET['id']))
		{
			$id=$_REQUEST['userid'];
		}
	else
		{
		$id=getSafePositiveInteger('id','g');
		}
//echo $id;
//exit(0);
//echo "select * from server_configurations where min_range<='$id' and '$id' <= max_range";
$curr_range_server=mysql_query("select * from server_configurations where min_range<='$id' and '$id' <= max_range");
//$curr_range_server_row=mysql_fetch_array($curr_range_server);
 if(mysql_num_rows($curr_range_server)==0) 
 {
 $curr_range_server = mysql_query("select * from server_configurations where srv_type=1"); 
 }
 $curr_range_server_row=mysql_fetch_array($curr_range_server);
if(isset($_GET['srvs']) && isset($_GET['id']))
{
$srvs=$_GET['srvs'];
$id=$_GET['id'];
//echo $srvs,$curr_range_server;
//if($srvs==$curr_range_server_row[0])
	//mysql_query("update ppc_publishers set server_id='0' where uid='$id'");
//else
	mysql_query("update ppc_publishers set server_id='$srvs' where uid='$id'");

}
 

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
//new statistics added on 18-August-2009
$flag_time=0;
//new statistics added on 18-August-2009
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 10;
$result=mysql_query("select * from ppc_publishers where uid='$id'");
$row=mysql_fetch_array($result);
//$total=$mysql->echo_one("select count(*) from ppc_publishers where uid='$id'");
if(!$row)
{
	 include("admin.header.inc.php");
	echo "<br><br><span class=\"already\">Invalid publisher id.</span><br>";
	include("admin.footer.inc.php");
	exit(0);
}
include("../publisher_statistics_utils.php");

$p_name=$row[1];


if(isset($_REQUEST['statistics']))
{	$show=$_REQUEST['statistics'];	}
else
{
	$show="day";
}
$url=$_SERVER['REQUEST_URI'];

  $string = $url;
  if(stristr($string, 'statistics') === FALSE) {
    $url=$url."&statistics=$show";
  }
$url=urlencode($url);



if($show=="day")
{
$flag_time=-1;	
$showmessage="Today";
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));
$beg_time=$time;

//echo date("d M h a",$end_time);
}
else if($show=="week")
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
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",time())+1-11,1,date("Y",time()));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time())+1,1,date("y",time()));
$beg_time=$time;
}
else if($show=="all")
{
	$flag_time=2;
	$showmessage="All Time";
	$time=0;
	$end_time=mktime(0,0,0,1,1,date("y",time())+1);
$beg_time=$time;

}
else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}


$reg_start_time=mktime(0,0,0,date("m",$time),date("d",$time)-1,date("Y",$time));
$reg_end_time=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
$referral_table="";

if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
		$referral_table="daily_referral_statistics";
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="publisher_yearly_statistics";
		$referral_table="monthly_referral_statistics";
	 	}
	 else
	 {
		$table_name="publisher_monthly_statistics";
		$referral_table="yearly_referral_statistics";
	 }
//echo $show;

$curr_stat="";
 if($row[4]==1)
			  	{
  				$stat_str="ppc-change-publisher-status.php?action=block&id=$row[0]&url=$url";
				$curr_stat= "Active";
				$check="Block";
				}
			else if($row[4]==-1)
				{
  				$stat_str="ppc-approve-publisher.php?action=activate&id=$row[0]&url=$url";
  				$stat_str_reject="ppc-disapprove-publisher.php?action=reject&id=$row[0]&url=$url";
				$curr_stat = "Pending";
				$check="Approve";
  				}
			else if($row[4]==-2)
				{
  				$stat_str="ppc-approve-publisher.php?action=activate&id=$row[0]&url=$url";
  				$stat_str_reject="ppc-disapprove-publisher.php?action=reject&id=$row[0]&url=$url";
				$curr_stat= "Email not verified";
				$check="Approve";
  				}			
			else
				{
  				$stat_str="ppc-change-publisher-status.php?action=activate&id=$row[0]&url=$url";
				$curr_stat= "Blocked";
				$check="Activate";
  				}
		if($row[19]!=0)
	{
		$type_value=3;	
	}
	else
	$type_value=1;				

include("admin.header.inc.php"); ?>

<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("Do you really want to delete this publisher.")
		if (answer)
			return true;
		else
			return false;
		}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/publishers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Publisher Profile</td>
  </tr>
</table>
<br />

<table width="100%"   cellpadding="0" cellspacing="0" > <!--style="background-color:#FDFDFD">-->
	<tr >
	  <td height="22" align="left" style="border-bottom:1px solid #CCCCCC"><strong>Primary Details</strong> </td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	<tr align="left">
 		 <td width="333" height="22">Username</td>
  		 <td width="17" ><strong>:</strong></td>
	    <td width="942"><?php echo $row[1];?> <?php if($check=='Block'){ echo '( <a href="member-login.php?type=1&id='.$row[0].'">Login</a> )'; }?></td>
	</tr>
	<tr>
 
  <td height="22" align="left">First name </td>
  <td ><strong>:</strong></td>
  <td align="left"><?php echo $row[15];?>&nbsp;</td>
</tr>
<tr>
  
  <td height="22" align="left">Last name </td>
  <td ><strong>:</strong></td>
  <td align="left"><?php echo $row[16];?>&nbsp;</td>
</tr>
<tr>
    <td height="22" align="left">Refered by </td>
    <td ><strong>:</strong></td>
    <td align="left"><?php if($row[12]==0) echo "Nobody"; else echo '<a href="view_profile_publishers.php?id='.$row[12].'">'.$mysql->echo_one("select username from ppc_publishers where  uid=$row[12]").'</a>';?></td>
    </tr>
	<tr align="left">
	  <td height="24">Primary Domain</td>
	  <td ><strong>:</strong></td>
	  <td><?php
  $domain_string= $row[6];
  if( substr($domain_string,0,7)=="http://")
	{
	$domain_string=$domain_string;
	}
	else if(substr($domain_string,0,8)=="https://")
	{
	$domain_string=$domain_string;
	}
	else
	$domain_string="http://".$domain_string;
  ?>
  <a href="<?php echo $domain_string;?>" target="_blank"><?php echo $row[6];?></a></td>
	</tr>
	<tr >
	  <td height="22" align="left" style="border-bottom:1px solid #CCCCCC"><strong>Contact Details</strong> </td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
  </tr>
<tr>
  
  <td height="22" align="left">Phone no. </td>
  <td ><strong>:</strong></td>
  <td align="left"><?php echo $row[17];?>&nbsp;</td>
</tr>
	<tr align="left">
  		<td height="22">Email Id</td>
  		<td ><strong>:</strong></td>
  		<td><?php echo $row[3];?> ( <?php  echo '<a href="ppc-send-mail.php?type=1&id='.$row[0].'&url='.$url.'">Send Mail</a>';?> )</td>
	</tr>
	<tr align="left">
		  <td height="22">Country</td>
		  <td ><strong>:</strong></td>
		  <td><?php echo $mysql->echo_one("select name from location_country where code='$row[5]'");?></td>
	</tr>
  <tr>
  <td height="22" align="left">Address</td>
  <td ><strong>:</strong></td>
  <td align="left"><?php echo nl2br($row[18]);?>&nbsp;</td>
</tr>
	<tr >
	  <td height="22" align="left" style="border-bottom:1px solid #CCCCCC"><strong>Account Details</strong> </td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
  </tr>
	<tr>
		<td height="22" align="left">Tax Identification No.</td>
		<td align="center"><strong>:</strong></td>
		<td align="left"><?php echo $row['taxidentification']; ?>&nbsp;</td>
	</tr>
	<tr align="left">
	  <td height="22">Account balance</td>
	  <td ><strong>:</strong></td>
	  <td><?php
	   
	  $amt_under_settlement=$mysql->echo_one("select COALESCE(sum(publisher_profit),0) from `ppc_daily_clicks` where  pid='$id'");
	  $adv_referral_amt=$mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from `ppc_daily_clicks`where  pub_rid=".$id);
		$pub_referral_amt=$mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from `ppc_daily_clicks`where  adv_rid=".$id);
		
		$amt_under_settlement=$amt_under_settlement+$adv_referral_amt+$pub_referral_amt;


	  $acc_balance=$row[9]-$amt_under_settlement;
	  echo moneyFormat($acc_balance);
	  ?> &nbsp;</td>
	</tr>
	<tr align="left">
	  <td height="22">Amount under settlement</td>
	  <td ><strong>:</strong></td>
	  <td><?php echo  moneyFormat($amt_under_settlement); ?> &nbsp;</td>
	</tr>
	<tr align="left">
	  <td height="22">Last login IP </td>
	  <td >:</td>
	  <td><?php echo $row[11];?>&nbsp;</td>
    </tr>
	<tr align="left">
	  <td height="22">Last login time </td>
	  <td >:</td>
	  <td><?php echo dateTimeFormat($row[8]);?>&nbsp;</td>
    </tr>
    <tr align="left">
	  <td height="22">Single sign on </td>
	  <td >:</td>
	  <td><?php if($row[19]!=0) echo "Enabled ( <a href=\"view-user-profile.php?id=".$row[19]."\">View Account</a> )";else echo "Disabled";?>&nbsp;</td>
    </tr>
    <?php if($portal_system==1) {?>
    <tr align="left">
	  <td height="22">Portal status </td>
	  <td >:</td>
	  <td><?php if($row[20]==0) echo "Blocked";elseif($row[20]==1) echo "Active";elseif($row[20]==-2) echo "Email not verified";else echo "Pending";?>&nbsp;</td>
    </tr>
    <?php } ?>
    
    
	<tr align="left">
		  <td height="22">Publisher Status</td>
		  <td ><strong>:</strong></td>
		  <td><?php 
			  echo $curr_stat;
			  if($row[19]!=0) { 
		
		
			 	echo ' ( <a href="confirm-user-status.php?id='.$row[19].'&url='.urlencode($url).'">Change Status</a> )';
		
			 }else {?>
		( <?php 	echo '<a href="'.$stat_str.'">'.$check.'</a>';
			if($check=='Approve') {
			?> | <a href="ppc-disapprove-publisher.php?action=reject&id=<?php echo $row[0]?>&url=<?php echo $url?>">Reject</a><?php } 
			
			if($row[19]==0) echo ' | <a href="ppc-delete-publisher-action.php?id='.$row[0].'&url=ppc-view-publishers.php" onclick="return promptuser()">Delete</a> ';
			?> 
		)	
<?php } ?>		 </td>
	</tr>

        <tr align="left">
	  <td height="22">XML API </td>
	  <td ><strong>:</strong></td>
	  <td><?php if($row[10]==1) echo 'Enabled ( <a href="ppc-change-xmlstatus.php?action=disable&id='.$row[0].'&url='.$url.'">Disable</a> )';  else echo 'Disabled ( <a href="ppc-change-xmlstatus.php?action=enable&id='.$row[0].'&url='.$url.'">Enable</a> )';?></td>
    </tr> 
    	
	<tr align="left">
	  <td height="22">Traffic Analysis </td>
	  <td ><strong>:</strong></td>
	  <td>   <?php
    if($row[14]==1) echo 'Enabled ( <a href="ppc-change-publisher-traffic-analysis-status.php?action=disable&m=1&id='.$row[0].'&url='.$url.'">Disable</a> )' ;
    else echo 'Disabled ( <a href="ppc-change-publisher-traffic-analysis-status.php?action=enable&m=1&id='.$row[0].'&url='.$url.'">Enable</a> )' 
   ?></td>
    </tr>  
	<tr align="left">
	  <td height="22">Warning Staus</td>
	  <td ><strong>:</strong></td>
	  <td><?php if($row[13]==1) echo "Warned"; else echo "Not warned";?>	  </td>
  </tr>
  	
	<tr align="left">
	  <td height="22">Captcha Status </td>
	  <td ><strong>:</strong></td>
	  <td><?php if($row[22]==1) echo 'On ( <a href="ppc-change-publisher-traffic-analysis-status.php?action=disable&m=3&id='.$row[0].'&url='.$url.'">Disable</a> )' ;
    else echo 'Off ( <a href="ppc-change-publisher-traffic-analysis-status.php?action=enable&m=3&id='.$row[0].'&url='.$url.'">Enable</a> )' ;?></td>
    </tr>
  <tr align="left">
	  <td height="22">Premium Status </td>
	  <td ><strong>:</strong></td>
	  <td><?php if($row[25]==1) echo 'On ( <a href="ppc-change-publisher-traffic-analysis-status.php?action=disable&m=2&id='.$row[0].'&url='.$url.'">Disable</a> )' ;
    else echo 'Off ( <a href="ppc-change-publisher-traffic-analysis-status.php?action=enable&m=2&id='.$row[0].'&url='.$url.'">Enable</a> )' ;?></td>
    </tr>
  
  <!--	<tr>
  	
	  <td height="22" align="right">
	  <?php if($check=='Block'){?>
		<?php echo '<a href="member-login.php?type=1&id='.$row[0].'">Login</a>';?>  <strong>|</strong>
		<?php }?>
	  <?php echo '<a href="'.$stat_str.'">'.$check.'</a>'; if($check=='Approve') {?> | <a href="ppc-disapprove-publisher.php?action=reject&id=<?php echo $row[0]?>&url=<?php echo $url?>">Reject</a><?php } ?></td>
	  <td ><strong>|</strong></td>
	  <td align="left"><?php  echo '<a href="ppc-send-mail.php?type=1&id='.$row[0].'&url='.$url.'">Send Mail</a>';?>	  </td>
  </tr>-->
  
  
  
  
  <tr >
	  <td height="22" align="left" style="border-bottom:1px solid #CCCCCC"><strong>Load Balancing Details</strong> </td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
  </tr>
  <tr align="left">
    <td height="22">Alloted Server ( as per id ) </td>
    <td ><strong>:</strong></td>
    <td>
	<?php
	  if($curr_range_server_row['srv_type']==1)
	  $serstr="Application Server";
	  else if($curr_range_server_row['srv_type']==2)
	  $serstr="Load Balancing Server";
	  
	  echo $curr_range_server_row['name']." ( ".$curr_range_server_row['min_range']." - ".$curr_range_server_row['max_range']." ) ".$serstr;
	?>	</td>
  </tr>
  <tr align="left">
	  <td height="22">Specific Server Allotment</td>
	  <td ><strong>:</strong></td>
	  <td>
	  <?php if($row[21] ==0 || $row[21] ==""){ echo "Not Applicable"; }
	  else
	  {
	  $server=mysql_query("select name,min_range,max_range,srv_type from server_configurations where id='$row[21]'");
	  $serverdata=mysql_fetch_row($server);
	  if($serverdata[3]==1)
	  $serstr="Application Server";
	  else if($serverdata[3]==2)
	  $serstr="Load Balancing Server";
	  
	  echo $serverdata[0]." ( ".$serverdata[1]." - ".$serverdata[2]." ) ".$serstr;
	  echo " ( <a href=\"reset-server.php?pid=$id&url=$url\">Reset</a> ) ";
	  
	  }
	  ?>	  </td>
	</tr>
  
  <tr align="left">
		  <td height="22">Change Server Allotment</td>
		  <td ><strong>:</strong></td>
		  <td>
		  <?php
		  $servers=mysql_query("select id,name,min_range,max_range,srv_type from  server_configurations ");//where srv_type=1 or (srv_type=2 and status='111')
		  $servers_no=mysql_num_rows($servers);
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


if($row[21]==$servers_row[0])
$serverstr.=' selected ';


$serverstr.='>'.$servers_row[1].' ( '.$servers_row[2].'-'.$servers_row[3].' ) - '.$srvstr.'</option>';

}
}
		  ?>
		  
		  
		  <form  method="get" action="view_profile_publishers.php">
		  <select name="srvs" id="srvs">
          <?php echo $serverstr; ?>
          </select>
		 
		  <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
		  <input  type="submit" name="sersub" id="sersub" value="Submit" />
		  </form>		 </td>
	</tr>
  <tr align="left">
    <td height="22" colspan="3" class="info"><strong>Note :</strong> If specific server is alloted, it will override default server allotment. </td>
  </tr>
</table>
<br><br>

<table width="100%"   border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #CCCCCC; ">
    <tr>
      <td width="100%"  valign="top" >


<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus" >
  <tr height="30px">
  
  <?php 
  
  $tab =$_GET['tab'];

if($tab!=2 && $tab!=3 && $tab!=4  && $tab!=5 && $tab!=6)
$tab=1;
  
  $str_tab_style='style="background:url(images/li_bgselect.jpg) repeat-x" class="statistics"';
  
  $str_tab_style1='';
  $str_tab_style2='';
  $str_tab_style3='';
  $str_tab_style4=''; 
  $str_tab_style5='';


  
  if($tab==1)
  {
    $str_tab_style1=$str_tab_style;
  }
  else  if($tab==2)
  {
    $str_tab_style2=$str_tab_style;
  }
  else  if($tab==3)
  {
  $str_tab_style3=$str_tab_style;
  }
  else  if($tab==4)
  {
  $str_tab_style4=$str_tab_style;
  }
  else  if($tab==5)
  {
  $str_tab_style5=$str_tab_style;
  }
  else  if($tab==6)
  {
  $str_tab_style6=$str_tab_style;
  }
  
  
?>
  
    <td  align="center" id="index1_li_1" <?php echo $str_tab_style1; ?> ><a href="view_profile_publishers.php?id=<?php echo $id; ?>&tab=1" >Overall Statistics</a></td>
    <td  align="center" id="index1_li_2" <?php echo $str_tab_style2; ?> ><a href="view_profile_publishers.php?id=<?php echo $id; ?>&tab=2" >Adunits</a></td>
	<td  align="center" id="index1_li_3"  <?php echo $str_tab_style3; ?> ><a href="view_profile_publishers.php?id=<?php echo $id; ?>&tab=3" >Time based<br />
 Statistics</a></td>
    <td  align="center" id="index1_li_4"  <?php echo $str_tab_style4; ?> ><a href="view_profile_publishers.php?id=<?php echo $id; ?>&tab=4" >Withdrawals</a></td>
	<td  align="center" id="index1_li_5"  <?php echo $str_tab_style5; ?>  ><a href="view_profile_publishers.php?id=<?php echo $id; ?>&tab=5" >Referral Statistics</a></td>
	<td  align="center" id="index1_li_6" <?php echo $str_tab_style6; ?>  ><a href="view_profile_publishers.php?id=<?php echo $id; ?>&tab=6" >Traffic Analysis</a></td>

  </tr>
</table>

</td>
</tr>
<tr>
<td class="container">

<?php

if($tab==1)
{

?>



<br><br />
<?php
$status=-1;
$statusstr="All";
$str="";
$urlstr="?status=-1";
if(isset($_REQUEST['status']))
$status=$_REQUEST['status'];
if($status==0)
{
$statusstr="Pending";
$str=" and a.status =0 ";
$urlstr="?status=0";
}
if($status==1)
{
$statusstr="Approved";
$str=" and a.status =1 ";
$urlstr="?status=1";
}	
if($status==2)
{
$statusstr="Rolled back";
$str=" and a.status =2 ";
$urlstr="?status=2";
}	
if($status==3)
{
$statusstr="Completed";
$str=" and a.status =3 ";
$urlstr="?status=3";
}	
?>


 			<form name="form1" method="get" action="view_profile_publishers.php">
			<input type="hidden" name="tab" value="1">
	<input type="hidden" name="userid" value="<?php echo $id;?>">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr width="100%"><td >Show statistics as of :&nbsp;
	        <select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?> selected="selected">Today</option>
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
	  &nbsp;
	 
	  <input type="submit" name="Submit" value="Show Statistics">
	     </td>
		 
			  </tr></table><br>
    </form>
		
		<table cellpadding="2" cellspacing="0" border="0" width="100%">
		<tr height="40px"><td width="26%"><strong>Valid Clicks </strong></td>
		                   <td width="74%"> : <?php  $aa=getPubAdClicks($time,$mysql,$id,$flag_time); echo numberFormat($aa,0);?></td>
		</tr>
	    <tr height="40px"><td><strong>Total Impressions</strong></td>
		                  <td>
						        : <?php
								 $imp_count= getPubAdImpressions($time,$mysql,$id,$flag_time);                                 
								 echo numberFormat($imp_count,0);?>
						  </td>
		</tr>
		
		<tr height="40px"><td><strong>CTR</strong></td>
		                  <td>
						        : <?php
								echo numberFormat(getCTR($aa,$imp_count));
								?> %
						  </td>
		</tr>
		<tr height="40px"><td><strong>Click  Profit </strong></td>
		                  <td> : <?php 	$ret1=getPubPublisherprofit($time,$mysql,$id,$flag_time);
						       if( $ret1=="")
	                           $ret=0;
                               $ret=round($ret1,2);
	                           echo  moneyFormat($ret);
	                          ?>
	      </td>
		</tr>
		<tr height="40px"><td><strong>ECPM</strong></td>
		                  <td>
						        : <?php
								 $ecpm=getECPM($ret1,$imp_count) ;
								 echo numberFormat($ecpm);?>
						  </td>
		</tr>
						  
		<tr height="40px"><td><strong>Possible Fraud Clicks </strong></td>
		                  <td>
						        : <?php
								 $fpc_count= $mysql->echo_one("select count(id) from ppc_fraud_clicks where pid='$id' and publisherfraudstatus=1 and clicktime>$time");                                 echo numberFormat($fpc_count,0);?>
						  </td>
		</tr>
		<tr height="40px"><td><strong>Invalid Clicks</strong></td>
		                  <td>
						      : <?php 
							   $ipc_count= $mysql->echo_one("select count(id) from ppc_fraud_clicks where pid='$id' and publisherfraudstatus=2 and clicktime>$time"); 
							   echo numberFormat($ipc_count,0);
							   ?>
						  </td>
		</tr>
		<tr height="40px"><td><strong>Proxy Clicks</strong></td>
		                  <td>
						      : <?php 
							   $pc_count= $mysql->echo_one("select count(id) from ppc_fraud_clicks where pid='$id' and publisherfraudstatus=3 and clicktime>$time"); 
							   echo numberFormat($pc_count,0);
							   ?>
						  </td>
		</tr>
		<tr height="40px"><td><strong>Action</strong></td>
		                   <td>
						         : <?php if($fpc_count>0 || $ipc_count>0|| $pc_count>0)  { ?><a href="ppc-warn-publisher-ad.php?type=7&id=<?php echo $id; ?>&url=<?php echo $url; ?>	">Warn</a>&nbsp;|
		                                 <a href="ppc-block-publisher-ad.php?type=8&id=<?php echo $id; ?>&url=<?php echo $url; ?>	">Block</a><?php } else echo  "N/A" ?>
						   </td>
		</tr>
		</table>


<?php
}
if($tab==2)
{

   //$adserver_upgradation_date=1;



	$row_cab=mysql_query("select id,name,wapstatus from `ppc_custom_ad_block` where pid=$id order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	$total=$mysql->echo_one("select count(*) from ppc_custom_ad_block where pid='$id' ");
	$total_temp=$total;
	if($adserver_upgradation_date!=0)
		$total_temp=$total+1;
	
	$lastpage=floor($total/$perpagesize);
	if($total%$perpagesize!=0)
		$lastpage+=1;
	
	$lastrow=$pageno*$perpagesize;
	if($lastrow==$total && $adserver_upgradation_date!=0)
	$lastrow=$total_temp;
	


$status=-1;
$statusstr="All";
$str="";
$urlstr="?status=-1";
if(isset($_REQUEST['status']))
$status=$_REQUEST['status'];
if($status==0)
{
$statusstr="Pending";
$str=" and a.status =0 ";
$urlstr="?status=0";
}
if($status==1)
{
$statusstr="Approved";
$str=" and a.status =1 ";
$urlstr="?status=1";
}	
if($status==2)
{
$statusstr="Rolled back";
$str=" and a.status =2 ";
$urlstr="?status=2";
}	
if($status==3)
{
$statusstr="Completed";
$str=" and a.status =3 ";
$urlstr="?status=3";
}	
?>
		<br>


 			<form name="form1" method="get" action="view_profile_publishers.php">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr width="100%"><td >Show statistics as of :&nbsp;
	        	<input type="hidden" name="userid" value="<?php echo $id;?>">
				<input type="hidden" name="tab" value="2">
<select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?> selected="selected">Today</option>
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
	  &nbsp;
	 
	  <input type="submit" name="Submit" value="Show Statistics">
	     </td>
		 
			  </tr></table>
    </form>
		<br>
		<?php if($total>=1) {?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0"  >

		<tr>
			<td colspan="2">   Showing Ad units <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
			<span class="inserted">
			<?php if((($pageno)*$perpagesize)>$total) echo $total_temp; else echo $lastrow; ?>
			</span>&nbsp;of <span class="inserted"><?php echo $total_temp; ?></span>&nbsp;
			&nbsp;&nbsp;		</td>
			<td width="50%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile_publishers.php?id=$id&tab=2&statistics=$show"); ?></td>
		  </tr>
	  </table>
		  	<?php } ?>	
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable" >

		  <tr class="headrow">
		<td width="159"><strong>Adunit</strong></td>
		<td width="153"><strong>Type </strong></td>
		<td width="152"><strong>Clicks  </strong></td>
		<td width="203"><strong>Impressions </strong></td>
		<td width="147"><strong>CTR (%)</strong></td>
		<td width="249"><strong>Click profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </strong></td>
		<td><strong>ECPM (<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </strong></td>
		</tr>
		<?php
	if($total_temp>0)
	{
		$i=1;
		 while($row_cab_val=mysql_fetch_row($row_cab))
		 { 
			$tot_clck=round(getPubBlockClicks($time,$mysql,$id,$row_cab_val[0],$flag_time),2); // $mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$id' and bid='$row_cab_val[0]' and time>'$time'");
			$tot_imp=round(getPubBlockImpressions($time,$mysql,$id,$row_cab_val[0],$flag_time),2); // $mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$id' and bid='$row_cab_val[0]' and time>'$time'");
			
			$pub_profit=getPubBlockPublisherprofit($time,$mysql,$id,$row_cab_val[0],$flag_time); // round($mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$id' and bid='$row_cab_val[0]' and time>'$time'"),2);
			//$ar_sh=round($mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$id' and bid='$row_cab_val[0]' and time>'$time'"),2);
			//$pr_sh=round($mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$id' and bid='$row_cab_val[0]' and time>'$time'"),2);
			
		  if($row[2]==1)
		        {
		        	$image='wap.png';
		        }
		        else
		        {
		        	$image='pc.png';
		        }
			
			?>
			 <tr  <?php if($i%2==1) { ?>class="specialrow" <?php } ?> >
			<td  height="25" ><a href="adunit-click-statistics.php?id=<?php  echo $row_cab_val[0];?>" ><?php echo $row_cab_val[1];  ?></a>&nbsp;</td>
			<td  height="25" ><img src="images/<?php echo $image; ?>"></td>
			<td  height="25" ><?php echo numberFormat($tot_clck,0);  ?>&nbsp;</td>
			<td height="25" ><?php echo numberFormat($tot_imp,0);  ?>&nbsp;</td>
			<td  height="25" ><?php echo numberFormat(getCTR($tot_clck,$tot_imp));  ?>&nbsp;</td>
			<td  height="25" ><?php echo numberFormat($pub_profit);  ?>&nbsp;</td>
			<td  height="25" ><?php echo numberFormat(getECPM($pub_profit,$tot_imp));  ?></td>
			</tr>
			<?php
			$i++;
		 } 
		
		if( $adserver_upgradation_date!=0 &&($lastpage == $pageno || $total==0))
		{
			//$result=mysql_query("select COALESCE(sum(clk_count),0),COALESCE(sum(publisher_profit),0) from $table_name where uid='$id' and bid=0 and time>'$time'");
			//$row=mysql_fetch_row($result);
			$un_clk= round(getPubBlockClicks($time,$mysql,$id,0,$flag_time),2); 
			$total_impressions=round(getPubBlockImpressions($time,$mysql,$id,0,$flag_time),2); // $mysql->echo_one("select COALESCE(sum(impression),0)  from $table_name where uid='$id' and bid=0 and time>'$time'");
			
		
		
					?>

			<tr height="25"<?php if($i%2==1){?>class="specialrow" <?php } ?>>
			<td   height="25">Uncategorized<!--<a href="adunit-click-statistics.php?id=0" ></a>--></td>
			<td  height="25"  ><img src="images/pc.png"></td>
			<td   height="25" ><?php echo numberFormat($un_clk,0); // $row[0]; ?></td>
			<td   height="25" ><?php echo numberFormat($total_impressions,0); ?></td>
			<td   height="25" ><?php echo numberFormat(getCTR($un_clk,$total_impressions)); ?></td>
			<td   height="25" ><?php  $aa=getPubBlockPublisherprofit($time,$mysql,$id,0,$flag_time); echo numberFormat($aa);// round($row[1],2); ?></td>
			<td  height="25"  ><?php echo numberFormat(getECPM($aa,$total_impressions)); ?></td>
			</tr>
	<?php
		}//end of if

	 }else
		{?>
		<tr><td colspan=7><br>No record found<br></td></tr>
	<?php } ?>
	
	</table>
	
			<?php if($total>=1) {?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0"  >

		<tr>
			<td colspan="2">   Showing Ad units <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
			<span class="inserted">
			<?php if((($pageno)*$perpagesize)>$total) echo $total_temp; else echo $lastrow; ?>
			</span>&nbsp;of <span class="inserted"><?php echo $total_temp; ?></span>&nbsp;
			&nbsp;&nbsp;		</td>
			<td width="50%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile_publishers.php?id=$id&tab=2&statistics=$show"); ?></td>
		  </tr>
	  </table>
		  	<?php } ?>	


<br />	
	
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	  
		<tr>
		  <td height="25" colspan="7"> <strong> XML API Statistics </strong>
        
        
	  </tr>

		  
	</table>






		<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable">


		  <tr  class="headrow" >
		<td width="239"><strong>Total Clicks  </strong></span></td>
		<td width="307"><strong>Total impressions </strong></span></td>
		<td width="325"><strong>CTR(%)</strong></span></td>
		<td width="223"><strong>Click profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </strong></span></td>
		<td width="325"><strong>ECPM(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</strong></span></td>
		</tr>
		<?php
	
			$tot_clck=getPubBlockClicks($time,$mysql,$id,-1,$flag_time); // $mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$id' and bid='-1' and time>'$time'");
			$tot_imp=getPubBlockImpressions($time,$mysql,$id,-1,$flag_time); // $mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$id' and bid='-1' and time>'$time'");
			
			$pub_profit=getPubBlockPublisherprofit($time,$mysql,$id,-1,$flag_time); // round($mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$id' and bid='-1' and time>'$time'"),2);
			//$ar_sh=round($mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$id' and bid='-1' and time>'$time'"),2);
			//$pr_sh=round($mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$id' and bid='-1' and time>'$time'"),2);
			
			?>
			 <tr >
			<td  ><?php echo numberFormat($tot_clck,0);  ?>&nbsp;</td>
			<td ><?php echo numberFormat($tot_imp,0);  ?>&nbsp;</td>
			<td  ><?php echo numberFormat(getCTR($tot_clck,$tot_imp));  ?> &nbsp;</td>
			<td  ><?php echo numberFormat($pub_profit);  ?>&nbsp;</td>
			<td  ><?php echo numberFormat(getECPM($pub_profit,$tot_imp));  ?>&nbsp;</td>
			</tr>
	</table>
	


<?php
}
if($tab==3)
{

?>


<br>


<?php
$status=-1;
$statusstr="All";
$str="";
$urlstr="?status=-1";
if(isset($_REQUEST['status']))
$status=$_REQUEST['status'];
if($status==0)
{
$statusstr="Pending";
$str=" and a.status =0 ";
$urlstr="?status=0";
}
if($status==1)
{
$statusstr="Approved";
$str=" and a.status =1 ";
$urlstr="?status=1";
}	
if($status==2)
{
$statusstr="Rolled back";
$str=" and a.status =2 ";
$urlstr="?status=2";
}	
if($status==3)
{
$statusstr="Completed";
$str=" and a.status =3 ";
$urlstr="?status=3";
}	
?>


 			<form name="form1" method="get" action="view_profile_publishers.php">
	<input type="hidden" name="userid" value="<?php echo $id;?>">
	<input type="hidden" name="tab" value="3">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr width="100%"><td ><p>Show statistics as of :&nbsp;
	        <select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?> selected="selected">Today</option>
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
	  &nbsp;
	
	  <input type="submit" name="Submit" value="Show Statistics">
	</p>
	     </td>
		 
			  </tr></table>
    </form>
<br />

<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable" >
  
		

      <tr class="headrow" >
        
        <td><strong>Period </strong></td>
        <td><strong>Clicks </strong></td>
        <td><strong>Impressions </strong></td>
        <td><strong>CTR(%) </strong></td>
        <td ><strong>Money Gained(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</strong></td>
 <td><strong>ECPM </strong></td>
  </tr>
  <?php 

  $tablestructure=getTimeBasedPublisherStatistics($show,$flag_time,$beg_time,$end_time,$id);
   if(count($tablestructure) > 0)
{$i=0;
foreach($tablestructure as $key => $value)
{
	
if($flag_time==0)
            {
               // $str=date("d/M/Y",$key-1);
$str=dateFormat($key-1,"%b %d"); 
                $show_duration="$str";
                           
            }
            else if($flag_time==1)
            {
               // $str=date("M/Y",$key-86400);
$str=dateFormat($key-86400,"%b");
                $show_duration="$str";
            }
             else if($flag_time==2)
            {
       
               // $str=date("Y",$key-86400);
$str=dateFormat($key-86400,"%Y");
                $show_duration="$str";
            }   
           
             if($flag_time==-1)
            {
   
$str=dateTimeFormat($key,"%d %b %l %p");

            $show_duration="$str";
            }
             
  
  
  ?>
  <tr <?php if($i%2==1) { ?>class="specialrow" <?php } ?> height="25">
  <td ><?php echo $show_duration; ?></td>
   <td ><?php echo numberFormat($value[1],0); ?></td>
   <td ><?php echo numberFormat($value[0],0); ?></td>
 <td ><?php echo numberFormat(getCTR($value[1],$value[0])); ?> </td>
<td ><?php echo numberFormat($value[2]); ?></td>
<td ><?php echo numberFormat(getECPM($value[2],$value[0])); ?></td>
  </tr> 
  <?php 
  
  $i++;
}
}
  ?>
 
</table>

<?php
}
if($tab==4)
{






$pageno=1;
if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
$perpagesize = 20;




$type="all";
$typestr="";
$urlstr="&type=all";
if(isset($_REQUEST['type']))
{	
	$type=$_REQUEST['type'];
	if($type=="p")
	{
	$typestr=" and a.paymode =1 ";
	$urlstr="&type=p";
	}
	if($type=="b")
	{
	$typestr=" and a.paymode =2 ";
	$urlstr="&type=b";
	}	
	if($type=="c")
	{
	$typestr=" and a.paymode =0 ";
	$urlstr="&type=c";
	}	
	if($type=="t")
	{
	$typestr=" and a.paymode =3 ";
	$urlstr="&type=t";
	}	
	if($type=="all")
	{
	$typestr="";
	$urlstr="&type=all";
	}	
}


$status="all";
$str="";

if(isset($_REQUEST['status']))
{
	$status=$_REQUEST['status'];
	if($status=="all")
	{
	$str="";
	$urlstr.="&status=all";
	}
	elseif($status==0)
	{
	$str=" and a.status =0 ";
	$urlstr.="&status=0";
	}
	elseif($status==1)
	{
	$str=" and a.status =1 ";
	$urlstr.="&status=1";
	}	
	elseif($status==2)
	{
	$str=" and a.status =2 ";
	$urlstr.="&status=2";
	}	
	elseif($status==3)
	{
	$str=" and a.status =3 ";
	$urlstr.="&status=3";
	}	
	elseif($status==-2)
	{
	$str=" and a.status =-2 ";
	$urlstr.="&status=-2";
	}	
	elseif($status==-3)
	{
	$str=" and a.status =-3 ";
	$urlstr.="&status=-3";
	}	
}

$result=mysql_query("select a.id,a.reqdate  ,b.username,a.amount ,a.currency,a.paymode ,a.payeedetails,a.txid,a.payerdetails ,a.status,a.uid,b.status  from  ppc_publisher_payment_hist a,ppc_publishers b where a.uid=b.uid  and a.uid=$id ".$str.$typestr."order by a.id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from ppc_publisher_payment_hist a,ppc_publishers b where a.uid=b.uid and a.uid=$id".$str.$typestr);
?>



<script language="javascript" type="text/javascript">

 var opt0 = new Option('All', 'all');
 var opt1 = new Option('Pending', '0');
 var opt2 = new Option('Rejected', '-2');
 var opt3 = new Option('Denied', '-3'); 
 var opt4 = new Option('Approved', '1');
 var opt5 = new Option('Rolled Back', '2');
 var opt6 = new Option('Completed', '3');

function updateOptions(type,sel_stat)
{
	sel_type=type.options[type.options.selectedIndex].value;
	status_control=document.ads.status;
	if(sel_type=='t')
	{
		for (i = status_control.options.length - 1; i>=0; i--) 
		{
			switch(status_control.options[i].value)
			{
				case '1':
				case '2':
						status_control.remove(i);
			}	   
		}
	}
	else
	{
	status_control.options[0]=opt0;
	status_control.options[1]=opt1;
	status_control.options[2]=opt2;
	status_control.options[3]=opt3;
	status_control.options[4]=opt4;
	status_control.options[5]=opt5;
	status_control.options[6]=opt6;
	if(sel_stat=='all')
		status_control.options[0].selected=true;
	if(sel_stat=='0')
		status_control.options[1].selected=true;
	if(sel_stat=='-2')
		status_control.options[2].selected=true;
	if(sel_stat=='-3')
		status_control.options[3].selected=true;
	if(sel_stat=='1')
		status_control.options[4].selected=true;
	if(sel_stat=='2')
		status_control.options[5].selected=true;
	if(sel_stat=='3')
		status_control.options[6].selected=true;
	}
}
</script>

<br />


<form name="ads" action="view_profile_publishers.php" method="get">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr height="35">
 
     <td width="4%"   >Type</td>
    <td width="31%"> 
<input type="hidden" name="id" value="<?php echo $id;?>">
<input type="hidden" name="tab" value="4">
<select name="type" id="type" onchange="updateOptions(this,'')">
<option value="all" <?php 
			  				  if($type=="all")echo "selected";			  
			  ?>>All</option>
<option value="p" <?php 
			  				  if($type=="p")echo "selected";			  
			  ?>>Paypal</option>
<option value="b" <?php 
			  				  if($type=="b")echo "selected";		
			  ?>>Bank</option>
<option value="c" <?php 
			  				  if($type=="c")echo "selected";			  
			  ?>>Check</option>
<option value="t" <?php 
			  				  if($type=="t")echo "selected";			  
			  ?>>Transfer To Advertiser</option>
</select>
</td>
    <td width="5%"  >Status</td>
    <td width="20%"> 

<select name="status" id="status" >
<option value="all" <?php 
			  				  if($status=="all")echo "selected";			  
			  ?>>All</option>
<option value="0" <?php 
			  				  if($status=="0")echo "selected";			  
			  ?>>Pending</option>
<option value="-2" <?php 
			  				  if($status=="-2")echo "selected";		
			  ?>>Rejected</option>
<option value="-3" <?php 
			  				  if($status=="-3")echo "selected";		
			  ?>>Denied</option>
<option value="1" <?php 
			  				  if($status=="1")echo "selected";			  
			  ?>>Approved</option>
<option value="2" <?php 
			  				  if($status=="2")echo "selected";		
			  ?>>Rolled Back</option>
<option value="3" <?php 
			  				  if($status=="3")echo "selected";		
			  ?>>Completed</option>
</select>
</td> 
<td width="40%"><input type="submit" name="Submit" value="Submit"></td>
  </tr>
  </table> </form>  

<script language="javascript" type="text/javascript">

updateOptions(document.ads.type,'<?php echo $status; ?>')


</script>

<?php
if(mysql_num_rows($result)==0)
{
	echo '<br>No records found<br><br>';

}
	
else
{ ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 	
	 <?php if($total>=1) {?>
  	<tr>
    <td colspan="2" >   Showing withdrawal history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
        &nbsp;&nbsp;    </td>
    <td colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile_publishers.php?id=$id&tab=4".$urlstr); ?></td>
	  </tr>
	  <?php } ?>
 <tr>
    <td colspan="4">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
        <tr class="headrow">
	        <td width="25%" style="padding-left:3px;"><strong>Request date</strong></td>
        
        <td width="6%"><strong>Amt&nbsp;(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</strong></td>
		 <td width="9%"><strong>Type</strong></td>
        <td width="11%"><strong>Status</strong></td>
		<td width="32%" ><strong>Action</strong></td>
      </tr>
	  <?php
	  $i=0;
	   while($row=mysql_fetch_row($result))
	   { 
      if($row[11]==1)
	  	{?>
      <tr <?php if($i%2==1) { ?>class="specialrow" <?php } ?>>    
	 <?php  }
	 else
	 	{?>
		<tr <?php  echo 'bgcolor="#F8B9AF"';?> height="28">  <?php 
		}?>
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><?php echo dateTimeFormat($row[1]); ?></td>
        
        <td style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($row[3]); ?></td>
		<td style="border-bottom: 1px solid #b7b5b3;"><?php if($row[5]==0)
			echo "Check";
		if($row[5]==1)
			echo "Paypal"; 
		if($row[5]==2)
			echo "Bank";
	  if($row[5]==3)
			echo "Transfer";
			?></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php 
		if($row[9]==0)
			echo "Pending";
		if($row[9]==1)
			echo "Approved";
		if($row[9]==2)
			echo "Rolled Back";
		if($row[9]==3)
			echo "Completed";
		if($row[9]==-2)
			echo "Rejected";
		if($row[9]==-3)
			echo "Denied";

		 ?>          </td>
		 <td style="border-bottom: 1px solid #b7b5b3;"><a href="ppc-publisher-request-view-details.php?id=<?php echo $row[0]; ?>&uid=<?php echo $row[10]; ?><?php echo $urlstr; ?>">Details</a> 
		 
		  <?php if($row[9]==0) 
		 {?>
		  | <a <?php  if($row[5]==3) { ?> onclick="return confirm('Please note that transfer requests are immediately completed unlike other withdrawal requests. Click OK to continue')" <?php } ?> href="ppc-publisher-request-change-status.php?id=<?php echo $row[0]; ?>&newstatus=1<?php echo $urlstr; ?>&type=<?php echo $row[5];?>">Approve</a>  | <a href="ppc-publisher-request-change-status.php?id=<?php echo $row[0]; ?>&newstatus=-2<?php echo $urlstr; ?>&type=<?php echo $row[5];?>">Reject</a>
		 <?php 
		 }
		 else if ($row[9]==2 || $row[9]==3 || $row[9]==-2|| $row[9]==-3)
		 {
		//do nothing
		 }
		 else
		 {
		 ?>
		  | <a href="ppc-publisher-request-change-status.php?id=<?php echo $row[0]; ?>&newstatus=3<?php echo $urlstr; ?>">Mark completed</a> | <a href="ppc-publisher-request-change-status.php?id=<?php echo $row[0]; ?>&newstatus=2<?php echo $urlstr; ?>">Rollback</a>
	    <?php 
		 } ?>          </td>
		</tr>
	<?php  $i++; } ?>	
	</table>
		</td>
  </tr>
  <?php if($total>=1) {?> 
	  	<tr>
    <td colspan="2" >  Showing withdrawal history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
       &nbsp;&nbsp;    </td>
    <td colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile_publishers.php?id=".$id."&tab=4".$urlstr); ?></td>
	  </tr>
	<tr>
	<td colspan="6"><br></td>
	</tr>
	<?php } ?> 

</table>
<?php } ?>

<?php
}
if($tab==5)
{

?>

<?php
$status=-1;
$statusstr="All";
$str="";
$urlstr="?status=-1";
if(isset($_REQUEST['status']))
$status=$_REQUEST['status'];
if($status==0)
{
$statusstr="Pending";
$str=" and a.status =0 ";
$urlstr="?status=0";
}
if($status==1)
{
$statusstr="Approved";
$str=" and a.status =1 ";
$urlstr="?status=1";
}	
if($status==2)
{
$statusstr="Rolled back";
$str=" and a.status =2 ";
$urlstr="?status=2";
}	
if($status==3)
{
$statusstr="Completed";
$str=" and a.status =3 ";
$urlstr="?status=3";
}	
?>
<br>

 			<form name="form1" method="get" action="view_profile_publishers.php">
	<input type="hidden" name="userid" value="<?php echo $id;?>">
<input type="hidden" name="tab" value="5">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr width="100%"><td ><p>Show statistics as of :&nbsp;
	        <select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?> selected="selected">Today</option>
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
	  &nbsp;
	 
	  <input type="submit" name="Submit" value="Show Statistics">
	</p>
	     </td>
		 
			  </tr></table>
    </form>
	<br>
	  
	<?php 
if($show=="day")
 	{
	$referral_result= mysql_query("select COALESCE(sum(unique_hits),0), COALESCE(sum(repeated_hits),0) from `referral_daily_visits`  where rid=$id and time>=$time");
	}
else
	{
	$referral_result= mysql_query("select COALESCE(sum(unique_hits),0), COALESCE(sum(repeated_hits),0) from referral_visits where rid=$id and time>=$time");
	}
$referral_row=mysql_fetch_row($referral_result);


$reg_end_time=mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
if($flag_time==0)
	  {
$reg_start_time=mktime(0,0,0,date("m",$time),date("d",$time)-1,date("Y",$time));
	  }
	 else if($flag_time==1)
	 {
$reg_start_time=mktime(0,0,0,date("m",$time)-1,1,date("Y",$time));
	 }
	 else if($flag_time==2)
	 {
$reg_start_time=mktime(0,0,0,1,1,date("Y",$time)-1);
	 }
	 else 
	 {
$reg_end_time=time();
$reg_start_time=$time;
	 }
$psigned=$mysql->total("ppc_publishers","rid=$id and regtime>$reg_start_time and regtime<=$reg_end_time"); 
$asigned=$mysql->total("ppc_users","rid=$id and regtime>$reg_start_time and regtime<=$reg_end_time"); 

?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">

       <tr class="headrow">
        
        <td>Unique Hits  Received</td>
        <td>Repeated Hits Received</td>
        <td width="12%">Advertiser Signups</td>
        <td width="12%">Publisher Signups</td>
        <td width="13%">Adv ref profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</td>
        <td width="13%">Pub ref profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</td>
        <td width="21%"><span class="style2"></span></td>
	    </tr>
		    
      <tr bgcolor="#ededed" height="25">
       
        <td height="25" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($referral_row[0],0);    ?></td>
        <td height="25" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($referral_row[1],0);    ?></td>
         <td height="25" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($psigned,0);//$mysql->total("ppc_users","rid=$id and regtime>$reg_start_time and regtime<=$reg_end_time"); 	?></td>
		<td height="25" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($asigned,0);//$mysql->total("ppc_publishers","rid=$id and regtime>$reg_start_time and regtime<=$reg_end_time");  	?></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php 
$aearning=getAdvertiserReferralProfitOfPublisher($time,$mysql,$id,$flag_time);
echo numberFormat($aearning);

?>&nbsp;</td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php $pearning=getPublisherReferralProfitOfPublisher($time,$mysql,$id,$flag_time); echo numberFormat($pearning); ?>&nbsp;</td>
        <td height="25" style="border-bottom: 1px solid #b7b5b3;"><a href="referral-traffic-sources.php?pid=<?php echo $id; ?>&statistics=<?php echo $show;?>">Traffic sources</a></td>
	  </tr>

	</table>
<?php
}
if($tab==6)
{
?>


<br>
<?php
$status=-1;
$statusstr="All";
$str="";
$urlstr="?status=-1";
if(isset($_REQUEST['status']))
$status=$_REQUEST['status'];
if($status==0)
{
$statusstr="Pending";
$str=" and a.status =0 ";
$urlstr="?status=0";
}
if($status==1)
{
$statusstr="Approved";
$str=" and a.status =1 ";
$urlstr="?status=1";
}	
if($status==2)
{
$statusstr="Rolled back";
$str=" and a.status =2 ";
$urlstr="?status=2";
}	
if($status==3)
{
$statusstr="Completed";
$str=" and a.status =3 ";
$urlstr="?status=3";
}	
?>


 			<form name="form1" method="get" action="view_profile_publishers.php">
	<input type="hidden" name="userid" value="<?php echo $id;?>">
	<input type="hidden" name="tab" value="6">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr width="100%"><td ><p>Show statistics as of :&nbsp;
	        <select name="statistics" id="statistics">
	 <!-- <option value="day"  <?php 
			  				 // if($show=="day")echo "selected";			  
			  ?> selected="selected">Today</option>-->
        <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 days</option>
        <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 days</option>
    
      </select>
	  &nbsp;
	 
	 <input type="hidden" name="show_visits" value="1">
	  <input type="submit" name="Submit" value="Show Statistics"> <a href="ppc-delete-page-visits.php?&pid=<?php echo $id; ?>&url=<?php echo $url; ?>">Delete all</a> 
	</p>
	     </td>
		 
			  </tr></table>
    </form>


<strong>Note:</strong><span class="info"> Last 30 days page visit statistics are shown below.</span><br />
<strong>Note:</strong><span class="info"> Last 24 hours data are not included in the statistics details.</span><br>
<strong>Note:</strong><span class="info"> Invalid clicks include proxy clicks,bot clicks,clicks from invalid ips and locations.</span><br>



	  <?php 
	$visit_time=mktime(0,0,0,date("m",time()),date("d",time())-30,date("Y",time()));
			$tmp_time=$mysql->echo_one("select e_time from `statistics_updation`  where task='click_backup'");
			$tmp_time=$tmp_time-(24*60*60);
	   $res=mysql_query("SELECT page_url,pid,sum(direct_hits),sum(referred_hits),sum(direct_clicks),sum(referred_clicks),sum(direct_impressions),sum(referred_impressions),sum(direct_invalid_clicks),sum(referred_invalid_clicks),sum(direct_fraud_clicks),sum(referred_fraud_clicks),sum(direct_repeated_click),sum(referred_repeated_click), sum(`direct_hits`)+sum(`referred_hits`) 
as a FROM  publisher_visits_statistics where pid=$id group by `page_url`  order by a desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	  $num_rows=$mysql->echo_one("select count(distinct(page_url)) from publisher_visits_statistics where pid=$id");


	if($num_rows==0)
	{
	echo "<br>No record found<br>";
	}
	else
	{
	  ?>
	  
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	  
	  
    <tr  >
      <td colspan="3" >

	          Showing Publisher Visits History <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$num_rows) echo $num_rows; else echo ($pageno)*$perpagesize; ?>
        </span>&nbsp;of <span class="inserted"><?php echo $num_rows; ?></span>&nbsp;
       &nbsp;&nbsp;<br />
        <br/>	  </td>
      <td colspan="3" align="right" ><?php echo $paging->page($num_rows,$perpagesize,"","view_profile_publishers.php?id=$id&tab=6&statistics=$show&status=$status&show_visits=1"); ?></td>
    </tr>
	</table>
	
		<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable">
	   <tr class="headrow" >
        
        <td width="39%"><strong>Ad Pages </strong></td>
        <td width="4%"></td>
        <td width="1%">&nbsp;</td>
        <td width="6%"><strong>Visits</strong></td>
        <td width="7%"><strong>Total impressions</strong></td>
        <td width="11%"><strong>Valid Clicks </strong></td>
        <td width="11%"><strong>Possible Fraud click </strong></td>
        <td width="11%"><strong>Repeated clicks </strong></td>
        <td width="10%"><strong>Invalid clicks</strong></td>
  		</tr>
   
 
	<?php
	$pidstr="pid=$id and ";
	$i=1;

 	  while($row=mysql_fetch_row($res))
      {
	   ?>
	  <tr  <?php if($i%2==1) { ?>class="specialrow" <?php } ?> height="25">
	       <td rowspan="2"  style="border-bottom: 1px solid #b7b5b3;"><?php
        if($row[0]=="")//unknown url
            echo "Unknown";
        else
        	echo wordwrap($row[0], 35, "<br>", 1);
			
             ?></td>
          <td style="border-left: 1px solid #b7b5b3;">Direct  </td>
        <td ><strong>:</strong></td>
        <td ><?php echo numberFormat($row[2],0);  ?></td>
		<td  ><?php echo numberFormat($row[6],0); ?></td>
		<td ><?php echo numberFormat($row[4],0); ?></td>
		
		<td ><?php echo numberFormat($row[10],0); ?></td>
        <td ><?php echo numberFormat($row[12],0); ?></td>        
        <td ><?php echo numberFormat($row[8],0); ?></td>
		</tr>
		 <tr  <?php if($i%2==1) echo 'bgcolor="#ededed"'; ?> height="25">
		   <td   style=" border-left: 1px solid #b7b5b3;">Referred  </td>
        <td  ><strong>:</strong></td>
        <td><?php echo numberFormat($row[3],0); ?></td>
				<td ><?php echo numberFormat($row[7],0); ?></td>
				<td><?php echo numberFormat($row[5],0); ?></td>
		
		 <td><?php echo numberFormat($row[11],0); ?></td>
        <td><?php echo numberFormat($row[13],0); ?></td>       
        <td><?php echo numberFormat($row[9],0); ?></td>
        </tr>
		<?php  
		$i++;
	   }

	  ?>
	 </table>

	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	  
	  
    <tr  >
      <td colspan="3" >

	          Showing Publisher Visits History <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$num_rows) echo $num_rows; else echo ($pageno)*$perpagesize; ?>
        </span>&nbsp;of <span class="inserted"><?php echo $num_rows; ?></span>&nbsp;
       &nbsp;&nbsp;<br />
        <br/>	  </td>
      <td colspan="3" align="right" ><?php echo $paging->page($num_rows,$perpagesize,"","view_profile_publishers.php?id=$id&tab=6&statistics=$show&status=$status&show_visits=1"); ?></td>
    </tr>
	</table>
<?php
}
?>

<?php
}
?>



</td>
  </tr></table>

	<br />
		

 <?php include("admin.footer.inc.php"); ?>