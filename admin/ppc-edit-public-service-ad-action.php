<?php 

/*--------------------------------------------------+
|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?>
<?php

$file="export_data";

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
$url1=urldecode($_POST['url1']);
$adtype=$_GET['adtype'];

/////////////////////////////wap

$wap_flag=$_GET['wap'];
phpsafe($wap_flag);

if($wap_flag==1)
{
	$table='wap_ad_block';
	$wap_status="wap_status=1";
}
else
{
	$table='ppc_ad_block';
	$wap_flag=0;
	$wap_status="wap_status=0";
}
////////////////////////////wap



$id=$_GET['id'];
phpsafe($id);

$ad_name=$_POST['txt'];
phpsafe($ad_name);





if($adtype==1) 
{
	
		$size=$_POST['size'];
		$url=$_POST['url'];
		$adlang=$_POST['language'];
		phpSafeUrl($url);
phpSafe($adlang);

		$banner=$_FILES['banner']['name'];
		if($wap_flag!=1)
		$banner=str_replace('#','',$banner);                              //************** For SWF
		
		
		phpSafe($banner);
		
		$width=$mysql->echo_one("select width from banner_dimension where $wap_status and id=$size");
		$height=$mysql->echo_one("select height from banner_dimension where $wap_status and id=$size");
		$filesize=$mysql->echo_one("select file_size from banner_dimension where $wap_status and id=$size");
		
		
		
		
		if($banner!="")
		{
		$exten=strtolower(substr($banner,-4));
		$newexten=str_replace('.',"",$exten);                    //************************ For Flash Ads ******************************
		
		$size_new=getimagesize($_FILES['banner']['tmp_name']);
		
		




if($wap_flag==0)
{

if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png"  && $exten!=".swf")
{

	?>
		<span class="already"><br><?php echo "You can upload only JPEG,GIF,PNG and SWF files only !";?> </span>
		<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br> <br>
	<?php
	include("admin.footer.inc.php");
	exit(0);



}
}
else
{
if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png")
{

	?>
		<span class="already"><br><?php echo "You can upload only JPEG,GIF and PNG files only !";?> </span>
		<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br> <br>
	<?php
	include("admin.footer.inc.php");
	exit(0);



}



}

if($newexten=="swf")
{
if(($size_new[0] > $width) || ($size_new[1] > $height) || ($size_new[0] < $width) || ($size_new[1] < $height) )
{	
	
	?>
		<span class="already"><br><?php echo "Image banner that you have uploaded is not within the size limit you selected !";?> </span>
		<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br> <br>
	<?php
	include("admin.footer.inc.php");
	exit(0);

	
	
	
}
}

if((($_FILES['banner']['size'])/1024) > $filesize )
								{
																		
									 echo "<span class=\"already\"><br>Banner file size should be in between 1 KB and $filesize KB !</span><a href=\"javascript:history.back(-1);\">
									 Go Back And Modify</a> <br><br>";
									
									include("admin.footer.inc.php");
									exit(0);
								}





if($newexten=="swf")
{
$name_new=$_FILES['banner']['tmp_name'];
$swf_file_news=Inout_FlashImageRetrieveFun($name_new);

list($result_news, $flg_href_new) = Inout_FlashInfoFun($swf_file_news);    //*************** For Finding HardCoded Links***********************************

if($flg_href_new !=0)
{

echo "<span class=\"already\"><br>Uploaded files contains non-replacable hard coded links.!</span><a href=\"javascript:history.back(-1);\">
		 Go Back And Modify</a><br><br>";
		
	include("admin.footer.inc.php");
	exit(0);
            //*************** If non-replacable Hard Coded Links Exists in the Image *******

}

}

		
		}
		
		
		
		
		
		
		if($size==""||$url==""||$adlang==""||$ad_name=="")
			{
				?>
				<span class="already"><br>
				<?php echo "Please check whether you filled all mandatory fields !";?></span>
				<a href="javascript:history.back(-1);">Go Back And Modify</a>           <br><br>
				<?php
			}
		else
			{
			if($banner!="")
				{
									
					mkdir($GLOBALS['service_banners_folder']."/$id/");
					if(copy($_FILES['banner']['tmp_name'],$GLOBALS['service_banners_folder']."/$id/".$banner))
						{
						
						
						
						//************************ Changes For Flash Ads ***************************************************************************



if($newexten=="swf")
{
$hard_count=0;

$name=$GLOBALS['service_banners_folder']."/$id/".$banner;
$swf_file=Inout_FlashImageRetrieveFun($name);

list($result, $flg_href) = Inout_FlashInfoFun($swf_file);    //*************** For Finding HardCoded Links***********************************

/*
if($flg_href !=0)
{

echo "<span class=\"already\"><br>Uploaded files contains non-replacable hard coded links.!</span><a href=\"javascript:history.back(-1);\">
		 Go Back And Modify</a><br><br>";
		
	include("admin.footer.inc.php");
	exit(0);






           //*************** If non-replacable Hard Coded Links Exists in the Image *******

}
*/

