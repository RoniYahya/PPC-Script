<?php



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/  

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
	$id=getSafePositiveInteger('userid','r');
	$uid=$id;
}
else
{
	$id=getSafePositiveInteger('id','g');
	$uid=$id;
}
//echo $id;
//exit(0);
$back=0;

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


loadsettings("ppc_new");
$budget_period=$GLOBALS['budget_period'];
// $budget_period (monthly ,daily)
 
if($budget_period==1)
{
	$budget_period_unit='Monthly';
	$PERIOD='in '.strftime("%B",time());

}
else if($budget_period==2)
{
	$budget_period_unit='Daily';
	$PERIOD='today';
}


$result=mysql_query("select * from ppc_users where uid='$id'");
$row=mysql_fetch_array($result);
if(!$row)
{
	include("admin.header.inc.php");
	echo "<br><br><span class=\"already\">Invalid user id.</span><br><br>";
	include("admin.footer.inc.php");
	exit(0);
}

$flag_time=0;

?><?php include("admin.header.inc.php"); ?>
<?php
include("../advertiser_statistics_utils.php");
//$temp=$_REQUEST['statistics'];
//echo $temp;
if(isset($_REQUEST['statistics']))
{	$show=$_REQUEST['statistics'];	}
else if(isset($_GET['statistics']))
{ $show=$_GET['statistics']; }
else
{
	$show="day";
}

$tab =$_GET['tab'];

if($tab!=2 && $tab!=3 && $tab!=4  && $tab!=5)
$tab=1;

$url=$_SERVER['REQUEST_URI'];
//Appending statistics with url
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



if($_REQUEST['adtype'])
{
	$adtype=$_REQUEST['adtype'];
	$device=$_REQUEST['device'];
	$st=$_REQUEST['status'];
	if($adtype=="0")
	{
		$adty="and b.adtype='0'";
		$adty1="and adtype='0'";
	}
	elseif($adtype=="1")
	{
		$adty="and b.adtype='1'";
		$adty1="and adtype='1'";
	}
	elseif($adtype=="2")
	{
		$adty="and b.adtype='2'";
		$adty1="and adtype='2'";
	}
	else
	{
		$adty="";
		
	}
	if($st=="1")
	{
		$stat="and b.status ='1'";
		$urlstr="&status=1";
	$stat1="and status ='1'";	
	}
	elseif($st=="-1")
	{
	$stat="and b.status ='-1'";	
	$urlstr="&status=-1";
	$stat1="and status ='-1'";	
	}
	elseif($st=="0")
	{
		$stat="and b.status ='0'";
		$urlstr="&status=0";	
			$stat1="and status ='0'";
	}
	else
	{
		$stat="";
		$stat1=" ";
	}
	if($device=="0")
	{
		$dev="and b.wapstatus='0'";
		$dev1="and wapstatus='0'";
	}
	elseif($device=="1")
	{
		$dev="and b.wapstatus='1'";
		$dev1="and wapstatus='1'";
	}
	else
	{
		$dev="";
		$dev1="";
	}
}
else
{
	$adty="";
	$dev="";
	$stat="";
	$adty1="";
	$dev1="";
	$stat1="";
}




$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 10;


?>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("You are about to delete the ad. It won't be available later.")
		if (answer)
			return true;
		else
			return false;
		}
</script>
<script type="text/javascript">
	function promptusers()
		{
		var answer = confirm ("Do you really want to delete this advertiser.")
		if (answer)
			return true;
		else
			return false;
		}
		</script>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>
<script  language="javascript" type="text/javascript" src="../swfobject.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/advertisers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Advertiser Profile</td>
  </tr>
</table>


<br />

<?php
$currr_status='';
	if($row[4]==1)
		{
			$stat_str="ppc-change-user-status.php?action=block&id=$row[0]";
			$currr_status= "Active";
			$check="Block";
		}
		elseif($row[4]==-1)
		{
			$stat_str="ppc-change-user-status.php?action=approve&id=$row[0]";
			$stat_str_reject="ppc-change-user-status.php?action=reject&id=$row[0]";
			$currr_status= "Pending";
			$check="Approve";
			$check_reject="Reject";
		}
			else if($row[4]==-2)
				{
  				$stat_str="ppc-change-user-status.php?action=approve&id=$row[0]";
  				$stat_str_reject="ppc-change-user-status.php?action=reject&id=$row[0]";
				$currr_status= "Email not verified";
			$check="Approve";
			$check_reject="Reject";
  				}			
		else
		{
			$stat_str="ppc-change-user-status.php?action=activate&id=$row[0]";
			$currr_status= "Blocked";
			$check="Activate";
		}
		
//	if($row[17]!=0)
//	{
//		$type_value=3;	
//	}
//	else
//	$type_value=2;	
		
