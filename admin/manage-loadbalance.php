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

<style type="text/css">
<!--
.style3 {font-weight: bold}

-->
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/loadbalance.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Servers</td>
  </tr>
</table>
<br />



   <?php
   
    $url=urlencode($_SERVER['REQUEST_URI']);



	$result=mysql_query("select id,name,server_url,min_range,max_range,srv_type,status from server_configurations order by id DESC");
	$no=mysql_num_rows($result);
	
	
	
   
   
	if($no!=0)
	{ 
	?>
	
 	  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">
	  
	<tr  class="headrow">
	  <td width="39%"  > Server Details </td>
      <td width="10%"   align="left">Range</td>
	      <td width="12%" align="left"  >Type</td>
		  <td width="9%" align="left">Status<strong><span  style="color:#FF0000">*</span></strong></td>
        <td width="30%" align="left"  >Options        </td>
    </tr>
	    <?PHP
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
		    

   
  
		  ?>
           <tr <?php if($i%2==1) { ?> class="specialrow" <?php } ?>>
             <td  ><?php echo $row[1];?> (<?php echo $row[2];?>)</td>
            <td  ><?php echo $row[3]." - ".$row[4]; ?></td>
		   <td  ><?php if($row[5]==1){ echo "App Server"; }else if($row[5]==2){ echo "Load Balancer";}//else if($row[5]==3){ echo "Statistics Server"; } ?></td>
		   
		   <td><?php echo $row[6]; ?></td>
		   
		  <td  align="left" >
		  <?php if($row[5] !=1){ ?> <a href=check-server.php?id=<?php echo $row[0]; ?>>Check</a>
		  
		 | <?php } ?>
		  
		  <a href=edit-server.php?id=<?php echo $row[0]; ?>&url=<?php echo $url; ?>>Edit</a>
		  
		 | <a href="view-publishers-server.php?id=<?php echo $row[0]; ?>">Publishers</a>
		
		 <?php if($row[5] !=1){ ?>| <a href="delete-server.php?id=<?php echo $row[0]; ?>&url=<?php echo $url; ?>">Delete</a><?php } ?>		 </td>
		  </tr>	
		<?php
		  $i=$i+1;
		 }
		  ?>
	 </table>	  
		  <br />
		  
<table width="100%"  border="0" cellpadding="0" cellspacing="0" >


<tr><td  colspan="6" scope="row" height="20px" ><span style="color:#FF0000">*</span><span class="info">Status 111 indicates TCP connection ok,Slave IO running,Slave SQL running.</span><br /></td></tr>
<tr><td  colspan="6" scope="row" height="20px" ><span style="color:#FF0000">*</span><span class="info">Status 110 indicates TCP connection ok,Slave IO running,Slave SQL not running.</span><br /></td></tr>
<tr><td  colspan="6" scope="row" height="20px" ><span style="color:#FF0000">*</span><span class="info">Status 100 indicates TCP connection ok,Slave IO not running,Slave SQL not running.</span><br /></td></tr>	
<tr><td  colspan="6" scope="row" height="20px" ><span style="color:#FF0000">*</span><span class="info">Status 101 indicates TCP connection ok,Slave IO not running,Slave SQL running.</span><br /></td></tr>   
<tr><td  colspan="6" scope="row" height="20px" ><span style="color:#FF0000">*</span><span class="info">Status 000 indicates TCP connection not ok,Slave IO not running,Slave SQL not running.</span><br /></td></tr>	 

<!--
<tr><td  colspan="6" scope="row" height="20px" ><span style="color:#FF0000">*</span><span class="info">Status 011 indicates TCP connection not ok,Slave IO status running,Slave SQL status running.</span><br /></td></tr>	 
<tr><td  colspan="6" scope="row" height="20px" ><span style="color:#FF0000">*</span><span class="info">Status 001 indicates TCP connection not ok,Slave IO status not running,Slave SQL status running.</span><br /></td></tr>	 
<tr><td  colspan="6" scope="row" height="20px" ><span style="color:#FF0000">*</span><span class="info">Status 010 indicates TCP connection not ok,Slave IO status running,Slave SQL status not running.</span><br /></td></tr>
-->
      	
	 
	 
</table>	 
	 
	 
		  <?php
		   }
		  else
		  {
		 	 echo "No Records Found<br><br>";
			 
			?>
	  <?php	 
		  }
		  ?>


<?php include("admin.footer.inc.php"); ?>