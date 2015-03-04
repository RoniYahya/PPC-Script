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
?>
<?php include("admin.header.inc.php"); ?>

<?php

$status=2;
if(isset($_GET['status']))
$status=$_GET['status'];


   
    $url=urlencode($_SERVER['REQUEST_URI']);

if($status==1)
{
	$strstring=mktime(0,0,0,date("m"),date("d")+1,date("Y"));
	$result=mysql_query("select id,coupon_code,amount,expirydate,name,status,type,no_times from gift_code  where expirydate >='$strstring' order by id DESC");
}	
elseif($status==0)
{
	$strstring=mktime(0,0,0,date("m"),date("d")+1,date("Y"));
	$result=mysql_query("select id,coupon_code,amount,expirydate,name,status,type,no_times from gift_code  where expirydate < '$strstring' order by id DESC");
}	
else
{
	$result=mysql_query("select id,coupon_code,amount,expirydate,name,status,type,no_times from gift_code    order by id DESC");
}	
   


?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="50" colspan="4"  align="left"><?php include "submenus/coupons.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Coupon Codes</td>
  </tr>
</table>
<br />

<form name="form1" method="get" action="manage-coupons.php">
Status <select name="status">
<option value="2" <?php if($status==2) echo "selected"; ?>>All</option>
<option value="1" <?php if($status==1) echo "selected"; ?>>Non-expired</option>
<option value="0" <?php if($status==0) echo "selected"; ?>>Expired</option></select>
<input type="submit" value="Submit" />
</form>
<br />

   <?php
   
 	$no=mysql_num_rows($result);
  
	if($no!=0)
	{ 
	?>
	
  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">
		  
	<tr   class="headrow">
	  <td width="13%"  > Name </td>
          <td width="13%" align="left"> Code </td>
      <td width="11%" align="left"  >Type     </td>
      <td width="9%" align="left"  >Value      </td>
		   <td width="9%" align="left">Status</td>
	       <td width="10%" align="left">Max Usage</td>
          <td width="10%" align="left"  >Expiry    </td>
        <td width="24%" align="left"  >Options</td>
    </tr>
	    <?php
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
		    

   
  
		  ?>
           <tr <?php if($i%2==1) { ?> class="specialrow" <?php } ?>>
             <td  style="border-bottom: 1px solid #b7b5b3;"><a href="coupon-details.php?id=<?php echo $row[0]; ?>&name=<?php echo $row[1]; ?>"><?php echo $row[4];?></a></td>
            <td align="left" style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[1];?></td>
            <td  align="left" style="border-bottom: 1px solid #b7b5b3;"><?php if($row[6] == 1){ echo "Percentage";}else if($row[6] == 2){ echo "Flat Rate";} ?>                                     </td>
         
            
			
		  <td  style="border-bottom: 1px solid #b7b5b3;"><?php if($row[6] == 1){ echo numberFormat($row[2])." %";}else { echo moneyformat($row[2]); }?></td>
		   <td style="border-bottom: 1px solid #b7b5b3;"><?php 
		   
		     if($row[5] == 1){ echo "Active";}else if($row[5] == -1){ echo "Pending";} else if($row[5] == 2){ echo "Blocked";}
			 if(time()>$row[3])
		   {
		   	echo "<br>(Expired)";
		   }
		   ?></td>
		   <td style="border-bottom: 1px solid #b7b5b3;"><?php if($row[7] == 0){echo "No Limit";}else {echo $row[7]; } ?> </td>
		   <td  style="border-bottom: 1px solid #b7b5b3;"><?php echo date("m/d/Y",$row[3]-1);?></td>
		  <td  align="left" style="border-bottom: 1px solid #b7b5b3;">
		   
		   <a href=edit-coupon.php?id=<?php echo $row[0]; ?>&url=<?php echo $url; ?>>Edit</a>  
		  <?php if($row[5] == -1 || $row[5] == 2 ){?> | <a href=change-coupon-status.php?id=<?php echo $row[0]; ?>&action=activate&url=<?php echo $url; ?>>Activate</a> 
		  <?php }else if($row[5] ==1){?> | <a href=change-coupon-status.php?id=<?php echo $row[0]; ?>&action=block&url=<?php echo $url; ?>>Block</a>   <?php } ?>
		  
		  
		  <?php
		  if($mysql->total("advertiser_bonus_deposit_history","couponid ='$row[0]'")==0)
		  {
		  ?>
		   | <a href="delete-coupon.php?id=<?php echo $row[0]; ?>&url=<?php echo $url; ?>">Delete</a>
		  
		  <?php
		  }
		  ?>
		  
		  </td>
		  </tr>	
		<?php
		  $i=$i+1;
		 }?>
		    </table>
			<?php
		   }
		  else
		  {
		 	 echo "No Records Found<br><br>";
		  }
		  ?>

<?php include("admin.footer.inc.php"); ?>