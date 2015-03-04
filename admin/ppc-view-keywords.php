<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php

include("config.inc.php");
include("../extended-config.inc.php");  
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

loadsettings("ppc_new");
$budget_period=$GLOBALS['budget_period'];
         // $budget_period (monthly ,daily)
         
if($budget_period==1)
{
	$budget_period_unit='Monthly';
	$PERIOD='in '.strftime("%B",time());
	
}
else if($budget_period==2)
{
	$budget_period_unit='Daily';
	$PERIOD='today';
}


?><?php include("admin.header.inc.php"); 

include("../graphutils.inc.php");
 include("../advertiser_statistics_utils.php");

$id=$_REQUEST['id'];
phpsafe($id);

$url=urlencode($_SERVER['REQUEST_URI']);

$deleteurl=$_REQUEST['url'];


//echo $row1[2];
//echo "select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused from ppc_users a,ppc_ads b where a.uid=b.uid and b.id='$id' and b.wapstatus='$wap_flag'";
//exit(0);
$result1=mysql_query("select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused,b.contenttype,b.hardcodelinks,b.adlang,b.wapstatus  from ppc_users a,ppc_ads b where a.uid=b.uid and b.id='$id' ");
$row1=mysql_fetch_row($result1);

$wap_flag=$row1[16];
/*if(isset($_GET['wap']))
{
 $wap_flag=$_GET['wap'];
phpSafe($wap_flag);
}*/

if($wap_flag==1)
{
	$name='wap';
	$image='wap.png';
}
else
{
	$wap_flag=0;
	
	$name='';
	$image='pc.png';
	
}

$resht=$mysql->echo_one("select height from banner_dimension where id='$row1[11]'");
$reswt=$mysql->echo_one("select width from banner_dimension where id='$row1[11]'");		



$flag_time=0;
if(!isset($_GET['statistics']))
{	$show="day";	}
else
{ $show=$_GET['statistics']; }


if($show=="day")
{
$flag_time=-1;	
$showmessage="Today";
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));
$beg_time=$time;

//echo date("d M h a",$end_time);
}
else if($show=="week")
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",time())+1-11,1,date("Y",time()));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time())+1,1,date("y",time()));
$beg_time=$time;
}
else if($show=="all")
{
	$flag_time=2;
	$showmessage="All Time";
	$time=0;
	$end_time=mktime(0,0,0,1,1,date("y",time())+1);
$beg_time=$time;

}
else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
//exit(0);
$table_name="advertiser_daily_statistics";
if($flag_time==0)
	  {
		$table_name="advertiser_daily_statistics";
	  }
	 else if($flag_time==2)
	 	{
	 	$table_name="advertiser_yearly_statistics";
		$beg_time=$mysql->echo_one("select min(time) from $table_name");
	 	}
	 else
	 {
		$table_name="advertiser_monthly_statistics";
	 }
//echo $show;


if(mysql_num_rows($result1)==0)
{
echo"<br>No Record Found<br><br>";
include("admin.footer.inc.php"); 
exit(0);
}

$result=mysql_query("select * from ppc_keywords where aid='$id' order by id DESC");

$total_impressions=0;
$total_clicks=0;


?>
<script  language="javascript" type="text/javascript" src="../swfobject.js"></script>
<script  language="javascript" type="text/javascript" src="loadswfadcontent.js"></script>
<script language='javascript' src='../FusionCharts/FusionCharts.js'></script>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("You are about to delete the ad. It won't be available later.")
		if (answer)
			return true;
		else
			return false;
		}
</script>
<script type="text/javascript">
	function promptuser_keyword()
		{
		var answer = confirm ("You are about to delete the keyword. It won't be available later.")
		if (answer)
			return true;
		else
			return false;
		}
</script>
<script language="javascript">
function check_value()
	{
	var key_val=document.getElementById('ad_keyword').value;
				if(key_val=="")
					{
					alert("Keyword cannot be null");
					document.getElementById('ad_keyword').value="";
					document.add_keyword.ad_keyword.focus();
					return false;
					}
					var min_clk_val=<?php echo $min_click_value; ?>;
				//	alert(min_clk_val);
				var val=document.getElementById('clk_val').value;
					if(val<min_clk_val)
					{
					alert("Pelase enter a value greater than minimum click value("+min_clk_val+")");
					document.getElementById('clk_val').value="";
					document.add_keyword.clk_val.focus();
					return false;
					}
				/*if(!(isInteger(val)))
					{
					alert("Pelase enter a valid max click value");
					document.getElementById('clk_val').value="";
					document.add_keyword.clk_val.focus();
					return false;
					}*/
			
	}
