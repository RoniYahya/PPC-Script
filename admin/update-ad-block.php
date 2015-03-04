<?php 
//print_r($_POST);
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

$id=trim($_POST['id']);


if(!isset($_POST['text_ad_type']))
	{
	$text_ad_type=1;
	
	}
else
	{
	$text_ad_type=$_POST['text_ad_type'];
	
	}

	
	if(isset($_POST['wap']) && $_POST['wap']==1)
	{
	$wap_flag=trim($_POST['wap']);
	$ad_title_name="wap_ad_title";
	$ad_desc_name="wap_ad_description";
	$ad_dispurl_name="wap_ad_display_url";
	 $wapdev=$mysql->echo_one("select count(*) from ppc_custom_ad_block where bid='$id' and wapstatus=1");
	}
else
	{
	$ad_title_name="ad_title";
	$ad_desc_name="ad_description";
	$ad_dispurl_name="ad_display_url";	
	$wap_flag=0;
	 $wapdev=$mysql->echo_one("select count(*) from ppc_custom_ad_block where bid='$id' and wapstatus=0");
	}

	if($script_mode=="demo")
	{ 
		if($id<=26 && $wap_flag==0 )
		{
		include_once("admin.header.inc.php");
		echo "<br><span class=\"already\">You cannot edit this block in demo. <a href=\"javascript:history.back(-1)\">Go Back</a></span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
		}

		if($id<=7 && $wap_flag==1 )
		{
		include_once("admin.header.inc.php");
		echo "<br><span class=\"already\">You cannot edit this block in demo. <a href=\"javascript:history.back(-1)\">Go Back</a></span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
		}
	}

	
	if($wap_flag==1)
	{
		$wap_table='wap_ad_block';
	}
	else
	{
		$wap_table='ppc_ad_block';
	}
$result=mysql_query("select ad_block_name,width,height,max_size,line_height,catalog_size,no_of_catalog_ads,catalog_alignment,catalog_line_seperator from $wap_table where id='$id'");
$row=mysql_fetch_row($result);
//print_r($row);
$ad_block_name=trim($_POST['ad_block_name']);
phpSafe($ad_block_name);

if($ad_block_name=="")
$ad_block_name=$row[0];

$ad_type=trim($_POST['ad_type']);



$bannerid=0;
if( $ad_type==2  ||  $ad_type==3)
{
$bannerid=$_POST['banner_size1'];
phpsafe($bannerid);
$banner_dime=mysql_query("select * from banner_dimension where id='$bannerid'");
$banner_dime1=mysql_fetch_row($banner_dime);
$width=$banner_dime1[1];
$height=$banner_dime1[2];

}
else
{
$width=trim($_POST['width']);
if(!isPositiveInteger($width))
$width=$row[1];
$height=trim($_POST['height']);
if(!isPositiveInteger($height))
$height=$row[2];
}




if($ad_type!=6&&$ad_type!=7)
{

/*
$allow_publishers=trim($_POST['allow_publishers']);

$banner_file_max_size=trim($_POST['banner_file_max_size']);
if(!isPositiveInteger($banner_file_max_size))
$banner_file_max_size=$row[3];

$no_of_text_ads=trim($_POST['no_of_text_ads']);
$ad_orientation=$_POST['ad_orientation'];
*/
	 

if($ad_type==2||$ad_type==3)
{
	if($wapdev==0)
	{
	 $banner_file_max_size=$bannerid;
	}
	else
	{
		$banner_file_max_size=$row[3];
	}
 //$filesize=$mysql->echo_one("select file_size from banner_dimension where id=$row[3]");

//if(!isPositiveInteger($banner_file_max_size))
//echo $banner_file_max_size=$filesize;

}
else
{
	$banner_file_max_size=$row[3];
}
$allow_publishers=trim($_POST['allow_publishers']);
$no_of_text_ads=trim($_POST['no_of_text_ads']);

$catalog_line_seperator=$_POST['catalog_line_seperator'];
$catalog_alignment=$_POST['catalog_alignment'];
$no_of_catalog_ads=$_POST['no_of_catalog_ads'];
$catalog_size=$_POST['catalog_size'];
$ad_orientation=$_POST['ad_orientation'];


}
else
{
$no_of_text_ads=1;
$banner_file_max_size=0;
$allow_publishers=1;

if($ad_type ==6)
$ad_orientation=2;
else
if($ad_type ==7)
$ad_orientation=1;

//catalog

$catalog_size=0;
$no_of_catalog_ads=1;
$catalog_alignment=1;
$catalog_line_seperator=0;

//end catalog



}


