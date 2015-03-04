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


?><?php include("admin.header.inc.php"); 

	$wap=-1;
	if(isset($_GET['wap']))
	$wap=trim($_GET['wap']);

?>
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
		var answer = confirm ("You are about to delete the ad unit. Ads won't be displayed in the pages where this ad unit is used.")
		if (answer)
			return true;
		else
			return false;
		}
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/admin-adunits.php"; ?> </td>
  </tr>
  
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Ad Units</td>
  </tr>

</table>
 <br />

 
  <form name="adunits" action="ppc-manage-ad-units.php" method="get">
Select Target Device 
<select name="wap">
<option value="-1" <?php if($wap==-1) echo "selected"; ?>>All</option>
<option value="0" <?php if($wap==0) echo "selected"; ?>>Desktops & Laptops</option>
<option value="1" <?php if($wap==1) echo "selected"; ?>>WAP Devices</option>
</select>
<input type="submit" value="Go" />
  </form>
  <br />

   
   <?php

   $url=urlencode($_SERVER['REQUEST_URI']);
   
	$pageno=1;
	if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
	$perpagesize = 20;
	
	if($wap==-1)
	{
	$total=$mysql->echo_one("select count(*) from ppc_custom_ad_block cad  where  cad.pid=0  ");
	$result=mysql_query("select cad.id,cad.name ,cad.wapstatus,cad.bid  from  ppc_custom_ad_block cad  where  cad.pid=0    order by cad.id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	}
	if($wap==0)
	{
	$total=$mysql->echo_one("select count(*) from ppc_custom_ad_block cad  where  cad.pid=0 and cad.wapstatus='0'");
	$result=mysql_query("select cad.id,cad.name ,cad.wapstatus,cad.bid  from  ppc_custom_ad_block cad  where  cad.pid=0  and cad.wapstatus='0' order by cad.id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	}

	if($wap==1)
	{
	$total=$mysql->echo_one("select count(*) from  ppc_custom_ad_block cad  where   cad.pid=0 and cad.wapstatus='1'");
	$result=mysql_query("select cad.id ,cad.name ,cad.wapstatus,cad.bid  from ppc_custom_ad_block cad  where  cad.pid=0 and cad.wapstatus='1' order by cad.id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
	}
	
   
	$no=mysql_num_rows($result);
	//echo "<br>no=$no<br>";
	if($no!=0)
	{ 
	?>
		<table width="100%"  border="0"  cellpadding="0" cellspacing="0">
		<tr>
			<td    >
		<?php if($total>=1) 
		{?>      
		Ad units <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
			<span class="inserted">
			<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
		</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
		<?php } ?>    &nbsp;&nbsp;  </td>
		<td width="51%"  align="right" scope="row"><?php echo $paging->page($total,$perpagesize,"","ppc-manage-ad-units.php?wap=$wap"); ?></td>
		   
		</tr>
		</table>

		<table width="100%"  border="0"  cellpadding="0" cellspacing="0" class="datatable">
   
		<tr class="headrow">
	      <td width="431" align="left"  >Ad unit name</td>
		   <td width="100" align="left"  >Target Device </td>
		   <td width="345" align="left"  >Parent Block </td>
		  <td width="164" align="left"  >Type</td>
          <td width="271" align="left"  >Options</td>
   		 </tr>
	    <?php
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
		  
		  if($row[2]==0) 
		  $adbrow=$mysql->select_one_row("select pad.width,pad.height,pad.ad_type,pad.ad_block_name from  ppc_ad_block pad where pad.id='$row[3]'  ");
		  else
		  $adbrow=$mysql->select_one_row("select pad.width,pad.height,pad.ad_type,pad.ad_block_name from  wap_ad_block pad where pad.id='$row[3]'  ");
		//  print_r($adbrow);
		   if($adbrow[2]==1)
	{
	$ad_type="Text only";
	}
elseif($adbrow[2]==2)
	{
	$ad_type="Banner only";
	}
elseif($adbrow[2]==4)
	{
	$ad_type="Catalog only";
	}
	elseif($adbrow[2]==6)
	{
	$ad_type="Inline Text";
	}
	elseif($adbrow[2]==7)
	{
	$ad_type="Inline Catalog";
	}
else
	{
	$ad_type="Text/Banner";
	}
		  ?>
           <tr <?php if($i%2==1) { ?> class="specialrow" <?php } ?>>
		    <td h  width="431" align="left"  ><?php echo $row[1];?></td>
          
          
		    <td ><?php if($row[2]==1) { ?><img src="images/wap.png"><?php }else { ?><img src="images/pc.png"> <?php } ?></td>
		    <td ><?php echo $adbrow[3];?>&nbsp;(<?php echo $adbrow[0];?>&nbsp;x&nbsp;<?php echo $adbrow[1];?>)</td>
		  <td ><?php echo $ad_type;?></td>
		  <td >
		  <?php if($row[2]==1) { ?>
		  <a href="ppc-admin-modify-wap-ad-unit.php?id=<?php echo $row[0]; ?>">Edit</a>
		  <?php } elseif($adbrow[2]!=7 && $adbrow[2]!=6) {?>
		  <a href="ppc-admin-modify-ad-unit.php?id=<?php echo $row[0]; ?>">Edit</a>
		  <?php } else { ?>
		  <a href="ppc-admin-modify-inline-ad-unit.php?id=<?php echo $row[0]; ?>">Edit</a>
		  <?php } ?> 
		  | <a href="ppc-ad-unit-delete.php?id=<?php echo $row[0] ;?>&url=<?php echo $url; ?>" onclick="return promptuser()">Delete</a>		  </td>
		</tr>	
		 <?php 
		 $i=$i+1;
		 }
		 ?>
		 </table>
		<table width="100%"  border="0"  cellpadding="0" cellspacing="0"> 
    <tr>
	    <td    >
	<?php if($total>=1) 
	{?>      
	Ad units <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td>
    <td width="51%"   align="right" scope="row"><?php echo $paging->page($total,$perpagesize,"","ppc-manage-ad-units.php?wap=$wap"); ?></td>
	   
    </tr>
</table>
		 
		<?php   }
		  else
		  {
		 	 echo " No Records Found<br><br>";
		  }
		  ?>

<?php include("admin.footer.inc.php"); ?>