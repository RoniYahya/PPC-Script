<?php 

/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/




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

include_once("admin.header.inc.php");
$banner=$_FILES['banner']['name'];
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
		$level=$mysql->echo_one("select level from affiliate_banners order by level desc limit 0,1");
		if($level=="")
			$level=1;
		else
			$level+=1;	
		mysql_query("INSERT INTO `affiliate_banners` ( `filename`, `level` ) VALUES ( '$banner','$level');");
		$id=$mysql->echo_one("select id from affiliate_banners order by id desc");
		mkdir("affiliate-banners/$id/");
		if(copy($_FILES['banner']['tmp_name'],"affiliate-banners/$id/".$_FILES['banner']['name']))
		{
			$size=getimagesize("affiliate-banners/$id/".$_FILES['banner']['name']);
			mysql_query("update `affiliate_banners` set height='$size[0]', width='$size[1]' where id='$id'");
			echo "<span class=\"inserted\"><br>New affiliate banner has been created successfully !</span>
			<a href=\"affiliate-banners.php\">Manage Affiliate Banners</a><br><br>";
		}
		else
		{
			rmdir("affiliate-banners/".$id);
			mysql_query("delete from `affiliate_banners` where id='$id'");
			echo "<span class=\"already\"><br>Failed to create new affiliate banner !</span>
			<a href=\"affiliate-banners.php\">Manage Affiliate Banners</a><br><br>";

		}
	}
}
 include("admin.footer.inc.php"); ?>
