<?php include("config.inc.php");

  $scriptname=$_SERVER['SCRIPT_NAME'];
 
	   $scriptname=substr($scriptname,0,strrpos($scriptname,"/"));
	  
	   $original_url_string= "http://".$_SERVER['HTTP_HOST'].$scriptname."/";
	 //  echo $install_url; exit;
$customid=getSafePositiveInteger('id',"g",1);
$uid=$_GET['uid'];

if(isset($_GET['flag_status']))
	{
//	echo "hai";
	$flag_status=$_GET['flag_status'];
	$tab_string="wap_ad_block pad,ppc_custom_ad_block cad";
	}
else
	{
		$tab_string="ppc_ad_block pad,ppc_custom_ad_block cad";
	}
//echo $client_language;
$result=mysql_query("select pad.*,cad.*,pad.credit_text as padtext,cad.title_color as tcolor,cad.desc_color as dcolor,cad.url_color as ucolor,cad.credit_text as ctext from $tab_string  where pad.id=cad.bid and cad.id='$customid'");
$row=mysql_fetch_array($result);
//print_r($row);exit;


if($row['pid']!=0)
{
if($row['allow_bordor_type'] == 0)
$bordertype=$row['border_type'];
else
$bordertype=$row['bordor_type'];
}
else
{
$bordertype=$row['bordor_type'];
}


if($row['pid']==0)
{
	$crstr=$row['ctext'];
}
else
{
	$crstr=$row['padtext'];
}

$clan=$mysql->echo_one("select value from ppc_settings where name='client_language'");
if($clan=="")
{
	$clan='en';
	$lanid=$mysql->echo_one("select id from adserver_languages where code='en'");
}
else
{
	$lanid=$mysql->echo_one("select id from adserver_languages where code='$clan'");
}
//echo "...".$row[63];

$adtpl=$row[63];
//$adtpl=1;
//echo "select filename from ppc_ad_templates where id=".$adtpl." and status='1'"; //exit;
//echo "<br>";
//echo "select filename from ppc_ad_templates where id='".adtpl."'";
//echo "select filename from ppc_ad_templates where id=".$adtpl." and status='1'"; exit;
$tplname=$mysql->echo_one("select filename from ppc_ad_templates where id=".$adtpl." and status='1'"); 





$ct=$mysql->echo_one("select credittype from ppc_publisher_credits where id='".$crstr."'");


if($row['adlang']==0)  //anylanguages
{
	if($clan=='en')
	{   
	    if($ct==0)
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		else if($ct==1)
		{
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
        $credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';
		
		}
		
	}
	else
	{
	
	
	    if($ct==0)
	    {
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($credit_text=='')
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		}
		else if($ct==1)
		{
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($ctimage=='')
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		
		$credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';
		}
		
		
	}
}
else
{
	
		if($row['adlang']==$lanid )
		{
		
		
		if($ct==0)
	    {
				if($clan=='en')
					$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				else
					$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$row['adlang']."'");
					
		}
		else if($ct==1)
		{
		
		   if($clan=='en')
					$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		   else
					$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$row['adlang']."'");
					
			
			$credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
					
		
		}			
					
				
		}
		else
		{
		
		    if($ct==0)
	        {
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$row['adlang']."'");
			if($credit_text=='')
				$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				
			}
			else if($ct==1)
		   {
			
			$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$row['adlang']."'");
			if($ctimage=='')
			$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			
			
			$credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
			
		   }
				
		}
		if($credit_text=='')
		{
		     if($ct==0)
			 $credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			 else if($ct==1)
		     {
			 $ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			 $credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
			 }
			 
		}	 
			
		
}







