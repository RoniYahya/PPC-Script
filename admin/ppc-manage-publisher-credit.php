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
	color: #666666;
	font-weight: bold;
}
.style1 {color: white;
	font-weight: bold;}
-->
</style>
<script language="javascript" type="text/javascript">
function showMultiLanguage(id)
{
//ctr=document.getElementById('multiparent').rows['multi'+id];
ctr=document.getElementById('multi'+id);

ctr.style.display=='none'?ctr.style.display='block':ctr.style.display='none';
}
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/credits.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading"> Credit Texts</td>
  </tr>
</table>

 <br />
 <span class="inserted">English language credit texts are displayed below.</span><br />
<br />


    <?php
    $result=mysql_query("select * from ppc_publisher_credits where parent_id='0' ");
    $result2=mysql_query("select * from ppc_publisher_credits");  	
	$no=mysql_num_rows($result2);
	if($no!=0)
	{ 
	?>
  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable" id="multiparent">
    <tr class="headrow">
      <td width="55%" align="left">Credit text</td>
      <td width="45%" align="left">Options<br>      </td>
    </tr>
    <?php
	    $i=1;
		  while($row=mysql_fetch_row($result))
          {
          	
          	
			  ?>
			<tr <?php if($i%2==1)  { ?>class="specialrow" <?php }?>  >
			
			  <?php if($row[4] !=1)
			  {
			  ?>
			  <td     align="left" style="border-bottom-width:0px;"><?php  echo $row[1];  ?></td>
			   <?php
			  }
			  else
			  {
			  ?> 
			   <td     align="left" style="border-bottom-width:0px;"><img src="../credit-image/<?php  echo $row[0];  ?>/<?php  echo $row[1];  ?>" width="300px" height="15px" /> </td>
			   <?php
			  }
			  ?> 
			   
			  
			  <td   align="left"  style="border-bottom-width:0px;"><?php  echo '<a href="ppc-edit-publisher-credit.php?id='.$row[0].'">Edit</a>';?> | <?php echo'<a href="ppc-delete-publisher-credit.php?id='.$row[0].'">Delete</a>'; ?>
			  
			  
			
			   | <a href="javascript:showMultiLanguage(<?php echo $row[0]; ?>)">Multi-language versions</a>
			 
			   
			   
			   </td>
			</tr>
	
		<tr <?php if($i%2==1)  { ?>class="specialrow" <?php }?> style="height:1px" >
		<td  colspan="2" width="100%">
			<table width="100%" cellpadding="0" cellspacing="0"  border="0" id="multi<?php echo $row[0]; ?>" style="display:none;">  
			<?php
	
			
			$result1=mysql_query("select * from ppc_publisher_credits  where parent_id='$row[0]'");
			if(mysql_num_rows($result1) >0)
			{ 
				while($row1=mysql_fetch_row($result1))
				{
				 //$i++;
					?><tr >
					<td  style="border-width:0px"><?php echo $mysql->echo_one("select language from adserver_languages where id='$row1[2]'"); ?></td>
					
					 <?php if($row1[4] !=1)
			        {
			        ?>
					
				  	<td   align="left" width="100%" style="border-width:0px"> &raquo; </strong><?php echo $row1[1]; ?></td> 
					  <?php
			  }
			  else
			  {
			  ?> 
			  	<td   align="left" width="100%" style="border-width:0px"> &raquo; </strong><img src="../credit-image/<?php  echo $row[0];  ?>/<?php  echo $row1[1];  ?>" width="300px" height="15px" /></td> 
			  
			   <?php
			  }
			  ?> 
					
				   </tr> 
			
				<?php	
				 }
			}
			else
			{?>
			<tr>
			<td style="border-width:0px" > &raquo; Multilanguage versions not found</td>
			</tr>
			<?php	
			}
			$i++;
			?>
			</table>
		</td>
		</tr>
		<?php	
		}
		?>
	  </table>
	<?php
		   }
		  else
		  { 
		   echo "<br>No Records Found<br><br>"; 
		  }
		  ?>

<?php include("admin.footer.inc.php"); ?>