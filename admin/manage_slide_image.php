<?php 



/*--------------------------------------------------+

|													 |

| Copyright ? 2006 http://www.inoutscripts.com/      |

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
	$url=$_SERVER['REQUEST_URI'];
	//echo $url;
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 10;

//filtering status 


$status_value="";

if(isset($_REQUEST['status']))
{

if($_REQUEST['status']==2)
$status_value=2;
if($_REQUEST['status']==1)
$status_value=1;

if($_REQUEST['status']==5)
$status_value=5;
}

$str_status="";
if($status_value==1)
{
 $str_status="where status=1";
}

else if($status_value==2)
{
 $str_status="where status=0";

}

else
{

 $str_status=" ";

}


//echo "select id,image_name,date,status from adserver_public_images $str_status"; exit;
$result=mysql_query("select id,image_name,date,status from adserver_public_images $str_status");

$total=mysql_num_rows($result);

if(mysql_num_rows($result)==0 && $pageno>1)
	{
		$pageno--;
		//echo "select a.id,a.cid,a.ad_logos_name,a.status,b.uid  from ad_logos_details a left join  ppc_users b  on b.uid=a.cid";exit;
		$result=mysql_query("select id,image_name,name,date,status from adserver_public_images  LIMIT".(($pageno-1)*$perpagesize).", ".$perpagesize);

	}




//echo $total;exit;
?><?php include("admin.header.inc.php"); ?>
<style type="text/css">
<!--
.style6 {font-size: 20px}

.style1 {color: white;
	font-weight: bold;}
-->
</style>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("Do you really want to delete this Slide Image.")
		if (answer)
			return true;
		else
			return false;
		}
		</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/slideimage.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Slide Images</td>
  </tr>
</table>

<br>

<form name="ads" action="manage_slide_image.php" method="get">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td   height="34" colspan=""> Status <select name="status" id="status" >
<option value="5" <?php 
			  				  if($status_value=="5")echo "selected";			  
			  ?>>All</option>
<option value="1"  <?php 
			  	if($status_value=="1")echo "selected";			  
			  ?>>Active</option>
<option value="2" <?php 
			  				  if($status_value=="2")echo "selected";			  
			  ?>>Blocked</option>

			  

			  
</select>

       

</select> <input type="submit" name="Submit" value="Submit"></td>
  </tr>
  </table> 
</form> 
<?php
if($total>0)
{  
?>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td colspan="2" ><?php if($total>=1) {?>   Showing Logo Details <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br /><br/>    </td>
    <td width="51%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","adserver_public_images.php?status=$status_value"); ?></td>
  </tr>
    </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable">
  
  <tr class="headrow">
    <td width="13%" height="34"><strong>Slide Image</strong></td>
<!--    <td width="15%"><strong>Added on</strong></td>
-->    <td width="23%"><strong>Status</strong></td>

	<td width="23%"><strong>Action</strong></td> <?php
//echo $result."hi";exit;
//$row=mysql_fetch_row($result);
//print_r($row);exit;

	while($row=mysql_fetch_row($result))
{ 
?>

  <tr>
    <td height="28" ><img src="../ad_logos/<?php echo  $row[1];?>" width="100" height="55"></a></td>
	
    
   <!-- <td><?php echo  $row[2];?></td>-->
    <td > <?php 
	$str="";
	if($row[3]==1)
	{
	echo "Active";
	
	//$str.='<a href="member-login.php?type=2&id='.$row[0].'">Login</a>&nbsp;|&nbsp;';  
	$str.='<a href="change-slide_image_status.php?action=block&id='.$row[0].'">Block</a>&nbsp;';  
	$str.= '&nbsp;|&nbsp;<a href="change-slide_image_status.php?id='.$row[0].'&name='.$row[1].'" onclick="return promptuser()">Delete</a>';
	}
   
  
	if($row[3]==0)
	{
	echo "Blocked";
	
	$str.='<a href="change-slide_image_status.php?action=activate&id='.$row[0].'">Activate</a>&nbsp;';  
	$str.= '&nbsp;|&nbsp;<a href="change-slide_image_status.php?id='.$row[0].'&name='.$row[1].'" onclick="return promptuser()">Delete</a>';
	
	}
	
	 ?></td>
	 

	
	
	
	<td><?php echo $str;?>    </td>

  </tr>


<?php

}

?>
</table>
	<?php if($total>=1) {?>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" >  Showing Logo Details <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
			<span class="inserted">
			<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
		</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span> </td>
		<td width="51%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","manage_logos.php?status=$status_value"); ?></td>
	  </tr>
	</table>
	<?php } ?>  
<?php
}
else
{
	echo"<br>No Records Found<br><br>";
}
 include("admin.footer.inc.php"); ?>