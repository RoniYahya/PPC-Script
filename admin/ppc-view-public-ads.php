<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/






 
include("config.inc.php");
include("../extended-config.inc.php");  
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

///////////////////////////wap 

//if(isset($_GET['wap']))
//{
//	$wap_flag=$_GET['wap'];
//}
//else
//{
//	$wap_flag='';
//}
//phpSafe($wap_flag);
//
//if($wap_flag==1)
//{
//$wap_flag_type=1;
//	$name='wap';
//	$wap_string=' wapstatus=1';
//	$image='wap.png';
//	
//}
//else if($wap_flag=='')
//{
//$wap_flag_type=2;
//	$name='';	
//	$wap_string=' (wapstatus=1 or wapstatus=0)' ;
//	//$wap_flag=2;
//}
//else
//{
//$wap_flag_type=0;
//   $wap_flag==0;
//   $name='';	
//   $wap_string=' wapstatus=0';
//   $image='pc.png';
//    
//   
//}
/////////////////////////wap

if($_REQUEST)
{
$adtype=$_REQUEST['adtype'];
$device=$_REQUEST['device'];
$st=$_REQUEST['status'];

if($adtype=="0")
{
	$adty="and adtype='0'";
}
elseif($adtype=="1")
{
	$adty="and adtype='1'";
}
elseif($adtype=="2")
{
	$adty="and adtype='2'";
}
else
{
	$adty="";
	
}
if($st=="1")
{
	$stat="and status ='1'";
}
elseif($st=="-1")
{
$stat="and status ='-1'";	
}
elseif($st=="0")
{
	$stat="and status ='0'";	
}
else
{
	$stat="";
}
if($device=="0")
{
	$dev="and wapstatus='0'";
	
}
elseif($device=="1")
{
	$dev="and wapstatus='1'";
	$wap_name='Wap';
}
else
{
	$dev="";
}
}
else
{
	$adty="";
	$dev="";
	$stat="";
}


