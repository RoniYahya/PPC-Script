<?php 

/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?>
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

include_once("admin.header.inc.php");
$banner=$_FILES['banner']['name'];
$id=$_POST['id'];
if($banner=="")
{
	?>
	<span class="already"><br>
	<?php echo "Please check whether you filled all mandatory fields !";?></span>
	<a href="javascript:history.back(-1);">Go Back And Modify</a>           <br> <br>
	<?php
}
else
{
	$exten=strtolower(substr($_FILES['banner']['name'],-4));
	if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png" )
	{
		?>
			<span class="already"><br><?php echo "You can upload JPEG/PNG/GIF files only !";?> </span>
			<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br> <br>
		<?php
	}
	else
	{
		$filename=$mysql->echo_one("select filename from affiliate_banners where id='$id'");
		if(copy($_FILES['banner']['tmp_name'],"affiliate-banners/$id/".$_FILES['banner']['name']))
		{
			$size=getimagesize("affiliate-banners/$id/".$_FILES['banner']['name']);
			mysql_query("update `affiliate_banners` set filename='$banner', height='$size[0]', width='$size[1]' where id='$id'");
			
			unlink("affiliate-banners/".$id."/$filename");
		 	echo "<span class=\"inserted\"><br>Referral banner has been modified !</span>
			<a href=\"affiliate-banners.php\">Manage Referral Banners</a><br><br>";
		}
		else
		{
			
		 	echo "<span class=\"inserted\"><br>Failed to update affiliate banner!</span>
			<a href=\"affiliate-banners.php\">Manage Affiliate Banners</a><br><br>";
		}	

	}
}
 include("admin.footer.inc.php"); ?>
