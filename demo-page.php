<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/





include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Inout Adserver Ultimate Demo Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $ad_display_char_encoding; ?>">
<link href="ppc-style.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php

includeClass("Template");

include("common-header.php");
if(isset($_POST['keyword']))
	$keyword=$_POST['keyword'];
else
	$keyword="";

?>
<table width="<?php echo $page_width; ?>"  class="admainbox"  border="0"  cellpadding="0" cellspacing="0">
<tr>
  <td>&nbsp;</td>
</tr>
<form name="demo" action="demo-page.php" enctype="multipart/form-data" method="post">
<tr><td>Show ads for <input type="text" name="keyword" id="keyword"  value="<?php echo $keyword; ?>"/><input type="submit" name="submit" value="Find ads" /> 
(specify  keywords in comma separated format) </td>
</tr>

</form>
<tr>
  <td>&nbsp;</td>
</tr>
<tr><td>

<strong>
<script language="javascript" type="text/javascript" src="http://www.inoutdemo.com/inout_adserver_ultimate/referralUrl.js">	</script>
	<script language="javascript" type="text/javascript">
	document.write('<a href="http://www.inoutdemo.com/inout_adserver_ultimate/index.php?r=2&host='+location['hostname']+'&from='+utf8_encode(location['href'])+'">Click here to signup for '+"Inout Adserver Ultimate"+'</a>');
	</script></strong>


</td>
</tr>

<tr><td>&nbsp;
</td>
</tr>
<tr>
<td   align="left" >Please hover the mouse over the underlined keywords ( web , Inline )
<!--<script language="javascript" type="text/javascript" src="http://www.inoutdemo.com/inout_adserver_ultimate/show-inline-ad.js"></script>
<script language="javascript">
inlinekeyword = "Inline , web"; 
showInlineAds('1573','http://www.inoutdemo.com/inout_adserver_ultimate/show-inline-ads.php');
</script>-->


<script language="javascript" type="text/javascript" src="http://www.inoutdemo.com/inout_adserver_ultimate/show-inline-ad.js"></script>
<script language="javascript">
inlinekeyword = "Inline , web"; 
showInlineAds('1647','http://www.inoutdemo.com/inout_adserver_ultimate/show-inline-ads.php');
</script>


</td>
</tr>
<tr><td>&nbsp;
</td>
</tr>
<tr><td>

<table border="0">
<tr><td>
<!-- Inout Adserver Ultimate - ad code starts -->
<span id="show_ads_90fd56f5612669d8c22ca3fa464a8ad7_6"></span>
<script language="javascript" type="text/javascript" src="http://www.inoutdemo.com/inout_adserver_ultimate/show-ads.js"></script>
<script language="javascript">
if (window.ads_90fd56f5612669d8c22ca3fa464a8ad7 ){ ads_90fd56f5612669d8c22ca3fa464a8ad7+= 1;}else{ ads_90fd56f5612669d8c22ca3fa464a8ad7 =1;}
keyword = "<?php echo $keyword; ?>"; 
setTimeout("showAdsforKeyword(6,336,295,'http://www.inoutdemo.com/inout_adserver_ultimate/publisher-show-ads.php',"+ads_90fd56f5612669d8c22ca3fa464a8ad7+",'ads_90fd56f5612669d8c22ca3fa464a8ad7')",1000*(ads_90fd56f5612669d8c22ca3fa464a8ad7 -1));
ads_90fd56f5612669d8c22ca3fa464a8ad7_6_position=0;
</script>
<!-- Inout Adserver Ultimate - ad code  ends -->

</td><td width="50%">

<!-- Inout Adserver Ultimate with WAP - ad code starts -->
<span id="show_ads_48b12cbd732cf2ac6cd4c13c6121757d_1574"></span>
<script language="javascript" type="text/javascript" src="http://www.inoutdemo.com/inout_adserver_ultimate/show-ads.js"></script>
<script language="javascript">
if (window.ads_48b12cbd732cf2ac6cd4c13c6121757d ){ ads_48b12cbd732cf2ac6cd4c13c6121757d+= 1;}else{ ads_48b12cbd732cf2ac6cd4c13c6121757d =1;}
keyword = "<?php echo $keyword; ?>"; 
setTimeout("showAdsforKeyword(1574,135,335,'http://www.inoutdemo.com/inout_adserver_ultimate/publisher-show-ads.php',"+ads_48b12cbd732cf2ac6cd4c13c6121757d+",'ads_48b12cbd732cf2ac6cd4c13c6121757d')",1000*(ads_48b12cbd732cf2ac6cd4c13c6121757d -1));
ads_48b12cbd732cf2ac6cd4c13c6121757d_1574_position=0;
</script>
<!-- Inout Adserver Ultimate with WAP - ad code  ends -->
</td>
</tr>
</table>

</td>
</tr>



<tr><td>&nbsp;
</td>
</tr>
<tr><td>

<!-- Inout Adserver Ultimate - ad code starts -->
<span id="show_ads_90fd56f5612669d8c22ca3fa464a8ad7_1"></span>
<script language="javascript" type="text/javascript" src="http://www.inoutdemo.com/inout_adserver_ultimate/show-ads.js"></script>
<script language="javascript">
if (window.ads_90fd56f5612669d8c22ca3fa464a8ad7 ){ ads_90fd56f5612669d8c22ca3fa464a8ad7+= 1;}else{ ads_90fd56f5612669d8c22ca3fa464a8ad7 =1;}
keyword = "<?php echo $keyword; ?>"; 
setTimeout("showAdsforKeyword(1,728,105,'http://www.inoutdemo.com/inout_adserver_ultimate/publisher-show-ads.php',"+ads_90fd56f5612669d8c22ca3fa464a8ad7+",'ads_90fd56f5612669d8c22ca3fa464a8ad7')",1000*(ads_90fd56f5612669d8c22ca3fa464a8ad7 -1));
ads_90fd56f5612669d8c22ca3fa464a8ad7_1_position=0;
</script>
<!-- Inout Adserver Ultimate - ad code  ends -->

</td>
</tr>
<tr><td>&nbsp;
</td>
</tr>
<tr><td>
<!-- Inout Adserver Ultimate - ad code starts -->
<span id="show_ads_90fd56f5612669d8c22ca3fa464a8ad7_27"></span>
<script language="javascript" type="text/javascript" src="http://www.inoutdemo.com/inout_adserver_ultimate/show-ads.js"></script>
<script language="javascript">
if (window.ads_90fd56f5612669d8c22ca3fa464a8ad7 ){ads_90fd56f5612669d8c22ca3fa464a8ad7 += 1;}else{ads_90fd56f5612669d8c22ca3fa464a8ad7 =1;}
keyword = "<?php echo $keyword; ?>"; 
setTimeout("showAdsforKeyword(27,800,45,'http://www.inoutdemo.com/inout_adserver_ultimate/show-ads.php',"+ads_90fd56f5612669d8c22ca3fa464a8ad7+",'ads_90fd56f5612669d8c22ca3fa464a8ad7')",1000*(ads_90fd56f5612669d8c22ca3fa464a8ad7 -1));
ads_90fd56f5612669d8c22ca3fa464a8ad7_27_position=8;
</script>
<!-- Inout Adserver Ultimate - ad code  ends -->
</td>
</tr>
<?php
include("common-footer.php");
?>
</table>
</body>
</html>