$title_font=trim($_POST['title_font']);
$ad_title_font=trim($_POST['ad_title_font']);
$ad_font_weight=$_POST['ad_title_font_weight'];
$ad_title_decoration=$_POST['ad_title_decoration'];
//$bc=$_POST['bc'];

$desc_font=$_POST['desc_font'];
$desc_size=$_POST['desc_size'];
$ad_desc_font_weight=$_POST['ad_desc_font_weight'];
$ad_desc_decoration=$_POST['ad_desc_decoration'];
//$gc=$_POST['gc'];

$display_font=$_POST['display_font'];
$disp_url_size=$_POST['disp_url_size'];
$ad_disp_url_font_weight=$_POST['ad_disp_url_font_weight'];
$ad_disp_url_decoration=$_POST['ad_disp_url_decoration'];
//$tc=$_POST['tc'];

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
//$dc=$_POST['dc'];
$line_spacing=$_POST['line_spacing'];
if(!isPositiveInteger($line_spacing))
$line_spacing=$row[4];


//$credit_text=$_POST['credit_text'];
$credit_text=$_POST['cc'];

/*if($client_language=='en')
{
$credit_text=$_POST['credit_text'];	
}
else
{
$parent_id=mysql_query("select parent_id from ppc_publisher_credits where id='$credit_text'");
$parent_id1=mysql_fetch_row($parent_id);
$credit_text=$parent_id1[0];	
}*/

$credit_text_font=$_POST['credit_text_font'];
$credit_text_font_weight=$_POST['credit_text_font_weight'];
$credit_text_decoration=$_POST['credit_text_decoration'];

$uc=$_POST['uc'];
$credit_text_positioning=trim($_POST['credit_text_positioning']);
$credit_text_alignment=trim($_POST['credit_text_alignment']);




$border_type=trim($_POST['border_type']);







//catalog
if(isset($_POST['catalog_size']))
	{
//	echo "value set";
		$catalog_size=$_POST['catalog_size'];
		$no_of_catalog_ads=$_POST['no_of_catalog_ads'];
		$catalog_alignment=$_POST['catalog_alignment'];
		$catalog_line_seperator=$_POST['catalog_line_seperator'];
	}
else
	{
	//echo "value un set...............";
	$catalog_size=$row[5];
		$no_of_catalog_ads=$row[6];
		$catalog_alignment=$row[7];
		$catalog_line_seperator=$row[8];
	
	}

//end catalog

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




