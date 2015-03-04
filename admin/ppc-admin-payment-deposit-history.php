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

?><?php include("admin.header.inc.php"); 

$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;


$url=urlencode($_SERVER['REQUEST_URI']);


$type="p";
$typestr="";
$urlstr="?type=p";
if(isset($_REQUEST['type']))
{	
	$type=$_REQUEST['type'];
	if($type=="p")
	{
		$urlstr="?type=p";
	}
	if($type=="a")
	{
		$urlstr="?type=a";
	}	
	if($type=="b")
	{
		$typestr=" where a.pay_type =2 ";
		$urlstr="?type=b";
	}	
	if($type=="c")
	{
		$typestr=" where a.pay_type =1 ";
		$urlstr="?type=c";
	}	
	if($type=="t")
	{
		$typestr=" where a.pay_type =3 ";
		$urlstr="?type=t";
	}	
	if($type=="o")
	{
		$urlstr="?type=o";
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





?><table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/advertisers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Advertiser Payments</td>
  </tr>
</table>

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

<form name="ads" action="ppc-admin-payment-deposit-history.php" method="get">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr height="35">

    <td width="4%"   >Type</td>
    <td width="19%"> 

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

		$result=mysql_query("select a.amount,a.currency,a.payeremail,a.timestamp,b.username,b.uid,b.status from inout_ppc_ipns a,ppc_users b where a.userid=b.uid  and a.result='1' order by a.ipnid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		$total=$mysql->echo_one("select count(*) from inout_ppc_ipns a,ppc_users b where a.userid=b.uid  and a.result='1'");
?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0"> 
   <tr>
    <td width="48%" ><?php if($total>=1) {?>   Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td>
    <td width="52%" align="right" ><?php echo $paging->page($total,$perpagesize,"","ppc-admin-payment-deposit-history.php".$urlstr); ?></td>
   </tr>
  <tr>
    <td colspan="2">
	
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
	</table>	</td>
   </tr>
   <tr>
    <td width="48%" ><?php if($total>=1) {?>   Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td>
    <td width="52%" align="right" ><?php echo $paging->page($total,$perpagesize,"","ppc-admin-payment-deposit-history.php".$urlstr); ?></td>
   </tr>
</table>

<?php
}
 if($type=="a")
 {
 /******************************** Authorize.net **********************************************************************/ 
 
 		$result=mysql_query("select a.x_trans_id,a.x_invoice_num, a.x_amount,a.x_email,a.timestamp,b.username,b.uid,b.status from authorize_ipn a,ppc_users b where a.x_cust_id=b.uid  and a.x_response_code='1' order by a.id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		$total=$mysql->echo_one("select count(*) from authorize_ipn a,ppc_users b where a.x_cust_id=b.uid  and a.x_response_code='1'");

?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0"> 
   <tr>
    <td width="48%" ><?php if($total>=1) {?>   Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td>
    <td width="52%" align="right" ><?php echo $paging->page($total,$perpagesize,"","ppc-admin-payment-deposit-history.php".$urlstr); ?></td>
   </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
   </tr>
  <tr>
    <td colspan="2">
	
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

	</table>	</td>
   </tr>
  	  <tr>
    <td colspan="2">&nbsp;</td>
   </tr>
   <tr>
    <td width="48%" ><?php if($total>=1) {?>   Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td>
    <td width="52%" align="right" ><?php echo $paging->page($total,$perpagesize,"","ppc-admin-payment-deposit-history.php".$urlstr); ?></td>
   </tr>
</table>

<?php

}

	if($type=="b" || $type=="c" || $type=="t")
	{
		$result=mysql_query("select * from  advertiser_fund_deposit_history a ".$typestr.$str."order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		if(mysql_num_rows($result)==0 && $pageno>1)
		{
			$pageno--;
			$result=mysql_query("select * from  advertiser_fund_deposit_history a ".$typestr.$str."order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		}
		$total=$mysql->echo_one("select count(*) from advertiser_fund_deposit_history a ".$typestr.$str);
		
?>

<table  width="100%"  border="0" cellspacing="0" cellpadding="0">
  	<tr>
    <td colspan="4" style="white-space:nowrap"><?php if($total>=1) {?>      Showing   deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;    </td>
    <td width="52%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-admin-payment-deposit-history.php".$urlstr); ?></td>
  </tr>
	  
	  <tr>
	  <td colspan="7">
	  
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
		 <td style="border-bottom: 1px solid #b7b5b3;" colspan="3"><?php if($type!="t") { ?><a href="ppc-advertiser-request-view-details.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>">Details</a><?php } ?>
		 <?php if($row[7]==0) 
		 {?>
		  | <a href="ppc-advertiser-request-approve.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&newstatus=1&url=<?php echo $url; ?>">Approve</a>
		  | <a href="ppc-advertiser-request-change-status.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&newstatus=2&url=<?php echo $url; ?>">Reject</a>		   
		 <?php 
		 }
		 else if ($row[7]==2 || $row[7]==3 || $row[7]==4 )
		 {
				//do nothing
		 }
		 else
		 {
		 ?>
		  | <a href="ppc-advertiser-request-completed-status.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&newstatus=3&url=<?php echo $url; ?>">Mark Complete</a> | <a href="ppc-advertiser-request-change-status.php<?php echo $urlstr; ?>&id=<?php echo $row[0]; ?>&newstatus=4&url=<?php echo $url; ?>">Rollback</a>
		 <?php 
		 } ?>	
        </td></tr>
	<?php $i++; } ?>	
		</table>
	</td>
	</tr>
	  	<tr>
    <td colspan="4" ><?php if($total>=1) {?>      Showing  deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;    </td>
    <td colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-admin-payment-deposit-history.php".$urlstr); ?></td>
	  </tr>
</table>		
		
<?php		

	}	
	if($type=="o")
	{


	$result=mysql_query("select a.*,b.username,b.uid,b.status from advertiser_bonus_deposit_history a,ppc_users b where b.uid=a.aid   order by logtime DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	
	$total=$mysql->echo_one("select count(*) from advertiser_bonus_deposit_history   ");

?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  
  

  <tr>
    <td height="27" colspan="2" ><?php if($total>=1) {?>   Showing  deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    </td>
    <td width="52%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-admin-payment-deposit-history.php".$urlstr); ?></td>
  </tr>
  <tr>
  <td colspan="5">
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
</td></tr>
<tr>
    <td colspan="2" ><?php if($total>=1) {?><br>   
      Showing deposit history <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted"><?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?></span>&nbsp;of 
		<span class="inserted"><?php echo $total; ?></span>&nbsp;<?php } ?></td>
    <td colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-admin-payment-deposit-history.php".$urlstr); ?></td>
  </tr>
</table>


<?php
	}	


if($total==0)
{
	echo"<br>No Records Found<br><br>";
}

include("admin.footer.inc.php"); ?>

<script language="javascript" type="text/javascript">

updateOptions(document.ads.type,'<?php echo $status; ?>')


</script>

