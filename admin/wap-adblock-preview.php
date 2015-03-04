<?php include("config.inc.php");
if(!isset($_COOKIE['inout_admin']))
{
//header("Location:index.php");
exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
	{
	//header("Location:index.php");
	exit(0);
	}

$customid=$_GET['id'];
phpsafe($customid);
$result=mysql_query("select * from wap_ad_block where id='$customid'");
	$row=mysql_fetch_array($result);
if(!$row)
{
	exit(0);
}	
 loadsettings("wap");
		$ad_title=$GLOBALS['wap_ad_title'];
		$d_description=$GLOBALS['wap_ad_description'];
		$ad_display_url=$GLOBALS['wap_ad_display_url'];
		
$res=mysql_query("select * from ppc_credittext_bordercolor where id=$row[17]");
$r1=mysql_fetch_row($res);
$type=$_GET['type'];




$ct=$mysql->echo_one("select credittype from ppc_publisher_credits where id='$row[33]'");
if($ct==0)
$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where id='$row[33]'");
else if($ct==1)
{
 $ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where id='$row[33]'");
 $ctext='<img src="../credit-image/'.$row[33].'/'.$ctimage.'" border="0" height="15px"/>';
}
 		






//echo $type;exit(0);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=<?php echo $ad_display_char_encoding; ?>" /><title>Untitled Document</title>
<style type="text/css">

.top, .bottom {display:block; background:transparent; font-size:1px;width:<?php  echo $row[1]; ?>;}
.tb1, .tb2, .tb3, .tb4 {display:block; overflow:hidden;}
.tb1, .tb2, .tb3 {height:1px;}
.tb2, .tb3, .tb4 {background:<?php if($row[20]==1 && $row[33] !=0) echo $r1[2]; else echo $row[15]; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>;}
.tb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.tb2 {margin:0 3px; border-width:0 2px;}
.tb3 {margin:0 2px;}
.tb4 {height:2px; margin:0 1px;}
.bb1, .bb2, .bb3, .bb4 {display:block; overflow:hidden;}
.bb1, .bb2, .bb3 {height:1px;}
.bb2, .bb3, .bb4 {background:<?php if($row[20]==0 && $row[33] !=0) echo $r1[2];  else echo  $row[15]; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>;}
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
if($row[3]==2 || $type=="image")
{
	?>
	border-width: 0px;
	<?php 
}
else
{
		if($row[22]==0){ ?>
	border-left: 1px solid  <?php echo $r1[2]; ?>;
	border-right: 1px solid  <?php echo $r1[2]; ?>;
	border-top-width:: 0px;
	border-bottom-width:: 0px;
	<?php }else {?>
	border: 1px solid  <?php echo $r1[2]; ?>;
	<?php }
	
}	
 ?>
background:<?php if($row[3]==2 || $type=="image") echo "#FFFFFF";  else echo  $row[15]; ?>;
padding:0 0;
margin:0 0;
table-layout:fixed;
overflow:hidden;
line-height: <?php echo $row[40]; ?>px;
}