if((isset($_POST['ad_description']))&&(isset($_POST['ad_title']))&&(isset($_POST['ad_display_url'])))
	{
	$ad_title=trim($_POST['ad_title']);
	$d_description=trim($_POST['ad_description']);
	$d_display_url=trim($_POST['ad_display_url']);
	phpSafe($ad_title);
	phpSafe($d_description);
	phpSafe($d_display_url);
	if($ad_title=="")
	$ad_title="This is a dummy ad title";		
	if($d_description=="")
	$d_description="This is a dummy ad description for the dummy ad.";		
	if($d_display_url=="")
	$d_display_url="DummyAdDisplayUrl";		
	mysql_query("update `ppc_settings` set value='$ad_title' where name='$ad_title_name';");
	mysql_query("update `ppc_settings` set value='$d_description' where name='$ad_desc_name';");
	mysql_query("update `ppc_settings` set value='$d_display_url' where name='$ad_dispurl_name';");
	}
	if( $ad_type==2  ||  $ad_type==3)
	{
	
	
	
	//echo "update banner_dimension set file_size='$banner_file_max_size' where id='$row[3]'";
//		//mysql_query("update banner_dimension set file_size='$banner_file_max_size' where id='$row[3]'");
//echo "update `$wap_table` set width=$width, height=$height, credit_font='$credit_text_font', credit_text_border_color='$uc', ad_type=$ad_type, max_size=$row[3], orientaion=$ad_orientation, title_font='$title_font', title_size='$ad_title_font', title_color='$bc',desc_font='$desc_font', desc_size=$desc_size,desc_color='$gc', url_font='$display_font', url_size=$disp_url_size, url_color='$tc' , bg_color='$dc' , no_of_text_ads=$no_of_text_ads, credit_text_alignment=$credit_text_alignment, credit_text_positioning=$credit_text_positioning, allow_publishers=$allow_publishers,border_type=$border_type, credit_text_font_weight=$credit_text_font_weight, credit_text_decoration=$credit_text_decoration , ad_font_weight=$ad_font_weight, ad_title_decoration=$ad_title_decoration, ad_desc_font_weight=$ad_desc_font_weight, ad_desc_decoration=$ad_desc_decoration, ad_disp_url_font_weight=$ad_disp_url_font_weight, ad_disp_url_decoration=$ad_disp_url_decoration,ad_block_name='$ad_block_name',credit_text='$credit_text',allow_title_color=$allow_title_color,allow_desc_color=$allow_desc_color,allow_disp_url_color=$allow_disp_url_color,allow_bg_color=$allow_bg_color,allow_credit_color=$allow_credit_color,allow_bordor_type=$allow_bordor_type,text_ad_type=$text_ad_type,line_height=$line_spacing,catalog_size=$catalog_size,no_of_catalog_ads=$no_of_catalog_ads,catalog_alignment=$catalog_alignment,catalog_line_seperator=$catalog_line_seperator where id=$id;";
	mysql_query("update `$wap_table` set width=$width, height=$height, credit_font='$credit_text_font', credit_text_border_color='$uc', ad_type=$ad_type, max_size=$banner_file_max_size, orientaion=$ad_orientation, title_font='$title_font', title_size='$ad_title_font', title_color='$bc',desc_font='$desc_font', desc_size=$desc_size,desc_color='$gc', url_font='$display_font', url_size=$disp_url_size, url_color='$tc' , bg_color='$dc' , no_of_text_ads=$no_of_text_ads, credit_text_alignment=$credit_text_alignment, credit_text_positioning=$credit_text_positioning, allow_publishers=$allow_publishers,border_type=$border_type, credit_text_font_weight=$credit_text_font_weight, credit_text_decoration=$credit_text_decoration , ad_font_weight=$ad_font_weight, ad_title_decoration=$ad_title_decoration, ad_desc_font_weight=$ad_desc_font_weight, ad_desc_decoration=$ad_desc_decoration, ad_disp_url_font_weight=$ad_disp_url_font_weight, ad_disp_url_decoration=$ad_disp_url_decoration,ad_block_name='$ad_block_name',credit_text='$credit_text',allow_title_color=$allow_title_color,allow_desc_color=$allow_desc_color,allow_disp_url_color=$allow_disp_url_color,allow_bg_color=$allow_bg_color,allow_credit_color=$allow_credit_color,allow_bordor_type=$allow_bordor_type,text_ad_type=$text_ad_type,line_height=$line_spacing,catalog_size=$catalog_size,no_of_catalog_ads=$no_of_catalog_ads,catalog_alignment=$catalog_alignment,catalog_line_seperator=$catalog_line_seperator where id=$id;");
	}
	else
	{
		
mysql_query("update `$wap_table` set width=$width, height=$height, credit_font='$credit_text_font', credit_text_border_color='$uc', ad_type=$ad_type, max_size=$banner_file_max_size, orientaion=$ad_orientation, title_font='$title_font', title_size='$ad_title_font', title_color='$bc',desc_font='$desc_font', desc_size=$desc_size,desc_color='$gc', url_font='$display_font', url_size=$disp_url_size, url_color='$tc' , bg_color='$dc' , no_of_text_ads=$no_of_text_ads, credit_text_alignment=$credit_text_alignment, credit_text_positioning=$credit_text_positioning, allow_publishers=$allow_publishers,border_type=$border_type, credit_text_font_weight=$credit_text_font_weight, credit_text_decoration=$credit_text_decoration , ad_font_weight=$ad_font_weight, ad_title_decoration=$ad_title_decoration, ad_desc_font_weight=$ad_desc_font_weight, ad_desc_decoration=$ad_desc_decoration, ad_disp_url_font_weight=$ad_disp_url_font_weight, ad_disp_url_decoration=$ad_disp_url_decoration,ad_block_name='$ad_block_name',credit_text='$credit_text',allow_title_color=$allow_title_color,allow_desc_color=$allow_desc_color,allow_disp_url_color=$allow_disp_url_color,allow_bg_color=$allow_bg_color,allow_credit_color=$allow_credit_color,allow_bordor_type=$allow_bordor_type,text_ad_type=$text_ad_type,line_height=$line_spacing,catalog_size=$catalog_size,no_of_catalog_ads=$no_of_catalog_ads,catalog_alignment=$catalog_alignment,catalog_line_seperator=$catalog_line_seperator where id=$id;");
	}


if($ad_type==6||$ad_type==7)
{
header("location:ppc-show-inline-ad-blocks.php?id=$id");
//echo mysql_error();
exit(0);

}
else

{

header("location:ppc-show-ad-block.php?id=$id&wap=$wap_flag");
exit(0);
}
?>