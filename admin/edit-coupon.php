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
<link rel="stylesheet" type="text/css" href="epoch_styles.css" />

<script type="text/javascript" src="epoch_classes.js"></script>

<script type="text/javascript">

/*You can also place this code in a separate file and link to it like epoch_classes.js*/

var bas_cal,dp_cal,ms_cal,dp_cal2;      



window.onload = function () {

		dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('e_date'));	

	//	dp_cal2  = new Epoch('epoch_popup','popup',document.getElementById('popup_container2'));	

};





</script>

<style type="text/css">



.style1 {color: #FF0000}



</style>
<script language="javascript">
function check_value()
				{
				if((document.getElementById('c_name').value=="")||(document.getElementById('c_times').value=="")||(document.getElementById('gift_code').value=="")||(document.getElementById('g_amount').value=="")||(document.getElementById('e_date').value==""))
					{
					//refresh();
					
					
					
					alert("Please fill all compulsory fields");
					//document.form1.ad_title.focus();
					return false;
					}
					else
							{

							var gamount=document.getElementById('g_amount').value;
							gamount=gamount.trim();
							
							
							var ctimes=document.getElementById('c_times').value;
							ctimes=ctimes.trim();
							
							
					
							if(gamount<=0)
							{
							
							alert("Please enter positive value");
							document.getElementById('g_amount').focus();
							return false;
							}
							
							
							if(ctimes<0)
							{
							
							alert("Please enter non-negative value");
							document.getElementById('c_times').focus();
							return false;
							}
							
							
				/*			
				for (i = 0; i < gamount.length; i++) 
					{			
					 ch = gamount[i];   
					 if (ch < "0" || ch > "9")
						{
						alert("Please enter positive integers");
						document.getElementById('g_amount').focus();
						return false;
						}
				   }
				   
				   
				   
				   for (i = 0; i < ctimes.length; i++) 
					{			
					 ch = ctimes[i];   
					 if (ch < "0" || ch > "9")
						{
						alert("Please enter positive integers");
						
						document.getElementById('c_times').focus();
						return false;
						}
				   }
				   
				   */
									
							
							}
						
					
				
				}

</script>
<?php
	
	$url=urldecode($_GET['url']);
	$url=urlencode($url);
	
	
	
	 $id=$_GET['id'];
	$result=mysql_query("select * from gift_code where id='$id'");
	$row=mysql_fetch_row($result);
	?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="50" colspan="4"  align="left"><?php include "submenus/coupons.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Edit Coupon Details</td>
  </tr>
</table>
<form action="edit-coupon-action.php?url=<?php echo $url; ?>" method="post" enctype="multipart/form-data" onsubmit="return check_value()">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
  
    <td height="36">Coupon Name </td>
    <td>:</td>
    <td><label>
       <input name="c_name" type="text" id="c_name" value="<?php echo $row[1];?>">
    </label><strong><span class="style1">*</span></strong></td>
  </tr>
  
  <tr>
  
    <td height="36">Coupon Code </td>
    <td>:</td>
    <td><label>
       <input name="gift_code" type="text" id="gift_code" value="<?php echo $row[2];?>">
    </label><strong><span class="style1">*</span></strong></td>
  </tr>
  
  
   <tr>
    <td width="429" height="36">Coupon Type</td>
    <td width="18">:</td>
    <td width="864"><label><strong>
	<?php
	if($row[4]==1)
	{
	echo "Percentage (%)";
	}
	else if($row[4]==2)
	{
	echo "Flat Rate";
	}
	?>
	</strong>
		
	
    </label></td>
  </tr>
  
  <tr>
    <td width="429" height="36">Coupon Amount</td>
    <td width="18">:</td>
    <td width="864"><label>
      <input name="g_amount" type="text" id="g_amount" value="<?php echo $row[5];?>">
    </label><strong><span class="style1">*</span></strong></td>
  </tr>
  
  
  
   <tr>
    <td width="429" height="36">Max Usage (no. of times) * </td>
    <td width="18">:</td>
    <td width="864"><label>
      <input name="c_times" type="text" id="c_times"   value="<?php echo $row[7];?>">
    </label><strong><span class="style1">*</span></strong><br /><span class="info">  </span></td>
  </tr>
  

  <tr>

    <td height="39">Expiry Date</td>
    <td height="39">:</td>
    <td><label>
       <input name="e_date" type="text" id="e_date"  readonly class="input" value="<?php echo date("m/d/Y",$row[6]-1); ?>">
    </label><strong><span class="style1">*</span></strong></td>
  </tr>
  
  
  
  
  <tr>
    <td height="39"></td>
    <td height="39"></td>
    <td    ><label>
	
	<input type="hidden" name="c_type" value="<?php echo $row[4];?>">
	<input type="hidden" name="id" value="<?php echo $row[0];?>">
      <input type="submit" name="Submit" value="Submit">
    </label></td>
    </tr>
</table>
</form>





		
		
		
		
		
		
		

<?php include("admin.footer.inc.php"); ?>		