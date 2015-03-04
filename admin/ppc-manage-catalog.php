<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

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

?><?php include("admin.header.inc.php"); ?>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("You are about to delete the catalog dimension. It won't be available later.")
		if (answer)
			return true;
		else
			return false;
		}
</script>
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr><td colspan="4"  >&nbsp;</td></tr>
    <tr>
      <td colspan="4" scope="row" class="heading" > 
        Manage Catalog Dimensions  </td>
    </tr>
	<tr>
	<td colspan="4" align="left" ><br />
<div ><span class="inserted">Catalog dimensions in database are listed below.</span></div></td>
    </tr>
    
 
  </table><br />

   <?php
	$result=mysql_query("select id,height,width,filesize,wapstatus from catalog_dimension order by id DESC");
	$no=mysql_num_rows($result);
	
	if($no!=0)
	{ 
	?>
  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">
		  
	<tr class="headrow">
      <td width="23%" align="left"  height="30">Dimension <br />
(width x height)</td>
		  <td width="25%" align="left">Max-Size (KB)</td>
        
        <td width="25%" align="left">Mode</td>
		  <td width="25%" align="left"  height="30">Options</td>
    </tr>
	    <?PHP
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
		
		  ?>
           <tr <?php if($i%2==1)  { ?>class="specialrow" <?php }?>>
		    <td   style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[2];?>&nbsp;x&nbsp;<?php echo $row[1];?></td>
            
		    <td align="left" style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[3];?></td>
		    <td align="left" style="border-bottom: 1px solid #b7b5b3;"><?php if($row[4]==1) echo "WAP"; else echo "Desktop&laptop";?></td>
		    <td   align="left" style="border-bottom: 1px solid #b7b5b3;"><?php echo '<a href=ppc-catalog-edit.php?id='.$row[0].'>Edit</a>';?>&nbsp;</td>
		  </tr>	
		 <?php 
		  $i=$i+1;
		 }?>
		    </table>
		<?php
		   }
		  else
		  {
		 	 echo "<br>No Records Found<br><br>";
		  }
		  ?>
<?php include("admin.footer.inc.php"); ?>