function isInteger(val)
				{
 				  // alert(val.value);
				  if(val==null)
				    {
			        //alert(val);
			        return false;
				    }
			    if (val.length==0)
				    {
			       // alert(val);
			        return false;
				    }
				 if (trim(val).length == 0) 
				 	{
					 return false;
				 	}
			    for (var i = 0; i < val.length; i++) 
				    {
			        var ch = val.charAt(i)
			        if (i == 0 && ch == "-")
				        {
			            continue
				        }
			        if (ch < "0" || ch > "9")
				        {
			            return false
				        }
				    }
			    return true
			}
function trim(stringValue)
				{
	return stringValue.replace(/(^\s*|\s*$)/, "");
	}
</script>

<br />

<table  cellpadding="0" cellspacing="0" width="100%">
<tr><td   class="heading">           	Ad details </td></tr>
</table>
<br />


<table border="0" width="100%" cellpadding="0" cellspacing="0">

 <tr>
   <td width="73%"  align="left"   valign="middle"><?php 
if($row1[4]=="0") { ?><span class="gridheader">Inactive Ad </span>&nbsp;<?php } else if($row1[4]=="1") { ?> 
        <span class="activead">Active Ad </span><?php 
		if($row1[10]==1)
			echo "<span class=paused_text>&nbsp;Paused by advertiser</span>";
		} else 
		{?>
      <span class="penting">Pending Ad </span>&nbsp;
	  
<?php } ?>
  <span class="note">&nbsp;&nbsp; Used <?php echo moneyFormat($row1[12])." ".$PERIOD;?> </span>
  <?php
//******************** time targetting********************************//  

$target_result=mysql_query("select date_tar_s,date_tar_e,time_tar_s,time_tar_e,day_tar_s,day_tar_e,date_flg,time_flg,day_flg from time_targeting where aid=$id");


 if(mysql_num_rows($target_result)!=0)
 {
  while($rn=mysql_fetch_row($target_result))
  {
  if($rn[0]==0)
  {
  	//echo "..........".$rn[6];	
    if($rn[6]==1){ ?>
	  <span class="note">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "Ad Time Duration : 1 Week"; ?></span><?php }
	else if($rn[6]==2){ ?>
	  <span class="note">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "Ad Time Duration : 2 Week"; ?></span><?php }
	else {?>
      <span class="note">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "30 Days"; ?></span><?php }
  }
  else
  {
    $tar_sdate=date("m/d/Y",$rn[0]);
    $tar_edate=date("m/d/Y",$rn[1]);
	?>
  <span class="note">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo "Start date:".$tar_sdate." | End date:".$tar_edate; ?> </span><?php 
  }
  
   }
 }
 
 if($time_targeting==1)
{
//echo "select beg_time from ppc_ads WHERE `aid` =$id ;"; exit;
$beg_time=$mysql->echo_one("select beg_time from ppc_ads WHERE `id` =$id ;");
$end_time=$mysql->echo_one("select end_time from ppc_ads WHERE `id` =$id ;");
if($beg_time==0 || $end_time==0)
{
echo "<br>"; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
echo "No Time Resstrictions";
}
else
{
echo "<br>"; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
$beg_t=$mysql->echo_one("select name from time_hour WHERE `code` ='$beg_time' ;");
$end_t=$mysql->echo_one("select name from time_hour WHERE `code` ='$end_time' ;");
echo "Start time:".$beg_t." | End time:".$end_t;
}
}
 

