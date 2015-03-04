<?php 
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

$ad_type=trim($_POST['ad-type']);


$wap_flag=trim($_POST['wap']);
phpSafe($wap_flag);

	$bannerid=0;
if( $ad_type==2  ||  $ad_type==3)
	{
//if(isset(trim($_POST['banner_size1'])))
//{
$bannerid=$_POST['banner_size1'];
phpsafe($bannerid);
$banner_dime=mysql_query("select * from banner_dimension where id='$bannerid'");
$banner_dime1=mysql_fetch_row($banner_dime);
$width=$banner_dime1[1];
$height=$banner_dime1[2];
	
//}
	}
	else
	{
	$width=trim($_POST['width']);

	if(!isPositiveInteger($width))
		$width=200;
	$height=trim($_POST['height']);
	if(!isPositiveInteger($height))
		$height=200;	
	}

//$width=trim($_POST['width']);
//
//if(!isPositiveInteger($width))
//$width=200;
//$height=trim($_POST['height']);
//if(!isPositiveInteger($height))
//$height=200;

$ad_block_name=trim($_POST['ad_block_name']);
if($ad_block_name=="")
	$ad_block_name="Ad_Block_".$width."x".$height;

phpSafe($ad_block_name);



$allow_publishers=trim($_POST['allow_publishers']);



//$banner_file_max_size=100;

//if(!isPositiveInteger($banner_file_max_size))
//$banner_file_max_size=100;

$no_of_text_ads=trim($_POST['no_of_text_ads']);
$ad_orientation=trim($_POST['ad_orientation']);

$title_font=trim($_POST['title_font']);
$ad_title_size=trim($_POST['ad_title_size']);
$ad_font_weight=$_POST['ad_title_font_weight'];
$ad_title_decoration=$_POST['ad_title_decoration'];


$desc_font=$_POST['desc_font'];
$desc_size=$_POST['desc_size'];
$ad_desc_font_weight=$_POST['ad_desc_font_weight'];
$ad_desc_decoration=$_POST['ad_desc_decoration'];

$display_font=$_POST['display_font'];
$disp_url_size=$_POST['disp_url_size'];
$ad_disp_url_font_weight=$_POST['ad_disp_url_font_weight'];
$ad_disp_url_decoration=$_POST['ad_disp_url_decoration'];


$line_spacing=$_POST['line_spacing'];
if(!isPositiveInteger($line_spacing))
$line_spacing=15;

//$credit_text=$_POST['credit_text'];
$credit_text=$_POST['cc'];


$credit_text_font=$_POST['credit_text_font'];
$credit_text_font_weight=$_POST['credit_text_font_weight'];
$credit_text_decoration=$_POST['credit_text_decoration'];

$uc=$_POST['uc'];
$credit_text_positioning=trim($_POST['credit_text_positioning']);
$credit_text_alignment=trim($_POST['credit_text_alignment']);
$border_type=trim($_POST['border_type']);

//catalog

$catalog_size=$_POST['catalog_size'];
$no_of_catalog_ads=$_POST['no_of_catalog_ads'];
$catalog_alignment=$_POST['catalog_alignment'];
$catalog_line_seperator=$_POST['catalog_line_seperator'];



//end catalog

$bc=$_POST['color1'];
	if($bc=="")
		{
		$bc="#000099";
		}

$gc=$_POST['color2'];
	if($gc=="")
		{
		$gc="#0F0F0F";
		}
$tc=$_POST['color3'];
	if($tc=="")
		{
		$tc="#009933";
		}
$dc=$_POST['color4'];
	if($dc=="")
		{
		$dc="#FFFFFF";
		}

if(!isset($_POST['allow_title_color']))
	$allow_title_color=0;
else
	$allow_title_color=$_POST['allow_title_color'];
if(!isset($_POST['allow_desc_color']))
	$allow_desc_color=0;
else
	$allow_desc_color=$_POST['allow_desc_color'];
if(!isset($_POST['allow_disp_url_color']))
	$allow_disp_url_color=0;
else
	$allow_disp_url_color=$_POST['allow_disp_url_color'];
if(!isset($_POST['allow_bg_color']))
	$allow_bg_color=0;
else
	$allow_bg_color=$_POST['allow_bg_color'];
if(!isset($_POST['allow_credit_color']))
	$allow_credit_color=0;
else
	$allow_credit_color=$_POST['allow_credit_color'];
// Added on 17th November 2009
if(!isset($_POST['text_ad_type']))
	{
	$text_ad_type=1;
	}
else
	{
	$text_ad_type=$_POST['text_ad_type'];
	}
// Added on 17th November 2009
if(!isset($_POST['allow_bordor_type']))
	$allow_bordor_type=0;
else
	$allow_bordor_type=$_POST['allow_bordor_type'];
	




