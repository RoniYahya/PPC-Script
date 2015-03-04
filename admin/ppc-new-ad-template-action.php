<?php  

/*--------------------------------------------------+
|													 |
| Copyright ? 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?>
<?php

include("config.inc.php");
include("../extended-config.inc.php");  
includeClass("ImageResizer");




include("../swf-function-inc.php");

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

 
$size=$_POST['size'];

$banner=$_FILES['banner']['name'];


$name=trim($_POST['txt1']);


phpSafe($banner);

phpSafe($name);


$wap_status="wap_status=0";


$width=$mysql->echo_one("select width from ppc_ad_block where id=$size");
$height=$mysql->echo_one("select height from ppc_ad_block where id=$size");


$max_size=$mysql->echo_one("select max_size from ppc_ad_block where id=$size");



//echo "select file_size from banner_dimension where $wap_status and  id=$size"; exit;
$filesize=$mysql->echo_one("select file_size from banner_dimension where $wap_status and  id=$max_size");



$ba_var=$_FILES['banner']['tmp_name'];
$ban_size=$_FILES['banner']['size'];




$exten=strtolower(substr($banner,-4));


$size_new=getimagesize($ba_var);


$special_flag=0;


if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png")
{

	?>
		<span class="already"><br><?php echo "You can upload only JPEG,GIF,PNG files only !";?> </span>
		<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br> <br>
	<?php
	include("admin.footer.inc.php");
	exit(0);



}

if($size==""||$banner==""||$name=="")
{
	?>
	<span class="already"><br>
	<?php echo "Please check whether you filled all mandatory fields !";?></span>
	<a href="javascript:history.back(-1);">Go Back And Modify</a>           <br> <br>
	<?php
}
else
{
	
	

if(($ban_size/1024) > $filesize )
{
echo "<span class=\"already\"><br>Banner file size should be in between 1 KB and $filesize KB !</span><a href=\"javascript:history.back(-1);\">
		 Go Back And Modify</a><br><br>";
		
	include("admin.footer.inc.php");
	exit(0);

}
else if($special_flag==1)
{

echo "<span class=\"already\"><br>Image banner that you have uploaded is not within the size limit you selected !</span><a href=\"javascript:history.back(-1);\">
		 Go Back And Modify</a><br><br>";
		
	include("admin.footer.inc.php");
	exit(0);

}

else
{

	
	
mysql_query("INSERT INTO `ppc_ad_templates` (`id`,`banner_size`,`name`,`createtime`,`filename`) 
VALUES ('0', '$size','$name','".time()."', '$banner');");

$id=$mysql->echo_one("select id from ppc_ad_templates order by id desc ");

mkdir("ad-templates/$id/");

if(copy($ba_var,"ad-templates/$id/".$banner))
{
			$rimg = new ImageResizer("ad-templates/$id/$banner");
			$rimg->resize($width,$height,$GLOBALS['service_banners_folder']."/$id/$banner");


 echo "<span class=\"inserted\"><br>New ad template has been created successfully !</span>
<a href=\"view-templates.php\">Manage Ad Templates</a><br><br>";
			
			
}
}
}
 include("admin.footer.inc.php"); ?>