$url=$_SERVER['REQUEST_URI'];
$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
$sqladdstring="";
if(isset($_REQUEST['id']))
$sqladdstring="and id='$_REQUEST[id]'";
//echo "select title,link,summary,status,id,adtype,displayurl,bannersize,wapstatus,name from ppc_public_service_ads where id <> 0 $sqladdstring  $dev $adty $stat  order by createtime desc  LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;
$result=mysql_query("select title,link,summary,status,id,adtype,displayurl,bannersize,wapstatus,name,contenttype from ppc_public_service_ads where id <> 0 $sqladdstring  $dev $adty $stat  order by createtime desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize); 
if(mysql_num_rows($result)==0 && $pageno>1)
	{
		$pageno--;
		$result=mysql_query("select title,link,summary,status,id,adtype,displayurl,bannersize,wapstatus,name,contenttype from ppc_public_service_ads where id <> 0 $sqladdstring $dev $adty $stat  order by createtime desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

	}
$total=$mysql->echo_one("select count(*) from ppc_public_service_ads where id <> 0 $sqladdstring $dev $adty $stat");
?><?php include("admin.header.inc.php"); ?>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("You are about to delete the ad. Do you want to continue?")
		if (answer)
			return true;
		else
			return false;
		}
		
		function showad(id)
		{
			document.getElementById('ad'+id).style.display='block';
		}

		function hidead(id)
		{
			document.getElementById('ad'+id).style.display='none';
		}
		
		
</script>
<script  language="javascript" type="text/javascript" src="../swfobject.js"></script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/service-ads.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Public Service Ads</td>
  </tr>
</table>
  <form name="psads" action="ppc-view-public-ads.php" method="post">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="34" colspan="6"><span class="inserted">Public service ads are listed below. You may block/activate/edit/delete  public service ads. </span></td>
  </tr>
 


<tr height="30px"><td colspan="6" >
Status
    <select name="status" id="status" >
  <option value="1"  <?php 
			  				  if($st=="1")echo "selected";			  
			  ?>>Active</option>
      
  <option value="0" <?php 
			  				  if($st=="0")echo "selected";			  
			  ?>>Blocked</option>
  <option value="4" <?php 
			  				  if(($st!="1") && ($st!="0") && ($st!="-1"))echo "selected";			  
			  ?>>All</option>
    </select>
Type
    <select name="adtype" id="adtype" >
      <option value="0" <?php 
			  				  if($adtype=="0")echo "selected";			  
			  ?>>Text Ads</option>
      <option value="1" <?php 
			  				  if($adtype=="1")echo "selected";			  
			  ?>>Banner Ads</option>
      <option value="2" <?php 
			  				  if($adtype=="2")echo "selected";			  
			  ?>>Catalog Ads</option>
      <option value="3" <?php 
			  				  if(($adtype!="1")&&($adtype!="0")&&($adtype!="2"))echo "selected";			  
			  ?>>All Ads</option>
      </select>
Target device
      <select name="device" id="device" >
        <option value="0" <?php 
			  				  if($device=="0")echo "selected";			  
			  ?>>Desktop&Laptop</option>
        <option value="1" <?php 
			  				  if($device=="1")echo "selected";			  
			  ?>>Wap</option>
        <option value="2" <?php 
			  				  if(($device!="0")&&($device!="1"))echo "selected";			  
			  ?>>All</option>
        </select> 
<input type="submit" name="Submit" value="Submit"></td>
</tr>

</table>
<?php
if($total>0)
{
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">

<tr>
    <td colspan="2"><?php if($total>=1) {?>   Showing Ads <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;    </td>
    <td colspan="6" align="right" ><?php echo $paging->page($total,$perpagesize,"","ppc-view-public-ads.php?adtype=$adtype&device=$device&status=$st"); ?></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0"  class="datatable">
<tr height="30" class="headrow">
<td width="31%" ><strong>Name</strong></td>
<td width="20%"><strong>Type</strong></td>
<td width="11%"><strong>Status</strong></td>
<td width="16%"><strong>Target Device</strong></td>
<td width="22%" colspan="2"><strong>Action</strong></td>
</tr>


<?php

//echo "select title,link,summary,status,id,adtype from ppc_public_service_ads where id <> 0 $sqladdstring order by createtime desc";
$i=0;
while($row=mysql_fetch_row($result))
{
	if($row[8]==1)
	{
		$image='wap.png';
	}
	else
	{
		$image='pc.png';
	}
?>
<tr <?php if($i%2==1) { ?>class="specialrow" <?php } ?>>
<td>
<span onmouseover="showad(<?php echo $row[4]; ?>)" onmouseout="hidead(<?php echo $row[4]; ?>)"><?php echo $row[9]; ?></span>
<div  id="ad<?php echo $row[4]; ?>" class="layerbox" >
<div class="adbox">
	<?php
	$catalog_width=0;
	$catalog_height=0;
	$banner_width=0;
	$banner_height=0;
	 if($row[5]==0) 
	 { 
	 ?><a href="<?php echo $row[1]; ?>"><?php echo $row[0]; ?></a><br><?php echo $row[2]; ?><br><?php echo $row[6]; ?>
	 <?php
	  } 
	  else  if($row[5]==2) 
	  {


		$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row[7]' ");
		$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row[7]' "); 
		
		  if($row[10]=="swf")
		   {
		
		?>
		
		<table   border="0" cellpadding="5" cellspacing="0"  >
		<td width="<?php echo $catalog_width; ?>" height="<?php echo $catalog_height; ?>" align="center" valign="top"><a href="<?php echo $row[1]; ?>">
		
		<script type="text/javascript">

		  var flashvars = {};
		  var params = {};
		  var attributes = {};
		  var i=1;
		  
		  flashvars.clickTag = "";
		  
          flashvars.clickTAG = "";
		  flashvars.clickTARGET = "_blank";
		  
		  
		  
		   params.wmode="transparent";
		 		
	      swfobject.embedSWF("<?php echo "./".$GLOBALS['service_banners_folder']."/$row[4]/$row[0]";?>", "myFlashDiv_<?php echo $row[4]; ?>", "<?php echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars,params,attributes);
</script>
		  <div id="myFlashDiv_<?php echo $row[4]; ?>"></div>
		
		
		
		
		
		
		</a></td>
		  <td align="left" valign="top"><a href="<?php echo $row[1]; ?>"><?php echo $row[6]; ?></a><br><?php echo $row[2]; ?></td>
		  </table>
		  
		
		
		
		
		  <?php 
		   }
		   else
		   {
		?>
		
		<table   border="0" cellpadding="5" cellspacing="0"  >
		<td width="<?php echo $catalog_width; ?>" height="<?php echo $catalog_height; ?>" align="center" valign="top"><a href="<?php echo $row[1]; ?>"><img src="./<?php echo $GLOBALS['service_banners_folder']; ?>/<?php echo $row[4]; ?>/<?php echo $row[0]; ?>" border="0" ></a></td>
		  <td align="left" valign="top"><a href="<?php echo $row[1]; ?>"><?php echo $row[6]; ?></a><br><?php echo $row[2]; ?></td>
		  </table>
		  
		  
		  
		  
		  <?php
		  }
	  }
	  else
	   {
	   $banner_width=$mysql->echo_one("select width from banner_dimension where id='$row[7]' ");
	   $banner_height=$mysql->echo_one("select height from banner_dimension where id='$row[7]' "); 
	  	   
	    if($row[10]=="swf")
		   {
   
		   ?>
		   <table  cellpadding="0" cellspacing="0"  >
	   <tr><td>
<script type="text/javascript">

		  var flashvars = {};
		  var params = {};
		  var attributes = {};
		  var i=1;
		  
		  flashvars.clickTag = "";
		  
          flashvars.clickTAG = "";
		  flashvars.clickTARGET = "_blank";
		  
		  
		  
		   params.wmode="transparent";
		 		
	      swfobject.embedSWF("<?php echo "./".$GLOBALS['service_banners_folder']."/$row[4]/$row[2]";?>", "myFlashDiv_<?php echo $row[4]; ?>", "<?php echo $banner_width; ?>", "<?php echo $banner_height; ?>", "9.0.0", "",flashvars,params,attributes);
</script>
		  <div id="myFlashDiv_<?php echo $row[4]; ?>"></div>

</td></tr>
	   </table>

		  <?php 
		   }
		   else
		   {
		
	   
	   ?><table  cellpadding="0" cellspacing="0"  >
	   <tr><td ><a href="<?php echo $row[1] ;?>"><img src="<?php echo "./".$GLOBALS['service_banners_folder']."/$row[4]/$row[2]";?>"  border="0" ></a></td></tr>
	   </table><?php 
	   
	   }
	   }
?>      
</div>

</div>
</td>
<td style="white-space:nowrap"><?php
if($row[5]=="0")
echo "Text";
elseif($row[5]=="1")
{
if($row[10]=="swf")
echo "Flash Banner"." ($banner_width x $banner_height)";
else
echo "Banner"." ($banner_width x $banner_height)";

}
else
{
if($row[10]=="swf")
echo "Flash Catalog"." ($catalog_width x $catalog_height)";
else
echo "Catalog"." ($catalog_width x $catalog_height)";
}
?></td>
<td><?php 
if($row[3]=="0") { ?>Inactive<?php } else { ?> 
        Active
	  
<?php } ?></td>
<td><img src="images/<?php echo $image; ?>"  width="20" height="20" border="0"></td>
<td colspan="2"><a href="ppc-edit-service-ad.php?id=<?php echo $row[4];?>&adtype=<?php echo $row[5]; ?>&wap=<?php echo $row[8] ?>&url1=<?php echo urlencode($url) ?>">Edit</a>&nbsp;|&nbsp;<a href="ppc-delete-service-ad.php?id=<?php echo $row[4]; ?>&wap=<?php echo $row[8] ?>&url=<?php echo urlencode($url) ?>" onclick="return promptuser()">Delete</a>&nbsp;|&nbsp;<?php if($row[3]==1) echo '<a href="ppc-change-service-ad-status.php?action=block&id='.$row[4].'&wap='. $row[8].'&url='.urlencode($url).'">Block</a>';  else echo '<a href="ppc-change-service-ad-status.php?action=activate&id='.$row[4].'&wap='.$row[8].'&url='.urlencode($url).'">Activate</a>';?> </td>
</tr>
<?php 
$i++;
 }
?>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">

<tr> 

 <td colspan="2"><?php if($total>=1) {?>   Showing Ads <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;    </td>
    <td colspan="4" align="right" ><?php echo $paging->page($total,$perpagesize,"","ppc-view-public-ads.php?adtype=$adtype&device=$device&status=$st "); ?></td>
</tr>
</table>
<?php 
 }
else
{
		  echo " No records found. ";

}?> 
  </form><?php  
include("admin.footer.inc.php"); ?>