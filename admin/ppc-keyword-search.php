<?php 
/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

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
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;

$search_keywords=$_REQUEST['s_keyword'];
phpSafe($search_keywords);


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





$status_flag=1;
$status_str=" and b.status=1 ";
if(isset($_REQUEST['status']))
$status_flag=$_REQUEST['status'];

if($status_flag==-1)
$status_str=" and b.status=-1 ";
elseif($status_flag==0)
$status_str=" and b.status=0 ";
elseif($status_flag==2)
$status_str="";
else
$status_str=" and b.status=1 ";



if(isset($_POST['wap']))
{
$wap_flag=$_POST['wap'];
phpSafe($wap_flag);

}
else
{
	$wap_flag=$_GET['wap'];
    phpSafe($wap_flag);
}

	$wap_url="&wap=$wap_flag"."&status=$status_flag";
if($wap_flag==1)
{
	$wap_string='b.wapstatus=1' ;
}
else if($wap_flag==2)
{
	$wap_string='(b.wapstatus=1 or b.wapstatus=0)' ;
}
else
{
	$wap_flag=0;	
	$wap_string=' b.wapstatus=0' ;
	
}
if(trim($search_keywords)!="")
{
$set=1;
//echo $search_keywords;
//echo "select aid,keyword,weightage,time,maxcv,b.wapstatus from `ppc_keywords` a, ppc_ads b where a.keyword='$search_keywords' and a.aid=b.id and $wap_string $status_str order by a.weightage desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;
//echo "select count(*) from ppc_keywords a, ppc_ads b where a.keyword='$search_keywords' and a.aid=b.id and $wap_string $status_str";
$result1=mysql_query("select aid,keyword,weightage,time,maxcv,b.wapstatus from `ppc_keywords` a, ppc_ads b where a.keyword='$search_keywords' and a.aid=b.id and $wap_string $status_str order by a.weightage desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$total=$mysql->echo_one("select count(*) from ppc_keywords a, ppc_ads b where a.keyword='$search_keywords' and a.aid=b.id and $wap_string $status_str");

}

if(substr_count($_SERVER['REQUEST_URI'],"?s_keyword=")==0)
$url=urlencode($_SERVER['REQUEST_URI']."?s_keyword=".$search_keywords."&wap=".$wap_flag."&status=".$status_flag);
else
$url=urlencode($_SERVER['REQUEST_URI']);
?><?php include("admin.header.inc.php"); ?>

<script  language="javascript" type="text/javascript" src="../swfobject.js"></script>
<script  language="javascript" type="text/javascript" src="loadswfadcontent.js"></script>
<script type="text/javascript">

function trim(stringValue){return stringValue.replace(/(^\s*|\s*$)/, "");}

function verifyform()
{
var a=document.getElementById("s_keyword").value;
//alert(a.trim());
if(trim(a).length==0)
{
alert("Search keyword cannot be null.");
 return false;
//document.ppc-search.s_keyword.focus();

}
}

function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
         {
           alert("You can only enter number/digit.");
            return false;
            }

         return true;
      }

</script>
	<script type="text/javascript" src="../js/livesearch.js"></script>
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



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/ads.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Search Ad By Keyword</td>
  </tr>
