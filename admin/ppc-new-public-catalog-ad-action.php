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

if($_POST['wap1']==0)
{
$size=$_POST['size'];
$url=trim($_POST['url']);
$summary=trim($_POST['summary']);
$displayurl=trim($_POST['displayurl']);
$banner=$_FILES['banner']['name'];
$banner=str_replace('#','',$banner);                              //************** For SWF


$adlang=trim($_POST['language']);
$banner_tmp=$_FILES['banner']['tmp_name'];
$name=trim($_POST['text1']);
}
else if($_POST['wap1']==1)
{
$size=$_POST['msize'];
$url=trim($_POST['murl']);
$summary=trim($_POST['msummary']);
$displayurl=trim($_POST['mdisplayurl']);
$banner=$_FILES['mbanner']['name'];
$adlang=trim($_POST['language']);
$banner_tmp=$_FILES['mbanner']['tmp_name'];
$name=trim($_POST['text2']);
}
//print_r($_POST);
//echo "sdhjhjdfdfdfk".$banner;
phpSafeUrl($url);
phpSafe($banner);
phpSafe($summary);
phpSafe($displayurl);
phpSafe($adlang);
phpSafe($name);
//echo $size."dddd";

/////////////////////wap

$wap_flag=trim($_POST['wap1']);
phpSafe($wap_flag);

if($wap_flag==1)
{
	$wap_string='where wapstatus=1';
	$wap_string2='and wapstatus=1';
}
else
{
	$wap_string='where wapstatus=0';
	$wap_string2='and wapstatus=0';
}

////////////////////wap


$width=$mysql->echo_one("select width from catalog_dimension where id=$size $wap_string2");
$height=$mysql->echo_one("select height from catalog_dimension where id=$size $wap_string2");
$filesize=$mysql->echo_one("select filesize from catalog_dimension where id=$size $wap_string2");


if($wap_flag==1)
{
$ba_var=$_FILES['mbanner']['tmp_name'];
$ban_size=$_FILES['mbanner']['size'];

}
else if($wap_flag==0)
{
$ba_var=$_FILES['banner']['tmp_name'];
$ban_size=$_FILES['banner']['size'];


}

$exten=strtolower(substr($banner,-4));
$newexten=str_replace('.',"",$exten);                    //************************ For Flash Ads ******************************

$size_new=getimagesize($ba_var);


if($newexten=="swf")
{
if(($size_new[0] > $width) || ($size_new[1] > $height) || ($size_new[0] < $width) || ($size_new[1] < $height) )
{	
	
	echo "<span class=\"already\"><br>Image banner that you have uploaded is not within the size limit you selected !</span><a href=\"javascript:history.back(-1);\">
		 Go Back And Modify</a><br><br>";
		
	include("admin.footer.inc.php");
	exit(0);
	
	
}
}



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
else if($wap_flag==1)
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
$name_new=$ba_var;
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

if($size==""||$url==""||$banner=="" ||$summary==""||$displayurl==""||$adlang==""||$name=="")
{
	?>
	<span class="already"><br>
	<?php echo "Please check whether you filled all mandatory fields !";?></span>
	<a href="javascript:history.back(-1);">Go Back And Modify</a>           <br> <br>
	<?php
	include("admin.footer.inc.php");
	exit(0);
}

if(($ban_size/1024) > $filesize )
{
echo "<span class=\"already\"><br>Banner file size should be in between 1 KB and $filesize KB !</span><a href=\"javascript:history.back(-1);\">
		 Go Back And Modify</a><br><br>";
		
	include("admin.footer.inc.php");
	exit(0);

}







		
	
						
mysql_query("INSERT INTO `ppc_public_service_ads` ( `id` ,`link` , `title`,`summary`,`createtime` ,`bannersize`,`adtype`, `status`, `displayurl` ,`wapstatus`,`adlang`,`name`,`contenttype`)
VALUES ('0','$url','$banner', '$summary','".time()."', '$size','2','1','$displayurl','$wap_flag','$adlang','$name','$newexten');");
$id=$mysql->echo_one("select id from ppc_public_service_ads $wap_string order by id desc");

						mkdir($GLOBALS['service_banners_folder']."/$id");
						
			
						if(copy($banner_tmp,$GLOBALS['service_banners_folder']."/$id/".$banner))
									{
									
									
									
									//************************ Changes For Flash Ads ***************************************************************************



if($newexten=="swf")
{
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
mysql_query("update ppc_public_service_ads set hardcodelinks='$hard_count' where id='$id'");
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
}
//************************ Changes For Flash Ads ***************************************************************************
else
{																	
										$rimg = new ImageResizer($GLOBALS['service_banners_folder']."/$id/$banner");
			                            $rimg->resize($width,$height,$GLOBALS['service_banners_folder']."/$id/$banner");

}	

																	
}
	

	 echo "<span class=\"inserted\"><br>New public service catalog ad has been created successfully !</span>
	<a href=\"ppc-view-public-ads.php\">Manage Public Service Ads</a><br><br>";
	




 include("admin.footer.inc.php"); ?>