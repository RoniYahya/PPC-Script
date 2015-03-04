<?php

/*--------------------------------------------------+
 |													 |
 | Copyright ? 2006 http://www.inoutscripts.com/  
 | All Rights Reserved.								 |
 | Email: contact@inoutscripts.com                    |
 |                                                    |
 +---------------------------------------------------*/
/*<?php
//if(isset($_POST['paypal_email']))
//{
//	$name12=safeRead('paypal_email');//$_POST['ppc_engine_name'];
//	if($name12=="")
//	{?>
//<span class="already"><br>
//	<?php echo "Invalid data. Cannot update Paypal email address.";?></span>
//<p></p>
//	<?php
//	}
//	else
//	{
//		mysql_query("update ppc_settings set value='$name12' where name='paypal_email'");
//		?>
//<span class="inserted"><br>
//		<?php echo "Paypal email  has been successfully updated . ";?></span>
//<p></p>
//		<?php
//	}
//}
//?>
//<?php
//if(isset($_POST['payapl_payment_item_escription']))
//{
//	$name13=safeRead('payapl_payment_item_escription');//$_POST['ppc_engine_name'];
//	if($name13=="")
//	{?>
//<span class="already"><br>
//	<?php echo "Invalid data. Cannot update Paypal payment description.";?></span>
//<p></p>
//	<?php
//	}
//	else
//	{
//		mysql_query("update ppc_settings set value='$name13' where name='payapl_payment_item_escription'");
//		?>
//<span class="inserted"><br>
//		<?php echo "Paypal payment description has been successfully updated . ";?></span>
//<p></p>
//		<?php
//	}
//}
//?>*/


?><?php


include("config.inc.php");
include("../extended-config.inc.php");  
includeClass("ImageResizer");

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


$ini_error_status=ini_get('error_reporting');

include_once("admin.header.inc.php");?>










<?php



$offline_logo_old=$offline_image;
$online_logo_old=$online_image;

$chat_name=trim($_POST['chat_name']);
$chat_status=$_POST['chat_status'];
if($chat_status==1)
{

			 $user_id=1;
			 setcookie("admin_in_user_id",$user_id,0,"/");
			  setcookie("user_id",$user_id,0,"/");
			mysql_query("update nesote_chat_login_status set status='1' where user_id='$user_id' ");
			 mysql_query("update ppc_settings set value='1' where name='default_chat_status'");
			 mysql_query("update ppc_settings set value='1' where name='chat_status'");
}
else
{
		$in_user_id=$_COOKIE['admin_in_user_id'];
			
			mysql_query("update nesote_chat_login_status set status='0' where user_id='$in_user_id' ");
		mysql_query("update ppc_settings set value='0' where name='default_chat_status'");
		mysql_query("update ppc_settings set value='0' where name='chat_status'");

		setcookie("admin_in_user_id","",0,"/");

}
if($chat_name!="")
{
mysql_query("update nesote_chat_public_user set username='$chat_name',name='$chat_name' where status='2' ");
}


$time=time();

$online_logo=$_FILES['online_logo']['name'];
$offline_logo=$_FILES['offline_logo']['name'];



//echo $time;exit;
$size=$_FILES['online_logo']['size'];
$size1=$_FILES['offline_logo']['size'];
$path="../".$GLOBALS['banners_folder']."/chat_status/";

	
	$filesize=100;
///////////////////////online_logo/////////////////////////		
	if($online_logo!="")	
	{
	if($script_mode=="demo")
	{
	$str='<br><br><br><br><br><br><br><center><span class="already">These Operation not allowed demo!</span> <br> <br>
		<a href="javascript:history.back(-1);"><strong>Go Back</strong></a>         </center>
		';
	echo $str; exit;
	}
	
		if((($_FILES['online_logo']['size'])/1024) > $filesize )
			{   
			
		
					//mysql_query("update ppc_settings set value='$file' where name=''");
				
				
				?><span class="already"><br>
		        <?php echo "Invalid Image. Size is too big (more than 100 kb).";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br><?php
				
				
				include("admin.footer.inc.php");
	exit(0);
			//	exit(0);
			}
			$path_info = pathinfo($online_logo);
			//print_r($path_info);exit;
			$filename=$path_info['filename'];
			
			$ext=$path_info['extension'];
			
			$online_logo=md5($filename.$time);
			$online_logo=$online_logo.".".$ext;
			
			//echo $path; exit;	
	if(!((($_FILES["online_logo"]["type"] == "image/gif")
	|| ($_FILES["online_logo"]["type"] == "image/jpeg")
	|| ($_FILES["online_logo"]["type"] == "image/jpg")
	|| ($_FILES["online_logo"]["type"] == "image/png")
	
	)))
		{
				 
			?>
	<span class="already"><br>
			<?php echo "Invalid data. Enter a valid image.";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br>
	<p></p>
			<?php
			include("admin.footer.inc.php");
		exit(0);
		}   



	
	$size=getimagesize($_FILES['online_logo']['tmp_name']);
//
	
	
	if($size[0]<=400 && $size[1]<=100)
	{
	
unlink($path.$online_logo_old);
		move_uploaded_file($_FILES['online_logo']['tmp_name'],$path.$online_logo);
	mysql_query("update ppc_settings set value='$online_logo' where name='online_image'");		
		
	?>
<span class="inserted"><br>
		<?php echo "Chat Setting has been successfully Updated ! ";?></span>
		<br /><br /><strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>
		<?php
	}
	else
	{ 
	
			unlink($path.$online_logo_old);
		
		move_uploaded_file($_FILES['online_logo']['tmp_name'],$path.$online_logo);
			if($size[0]>200 && $size[1]<=100)
			{
			
$width=200;
$height=(200*$size[1])/$size[0];
		$rimg = new ImageResizer($path."/$online_logo");
		$rimg->resize($width,$height,$path."/$online_logo");
			}
			if($size[0]<=200 && $size[1]>100)
			{
			
$height=100;
$width=($size[0]/$size[1])*100;
		$rimg = new ImageResizer($path."/$online_logo");
		$rimg->resize($width,$height,$path."/$online_logo");
			}
	if($size[0]>200 && $size[1]>100)
			{
			
$width=200;
$height=(200*$size[1])/$size[0];
if($height>100)
{
	
	
	$width=($width/$height)*100;
	$height=100;
}
//echo $width."<br>";
//echo $height; exit;
		$rimg = new ImageResizer($path."/$online_logo");
		$rimg->resize($width,$height,$path."/$online_logo");
			}
			mysql_query("update ppc_settings set value='$online_logo' where name='online_image'");		
			
			?>
			<span class="inserted"><br>
		<?php echo "Chat Setting has been successfully Updated ! ";?></span>
		<strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>

	<?php
}


?>
<br>
<br>

<?php include("admin.footer.inc.php");

			
}