//******************** time targetting********************************//
  ?></td>
  <td align="right"><img src="images/<?php echo $image ?>"></td>
 </tr>
 <tr >
     <td  colspan="5" valign="middle">
	
	<?php
	
	 if($row1[8]==0) 
	 { 
	
		 $dispurl=$row1[9];
		 
		 if($dispurl=="")
		 {
		
		 $dispurl=$row1[1];
		 }
		 echo "<div class=\"box\"><a href=\"$row1[1]\">$row1[0]</a><br>$row1[2]<br>$dispurl<br></div>"; 
	 } 
	 		 	  else  if($row1[8]==2) 
	 {
	 
$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row1[11]'");
$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row1[11]'");  
	 
$strFlashHardLinks="";
$strHardLinks="";
$checking_link=$GLOBALS['hardcode_check_url'];		 	
if($row1[13]=="swf" && $row1[8]=="2")
{

if($row1[14] >0)
{
for($i=0;$i<$row1[14];$i++)
{
$strHardLinks.="flashvars.alink".($i+1)."='".$row1[1]."';";
$strHardLinks.="flashvars.atar".($i+1)."='_blank';";


$strFlashHardLinks.="flashvars_tmp.alink".($i+1)."='".$checking_link."';";
$strFlashHardLinks.="flashvars_tmp.atar".($i+1)."='_blank';";


}

}

	 
	 
	 
	 ?>
	 
	 <script type="text/javascript">

var flashvars_tmp = {};

 <?php echo $strFlashHardLinks; ?>

		  var flashvars = {};
		  var params = {};
		  var attributes = {};
		  //var i=1;
		  
          flashvars.clickTag = "<?php echo $row1[1]; ?>";
		  
		  
		  flashvars.clickTAG = "<?php echo $row1[1]; ?>";
		  flashvars.clickTARGET = "_blank";
		  
		 <?php echo $strHardLinks; ?>
		 		
		 params.wmode="transparent";
		 
		 		
	      swfobject.embedSWF("../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row1[7]; ?>/<?php echo $row1[0]; ?>", "myFlashDivNew", "<?php echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars,params,attributes);
</script>

 <table width="100%" height="33" border="0" cellpadding="0" cellspacing="0" class="box">
        <tr>
		<td >
<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0">
<td width="<?php echo $catalog_width; ?>"  height="<?php echo $catalog_height; ?>" ><a href="<?php echo $row1[1]; ?>">
		  <div id="myFlashDivNew"></div></a></td>
		  <td align="left" valign="top"><a href="<?php echo $row1[1]; ?>"><?php echo $row1[9]; ?></a><br>
		    <span><?php echo $row1[2]; ?></span></td>
		  </table>
		  </td>
		  </tr>
    </table>



<?php
 } else { 	 
	 
	 
	 
	 
	 ?>
	 
	 
	 
	 
	   <table width="100%" height="33" border="0" cellpadding="0" cellspacing="0" class="box">
        <tr>
		<td >
<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0">
<td width="<?php echo $catalog_width; ?>"  height="<?php echo $catalog_height; ?>" ><a href="<?php echo $row1[1]; ?>"><img src="../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row1[7]; ?>/<?php echo $row1[0]; ?>" border="0" ></a></td>
		  <td align="left" valign="top"><a href="<?php echo $row1[1]; ?>"><?php echo $row1[9]; ?></a><br>
		    <span><?php echo $row1[2]; ?></span></td>
		  </table>
		  </td>
		  </tr>
    </table>

	 <?php 	
	 }
	 } 
	 else 
	 {
	 
$strFlashHardLinks="";
$strHardLinks="";
$checking_link=$GLOBALS['hardcode_check_url'];		 	
if($row1[13]=="swf" && $row1[8]=="1")
{

if($row1[14] >0)
{
for($i=0;$i<$row1[14];$i++)
{
$strHardLinks.="flashvars.alink".($i+1)."='".$row1[1]."';";
$strHardLinks.="flashvars.atar".($i+1)."='_blank';";


$strFlashHardLinks.="flashvars_tmp.alink".($i+1)."='".$checking_link."';";
$strFlashHardLinks.="flashvars_tmp.atar".($i+1)."='_blank';";


}

}

//echo $strFlashHardLinks;




?>
<script type="text/javascript">

var flashvars_tmp = {};

 <?php echo $strFlashHardLinks; ?>

		  var flashvars = {};
		  var params = {};
		  var attributes = {};
		  //var i=1;
		  
          flashvars.clickTag = "<?php echo $row1[1]; ?>";
		  
		  
		  flashvars.clickTAG = "<?php echo $row1[1]; ?>";
		  flashvars.clickTARGET = "_blank";
		  
		 <?php echo $strHardLinks; ?>
		 		
		 params.wmode="transparent";
		 
		 		
	      swfobject.embedSWF("../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row1[7]; ?>/<?php echo $row1[2]; ?>", "myFlashDivNew", "<?php echo $reswt; ?>", "<?php echo $resht; ?>", "9.0.0", "",flashvars,params,attributes);
</script>

<?php echo  "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"box\"><a href=\"$row1[1]\">"; ?>
		  <div id="myFlashDivNew"></div>	
		  <?php echo "</a></td></tr></table>"; ?>


<?php
 } else { 	 
	 
	 
	 
	 
	 
	 
		  echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"box\"><a href=\"$row1[1]\"><img border=\"0\" src=\"../".$GLOBALS['banners_folder']."/$row1[7]/$row1[2]\"></a></td></tr></table>";
		  
		  }
	 }
	  ?> 
    </td>
  </tr>
<tr bgcolor="#BCC5C2"> 
    <td height="20">&nbsp;Created by : <a href="view_profile.php?id=<?php echo $row1[6]; ?>&wap=<?php echo $wap_flag; ?>"><?php echo $row1[5];?></a>&nbsp;|&nbsp;<?php echo $budget_period_unit;?> Budget :<?php echo moneyFormat($row1[3]);?>
	
	<?php if($row1[8]=="1" && $row1[13]=="swf"){?> | <a href="#" onclick="LoadSwfImage('../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row1[7]; ?>/<?php echo $row1[2]; ?>',<?php echo $reswt; ?>, <?php echo $resht; ?>,'<?php echo $GLOBALS['hardcode_check_url']; ?>',flashvars_tmp)">Check Hard Coded Links</a> <?php }?>
	
	<?php if($row1[8]=="2" && $row1[13]=="swf"){?> | <a href="#" onclick="LoadSwfImage('../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row1[7]; ?>/<?php echo $row1[0]; ?>',<?php echo $catalog_width; ?>, <?php echo $catalog_height; ?>,'<?php echo $GLOBALS['hardcode_check_url']; ?>',flashvars_tmp)">Check Hard Coded Links</a> <?php }?>

	| Language:<?php $aa=$mysql->echo_one("select language from adserver_languages where id='$row1[15]'"); if($row1[15]==0){ echo "Any languages";}else echo $aa;?>
	
	</td>
    <td width="28%" colspan="4" align="right" style="white-space: nowrap"><a href="ppc-delete-ad.php?category=all&id=<?php echo $id; ?>&url=<?php echo $deleteurl; ?>"  onclick="return promptuser()">Delete</a> |
    <?php if($row1[4]==1) echo '<a href="ppc-change-ad-status.php?category=all&action=block&id='.$row1[7].'&url='.$url.'">Block</a>';  
          else if($row1[4]==-1) {
          	            echo '<a href="ppc-change-ad-status.php?category=all&action=activate'.'&id='.$row1[7].'&url='.$url.'">Activate</a> | ';
          	            echo '<a href="ppc-change-ad-status.php?category=all&action=block'.'&id='.$row1[7].'&url='.$url.'">Block</a>';          	           
                                }
    else echo '<a href="ppc-change-ad-status.php?category=all&action=activate&id='.$row1[7].'&url='.$url.'">Activate</a>';?>&nbsp;</td>
  </tr>

</table>
<div id="fade" class="black_popup_flash"></div>
<div id="light" class="white_popup_flash"></div>
<br /><br />


<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><form name="form1" method="get" action="ppc-view-keywords.php?id=<?php echo $id; ?>">
  Show   statistics as of 
      <select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?>>Today</option>
        <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 days</option>
        <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 days</option>
        <option value="year"  <?php 
			  				  if($show=="year")echo "selected";			  
			  ?>>Last 12 months</option>
        <option value="all"  <?php 
			  				  if($show=="all")echo "selected";			  
			  ?>>All Time</option>
      </select>
      <input type="submit" name="Submit" value="Go">
	  <input name="tab" type="hidden" id="tab" value="1">
      <input name="id" type="hidden" id="id" value="<?php echo $_REQUEST['id']; ?>">
	  <input name="url" type="hidden" id="url" value="<?php echo  $deleteurl; ?>">
	  <input name="wap" type="hidden" id="tab" value="">
      <input name="wap" type="hidden" id="wap" value="<?php echo $wap_flag; ?>">
    </form></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
 
</table>
	<br />


 <table width="100%" border="0" cellspacing="0" cellpadding="0"  style="border-color:#CCCCCC; border-width:1px; border-top-width:1px; border-style:solid; ">
  <tr>
  <td  valign="top" >
<div align="left" width: 100%>
<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus"  > 
 <tr height="30px">
    <td align="center"  id="index1_li_1" ><a href="javascript:index1_ShowTab('1');"  >Keywords</a></td>
     <td  align="center" id="index1_li_3" ><a href="javascript:index1_ShowTab('3');" >Locations</a></td>
   <td  align="center" id="index1_li_5" ><a href="javascript:index1_ShowTab('5');" >Keyword Statistics</a></td>
   <td  align="center" id="index1_li_4" ><a href="javascript:index1_ShowTab('4');" >Time Based Statistics</a></td>
    <td  align="center" id="index1_li_2"><a href="javascript:index1_ShowTab('2');" >Graphical Statistics</a></td>
  </tr>
</table>
</div>
</td>
</tr>
<tr   >
<td width="100%" valign="top"  >



  <div id="index1_div_1" style="padding:5px;" class="div_font_style">
  <p class="inserted">The keywords   highlighted in <span style="background-color:#F8B9AF">this color</span> are blocked keywords and those in <span style="background-color:#ffff99">this color</span> are pending keywords. </p> 

  <table width="100%"  border="0" cellspacing="0" cellpadding="1" class="datatable">
  <tr class="headrow">
    <td height="25" colspan="2">Keyword</td>
    <td width="36%">MAX Click Value(<?php if($GLOBALS['currency_format']=="$$") echo $GLOBALS['system_currency'];else echo $currency_symbol; ?>) </td>
    <td width="30%">Action</td>
  </tr>
<?php
//$number=mysql_num_rows($result);
if(mysql_num_rows($result)>0)
{
	$i=0;
	while($row=mysql_fetch_row($result))
	{
	

		$extrastring="";
		if($id!="")
		$extrastring="&aid=".$id;
		?>
		  <tr  height="25" <?php if($row[5]==0) echo 'bgcolor="#F8B9AF"'; else if($row[5]==-1) echo 'bgcolor="#ffff99"'; else if($i%2==1) {?> class="specialrow"  <?php } ?>>
			<td height="25" colspan="2" ><?php echo $row[3];?></td>
			<td > <?php echo numberFormat( $row[4]);?></td>
			<td ><?php  
			if($ad_keyword_mode!=2)
			{
				if($ad_keyword_mode==3  && $row[3]==$keywords_default)
				{
				echo "N/A";
				}
				else
				{
	?>  
				  <a href="ppc-delete-keyword.php?id=<?php echo $row[0].$extrastring; ?>&url=<?php echo $url; ?>" onclick="return promptuser_keyword()">Delete</a><?php
				 }
			}
			else 
				echo "N/A"; ?></td>
		  </tr>
		<?php
		$i++;
	}
}
else
{
	echo"<tr><td colspan=\"3\">No Keywords Found <br><br></td></tr>";
}
?>	
</table>

<?php 

 if($ad_keyword_mode!=2)	
	{
	?>
<form name="add_keyword" method="post" action="add-new-keyword.php?url=<?php echo $url; ?>&wap=<?php echo $wap_flag; ?>" onsubmit="return check_value()">
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr><td colspan="3"><p class="inserted">You can add new keyword for this ad below. </p></td></tr>
<tr>
<td>Keyword</td>
<td> Click Value </td>
<td>&nbsp;</td>
<tr >
     <td><input type="text" name="ad_keyword" id="ad_keyword" value=""/></td>
	 <td><input type="text" name="clk_val" id="clk_val" value="<?php echo $min_click_value; ?>" /></td>
	 <td><input type="submit" name="Add keyword" value="Add keyword" /></td>
	</tr>
</table>
<input type="hidden" name="uid" value="<?php echo $row1[6];?>" />
<input type="hidden" name="aid" value="<?php echo $id; ?>" />
<input type="hidden" name="url" value="<?php echo $url; ?>" />
</form>
<?php 
} 
?>
</div>	


<div id="index1_div_2" style="padding:5px;">
 
<?php
	//echo $mysql->echo_one("select link from ppc_ads where id='$row[1]'");

 

 

	
 
		
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
}

