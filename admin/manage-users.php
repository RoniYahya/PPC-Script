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
	$url=$_SERVER['REQUEST_URI'];
	
	
//	echo "select id,username,status from nesote_inoutscripts_users order by id DESC";
//	exit;
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
$result=mysql_query("select id,username,status from nesote_inoutscripts_users order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
if(mysql_num_rows($result)==0 && $pageno>1)
	{
		$pageno--;
		$result=mysql_query("select uid,username,status from nesote_inoutscripts_users order by uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

	}
$total=$mysql->echo_one("select count(*) from nesote_inoutscripts_users ");
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
		var answer = confirm ("Do you really want to delete this single sign on account.")
		if (answer)
			return true;
		else
			return false;
		}
		</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/users.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Users</td>
  </tr>
</table><br />

<?php
if($total>0)
{
	
?>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td colspan="2" ><?php if($total>=1) {?> Showing Users <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br /><br/>    </td>
    <td width="50%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","manage-users.php"); ?></td>
  </tr>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
  
  <tr class="headrow">
    <td width="17%" height="34"><strong>Username</strong></td>
	<td width="16%" height="34">
    <?php if(($single_account_mode==1)&&($portal_system==1)) {  ?><strong>Portal Status</strong>    <?php } ?>	 </td>
    <td width="18%"><strong>Advertiser Account</strong></td>
    <td width="17%"><strong>Publisher Account</strong></td>
    <td colspan="8"><strong>Action</strong></td>
  </tr>
<?php

$i=1;

while($row=mysql_fetch_row($result))
{
?>
  <tr <?php if($i%2==1) {?>class="specialrow" <?php }?>>
    <td height="28" style="border-bottom: 1px solid #b7b5b3;"><a href="view-user-profile.php?id=<?php echo  $row[0];?>"><?php echo  $row[1];?></a></td>
    <td style="border-bottom: 1px solid #b7b5b3;">
	<?php if(($single_account_mode==1)&&($portal_system==1)) 
	{ 
    if($row[2]==1){ echo "Active";}   elseif($row[2]==0){ echo "Blocked"; } elseif($row[2]==-2) echo "Email not verified";   else {echo "Pending" ; }
	 } ?>	</td>
    <td style="border-bottom: 1px solid #b7b5b3;"><?php 
 $advst = $mysql->echo_one(" select  status from ppc_users where common_account_id='$row[0]'");
 $advid = $mysql->echo_one(" select  uid from ppc_users where common_account_id='$row[0]'");
	if($advst=="1")
		echo "Active";
	elseif($advst=="0")
	echo "Blocked";
	elseif($advst==-2)
	echo "Email not verified";
	else
	echo "Pending";
	?></td>
    <td style="border-bottom: 1px solid #b7b5b3;"><?php 
	$pubst = $mysql->echo_one("select status from ppc_publishers where common_account_id='$row[0]'");
	$pubid = $mysql->echo_one(" select  uid from ppc_publishers where common_account_id='$row[0]'");
	if($pubst=="1")
		echo "Active";
	elseif($pubst=="0")
	echo "Blocked";
	elseif($pubst=="-2")
	echo "Email not verified";
	else
	
		echo "Pending";
	?>		</td>
			<td width="32%" style="border-bottom: 1px solid #b7b5b3;" align="left">
			<?php
					 echo '<a href="confirm-user-status.php?id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;|&nbsp;';  
					 echo '<a href="ppc-send-mail.php?type=2&id='.$row[0].'&url='.urlencode($url).'">Mail</a>&nbsp;|&nbsp;';
					 echo '<a href="delete-user-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>&nbsp;';
					 
					 if($pubst=="1" || $advst==1)
					 echo '|&nbsp;<a href="member-login.php?type=3&id='.$row[0].'">Login</a>'; 
	
		/*	 if(($single_account_mode==1)&&($portal_system==1)) 
			 {
			 	
			 	if($row[2]==0)		
				{
					 echo 'N.A';
				}
				else
				{
			 		if($advst==1)
					{ 
						if($pubst==1)
						{ 
						 echo '<a href="member-login.php?type=3&id='.$row[0].'">Login</a>&nbsp;|&nbsp;'; 
						 echo '<a href="confirm-user-status.php?action=block&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;|&nbsp;';  
						 echo '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.urlencode($url).'">Mail</a>&nbsp;';
						}
						else
						{ 
							if($pubst==0)
							{
								?>
								<a href="confirm-user-status.php?action=activate&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a> 
								<?php 
							}
							else
							{
							?>
								<a href="confirm-user-status.php?action=pending&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a> 
							<?php	
								
							}
						}	
					} 
					elseif($advst==0)
					{
						if($pubst==0)
						{ 
							echo '<a href="confirm-user-status.php?action=active&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;|&nbsp;';  
							echo '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.urlencode($url).'">Mail</a>&nbsp; ';	
						}
						elseif($pubst==1)
						{ 
						?>
						<a href="confirm-user-status.php?action=activate&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a>			
						<?php			
						}
						else
						{ 
						?>
						<a href="confirm-user-status.php?action=pending&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a>				
				<?php	}
					}
					 elseif($advst==-1)
				 	{
						if($pubst==0)
						{ ?>
						<a href="confirm-user-status.php?action=pending&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a>				
						 <?php	
						}
				 					 if($pubst==1)   //advstatus pending,publisher active
 	{ 
 			
echo '<a href="confirm-user-status.php?action=pending&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;';					
 	}
  else  //advstatus pending,publisher -1
 	{ 		
echo '<a href="confirm-user-status.php?action=approve&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>';					
 
 	}
					}
					else
					{ 
						 echo '<a href="confirm-user-status.php?action=approve&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;';  
						
				 	}
				}
			}
			else
		 	{
				if($advst==1)
				{ 
					if($pubst==1)
					{
					 echo '<a href="member-login.php?type=3&id='.$row[0].'">Login</a>&nbsp;|&nbsp;'; 
					 echo '<a href="confirm-user-status.php?action=block&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;|&nbsp;';  
					 echo '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.urlencode($url).'">Mail</a>&nbsp;|&nbsp;';
					 echo '<a href="delete-user-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';
					
					}
					else
					{ 
						if($pubst==0)
						{
							?>
						<a href="confirm-user-status.php?action=activate&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a>&nbsp;|&nbsp;

					 <a href="delete-user-action.php?id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>" onclick="return promptuser()">Delete</a>
						<?php 
						}
	
						else
						{?>
							<a href="confirm-user-status.php?action=pending&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a>&nbsp;|&nbsp;
							<a href="delete-user-action.php?id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>" onclick="return promptuser()">Delete</a>
						<?php	
						}
					}
				} 
				elseif($advst==0)
				{
					if($pubst==0)
					{ 
			
						echo '<a href="confirm-user-status.php?action=active&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;|&nbsp;';  
						echo '<a href="ppc-send-mail.php?type=0&id='.$row[0].'&url='.urlencode($url).'">Mail</a>&nbsp; ';	
						echo '<a href="delete-user-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';
					}
					elseif($pubst==1)
					{
								 ?>
						<a href="confirm-user-status.php?action=activate&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a>&nbsp;|&nbsp;			
						<a href="delete-user-action.php?id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>" onclick="return promptuser()">Delete</a>
								 <?php			
					}
					else
					{?>
			
						<a href="confirm-user-status.php?action=pending&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a>&nbsp;|&nbsp;			
			<a href="delete-user-action.php?id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>" onclick="return promptuser()">Delete</a>
			<?php	}
				}
				 elseif($advst==-1)
				 {
					if($pubst==0)
					{ ?>
					<a href="confirm-user-status.php?action=pending&id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>">Change Status</a>&nbsp;|&nbsp;					
					 <a href="delete-user-action.php?id=<?php echo $row[0]; ?>&url=<?php echo urlencode($url); ?>" onclick="return promptuser()">Delete</a>
					<?php	
					}
				 if($pubst==1)   //advstatus pending,publisher active
 	{ 
 			
echo '<a href="confirm-user-status.php?&action=pending&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;|&nbsp;';	
echo '<a href="delete-user-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';				
 	}
  else  //advstatus pending,publisher -1
 	{ 		
echo '<a href="confirm-user-status.php?category=approve&action=approve&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;|&nbsp;';
echo '<a href="delete-user-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';					
 
 	}
				 }
				else
				{  
					 echo '<a href="confirm-user-status.php?action=approve&id='.$row[0].'&url='.urlencode($url).'">Change Status</a>&nbsp;|&nbsp;';  
					echo '<a href="delete-user-action.php?id='.$row[0].'&url='.urlencode($url).'" onclick="return promptuser()">Delete</a>';
					
				 }
			} */ ?>		</td>
  </tr>


<?php
$i++;
}	


?>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0"  >

<tr>
    <td colspan="2" ><?php if($total>=1) {?><br>   
      Showing Users <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;    </td>
    <td width="50%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","manage-users.php"); ?></td>
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