///////////////////////online_logo/////////////////////////



///////////////////////offline_logo/////////////////////////

if($offline_logo!="")	
	{
	if($script_mode=="demo")
	{
	$str='<br><br><br><br><br><br><br><center><span class="already">These Operation not allowed demo!</span> <br> <br>
		<a href="javascript:history.back(-1);"><strong>Go Back</strong></a>         </center>
		';
	echo $str; 
	
	exit;
	}
	
		if((($_FILES['offline_logo']['size'])/1024) > $filesize )
			{   
			
			
					//mysql_query("update ppc_settings set value='$file' where name=''");
				//	unlink("../".$GLOBALS['banners_folder']."/ad_logos/".$file);
				
				?><span class="already"><br>
		        <?php echo "Invalid Image. Size is too big (more than 100 kb).";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br><?php
				
				
				include("admin.footer.inc.php");
	exit(0);
			//	exit(0);
			}
			$path_info = pathinfo($online_logo);
			//print_r($path_info);exit;
			$filename=$path_info['filename'];
			
			$ext=$path_info['extension'];
			
			$offline_logo=md5($filename.$time);
			$offline_logo=$offline_logo.".".$ext;
			//$path="../".$GLOBALS['banners_folder']."/chat_status/";
			//echo $path; exit;	
	if(!((($_FILES["offline_logo"]["type"] == "image/gif")
	|| ($_FILES["offline_logo"]["type"] == "image/jpeg")
	|| ($_FILES["offline_logo"]["type"] == "image/jpg")
	|| ($_FILES["online_logo"]["type"] == "image/png")
	
	)))
		{
				 
			?>
	<span class="already"><br>
			<?php echo "Invalid data. Enter a valid image.";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br>
	<p></p>
			<?php
			include("admin.footer.inc.php");
		exit(0);
		}   



	
	$size=getimagesize($_FILES['offline_logo']['tmp_name']);
//
	
	
	if($size[0]<=400 && $size[1]<=100)
	{
	
		unlink($path.$offline_logo_old);
		move_uploaded_file($_FILES['offline_logo']['tmp_name'],$path.$offline_logo);
	mysql_query("update ppc_settings set value='$offline_logo' where name='offline_image'");		
		
	?>
<span class="inserted"><br>
		<?php echo "Chat Setting has been successfully Updated ! ";?></span>
		<br /><br /><strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>
		<?php
	}
	else
	{ 
		
		unlink($path.$offline_logo_old);
		move_uploaded_file($_FILES['offline_logo']['tmp_name'],$path.$offline_logo);
			if($size[0]>200 && $size[1]<=100)
			{
			
$width=200;
$height=(200*$size[1])/$size[0];
		$rimg = new ImageResizer($path."/$offline_logo");
		$rimg->resize($width,$height,$path."/$offline_logo");
			}
			if($size[0]<=200 && $size[1]>100)
			{
			
$height=100;
$width=($size[0]/$size[1])*100;
		$rimg = new ImageResizer($path."/$offline_logo");
		$rimg->resize($width,$height,$path."/$offline_logo");
			}
	if($size[0]>200 && $size[1]>100)
			{
			
$width=200;
$height=(200*$size[1])/$size[0];
if($height>100)
{
	
	
	$width=($width/$height)*100;
	$height=100;
}
//echo $width."<br>";
//echo $height; exit;
		$rimg = new ImageResizer($path."/$offline_logo");
		$rimg->resize($width,$height,$path."/$offline_logo");
			}
			mysql_query("update ppc_settings set value='$offline_logo' where name='offline_image'");		
			
			?>
			<span class="inserted"><br>
		<?php echo "Chat Setting has been successfully Updated ! ";?></span>
		<strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>

	<?php
}


?>
<br>
<br>

<?php include("admin.footer.inc.php");

			
}

///////////////////////offline_logo/////////////////////////		
?>
<span class="inserted"><br>
		<?php echo "Chat Setting has been successfully Updated ! ";?></span>
		<strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>	
<?php include("admin.footer.inc.php");
	