$uid=$row1[6];

$returnVal=plotAdvertiserGraphs($show,$flag_time,$beg_time,$end_time,$selected_colorcode,$uid,$id );

$FC=$returnVal[0];


//echo $returnVal[0];
$FD=$returnVal[1];

			$FC->renderChart();
			echo "<br>";
		
			$FD->renderChart();


 
?>

 
</div>

<div id="index1_div_5" style="padding:5px;">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="97%">&nbsp;</td>
  </tr>
  <tr>
    <td height="25"> Keyword click statistics for the selected ad are shown below. </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Existing Keywords</strong></td>
  </tr>
  <tr>
    <td>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
      <tr class="headrow">
        <td>Keyword</td>
        <td>Clicks</td>
        <td>Impressions</td>
        <td>CTR</td>
        <td> Click Value(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
        <td width="10%">Publisher Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
        <td width="12%">Referral Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) &nbsp;</td>
        <td width="14%">Your Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
      </tr>
	  <?php
	  $i=0;
		mysql_data_seek($result,0);

$key_clk_sum=0;
$key_imp_sum=0;
$key_money_sum=0;
$key_pubprof_sum=0;
$key_ref_sum=0;
$key_adminshare_sum=0;


	   while($row=mysql_fetch_row($result)) 
	   { 
			?>
			<tr <?php if($i%2==1) { ?>class="specialrow" <?php }?>>
			<td ><?php echo $row[3]; ?>&nbsp;</td>
			
			<td ><?php $clk= getKeywordClicks($row[0],$time,$mysql,$row1[6],$id,$flag_time); echo numberFormat($clk,0); $key_clk_sum+=$clk; ?></td>
			<td ><?php $imp= getKeywordImpressions($row[0],$time,$mysql,$row1[6],$id,$flag_time); echo numberFormat($imp,0); $key_imp_sum+=$imp; ?></td>
			<td ><?php
 
			$ctr=getCTR($clk,$imp);
			echo numberFormat($ctr);
			?>
			  &nbsp;%</td>
			<td ><?php 	$ret=getKeywordMoneySpent($row[0],$time,$mysql,$row1[6],$id,$flag_time);
			if( $ret=="")
				$ret=0;
			echo  numberFormat($ret);
			$key_money_sum+=$ret;
			?></td>
				<td ><?php 	$re=getKeywordPublisherprofit($row[0],$time,$mysql,$row1[6],$id,$flag_time);
			if( $re=="")
				$re=0;
			echo  numberFormat($re);
			$key_pubprof_sum+=$re;
			?></td>
				<td ><?php
				$ref_sh=getKeywordPublisherrefprofit($row[0],$time,$mysql,$row1[6],$id,$flag_time);
			if( $ref_sh=="")
			$ref_sh=0;
			$adv_ref_sh=getKeywordAdvrefprofit($row[0],$time,$mysql,$row1[6],$id,$flag_time);
			if( $adv_ref_sh=="")
			$adv_ref_sh=0;
			$tot_ref=$ref_sh+$adv_ref_sh;
			echo numberFormat($tot_ref);
				$key_ref_sum+=$tot_ref;
			?>&nbsp;</td>
			<td ><?php echo numberFormat($ret-($re+$tot_ref)); ?></td>
			 </tr>
			<?php  
			$i++;
		}
