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
$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 10;
$result=mysql_query("select * from messages order by id desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
 $total=$mysql->echo_one("select count(*) from messages");
?><?php include("admin.header.inc.php"); ?>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("You are about to delete this message. It won't be available later.")
		if (answer)
			return true;
		else
			return false;
		}
</script>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/messages.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Messages</td>
  </tr>
</table><br />

  
   <?php if($total>0)
	{  ?>

<table width="100%" border="0"  border="0" cellspacing="0" cellpadding="0" >

    <tr>
	<td colspan="2" ><?php if($total>=1) {?>   Showing messages <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span> of <span class="inserted"><?php echo $total; ?></span> 
    <?php } ?>     
	</td>
	<td align="right" colspan="2" width="50%">
    <?php echo $paging->page($total,$perpagesize,"","manage-message.php"); ?>
    </td>
  	</tr> 
</table>
  <?php		} 	?>
  

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="datatable">
     <tr class="headrow">
	 <td width="5%"><strong>No.</strong></td>
      <td width="44%"><strong>Message</strong></td>
      <td width="9%"><strong>Target</strong></td>
       <td width="12%"><strong>Expiry</strong></td>
       <td width="8%"><strong>Status</strong></td>
        <td width="22%"><strong>Action</strong></td>
    </tr>
    
    
  
    
    
	<?php 
	
		
	if($total==0)
	{  ?>
	<tr><td colspan="6">No Record Found</td></tr>
	<?php	
	}
	else
	{ 
 
	  if($_REQUEST['page']=="")
	  {
		$i=1;
	  }
	  else
	  {
		  $i=($pageno-1)*$perpagesize+1;
	  }
  
		while($ans=mysql_fetch_row($result))
		{
			?>
			<tr <?php if($i%2==1) { ?>class="specialrow" <?php } ?>><td><?php echo $i; ?></td>
			<td><?php   $mes=stripslashes($ans[1]); 
			//$asf=wordwrap($mes,50,"<br />\n",TRUE); echo $asf;
			echo $mes;
			?></td>
			<td><?php echo $ans[2]; ?></td>
			 <td><?php echo dateFormat($ans[3]-1) ; ?></td>
			 <td><?php 
			 if(time()>$ans[3])
			 {
			 	echo "Expired"; 
			 }
			 else if($ans[4]==-1)
			 {
			 
			 echo "Pending"; 
			 }
			 elseif($ans[4]==0)
			 {
			 echo "Blocked";
			 }
			 else
			 {
			 echo "Active";
			 }?></td>
			<td>
			 <a href="edit-message.php?id=<?php echo $ans[0]; ?>">Edit</a> | <a href="delete-message.php?id=<?php echo $ans[0] ?>" onclick="return promptuser()">Delete</a> | 
			 <?php if($ans[4]==-1){?><a href="change-message-status.php?id=<?php echo $ans[0] ?>&action=activate">Activate</a><?php } 
			elseif($ans[4]==1){?><a href="change-message-status.php?id=<?php echo $ans[0] ?>&action=block">Block</a><?php }
			else{ ?><a href="change-message-status.php?id=<?php echo $ans[0] ?>&action=activate">Activate</a><?php } ?>			 </td>
			</tr>
		<?php  $i++;
		 }
	 }?>
</table>
   <?php if($total>0)
	{  
	?>
	<table width="100%" border="0"  cellspacing="0" cellpadding="0" >
	
	
	<tr>
	<td colspan="2" ><?php if($total>=1) {?>   Showing messages <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;  </td><td width="50%" colspan="2" align="right">
   
    <?php echo $paging->page($total,$perpagesize,"","manage-message.php"); ?>
    </td>
  </tr>
  </table>
	 <?php	
	}
	?> 
	
    <?php
   include_once("admin.footer.inc.php");
?>