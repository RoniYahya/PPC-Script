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


?><?php include("admin.header.inc.php");

$uid=$_GET['id'];
phpsafe($uid);
$confirm=mysql_query("select username,status from nesote_inoutscripts_users where id=$uid");
$confirm1=mysql_fetch_row($confirm);
$name=$confirm1[0];
$portst=$confirm1[1];
$action=$_GET['action'];
$url=$_GET['url'];
//echo $url;

$pubid = $mysql->echo_one(" select  uid from ppc_publishers where common_account_id='$uid'");
$advid = $mysql->echo_one(" select  uid from ppc_users where common_account_id='$uid'");
$pubst = $mysql->echo_one(" select  status from ppc_publishers where common_account_id='$uid'");
$advst = $mysql->echo_one(" select  status from ppc_users where common_account_id='$uid'");
if(!isset($_GET['action']))
{
	if($advst==1)
	{
		if($pubst==1)
		{
			$action="block";
		}
		else
		{
			if($pubst==0)
			{
				$action="activate";
			}
			else
			{
				$action="pending";
			}
		}

	}
	elseif($advst==0)
	{
		if($pubst==0)
		{
			$action="active";
		}
		elseif($pubst==1)
		{
			$action="activate";
		}

		else
		{
			$action="pending";
		}
	}
	elseif($advst==-1)
	{
		if($pubst==0)
		{
			$action="pending";
		}
		if($pubst==1)
		{
			$action="pending";
		}
		else
		{
			$action="approve";
		}
	}
	else
	{
		$action="approve";
	}
		
}



if($action=="block")
{
	if($name=="advertiser" && $script_mode=="demo")
	{
		echo "<br>This feature is disabled in demo version<br><br>";
		include("admin.footer.inc.php");
		exit(0);
	}
}
//echo "select  id from ppc_publishers where common_account_id='$uid'";






//if($action=="block")
//	$type=7;
//else if($action=="activate")
//	$type=8;
//else if($action=="reject")
//	$type=18;
//else if($action=="approve")
//	$type=17;
//
//
//$sub=$mysql->echo_one("select email_subject from email_templates where id='$type'");
//$sub=str_replace("{USERNAME}",$name,$sub);
//$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
//$body=$mysql->echo_one("select email_body from email_templates where id='$type'");
//$body=str_replace("{USERNAME}",$name,$body);
//$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
//$body=str_replace("{ADV_LOGIN_PATH}",$server_dir."ppc-user-login.php",$body);

?>
<style type="text/css">
<!--
.style6 {
	font-size: 20px
}
-->
</style>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="50" colspan="4" align="left"><?php include "submenus/users.php"; ?>
		</td>
	</tr>
	<tr>
		<td colspan="4" scope="row" class="heading"><?php if($action=="block") { ?>Block
		User<?php } else if($action=="reject") { ?>Reject User<?php } else if($action=="approve") { ?>Approve
		User<?php } else if($action=="active") { ?>Activate User<?php } else { ?>Change
		User Status<?php } ?></td>
	</tr>
</table>

<form action="change-user-status.php" method="post"
	enctype="multipart/form-data" name="form1"><input type="hidden"
	name="url" value="<?php echo $url; ?>" />