<?php 
if($row[3]!=2)
{
?>
		.inout-title a:link,.inout-title a:visited,.inout-title a:hover,.inout-title a:active,.inout-title a:focus
		{
		font-family:<?php echo $row[6];?>;
		font-size:<?php echo $row[7];?>px;
		color:<?php echo $row[8];?>;
		
		<?php
		if($row[24]==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($row[25]==3)
		$deco="blink";
		elseif($row[25]==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		
		}
		
		
		.inout-desc 
		{
		font-family:<?php echo $row[9];?>;
		font-size:<?php echo $row[10];?>px;
		color:<?php echo $row[11];?>;
		<?php
		if($row[26]==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($row[27]==3)
		$deco="blink";
		elseif($row[27]==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		
		}
		
		
		.inout-url a:link,.inout-url a:visited,.inout-url a:hover,.inout-url a:active,.inout-url a:focus
		{
		font-family:<?php echo $row[12];?>;
		font-size:<?php echo $row[13];?>px;
		color:<?php echo $row[14];?>;
		<?php
		if($row[28]==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($row[29]==3)
		$deco="blink";
		elseif($row[29]==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		 white-space:nowrap;
		}

<?php 
}
?>

.inout_credit_over
{
height:15px;
font-size:12px;
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

.liclass 
{
height:15px;
position:absolute;
margin:0;
padding:0;

list-style-type:none;
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

.liclass  #crd{display:none;}
li.liclass:hover #crd {
display:block;


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
/*width:<?php  echo $row[1]; ?>px;*/


}








.inout-credit
{
/*width:auto;*/
height:15px;

<?php
if($ct ==0 && $row[20]!=2 && $row[20]!=3)
{
?>
max-width:<?php  echo $row['width']-10; ?>px;
background-color:<?php echo $r1[2]; ?>;
padding:0 5;
<?php
}
else if($ct ==1 && $row[20]!=2 && $row[20]!=3)
{
?>
max-width:<?php  echo $row['width']; ?>px;
<?php
}
?>

<?php
if($ct ==0 && ($row[20]==2 || $row[20]==3))
{
?>


background-color:<?php echo $r1[2]; ?>;
width:<?php echo $singlewidth-10; ?>px;
padding:0 5;
overflow:hidden;
<?php
}
else if($ct ==1 && ($row[20]==2 || $row[20]==3))
{
?>
width:<?php echo $singlewidth; ?>px;
overflow:hidden;
<?php
}
?>







white-space:nowrap;


margin:0px;
<?php
if($row[19]==0)
$t_align="left";
else
$t_align="right";	
?>
text-align:<?php echo $t_align; ?>;
}
.inout-credit a:link,.inout-credit a:visited,.inout-credit a:hover,.inout-credit a:active,.inout-credit a:focus
{
color:<?php echo $r1[1]; ?>;
font-family:<?php echo $row[16];?>;
font-size:10px;
line-height: 15px;
<?php
if($row[30]==2)
$f_weight="bold";
else
$f_weight="normal";	
?>
font-weight:<?php echo $f_weight; ?>;
<?php 
if($row[31]==3)
$deco="blink";
elseif($row[31]==1)
$deco="none";	
else
$deco="underline";		
?>
text-decoration:<?php echo $deco; ?>;
}
</style>
</head>

<body><div><?php  
if($row[3]==2 || $type=="image")
{
		?><table height="<?php echo $row[2]; //if credit text is present ,append 15px for credit text ?>"  width="<?php  echo $row[1]; ?>" cellpadding="0" cellspacing="0" class="inout-table"><?php
		//if($row[20]==1 && $row[33] !=0)
			//	echo "<tr ><td class=\"inout-credit\"><a href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
		?><tr ><td style="background: url(../images/banner.gif) repeat; height:<?php echo $row[2]; ?>px;"><?php
		if($row['credit_text_positioning']==1 && $ctext!="")  
				{
				?><li class="liclass"  ><div id="idiv" class="inout_credit_over">(i)</div><div class="inout_credit_data"   id="crd"  ><table cellpadding="0" cellspacing="0" class="inout-before-credit"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $server_dir.'index.php'; ?>"><?php echo $ctext;?></a></td></tr></table></div></li><?php
				}	
		
	
	
		if($row['credit_text_positioning']==0 && $ctext!="")  
				{
				?><li class="liclass"  ><div id="idiv" class="inout_credit_over">(i)</div><div class="inout_credit_data"   id="crd"  ><table cellpadding="0" cellspacing="0" class="inout-before-credit"><tr ><td class="inout-credit"><a target="_blank"  href="<?php echo $server_dir.'index.php'; ?>"><?php echo $ctext;?></a></td></tr></table></div></li><?php
				}		
		
		?></td></tr><?php	
		
		
		
		
		
		
		//if($row[20]==0 && $row[33] !=0)
		//	echo "<tr ><td class=\"inout-credit\"><a href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
		?></table>
		
		
		
		
		
		<?php			
}
else if($row[3]==4 || $type=="catalog")
{
		$blockheight= $row[2];
		if($row[22]==0)
		{
		?><b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b><?php
		$blockheight= $row[2]-10;
		}
		//$blockheight_without_credit=$blockheight;
		//if($row[33]!=0)  $blockheight+=15;
		?><table height="<?php echo $blockheight;  ?>px"  width="<?php  echo  $row[1]; ?>px" cellpadding="0" cellspacing="0" class="inout-table" ><?php 
		
		$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row[42]' and wapstatus=1");
		$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row[42]'and wapstatus=1");
		//echo $catalog_width."dddddd";
		
		if($row[5]==2)//horizontal
			{
			//echo $catalog_width."ssss";
				
				if($row[20]==1 && $row[33] !=0)
					echo "<tr><td  colspan=\"$row[43]\" class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
				
				?><tr><?php
					for($i=0;$i<$row[43];$i++)
					{
					?><td ><?php 
					if($row[44]==1) 
					{
					 ?><table  cellpadding="0" cellspacing="5" height="100%" border=0  style="line-height: <?php echo $row[40]; ?>px;table-layout:fixed;overflow:hidden;<?php if($row[45]==1 && $i < $row[43]-1 ){ ?>border-right:solid 1px <?php echo $r1[2]; ?>; <?php } ?>"><tr><td align="center" valign="top" style="width:<?php echo $catalog_width; ?>px;"><div style="height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td><td align="left" valign="top"><div class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></div><div class="inout-desc"><?php echo $d_description; ?></div></td></tr></table><?php 
					 } 
					 else 
					 { 
					 ?><table cellpadding="0" cellspacing="5" height="100%" border=0  style="line-height: <?php echo $row[40]; ?>px;table-layout:fixed;overflow:hidden;<?php if($row[45]==1 && $i < $row[43]-1) { ?> border-right:solid 1px <?php echo $r1[2]; ?>; <?php } ?>"><tr><td align="center" valign="top" style="height:<?php echo $catalog_height; ?>px;"><div style="height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td></tr><tr><td valign="top"><div class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></div><div class="inout-desc"><?php echo $d_description; ?></div></td></tr></table><?php 
					 } 
					 ?></td><?php 
					 } 
					 ?></tr><?php 
				if($row[20]==0 && $row[33] !=0)
					echo "<tr ><td  colspan=\"$row[43]\" class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
			}
		else //vertical
			{
				if($row[20]==1 && $row[33] !=0)
					echo "<tr ><td class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
					
				for($i=0;$i<$row[43];$i++)
					{
				?><tr><td ><?php if($row[44]==1) 
							{ ?><table  cellpadding="0"  cellspacing="5" border=0  style="line-height: <?php echo $row[40]; ?>px;table-layout:fixed;overflow:hidden;<?php if($row[45]==1  && $i < $row[43]-1) { ?>border-bottom:solid 1px <?php echo $r1[2]; ?>;<?php } ?>"><tr><td align="center" valign="top"  style="width:<?php echo $catalog_width; ?>px"><div style=" height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td><td align="left" valign="top"><div class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></div><div class="inout-desc"><?php echo $d_description; ?></div></td></tr></table><?php
				 } else 
				{ ?><table  cellpadding="0"  cellspacing="5" border=0 style="line-height: <?php echo $row[40]; ?>px;table-layout:fixed;overflow:hidden;<?php if($row[45]==1  && $i < $row[43]-1) { ?> border-bottom:solid 1px <?php echo $r1[2]; ?>; <?php } ?>"><tr><td align="center" valign="top"  style=" height:<?php echo $catalog_height; ?>px;"><div style="height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td></tr><tr><td valign="top"><div class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></div><div class="inout-desc"><?php echo $d_description; ?></div></td></tr></table><?php 
				} ?></td></tr><?php
				}
				if($row[20]==0 && $row[33] !=0)
					echo "<tr ><td class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
			}
			
		?></table><?php  
		if($row[22]==0)
		{?><b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b><?php
		}


}

else if($row[3]==7)
{

//echo $_POST['catalogid']."fff";

		$blockheight= $row[2];
		if($row[22]==0)
		{
		?><b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b><?php
		$blockheight= $row[2]-10;
		}
		//$blockheight_without_credit=$blockheight;
		//if($row[33]!=0)  $blockheight+=15;
		
	//	if($row[20]==1 && $row[33] !=0)
	//	echo $credittextpreview;
		?><table height="<?php echo $blockheight; //if credit text is present ,append 15px for credit text ?>px"  width="<?php  echo  $row[1]; ?>px" cellpadding="0" cellspacing="0" class="inout-table" ><?php 
		
	//	
		
		if(!isset($_GET['catalogid']))
		{
		$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row[42]' and wapstatus=1");
//	echo"select width from catalog_dimension where id='$row[42]'";
	
		$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row[42]'and wapstatus=1");
		}
		else
		{
		//echo "ffffff";
		$catalogid=$_GET['catalogid'];
		$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$catalogid' and wapstatus=1");
		$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$catalogid'and wapstatus=1");
		
		
		}
		//$catalog_width=$inline_width;
	//	$catalog_height=$inline_height;
		
		
		
		//echo $catalog_width."dddddd";
		
	
		//	echo $row[43];
				if($row[20]==1 && $row[33] !=0)
					echo "<tr ><td class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
					
			
				
				?><tr><td ><?php if($row[44]==1) 
							{ ?><table  cellpadding="5"  cellspacing="0" border=0  style="line-height: <?php echo $row[40]; ?>px;table-layout:fixed;overflow:hidden;<?php if($row[45]==1  && $i < $row[43]) { ?>border-bottom:solid 1px <?php echo $row[17]; ?>;<?php } ?>"><tr><td align="center"  style="width:<?php echo $catalog_width; ?>px"><div style=" height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td><td align="left" valign="top"><span class="inout-desc"><?php echo $d_description; ?></span><br><span class="inout-url"><a href="#" target="_blank"><?php echo $ad_display_url; ?></a></span></td></tr></table><?php
				 } else 
				{ ?><table  cellpadding="5"  cellspacing="0" border=0 style="line-height: <?php echo $row[40]; ?>px;table-layout:fixed;overflow:hidden;<?php if($row[45]==1  && $i < $row[43]) { ?> border-bottom:solid 1px <?php echo $row[17]; ?>; <?php } ?>"><tr><td align="center"  style=" height:<?php echo $catalog_height; ?>px;"><div style="height:<?php echo $catalog_height; ?>px;width:<?php echo $catalog_width; ?>px;background: url(../images/banner.gif) repeat; "></div></td><td valign="top"><span class="inout-desc"><?php echo $d_description; ?></span><br><span class="inout-url"><a href="#" target="_blank"><?php echo $ad_display_url; ?></a></span></td></tr></table><?php 
				} ?></td></tr><?php
				
				if($row[20]==0 && $row[33] !=0)
					echo "<tr ><td class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";

			
		?></table><?php  
	//	if($row[20]==0 && $row[33] !=0)
	//	echo $credittextpreview;
		
		if($row[22]==0)
		{?><b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b><?php
		}


}

else //text preveiew
{

        $singleflag=0;
	    if($row[3]==1 && $row[41] ==2)
				{
				$singleflag=1;
				$singlewidth=$row[1]/($row[18]+1);
				
				}

		$blockheight= $row[2];
		if($row[22]==0) //rounded corner uses 10px of block height, 5 at top and 5 at bottom
		{
		?><b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b><?php
		$blockheight= $row[2]-10;
		}
		?><table height="<?php  echo $blockheight; //if credit text is present ,append 15px for credit text ?>"  width="<?php  echo  $row[1]; ?>" cellpadding="0" cellspacing="0" class="inout-table"><?php 
		
		if($row[5]==2)//horizontal
			{
				if($row[20]==1 && $row[33] !=0)
					echo "<tr ><td  colspan=\"$row[18]\" class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
				
				?><tr><?php
				
				if($singleflag==1 && $row[20]==2 &&  $row[33] !=0)
				{
				?>
				<td class="inout-credit" ><a target="_blank" href="<?php echo $server_dir."index.php"; ?>"><?php echo $ctext; ?></a></td>
				<?php
				}
				
				for($i=0;$i<$row[18];$i++)
				{
					?><td ><span class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></span><?php
					if($row[41]!=2)// not title only
					{
					?><br><span class="inout-desc"><?php
					 echo $d_description; 
					?></span><?php
					}
					if($row[41]==1) // title/desc/display url
					{
					?><br><span class="inout-url"><a href="#" target="_blank"><?php
					echo $ad_display_url; 
					?></a></span><?php 	
					}
					?></td><?php 
				} 
				
				if($singleflag==1 && $row[20]==3 &&  $row[33] !=0)
				{
				?>
				<td class="inout-credit" ><a target="_blank" href="<?php echo $server_dir."index.php"; ?>"><?php echo $ctext; ?></a></td>
				<?php
				}
				
				
				?></tr><?php 
				if($row[20]==0 && $row[33] !=0)
					echo "<tr ><td  colspan=\"$row[18]\" class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
			}
		else //vertical
			{
				
				if($row[20]==1 && $row[33] !=0)
					echo "<tr ><td class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
				for($i=0;$i<$row[18];$i++)
				{
					?><tr><td ><span class="inout-title"><a href="#" target="_blank"><?php echo $ad_title; ?></a></span><?php
					if($row[41]!=2)
					{
					?><br><span class="inout-desc"><?php
					 echo $d_description; 
					?></span><?php
				 	}
					if($row[41]==1)
					{
					?><br><span class="inout-url"><a href="#" target="_blank"><?php
					echo $ad_display_url; 
					?></a></span><?php 	
					}
					?></td></tr><?php
				}
				if($row[20]==0 && $row[33] !=0)
					echo "<tr ><td class=\"inout-credit\"><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr>";
			}
			
		?></table><?php  
		if($row[22]==0) //rounded corner uses 10px of block height, 5 at top and 5 at bottom
		{
		?><b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b><?php
		}

}
?></div></body></html>