$key_adminshare_sum=$key_money_sum-($key_pubprof_sum+$key_ref_sum);
 ?>	
	</table>
	</td>
  </tr>
        
  <?php
  if(mysql_num_rows($result)==0)

	{
	echo"<tr><td >No Keywords Found <br></td></tr>";
	}
	?>
		<tr>
		  <td>&nbsp;</td>
  </tr>

  <tr>
        <td>&nbsp;</td>
     </tr> 
	<tr>
		  <td><strong>Deleted Keywords </strong></td>
  </tr>
 
  <tr>
    <td>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
      <tr class="headrow">
        <td width="12%">Clicks</td>
        <td width="15%">Impressions</td>
        <td width="15%">CTR</td>
        <td width="15%"> Click Value(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
        <td width="15%">Publisher Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
        <td width="14%">Referral Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) &nbsp;</td>
        <td width="14%">Your Share (<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</td>
      </tr>
	  <?php
 
		$total_clicks=getAdClicks($id,$time,$mysql,$row1[6],$flag_time);
		$total_impressions=getAdImpressions($id,$time,$mysql,$row1[6],$flag_time);
		$ret_tot=getAdMoneySpent($id,$time,$mysql,$row1[6],$flag_time);



 
$tot_ctr=getCTR($total_clicks,$total_impressions);

	if( $ret_tot=="")
	$ret_tot=0;


		$re_tot=getPublisherprofit($id,$time,$mysql,$row1[6],$flag_time);

	if( $re_tot=="")
	$re_tot=0;

		$pub_ref_sh=getPublisherrefprofit($id,$time,$mysql,$row1[6],$flag_time); 
		$adv_ref_sh=getAdvertiserrefprofit($id,$time,$mysql,$row1[6],$flag_time); 

		if( $pub_ref_sh=="")
		$pub_ref_sh=0;
		if( $adv_ref_sh=="")
		$adv_ref_sh=0;
		$tot_ref=$pub_ref_sh+$adv_ref_sh;

		$tot_admin_share=$ret_tot-($re_tot+$tot_ref);

$imp=$total_impressions-$key_imp_sum;
$clk=$total_clicks-$key_clk_sum;
$ret=$ret_tot-$key_money_sum;
$re=$re_tot-$key_pubprof_sum;
$ref=$tot_ref-$key_ref_sum;
$admin_share=$tot_admin_share-$key_adminshare_sum;



		$ctr=getCTR($clk,$imp);
		
		if( $ret=="")
			$ret=0;
		$ret=round($ret,2);
	
		if( $re=="")
			$re=0;
		$re=round($re,2);
		
	  ?>
      <tr  height="25">
        <td ><?php echo numberFormat($clk,0);?>&nbsp;</td>
        <td ><?php echo numberFormat($imp,0); ?>&nbsp;</td>
        <td ><?php echo numberFormat($ctr); ?>&nbsp;%&nbsp;</td>
        <td ><?php 	echo  numberFormat($ret); ?>&nbsp; </td>
        <td   ><?php 	echo numberFormat($re); ?>&nbsp;</td>
        <td   ><?php
		

		echo numberFormat($ref);
		
		?>&nbsp;</td>
        <td  ><?php echo numberFormat($admin_share); ?> &nbsp;</td>
      </tr>
 
	</table><br />

	</td>
  </tr>
	<tr>
    <td><span class="styleTitle"><strong> Total Statistics </strong></span></td>
  </tr>
      <tr >
	  <td>
	 	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
    
      <tr class="headrow">
        <td width="12%">Clicks</td>
        <td width="15%">Impressions</td>
        <td width="15%">CTR</td>
        <td width="15%"> Click Value(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
        <td width="15%">Publisher Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
        <td width="14%">Referral Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) &nbsp;</td>
        <td width="14%">Your Share(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </td>
      </tr>
	        <tr height="25">
        <td   ><?php 
		
        if($total_clicks==0)
        echo "0";
        else
        echo numberFormat($total_clicks,0); ?></td>
        <td   ><?php 
		
        if($total_impressions==0)
        echo "0";
        else
        echo numberFormat($total_impressions,0); 
        ?></td>
        <td   ><?php echo numberFormat($tot_ctr); ?>&nbsp;%</td>
        <td  ><?php 	

	echo  numberFormat($ret_tot);
	?></td>
        <td ><?php 	

	
	echo  numberFormat($re_tot);
	?></td>
        <td ><?php
		

		echo numberFormat($tot_ref);
		
		?>&nbsp;</td>
        <td ><?php echo numberFormat($tot_admin_share); ?></td>
      </tr>
	 </table>  <br />

   </td>
  </tr>
</table>

<?php 
if($adserver_upgradation_date!=0)
	{
	?>
	<p><strong>Note:</strong><span class="info"> Impressions and Click Through Rates are available from : <?php echo dateFormat($adserver_upgradation_date);?>.</span> </p>
	<?php
	}
	?>
</div>

<div id="index1_div_3"  style="padding:5px;">

<p>Target Locations added for the selected ad are listed below.</p> 


<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
  <tr class="headrow">
    <td width="65%" height="25" > Location </td>
    <td width="65%"></td>
  </tr>
<?php
$locresult=mysql_query("select * from ad_location_mapping where adid='$id' and country<>'00' and region<>'00' and city<>'00' order by id ");

if(mysql_num_rows($locresult)>0)
{
	$i=0;
	while($locrow=mysql_fetch_row($locresult))
	{

	?>
	  <tr <?php if($i%2==1){?> class="specialrow"  <?php } ?>>
		<td height="25"  colspan="2"><?php 
		  echo $mysql->echo_one("select name from location_country where code='$locrow[2]'");
		/*  if($locrow[3]!='')
		  {
		  	echo " >> ".$mysql->echo_one("select region_name from location_region where country_code='$locrow[2]' and region_code='$locrow[3]'");
		  }
		  if($locrow[4]!='')
		  {
		  	echo " >> ".$locrow[4];
		  }
		 */
		  ?></td>
	  </tr>
	
	
	<?php
	$i++;
	}
}
else
{
?>
  <tr>
    <td height="25" colspan="2"> Worldwide </td>
  </tr>

<?php
}
?>
  </table>
</div>


<div  id="index1_div_4"  style="padding:5px;">

<br />
 
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
      <tr class="headrow">
        <td width="20%">Period</td>
           <td width="20%">Clicks</td>
        <td width="20%">Impressions</td>
        <td width="20%">CTR </td>
        <td width="20%">Money Spent(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol']; ?>) </td>
        
      </tr>
  <?php
$tablestructure=getTimeBasedAdvertiserStatistics($show,$flag_time,$beg_time,$end_time,$uid,$id);
//print_r($tablestructure);
 if(count($tablestructure) > 0)
{$i=0;
foreach($tablestructure as $key => $value)
{
	
if($flag_time==0)
            {
               // $str=date("d/M/Y",$key-1);
$str=dateFormat($key-1,"%b %d"); 
                $show_duration="$str";
                           
            }
            else if($flag_time==1)
            {
               // $str=date("M/Y",$key-86400);
$str=dateFormat($key-86400,"%b");
                $show_duration="$str";
            }
             else if($flag_time==2)
            {
       
               // $str=date("Y",$key-86400);
$str=dateFormat($key-86400,"%Y");
                $show_duration="$str";
            }   
           
             if($flag_time==-1)
            {
           // $str=date("d/M h a",$key);
$str=dateTimeFormat($key,"%d %b %l %p");
            $show_duration="$str";
            }
             
  
  
  ?>
  <tr <?php if($i%2==1)  { ?>class="specialrow" <?php }?>>
  <td><?php echo $show_duration; ?></td>
 <td><?php echo numberFormat($value[1],0); ?></td>
 <td><?php echo numberFormat($value[0],0); ?></td>
 <td><?php echo numberFormat(getCTR($value[1],$value[0])); ?> %</td>
<td><?php echo numberFormat($value[2]); ?></td>
  </tr> 
  <?php 
  
  $i++;
}
}
  ?>

  </table>
  
  
  
  <?php 
if($adserver_upgradation_date!=0)
	{
	?>
	<p><strong>Note:</strong><span class="info"> Impressions and Click Through Rates are available from : <?php echo dateFormat($adserver_upgradation_date);?>.</span> </p>
	<?php
	}
	?>

</div>


</td>
</tr>
</table>
<script language="javascript" type="text/javascript">
function index1_ShowTab(id)
{
	if(id>5)
		id=1;
	for(i=1;i<=5;i++)
	{
		
		if(i==id)
		{
			document.getElementById('index1_div_'+i).style.display="";
			document.getElementById("index1_li_"+i).style.background="url(images/li_bgselect.jpg) repeat-x";
			document.getElementById("tab").value=id;
			continue;
		}
		document.getElementById('index1_div_'+i).style.display="none";
		document.getElementById("index1_li_"+i).style.background="url(images/li_bgnormal.jpg) repeat-x";
	}
}

<?php
if(isset($_REQUEST['tab']) && is_numeric($_REQUEST['tab']) )
{
?>
index1_ShowTab(<?php echo $_REQUEST['tab']; ?>);
<?php
}
else
{
?>
index1_ShowTab(1);
<?php
}
?>

</script>
<br />

<?php  include("admin.footer.inc.php"); ?>
