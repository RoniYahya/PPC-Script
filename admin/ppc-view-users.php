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
	//echo $url;
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

$com_account="";
//filtering status

//filtering single signon
$commonaccount=trim($_REQUEST['commonaccount']);
if(isset($_REQUEST['commonaccount']))
{
if($_REQUEST['commonaccount']==1)
{
	$com_account="and a.common_account_id!=0";
}
elseif($commonaccount=="0")
{
	$com_account="and a.common_account_id=0";
}
else
{
	$com_account="";
}
}
else
{
	$commonaccount="both";
}



//filtering single signon
//filtering country

$country_code=-1;
$str_country="";

if(isset($_REQUEST['country']) && $_REQUEST['country'] !=-1)
{
$country_code=$_REQUEST['country'];
$str_country="and a.country='$country_code'";
}
//filtering country

//echo "select  a.uid,a.username,b.name,a.domain from ppc_users a,location_country b  where a.country=b.code $str_status  $str_country $com_account order by a.uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;die;
$result=mysql_query("select  a.uid,a.username,b.name,a.domain,a.status,a.common_account_id from ppc_users a left join location_country b  on a.country=b.code where a.uid>0 $str_status  $str_country $com_account order by a.uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
if(mysql_num_rows($result)==0 && $pageno>1)
	{
		$pageno--;
		$result=mysql_query("select a.uid,a.username,b.name,a.domain,a.status,a.common_account_id from ppc_users a left join location_country b  on a.country=b.code where a.uid>0 $str_status $str_country  $com_account order by a.uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

	}
$total=$mysql->echo_one("select count(*) from ppc_users a   where a.uid>0  $str_status $str_country $com_account");
?><?php include("admin.header.inc.php"); ?>
<style type="text/css">
<!--
.style6 {font-size: 20px}

.style1 {color: white;
	font-weight: bold;}
-->
</style>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("Do you really want to delete this advertiser.")
		if (answer)
			return true;
		else
			return false;
		}
		</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/advertisers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Advertisers</td>
  </tr>
</table>

<br>

<form name="ads" action="ppc-view-users.php" method="get">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td   height="34" colspan=""> Status <select name="status" id="status" >
<option value="5" <?php 
			  				  if($status_value=="5")echo "selected";			  
			  ?>>All</option>
<option value="1"  <?php 
			  	if($status_value=="1")echo "selected";			  
			  ?>>Active</option>
<option value="0" <?php 
			  				  if($status_value=="0")echo "selected";			  
			  ?>>Blocked</option>
<option value="-1" <?php 
			  				  if($status_value=="-1")echo "selected";			  
			  ?> >Pending Approval</option>
			  
<option value="-2" <?php 
			  				  if($status_value=="-2")echo "selected";			  
			  ?> >Email not verified</option>
			  
</select>
	   <?php if($account_migration==1){ ?> Single-sign-on <select name="commonaccount">
	  <option value="1"  <?php  if($commonaccount=="1") { echo "selected";} ?>>Enabled</option>
	  <option value="0" <?php if($commonaccount=="0") { echo "selected";} ?>>Disabled</option>
	    <option value="both" <?php if($commonaccount=="both") { echo "selected";} ?>>Both</option>
	  </select> <?php   }  ?> Country <select name="country" id="country" style="width:150px">
			  
			  
			   <option value="-1" <?php 
			  				  if($country_code=="-1")echo "selected";			  
			  ?>>All</option>
			  
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

       

</select> <input type="submit" name="Submit" value="Submit"></td>
  </tr>
  </table> 
</form> 
<?php
if($total>0)
{
?>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td colspan="2" ><?php if($total>=1) {?>   Showing Advertisers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br /><br/>    </td>
    <td width="51%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-view-users.php?status=$status_value&country=$country_code"); ?></td>
  </tr>
    </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable">
  
  <tr class="headrow">
    <td width="13%" height="34"><strong>Advertiser</strong></td>
    <td width="15%"><strong>Country</strong></td>
    <td width="23%"><strong>Domain</strong></td>
	<td width="23%"><strong>Status</strong></td>
	<?php if($single_account_mode==0) 
	{?>
    <td width="23%"><strong>Action</strong></td> <?php

	}
	else
	{ ?>
	<td width="23%"><strong>Single sign on</strong></td> 
	<?php	
	}
    ?>
  </tr>
<?php
	
$i=1;

while($row=mysql_fetch_row($result))
{
?>
  <tr <?php if($i%2==1) { ?>class="specialrow" <?php }?>>
    <td height="28" ><a href="view_profile.php?id=<?php echo  $row[0];?>"><?php echo  $row[1];?></a></td>
	
    
    <td ><?php 
	if($row[2]=="")
		echo "&nbsp;";
	else
		echo $row[2];
	?></td>
    <td ><?php 
	if($row[3]!="")
		{
		
		   $domain_string= $row[3];
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
			 <a href="<?php echo $domain_string;?>"  target="_blank"><?php echo  $row[3]; ?></a>
			 
			 <?php
		 }
		 else echo '&nbsp;';
	 ?></td>
	 
	<td>
	<?php
	$str="";
	if($row[4]==1)
	{
	echo "Active";
	
	$str.='<a href="member-login.php?type=2&id='.$row[0].'">Login</a>&nbsp;|&nbsp;';  
	$str.='<a href="ppc-change-user-status.php?action=block&id='.$row[0].'&url='.urlencode($url).'">Block</a>&nbsp;|&nbsp;';  
	$str.= '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.urlencode($url).'">Mail</a>';
	if($row[5]==0)
		$str.= '&nbsp;|&nbsp;<a href="ppc-delete-advertiser-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';
	}
   if($row[4]==-2)
	{
	echo "Email not verified";
	
	$str.='<a href="ppc-change-user-status.php?action=approve&id='.$row[0].'&url='.urlencode($url).'">Approve</a>&nbsp;|&nbsp;';  
	$str.='<a href="ppc-change-user-status.php?action=reject&id='.$row[0].'&url='.urlencode($url).'">Reject</a>&nbsp;|&nbsp;';  
	$str.= '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.urlencode($url).'">Mail</a>';
	if($row[5]==0)
		$str.= '&nbsp;|&nbsp;<a href="ppc-delete-advertiser-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';
	}
	if($row[4]==-1)
	{
	echo "Pending";
	
    $str.='<a href="ppc-change-user-status.php?action=approve&id='.$row[0].'&url='.urlencode($url).'">Approve</a>&nbsp;|&nbsp;';  
	$str.='<a href="ppc-change-user-status.php?action=reject&id='.$row[0].'&url='.urlencode($url).'">Reject</a>&nbsp;|&nbsp;';  
	$str.= '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.urlencode($url).'">Mail</a>';
	if($row[5]==0)
		$str.= '&nbsp;|&nbsp;<a href="ppc-delete-advertiser-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';
	}
	if($row[4]==0)
	{
	echo "Blocked";
	
	$str.='<a href="ppc-change-user-status.php?action=activate&id='.$row[0].'&url='.urlencode($url).'">Activate</a>&nbsp;|&nbsp;';  
	$str.= '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.urlencode($url).'">Mail</a>';
	if($row[5]==0)
		$str.= '&nbsp;|&nbsp;<a href="ppc-delete-advertiser-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';
	}
	?>	</td>
	
	
	
	
	<?php
if($single_account_mode==0)
{
	 ?>
	<td>
	<?php echo $str;?>    </td>
	<?php
}
else
{
if($row[5]==0)
$sso="Disabled";
else
$sso="Enabled";	
	
?><td><?php echo $sso; ?></td>	
<?php	
}
	
	?>
  </tr>


<?php
$i++;
}

?>
</table>
	<?php if($total>=1) {?>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" >  Showing Advertisers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
			<span class="inserted">
			<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
		</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span> </td>
		<td width="51%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-view-users.php?status=$status_value&country=$country_code"); ?></td>
	  </tr>
	</table>
	<?php } ?>  
<?php
}
else
{
	echo"<br>No Records Found<br><br>";
}
 include("admin.footer.inc.php"); ?>