if($uid==0)
{
	if(!isset($_COOKIE['inout_admin']))
	{
		exit(0);
	}
	$inout_username=$_COOKIE['inout_admin'];
	$inout_password=$_COOKIE['inout_pass'];
	if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
	{
		exit(0);
	}
}	
else
{
	include("../extended-config.inc.php");
	include("../".$GLOBALS['classes_folder']."/class.User.php");//echo "../".$classpath."class.User.php";
	//print_r( get_included_files());
	$user=new User("ppc_publishers");
	//print_r( $user);
	if(!$user->validateUser())
	{
	exit(0);
	}
	if($user->getUserID()!=$row['pid'])
	{
	exit(0);
	}
}	
//echo "......".$original_url_string; exit;
//$original_url_string
 $type=$_GET['type'];
$colspanstr="";
if($row['ad_type']==1 && $row['orientaion']==2)  // text only horiz
	$colspanstr="colspan=\"".$row['no_of_text_ads']."\"";
if($row['ad_type']==3 && $type!="image" && $row['orientaion']==2)// text/baner  showing horiz text preview
	$colspanstr="colspan=\"".$row['no_of_text_ads']."\"";

//echo $row[3];
if($uid==0)
$credittextpreview="<tr ><td class=\"inout-credit\" $colspanstr><a target=\"_blank\" href=\"$server_dir"."index.php\">$credit_text</a></td></tr>";
else
$credittextpreview="<tr ><td class=\"inout-credit\" $colspanstr><a target=\"_blank\" href=\"$server_dir"."index.php?r=$uid\">$credit_text</a></td></tr>";
	
$res=mysql_query("select * from ppc_credittext_bordercolor where id=".$row['credit_color']);
$r1=mysql_fetch_row($res);
$type=$_GET['type'];
//echo $type;//exit(0);
?><html><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo $ad_display_char_encoding; ?>" /><title>Untitled Document</title>
<style type="text/css">
.top, .bottom {display:block; background:transparent; font-size:1px;width:<?php  echo $row['width']; ?>;}
.tb1, .tb2, .tb3, .tb4 {display:block; overflow:hidden;}
.tb1, .tb2, .tb3 {<?php if($adtpl!=-1) { ?> height:0px; <?php } else { ?> height:1px;  <?php } ?>}