$cr_type=0;
$cr_type=$mysql->echo_one("select credittype from ppc_publisher_credits where id='$credit_text'");
if($border_type == 0 && $cr_type ==1)
$border_type =1;
if($allow_bordor_type == 1 && $cr_type ==1)
$allow_bordor_type =0;


if($credit_text_positioning ==2 || $credit_text_positioning ==3)
$border_type =1;
if($allow_bordor_type == 1 && ($credit_text_positioning ==2 || $credit_text_positioning ==3))
$allow_bordor_type =0;


/*	
if($ad_type==2)
	{	
	
	mysql_query("INSERT INTO `ppc_ad_block` ( `width`, `height`,`credit_font`,`credit_text_border_color`, `ad_type`,`credit_text_alignment`,`credit_text_positioning`,`allow_publishers`,`border_type`,`credit_text_font_weight`,`credit_text_decoration`,`ad_block_name`,`credit_text`,`allow_credit_color`,`allow_bordor_type`) VALUES ( '$width','$height','$credit_text_font','$uc',$ad_type,$credit_text_alignment,$credit_text_positioning,$allow_publishers,$border_type,$credit_text_font_weight,$credit_text_decoration,'$ad_block_name','$credit_text',$allow_credit_color,$allow_bordor_type);");
		
	}
elseif($ad_type==1)
	{
	mysql_query("INSERT INTO `ppc_ad_block` ( `width`, `height`, `credit_font`,`credit_text_border_color`, `ad_type`,  `orientaion`, `title_font`, `title_size`,`title_color`,`desc_font`,`desc_size`,`desc_color`,`url_font`,`url_size`,`url_color`,`bg_color`,`no_of_text_ads`,`credit_text_alignment`,`credit_text_positioning`,`allow_publishers`,`border_type`,`credit_text_font_weight`,`credit_text_decoration`,`ad_font_weight`,ad_title_decoration,ad_desc_font_weight,`ad_desc_decoration`,`ad_disp_url_font_weight`,`ad_disp_url_decoration`,`ad_block_name`,`credit_text`,`allow_title_color`,`allow_desc_color`,`allow_disp_url_color`,`allow_bg_color`,`allow_credit_color`,`allow_bordor_type`) VALUES ( $width, $height, '$credit_text_font', '$uc', '$ad_type', $ad_orientation, '$title_font', '$ad_title_size', '$bc','$desc_font', $desc_size, '$gc','$display_font', $disp_url_size, '$tc','$dc',$no_of_text_ads,$credit_text_alignment,$credit_text_positioning,$allow_publishers,$border_type,$credit_text_font_weight,$credit_text_decoration,$ad_font_weight,$ad_title_decoration,$ad_desc_font_weight,$ad_desc_decoration,$ad_disp_url_font_weight,$ad_disp_url_decoration,'$ad_block_name','$credit_text',$allow_title_color,$allow_desc_color,$allow_disp_url_color,$allow_bg_color,$allow_credit_color,$allow_bordor_type);");
	}
else
	{

mysql_query("INSERT INTO `ppc_ad_block` ( `width`, `height`, `credit_font`,`credit_text_border_color`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`,`title_color`,`desc_font`,`desc_size`,`desc_color`,`url_font`,`url_size`,`url_color`,`bg_color`,`no_of_text_ads`,`credit_text_alignment`,`credit_text_positioning`,`allow_publishers`,`border_type`,`credit_text_font_weight`,`credit_text_decoration`,`ad_font_weight`,ad_title_decoration,ad_desc_font_weight,`ad_desc_decoration`,`ad_disp_url_font_weight`,`ad_disp_url_decoration`,`ad_block_name`,`credit_text`,`allow_title_color`,`allow_desc_color`,`allow_disp_url_color`,`allow_bg_color`,`allow_credit_color`,`allow_bordor_type`) VALUES ( $width, $height, '$credit_text_font', '$uc', $ad_type, $banner_file_max_size, $ad_orientation, '$title_font', '$ad_title_size', '$bc','$desc_font', $desc_size, '$gc','$display_font', $disp_url_size, '$tc','$dc',$no_of_text_ads,$credit_text_alignment,$credit_text_positioning,$allow_publishers,$border_type,$credit_text_font_weight,$credit_text_decoration,$ad_font_weight,$ad_title_decoration,$ad_desc_font_weight,$ad_desc_decoration,$ad_disp_url_font_weight,$ad_disp_url_decoration,'$ad_block_name','$credit_text',$allow_title_color,$allow_desc_color,$allow_disp_url_color,$allow_bg_color,$allow_credit_color,$allow_bordor_type);");

}*/

