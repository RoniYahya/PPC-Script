<?php
include("config.inc.php");
include("../extended-config.inc.php");  

if(!isset($_GET['id']))
{
	$id=$_POST['userid'];
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
	$PERIOD='in '.date("F");

}
else if($budget_period==2)
{
	$budget_period_unit='Daily';
	$PERIOD='today';
}

$total=$mysql->echo_one("select count(*) from nesote_inoutscripts_users  where id='$uid'");
if($total==0)
{
	include("admin.header.inc.php");
	echo "<br><br><span class=\"already\">Invalid user id.</span><br><br>";
	include("admin.footer.inc.php");
	exit(0);
}

$result=mysql_query("select * from ppc_users where common_account_id='$id'");
$row=mysql_fetch_array($result);


$flag_time=0;

?><?php include("admin.header.inc.php"); ?>

<?php
include("../advertiser_statistics_utils.php");
//$temp=$_POST['statistics'];
//echo $temp;
if(isset($_POST['statistics']))
{	$show=$_POST['statistics'];	}
else if(isset($_GET['statistics']))
{ $show=$_GET['statistics']; }
else
{
	$show="day";
}
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

if($flag_time==0)
{
	$table_name="advertiser_daily_statistics";
}
else if($flag_time==2)
{
	$table_name="advertiser_yearly_statistics";
}
else
{
	$table_name="advertiser_monthly_statistics";
}
//echo $show;
?>
<?php
$status=$_GET['status'];
$statusstr="all ";
if(isset($_GET['status']))
{
	$status=$_GET['status'];
}
else
{
	$status=4;
}
if($status==1)
{
	$str="and b.status=".'1';
	$str1="and status=".'1';
	$statusstr="active ";
}
else if($status==-1)
{
	$str="and b.status=".'-1';
	$str1="and status=".'-1';
	$statusstr="pending ";
}
else if($status==0)
{
	$str="and b.status=0";
	$str1="and status=".'0';
	$statusstr="blocked ";
}
else
{
	$str="";
	$str1="";
}

$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 10;
 $advst = $mysql->echo_one("select status from ppc_users where common_account_id='$id'");
$pubst = $mysql->echo_one("select status from ppc_publishers where common_account_id='$id'");
$mainst = $mysql->echo_one("select status from nesote_inoutscripts_users where id='$id'");

$pid = $mysql->echo_one("select uid from ppc_publishers where common_account_id='$id'");

?>


<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("Do you really want to delete this single sign on account.")
		if (answer)
			return true;
		else
			return false;
		}
		</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/users.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">User Profile</td>
  </tr>
</table>
<br />

<table width="100%"   border="0" cellpadding="0" cellspacing="0">
	<tr >
	  <td height="22" align="left" style="border-bottom:1px solid #CCCCCC"><strong>Primary Details</strong> </td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	  <td style="border-bottom:1px solid #CCCCCC">&nbsp;</td>
	<tr>
		<td width="311" height="22" align="left">Username</td>
		<td width="10" align="center"><strong>:</strong></td>
		<td width="990" align="left"><?php echo $row[1];?> <?php if($mainst==1){ ?>( <a href="member-login.php?type=3&id=<?php echo $row[17] ; ?>">Login</a> ) <?php } ?> </td>
	</tr>
	<tr>
		<td height="22" align="left">First name</td>
		<td align="center"><strong>:</strong></td>
		<td align="left"><?php echo $row[12];?>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" align="left">Last name</td>
		<td align="center"><strong>:</strong></td>
		<td align="left"><?php echo $row[13];?>&nbsp;</td>
	</tr>
	<tr>
	  <td height="22" align="left">Referred by</td>
	  <td align="center"><strong>:</strong></td>
	  <td align="left"><?php if($row[10]==0) echo "Nobody"; else echo '<a href="view_profile_publishers.php?id='.$row[10].'">'.$mysql->echo_one("select username from ppc_publishers where  uid=$row[10]").'</a>';?></td>
    </tr>
	<tr>
		<td height="24" align="left">Primary Domain</td>
		<td align="center"><strong>:</strong></td>
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
		<td height="22" align="left">Phone no</td>
		<td align="center"><strong>:</strong></td>
		<td align="left"><?php echo $row[14];?>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" align="left">Email id</td>
		<td align="center"><strong>:</strong></td>
		<td align="left"><?php echo $row[3];?> ( <a href="ppc-send-mail.php?type=2&id=<?php echo $row[17].'&url='.urlencode($url) ; ?>">Mail</a> ) </td>
	</tr>
	<tr>
		<td height="22" align="left">Address</td>
		<td align="center"><strong>:</strong></td>
		<td align="left"><?php echo nl2br($row[15]);?>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" align="left">Country</td>
		<td align="center"><strong>:</strong></td>
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


    <?php if(($single_account_mode==1)&&($portal_system==1)) {  ?>
	

	<tr>
		<td height="22" align="left">Portal Status</td>
		<td align="center"><strong>:</strong></td>
		<td align="left"><?php  if($mainst==1){ echo "Active";}   elseif($mainst==0){ echo "Blocked"; }  elseif($mainst==-2){ echo "Email not verified"; }  else {echo "Pending" ; } ?>&nbsp;</td>
	</tr>

<?php } ?>
	<tr>
		<td height="22" align="left">Advertiser Account </td>
		<td align="center"><strong>:</strong></td>
		<td align="left"><?php 
	
	
		
