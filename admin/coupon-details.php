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



$name=$_GET['name'];
?>
<?php include("admin.header.inc.php"); ?>
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="50" colspan="4"  align="left"><?php include "submenus/coupons.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Coupon Details</td>
  </tr>
</table>


<br />

  
  
  
  

 
   <?php
   
    $url=urlencode($_SERVER['REQUEST_URI']);

	 $id=$_GET['id'];
 phpsafe($id);
 
	$result=mysql_query("select a.amount,a.logtime,b.username from  advertiser_bonus_deposit_history as a inner join ppc_users as b on a.aid=b.uid where a.couponid='$id'");
	$no=mysql_num_rows($result);
	
	
	$coupondetails=mysql_query("select name,status,type,amount,expirydate,no_times,count,coupon_code from gift_code where id='$id'");
	
	if(mysql_num_rows($coupondetails) >0)
	{
	
	$coupondetails_row=mysql_fetch_row($coupondetails);
	
	
	
   ?>
   <table width="100%"  border="0" cellpadding="0" cellspacing="0">
   
   <tr>
     <td width="26%" height="24">Coupon Name</td>
     <td width="1%"><strong>:</strong></td>
     <td width="58%"><?php echo $coupondetails_row[0]; ?> </td>
     <td width="15%">&nbsp;</td>
   </tr>
   <tr>
     <td height="24">Coupon Code </td>
     <td><strong>:</strong></td>
     <td> <?php echo $coupondetails_row[7]; ?> </td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td height="24">Coupon Type</td>
     <td><strong>:</strong></td>
     <td> 
	 
	 <?php if($coupondetails_row[2] == 1)
	echo "Percentage (%)";
	else if($coupondetails_row[2] == 2)
	echo "Flat Rate"; ?>	  </td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td height="24">Coupon Status</td>
     <td><strong>:</strong></td>
     <td> 
      <?php 
	 
	 if($coupondetails_row[1] == -1)
	echo "Pending";
	else if($coupondetails_row[1] == 2)
	echo "Blocked";
	else if($coupondetails_row[1] == 1)
	echo "Active";
	?>      </td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td height="24">Coupon Amount</td>
     <td><strong>:</strong></td>
     <td> <?php echo $coupondetails_row[3]; ?> </td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td height="24">Coupon Expiry</td>
     <td><strong>:</strong></td>
     <td> <?php echo date("m/d/Y",$coupondetails_row[4]-1);?> </td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td height="24">Max Usage (No Of Times)</td>
     <td><strong>:</strong></td>
     <td> 
      <?php if($coupondetails_row[5] ==0){echo "No Limit";} else {echo $coupondetails_row[5];} ?>      </td>
     <td>&nbsp;</td>
   </tr>
   <tr>
     <td height="24">No Of Times Used</td>
     <td><strong>:</strong></td>
     <td> <?php echo $coupondetails_row[6]; ?> </td>
     <td>&nbsp;</td>
   </tr>
   </table>
   <?php
   }
   else
   {
   ?><br />

   <span class="already">Invalid id. </span>
   <?php
   include("admin.footer.inc.php"); die;
   }
   ?>
   
	


	<br />
<span class="inserted" >Coupon <?php echo $coupondetails_row[7]; ?> -  usage details</span>
<br /><br />

	
   <?php
   
	if($no!=0)
	{ 
	?>
	
   
	
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">
	<tr class="headrow">
      <td width="30%" align="left"  >Date </td>
		   <td width="34%" align="left"  > Advertiser </td>
		  <td width="29%" align="left"  > Coupon  Amount </td>
      </tr>
	    <?php
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
		    

   
  
		  ?>
           <tr <?php if($i%2==1){ ?> class="specialrow" <?php } ?>>
		    <td  align="left" style="border-bottom: 1px solid #b7b5b3;"><?php echo date("m/d/Y",$row[1]);?></td>
         
            
			
		  <td  style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[2];?></td>
		   <td  style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[0];?></td>
		  </tr>	
		<?php
		  $i=$i+1;
		 }
		  ?>
		  </table>
		  <?php
		   }
		  else
		  {
		  ?>
		  
<br />
No Records Found<br />

			 
		<?php	 
			 
		  }
		  ?>










<?php include("admin.footer.inc.php"); ?>