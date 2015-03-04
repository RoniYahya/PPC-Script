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
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;

if(isset($_REQUEST['s_keyword']))
{
$search_keywords=$_REQUEST['s_keyword'];
phpSafe($search_keywords);
$search_key=$_REQUEST['s_type'];
$temp="$search_keywords%";
$result=mysql_query("select uid,username,email,status from ppc_publishers where $search_key like '".$temp."'");
$total=$mysql->echo_one("select count(*) from ppc_publishers where $search_key like '".$temp."'");
}
else
{
$search_keywords="";
$search_key="";
$total=0;
}

?><?php include("admin.header.inc.php"); ?>
<script language="javascript">
function check_value()
				{
				
				if(document.getElementById('s_keyword').value=="")
					{
					//refresh();
					alert("Search keyword should not be blank");
					//document.form1.ad_title.focus();
					return false;
					}
				
				}
</script>
	<script type="text/javascript" src="../js/livesearch.js"></script>
<?php 
//print_r($_POST);
//$search_keywords=$_REQUEST['search_keyword'];
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/publishers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Search Publishers</td>
  </tr>
</table>
<br />

<table   width="100%" cellpadding="0" cellspacing="0">
<form action="search-publisher.php" method="get" name="ppc-search" enctype="multipart/form-data" onSubmit="return check_value()">
<tr><td >
<strong>  Search for:</strong>
  </td><td>
  <input name="s_keyword"  id="s_keyword" type="text" value="<?php echo $search_keywords;?>"  onKeyUp="showPublisher(this.value)"  autocomplete="off">
  </td><td>
<strong>Search in :</strong>
  <select name="s_type" id="s_type">
    <option value="username">Name</option>
    <option value="email">Email</option>
  </select>
  <input name="Submit" type="submit" id="Submit" value="Search !">
  </td></tr>
  </form>
  <tr>
  <td>&nbsp;</td>
     <td><div id="livesearch"  style="z-index:1000;position:absolute;_position:absolute;background-color:#CCCCCC;height: 200px;width:200px;display:none;overflow: scroll;" ></div>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
  <?php if($total>0) {?> 
  <table  width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td colspan="2" >  Showing Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>   </td>
    <td width="51%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","search-publisher.php?s_keyword=$search_keywords&s_type=$search_key"); ?></td>
  </tr>
  </table>
   <?php } ?>

<?php 
if($total>0) 
{?>  
<table width="100%" cellpadding="0" cellspacing="0">
  <tr bgcolor="#b7b5b3" class="style1" height="28">
    <td width="138" height="22"><span style="font-weight: bold;padding-left:3px;">Publisher  name</span></td>
    <td width="239"><span style="font-weight: bold">Email</span></td>
    <td width="171"><span style="font-weight: bold">View profile </span></td>
  </tr>
  <?php
  $i=1;
    while($row=mysql_fetch_row($result))
  	{?><tr <?php if($row[3]==0) echo 'bgcolor="#F8B9AF"'; elseif($row[3]==-1||$row[3]==-2) echo 'bgcolor="#ffff99"'; elseif($i%2==1) echo 'bgcolor="#ededed"';?> height="28">
	<td width="138" height="22" style="border-bottom: 1px solid #b7b5b3;padding-left:3px;"><span><?php echo $row[1];?></span></td>
    <td width="239" style="border-bottom: 1px solid #b7b5b3;"><span><?php echo $row[2];?></span></td>
    <td width="171" style="border-bottom: 1px solid #b7b5b3;"><span><a href="view_profile_publishers.php?id=<?php echo $row[0];?>">View profile</a></span></td>
	</tr>
	<?php $i++; }?>
</table>
<?php 
}
else if(isset($_REQUEST['s_keyword']))
{
echo "<table align=center width=\"100%\"><tr><td>No record found .</td></tr></table>";
}
?>
  <?php if($total>0) {?> 
  <table  width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td colspan="2" >  Showing Publishers <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>   </td>
    <td width="51%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","search-publisher.php?s_keyword=$search_keywords&s_type=$search_key"); ?></td>
  </tr>
<tr>
<td colspan="4"><br />

<span class="bold">Note:</span> <span class="info">Blocked publishers are in <span style="background-color:#F8B9AF">this color</span>; pending publishers are in <span style="background-color:#ffff99">this color</span> and others are active publishers. </span>


</td>
</tr>
  </table>
   <?php } ?>



<br />
<?php include("admin.footer.inc.php"); ?>