if($advst=="1")
{
		echo "Active";
		
}
	elseif($advst=="0")
	{
	echo "Blocked";
	}
	else
	{
		echo "Pending";		
	}	
	 ?> ( <a href="view_profile.php?id=<?php echo $row[0];?>">View</a> )		</td>
	</tr>

	<tr>
		<td height="24" align="left">Publisher Account </td>
		<td align="center"><strong>:</strong></td>
		<td align="left">
		<?php
			if($pubst=="1")
		echo "Active";
	elseif($pubst=="0")
	echo "Blocked";
	else
	
		echo "Pending";
		?> ( <a href="view_profile_publishers.php?id=<?php echo $pid;?>">View</a>	)	</td>
		</tr>
	
		<?php
		 if(($single_account_mode==1)&&($portal_system==1)) 
			 {
			 	
					if($mainst==0)		
					{
						$check= 'N.A';
					}
					else
					{	
					
					
						
						
						
						if($advst==1)
						{ 
							if($pubst==1)
							{
								$check='<a href="confirm-user-status.php?action=block&id='.$row[17].'&url='.urlencode($url).'">Change status</a>';  
								
								
							}
							else
							{ 
								if($pubst==0)
								{
								
								
									$check='<a href="confirm-user-status.php?action=activate&id='.$row[17].'&url='.urlencode($url).'">Change status</a>';
								
								}
								
								else
								{
								
									$check='<a href="confirm-user-status.php?action=pending&id='.$row[17].'&url='.urlencode($url).'">Change status</a>';
								
								}
								
								
								}	
							} 
							elseif($advst==0)
							{
								if($pubst==0)
								{ 
								
								
									$check='<a href="confirm-user-status.php?action=active&id='.$row[17].'&url='.urlencode($url).'">Change status</a>';  

								
								}
								elseif($pubst==1)
								
								
								{ 
								
									$check='<a href="confirm-user-status.php?action=activate&id='.$row[17].'&url='. urlencode($url).'">Change status</a>';
								
								}
								else
								{ 		
									$check='<a href="confirm-user-status.php?action=pending&id='.$row[17].'&url='.urlencode($url).'">Change status</a>';				
								}
							}
							elseif($advst==-1)
							{
								if($pubst==0)
								{ 
								
								$check='<a href="confirm-user-status.php?action=pending&id='.$row[17].'&url='. urlencode($url).'">Change status</a>';					
								}
							 if($pubst==1)   //advstatus pending,publisher active
 	{ 
 			
$check='<a href="confirm-user-status.php?&action=pending&id='.$row[17].'&url='.urlencode($url).'">change status</a> ';					
 	}
  else  //advstatus pending,publisher -1
 	{ 		
//$check='<a href="confirm-user-status.php?action=approve&id='.$row[17].'&url='.urlencode($url).'">Approve</a>&nbsp;|&nbsp;';  
//						$check.='<a href="confirm-user-status.php?action=reject&id='.$row[17].'&url='.urlencode($url).'">Reject</a>&nbsp;';
// 
$check='<a href="confirm-user-status.php?action=approve&id='.$row[17].'&url='.urlencode($url).'">Change Status</a> ';  
 	}
							}
							else
							{ 
							
//								$check= '<a href="confirm-user-status.php?action=approve&id='.$row[17].'&url='.urlencode($url).'">Approve</a>&nbsp;|&nbsp;';  
//								$check.= '<a href="confirm-user-status.php?action=reject&id='.$row[17].'&url='.urlencode($url).'">Reject</a>&nbsp;';
							$check='<a href="confirm-user-status.php?action=approve&id='.$row[17].'&url='.urlencode($url).'">Change Status</a> ';  
							}
							
						
					}
			 }
			 else
			 {
					
					if($advst==1)
					{ 
						if($pubst==1)
						{
							$check='<a href="confirm-user-status.php?action=block&id='.$row[17].'&url='.urlencode($url).'">Change status</a>';  
							
						}
						else
						{ 
							if($pubst==0)
							{
							
							
								$check='<a href="confirm-user-status.php?action=activate&id='. $row[17].'&url='.urlencode($url).'">Change status</a>';
							
							}
							
							else
							{
								$check='<a href="confirm-user-status.php?action=pending&id='.$row[17].'&url='.urlencode($url).'">Change status</a>';
							
							}
						
						
						}	
					} 
					elseif($advst==0)
					{
						if($pubst==0)
						{ 
						
						
							$check='<a href="confirm-user-status.php?action=active&id='.$row[17].'&url='.urlencode($url).'">Change status</a>';  
						
						}
						elseif($pubst==1)
						{
						
							$check='<a href="confirm-user-status.php?action=activate&id='. $row[17].'&url='. urlencode($url).'">Change status</a>';	
						
						}
						else
						{
							$check='<a href="confirm-user-status.php?action=pending&id='. $row[17].'&url='.urlencode($url).'">Change status</a>';			
						
						}
					}
					elseif($advst==-1)
					{
						if($pubst==0)
						{ 
						
							$check='<a href="confirm-user-status.php?action=pending&id='. $row[17].'&url='.urlencode($url).'">Change status</a>';					
						}
					if($pubst==1)   //advstatus pending,publisher active
 	{ 
 			
$check='<a href="confirm-user-status.php?&action=pending&id='.$row[17].'&url='.urlencode($url).'">change status</a> ';					
 	}
  else  //advstatus pending,publisher -1
 	{ 		
//$check='<a href="confirm-user-status.php?action=approve&id='.$row[17].'&url='.urlencode($url).'">Approve</a>&nbsp;|&nbsp;';  
//						 $check.='<a href="confirm-user-status.php?action=reject&id='.$row[17].'&url='.urlencode($url).'">Reject</a>&nbsp;';
// 
$check='<a href="confirm-user-status.php?action=approve&id='.$row[17].'&url='.urlencode($url).'">Change Status</a> ';  
 	}
					}
					else
					{ 
//						$check= '<a href="confirm-user-status.php?action=approve&id='.$row[17].'&url='.urlencode($url).'">Approve</a>&nbsp;|&nbsp;';  
//						$check.='<a href="confirm-user-status.php?action=reject&id='.$row[17].'&url='.urlencode($url).'">Reject</a>&nbsp;';
//					
					 $check='<a href="confirm-user-status.php?action=approve&id='.$row[17].'&url='.urlencode($url).'">Change Status</a> ';  
					
					}
					
					
			} 
	?>
	
	<tr>
		<td height="22" align="left">Action </td>
		<td align="center"><strong>:</strong></td>
		<td height="22" > 
		<?php echo $check; ?> | 	<?php echo '<a href="delete-user-action.php?id='.$id.'&url=manage-users.php" onclick="return promptuser()">Delete</a> '; ?>
		<?php 
	$advparent=$mysql->echo_one("select parent_status from ppc_users where common_account_id='$uid'");
	$pubparent=$mysql->echo_one("select parent_status from ppc_publishers where common_account_id='$uid'");
if(($pubparent!==$mainst) || ($advparent!==$mainst))
	
{?>		
		| <a href="manage-parentstatus.php?id=<?php echo $uid; ?>" class="already">Fix login issues</a>
<?php } ?>

</td>
	</tr>
	
	
	

		
	</tr>
	
</table>

<br>


	<?php include("admin.footer.inc.php");
?>