</table>
<form action="ppc-keyword-search.php" method="POST" name="ppc-search" id="ppc-search" enctype="multipart/form-data" onsubmit=" return verifyform()">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="3" style="white-space:nowrap">

  Keyword  
    <input name="s_keyword" id="s_keyword" type="text"  value="<?php echo $search_keywords;?>"   onKeyUp="showResult(this.value)" onChange="show()" autocomplete="off"> Target Device 
  <select name="wap" id="wap">
  <option value="2" <?php if($wap_flag==2) echo "selected"; ?> >Both</option>
  <option value="1"  <?php if($wap_flag==1) echo "selected"; ?>>Wap</option>
  <option value="0"  <?php if($wap_flag==0) echo "selected"; ?>>Desktops & Laptops</option>
  </select>
  Ad status
  <select name="status" id="status">
  <option value="1" <?php if($status_flag==1) echo "selected"; ?> >Active</option>
  <option value="0" <?php if($status_flag==0) echo "selected"; ?> >Blocked</option>
  <option value="-1"  <?php if($status_flag==-1) echo "selected"; ?>>Pending</option>
  <option value="2"  <?php if($status_flag==2) echo "selected"; ?>>All</option>
  </select>
    
  &nbsp; <input name="Submit" type="submit" value="Search!">  <br></td>
  </tr>
  
 <tr><td colspan="4"></td>
  </tr>
 <tr>
 <td width="60px"></td>
  <td colspan="3"> <div id="livesearch"  style="z-index:1000;position:absolute;_position:absolute;background-color:#CCCCCC;height: 200px;width:200px;display:none;overflow: scroll;" ></div> </td>
   
  </tr>