if(count($result) >0)                                    //*************** If HardCoded Links Already Exists ****************************
{
$hard_count=count($result);

}



if(count($result) >0)                                    //*************** If HardCoded Links Already Exists ****************************
{
   if ($swf_file) {
        if (Inout_FlashVersionFun($swf_file) >= 3 && Inout_FlashInfoFun($swf_file))
		 {
		
		   // SWF's requiring player version 6+ which are already compressed should stay compressed
            if (Inout_FlashVersionFun($swf_file) >= 6 && Inout_FlashCompressedFun($swf_file))
			{
                $compress = true;
            } 
			elseif (isset($compress))
			{
                $compress = true;
            } 
			else 
			{
                $compress = false;
            }

           $convert_links = array();
          
for($i=0;$i < count($result);$i++)
{
$convert_links[$i]=$i+1;
}

  list($result, $parameters) = Inout_FlashConvertFun($swf_file, $compress, $convert_links);              //********** For Removing HardCoded Links ***************

                     
$name=$GLOBALS['service_banners_folder']."/$id/".$banner;           
Inout_FlashImageStoreFun($name,$result);                                                                    //********** For Storing Converted Images ***************     


            
        }
    }

}

mysql_query("update `ppc_public_service_ads` set link='$url',bannersize='$size',summary='$banner',adlang='$adlang',name='$ad_name',contenttype='$newexten',hardcodelinks='$hard_count' where id='$id' and wapstatus='$wap_flag';");

}
//************************ Changes For Flash Ads ***************************************************************************
else
{
						
							$rimg = new ImageResizer($GLOBALS['service_banners_folder']."/$id/$banner");
			                $rimg->resize($width,$height,$GLOBALS['service_banners_folder']."/$id/$banner");
				
mysql_query("update `ppc_public_service_ads` set link='$url',bannersize='$size',summary='$banner',adlang='$adlang',name='$ad_name',contenttype='',hardcodelinks='0' where id='$id' and wapstatus='$wap_flag';");

}

							
						
				
						
						}
					
			
			}
		else
			{
			mysql_query("update `ppc_public_service_ads` set link='$url',adlang='$adlang',name='$ad_name' where id='$id' and wapstatus='$wap_flag';");
			}
			
			 echo "<span class=\"inserted\"><br>Public service banner ad has been updated successfully !</span>
			<a href=\"ppc-view-public-ads.php\">Manage Public Service Ads</a><br><br>";
}
}






