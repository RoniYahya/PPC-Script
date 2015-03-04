<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
includeClass("ImageResizer");
$user=new User("ppc_publishers");
$pubid=$user->getUserID();
$uid=$mysql->echo_one("select uid from ppc_publishers where uid=$pubid");





$name1=$_POST['name'];

$time=time();

$file=$_FILES['ad_logos']['name'];


 $filename_check = $_FILES["ad_logos"]["tmp_name"];

			 $xmldata="";
			if($fp=fopen($filename_check,"r"))
			{
			    while(!feof($fp))
			          {
				    $xmldata.=fgetc($fp);
				}
			    fclose($fp);
			}
		 	$count1= substr_count($xmldata, '<? ');
		 	$count2= substr_count($xmldata, '<?php');
		 
			if(($count1>0) || ($count2>0) )
			{

		
		header("Location:publisher-show-message.php?id=10506");

		    	   exit(0);
			}	


$ad_logosname=$file;


$size=$_FILES['ad_logos']['size'];


if((isset($_FILES['ad_logos']['name']))&&($_FILES['ad_logos']['name']!=''))
{  // echo "<pre>";print_r($_FILES);exit;
	
	
	$filesize=100;
		
			if((($_FILES['ad_logos']['size'])/1024) > $filesize )
			{   
			
			
					//mysql_query("update ppc_settings set value='$file' where name=''");
				//	unlink($GLOBALS['banners_folder']."/ad_logos/".$file);
				
				
						header("Location:publisher-show-message.php?id=10507");
						exit;
				
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

		header("Location:publisher-show-message.php?id=10506");
	exit(0);
	}
	else if($file!=''){
		
			
		///////////////////////////////////
	
		//move_uploaded_file($_FILES["ad_logos"]["tmp_name"],$GLOBALS['banners_folder']."/ad_logos/" . $_FILES["ad_logos"]["name"]);
	if(!file_exists($GLOBALS['ad_logos_folder']))
	{
	
	
	mkdir($GLOBALS['ad_logos_folder']."/ad_logos/",0777);	
	}
	
	$size=getimagesize($_FILES['ad_logos']['tmp_name']);
//
		
	
	if($size[0]<=400 && $size[1]<=100)
	{
	
	//echo "hhh"; exit;
		move_uploaded_file($_FILES['ad_logos']['tmp_name'],$GLOBALS['ad_logos_folder']."/".$ad_logosname);
		

			
//			$size1=getimagesize($GLOBALS['banners_folder']."/ad_logos/".$file);
//
//			// propotionally image resizing
//$width=($size1[0]/$size1[1])*60;
//$height=$size[1];
//			$rimg = new ImageResizer($GLOBALS['banners_folder']."/ad_logos/$file");
//			$rimg->resize($width,$height,$GLOBALS['banners_folder']."/ad_logos/$file");
//			
	//mysql_query("update ppc_settings set value='$file' where name='ad_logos_path'");
	
	//echo "hhh"; exit;
	//echo "insert into ad_ad_logos_details(id,cid,ad_logos_name,status) values('','0','$ad_logosname','1')";exit;
		//echo "insert into ad_logos_details(id,cid,name,ad_logos_name,status,user_status) values('',$uid,'$name1','$ad_logosname','1','2')";exit;
	mysql_query("insert into ad_logos_details(id,cid,name,ad_logos_name,status,user_status) values('',$uid,'$name1','$ad_logosname','-1','1')");		
		///////////////////////////////////
		header("Location:publisher-show-success.php?id=10505&page=ppc-publisher-logo-details.php");
exit;
		?>
		
<span class="inserted"><br>
		<?php echo "Ad Logo has been successfully Created !";?></span>
		<br /><br /><strong><a href="javascript:history.back(-1)">Back</a></strong>
<p></p>
		<?php
	}
	else
	{ 
		
		
		move_uploaded_file($_FILES['ad_logos']['tmp_name'],$GLOBALS['ad_logos_folder']."/".$ad_logosname);
//					$size1=getimagesize($GLOBALS['banners_folder']."/ad_logos/".$file);
			// propotionally image resizing
			if($size[0]>400 && $size[1]<=100)
			{
				
$width=400;
$height=(400*$size[1])/$size[0];
		$rimg = new ImageResizer($GLOBALS['ad_logos_folder']."/$file");
		$rimg->resize($width,$height,$GLOBALS['ad_logos_folder']."/$file");
			}
			if($size[0]<=400 && $size[1]>100)
			{
			
$height=100;
$width=($size[0]/$size[1])*100;
		$rimg = new ImageResizer($GLOBALS['ad_logos_folder']."/$file");
		$rimg->resize($width,$height,$GLOBALS['ad_logos_folder']."/$file");
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

		$rimg = new ImageResizer($GLOBALS['ad_logos_folder']."/$file");
		$rimg->resize($width,$height,$GLOBALS['ad_logos_folder']."/$file");
		
			}
			
			//mysql_query("update ppc_settings set value='$file' where name='ad_logos_path'");
		//	echo "insert into ad_logos_details(id,cid,name,ad_logos_name,status,user_status) values('',$uid,'$name1','$ad_logosname','1','2')";exit;
			mysql_query("insert into ad_logos_details(id,cid,name,ad_logos_name,status,user_status) values('',$uid,'$name1','$ad_logosname','-1','1')");		
			header("Location:publisher-show-success.php?id=10505&page=ppc-publisher-logo-details.php");
exit;
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