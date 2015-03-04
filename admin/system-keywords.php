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
$status_val=trim($_GET['stat']);
phpSafe($status_val);
if(!isset($_GET['stat']))
$status_val=1;
else
$status_val=$status_val;
$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
//echo "select * from system_keywords where status=$status_val order by keyword ASC ";
$result=mysql_query("select * from system_keywords  where status=$status_val order by keyword ASC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
if(mysql_num_rows($result)==0 && $pageno>1)
	{
		$pageno--;
		$result=mysql_query("select  * from system_keywords  where status=$status_val order by keyword ASC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

	}
$total=$mysql->echo_one("select count(*) from system_keywords  where status=$status_val");

 include("admin.header.inc.php"); 
?>
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
		var answer = confirm ("You are about to delete this keyword. It will be deleted from the ads using this keyword as well.")
		if (answer)
			return true;
		else
			return false;
		}
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/keywords.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">System Keywords</td>
  </tr>
</table>
<br />
<form name="form" method="get" action="system-keywords.php">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
    <td  >Status
      <select name="stat">
        <option value="1" <?php if($status_val==1) echo "selected"; ?>>Active</option>
        <option value="-1" <?php if($status_val==-1) echo "selected"; ?>>Pending</option>
        <option value="0" <?php if($status_val==0) echo "selected"; ?>>Blocked</option>
        </select>    <input type="submit" name="submit" value="Submit"></td>
    </tr>
  </table>
</form>
  <?php
if($total>0)
{
	?>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">


  <tr>
    <td colspan="2" ><?php if($total>=1) {?>
      Showing keywords <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
        </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
      <?php } ?>
      &nbsp;&nbsp;<br />
      <br/>    </td>
    <td width="50%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","system-keywords.php?stat=$status_val"); ?></td>
  </tr>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
  
   <tr   class="headrow">
    <td width="25%"><strong>Keyword</strong></td>
    <td width="25%"><strong>Create time</strong></td>
    <td width="10%"><strong>Rating</strong></td>
    <td width="12%"><strong>Status</strong></td>
    <td width="28%"><strong>Action</strong></td>
  </tr>
  <?php
  $i=0;
  $url=urlencode($_SERVER['REQUEST_URI']);
while($ans=mysql_fetch_row($result))
{ ?>
  <tr  <?php if($i%2==1) { ?>class="specialrow" <?php } ?>>
    <td><?php echo $ans[1]; ?> </td>
    <td><?php echo datetimeformat($ans[2]); ?></td>
    <td><?php echo numberformat($ans[3],0); ?></td>
	<td><?php  
	
	if($ans[4]==1)
	{
	echo "Active";
	$value="<a href=\"edit-keyword.php?id=$ans[0]&url=$url\">Edit</a> | <a href=\"change-keyword-status.php?id=$ans[0]&action=block&url=$url\">Block</a> | <a href=\"delete-keywords.php?id=$ans[0]&url=$url\" onclick=\"return promptuser()\">Delete</a>";
	}
	elseif($ans[4]==-1) 
	{
	echo "Pending";
	$value="<a href=\"edit-keyword.php?id=$ans[0]&url=$url\">Edit</a> | <a href=\"change-keyword-status.php?id=$ans[0]&action=activate&url=$url\">Activate</a> |  <a href=\"change-keyword-status.php?id=$ans[0]&action=block&url=$url\">Block</a> | <a href=\"delete-keywords.php?id=$ans[0]&url=$url\" onclick=\"return promptuser()\">Delete</a>";
	}
	else
	{
	echo "Blocked";
		$value="<a href=\"edit-keyword.php?id=$ans[0]&url=$url\">Edit</a> | <a href=\"change-keyword-status.php?id=$ans[0]&action=activate&url=$url\">Activate</a> | <a href=\"delete-keywords.php?id=$ans[0]&url=$url\" onclick=\"return promptuser()\">Delete</a>";
	}
	
	
	
	
	
	?></td>
	<td><?php if($ans[1]!=$GLOBALS['keywords_default'])
	{
		echo $value; }
	else
	{
		echo "N.A";
	}
	?> </td>
  </tr>
  <?php	
  $i++;
}
      ?>
	</table>
	<br />
	<span class="note"><strong>Keyword rating :</strong> Keyword rating is the percentage of impressions of a particular  keyword  in  total impressions (for past 30 days). 
	</span><br />

<br />

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" ><?php if($total>=1) {?>
      Showing keywords <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
        </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
      <?php } ?>
       </td>
    <td width="50%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","system-keywords.php?stat=$status_val"); ?></td>
  </tr>
</table>
<?php
}
else
{
	echo"<br>No Records Found<br><br>";
}


include("admin.footer.inc.php");	
?>