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
include("admin.header.inc.php"); 

$pageno=1;
if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
$perpagesize = 20;




$type="all";
$typestr="";
$urlstr="?type=all";
if(isset($_REQUEST['type']))
{	
	$type=$_REQUEST['type'];
	if($type=="p")
	{
	$typestr=" and a.paymode =1 ";
	$urlstr="?type=p";
	}
	if($type=="b")
	{
	$typestr=" and a.paymode =2 ";
	$urlstr="?type=b";
	}	
	if($type=="c")
	{
	$typestr=" and a.paymode =0 ";
	$urlstr="?type=c";
	}	
	if($type=="t")
	{
	$typestr=" and a.paymode =3 ";
	$urlstr="?type=t";
	}	
	if($type=="all")
	{
	$typestr="";
	$urlstr="?type=all";
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

$result=mysql_query("select a.id,a.reqdate  ,b.username,a.amount ,a.currency,a.paymode ,a.payeedetails,a.txid,a.payerdetails ,a.status,a.uid,b.status  from  ppc_publisher_payment_hist a,ppc_publishers b where a.uid=b.uid ".$str.$typestr."order by a.id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from ppc_publisher_payment_hist a,ppc_publishers b where a.uid=b.uid".$str.$typestr);
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/publishers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Publisher Withdrawals</td>
  </tr>
</table>

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


<form name="ads" action="ppc-publisher-withdrawal-history.php" method="get">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr height="35">
 
     <td width="4%"   >Type</td>
    <td width="31%"> 

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

<?php
if(mysql_num_rows($result)==0)
{
	echo '<br>No records found<br><br>';
	include("admin.footer.inc.php");
	exit(0);
}
	
	
	?>

<style type="text/css">
<!--
.style1 {
	color: #006600;
	font-weight: bold;
}
.style11 {color: white;
	font-weight: bold;}
-->
</style>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  

  	<tr>
    <td colspan="2" ><?php if($total>=1) {?>   Showing withdrawal history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;    </td>
    <td colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-publisher-withdrawal-history.php".$urlstr); ?></td>
	  </tr>
 <tr>
    <td colspan="4">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
        <tr class="headrow">
	        <td width="25%" style="padding-left:3px;"><strong>Request date</strong></td>
        <td width="17%"><strong>Publisher</strong></td>
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
        <td style="border-bottom: 1px solid #b7b5b3;"><a href="view_profile_publishers.php?id=<?php echo $row[10] ; ?>"><?php echo $row[2]; ?></a> </td>
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
		 <td style="border-bottom: 1px solid #b7b5b3;"><a href="ppc-publisher-request-view-details.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&uid=<?php echo $row[10]; ?>">Details</a> 
		 <?php if($row[9]==0) 
		 {?>
		  | <a <?php  if($row[5]==3) { ?> onclick="return confirm('Please note that transfer requests are immediately completed unlike other withdrawal requests. Click OK to continue')" <?php } ?> href="ppc-publisher-request-change-status.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&newstatus=1&type=<?php echo $row[5];?>">Approve</a>  | <a href="ppc-publisher-request-change-status.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&newstatus=-2&type=<?php echo $row[5];?>">Reject</a>
		 <?php 
		 }
		 else if ($row[9]==2 || $row[9]==3 || $row[9]==-2|| $row[9]==-3)
		 {
		//do nothing
		 }
		 else
		 {
		 ?>
		  | <a href="ppc-publisher-request-change-status.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&newstatus=3">Mark completed</a> | <a href="ppc-publisher-request-change-status.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&newstatus=2">Rollback</a>
	    <?php 
		 } ?>        </td>
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
    <td colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-publisher-withdrawal-history.php".$urlstr); ?></td>
	  </tr>
	<tr>
	<td colspan="6"><br></td>
	</tr>
	<tr>
	<td colspan="6"><span class="info">Blocked publishers' withdrawals are  shown in <span style="background-color:#F8B9AF">this color</span></span></td>
	</tr>
	<?php } ?> 
	<tr>
	<td colspan="4"><br></td>
	</tr>
</table>

<?php include("admin.footer.inc.php"); ?>

<script language="javascript" type="text/javascript">

updateOptions(document.ads.type,'<?php echo $status; ?>')


</script>



