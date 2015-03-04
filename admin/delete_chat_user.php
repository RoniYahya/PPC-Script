<?php
include("config.inc.php");

if(!isset($_COOKIE['inout_admin']))
{
header("Location:index.php");
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
	{
	header("Location:index.php");
	}


?><?php include("admin.header.inc.php"); ?>

	<?php

$id=$_GET['id'];



	mysql_query("delete from `nesote_chat_public_user` where id=$id");

?>
<span class="inserted"><br><?php echo "You have successfully deleted the chat users";?></span>
	<strong><a href="manage_chat_users.php">Manage Existing Users</a></strong>





<?php include("admin.footer.inc.php"); ?>
