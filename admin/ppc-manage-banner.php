<?php

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

?><?php include("admin.header.inc.php"); ?>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/banner-dimension.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Banner Dimensions</td>
  </tr>
</table>
 <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
 <br />
 
<div ><span class="inserted">Banner Dimensions already in database are listed below.</span>
</div>
  <br />
  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">
   <?php
	$result=mysql_query("select id,height,width,file_size,wap_status from banner_dimension order by id DESC");
	$no=mysql_num_rows($result);
	
	if($no!=0)
	{ 
	?>

		  
	<tr  class="headrow">
      <td width="26%" align="left"   ><span class="style1">Dimension (width X height)</span></td>
		  <td width="23%" align="left"><span class="style1">Max-Size (KB)</span></td>
        
        <td width="25%" align="left"><span class="style1">Target Device</span></td>
		  <td width="26%" align="left"  ><span class="style1">Action</span></td>
    </tr>
    <?PHP
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
          if($row[4]==0)
          {
          $wapstatus="wapstatus=0";
          $table="ppc_ad_block";
          }
          else
          {
           $wapstatus="wapstatus=1";
               $table="wap_ad_block";
          }
     
		  ?>
           <tr <?php if($i%2==1) echo 'bgcolor="#ededed"';?>>  
		    <td  ><?php echo $row[2];?>px  X  <?php echo $row[1];?>px </td>
            
		    <td align="left" ><?php echo $row[3];?></td>
		    <td align="left" ><?php if($row[4]==1) echo "WAP"; else echo "Desktop&laptop";?></td>
		    <td  align="left" ><?php echo '<a href=ppc-banner-edit.php?id='.$row[0].'>Edit</a>|<a href=ppc-banner-dimension-delete.php?id='.$row[0].'>Delete</a>';?></td>
		  </tr>	
		 <?php 
		  $i=$i+1;
		 }
		  
    
     }
		  else
		  {
		 	 echo "-No Records Found-<br><br>";
		  }
		  ?>
  </table>
  <br />


<?php include("admin.footer.inc.php"); ?>