else if($adtype==2) 
{
$size=$_POST['size'];
$url=trim($_POST['url']);
$summary=trim($_POST['summary']);
$displayurl=trim($_POST['displayurl']);
$banner=$_FILES['banner']['name'];
if($wap_flag!=1)
$banner=str_replace('#','',$banner);                              //************** For SWF





$adlang=$_POST['language'];
phpSafe($adlang);
phpSafe($url);
phpSafe($banner);
phpSafe($summary);
phpSafe($displayurl);



$width=$mysql->echo_one("select width from catalog_dimension where id=$size and wapstatus='$wap_flag'");
$height=$mysql->echo_one("select height from catalog_dimension where id=$size and wapstatus='$wap_flag'");
$filesize=$mysql->echo_one("select filesize from catalog_dimension where id=$size and wapstatus='$wap_flag'");
																			



if($banner!="")
{
$exten=strtolower(substr($banner,-4));
$newexten=str_replace('.',"",$exten);                    //************************ For Flash Ads ******************************
$size_new=getimagesize($_FILES['banner']['tmp_name']);



if($wap_flag==0)
{

if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png"  && $exten!=".swf")
{

	?>
		<span class="already"><br><?php echo "You can upload only JPEG,GIF,PNG and SWF files only !";?> </span>
		<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br> <br>
	<?php
	include("admin.footer.inc.php");
	exit(0);



}
}
else
{
if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png")
{

	?>
		<span class="already"><br><?php echo "You can upload only JPEG,GIF and PNG files only !";?> </span>
		<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br> <br>
	<?php
	include("admin.footer.inc.php");
	exit(0);



}



}


if($newexten=="swf")
{
if(($size_new[0] > $width) || ($size_new[1] > $height) || ($size_new[0] < $width) || ($size_new[1] < $height) )
{	
	
	?>
		<span class="already"><br><?php echo "Image banner that you have uploaded is not within the size limit you selected !";?> </span>
		<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br> <br>
	<?php
	include("admin.footer.inc.php");
	exit(0);

	
	
	
}
}


if($newexten=="swf")
{
$name_new=$_FILES['banner']['tmp_name'];
$swf_file_news=Inout_FlashImageRetrieveFun($name_new);

list($result_news, $flg_href_new) = Inout_FlashInfoFun($swf_file_news);    //*************** For Finding HardCoded Links***********************************

if($flg_href_new !=0)
{

echo "<span class=\"already\"><br>Uploaded files contains non-replacable hard coded links.!</span><a href=\"javascript:history.back(-1);\">
		 Go Back And Modify</a><br><br>";
		
	include("admin.footer.inc.php");
	exit(0);
            //*************** If non-replacable Hard Coded Links Exists in the Image *******

}

}

if((($_FILES['banner']['size'])/1024) > $filesize )
{
																					
echo "<span class=\"already\"><br>Catalog file size should be in between 1 KB and $filesize KB !</span><a href=\"javascript:history.back(-1);\">																	 Go Back And Modify</a> <br><br>";
include("admin.footer.inc.php");
exit(0);
}



















}





		if($size==""||$url==""||$summary==""||$displayurl==""||$adlang==""||$ad_name=="")
			{
				?>
				<span class="already"><br>
				<?php echo "Please check whether you filled all mandatory fields !";?></span>
				<a href="javascript:history.back(-1);">Go Back And Modify</a>           <br><br>
				<?php
			}
		
		else
			{
				
						if($banner!="")
						{
																			
												
															mkdir($GLOBALS['service_banners_folder']."/$id/");
															if(copy($_FILES['banner']['tmp_name'],$GLOBALS['service_banners_folder']."/$id/".$banner))
																		{
																			
																			
																			
//************************ Changes For Flash Ads ***************************************************************************



if($newexten=="swf")
{
$hard_count=0;

$name=$GLOBALS['service_banners_folder']."/$id/".$banner;
$swf_file=Inout_FlashImageRetrieveFun($name);

list($result, $flg_href) = Inout_FlashInfoFun($swf_file);    //*************** For Finding HardCoded Links***********************************

/*
if($flg_href !=0)
{

echo "<span class=\"already\"><br>Uploaded files contains non-replacable hard coded links.!</span><a href=\"javascript:history.back(-1);\">
		 Go Back And Modify</a><br><br>";
		
	include("admin.footer.inc.php");
	exit(0);






           //*************** If non-replacable Hard Coded Links Exists in the Image *******

}
*/

if(count($result) >0)                                    //*************** If HardCoded Links Already Exists ****************************
{
$hard_count=count($result);

}



if(count($result) >0)                                    //*************** If HardCoded Links Already Exists ****************************
{
   if ($swf_file) {
        if (Inout_FlashVersionFun($swf_file) >= 3 && Inout_FlashInfoFun($swf_file))
		 {
		
		   // SWF's requiring player version 6+ which are already compressed should stay compressed
            if (Inout_FlashVersionFun($swf_file) >= 6 && Inout_FlashCompressedFun($swf_file))
			{
                $compress = true;
            } 
			elseif (isset($compress))
			{
                $compress = true;
            } 
			else 
			{
                $compress = false;
            }

           $convert_links = array();
          
for($i=0;$i < count($result);$i++)
{
$convert_links[$i]=$i+1;
}

  list($result, $parameters) = Inout_FlashConvertFun($swf_file, $compress, $convert_links);              //********** For Removing HardCoded Links ***************

                     
$name=$GLOBALS['service_banners_folder']."/$id/".$banner;           
Inout_FlashImageStoreFun($name,$result);                                                                    //********** For Storing Converted Images ***************     


            
        }
    }

}

$sqladdon=",bannersize='$size',displayurl='$displayurl',summary='$summary', title='".$banner."',contenttype='".$newexten."',hardcodelinks='".$hard_count."'";

mysql_query("update `ppc_public_service_ads` set link='$url',name='$ad_name',adlang='$adlang' $sqladdon  where id='$id' and wapstatus='$wap_flag';");

}					
//************************ Changes For Flash Ads ***************************************************************************
else
{																		
$sqladdon=",bannersize='$size',displayurl='$displayurl',summary='$summary', title='".$banner."',contenttype='',hardcodelinks='0'";
$rimg = new ImageResizer($GLOBALS['service_banners_folder']."/$id/$banner");
$rimg->resize($width,$height,$GLOBALS['service_banners_folder']."/$id/$banner");
mysql_query("update `ppc_public_service_ads` set link='$url',name='$ad_name',adlang='$adlang' $sqladdon  where id='$id' and wapstatus='$wap_flag';");


}																
	
																		}
													
											
					}
				else
					{
					
					$sql=",displayurl='$displayurl',summary='$summary'";
					mysql_query("update `ppc_public_service_ads` set link='$url',name='$ad_name',adlang='$adlang' $sql where id='$id' and wapstatus='$wap_flag';");
					
					
					
					
					}
			
			 echo "<span class=\"inserted\"><br>Public service catalog ad has been updated successfully !</span>
			<a href=\"ppc-view-public-ads.php\">Manage Public Service Ads</a><br><br>";
		}

}
else
{
	$title=$_POST['title'];
	$url=$_POST['url'];
	$summary=$_POST['summary'];
	$displayurl=$_POST['disp'];
	$adlang=$_POST['language'];
phpSafe($adlang);
	phpSafe($title);
phpSafeUrl($url);
phpSafe($summary);
phpSafe($displayurl);

	
	if($title==""||$url==""||$summary==""||$displayurl==""||$adlang=="" ||$ad_name=="")
		{
			?><span class="already"><br><?php echo "Please check whether you filled all mandatory fields !";?> </span>
			<a href="javascript:history.back(-1);">Go Back And Modify</a>          <br><br>
			<?php
			}
		else
			{
		
			mysql_query("update `ppc_public_service_ads` set link='$url',title='$title',summary='$summary',displayurl='$displayurl',adlang='$adlang',name='$ad_name' where id='$id'and wapstatus='$wap_flag' ;");
			
$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}

			?> <span class="inserted"><br><?php echo "Public service text ad has been updated successfully !"; ?></span><br /><br />
			<a href="<?php echo $url1; ?>">Click here to go back to the page you were viewing</a><br><br>
			<?php
			}
}

?>

<?php include("admin.footer.inc.php");?>