.tb2, .tb3, .tb4 {

<?php if($adtpl!=-1) { ?>
<?php 
if($tplname!='')
{
 ?> 


background:url("<?php echo "$original_url_string"."/ad-templates/".$adtpl."/".$tplname.""; ?>" );

<?php }  } 
else
{
?>
background:<?php if($row['credit_text_positioning']==1 && $crstr !=0) echo $r1[2]; else echo $row['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2];  } ?>;}
.tb1 {margin:0 5px; <?php if($adtpl!=-1) { ?> background:0px; <?php } else {?> background:<?php echo $r1[2]; ?>; <?php } ?> }
.tb2 {margin:0 3px; border-width:0 2px;}
.tb3 {margin:0 2px;}
.tb4 {

<?php if($adtpl!=-1) { ?> height:0px; <?php } else {?> height:2px;<?php } ?>

margin:0 1px;}
.bb1, .bb2, .bb3, .bb4 {display:block; overflow:hidden;}
.bb1, .bb2, .bb3 {height:1px;}
.bb2, .bb3, .bb4 {background:<?php if($row['credit_text_positioning']==0 && $crstr !=0) echo $r1[2];  else echo  $row['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>;}
.bb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.bb2 {margin:0 3px; border-width:0 2px;}
.bb3 {margin:0 2px;}
.bb4 {height:2px; margin:0 1px;}
.parenttable {display:block; background:#FFFFFF; border-style:solid; border-color: <?php echo $r1[2]; ?>; border-width:0 1px; padding: 0px;margin:0 0px;}


body{
padding:0;
margin:0;
}

.inout-table{
<?php 
if($row['ad_type']==2 || $type=="image")
{
	?>
	border-width: 0px;
	<?php 
}
else
{
		if($bordertype==0){ ?>
		
		<?php if($adtpl!=-1) { ?> border-left: 0px solid  
	border-right: 0px solid <?php } else { ?>
		
		
		
	border-left: 1px solid  <?php echo $r1[2]; ?>;
	border-right: 1px solid  <?php echo $r1[2]; ?>;
	<?php } ?>
	border-top-width: 0px;
	border-bottom-width: 0px;
	<?php }else {?>
	border: 1px solid  <?php echo $r1[2]; ?>;
	<?php }
	
}	
 ?>
background:<?php if($row['ad_type']==2 || $type=="image") echo "#FFFFFF";  else echo  $row['bg_color']; ?>;
padding: 0px;
margin:0 0px;
table-layout:fixed;
overflow:hidden;
line-height: <?php echo $row['line_height']; ?>px;

<?php if($adtpl!=-1) { ?>


<?php 
if($tplname!='')
{
 ?> 
background:url("<?php echo "$original_url_string"."/ad-templates/".$adtpl."/".$tplname.""; ?>" );


<?php } }?>

}
.inout-table td
{
<?php if($row['scroll_ad']==1) { ?>
 padding-left:1px;
<?php } ?>
}
.marqueetable
{
padding:0 0;
margin:0 0;
table-layout:fixed;
overflow:hidden;
line-height: <?php echo $row['line_height']; ?>px;
}

<?php 
if($row['ad_type']!=2)
{
?>
		.inout-title a:link,.inout-title a:visited,.inout-title a:hover,.inout-title a:active,.inout-title a:focus
		{
		font-family:<?php echo $row['title_font'];?>;
		font-size:<?php echo $row['title_size'];?>;
		color:<?php echo $row['tcolor'];?>;
		
		<?php
		if($row['ad_font_weight']==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($row['ad_title_decoration']==3)
		$deco="blink";
		elseif($row['ad_title_decoration']==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		padding:0px;
		}
		
		
		.inout-desc 
		{
		font-family:<?php echo $row['desc_font'];?>;
		font-size:<?php echo $row['desc_size'];?>;
		color:<?php echo $row['dcolor'];?>;
		<?php
		if($row['ad_desc_font_weight']==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($row['ad_desc_decoration']==3)
		$deco="blink";
		elseif($row['ad_desc_decoration']==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		padding:0px;
		}
		
		
		.inout-url a:link,.inout-url a:visited,.inout-url a:hover,.inout-url a:active,.inout-url a:focus
		{
		font-family:<?php echo $row['url_font'];?>;
		font-size:<?php echo $row['url_size'];?>;
		color:<?php echo $row['ucolor'];?>;
		<?php
		if($row['ad_disp_url_font_weight']==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($row['ad_disp_url_decoration']==3)
		$deco="blink";
		elseif($row['ad_disp_url_decoration']==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		 white-space:nowrap;
		padding:0px;
		}

<?php 
}
?>




.inout_credit_over
{
font-size:12px;
height:15px;
/*background-image:url(images/tabs.png);*/
background-color:<?php echo $r1[2]; ?>;
background-repeat:repeat;
color:<?php echo $r1[1]; ?>;
<?php
if($row['credit_text_positioning']==1)  // Top
{
?>
position:absolute;
top:0px;
<?php
}
?>
<?php
if($row['credit_text_positioning']==0)  // Bottom
{
?>
position:absolute;
bottom:0px;
<?php
}
?>
<?php
if($row['credit_text_alignment']==1)  // Right
{
?>
right:0px;
<?php
}
?>
<?php
if($row['credit_text_alignment']==0)  // Left
{
?>
left:0px;
<?php
}
?>

}
.inout_credit_data
{
overflow:hidden;
max-width:<?php echo $row['width']; ?>px;

<?php
if($row['credit_text_positioning']==1)  // Top
{
?>
position:absolute;
top:0px;
<?php
}
?>
<?php
if($row['credit_text_positioning']==0)  // Bottom
{
?>
position:absolute;
bottom:0px;
<?php
}
?>
<?php
if($row['credit_text_alignment']==1)  // Right
{
?>
right:0px;
<?php
}
?>
<?php
if($row['credit_text_alignment']==0)  // Left
{
?>
left:0px;
<?php
}
?>




}


.inout-before-credit
{
table-layout:fixed;
overflow:hidden;
/*width:<?php  echo $row['width']; ?>px;*/



}



.inout-credit
{
/*width:auto;*/

height:15px;

<?php
if($ct ==0 && $row['credit_text_positioning']!=2 && $row['credit_text_positioning']!=3)
{
?>
max-width:<?php  echo $row['width']-10; ?>px;
background-color:<?php echo $r1[2]; ?>;
padding:0 5;
<?php
}
else if($ct ==1 && $row['credit_text_positioning']!=2 && $row['credit_text_positioning']!=3)
{
?>
max-width:<?php  echo $row['width']; ?>px;
padding:0 0;
<?php
}
?>

<?php
if($ct ==0 && ($row['credit_text_positioning']==2 || $row['credit_text_positioning']==3))
{
?>


background-color:<?php echo $r1[2]; ?>;
width:<?php echo $singlewidth-10; ?>px;
padding:0 5;
overflow:hidden;
<?php
}
else if($ct ==1 && ($row['credit_text_positioning']==2 || $row['credit_text_positioning']==3))
{
?>
width:<?php echo $singlewidth; ?>px;
overflow:hidden;
padding:0 0;
<?php
}
?>














white-space:nowrap;

margin:0px;
<?php
if($row['credit_text_alignment']==0)
$t_align="left";
else
$t_align="right";	
?>
text-align:<?php echo $t_align; ?>;
}
.inout-credit a:link,.inout-credit a:visited,.inout-credit a:hover,.inout-credit a:active,.inout-credit a:focus
{
color:<?php echo $r1[1]; ?>;

font-family:<?php echo $row['credit_font'];?>;
font-size:12px;
line-height: 15px;
<?php
if($row['credit_text_font_weight']==2)
$f_weight="bold";
else
$f_weight="normal";	
?>
font-weight:<?php echo $f_weight; ?>;
<?php 
if($row['credit_text_decoration']==3)
$deco="blink";
elseif($row['credit_text_decoration']==1)
$deco="none";	
else
$deco="underline";		
?>
text-decoration:<?php echo $deco; ?>;
}
</style>
</head><body><div><?php  
if($row['ad_type']==2 || $type=="image")
{
		?><table height="<?php echo $row['height'];  ?>"  width="<?php  echo $row['width']; ?>" cellpadding="0" cellspacing="0" class="inout-table"><?php
		//if($row['credit_text_positioning']==1 && $crstr !=0)
		//	echo $credittextpreview;
		?>
		
		<tr ><td style="background: url(../images/banner.gif) repeat; height:<?php echo $row['height']; ?>px;">
		
		<?php
		if($row['credit_text_positioning']==1 && $crstr!="")  
				{
				?>  
				<div id="idiv" class="inout_credit_over" onMouseOver="LoadCreditText()">(i)</div><div class="inout_credit_data" id="crd" style="display:none;" onMouseOut="DisableCreditText()" ><table cellpadding="0" cellspacing="0" class="inout-before-credit" ><?php echo $credittextpreview;?></table></div>
				
				<?php
				}	
		
		?>
			<?php
		if($row['credit_text_positioning']==0 && $crstr!="")  
				{
				?>  
				<div id="idiv" class="inout_credit_over" onMouseOver="LoadCreditText()">(i)</div><div class="inout_credit_data" id="crd" style="display:none;" onMouseOut="DisableCreditText()" ><table cellpadding="0" cellspacing="0" class="inout-before-credit"><?php echo $credittextpreview;?></table></div>
				
				<?php
				}		
		
		?>
		
		
		
		
		
		
		
		
		</td></tr><?php	
		//if($row['credit_text_positioning']==0 && $crstr !=0)
		//	echo $credittextpreview;
		?></table><?php			
}
else if($row['ad_type']==4 || $type=="catalog")
{
$blockheight= $row['height'];

		if($bordertype==0)
		{
		?><b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b><?php
		$blockheight= $row['height']-10;
		}
		//$blockheight_without_credit=$blockheight;
		//if($crstr!=0)  $blockheight+=15;
		?><table height="<?php  echo $blockheight; ?>px"  width="<?php  echo  $row['width']; ?>px" cellpadding="0" cellspacing="0" class="inout-table"><?php 
		
		$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='".$row['catalog_size']."'");
		$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='".$row['catalog_size']."'");
	//	echo $catalog_width."dddddddd".$row[41];
		if($row['orientaion']==2)//horizontal
			{
			//echo $catalog_width."ssss";
				
				if($row['credit_text_positioning']==1 && $crstr !=0)
					echo "<tr ><td  colspan=\"".$row['no_of_catalog_ads']."\" class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$credit_text</a></td></tr>";
				
				?><tr><?php
					for($i=0;$i<$row['no_of_catalog_ads'];$i++)
					{
					?><td ><?php 
					if($row['catalog_alignment']==1)
					 { 
					 ?><table cellpadding="5" cellspacing="0" height="100%" border=0  style="line-height: <?php echo $row['line_height']; ?>px;table-layout:fixed;overflow:hidden;<?php if($row['catalog_line_seperator']==1 && $i < $row['no_of_catalog_ads']-1 ){ ?>border-right:solid 1px <?php echo $r1[2]; ?>; <?php } ?>"><tr><td align="center" style="width:<?php echo $catalog_width; ?>px;vertical-align:top;"><div style="height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td><td align="left" valign="top"><div class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></div><div class="inout-desc"><?php echo $ad_description; ?></div></td></tr></table><?php 
					 } else 
					 {
					  ?><table cellpadding="5" height="100%" cellspacing="0" border=0  style="line-height: <?php echo $row['line_height']; ?>px;table-layout:fixed;overflow:hidden;<?php if($row['catalog_line_seperator']==1 && $i < $row['no_of_catalog_ads']-1) { ?> border-right:solid 1px <?php echo $r1[2]; ?>; <?php } ?>"><tr><td align="center" style="height:<?php echo $catalog_height; ?>px;vertical-align:top;"><div style="height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td></tr><tr><td valign="top"><div class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></div><div class="inout-desc"><?php echo $ad_description; ?></div></td></tr></table><?php 
					  } 
					  ?></td><?php
					   }
					   ?></tr><?php 
				if($row['credit_text_positioning']==0 && $crstr !=0)
					echo "<tr ><td  colspan=\"".$row['no_of_catalog_ads']."\" class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$credit_text</a></td></tr>";
			}
		else //vertical
			{
				if($row['credit_text_positioning']==1 && $crstr !=0)
						echo $credittextpreview;
				for($i=0;$i<$row['no_of_catalog_ads'];$i++)
					{
				?><tr><td ><?php 
				if($row['catalog_alignment']==1) 
				{ 
				?><table  cellpadding="5"  cellspacing="0" border=0  style="line-height: <?php echo $row['line_height']; ?>px;table-layout:fixed;overflow:hidden;<?php if($row['catalog_line_seperator']==1  && $i < $row['no_of_catalog_ads']-1) { ?> border-bottom:solid 1px <?php echo $r1[2]; ?>;<?php } ?>"><tr><td align="center" style="width:<?php echo $catalog_width; ?>px;vertical-align:top;"><div style=" height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td><td align="left" valign="top"><div class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></div><div class="inout-desc"><?php echo $ad_description; ?></div></td></tr></table><?php 
				} 
				else 
				{ 
				?><table cellpadding="5"  cellspacing="0" border=0 style="line-height: <?php echo $row['line_height']; ?>px;table-layout:fixed;overflow:hidden;<?php if($row['catalog_line_seperator']==1  && $i < $row['no_of_catalog_ads']-1) { ?> border-bottom:solid 1px <?php echo $r1[2]; ?>; <?php } ?>"><tr><td align="center" style="height:<?php echo $catalog_height; ?>px;vertical-align:top;"><div style="height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td></tr><tr><td valign="top"><div class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></div><div class="inout-desc"><?php echo $ad_description; ?></div></td></tr></table><?php 				}
				 ?></td></tr><?php
				}
				if($row['credit_text_positioning']==0 && $crstr !=0)
						echo $credittextpreview;
			}
			
		?></table><?php  
		if($bordertype==0)
		{?><b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b><?php
		}


}

else
{

$singleflag=0;
	if($row['ad_type']==1 && $row['text_ad_type'] ==2)
				{
				$singleflag=1;
				$singlewidth=$row['width']/($row['no_of_text_ads']+1);
				
				}




		$blockheight= $row['height'];

		if($bordertype==0)
		{
		?><b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b><?php
		$blockheight= $row['height']-10;
		}
		$marqueeheight=$blockheight;
		?><table height="<?php  echo $blockheight;  ?>"  width="<?php  echo $row['width']; ?>" cellpadding="<?php  /*if($row['scroll_ad']==0) echo "5"; else */ echo "0";  ?>" cellspacing="0" class="inout-table"><?php 
		$textadblock="";
		$textadblockstart="";
		$textadblockend="";
		
		if($row['orientaion']==2)//horizontal
			{
				if($row['credit_text_positioning']==1 && $crstr !=0)
					$textadblockstart.=$credittextpreview;
				if($row['scroll_ad']==1)
				{
					$textadblockstart.="<tr><td  $colspanstr>";
					$textadblock.= "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\"  height=$marqueeheight width=".$row['width']." class=\"marqueetable\">";
				}
				$textadblock.="<tr>";
				
				?>
				<?php
				
				if($singleflag==1 && $row['credit_text_positioning']==2 && $crstr !=0)
				{
				
				if($uid==0)
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$server_dir.'index.php">'.$credit_text.'</a></td>';
				else
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$server_dir.'index.php??r='.$uid.'">'.$credit_text.'</a></td>';
				
				}
				
				
				for($i=0;$i<$row['no_of_text_ads'];$i++)
					{
						$textadblock.='<td valign="top"><div class="inout-title"><a href="#" target="_blank">'.$ad_title.'</a></div>';
						if($row['text_ad_type']!=2)
							{
							$textadblock.='<div class="inout-desc">'.$ad_description.'</div>'; 
							}

						 if($row['text_ad_type']==1)
							{
												$textadblock.='<div class="inout-url"><a href="#" target="_blank">'.$ad_display_url.'</a></div>';
							} 
								
						$textadblock.='</td>';
					 } 
					 
				if($singleflag==1 && $row['credit_text_positioning']==3 && $crstr !=0)
				{
				
				if($uid==0)
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$server_dir.'index.php">'.$credit_text.'</a></td>';
				else
				$textadblock.='<td class="inout-credit" ><a target="_blank" href="'.$server_dir.'index.php??r='.$uid.'">'.$credit_text.'</a></td>';
				
				} 
					 
				$textadblock.='</tr>';
				if($row['scroll_ad']==1)
				{
					$textadblock.= "</table>";
					$textadblockend="</td></tr>";
				}
				 
				if($row['credit_text_positioning']==0 && $crstr !=0)
					$textadblockend.=$credittextpreview;
			}
		else //vertical
			{
				if($row['credit_text_positioning']==1  && $crstr !=0)
					$textadblockstart.=$credittextpreview;
				if($row['scroll_ad']==1)
				{
					$textadblockstart.="<tr><td>";
					$textadblock.= "<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\"  height=$marqueeheight width=".$row['width']." class=\"marqueetable\">";
				}
				for($i=0;$i<$row['no_of_text_ads'];$i++)
				{
					$textadblock.= '<tr><td ><div class="inout-title"><a href="#" target="_blank">'.$ad_title.'</a></div>';
				
				 if($row['text_ad_type']!=2)
					{
					 $textadblock.= '<div class="inout-desc">'.$ad_description.'</div>'; 
				 	}
				 
				  if($row['text_ad_type']==1)
					{
							 $textadblock.= '<div class="inout-url"><a href="#" target="_blank">'.$ad_display_url.'</a></div>'; 
					}
				
				 $textadblock.= '</td></tr>'; 
				
				}
								if($row['scroll_ad']==1)
				{
					$textadblock.= "</table>";
					$textadblockend.= "</td></tr>";
				}

				if($row['credit_text_positioning']==0 && $crstr !=0)
					$textadblockend.=$credittextpreview;
			}
			


if($row['scroll_ad']==1)
{
	echo $textadblockstart;
?><script language="JavaScript1.2">

/*
Cross browser Marquee script- ? Dynamic Drive (www.dynamicdrive.com)
For full source code, 100's more DHTML scripts, and Terms Of Use, visit http://www.dynamicdrive.com
Modified by jscheuer1 for continuous content. Credit MUST stay intact
*/

//Specify the marquee's width (in pixels)
var marqueewidth="<?php echo $row['width']-4; ?>px"
//Specify the marquee's height
var marqueeheight="<?php echo $marqueeheight-2 ; ?>px"
//Specify the marquee's marquee speed (larger is faster 1-10)
var marqueespeed=1
//Specify initial pause before scrolling in milliseconds
var initPause=0
//Specify start with Full(1)or Empty(0) Marquee
var full=0
//configure background color:
var marqueebgcolor="<?php if($row['ad_type']==2 || $type=="image") echo "#FFFFFF";  else echo  $row['bg_color']; ?>"
//Pause marquee onMousever (0=no. 1=yes)?
var pauseit=1

//Specify the marquee's content (don't delete <nobr> tag)
//Keep all content on ONE line, and backslash any single quotations (ie: that\'s great):

var marqueecontent='<nobr><?php echo $textadblock;	?></nobr>'
</script><?php
	if($row['orientaion']==2)//horizontal
	{
	
?><script language="JavaScript1.2">	
////NO NEED TO EDIT BELOW THIS LINE////////////
var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var iedom=document.all||document.getElementById
if (iedom)
document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px">'+marqueecontent+'</span>')
var actualwidth=''
var cross_marquee, cross_marquee2, ns_marquee
function populate(){
if (iedom){
var initFill=(full==1)? '8px' : parseInt(marqueewidth)+8+"px"
actualwidth=document.all? temp.offsetWidth : document.getElementById("temp").offsetWidth
cross_marquee=document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee
cross_marquee.style.left=initFill
cross_marquee2=document.getElementById? document.getElementById("iemarquee2") : document.all.iemarquee2
cross_marquee2.innerHTML=cross_marquee.innerHTML=marqueecontent
cross_marquee2.style.left=(parseInt(cross_marquee.style.left)+actualwidth+8)+"px" //indicates following #1
}
else if (document.layers){
ns_marquee=document.ns_marquee.document.ns_marquee2
ns_marquee.left=parseInt(marqueewidth)+8
ns_marquee.document.write(marqueecontent)
ns_marquee.document.close()
actualwidth=ns_marquee.document.width
}
setTimeout('lefttime=setInterval("scrollmarquee()",30)',initPause)
}
window.onload=populate

function scrollmarquee(){
if (iedom){
if (parseInt(cross_marquee.style.left)<(actualwidth*(-1)+8))
cross_marquee.style.left=(parseInt(cross_marquee2.style.left)+actualwidth+8)+"px"
if (parseInt(cross_marquee2.style.left)<(actualwidth*(-1)+8))
cross_marquee2.style.left=(parseInt(cross_marquee.style.left)+actualwidth+8)+"px"
cross_marquee2.style.left=parseInt(cross_marquee2.style.left)-copyspeed+"px"
cross_marquee.style.left=parseInt(cross_marquee.style.left)-copyspeed+"px"
}
else if (document.layers){
if (ns_marquee.left>(actualwidth*(-1)+8))
ns_marquee.left-=copyspeed
else
ns_marquee.left=parseInt(marqueewidth)+8
}
}

</script><?php
			}
			else
			{
?><script language="JavaScript1.2">

////NO NEED TO EDIT BELOW THIS LINE////////////
var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var iedom=document.all||document.getElementById
if (iedom)
document.write('<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px">'+marqueecontent+'</span>')
var actualheight=''
var cross_marquee, cross_marquee2, ns_marquee
function populate(){
if (iedom){
var initFill=(full==1)? '8px' : parseInt(marqueeheight)+8+"px"
actualheight=document.all? temp.offsetHeight : document.getElementById("temp").offsetHeight
cross_marquee=document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee
cross_marquee.style.top=initFill
cross_marquee2=document.getElementById? document.getElementById("iemarquee2") : document.all.iemarquee2
cross_marquee2.innerHTML=cross_marquee.innerHTML=marqueecontent
cross_marquee2.style.top=(parseInt(cross_marquee.style.top)+actualheight+8)+"px" //indicates following #1
}
else if (document.layers){
ns_marquee=document.ns_marquee.document.ns_marquee2
ns_marquee.top=parseInt(marqueeheight)+8
ns_marquee.document.write(marqueecontent)
ns_marquee.document.close()
actualheight=ns_marquee.document.height
}
setTimeout('lefttime=setInterval("scrollmarquee()",30)',initPause)
}
window.onload=populate

function scrollmarquee(){
if (iedom){
if (parseInt(cross_marquee.style.top)<(actualheight*(-1)+8))
cross_marquee.style.top=(parseInt(cross_marquee2.style.top)+actualheight+8)+"px"
if (parseInt(cross_marquee2.style.top)<(actualheight*(-1)+8))
cross_marquee2.style.top=(parseInt(cross_marquee.style.top)+actualheight+8)+"px"
cross_marquee2.style.top=parseInt(cross_marquee2.style.top)-copyspeed+"px"
cross_marquee.style.top=parseInt(cross_marquee.style.top)-copyspeed+"px"
}
else if (document.layers){
if (ns_marquee.top>(actualheight*(-1)+8))
ns_marquee.top-=copyspeed
else
ns_marquee.top=parseInt(marqueeheight)+8
}
}

</script><?php			
			}?><script language="JavaScript1.2">
			
			if (iedom||document.layers){
with (document){
document.write('<table border="0" cellspacing="0" cellpadding="0" ><td>')
if (iedom){
write('<div style="position:relative;width:'+marqueewidth+';height:'+marqueeheight+';overflow:hidden">')
write('<div style="position:absolute;width:'+marqueewidth+';height:'+marqueeheight+';background-color:'+marqueebgcolor+'" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">')
write('<div id="iemarquee" style="position:absolute;left:0px;top:0px;display:inline;"></div>')
write('<div id="iemarquee2" style="position:absolute;left:0px;top:0px;display:inline;"></div>')
write('</div></div>')
}
else if (document.layers){
write('<ilayer width='+marqueewidth+' height='+marqueeheight+' name="ns_marquee" bgColor='+marqueebgcolor+'>')
write('<layer name="ns_marquee2" left=0 top=0 onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed"></layer>')
write('</ilayer>')
}
document.write('</td></table>')
}
}</script><?php
			echo $textadblockend; 
		}
		else
			echo $textadblockstart.$textadblock.$textadblockend;	
		?></table><?php  
		if($bordertype==0)
		{?>
		<b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b>
		<?php
		}

}
?></div></body></html>
<script language="javascript" type="text/javascript">
function LoadCreditText()
{
document.getElementById("idiv").style.display="none";
document.getElementById("crd").style.display="";

}
function DisableCreditText()
{
document.getElementById("idiv").style.display="";
document.getElementById("crd").style.display="none";

}
</script>