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
-->
</style>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("You are about to delete the banner. It won't be available later.")
		if (answer)
			return true;
		else
			return false;
		}
</script>


  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    
    <tr>
      <td     colspan="4" scope="row" height="50" ><?php include ("submenus/referral.php"); ?></td>
    </tr>
    <tr>
      <td   height="65" colspan="4" scope="row"class="heading" >         Referral Banners </td>
    </tr>
  </table>
	    		  <?php
		  $result=mysql_query("select * from affiliate_banners order by level ASC");
		  $no=mysql_num_rows($result);
		 if($no!=0)
		  { 
		  ?>
  <table width="100%"  border="0"  cellpadding="0" cellspacing="0">
    
    <tr>
      <td colspan="2"  scope="row"><span class="inserted"> Banner already in database are listed below. </span></td>
    </tr>
    <tr>
      <td colspan="2" scope="row">&nbsp;</td>
    </tr>
  </table>
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">


	    <?php
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
		  ?>
           <tr>
             <td height="19" colspan="5" align="left" style="border-top:1px solid #CCCCCC ;border-left:1px solid #CCCCCC ;">&nbsp;</td>
             <td align="left" style="border-right:1px solid #CCCCCC ; border-top:1px solid #CCCCCC ;">&nbsp;</td>
           </tr>
           <tr>
             <td height="35" colspan="5" align="left" style="border-left:1px solid #CCCCCC ;"><img src="affiliate-banners/<?php echo $row[0];?>/<?php echo $row[3];?>">&nbsp;&nbsp;&nbsp;</td>
            <td align="left" style="border-right:1px solid #CCCCCC ;">&nbsp;</td>
          </tr>	
  		  <tr valign="middle">
  		    <td width="18%" height="34" align="left" style="border-bottom:1px solid #CCCCCC ;border-left:1px solid #CCCCCC ;"><strong><?php echo $row[1]." X ".$row[2];?></strong></td>
      <td width="13%" style="border-bottom:1px solid #CCCCCC ;"><div align="left"><?php echo '<a href="edit-affiliate-banner.php?id='.$row[0].'">Edit</a>'?></div></td>
      <td width="13%" style="border-bottom:1px solid #CCCCCC ;"><div align="left"><?php echo '<a href="delete-affiliate-banner.php?id='.$row[0].'" onclick="return promptuser()">Delete</a>'?></div></td>
      <td width="17%" style="border-bottom:1px solid #CCCCCC ;"><div align="left">
        <?php if($i<($no-1)) {?>
        <a href="change-affiliate-banner-level.php?type=1&id=<?php echo $row[0];?>">Move Down</a>
        <?php } ?>
</div></td>
      <td width="37%" style="border-bottom:1px solid #CCCCCC ;"><?php if($row[4]!=1) {?>
        <a href="change-affiliate-banner-level.php?type=2&id=<?php echo $row[0];?>">Move Up</a>
        <?php } ?></td>
      <td width="2%" style="border-bottom:1px solid #CCCCCC ;border-right:1px solid #CCCCCC ;">&nbsp;</td>
    </tr>
	  
		    		  <tr valign="middle">
		    		    <td width="18%" align="left"></td>
      <td width="13%">&nbsp;</td>
      <td width="13%">&nbsp;</td>
      <td width="17%"><div align="left"></div></td>
      <td width="37%">&nbsp;</td>
      <td width="2%">&nbsp;</td>
    </tr>
 	<?php
	$i++;
	 }
 	?>
  </table>
  		 <?php 
		   }
		  else
		  {
		  echo "<br>- There is no record to display -<br><br>";
		  }
 include("admin.footer.inc.php"); ?>