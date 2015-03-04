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


$time=time();

$file=$_FILES['ad_logos']['name'];

$ad_logosname=$file;

//echo $time;exit;
$size=$_FILES['ad_logos']['size'];


if((isset($_FILES['ad_logos']['name']))&&($_FILES['ad_logos']['name']!=''))
{   //echo "<pre>";print_r($_FILES);exit;
	
	$path_info = pathinfo($ad_logosname);
			//print_r($path_info);exit;
			$filename=$path_info['filename'];
			
			$ext=$path_info['extension'];
			
			$file=md5($filename.$time);
			$ad_logosname=$file.".".$ext;
	
		

		
	
	
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
		
	
	
		move_uploaded_file($_FILES['ad_logos']['tmp_name'],"../".$GLOBALS['ad_logos_folder']."/".$ad_logosname);
		
   mysql_query("insert into adserver_public_images(id,image_name,date,status) values('','$ad_logosname','$time','1')");		
		///////////////////////////////////
		
		?>
<span class="inserted"><br>
		<?php echo "Slide Image has been successfully Created !";?></span>
		<br /><br /><strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>
		<?php

}
}

?>
<br>

<?php include("admin.footer.inc.php");

function safeRead($var)
{
$str=$_POST[$var];
phpSafe($str);
return $str;
}
?>