?>		
<table width="100%"   cellpadding="0" cellspacing="0" > <!--style="background-color:#FDFDFD">-->
	<tr >
	  <td height="22" align="left" style="border-bottom:1px solid #CCCCCC"><strong>Primary Details</strong> </td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
  </tr>
	<tr>
		<td width="362" height="22" align="left">Username</td>
		<td width="33" ><strong>:</strong></td>
		<td width="914" align="left"><?php echo $row[1];?> <?php if($check=='Block')  
		{
		echo ' ( <a href="member-login.php?type=2&id='.$row[0].'">Login</a> )'; 
		}
		
		?>
	</td>
	</tr>
	<tr>
		<td height="22" align="left">First name</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php echo $row[12];?>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" align="left">Last name</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php echo $row[13];?>&nbsp;</td>
	</tr>
	<tr>
	  <td height="22" align="left">Referred by</td>
	  <td ><strong>:</strong></td>
	  <td align="left"><?php if($row[10]==0) echo "Nobody"; else echo '<a href="view_profile_publishers.php?id='.$row[10].'">'.$mysql->echo_one("select username from ppc_publishers where  uid=$row[10]").'</a>';?>	  </td>
    </tr>
	<tr>
		<td height="24" align="left">Primary Domain</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php
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
		?> <a href="<?php echo $domain_string;?>" target="_blank"><?php echo $row[6];?></a></td>
	</tr>
	<tr >
	  <td height="22" align="left" style="border-bottom:1px solid #CCCCCC"><strong>Contact Details</strong> </td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
  </tr>
	<tr>
		<td height="22" align="left">Phone no.</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php echo $row[14];?>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" align="left">Email Id</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php echo $row[3]; echo ' ( <a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.$url.'">Send Mail</a> )'; ?></td>
	</tr>
	<tr>
		<td height="22" align="left">Address</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php echo nl2br($row[15]);?>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" align="left">Country</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php $ctr = $mysql->echo_one(" select  name from location_country where code='$row[5]'");
		if($ctr=="")
		echo $row[5];
		else
		echo $ctr;
		?></td>
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
	<tr>
		<td height="22" align="left">Account balance</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php echo moneyFormat($row[9]); echo ' ( <a href="add-advertiser-fund.php?id='.$row[0].'&url='.$url.'">Add Fund</a> )'; ?>
		&nbsp;</td>
	</tr>
	<tr>
		<td height="22" align="left">Bonus balance</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php echo moneyFormat($row[11]);?></td>
	</tr>
	<tr>
		<td height="22" align="left">Amount under settlement</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php echo moneyFormat($mysql->echo_one("select sum(clickvalue) from `ppc_daily_clicks` where   uid='$id'")); ?>
		&nbsp;</td>
	</tr>
	<tr>
		<td height="24" align="left">Last login time </td>
	  <td >:</td>
	  <td><?php echo dateTimeFormat($row[8]);?>&nbsp;</td>
    </tr>
    <tr>
		<td height="24" align="left">Single sign on </td>
	  <td >:</td>
	  <td><?php if($row[17]!=0) echo "Enabled ( <a href=\"view-user-profile.php?id=".$row[17]."\">View Account</a> )";else echo "Disabled"; ?>&nbsp;</td>
    </tr>
    <?php if($portal_system==1) {?>
    <tr>
		<td height="24" align="left">Portal status </td>
	  <td >:</td>
	  <td><?php if($row[18]==1) echo "Active";elseif($row[18]==0) echo "Blocked";elseif($row[18]==-2) echo "Email not verified";else echo "Pending";?>&nbsp;</td>
    </tr>
    
    
    <?php } ?>
    
	<tr>
		<td height="22" align="left">Advertiser Status</td>
		<td ><strong>:</strong></td>
		<td align="left"><?php 		echo $currr_status;  if($row[17]!=0) { 
		
		
			 	echo '( <a href="confirm-user-status.php?id='.$row[17].'&url='.urlencode($url).'">Change Status</a> )';
		
			 }else 
			 {
			 ?>
				( <?php 	echo '<a href="'.$stat_str.'&url='.$url.'">'.$check.'</a>'; 
			if($check=='Approve')
	  		{
	  		?> | <?php	echo '<a href="'.$stat_str_reject.'&url='.$url.'">'.$check_reject.'</a>';  
			} 
			
			
			echo ' | <a href="ppc-delete-advertiser-action.php?id='.$row[0].'&url=ppc-view-users.php" onclick="return promptusers()">Delete</a> ';

			
			?>
			
			
		)	
		</td>
	</tr>
<!--	<tr>
		<td height="22"  colspan="3">
		<?php if($check=='Block'){?>
		<?php echo '<a href="member-login.php?type=2&id='.$row[0].'">Login</a>';?> <strong>|</strong>
		<?php }
		echo '<a href="'.$stat_str.'&url='.$url.'">'.$check.' This Advertiser</a>';?>
		<?php if($check=='Approve'){?>
		<strong>|</strong>&nbsp;<?php 
		echo '<a href="'.$stat_str_reject.'&url='.$url.'">'.$check_reject.' This Advertiser</a>';?><?php } ?>
		<strong>|</strong> <?php echo '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.$url.'">Mail To This Advertiser</a>';?>
		<strong>|</strong> <?php echo '<a href="add-advertiser-fund.php?id='.$row[0].'&url='.$url.'">Add Fund</a>'; ?>		</td>
	</tr>-->
	<?php } ?>

</table>

<br>

<table width="100%"   border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #CCCCCC; ">

    <tr>
	      <td width="100%"  valign="top" >


	<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus" >
	  <tr >
	  
	  <?php 
	  
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
	  
	  
	?>
	  
		<td  align="center" id="index1_li_1" <?php echo $str_tab_style1; ?> ><a href="view_profile.php?id=<?php echo $uid; ?>&tab=1" >Overall Statistics</a></td>
		<td  align="center" id="index1_li_2" <?php echo $str_tab_style2; ?> ><a href="view_profile.php?id=<?php echo $uid; ?>&tab=2" >Ad Statistics</a></td>
		<td  align="center" id="index1_li_3"  <?php echo $str_tab_style5; ?> ><a href="view_profile.php?id=<?php echo $uid; ?>&tab=5" >Time based Statisics</a></td>
		<td  align="center" id="index1_li_3"  <?php echo $str_tab_style3; ?> ><a href="view_profile.php?id=<?php echo $uid; ?>&tab=3" >Fund History</a></td>
		<td  align="center" id="index1_li_4"  <?php echo $str_tab_style4; ?>  ><a href="view_profile.php?id=<?php echo $uid; ?>&tab=4" >Click Analysis</a></td>
	   
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
  
  <table width="100%" border= "0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center" style="font-size: 18px" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		 <td  >
		<form name="form1" method="get"			action="view_profile.php">
		<input type="hidden"			name="userid" value="<?php echo $id;?>"> 
			<input type="hidden"			name="tab" value="1"> 

			Show statistics as of 
			<select			name="statistics" id="statistics">
			<option value="day" <?php			if($show=="day")echo "selected";			?>>Today</option>
			<option value="week"			<?php			if($show=="week")echo "selected";			?>>Last 14 days</option>
			<option value="month"			<?php			if($show=="month")echo "selected";			?>>Last 30 days</option>
			<option value="year"			<?php			if($show=="year")echo "selected";			?>>Last 12 months</option>
			<option value="all" <?php			if($show=="all")echo "selected";			?>>All Time</option>
		</select> 
		 <input type="submit" name="Submit" value="Show Statistics">
	 		</form>
	</td>
		</tr>
	</table>

<table border="0" width="100%" cellpadding="0" cellspacing="0" >
<tr height="35px">
    <td width="26%"> Clicks Received </td>
	<td width="74%">:
	  <?php $totclk= round(getAdClicks(0,$time,$mysql,$id,$flag_time),2); 
              echo numberFormat($totclk,0);?>    </td>
</tr>
<tr height="35px"><td> Impressions </td>
    <td>
	     :
          <?php $totimp= round(getAdImpressions(0,$time,$mysql,$id,$flag_time),2); 
	           echo numberFormat($totimp,0);?>	</td>
</tr>
<tr height="35px"><td> CTR </td>
    <td>
	     :
	       <?php

		if($totimp==0)
		{
			$ctr=0;
		}
		else
		{
			$ctr=getCTR($totclk,$totimp);
			$ctr=numberFormat($ctr);
		}
		echo $ctr?>
          <strong>&nbsp;</strong>% </td>
</tr>
<tr height="35px"><td> Total Click Value  </td>
    <td>
	     :
          <?php 	$ret= round(getAdMoneySpent(0,$time,$mysql,$id,$flag_time),2);
		        if( $ret=="")
		         $ret=0;
		         $ret=round($ret,2);
		         echo  moneyFormat($ret);
		?>	</td>
</tr>
<tr height="35px"><td> Publisher Share </td>
     <td>
	      :
          <?php 	
		         $ret1= round(getPublisherprofit(0,$time,$mysql,$id,$flag_time),2); 
		         if( $ret1=="")
		           $ret1=0;
		           $ret1=round($ret1,2);
		        echo  moneyFormat($ret1);
		  ?>	 </td>
</tr>
<tr height="35px">
    <td>Advertiser Referral Share </td>
    <td> :
      <?php

		$ref_sh= round(getPublisherrefprofit(0,$time,$mysql,$id,$flag_time),2); 
		if( $ref_sh=="")
		$ref_sh=0;
		$ref_sh=round($ref_sh,2);
		$adv_ref_sh= round(getAdvertiserrefprofit(0,$time,$mysql,$id,$flag_time),2); 
		if( $adv_ref_sh=="")
		$adv_ref_sh=0;
		$adv_ref_sh=round($adv_ref_sh,2);
		$tot_ref=$ref_sh+$adv_ref_sh;
		echo moneyFormat($adv_ref_sh);

		?>	</td>
