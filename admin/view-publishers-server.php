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
<?php

$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 1;


$id=$_GET['id'];
$sname=$mysql->echo_one("select name from server_configurations where id='$id'");
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/loadbalance.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Publishers in -  <?php echo $sname; ?></td>
  </tr>
</table>

<br />


   <?php
   
  
$total=mysql_query("select uid,username from ppc_publishers where server_id='$id' order by uid DESC");
$no=mysql_num_rows($total);






	$result=mysql_query("select uid,username from ppc_publishers where server_id='$id' order by uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	
   
	if($no!=0)
	{ 
	
	
if($no >0)
	{  
	?>
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0"  >

	<tr>
	<td colspan="2" ><?php if($no>=1) {?>   Showing Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$no) echo $no; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $no; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td><td width="50%"  colspan="2" align="right">
   
    <?php echo $paging->page($no,$perpagesize,"","view-publishers-server.php?id=$id"); ?>
    </td>
  </tr>
  </table>
	 <?php	
	}	
	
	?>
			  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">
  
	<tr  class="headrow">
	  <td width="29%"  ><strong>Publisher Name </strong></td>
      <td width="37%" align="left"  ><strong>Options</strong></td>
    </tr>
	    <?PHP
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
		    

   
  
		  ?>
           <tr <?php if($i%2==1) { ?> class="specialrow" <?php } ?>>
             <td  ><?php echo $row[1]; ?></td>
            <td  align="left" ><a href=view_profile_publishers.php?id=<?php echo $row[0]; ?>>View Profile</a>		 </td>
		  </tr>	
		<?php
		  $i=$i+1;
		 }
		  ?>
		  </table>
		<?php
if($no >0)
	{  
	?>
	
	
		  <table width="100%"  border="0" cellpadding="0" cellspacing="0"  >

	<tr>
	<td colspan="2" ><?php if($no>=1) {?>   Showing Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$no) echo $no; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $no; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td><td width="50%"  colspan="2" align="right">
   
    <?php echo $paging->page($no,$perpagesize,"","view-publishers-server.php?id=$id"); ?>
    </td>
  </tr>
  </table>
	 <?php	
	}	
	
	?>
		  
	  </table>
	  
		  
<!-- <tr>
      <td width="3%"  height="40px" >&nbsp;</td>
	 <td  colspan="5" scope="row" ><span class="info">Note <strong><span  style="color:#FF0000">*</span></strong> indicates Inactive status. </span></td>
      
    </tr>  -->
		 
		  <?php
		   }
		  else
		  {
		 	 echo "No Records Found<br><br>";
		  }
		  ?>
		  
		  
		  
		  

<?php include("admin.footer.inc.php"); ?>