//mysql_query("INSERT INTO `ppc_ad_block`  VALUES ( '0', $width, $height,$ad_type, $banner_file_max_size,   $ad_orientation, '$title_font', '$ad_title_size', '$bc','$desc_font', $desc_size, '$gc','$display_font', $disp_url_size, '$tc', '$dc','$credit_text_font', '$uc',  $no_of_text_ads,$credit_text_alignment,$credit_text_positioning, $allow_publishers,$border_type,0, $ad_font_weight,$ad_title_decoration,$ad_desc_font_weight, $ad_desc_decoration,$ad_disp_url_font_weight,$ad_disp_url_decoration,$credit_text_font_weight,$credit_text_decoration,'$ad_block_name','$credit_text',$allow_title_color, $allow_desc_color,$allow_disp_url_color,$allow_bg_color, $allow_credit_color,$allow_bordor_type,$line_spacing);");
   if($wap_flag==1)
   {
//   	echo"hai";
//   	exit();
	
    mysql_query("INSERT INTO `wap_ad_block` ( `id`, `width`, `height`, `credit_font`,`credit_text_border_color`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`,`title_color`,`desc_font`,`desc_size`,`desc_color`,`url_font`,`url_size`,`url_color`,`bg_color`,`no_of_text_ads`,`credit_text_alignment`,`credit_text_positioning`,`allow_publishers`,`border_type`,`credit_text_font_weight`,`credit_text_decoration`,`ad_font_weight`,ad_title_decoration,ad_desc_font_weight,`ad_desc_decoration`,`ad_disp_url_font_weight`,`ad_disp_url_decoration`,`ad_block_name`,`credit_text`,`allow_title_color`,`allow_desc_color`,`allow_disp_url_color`,`allow_bg_color`,`allow_credit_color`,`allow_bordor_type`,`text_ad_type`, `catalog_size`, `no_of_catalog_ads`, `catalog_alignment`, `catalog_line_seperator`) VALUES ('0', $width, $height, '$credit_text_font', '$uc', $ad_type, $bannerid, $ad_orientation, '$title_font', '$ad_title_size', '$bc','$desc_font', $desc_size, '$gc','$display_font', $disp_url_size, '$tc','$dc',$no_of_text_ads,$credit_text_alignment,$credit_text_positioning,$allow_publishers,$border_type,$credit_text_font_weight,$credit_text_decoration,$ad_font_weight,$ad_title_decoration,$ad_desc_font_weight,$ad_desc_decoration,$ad_disp_url_font_weight,$ad_disp_url_decoration,'$ad_block_name','$credit_text',$allow_title_color,$allow_desc_color,$allow_disp_url_color,$allow_bg_color,$allow_credit_color,$allow_bordor_type,$text_ad_type,$catalog_size,$no_of_catalog_ads,$catalog_alignment,$catalog_line_seperator);");
	
    echo mysql_error();
	$result=mysql_query("select * from wap_ad_block order by id DESC");
	}
	else
	{
	
		
	mysql_query("INSERT INTO `ppc_ad_block` ( `id`, `width`, `height`, `credit_font`,`credit_text_border_color`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`,`title_color`,`desc_font`,`desc_size`,`desc_color`,`url_font`,`url_size`,`url_color`,`bg_color`,`no_of_text_ads`,`credit_text_alignment`,`credit_text_positioning`,`allow_publishers`,`border_type`,`credit_text_font_weight`,`credit_text_decoration`,`ad_font_weight`,ad_title_decoration,ad_desc_font_weight,`ad_desc_decoration`,`ad_disp_url_font_weight`,`ad_disp_url_decoration`,`ad_block_name`,`credit_text`,`allow_title_color`,`allow_desc_color`,`allow_disp_url_color`,`allow_bg_color`,`allow_credit_color`,`allow_bordor_type`,`text_ad_type`, `catalog_size`, `no_of_catalog_ads`, `catalog_alignment`, `catalog_line_seperator`) VALUES ('0', $width, $height, '$credit_text_font', '$uc', $ad_type, $bannerid, $ad_orientation, '$title_font', '$ad_title_size', '$bc','$desc_font', $desc_size, '$gc','$display_font', $disp_url_size, '$tc','$dc',$no_of_text_ads,$credit_text_alignment,$credit_text_positioning,$allow_publishers,$border_type,$credit_text_font_weight,$credit_text_decoration,$ad_font_weight,$ad_title_decoration,$ad_desc_font_weight,$ad_desc_decoration,$ad_disp_url_font_weight,$ad_disp_url_decoration,'$ad_block_name','$credit_text',$allow_title_color,$allow_desc_color,$allow_disp_url_color,$allow_bg_color,$allow_credit_color,$allow_bordor_type,$text_ad_type,$catalog_size,$no_of_catalog_ads,$catalog_alignment,$catalog_line_seperator);");

	$result=mysql_query("select * from ppc_ad_block order by id DESC");
	}
	$row=mysql_fetch_row($result);

	header("location:ppc-show-ad-block.php?id=$row[0]&wap=$wap_flag");
	exit(0);


?>