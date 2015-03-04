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
   <td   colspan="4" scope="row" class="heading">Check Server Status</td>
  </tr>
</table>
<br />




   <?php
set_time_limit(0);   
   // $url=urlencode($_SERVER['REQUEST_URI']);
    $id=$_GET['id'];


	$result=mysql_query("select name,server_url from server_configurations where id='$id'");
	$no=mysql_num_rows($result);
	$row=mysql_fetch_row($result);
	
$master_data="";	
$statusmessage="";

if(isset($_GET['chk']))
{
	
if($fp_master=fopen($row[1]."slave-status-check-repair.php","r")) 
{



	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);

	
 fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-status-check-repair.php");
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 

if($master_data!=111 && $master_data!=110 && $master_data!=101 && $master_data!=100 )
{
mysql_query("update server_configurations set status='000' where id='$id'");
$statusmessage="<span class='already'>TCP Connection Not Ok</span>";	

}
else
{
if($master_data ==111)	
{
mysql_query("update server_configurations set status='111' where id='$id'");
$statusmessage="<span class='inserted'>TCP Connection Ok,Slave IO Running,Slave SQL Running</span>";
}	
else if($master_data ==110)	
{
mysql_query("update server_configurations set status='110' where id='$id'");
$statusmessage="<span class='inserted'>TCP Connection Ok,Slave IO Running,</span><span class='already'>Slave SQL Not Running</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='check-server.php?chkio=1&id=".$id."'>Repair Slave</a>";	
}
else if($master_data ==101)	
{
mysql_query("update server_configurations set status='101' where id='$id'");
$statusmessage="<span class='inserted'>TCP Connection Ok,</span><span class='already'>Slave IO Not Running,</span><span class='inserted'>Slave SQL Running</span>";	
}
else if($master_data ==100)	
{
mysql_query("update server_configurations set status='100' where id='$id'");
$statusmessage="<span class='inserted'>TCP Connection Ok,</span><span class='already'>Slave IO Not Running,Slave SQL Not Running</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='check-server.php?chkio=1&id=".$id."'>Repair Slave</a>";	
}	
	
	
	
	
	
}	
	
}

if(isset($_GET['chkio']))
{
	
if($fp_master=fopen($row[1]."slave-status-check-repair.php?tp=1","r")) 
{



	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);

	
 fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-status-check-repair.php?tp=1");
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 
	
	
if($master_data!=111 && $master_data!=110 && $master_data!=101 && $master_data!=100 )
{
mysql_query("update server_configurations set status='000' where id='$id'");
$statusmessage="<span class='already'>TCP Connection Not Ok</span>";	
}
else
{
if($master_data ==111)	
{
mysql_query("update server_configurations set status='111' where id='$id'");
$statusmessage="<span class='inserted'>TCP Connection Ok,Slave IO Running,Slave SQL Running</span>";	
}
else if($master_data ==110)	
{
mysql_query("update server_configurations set status='110' where id='$id'");
$statusmessage="<span class='inserted'>TCP Connection Ok,Slave IO Running,</span><span class='already'>Slave SQL Not Running</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='check-server.php?chkio=1&id=".$id."'>Repair Slave</a>";	
}
else if($master_data ==101)	
{
mysql_query("update server_configurations set status='101' where id='$id'");
$statusmessage="<span class='inserted'>TCP Connection Ok,</span><span class='already'>Slave IO Not Running,</span><span class='inserted'>Slave SQL Running</span>";	
}
else if($master_data ==100)	
{
mysql_query("update server_configurations set status='100' where id='$id'");
$statusmessage="<span class='inserted'>TCP Connection Ok,</span><span class='already'>Slave IO Not Running,Slave SQL Not Running</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='check-server.php?chkio=1&id=".$id."'>Repair Slave</a>";	
}	
	
	
	
	
	
}		
	
	
	
}


	
   
   
	if($no >0)
	{
	 
	?>
  <table width="100%"  border="0" cellpadding="0" cellspacing="0" ><!--class="datatable"-->	
 	<tr  >
 	  <td   >&nbsp;</td>
	  <td    >&nbsp;</td>
    </tr>	  
	<tr  >
      <td width="26%"   align="left" style="line-height:20px;">Status Of Server <span class="already"><?php echo $row[0]; ?></span></td>
	  <td width="74%"   align="left" style="line-height:20px;"><strong>:</strong> <a href="check-server.php?id=<?php echo $id; ?>&chk=1"><strong>Check now</strong></a></td>
	 
    </tr>
	
	 	<tr  >
 	  <td   ><?php 
	  if($statusmessage!="")	
	echo "Status check response";
	  ?></td>
	  <td    ><strong> <?php 
	   if($statusmessage!="") echo ": "; 
	   echo $statusmessage; ?></strong></td>
    </tr>	  

	 </table>	  
			
      
	
	
	
	
		  
		  

		 
		  <?php
		   }
		  else
		  {
		 	 echo "No Records Found<br><br>";
			 
		  }
		  ?>


<?php include("admin.footer.inc.php"); ?>