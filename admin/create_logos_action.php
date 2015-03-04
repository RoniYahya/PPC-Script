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
if($script_mode=="demo")
	{
		if($lid<5)
		{
	
		
		$str='<br><br><br><br><br><br><br><center><span class="already">These Operation not allowed demo!</span> <br> <br>
		<a href="javascript:history.back(-1);"><strong>Go Back</strong></a>         </center>		
		';
		
		echo $str; 
		
		exit;
		}
	}

$name1=$_POST['name'];

$time=time();

$file=$_FILES['ad_logos']['name'];

$ad_logosname=$file;

//echo $time;exit;
$size=$_FILES['ad_logos']['size'];


if((isset($_FILES['ad_logos']['name']))&&($_FILES['ad_logos']['name']!=''))
{   //echo "<pre>";print_r($_FILES);exit;
	
	
	$filesize=100;
		
			if((($_FILES['ad_logos']['size'])/1024) > $filesize )
			{   
			
			
					//mysql_query("update ppc_settings set value='$file' where name=''");
				//	unlink("../".$GLOBALS['banners_folder']."/ad_logos/".$file);
				
				?><span class="already"><br>
		        <?php echo "Invalid Image. Size is too big (more than 100 kb).";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br><?php
				
				
				include("admin.footer.inc.php");
	exit(0);
			//	exit(0);
			}
			$path_info = pathinfo($file);
			//print_r($path_info);exit;
			$filename=$path_info['filename'];
			
			$ext=$path_info['extension'];
			
			$ad_logosname=$filename.$time;
			//$file=md5('$file');
		
		$ad_logosname=$ad_logosname.".".$ext;
		
	
	
	if(!((($_FILES["ad_logos"]["type"] == "image/gif")
	|| ($_FILES["ad_logos"]["type"] == "image/jpeg")
	|| ($_FILES["ad_logos"]["type"] == "image/jpg")
	|| ($_FILES["ad_logos"]["type"] == "image/png")
	
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
	else if($file!=''){
		
			
		///////////////////////////////////
	
		//move_uploaded_file($_FILES["ad_logos"]["tmp_name"],"../".$GLOBALS['banners_folder']."/ad_logos/" . $_FILES["ad_logos"]["name"]);
	if(!file_exists("../".$GLOBALS['ad_logos_folder']))
	{
	
	
	mkdir("../".$GLOBALS['ad_logos_folder']."/ad_logos/",0777);	
	}
	
	$size=getimagesize($_FILES['ad_logos']['tmp_name']);
//
		
	
	if($size[0]<=400 && $size[1]<=100)
	{
	

		move_uploaded_file($_FILES['ad_logos']['tmp_name'],"../".$GLOBALS['ad_logos_folder']."/".$ad_logosname);
		

			
//			$size1=getimagesize("../".$GLOBALS['banners_folder']."/ad_logos/".$file);
//
//			// propotionally image resizing
//$width=($size1[0]/$size1[1])*60;
//$height=$size[1];
//			$rimg = new ImageResizer("../".$GLOBALS['banners_folder']."/ad_logos/$file");
//			$rimg->resize($width,$height,"../".$GLOBALS['banners_folder']."/ad_logos/$file");
//			
	//mysql_query("update ppc_settings set value='$file' where name='ad_logos_path'");
	
	
	//echo "insert into ad_ad_logos_details(id,cid,ad_logos_name,status) values('','0','$ad_logosname','1')";exit;
		//echo "insert into ad_logos_details(id,cid,name,ad_logos_name,status) values('','0','$name','$ad_logosname','1')";exit;
	mysql_query("insert into ad_logos_details(id,cid,name,ad_logos_name,status) values('','0','$name1','$ad_logosname','1')");		
		///////////////////////////////////
		
		?>
<span class="inserted"><br>
		<?php echo "Ad Logo has been successfully Created !";?></span>
		<br /><br /><strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>
		<?php
	}
	else
	{ 
		
		
		move_uploaded_file($_FILES['ad_logos']['tmp_name'],"../".$GLOBALS['ad_logos_folder']."/".$ad_logosname);
//					$size1=getimagesize("../".$GLOBALS['banners_folder']."/ad_logos/".$file);
			// propotionally image resizing
			if($size[0]>400 && $size[1]<=100)
			{
$width=400;
$height=(400*$size[1])/$size[0];
		$rimg = new ImageResizer("../".$GLOBALS['ad_logos_folder']."/$file");
		$rimg->resize($width,$height,"../".$GLOBALS['ad_logos_folder']."/$file");
			}
			if($size[0]<=400 && $size[1]>100)
			{
$height=100;
$width=($size[0]/$size[1])*100;
		$rimg = new ImageResizer("../".$GLOBALS['ad_logos_folder']."/$file");
		$rimg->resize($width,$height,"../".$GLOBALS['ad_logos_folder']."/$file");
			}
	if($size[0]>400 && $size[1]>100)
			{
$width=400;
$height=(400*$size[1])/$size[0];
if($height>100)
{
	
	
	$width=($width/$height)*100;
	$height=100;
}
		$rimg = new ImageResizer("../".$GLOBALS['ad_logos_folder']."/$file");
		$rimg->resize($width,$height,"../".$GLOBALS['ad_logos_folder']."/$file");
			}
			//mysql_query("update ppc_settings set value='$file' where name='ad_logos_path'");
			//echo "insert into ad_ad_logos_details(id,cid,ad_logos_name,status) values(' ','0','$ad_logosname','1')";exit;
			mysql_query("insert into ad_logos_details(id,cid,name,ad_logos_name,status) values(' ','0','$name1','$ad_logosname','1')");
			
			?>
			<span class="inserted"><br>
		<?php echo "Ad Logo has been successfully Created ! . ";?></span>
		<strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>

	<?php
}
}
}

		else
{
?>

<strong><a href="javascript:history.back(-1)">Back</a></strong>
<?php
}
?>
<br>
<br>

<?php include("admin.footer.inc.php");

function safeRead($var)
{
$str=$_POST[$var];
phpSafe($str);
return $str;
}
?>