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
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;

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

if($wap_flag==1)
{
	$wap_name='wap';
	$image='wap.png';
}
else
{
	$wap_flag=0;
	
	$name_name='';
	$image='pc.png';
	
}

$search_keywords=$_REQUEST['s_keyword'];
phpsafe($search_keywords);
if(trim($search_keywords)!="")
{
$set=1;
//echo $search_keywords;
$result=mysql_query("select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused ,b.wapstatus,b.contenttype,b.hardcodelinks from ppc_users a,ppc_ads b where a.uid=b.uid and b.id='$search_keywords'");
$total=$mysql->echo_one("select count(id) from ppc_ads where id=$search_keywords ");

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


if(substr_count($_SERVER['REQUEST_URI'],"s_keyword=")==0)
{
	if(substr_count($_SERVER['REQUEST_URI'],"?")==0)
		$url=urlencode($_SERVER['REQUEST_URI']."?s_keyword=".$search_keywords);
	else
		$url=urlencode($_SERVER['REQUEST_URI']."&s_keyword=".$search_keywords);	
}		
else 
$url=urlencode($_SERVER['REQUEST_URI']);
?><?php include("admin.header.inc.php"); ?>
<script type="text/javascript">

function trim(stringValue){return stringValue.replace(/(^\s*|\s*$)/, "");}

function verifyform()
{
var a=document.getElementById("s_keyword").value;
//alert(a.trim());
if(trim(a).length==0)
{
alert("Please input an id.");
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

<script  language="javascript" type="text/javascript" src="../swfobject.js"></script>
<script  language="javascript" type="text/javascript" src="loadswfadcontent.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/ads.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Search Ad By Id</td>
  </tr>
</table>
<table  width="100%"><tr><td>
<form action="ppc-search-ad.php?wap=<?php echo $wap_flag ?>" method="POST" name="ppc-search" id="ppc-search" enctype="multipart/form-data" onsubmit=" return verifyform()">

  <labe><span style="font-weight: bold"><br>
  <br>
  Search for :</span>
  <input name="s_keyword" id="s_keyword" type="text"  value="<?php echo $search_keywords;?>" onkeypress="return isNumberKey(event)">
  </label>
  <label>
  <input name="wap" type="hidden" value="<?php echo$wap_flag; ?>">
  <input name="Submit" type="submit" value="Search!">
  </label>

  </form></td></tr></table>
 <?php if($total>0) {?> 
 <?php
$row=mysql_fetch_row($result);
?> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="28%">&nbsp;</td>
  </tr>
  <tr>
    <td  colspan="4"><?php 
if($row[4]==0) { ?><span class="gridheader">Inactive Ad </span><?php } else if($row[4]==1) { ?> 
        <span class="activead">Active Ad </span><?php
		 if(($row[4]==1)&&($row[10]==1))
			echo "&nbsp;<span class=paused_text>&nbsp;Paused by advertiser</span>";
		 } 
		 else {?>
      <span class="penting">Pending Ad </span>
	  
<?php } 
if($row[13]==1)
{
	$image='wap.png';
}
else
{
	$image='pc.png';
}
?><span class="note">&nbsp;&nbsp; Used <?php echo moneyFormat($row[12])." ".$PERIOD;?> </span></td>
    <td width="18%">&nbsp;</td>
    <td width="17%" align="right"><img src="images/<?php echo $image ?>"></td>
  </tr>
 




  <tr >
    <td height="28" colspan="6" valign="middle" >
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
$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row[11]' "); 
	 
	 
$strFlashHardLinks="";
$strHardLinks="";
$checking_link=$GLOBALS['hardcode_check_url'];		 	
if($row[14]=="swf" && $row[8]=="2")
{

if($row[15] >0)
{
for($i=0;$i<$row[15];$i++)
{
$strHardLinks.="flashvars.alink".($i+1)."='".$row[1]."';";
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
		  
          flashvars.clickTag = "<?php echo $row[1]; ?>";
		  
		  
		  flashvars.clickTAG = "<?php echo $row[1]; ?>";
		  flashvars.clickTARGET = "_blank";
		  
		 <?php echo $strHardLinks; ?>
		 		
		 params.wmode="transparent";
		 
		 		
	      swfobject.embedSWF("../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row[7]; ?>/<?php echo $row[0]; ?>", "myFlashDivNew", "<?php echo $catalog_width; ?>", "<?php echo $catalog_height; ?>", "9.0.0", "",flashvars,params,attributes);
</script>	 
	 
	 
	 <table width="100%" height="33" border="0" cellpadding="0" cellspacing="0" class="box"><tr><td ><table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0">
<td width="<?php echo $catalog_width; ?>"  height="<?php echo $catalog_height; ?>" ><a href="<?php echo $row[1]; ?>"><div id="myFlashDivNew"></div>
</a></td><td align="left" valign="top"><a href="<?php echo $row[1]; ?>"><?php echo $row[9]; ?></a><br><span><?php echo $row[2]; ?></span></td>
</table></td></tr></table>

	 
	 
	 

	 
<?php

 } else { 	 
 ?>	 
	 
	 
	 
	 
	 
	 
	 
<table width="100%" height="33" border="0" cellpadding="0" cellspacing="0" class="box"><tr><td ><table width="100%" height="100%" border="0" cellpadding="5" cellspacing="0">
<td width="<?php echo $catalog_width; ?>"  height="<?php echo $catalog_height; ?>" ><a href="<?php echo $row[1]; ?>"><img src="../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row[7]; ?>/<?php echo $row[0]; ?>" border="0" ></a></td><td align="left" valign="top"><a href="<?php echo $row[1]; ?>"><?php echo $row[9]; ?></a><br><span><?php echo $row[2]; ?></span></td>
</table></td></tr></table>






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
$strHardLinks.="flashvars.alink".($i+1)."='".$row[1]."';";
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
		  
          flashvars.clickTag = "<?php echo $row[1]; ?>";
		  
		  
		  flashvars.clickTAG = "<?php echo $row[1]; ?>";
		  flashvars.clickTARGET = "_blank";
		  
		 <?php echo $strHardLinks; ?>
		 		
		 params.wmode="transparent";
		 
		 		
	      swfobject.embedSWF("../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row[7]; ?>/<?php echo $row[2]; ?>", "myFlashDivNew", "<?php echo $reswt; ?>", "<?php echo $resht; ?>", "9.0.0", "",flashvars,params,attributes);
</script>

<?php echo  "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"box\"><a href=\"$row[1]\">"; ?>
		  <div id="myFlashDivNew"></div>	
		  <?php echo "</a></td></tr></table>"; ?>


<?php
 } else { 	 
	 
	  
	 
	 
	 	 echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td class=\"box\"><a href=\"$row[1]\"><img src=\"../".$GLOBALS['banners_folder']."/$row[7]/$row[2]\"  border=\"0\" ></a></td></tr></table>";
	 }
	 }
	  ?>      </td>
  </tr>
  <tr bgcolor="#BCC5C2">
    <td height="25" colspan="3" align="left">&nbsp;&nbsp;<a href="ppc-view-keywords.php?id=<?php echo $row[7]; ?>&wap=<?php echo $wap_flag; ?>&url=<?php echo $url; ?>">Details</a> |  <a href="ppc-delete-ad.php?category=all&id=<?php echo $row[7]; ?>&wap=<?php echo $wap_flag; ?>&url=<?php echo $url; ?>" onclick="return promptuser()">Delete</a> |
    <?php if($row[4]==1) echo '<a href="ppc-change-ad-status.php?category=all&action=block&id='.$row[7].'&wap='.$wap_flag.'&url='.$url.'">Block</a>';  else echo '<a href="ppc-change-ad-status.php?category=all&action=activate&id='.$row[7].'&wap='.$wap_flag.'&url='.$url.'">Activate</a>';?>
	
	
	<?php if($row[8]=="1" && $row[14]=="swf"){?> | <a href="#" onclick="LoadSwfImage('../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row[7]; ?>/<?php echo $row[2]; ?>',<?php echo $reswt; ?>, <?php echo $resht; ?>,'<?php echo $GLOBALS['hardcode_check_url']; ?>',flashvars_tmp)">Check Hard Coded Links</a> <?php }?>
	
	<?php if($row[8]=="2" && $row[14]=="swf"){?> | <a href="#" onclick="LoadSwfImage('../<?php echo $GLOBALS['banners_folder']; ?>/<?php echo $row[7]; ?>/<?php echo $row[0]; ?>',<?php echo $catalog_width; ?>, <?php echo $catalog_height; ?>,'<?php echo $GLOBALS['hardcode_check_url']; ?>',flashvars_tmp)">Check Hard Coded Links</a> <?php }?>
	
	
	
	
	
	
	</td>
	<td colspan="3" align="right">Created by :  <a href="view_profile.php?id=<?php echo $row[6];?>"><?php echo $row[5];?></a>&nbsp;|&nbsp;<?php echo $budget_period_unit;?> Budget : <?php echo moneyFormat( $row[3]) ; ?>&nbsp;</td>
  </tr>
  <tr >
    <td height="28" colspan="4">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<div id="fade" class="black_popup_flash"></div>
<div id="light" class="white_popup_flash"></div>
<?php }
else
{
if($search_keywords!="")
{
echo "<table width=480><tr><td>No record found</td></tr></table>";}
}
 ?>

<p>&nbsp;</p>
<?php include("admin.footer.inc.php"); ?>