</tr>
<tr height="35px">
    <td>Publisher Referral Share </td>
    <td>
	: <?php echo moneyFormat($ref_sh);?>	</td>
</tr>
<tr height="35px">
    <td > Your Share<br />
 </td>
	<td>: <?php echo  moneyFormat($ret-($ret1+$tot_ref));?>  <br />
	  <span class="note">Your share=Total Click Value- (Publisher Share+Publisher Referral Share+ Advertiser Referral Share)</span>
	   </td>
</tr>
</table>
<br />

  <?php 
  }
  ?>



  
  <?php 
 
  if($tab==2)
  {
  ?>
  


	<table width="100%" border= "0" cellpadding="0" cellspacing="0">

	<tr>
		<td ><br />

		<form name="form1" method="get"			action="view_profile.php">
		<input type="hidden"			name="userid" value="<?php echo $id;?>">
					<input type="hidden"			name="tab" value="2"> 

			<!--<input type="hidden"
			name="page" value="<?php echo $pageno;?>">-->
		   Period 
		   		    <select			name="statistics" id="statistics">
			<option value="day" <?php			if($show=="day")echo "selected";			?>>Today</option>
			<option value="week"			<?php			if($show=="week")echo "selected";			?>>Last 14 days</option>
			<option value="month"			<?php			if($show=="month")echo "selected";			?>>Last 30 days</option>
			<option value="year"			<?php			if($show=="year")echo "selected";			?>>Last 12 months</option>
			<option value="all" <?php			if($show=="all")echo "selected";			?>>All Time</option>
		</select>  
		 Status <select name="status" id="status" >
			<option value="1"  <?php 
										  if($st=="1")echo "selected";			  
						  ?>>Active</option>
			<option value="-1" <?php 
										  if($st=="-1")echo "selected";			  
						  ?> >Pending</option>
			<option value="0" <?php 
										  if($st=="0")echo "selected";			  
						  ?>>Blocked</option>
			<option value="4" <?php 
										  if(($st!="1") && ($st!="0") && ($st!="-1"))echo "selected";			  
						  ?>>All</option>
			</select> 
Type <select name="adtype" id="adtype" >
		<option value="0" <?php 
									  if($adtype=="0")echo "selected";			  
					  ?>>Text Ads</option>
		<option value="1" <?php 
									  if($adtype=="1")echo "selected";			  
					  ?>>Banner Ads</option>
		<option value="2" <?php 
									  if($adtype=="2")echo "selected";			  
					  ?>>Catalog Ads</option>
		<option value="3" <?php 
									  if(($adtype!="1")&&($adtype!="0")&&($adtype!="2"))echo "selected";			  
					  ?>>All Ads</option>
		</select>
Target <select name="device" id="device" >
		<option value="0" <?php 
									  if($device=="0")echo "selected";			  
					  ?>>Desktop&Laptop</option>
		<option value="1" <?php 
									  if($device=="1")echo "selected";			  
					  ?>>Wap</option>
		<option value="2" <?php 
									  if(($device!="0")&&($device!="1"))echo "selected";			  
					  ?>>All</option>
		</select>
		<input type="submit" name="Submit" value="Go">
		</form>
		</td>
		
	</tr>
</table>






	<?php
//echo "select count(*) from ppc_ads where uid='$id' $stat1 $adty1 $dev1 ";
	$total1=$mysql->echo_one("select count(*) from ppc_ads where uid='$id' $stat1 $adty1 $dev1 ");
	if($total1==0)
	{
		echo "<br>&nbsp;No ads found for the advertiser.<br><br>";
	}
	else
	{
		//echo "select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused ,b.wapstatus from ppc_users a,ppc_ads b where a.uid=b.uid and a.uid='$id' $stat $adty $dev order by updatedtime";
		$result1=mysql_query("select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused ,b.wapstatus,b.name,b.contenttype from ppc_users a,ppc_ads b where a.uid=b.uid and a.uid='$id' $stat $adty $dev order by updatedtime  DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);//for display adds by a user
		if(mysql_num_rows($result1)==0 && $pageno>1)
		{
			$pageno--;
			$result1=mysql_query("select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused, b.wapstatus,b.name,b.contenttype from ppc_users a,ppc_ads b where a.uid=b.uid and a.uid='$id'   $stat $adty $dev order by updatedtime  DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

		}
		//echo $aa=mysql_num_rows($result1)."ll";

	?>
		
	<table width="100%" border="0" cellpadding="0" cellspacing="0"  >
	
	<tr>
		<td colspan="2" align="left"><?php if($total1>=1) {?> Showing Ads <span
			class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span
			class="inserted"> <?php if((($pageno)*$perpagesize)>$total1) echo $total1; else echo ($pageno)*$perpagesize; ?>
		</span>&nbsp;of <span class="inserted"><?php echo $total1; ?></span>&nbsp;
		<?php } ?>&nbsp;&nbsp;<br />
		<br />
		</td>
		<td width="50%" colspan="2" align="right"><?php echo $paging->page($total1,$perpagesize,"","view_profile.php?id={$uid}&statistics={$show}&adtype=$adtype&device=$device&status=$st&tab=2"); ?></td>
	</tr>
  </table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable">

<tr class="headrow" >
    <td width="249"><strong>Name</strong></td>
    <td width="143"><strong>Status</strong></td>
	<td width="110"><strong> Clicks</strong></td>
	<td width="116"><strong>Impressions</strong></td>
	<td width="138"><strong>CTR </strong></td>
	<td width="124"><strong> Click Value (<?php if($currency_format=="$$"){echo $system_currency;}else{ echo $currency_symbol;} ?>)</strong></td>
	<td width="147"><strong>Publisher Share(<?php if($currency_format=="$$"){echo $system_currency;}else{ echo $currency_symbol;} ?>)</strong></td>
				<td width="79"><strong>Referral Share (<?php if($currency_format=="$$"){echo $system_currency;}else{ echo $currency_symbol;} ?>)</strong></td>
			    <td width="75"><strong>Your Share (<?php if($currency_format=="$$"){echo $system_currency;}else{ echo $currency_symbol;} ?>)</strong></td>
			<td width="111"><strong>Action</strong></td>
	</tr>
	<?php
	$i=0;
	while($row1=mysql_fetch_row($result1))
	{

		?>

	<tr <?php if($i%2==1) { ?>class="specialrow" <?php } ?>>
	<td ><span onmouseover="showad(<?php echo $row1[7]; ?>)" onmouseout="hidead(<?php echo $row1[7]; ?>)"><a href="ppc-view-keywords.php?id=<?php echo $row1[7]; ?>&statistics=<?php echo $show; ?>&wap=<?php echo $device; ?>&url=<?php echo $url; ?>"><?php echo $row1[14]; ?></a></span>
		
	<div  id="ad<?php echo $row1[7]; ?>" class="layerbox" >
		<div class="adbox">
		<?php
		$catalog_width=0;
		$catalog_height=0;
		$banner_width=0;
		$banner_height=0;
		 if($row1[8]==0) 
		 { 
		 ?><a href="<?php echo $row1[1]; ?>"><?php echo $row1[0]; ?></a><br><?php echo $row1[2]; ?><br><?php echo $row1[9]; ?>
		 <?php
		  } 
		  else  if($row1[8]==2) 
		  {
	
			$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row1[11]' ");
			$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row1[11]' "); 
			
			
			
			
			  if($row1[15]=="swf")
		   {
		   
		  
          
		   
		   ?>
		<table   border="0" cellpadding="5" cellspacing="0"  >
			<td width="<?php echo $catalog_width; ?>" height="<?php echo $catalog_height; ?>" align="center" valign="top"><a href="<?php echo $row1[1]; ?>">
<script type="text/javascript">

		  var flashvars = {};
		  var params = {};
		  var attributes = {};
		  var i=1;
		  
		  flashvars.clickTag = "";
		  
          flashvars.clickTAG = "";
		  flashvars.clickTARGET = "_blank";
		  
		  
		  
		   params.wmode="transparent";
		 		
	      swfobject.embedSWF("<?php echo "../".$GLOBALS['banners_folder']."/$row1[7]/$row1[0]";?>", "myFlashDiv_<?php echo $row1[7]; ?>", "<?php echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars,params,attributes);
</script>
		  <div id="myFlashDiv_<?php echo $row1[7]; ?>"></div>

</a></td>
			  <td align="left" valign="top"><a href="<?php echo $row1[1]; ?>"><?php echo $row1[9]; ?></a><br><?php echo $row1[2]; ?></td>
			  </table>

		  <?php 
		   }
		   else
		   {
		   ?>
			
			
			
			<table   border="0" cellpadding="5" cellspacing="0"  >
			<td width="<?php echo $catalog_width; ?>" height="<?php echo $catalog_height; ?>" align="center" valign="top"><a href="<?php echo $row1[1]; ?>"><img src="../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row1[7]; ?>/<?php echo $row1[0]; ?>" border="0" ></a></td>
			  <td align="left" valign="top"><a href="<?php echo $row1[1]; ?>"><?php echo $row1[9]; ?></a><br><?php echo $row1[2]; ?></td>
			  </table>
			  
			  
			  
			  
			  <?php
			  }
		  }
		  else
		   {
		   
		   if($row1[15]=="swf")
		   {
		   
		   $resht=$mysql->echo_one("select height from banner_dimension where id='$row1[11]'");
           $reswt=$mysql->echo_one("select width from banner_dimension where id='$row1[11]'");
		   
		   ?>
		   <table  cellpadding="0" cellspacing="0"  >
	   <tr><td>
<script type="text/javascript">

		  var flashvars = {};
		  var params = {};
		  var attributes = {};
		  var i=1;
		  
		  flashvars.clickTag = "";
		  
          flashvars.clickTAG = "";
		  flashvars.clickTARGET = "_blank";
		  
		  
		  
		   params.wmode="transparent";
		 		
	      swfobject.embedSWF("<?php echo "../".$GLOBALS['banners_folder']."/$row1[7]/$row1[2]";?>", "myFlashDiv_<?php echo $row1[7]; ?>", "<?php echo $reswt; ?>", "<?php echo $resht; ?>", "9.0.0", "",flashvars,params,attributes);
</script>
		  <div id="myFlashDiv_<?php echo $row1[7]; ?>"></div>

</td></tr>
	   </table>

		  <?php 
		   }
		   else
		   {
		   
		   
		   ?><table  cellpadding="0" cellspacing="0"  >
		   <tr><td ><a href="<?php echo $row1[1] ;?>"><img src="<?php echo "../".$GLOBALS['banners_folder']."/$row1[7]/$row1[2]";?>"  border="0" ></a></td></tr>
		   </table><?php
		   }
		   }
			?>      
		</div>
	
	</div>
	
		
	
	</td>
	<td><?php if($row1[4]=="0")echo "Blocked";elseif($row1[4]=="1") echo "Active";else echo"Pending"; ?></td>
	<td>
	<?php
			$total_clicks=round(getAdClicks($row1[7],$time,$mysql,$row1[6],$flag_time),2); //$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$row1[6]' and aid='$row1[7]'  and time>$time");
			$ret=round(getAdMoneySpent($row1[7],$time,$mysql,$row1[6],$flag_time),2); //$mysql->echo_one("select COALESCE(SUM(money_spent),0) from $table_name where uid='$row1[6]' and aid='$row1[7]' and time>$time");
			if( $ret=="")
			$ret=0;
			$ret=round($ret,2);
	  ?>
	<?php echo numberFormat($total_clicks,0); ?>
	
	</td>
	
	<td><?php $total_impressions=round(getAdImpressions($row1[7],$time,$mysql,$row1[6],$flag_time),2); //round($mysql->echo_one("select COALESCE(SUM(impression),0) from $table_name where uid='$id' and aid='$row1[7]' and time>'$time'"),0); 
	  echo numberFormat($total_impressions,0);
	  ?></td>
	<td><?php if($total_impressions==0)
				{
					$ctr=0;
				}
				else
				{
					$ctr=($total_clicks/$total_impressions) * 100;
					$ctr=round($ctr,2);
				}
				echo numberFormat($ctr);?> %</td>
	
	<td><?php 
				
				echo numberFormat($ret);?></td>
	
	<td><?php $f=getPublisherprofit($row1[7],$time,$mysql,$row1[6],$flag_time); echo $re=numberFormat($f); ?></td>
	<td><?php $ref_sh=round(getPublisherrefprofit($row1[7],$time,$mysql,$row1[6],$flag_time),2); //$mysql->echo_one("select SUM(pub_ref_profit) from $table_name where aid='$row1[7]' and time>$time");
	  if( $ref_sh=="")
	  $ref_sh=0;
	  $ref_sh=round($ref_sh,2);
	  $adv_ref_sh=round(getAdvertiserrefprofit($row1[7],$time,$mysql,$row1[6],$flag_time),2); //$mysql->echo_one("select SUM(adv_ref_profit) from $table_name where aid='$row1[7]' and time>$time");
	  if( $adv_ref_sh=="")
	  $adv_ref_sh=0;
	  $adv_ref_sh=round($adv_ref_sh,2);
	  $tot_ref=$ref_sh+$adv_ref_sh;
	  echo numberFormat($tot_ref);?></td>
	<td><?php echo numberFormat($ret-($re+$tot_ref)); ?></td>
	<td><a href="ppc-delete-ad.php?id=<?php echo $row1[7]; ?>&url=<?php echo $url; ?>"
					onclick="return promptuser()">Delete</a> | <?php if($row1[4]==1) echo '<a href="ppc-change-ad-status.php?action=block&id='.$row1[7].'&url='.$url.'">Block</a>';  
	                
					else if($row1[4]==-1) {
          	            echo '<a href="ppc-change-ad-status.php?category=all&action=activate&id='.$row1[7].'&url='.$url.'">Activate</a> | ';
          	            echo '<a href="ppc-change-ad-status.php?category=all&action=block&id='.$row1[7].'&url='.$url.'">Block</a>';          	           
                                }
					else echo '<a href="ppc-change-ad-status.php?action=activate&id='.$row1[7].'&url='.$url.'">Activate</a>';?>
		</td>
				
	</tr>
	<?php 
	$i++;
	} 
	} ?>
	</table>
	
	<table width="100%" border="0" cellpadding="0" cellspacing="0"  >
	<tr>
		<td colspan="2" align="left"><?php if($total1>=1) {?> Showing Ads <span
			class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span
			class="inserted"> <?php if((($pageno)*$perpagesize)>$total1) echo $total1; else echo ($pageno)*$perpagesize; ?>
		</span>&nbsp;of <span class="inserted"><?php echo $total1; ?></span>&nbsp;
		<?php } ?>&nbsp;&nbsp;<br />
		<br />
		</td>
		<td width="50%" colspan="2" align="right"><?php echo $paging->page($total1,$perpagesize,"","view_profile.php?id={$uid}&statistics={$show}&adtype=$adtype&device=$device&status=$st&tab=2"); ?></td>
	</tr>

	</table>
  
  <?php 
  }
  ?>
  


    <?php 
 
  if($tab==3)
  {
  ?><?php 

$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;


$url=urlencode($_SERVER['REQUEST_URI']);


$type="p";
$typestr="";
$urlstr="&type=p";

if(isset($_REQUEST['type']))
{	

	$type=$_REQUEST['type'];
	if($type=="p")
	{
		$urlstr="&type=p";
	}
	if($type=="a")
	{
		$urlstr="&type=a";
	}	
	if($type=="b")
	{
		$typestr=" where a.pay_type =2 and a.uid=$id ";
		$urlstr="&type=b";
	}	
	if($type=="c")
	{
		$typestr=" where a.pay_type =1  and a.uid=$id ";
		$urlstr="&type=c";
	}	
	if($type=="t")
	{
		$typestr=" where a.pay_type =3  and a.uid=$id ";
		$urlstr="&type=t";
	}	
	if($type=="o")
	{
		$urlstr="&type=o";
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
	elseif($status==4)
	{
	$str=" and a.status =4 ";
	$urlstr.="&status=4";
	}	
}




$total=0;





?>
<br />



<script language="javascript" type="text/javascript">

 var opt0 = new Option('All', 'all');
 var opt1 = new Option('Pending', '0');
 var opt2 = new Option('Rejected', '2');
 var opt3 = new Option('Approved', '1');
 var opt4 = new Option('Rolled Back', '4');
 var opt5 = new Option('Completed', '3');

function updateOptions(type,sel_stat)
{
	sel_type=type.options[type.options.selectedIndex].value;
	status_control=document.ads.status;
	if(sel_type=='p'||sel_type=='a'||sel_type=='t'||sel_type=='o')
	{
		for (i = status_control.options.length - 1; i>=0; i--) 
		{
			switch(status_control.options[i].value)
			{
				case 'all':
				case '0':
				case '2':
				case '1':
				case '4':
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
		if(sel_stat=='all')
			status_control.options[0].selected=true;
		if(sel_stat=='0')
			status_control.options[1].selected=true;
		if(sel_stat=='2')
			status_control.options[2].selected=true;
		if(sel_stat=='1')
			status_control.options[3].selected=true;
		if(sel_stat=='4')
			status_control.options[4].selected=true;
		if(sel_stat=='3')
			status_control.options[5].selected=true;
	}
}
</script>

<form name="ads" action="view_profile.php" method="get">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr height="35">

    <td width="4%"   >Type</td>
    <td width="19%"> 
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="tab" value="3">
<select name="type" id="type" onchange="updateOptions(this,'')">
<option value="p" <?php if($type=="p")echo "selected"; ?>>Paypal</option>
<option value="a" <?php if($type=="a")echo "selected"; ?>>Authorize.net</option>
<option value="b" <?php if($type=="b")echo "selected"; ?>>Bank</option>
<option value="c" <?php if($type=="c")echo "selected"; ?>>Check</option>
<option value="t" <?php if($type=="t")echo "selected"; ?>>Transfer</option>
<option value="o" <?php if($type=="o")echo "selected"; ?>>Other</option>
</select>
</td>
    <td width="4%"  >Status</td>
    <td width="16%"> 

<select name="status" id="status" >
<option value="all" <?php if($status=="all")echo "selected"; ?>>All</option>
<option value="0" <?php if($status=="0")echo "selected"; ?>>Pending</option>
<option value="2" <?php if($status=="2")echo "selected"; ?>>Rejected</option>
<option value="1" <?php if($status=="1")echo "selected"; ?>>Approved</option>
<option value="4" <?php if($status=="4")echo "selected"; ?>>Rolled Back</option>
<option value="3" <?php if($status=="3")echo "selected"; ?>>Completed</option>
</select>
</td> 
<td width="57%"><input type="submit" name="Submit" value="Submit"></td>
  </tr>
  </table> </form>  



<?php
 if($type=="p")
 {
 /******************************** PAYPAL **********************************************************************/ 

		$result=mysql_query("select a.amount,a.currency,a.payeremail,a.timestamp,b.username,b.uid,b.status from inout_ppc_ipns a,ppc_users b where a.userid=b.uid and b.uid=$id and a.result='1' order by a.ipnid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		$total=$mysql->echo_one("select count(*) from inout_ppc_ipns a,ppc_users b where a.userid=b.uid  and b.uid=$id  and a.result='1'");
?>

<?php if($total>=1) {?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0"> 
   <tr>
    <td width="48%" >   Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
     &nbsp;&nbsp;  </td>
    <td width="52%" align="right" ><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$id&tab=3".$urlstr); ?></td>
   </tr> 
   </table>
   <?php } ?>  
	
	<table width="100%"  border="0" cellspacing="0" class="datatable">
        <tr class="headrow">
        <td width="27%" style="padding-left:3px;"><strong>Advertiser</strong></td>
        <td width="15%"><strong>Amount(<?php if($GLOBALS['currency_format']=="$$") echo $GLOBALS['system_currency']; else echo $GLOBALS['currency_symbol']; ?>) </strong></td>
        <td width="29%"><strong>Payer Email </strong></td>
        <td width="29%"><strong>Time</strong></td>
        </tr>
	  <?php 
	  $i=0;
	  while($row=mysql_fetch_row($result)) { ?>
     
      <tr <?php if($row[6]==0) echo 'bgcolor="#F8B9AF"'; elseif($i%2==1) {?> class="specialrow" <?php } ?>>
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><a href="view_profile.php?id=<?php echo $row[5]; ?>"><?php echo $row[4]; ?></a></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($row[0]); ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[2]; ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php  $aa=strtotime($row[3]); echo dateTimeFormat($aa);?></td>
        </tr>
	<?php $i++; } ?>	
	</table>	
	
	<?php if($total>=1) {?>
	 <table width="100%"  border="0" cellspacing="0" cellpadding="0"> 

   <tr>
    <td width="48%" >   Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
      &nbsp;&nbsp;  </td>
    <td width="52%" align="right" ><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$id&tab=3".$urlstr); ?></td>
   </tr>
	</table>
	 <?php } ?> 
<?php
}
 if($type=="a")
 {
 /******************************** Authorize.net **********************************************************************/ 
 
 		$result=mysql_query("select a.x_trans_id,a.x_invoice_num, a.x_amount,a.x_email,a.timestamp,b.username,b.uid,b.status from authorize_ipn a,ppc_users b where a.x_cust_id=b.uid  and a.x_response_code='1'  and b.uid=$id  order by a.id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		$total=$mysql->echo_one("select count(*) from authorize_ipn a,ppc_users b where a.x_cust_id=b.uid  and b.uid=$id  and a.x_response_code='1'");

?>
<?php if($total>=1) {?> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0"> 
   <tr>
    <td width="48%" >   Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
       &nbsp;&nbsp;  </td>
    <td width="52%" align="right" ><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$id&tab=3".$urlstr); ?></td>
   </tr>
   </table>
 <?php } ?>
 
 
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
        <tr class="headrow">
        <td width="10%" style="padding-left:3px;"> <strong>Invoice Number</strong> </td>
        <td width="13%" style="padding-left:3px;"> <strong>Transaction ID </strong></td>
        <td width="18%" style="padding-left:3px;"><strong>Advertiser</strong></td>
        <td width="8%"><strong>Amount (<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $currency_symbol; ?>)</strong></td>
        <td width="24%"><strong>Payer Email </strong></td>
        <td width="27%"><strong>Time</strong></td>
        </tr>
	  <?php 
	  $i=0;
	  while($row=mysql_fetch_row($result)) { ?>
     
      <tr <?php if($row[7]==0) echo 'bgcolor="#F8B9AF"'; elseif($i%2==1) {?> class="specialrow" <?php } ?>>
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><?php echo $row[1]; ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><?php echo $row[0]; ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><a href="view_profile.php?id=<?php echo $row[6]; ?>"><?php echo $row[5]; ?></a></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($row[2]); ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[3]; ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;"><?php  $aa=strtotime($row[4]); echo dateTimeFormat($aa) ; ?></td>
        </tr>
	<?php $i++; } ?>	

	</table>	

	<?php if($total>=1) {?> 
	 <table width="100%"  border="0" cellspacing="0" cellpadding="0"> 

   <tr>
    <td width="48%" >  Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
       &nbsp;&nbsp;  </td>
    <td width="52%" align="right" ><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$id&tab=3".$urlstr); ?></td>
   </tr>
	</table>
	 <?php } ?>
<?php

}

	if($type=="b" || $type=="c" || $type=="t")
	{
		$result=mysql_query("select * from  advertiser_fund_deposit_history a ".$typestr.$str." and a.uid='$uid' order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		if(mysql_num_rows($result)==0 && $pageno>1)
		{
			$pageno--;
			$result=mysql_query("select * from  advertiser_fund_deposit_history a ".$typestr.$str." and a.uid='$uid' order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		}
		$total=$mysql->echo_one("select count(*) from advertiser_fund_deposit_history a ".$typestr.$str." and a.uid='$uid'");
		
?>
<?php if($total>=1) {?>
<table  width="100%"  border="0" cellspacing="0" cellpadding="0">
  	<tr>
    <td colspan="4" style="white-space:nowrap">      Showing   deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
     &nbsp;&nbsp;    </td>
    <td width="52%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$id&tab=3".$urlstr); ?></td>
  </tr>
</table>	  
<?php } ?>   	  
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
        <tr class="headrow">
	  <td  style="padding-left:3px;" width="15%"><strong>Advertiser</strong></td>
        <td width="27%"><strong>Request date</strong></td>
        <td width="15%" ><strong>Amount(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $currency_symbol; ?>)</strong></td>
        <td width="11%"><strong>Status</strong></td>
		<td colspan="3" width="32%"><?php if($type!="t") { ?><strong>Action</strong><?php } ?></td>
      </tr>
	  <?php
	  $i=0;
	   while($row=mysql_fetch_row($result))
	   {  
	   $adv_status=$mysql->echo_one("select status from ppc_users where uid='$row[1]'");
	   ?>
	   <tr
	   <?php if($adv_status==1)
	  	{
		  if($i%2==1) { ?>class="specialrow" <?php }
		}
	 	else
	 	{  
			echo 'bgcolor="#F8B9AF"';
		}
		?>>
	  <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;">
	  <?php 
	 /* if($row[11]==2)
	  		echo "(B)&nbsp;";
	  		elseif($row[11]==3)
	  		echo "(T)&nbsp;";
		else
			echo "(C)&nbsp;";
		*/
	  ?><a href="view_profile.php?id=<?php echo $row[1]; ?>"><?php echo $mysql->echo_one("SELECT USERNAME FROM `ppc_users` WHERE uid='$row[1]'") ?></a>
	  </td>             
        <td style="border-bottom: 1px solid #b7b5b3;padding-left:3px;" ><?php echo dateTimeFormat($row[6]); ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php echo numberFormat($row[5]);  if($row[7]!=3 && $row[8]==0) { echo "<br><span class=\"note\">(Non $system_currency)</span>";  } ?></td>
        <td style="border-bottom: 1px solid #b7b5b3;" ><?php 
		if($row[7]==0)
			echo "Pending";
		if($row[7]==1)
			echo "Approved";
		if($row[7]==2)
			echo "Rejected";
		if($row[7]==3)
			echo "Completed";
			if($row[7]==4)
			echo "Rolled Back";

		 ?>          </td>
		 <td style="border-bottom: 1px solid #b7b5b3;" colspan="3"><?php if($type!="t") { ?><a href="ppc-advertiser-request-view-details.php?id=<?php echo $row[0]; ?><?php echo $urlstr; ?>">Details</a><?php } ?>
		 <?php if($row[7]==0) 
		 {?>
		  | <a href="ppc-advertiser-request-approve.php?id=<?php echo $row[0]; ?><?php echo $urlstr; ?>&newstatus=1&url=<?php echo $url; ?>">Approve</a>
		  | <a href="ppc-advertiser-request-change-status.php?id=<?php echo $row[0]; ?><?php echo $urlstr; ?>&newstatus=2&url=<?php echo $url; ?>">Reject</a>		   
		 <?php 
		 }
		 else if ($row[7]==2 || $row[7]==3 || $row[7]==4 )
		 {
				//do nothing
		 }
		 else
		 {
		 ?>
		  | <a href="ppc-advertiser-request-completed-status.php?id=<?php echo $row[0]; ?><?php echo $urlstr; ?>&newstatus=3&url=<?php echo $url; ?>">Mark Complete</a> | <a href="ppc-advertiser-request-change-status.php?id=<?php echo $row[0]; ?><?php echo $urlstr; ?>&newstatus=4&url=<?php echo $url; ?>">Rollback</a>
		 <?php 
		 } ?>	
        </td></tr>
	<?php $i++; } ?>	
		</table>
	
	 <?php if($total>=1) {?>
	 <table width="100%"  border="0" cellspacing="0" cellpadding="0"> 

	  	<tr>
    <td colspan="4" >      Showing  deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    &nbsp;&nbsp;    </td>
    <td colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$id&tab=3".$urlstr); ?></td>
	  </tr>
	</table>		
	  <?php } ?>  		
<?php		

	}	
	if($type=="o")
	{


	$result=mysql_query("select a.*,b.username,b.uid,b.status from advertiser_bonus_deposit_history a,ppc_users b where b.uid=a.aid and b.uid =$id order by logtime DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	
	$total=$mysql->echo_one("select count(*) from advertiser_bonus_deposit_history a,ppc_users b where b.uid=a.aid and b.uid =$id ");

?>

<?php if($total>=1) {?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="27" colspan="2" >   Showing  deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
      </td>
    <td width="52%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$id&tab=3".$urlstr); ?></td>
  </tr> 
</table>  
 <?php } ?> 
	  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
        <tr class="headrow">
    <td width="17%" height="34"><strong>Advertiser</strong></td>
    <td width="11%"><strong>Amount(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $currency_symbol; ?>)</strong></td>
    <td width="26%"><strong>Date </strong></td>
    <td><strong>Mode</strong></td>
    <td><strong>Comment</strong></td>
  </tr>
<?php


$i=0;
while($row=mysql_fetch_row($result))
{
?>
  <tr <?php if($row[10]==0) echo 'bgcolor="#F8B9AF"'; elseif($i%2==1) {?> class="specialrow" <?php } ?>>
    <td height="28" style="border-bottom: 1px solid #b7b5b3;"><a href="view_profile.php?id=<?php echo $row[8]; ?>"><?php echo $row[9];?></a></td>
    <td style="border-bottom: 1px solid #b7b5b3;">&nbsp;<?php echo numberFormat($row[2]);?></td>
    <td style="border-bottom: 1px solid #b7b5b3;" ><?php echo dateTimeFormat($row[5]); ?></td>
    <td width="12%" style="border-bottom: 1px solid #b7b5b3;"><?php if($row[3]==0) echo "Bonus balance"; else echo "Account balance"; ?></td>
    <td width="34%" style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[4];?>&nbsp;</td>
  </tr>


<?php
$i++;
}

?>
</table>
<?php if($total>=1) {?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0"> 
<tr>
    <td colspan="2" ><br>   
      Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted"><?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?></span>&nbsp;of 
		<span class="inserted"><?php echo $total; ?></span>&nbsp;</td>
    <td colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$id&tab=3".$urlstr); ?></td>
  </tr>
</table>
<?php } ?>

<?php
	}	


if($total==0)
{
	echo"<br>&nbsp;No Records Found<br><br>";
}

?>

<script language="javascript" type="text/javascript">

updateOptions(document.ads.type,'<?php echo $status; ?>')


</script>



  
  <?php

  }
  ?>

	
	
	

  
  <?php 
 
  if($tab==4)
  {
  
 
include("../geo/geoip.inc");
$gi = geoip_open("../geo/GeoIP.dat",GEOIP_STANDARD);

if(!isset($_REQUEST['statistics']))
{	
$show="day";	}
else
{
	$show=$_REQUEST['statistics'];
 }
 if(!isset($_REQUEST['status_clicks']))
{	
$clickbased="valid";
	}
else
{
	$clickbased=$_REQUEST['status_clicks'];
}
	$advertiserid="uid='$uid' and ";
	$advertiserid1="b.uid='$uid' and ";
 $pub_id=-1;
if(isset($_REQUEST['publishers']))
 {
$pub_id=$_REQUEST['publishers'];
 }
  if($pub_id==-1)
{	
$publisherid="";
$publisherid1="";
	}
else
{ 
	$publisherid=" and pid='$pub_id'";
	$publisherid1=" and a.pid='$pub_id'";
 }
 
$urlstr='&status_clicks='.$clickbased.'&publishers='.$pub_id.'&statistics='.$show;
if($show=="day")
{
$flag_time=-1;	
$showmessage="Today";
$time=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));
$beg_time=$time;

//echo date("d M h a",$end_time);
}
else if($show=="week")
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-13,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$beg_time=$time;
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-29,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time())+1,date("d",time()),date("y",time()));
$beg_time=$time;
}



if($flag_time==-1)
{
	$table="ppc_daily_clicks";
}
else
{
	$table="ppc_clicks";
}
	





$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 15;

if($clickbased=="valid")
{
	
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));	


if($flag_time==0)
{
//echo "select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from (select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from $table  where $advertiserid time>=$beg_time $publisherid UNION select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from ppc_daily_clicks  where $advertiserid time>=$spec_time_limits $publisherid )x order by `current_time` DESC";
$result=mysql_query("select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from (select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from $table  where $advertiserid time>=$beg_time $publisherid UNION select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent from ppc_daily_clicks  where $advertiserid time>=$spec_time_limits $publisherid )x order by `current_time` DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);




$total=$mysql->echo_one("SELECT count(id) FROM (SELECT * FROM $table WHERE $advertiserid time>=$beg_time $publisherid UNION SELECT *  FROM ppc_daily_clicks
WHERE $advertiserid time>=$spec_time_limits $publisherid)x");
}
else
{
	
$result=mysql_query("select id,clickvalue,ip,`current_time`,country,uid,pid,browser,platform,version,user_agent  from $table  where $advertiserid time>=$beg_time $publisherid  order by time DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from $table  where $advertiserid time>=$beg_time $publisherid");
}






}
else
{
	
	
$result=mysql_query("select a.id,a.ip,b.uid,a.pid,a.clicktime,a.publisherfraudstatus,a.browser,a.platform,a.version,a.user_agent  from ppc_fraud_clicks a join ppc_ads b on b.id=a.aid  where $advertiserid1 a.clicktime>=$beg_time $publisherid1  order by a.clicktime DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from ppc_fraud_clicks a join ppc_ads b on b.id=a.aid  where $advertiserid1 a.clicktime>=$beg_time $publisherid1  order by a.clicktime");	
}




?>
  

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
  <td colspan="2" class="style1">&nbsp;</td>
  <td width="2%">&nbsp;</td>
</tr>

  <tr>
   	<td colspan="2">	<form name="form3" method="get" action="view_profile.php">

         Period   <select name="statistics" id="statistics">
	  <option value="day"  <?php  if($show=="day")echo "selected";			  ?>>Today</option>
        <option value="week"  <?php   if($show=="week")echo "selected";				  ?>>Last 14 days</option>
        <option value="month"  <?php 	  if($show=="month")echo "selected";			  ?>>Last 30 days</option>
      </select>
			  
            <select  name="status_clicks" id="status_clicks">
			 <option value="valid"  <?php 	  if($clickbased=="valid")echo "selected";			  ?>>Valid Clicks</option>
			<option value="fraud"  <?php 	  if($clickbased=="fraud")echo "selected";		  ?>>Fraud Clicks</option>
			</select>
		
		
               <input type="hidden" name="tab" value="4">
			   <input type="hidden" name="id" value="<?php echo $uid;?>">
			
			
			Publisher
			<select  name="publishers" id="publishers">
				<option value="-1"  <?php   if($pub_id=="0")echo "selected";	  ?>>All</option>
			  <option value="0"  <?php   if($pub_id=="0")echo "selected";	  ?>>Admin</option>			 <?php		  
			  
			  
			  $ppcpub=mysql_query("select uid,username from ppc_publishers  where status='1' order by username");

		while($publist=mysql_fetch_row($ppcpub))
			{ ?>
		
		<option value="<?php echo $publist[0]; ?>" <?php if($pub_id==$publist[0]) { ?> selected="selected" <?php } ?> ><?php echo  $publist[1]; ?></option>
		
			<?php }
			?>
			  
			  
			</select> 
            <input type="submit" name="Submit" value="Show Statistics">
    </form></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

  <?php

   if($total>0)
  {?>
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
   
    <td  colspan="2" width="50%"> Showing Click Details <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
     &nbsp;&nbsp;<br />
    <br/>    </td>
    <td colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$uid&tab=4".$urlstr); ?></td>
  </tr>
  </table>
   <?php } 
  
?>  
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
        <tr class="headrow">
  <td width="7%"><strong>No </strong></td>
  <td width="7%"><strong>IP</strong></td>
  <td width="10%"><strong>Country</strong></td>
  <td width="10%"><strong>Click time</strong></td>
   <td width="10%"><strong>
   <?php if($clickbased!="fraud"){ ?>
               Click value (<?php if($GLOBALS['currency_format']=="$$") echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) <?php }
    else { ?>Type <?php } ?></strong></td>
    <td width="8%"><strong>Browser(version)</strong></td>
    <td width="8%"><strong>Platform</strong></td>
    <td width="10%"><strong>User Agent</strong></td>
   <td width="10%"><strong>Publisher</strong></td>
  </tr>
  <?php 
  if($pageno!=1)
  {
  $i=($pageno-1)*$perpagesize+1;
  }
  else
  $i=1;
 while($ans=mysql_fetch_row($result))
{ 
	if($clickbased=="valid")
	{
		$ips=$ans[2];
		$ctime=$ans[3];
		$advid1=$ans[5];
		$pubid1=$ans[6];
		$record = geoip_country_code_by_addr($gi, $ans[2]);
	}
	else
	{
		$ips=$ans[1];
		$ctime=$ans[4];
		$advid1=$ans[2];
		$pubid1=$ans[3];
		$record = geoip_country_code_by_addr($gi,$ans[1]);
	}
	?>
	
	<tr height="25" <?php if($i%2==1) {?> class="specialrow" <?php } ?>>
	<td ><?php echo $i; ?></td>
	<td ><?php echo $ips; ?></td>
	<td ><?php $country_name=$mysql->echo_one("select name from location_country where code='$record'");   echo  $country_name; ?></td>
	<td ><?php echo dateTimeFormat($ctime); ?></td>
	 <td ><?php
	  if($clickbased!="fraud")
		{  echo numberFormat($ans[1]); 
		 }else 
		 { 
		 if($ans[5]==1){ echo "Publisher Fraud"; }elseif($ans[5]==0) { echo "Repetitive Click"; }elseif($ans[5]==2){ echo "Invalid IP"; }elseif($ans[5]==3){ echo "Proxy Click"; }elseif($ans[5]==4){ echo "Bot Click"; }elseif($ans[5]==5){ echo "Invalid Geo"; }
		}
	 ?></td>
	 <?php
	  if($clickbased!="fraud")
		{ // echo numberFormat($ans[1]); 
		 
		?>
		<td><?php echo $ans[7]."(".$ans[9].")"; ?></td>
		<td><?php echo $ans[8]; ?></td>
		<td><?php echo $ans[10]; ?></td>
		<?php
		}
		else 
		{ 
		?>
		<td><?php echo $ans[6]."(".$ans[8].")"; ?></td>
		<td><?php echo $ans[7]; ?></td>
		<td><?php echo $ans[9]; ?></td>
		<?php	
		}
	 ?>
	<td ><?php if($pubid1==0) echo "Admin"; else echo $mysql->echo_one("select username from ppc_publishers where uid='$pubid1' "); ?></td>
	</tr>
	<?php
	$i++;
}
 ?>
   </table>
   <?php if($total>=1) {?>
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td  colspan="2"> Showing Click Details <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
      &nbsp;&nbsp;<br /><br/>    </td>
    <td width="50%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","view_profile.php?id=$uid&tab=4".$urlstr); ?></td>
  </tr>
  </table>
 <?php

  }
  else
  {  ?>
  	<br>No Records Found.<br /><br />


  	<?php 
	 } 
  }
  ?>	




  <?php 
 
  if($tab==5)
  {
  
  ?>
  <table width="100%" border= "0" cellpadding="0" cellspacing="0">
	<tr>
	<td width="45%">
	
		<form name="form1" method="get"	action="view_profile.php">
			<input type="hidden"		name="userid" value="<?php echo $id;?>"> 
						<input type="hidden"			name="tab" value="5"> 

			<!--<input type="hidden"	name="page" value="<?php echo $pageno;?>">-->
			 Show statistics as of
			  <select			name="statistics" id="statistics">
			<option value="day" <?php			if($show=="day")echo "selected";			?>>Today</option>
			<option value="week"			<?php			if($show=="week")echo "selected";			?>>Last 14 days</option>
			<option value="month"			<?php			if($show=="month")echo "selected";			?>>Last 30 days</option>
			<option value="year"			<?php			if($show=="year")echo "selected";			?>>Last 12 months</option>
			<option value="all" <?php			if($show=="all")echo "selected";			?>>All Time</option>
		</select> 
		<input type="submit" name="Submit" value="Show Statistics">
		</form>
	  </td>
	</tr>
		
</table>



<table width="100%" cellspacing="0" cellpadding="0" class="datatable">
        <tr class="headrow">
	<td><span ><strong>Period</strong></span></td>
<td><span ><strong> Clicks Received </strong></span></td>
		<td><span ><strong>Impressions</strong></span></td>
		<td><span ><strong>CTR(%)</strong></span></td>
<td><span ><strong>Money Spent(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</strong></span></td>
</tr>

<?php
$tablestructure=getTimeBasedAdvertiserStatistics($show,$flag_time,$beg_time,$end_time,$id);
if(count($tablestructure) > 0)
{
$i=0;
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
           // $str=date("d/M h a",$key);
$str=dateTimeFormat($key,"%d %b %l %p");
            $show_duration="$str";
            }
            
           
?>
<tr height="25" <?php if($i%2==1) { ?>class="specialrow" <?php } ?>>
<td>
<?php echo  $show_duration; ?></td>
<td><?php echo numberFormat($value[1],0); ?></td>
<td><?php echo numberFormat($value[0],0); ?></td>

<td><?php echo numberFormat(getCTR($value[1],$value[0])); ?></td>
<td><?php echo numberFormat($value[2]); ?></td>
</tr>
<?php
$i++;
}

}
else
{
?>
<tr  height="25px">
<td colspan="5"  >No Data to display. </td>
</tr>


<?php
}
?>
</table>

<?php
		if($adserver_upgradation_date!=0)
		{
			?>
		<br>
		<strong>Note:</strong><span class="info"> Impressions and Click Through Rates are available from : <?php echo dateTimeFormat($adserver_upgradation_date);?>.</span>

			<?php 
		}

			
  }
  ?>	
	
	</td></tr></table>
	<br />

<?php include("admin.footer.inc.php"); ?>

<script type="text/javascript">

		
		function showad(id)
		{
			document.getElementById('ad'+id).style.display='block';
		}

		function hidead(id)
		{
			document.getElementById('ad'+id).style.display='none';
		}
		
		

</script>