<table width="100%" border="0" cellspacing="0" cellpadding="0">


	<tr>
		<td height="19" colspan="2"><span class="inserted">&nbsp; </span></td>
	</tr>
	<tr>
		<td height="27" width="211" align="left">Username</td>

		<td width="1100"><?php   echo $name;?></td>
	</tr>
	<tr>
		<td height="27" align="left">Advertiser Status</td>

		<td width="1100"><?php  if($advst==1) echo "Active";elseif($advst==0) echo "Blocked";elseif($advst==-2) echo "Email not verified";else echo "Pending";?></td>
	</tr>
	<tr>
		<td height="27" align="left">Publisher Status</td>

		<td width="1100"><?php  if($pubst==1) echo "Active";elseif($pubst==0) echo "Blocked";elseif($advst==-2) echo "Email not verified";else echo "Pending";?></td>
	</tr>
	<?php if(($single_account_mode==1)&&($portal_system==1)) {  ?>

	<tr>
		<td height="27" align="left">Portal Status</td>

		<td width="1100"><?php  if($portst==1) echo "Active";elseif($portst==0) echo "Blocked";elseif($advst==-2) echo "Email not verified";else echo "Pending";?></td>
	</tr>
	<?php } ?>
	<tr>
		<td height="20" align="left">Action</td>

		<td><?php if($action=="block") 
		{ ?> <strong><a
			href="ppc-change-user-status.php?action=block&id=<?php echo $advid; ?>&url=<?php echo urlencode($url); ?>">Block
		Advertiser</a></strong> | <strong><a
			href="ppc-change-publisher-status.php?action=block&id=<?php echo $pubid; ?>&url=<?php echo urlencode($url); ?>">Block
		Publisher</a></strong> | <strong><a
			href="change-user-status.php?action=block&id=<?php echo $uid; ?>&url=<?php echo urlencode($url); ?>">Block
		Both</a></strong> <?php }
		elseif($action=="approve")
		{
			?> <strong><a
			href="change-common-user-status.php?action=approve&category=advertiser&id=<?php echo $uid; ?>&url=<?php echo urlencode($url); ?>">Approve
		Advertiser Only</a></strong> | <strong><a
			href="change-common-user-status.php?id=<?php echo $uid; ?>&action=approve&category=publisher&url=<?php echo urlencode($url); ?>">Approve
		Publisher Only</a></strong> | <strong><a
			href="approve-user.php?action=approve&id=<?php echo $uid; ?>&url=<?php echo urlencode($url); ?>">Approve
		Both</a></strong> | <strong><a
			href="user-reject.php?id=<?php echo $uid; ?>&url=<?php echo urlencode($url); ?>">Reject
		Both</a></strong> <?php  } 

			
		elseif($action=="pending")
		{

			if($advst==1)
			{

				if(($pubst==-1) ||($pubst==-2))
				{ ?> <strong><a
			href="ppc-change-user-status.php?action=block&id=<?php echo $advid; ?>&url=<?php echo urlencode($url); ?>">Block
		Advertiser</a></strong> | <strong><a
			href="ppc-approve-publisher.php?id=<?php echo $pubid; ?>&url=<?php echo urlencode($url); ?>">Approve
		Publisher</a></strong> | <strong><a
			href="ppc-disapprove-publisher.php?id=<?php echo $pubid; ?>&url=<?php echo urlencode($url); ?>">Reject
		Publisher</a></strong> <?php 	}
			}
			else
			{
		  if($advst==0)
		  {

		  	if(($pubst==-1) ||($pubst==-2))
		  	{?> <strong><a
			href="ppc-change-user-status.php?action=activate&id=<?php echo $advid; ?>&url=<?php echo urlencode($url); ?>">Activate
		Advertiser</a></strong> | <strong><a
			href="ppc-approve-publisher.php?id=<?php echo $pubid; ?>&url=<?php echo urlencode($url); ?>">Approve
		Publisher</a></strong> | <strong><a
			href="ppc-disapprove-publisher.php?id=<?php echo $pubid; ?>&url=<?php echo urlencode($url); ?>">Reject
		Publisher</a></strong> <?php 	}
		  }

			}
			if($pubst==1)
			{

				if(($advst==-1) ||($advst==-2))
				{ ?> <strong><a
			href="ppc-change-publisher-status.php?action=block&id=<?php echo $pubid; ?>&url=<?php echo urlencode($url); ?>">Block
		Publisher</a></strong> | <strong><a
			href="ppc-change-user-status.php?action=approve&id=<?php echo $advid; ?>&url=<?php echo urlencode($url); ?>">Approve
		Advertiser</a></strong> | <strong><a
			href="ppc-change-user-status.php?id=<?php echo $advid; ?>&action=reject&url=<?php echo urlencode($url); ?>">Reject
		Advertiser</a></strong> <?php 	}
			}
			else
			{
		  if($pubst==0)
		  {

		  	if(($advst==-1) ||($advst==-2))
		  	{?> <strong><a
			href="ppc-change-publisher-status.php?id=<?php echo $pubid; ?>&action=activate&url=<?php echo urlencode($url);?>">Activate
		Publisher</a></strong> | <strong><a
			href="ppc-change-user-status.php?action=approve&id=<?php echo $advid; ?>&url=<?php echo urlencode($url); ?>">Approve
		Advertiser</a></strong> | <strong><a
			href="ppc-change-user-status.php?id=<?php echo $advid; ?>&action=reject&url=<?php echo urlencode($url); ?>">Reject
		Advertiser</a></strong> <?php 	}
		  }
			}




		}

		elseif($action=="active")
		{

			?> <strong><a
			href="ppc-change-user-status.php?action=activate&id=<?php echo $advid; ?>&url=<?php echo urlencode($url); ?>">Activate
		Advertiser</a></strong> | <strong><a
			href="ppc-change-publisher-status.php?id=<?php echo $pubid; ?>&action=activate&url=<?php echo urlencode($url); ?>">Activate
		Publisher</a></strong> | <strong><a
			href="change-user-status.php?id=<?php echo $uid; ?>&action=activate&url=<?php echo urlencode($url); ?>">Activate
		Both</a></strong> <?php
		}
		else
		{
			if($advst==0)
			{
				if($pubst==1)
				{?> <strong><a
			href="ppc-change-user-status.php?action=activate&id=<?php echo $advid; ?>&url=<?php echo urlencode($url); ?>">Activate
		Advertiser</a></strong> | <strong><a
			href="ppc-change-publisher-status.php?action=block&id=<?php echo $pubid; ?>&url=<?php echo urlencode($url); ?>">Block
		Publisher</a></strong> <?php 	}
			}

			else
			{

			 if($advst==1)
		  {
		  	if($pubst==0)
		  	{
		  		?> <strong><a
			href="ppc-change-user-status.php?action=block&id=<?php echo $advid; ?>&url=<?php echo urlencode($url); ?>">Block
		Advertiser</a></strong> | <strong><a
			href="ppc-change-publisher-status.php?id=<?php echo $pubid; ?>&action=activate&url=<?php echo urlencode($url); ?>">Activate
		Publisher</a></strong> <?php } } ?> <?php } 

		} ?></td>
	</tr>


	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
<input type="hidden" name="action" value="<?php echo $action;?>"> <input
	type="hidden" name="uid" value="<?php echo $uid;?>"></form>

		<?php include("admin.footer.inc.php");
		?>