</table>
  </form> 
 <?php if($total>0) {?> 
 <?php
$row=mysql_fetch_row($result);
?> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<?php
//added on 26/08/2009

while($row1=mysql_fetch_row($result1))
{
$result=mysql_query("select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused, b.wapstatus,b.contenttype,b.hardcodelinks from ppc_users a,ppc_ads b where a.uid=b.uid and b.id='$row1[0]' and $wap_string");
$row=mysql_fetch_row($result);
//added on 26/08/2009
if($row[13]==1)
	{
	$image='wap.png';
	}
	else
	{
		$image='pc.png';
	}
?>

	  <tr>
		<td  colspan="3"><?php 
	if($row[4]==0) { ?><span class="gridheader">Inactive Ad </span><?php } else if($row[4]==1) { ?> 
			<span class="activead">Active Ad </span><?php
			 if(($row[4]==1)&&($row[10]==1))
				echo "&nbsp;<span class=paused_text>&nbsp;Paused by advertiser</span>";
			 } 
			 else {?>
		  <span class="penting">Pending Ad </span>
		  
	<?php } ?><span class="note">&nbsp;&nbsp; Used <?php echo moneyFormat($row[12])." ".$PERIOD;?> </span></td>
		<td width="20%">&nbsp;</td>
		<td width="16%" align="right"><img src="images/<?php echo $image ?>"></td>
	  </tr>
 




	  <tr >
		<td height="28" colspan="5" valign="middle" >
		<?php
		 if($row[8]==0) 
		 { 
			 $dispurl=$row[11];
			 if($dispurl=="")
			 {
			 $dispurl=$row[1];
			 }
			 echo "<div class=\"box\"><a href=\"$row[1]\">$row[0]</a><br>$row[2]<br>$dispurl<br></div>"; 
		 } 
		 	  else  if($row[8]==2) 
	 {
	 
	
$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row[11]' ");
$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row[11]'  "); 

	
	$strFlashHardLinks="";
$strHardLinks="";
$checking_link=$GLOBALS['hardcode_check_url'];		 	
if($row[14]=="swf" && $row[8]=="2")
{

if($row[15] >0)
{
for($i=0;$i<$row[15];$i++)
{
$strHardLinks.="flashvars_".$row[7].".alink".($i+1)."='".$row[1]."';";
$strHardLinks.="flashvars_".$row[7].".atar".($i+1)."='_blank';";


$strFlashHardLinks.="flashvars_tmp_".$row[7].".alink".($i+1)."='".$checking_link."';";
$strFlashHardLinks.="flashvars_tmp_".$row[7].".atar".($i+1)."='_blank';";


}

}
	 ?>
<script type="text/javascript">

var flashvars_tmp_<?php echo $row[7];?> = {};

 <?php echo $strFlashHardLinks; ?>

		  var flashvars_<?php echo $row[7];?> = {};
		  var params_<?php echo $row[7];?> = {};
		  var attributes_<?php echo $row[7];?> = {};
		  //var i=1;
		  
          flashvars_<?php echo $row[7];?>.clickTag = "<?php echo $row[1]; ?>";
		  
		  
		  flashvars_<?php echo $row[7];?>.clickTAG = "<?php echo $row[1]; ?>";
		  flashvars_<?php echo $row[7];?>.clickTARGET = "_blank";
		  
		 <?php echo $strHardLinks; ?>
		 		
		 params_<?php echo $row[7];?>.wmode="transparent";
		 
		 		
	      swfobject.embedSWF("../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row[7]; ?>/<?php echo $row[0]; ?>", "myFlashDivNew_<?php echo $row[7]; ?>", "<?php echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars_<?php echo $row[7];?>,params_<?php echo $row[7];?>,attributes_<?php echo $row[7];?>);
</script>	
	
	
	<table width="100%"   border="0" cellpadding="0" cellspacing="0" class="box">
        <tr>
		<td >
<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0">
<td width="<?php echo $catalog_width; ?>"  height="<?php echo $catalog_height; ?>" ><a href="<?php echo $row[1]; ?>"><div id="myFlashDivNew_<?php echo $row[7]; ?>"></div></a></td>
		  <td align="left" valign="top"><a href="<?php echo $row[1]; ?>"><?php echo $row[9]; ?></a><br>
		    <span><?php echo $row[2]; ?></span></td>
		  </table>		  </td>
        </tr>
    </table>
	 
	 
	 
	 
	 <?php

 } else { 	 
 ?>	 
	 

	 
	   <table width="100%"   border="0" cellpadding="0" cellspacing="0" class="box">
        <tr>
		<td >
<table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0">
<td width="<?php echo $catalog_width; ?>"  height="<?php echo $catalog_height; ?>" ><a href="<?php echo $row[1]; ?>"><img src="../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row[7]; ?>/<?php echo $row[0]; ?>" border="0" ></a></td>
		  <td align="left" valign="top"><a href="<?php echo $row[1]; ?>"><?php echo $row[9]; ?></a><br>
		    <span><?php echo $row[2]; ?></span></td>
		  </table>		  </td>
	     </tr>
    </table>

	 <?php 	
	 } 
	 }
		 else 
		 {
		 
		 
		 $resht=$mysql->echo_one("select height from banner_dimension where id='$row[11]'");
$reswt=$mysql->echo_one("select width from banner_dimension where id='$row[11]'");			 
	 
	 
	 $strFlashHardLinks="";
$strHardLinks="";
$checking_link=$GLOBALS['hardcode_check_url'];		 	
if($row[14]=="swf" && $row[8]=="1")
{

if($row[15] >0)
{
for($i=0;$i<$row[15];$i++)
{
$strHardLinks.="flashvars_".$row[7].".alink".($i+1)."='".$row[1]."';";
$strHardLinks.="flashvars_".$row[7].".atar".($i+1)."='_blank';";


$strFlashHardLinks.="flashvars_tmp_".$row[7].".alink".($i+1)."='".$checking_link."';";
$strFlashHardLinks.="flashvars_tmp_".$row[7].".atar".($i+1)."='_blank';";


}

}
	 ?>
	 
	<script type="text/javascript">

var flashvars_tmp_<?php echo $row[7];?> = {};

 <?php echo $strFlashHardLinks; ?>

		  var flashvars_<?php echo $row[7];?> = {};
		  var params_<?php echo $row[7];?> = {};
		  var attributes_<?php echo $row[7];?> = {};
		  //var i=1;
		  
          flashvars_<?php echo $row[7];?>.clickTag = "<?php echo $row[1]; ?>";
		  
		  
		  flashvars_<?php echo $row[7];?>.clickTAG = "<?php echo $row[1]; ?>";
		  flashvars_<?php echo $row[7];?>.clickTARGET = "_blank";
		  
		 <?php echo $strHardLinks; ?>
		 		
		 params_<?php echo $row[7];?>.wmode="transparent";
		 
		 		
	      swfobject.embedSWF("../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row[7]; ?>/<?php echo $row[2]; ?>", "myFlashDivNew_<?php echo $row[7]; ?>", "<?php echo $reswt; ?>", "<?php echo $resht; ?>", "9.0.0", "",flashvars_<?php echo $row[7];?>,params_<?php echo $row[7];?>,attributes_<?php echo $row[7];?>);
</script>




		<?php	 echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"box\"><a href=\"$row[1]\">"; ?>
			 
			 <div id="myFlashDivNew_<?php echo $row[7]; ?>"></div>	
			<?php echo  "</a></td></tr></table>";

		 
		 
		 

 } else { 	 
	 		 
		 
			 echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"box\"><a href=\"$row[1]\"><img src=\"../".$GLOBALS['banners_folder']."/$row[7]/$row[2]\"  border=\"0\" ></a></td></tr></table>";
			 
			 
			 
			 
			 
			 
			 
			 
			 
		 }
		 }
		  ?>      </td>
	  </tr>
	  <tr  height="25" style="border:1px solid #ddd; border-top-width:0px;">
		<td width="33%"  style="border:1px solid #ddd; border-top-width:0px;">&nbsp; Keyword weightage :<?php echo round($row1[2],4);?></td>
		<td colspan="2"  style="border:1px solid #ddd; border-top-width:0px;"> Click value: <?php echo moneyFormat($row1[4]);?>&nbsp;</td>
		<td colspan="2" style="border:1px solid #ddd; border-top-width:0px;" >Last updated : <?php echo dateFormat($row1[3]);?>&nbsp;</td>
	  </tr>
	  <tr bgcolor="#BCC5C2">
		<td height="25" colspan="2" align="left">&nbsp; <a href="ppc-view-keywords.php?id=<?php echo $row[7]; ?>&wap=<?php echo $row[13]; ?>&url=<?php echo $url; ?>">View ad details</a> |  <a href="ppc-delete-ad.php?category=all&id=<?php echo $row[7]; ?>&wap=<?php echo $row[13]; ?>&url=<?php echo $url; ?>" onclick="return promptuser()">Delete</a> |
		<?php if($row[4]==1) echo '<a href="ppc-change-ad-status.php?category=all&action=block&id='.$row[7].'&wap='.$row[13].'&url='.$url.'">Block Ad</a>';  else echo '<a href="ppc-change-ad-status.php?category=all&action=activate&id='.$row[7].'&wap='.$row[13].'&url='.$url.'">Activate Ad</a>';?>
		
		

		
		
		
		
		
		
		</td>
		<td colspan="3" align="right">Created by :  <a href="view_profile.php?id=<?php echo $row[6];?>"><?php echo $row[5];?></a>&nbsp;|&nbsp;<?php echo $budget_period_unit;?> Budget : <?php  echo moneyFormat($row[3]);?>&nbsp;</td>
	  </tr>
	    <tr >
		<td height="28" colspan="5" valign="middle" >&nbsp;</td></tr>
  <?php
  //added on 26/08/2009
  }
  //added on 26/08/2009
  ?>

<tr height="30px">
    <td colspan="2" ><?php if($total>=1) {?>Showing ads <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;    </td>

    <td colspan="3" align="right" ><?php echo $paging->page($total,$perpagesize,"","ppc-keyword-search.php?s_keyword=$search_keywords".$wap_url); ?></td>
  </tr> 
</table>


<div id="fade" class="black_popup_flash"></div>
<div id="light" class="white_popup_flash"></div>

<?php }
else
{
if($search_keywords!="")
{
echo "<table  width=480><tr><td>No record found</td></tr></table>";}
}
 ?>

<p>&nbsp;</p>
<?php include("admin.footer.inc.php"); ?>