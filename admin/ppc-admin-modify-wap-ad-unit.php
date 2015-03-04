
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

include("admin.header.inc.php");
$id=$_GET['id'];
phpsafe($id);
$result=mysql_query("select pad.*,cad.*,pad.credit_text as padtext,cad.title_color as tcolor,cad.desc_color as dcolor,cad.url_color as ucolor,cad.credit_text as ctext,cad.id as cadid from wap_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$id' and cad.wapstatus='1'");
if($row=mysql_fetch_array($result))
{
//sticky ad
if(!isset($_GET['ad_pos']))
	{
	$ad_pos=0;
	}
else
	{
	$ad_pos=$_GET['ad_pos'];
	}
$scroll_ad=$row['scroll_ad'];

if(!isset($_GET['ad_type_sticky']))
	{
	$ad_type_sticky=0;
	$ad_pos=0;
	}
else
	{
	$ad_type_sticky=$_GET['ad_type_sticky'];
	if($ad_type_sticky==0)
		$ad_pos=0;
	}
if(isset($_GET["users_resolution1"]))
$screen_width = $_GET["users_resolution1"];
$screen_width=$screen_width/2;

if(isset($_GET["users_resolution2"]))
$screen_height = $_GET["users_resolution2"];
$screen_height=$screen_height/2;

$iframe_width=$row['width'];
$iframe_height=$row['height'];

$horiz_center=($screen_width- ($iframe_width/2))."px";
$ver_center=($screen_height- ($iframe_height/2))."px";
$frame_val="";
$stickyflag=false;
		switch($ad_pos)
		{
		
			case 1:
			$frame_val="<div  id=\"fl813691\"  style=\"z-index:900;position:fixed;_position: absolute;left:0;top:0\" >";
			$stickyflag=true;
			break;
			case 2:
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:".$horiz_center.";top:0\" >";
			$stickyflag=true;
			break;
			case 3:
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;top:0;right:0; \" >";
			$stickyflag=true;
			break;
			
			case 7:
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:0;bottom:0\" >";
			$stickyflag=true;
			break;
			case 8:
		//	echo "inside switch";
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:".$horiz_center.";bottom:0\" >";
		//	echo $frame_val;			
			$stickyflag=true;
			break;
			case 9:
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;right:0;bottom:0\" >";
			$stickyflag=true;
			break;
			default:
			break;
		}
//sticky ad
if($row['ad_type']==1)
	{
	$ad_type="Text only";
	}
elseif($row['ad_type']==2)
	{
	$ad_type="Banner only";
	}
	elseif($row['ad_type']==4)
	{
	$ad_type="Catalog only";
	}
else
	{
	$ad_type="Text/Banner";
	}
if($row['orientaion']==1)
	{
	$orientation="vertical";
	}
else
	{
	$orientation="Horizontal";
	}
if($row['allow_bordor_type']==1)
	{
	$border_type="Regular";
	}
else
	{
	$border_type="Rounded";
	}
//echo $id;
?>

 <script type="text/javascript" src="../farbtastic/jquery.js"></script>
 <script type="text/javascript" src="../farbtastic/farbtastic.js"></script>
 <link rel="stylesheet" href="../farbtastic/farbtastic.css" type="text/css" />
 <style type="text/css" media="screen">
   .colorwell {
     border: 2px solid #fff;
     width: 6em;
     text-align: center;
     cursor: pointer;
   }
   body .colorwell-selected {
     border: 2px solid #000;
     font-weight: bold;
   }
 </style>
 
 <script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    var f = $.farbtastic('#picker');
    var p = $('#picker').css('opacity', 0.25);
    var selected;
    $('.colorwell')
      .each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
      .focus(function() {
        if (selected) {
          $(selected).css('opacity', 0.75).removeClass('colorwell-selected');
        }
        f.linkTo(this);
        p.css('opacity', 1);
        $(selected = this).css('opacity', 1).addClass('colorwell-selected');
      });
  });
 </script>



<link href="style.css" rel="stylesheet" type="text/css">
<style type="text/css">
dl, dt, dd, ul, ol, li{margin:0;padding:0;vertical-align:baseline;}
</style>
<script language="javascript">

	function index1_ShowTab(id)
{
	if(id=='1')
	{
		document.getElementById('index1_div_1').style.display="";
		document.getElementById('index1_div_2').style.display="none";
		document.getElementById('index1_div_3').style.display="none";
		document.getElementById('index1_div_4').style.display="none";
		
		document.getElementById("index1_li_1").style.background="url(images/li_bgselect.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_3").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_4").style.background="url(images/li_bgnormal.jpg) repeat-x";
		
	}
	if(id=='2')
	{
		document.getElementById('index1_div_1').style.display="none";
		document.getElementById('index1_div_2').style.display="";
		document.getElementById('index1_div_3').style.display="none";
		document.getElementById('index1_div_4').style.display="none";
		
		document.getElementById("index1_li_2").style.background="url(images/li_bgselect.jpg) repeat-x";
		document.getElementById("index1_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_3").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_4").style.background="url(images/li_bgnormal.jpg) repeat-x";
		
	}
	if(id=='3')
	{
		document.getElementById('index1_div_1').style.display="none";
		document.getElementById('index1_div_2').style.display="none";
		document.getElementById('index1_div_3').style.display="";
		document.getElementById('index1_div_4').style.display="none";
		
		document.getElementById("index1_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_3").style.background="url(images/li_bgselect.jpg) repeat-x";
		document.getElementById("index1_li_4").style.background="url(images/li_bgnormal.jpg) repeat-x";
		
	}

if(id=='4')
	{
		document.getElementById('index1_div_1').style.display="none";
		document.getElementById('index1_div_2').style.display="none";
		document.getElementById('index1_div_3').style.display="none";
		document.getElementById('index1_div_4').style.display="";
		
		document.getElementById("index1_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_3").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_4").style.background="url(images/li_bgselect.jpg) repeat-x";
		
	}


}

function index1_Show(id)
{
//alert(id);
if(id=='1')
	{
	if(document.getElementById('r1')!=null)
			document.getElementById('r1').checked="Checked";
		document.getElementById('div11').style.display="";
		document.getElementById('div22').style.display="none";
			document.getElementById('div33').style.display="none";
		
		
	}
	if(id=='2')
	{
		if(document.getElementById('r2')!=null)
			document.getElementById('r2').checked="Checked";
		document.getElementById('div22').style.display="";
		document.getElementById('div11').style.display="none";
			document.getElementById('div33').style.display="none";
	}
	if(id=='3')
	{
		if(document.getElementById('r3')!=null)
			document.getElementById('r3').checked="Checked";
		document.getElementById('div22').style.display="none";
		document.getElementById('div11').style.display="none";
			document.getElementById('div33').style.display="";
		
		
	}
}

	</script>
<script language="javascript" type="text/javascript">
function updateCreditBorderColor(credit_border_id,creditcolor,backgroundcolor)
{
document.getElementById('uc').value=credit_border_id;
document.getElementById('selected_credit').style.color=creditcolor;
document.getElementById('selected_credit').style.background=backgroundcolor;


}
</script>
<script type="text/javascript" src="../dropdown.js"></script>

<!--///////////////////////////-->



<style type="text/css">

<!-- Please don't remove Made by Giorgi Matakheria -->
* html div#fl813691 {position: absolute; overflow:hidden;
top:expression(eval(document.compatMode &&
document.compatMode=='CSS1Compat') ?
documentElement.scrollTop
+(documentElement.clientHeight-this.clientHeight)
: document.body.scrollTop
+(document.body.clientHeight-this.clientHeight));}
#coh963846{display:block; height:15px; line-height:15px; width:<?php echo $iframe_width ?>px;}
#coc67178{float:right; padding:0; margin:0; list-style:none; overflow:hidden; height:15px;border:1px solid grey}
			#coc67178 li{display:inline; }
			#coc67178 li a{background-image:url(images/button.gif); background-repeat:no-repeat; width:30px; height:0; padding-top:15px; overflow:hidden; float:left;}
				#coc67178 li a.close{background-position: 0 0;}
				#coc67178 li a.close:hover{background-position: 0 -15px;}
				#coc67178 li a.min{background-position: -30px 0;}
				#coc67178 li a.min:hover{background-position: -30px -15px;}
				#coc67178 li a.max{background-position: -60px 0;}
				#coc67178 li a.max:hover{background-position: -60px -15px;}
#co453569{display:block; margin:0; padding:0; height:123px;  border-style:solid; border-width:1px; border-color:#111 #999 #999 #111; line-height:1.6em; overflow:hidden;}

</style>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/admin-adunits.php"; ?> </td>
  </tr>
  
  <tr>
   <td   colspan="4" scope="row" class="heading">Edit Ad Unit</td>
  </tr>

</table>
<br />


    <table width="100%"  border="0" cellpadding="0" cellspacing="0"  > 
    <tr height="30px">
	<?php
	if($row['ad_type']==3)
		{
		?>
    <td   id="div_li_1"><label><input type="radio" value="1" name="r1" id="r1" checked="checked" onClick="javascript:index1_Show(this.value);">Text Preview&nbsp;&nbsp;&nbsp;</label>      
    <label>&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="r1" id="r2" onClick="javascript:index1_Show(this.value);">Banner Preview </label>    <!--  <label>&nbsp;&nbsp;&nbsp;<input type="radio" value="3" name="r1" id="r3" onClick="javascript:index1_Show(this.value);">Catalog Preview </label>--></td>
    <?php }
	
	?>
    </tr>
    </table>
<br />


	<?php 
	//if($row[33]==0)
		//{
		//$ad_val=0;
		//}
	//else
		//{
		//$ad_val=15;
		//}
		
		$ad_val=0;
	?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	
<tr><td align="left">
<?php if($stickyflag)
{
		echo "$frame_val"; 
		if($ad_pos>4)
		{
		?>
			  <div id="coh963846">
					<ul id="coc67178">
						<li style="display: inline;" id="pf204652hide"><a class="min" href="javascript:pf204652clickhide();" title="Hide this window">&nbsp;</a></li>
						<li id="pf204652show" style="display: none;"><a class="max" href="javascript:pf204652clickshow();" title="Show this window">&nbsp;</a></li>
						<li id="pf204652close"><a class="close" href="javascript:pf204652clickclose();" title="Close this window">&nbsp;</a></li>
					</ul>
			   </div>
		<?php	   
		}
}
?>
	<div id="div11" style="padding:0px;" >
	 <iframe height="<?php echo $row['height']+$ad_val; ?>" width="<?php echo $row['width']; ?>" frameborder="0" src="custom-wap-ad-unit-preview.php?id=<?php echo $row['cadid']; ?>&uid=0&flag_status=1" ></iframe>
		</div>

 <div id="div22"  style="padding:0px;display:none;">
 <iframe height="<?php echo $row['height']+$ad_val; ?>" width="<?php echo $row['width']; ?>" frameborder="0" src="custom-wap-ad-unit-preview.php?id=<?php echo $row['cadid']; ?>&type=image&uid=0&flag_status=1"></iframe>
   </div>	
   <div id="div33"  style="padding:0px;display:none;">
 <iframe height="<?php echo $row['height']+$ad_val; ?>" width="<?php echo $row['width']; ?>" frameborder="0" src="custom-wap-ad-unit-preview.php?id=<?php echo $row['cadid']; ?>&type=catalog&uid=0&flag_status=1"></iframe>
   </div>	
<?php if($stickyflag)
	{
		if($ad_pos<4)
		{
		?>
			  <div id="coh963846">
					<ul id="coc67178">
						<li style="display: inline;" id="pf204652hide"><a class="min" href="javascript:pf204652clickhide();" title="Hide this window">&nbsp;</a></li>
						<li id="pf204652show" style="display: none;"><a class="max" href="javascript:pf204652clickshow();" title="Show this window">&nbsp;</a></li>
						<li id="pf204652close"><a class="close" href="javascript:pf204652clickclose();" title="Close this window">&nbsp;</a></li>
					</ul>
			   </div>
		<?php	   
		}
		echo "</div>";

		?>
		
		<script>
		pf204652bottomLayer = document.getElementById('fl813691');
		//alert(pf204652bottomLayer);
		
		var pf204652IntervalId = 0;
		var pf204652maxHeight = <?php echo $row['height']+$ad_val+14; ?>;
		var pf204652curHeight = 0;
		var ad_pos=<?php echo $ad_pos; ?>;
		var originalTop=<?php echo $row['height']*-1 -14; ?>;
		var tempTop=<?php echo $row['height']*-1 -14; ?>;
		
		var scrHeight=<?php echo $screen_height*2; ?>;
		var adunitHeight=<?php echo $row['height']+$ad_val; ?>;
		var pf204652curHeight=scrHeight;
		var pf204652minHeight = scrHeight-15;
		var pf204652maxHeight =scrHeight-pf204652maxHeight;
		
		//alert(scrHeight);
		//alert(adunitHeight);
		//alert(scrHeight);
		

		function pf204652show(){
		if(ad_pos>4)
				{
					  pf204652curHeight -= 2;
					  if (pf204652curHeight < pf204652maxHeight){
						clearInterval ( pf204652IntervalId );
					  }
					//  pf204652bottomLayer.style.height = pf204652curHeight+'px';
					 pf204652bottomLayer.style.top = pf204652curHeight+'px';
				  }
		else		
				{
					 tempTop +=2;
					  if (tempTop >= 0){
						clearInterval ( pf204652IntervalId );
					  }
					  pf204652bottomLayer.style.top = tempTop+'px';
				}
		}
		function pf204652hide( ){
			if(ad_pos>4)
					{
		//alert(pf204652curHeight);
			  pf204652curHeight += 3;
			  if (pf204652curHeight > pf204652minHeight){
				clearInterval ( pf204652IntervalId );
			  }
			 // pf204652bottomLayer.style.height = pf204652curHeight+'px';
				pf204652bottomLayer.style.top = pf204652curHeight+'px';
		//  pf204652bottomLayer.style.bottom-=3;
			  }
			 else
				{
			  tempTop -= 3;
			  if (tempTop < originalTop){
				clearInterval ( pf204652IntervalId );
			  }
			  pf204652bottomLayer.style.top = tempTop+'px';
				}
		}
		
		pf204652IntervalId = setInterval ( 'pf204652show()', 5 );

		function pf204652clickhide(){
			document.getElementById('pf204652hide').style.display='none';
			document.getElementById('pf204652show').style.display='inline';
			pf204652IntervalId = setInterval ( 'pf204652hide()', 5 );
		}
		function pf204652clickshow(){
			document.getElementById('pf204652hide').style.display='inline';
			document.getElementById('pf204652show').style.display='none';
			pf204652IntervalId = setInterval ( 'pf204652show(0)', 5);
		}
		function pf204652clickclose(){
			document.body.style.marginBottom = '0px';
			pf204652bottomLayer.style.display = 'none';
		}
	</script>
<?php
}
?>
   </td></tr>
<tr><td><br />
 <div  class="heading">        Html code  </div><br>
<span class="info"><strong>
&#8226;</strong>Please copy the server side code displayed in the text area below and paste it into your web page where you want to show the ads. 
<strong><br>
&#8226;</strong>Please ensure to write your keywords in place of &lt;YOUR KEYWORDS HERE&gt; (in the case of multiple keywords use comma as separator Eg:keyword1,keyword2).
<br>

<strong>&#8226;</strong>Please ensure that proper DOCTYPE is set for the page where the ad code is used.</span>


		</td></tr>
</table>
<br>
<br>

  <table width="100%"  border="0" cellpadding="0"  cellspacing="0" style="border:1px solid #CCCCCC">
  <tr>
  <td>
  <table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus" >
  <tr height="30px" >
    <td align="center"  id="index1_li_1" style="border-left-width:0px; "><a href="javascript:index1_ShowTab('1');"  >PHP code</a></td>
    <td  align="center" id="index1_li_2"><a href="javascript:index1_ShowTab('2');" >JSP code</a></td>
	<td  align="center" id="index1_li_3"><a href="javascript:index1_ShowTab('3');" >PERL code</a></td>
	<td  align="center" id="index1_li_4"><a href="javascript:index1_ShowTab('4');" >ASP code</a></td>
        </tr>
		</table>
	</td>
</tr>
<tr>
<td>
		<?php
		
		$io_eng=$_SERVER['HTTP_HOST'];
	if(substr($io_eng,0,4)=="www.")
	$io_eng=str_replace("www.","",$io_eng);
$io_eng=str_replace(" ","_",$io_eng);
$io_eng=str_replace(".","_",$io_eng);
$io_eng=str_replace("-","_",$io_eng);
$io_eng=removeNumbers($io_eng);

		
		$adb_val="ads_".md5($ppc_engine_name); 
	?>
			  <div id="index1_div_1" >
			  <textarea name="text1"  rows="15" cols="80"  style="border:0px;width:99%;" readonly="readonly">
&lt;?php

$searchKeywords = "&lt;YOUR KEYWORDS HERE&gt;";

$GLOBALS['<?php echo $io_eng; ?>']['pid']='0'; ////////////////////
$GLOBALS['<?php echo $io_eng; ?>']['id']='<?php echo $id; ?>';  ////////////////////
$GLOBALS['<?php echo $io_eng; ?>']['search']=urlencode($searchKeywords);  
$GLOBALS['<?php echo $io_eng; ?>']['ip']=read_global('REMOTE_ADDR');
$GLOBALS['<?php echo $io_eng; ?>']['ref']=read_global('HTTP_REFERER');
$GLOBALS['<?php echo $io_eng; ?>']['url']=read_global('HTTP_HOST') . read_global('REQUEST_URI');
$GLOBALS['<?php echo $io_eng; ?>']['useragent']=read_global('HTTP_USER_AGENT');

<?php echo $io_eng; ?>_set_screen_res();
<?php echo $io_eng; ?>_set_via_and_accept();

function read_global($var) {
  return isset($_SERVER[$var]) ? $_SERVER[$var]: '';
}

function <?php echo $io_eng; ?>_append_url(&$url, $param, $value) {
  $url .=  $param . '=' . urlencode($value).'&';
}

function <?php echo $io_eng; ?>_append_globals(&$url, $param) {
  <?php echo $io_eng; ?>_append_url($url, $param, $GLOBALS['<?php echo $io_eng; ?>'][$param]);
}

function <?php echo $io_eng; ?>_set_screen_res() {
  $screen_res = read_global('HTTP_UA_PIXELS');
  if ($screen_res == '') {
    $screen_res = read_global('HTTP_X_UP_DEVCAP_SCREENPIXELS');
  }
  if ($screen_res == '') {
    $screen_res = read_global('HTTP_X_JPHONE_DISPLAY');
  }
  $res_array = split('[x,*]', $screen_res);
  if (sizeof($res_array) == 2) {
    $GLOBALS['<?php echo $io_eng; ?>']['u_w'] = $res_array[0];
    $GLOBALS['<?php echo $io_eng; ?>']['u_h'] = $res_array[1];
  }
}

function <?php echo $io_eng; ?>_set_muid() {
  $muid = read_global('HTTP_X_DCMGUID');
  if ($muid != '') {
    $GLOBALS['<?php echo $io_eng; ?>']['muid'] = $muid;
  }
  $muid = read_global('HTTP_X_UP_SUBNO');
  if ($muid != '') {
    $GLOBALS['<?php echo $io_eng; ?>']['muid'] = $muid;
  }
  $muid = read_global('HTTP_X_JPHONE_UID');
  if ($muid != '') {
    $GLOBALS['<?php echo $io_eng; ?>']['muid'] = $muid;
  }
  $muid = read_global('HTTP_X_EM_UID');
  if ($muid != '') {
    $GLOBALS['<?php echo $io_eng; ?>']['muid'] = $muid;
  }
}

function <?php echo $io_eng; ?>_set_via_and_accept() {
  $ua = read_global('HTTP_USER_AGENT');
  if ($ua == '') {
     $GLOBALS['<?php echo $io_eng; ?>']['via'] = read_global('HTTP_VIA');
     $GLOBALS['<?php echo $io_eng; ?>']['accept'] = read_global('HTTP_ACCEPT');
  }
}

function <?php echo $io_eng; ?>_get_ad_url() {
  $<?php echo $io_eng; ?>_ad_url = "<?php echo $server_dir; ?>wap-ads.php?" ;
  foreach ($GLOBALS['<?php echo $io_eng; ?>'] as $param =&gt; $value) {
    if (strpos($param, 'url') === 0) {
      $<?php echo $io_eng; ?>_scheme = (read_global('HTTPS') == 'on')
          ? 'https://' : 'http://';
      <?php echo $io_eng; ?>_append_url($<?php echo $io_eng; ?>_ad_url, $param,
                        $<?php echo $io_eng; ?>_scheme . $GLOBALS['<?php echo $io_eng; ?>'][$param]);
    } else {
      <?php echo $io_eng; ?>_append_globals($<?php echo $io_eng; ?>_ad_url, $param);
    }
  }
 $<?php echo $io_eng; ?>_ad_url=substr($<?php echo $io_eng; ?>_ad_url,0,-1);
  return $<?php echo $io_eng; ?>_ad_url;
}

$<?php echo $io_eng; ?>_ad_handle = @fopen(<?php echo $io_eng; ?>_get_ad_url(), 'r');
if ($<?php echo $io_eng; ?>_ad_handle) {
  while (!feof($<?php echo $io_eng; ?>_ad_handle)) {
    echo fread($<?php echo $io_eng; ?>_ad_handle, 8192);
 }
  fclose($<?php echo $io_eng; ?>_ad_handle);
}

?&gt;

</textarea>
		</div>

	    <div id="index1_div_2"  style="display:none;">
			  <textarea name="text2"  rows="15" cols="80"  style="border:0px;width:99%;" readonly="readonly">
&lt;%@ page import="java.io.BufferedReader,
                 java.io.InputStreamReader,
                 java.io.IOException,
                 java.io.UnsupportedEncodingException,
                 java.net.URL,
                 java.net.URLEncoder,
                 java.util.ArrayList,
                 java.util.List" %&gt;
&lt;%!

String searchKeywords = "&lt;YOUR KEYWORDS HERE&gt;";

private static final String ADPAGE =   "<?php echo $server_dir; ?>wap-ads.php?";

private void <?php echo $io_eng; ?>AppendUrl(StringBuilder url, String param, String value)
    throws UnsupportedEncodingException {
  if (value != null) {
    String encodedValue = URLEncoder.encode(value, "<?php echo $ad_display_char_encoding; ?>");
    url.append("&").append(param).append("=").append(encodedValue);
  }
}

private void <?php echo $io_eng; ?>AppendScreenRes(StringBuilder url, String uaPixels,
    String xUpDevcapScreenpixels, String xJphoneDisplay) {
  String screenRes = uaPixels;
  if (screenRes == null) {
    screenRes = xUpDevcapScreenpixels;
  }
  if (screenRes == null) {
    screenRes = xJphoneDisplay;
  }
  if (screenRes != null) {
    String[] resArray = screenRes.split("[x,*]");
    if (resArray.length == 2) {
      url.append("&u_w=").append(resArray[0]);
      url.append("&u_h=").append(resArray[1]);
    }
  }
}

private void <?php echo $io_eng; ?>AppendMuid(StringBuilder url, List&lt;String&gt; muids) {
  for (String muid : muids) {
    if (muid != null) {
      url.append("&muid=").append(muid);
    }
  }
}

private void <?php echo $io_eng; ?>AppendViaAndAccept(StringBuilder url, String via,
    String accept) throws UnsupportedEncodingException {
  <?php echo $io_eng; ?>AppendUrl(url, "via", via);
  <?php echo $io_eng; ?>AppendUrl(url, "accept", accept);
}

StringBuilder <?php echo $io_eng; ?>UrlStr = new StringBuilder(ADPAGE);
%>
<%
<?php echo $io_eng; ?>UrlStr.append("pid=0");
<?php echo $io_eng; ?>UrlStr.append("&id=<?php echo $id; ?>");
<?php echo $io_eng; ?>UrlStr.append("&search=");
<?php echo $io_eng; ?>UrlStr.append(URLEncoder.encode(searchKeywords, "<?php echo $ad_display_char_encoding; ?>"));
<?php echo $io_eng; ?>AppendUrl(<?php echo $io_eng; ?>UrlStr, "ip", request.getRemoteAddr());
<?php echo $io_eng; ?>AppendUrl(<?php echo $io_eng; ?>UrlStr, "ref", request.getHeader("Referer"));
String <?php echo $io_eng; ?>RequestUrl = request.getRequestURL().toString();
if (request.getQueryString() != null) {
  <?php echo $io_eng; ?>RequestUrl += "?" + request.getQueryString().toString();
}
<?php echo $io_eng; ?>AppendUrl(<?php echo $io_eng; ?>UrlStr, "url", <?php echo $io_eng; ?>RequestUrl);
String <?php echo $io_eng; ?>UserAgent = request.getHeader("User-Agent");
<?php echo $io_eng; ?>AppendUrl(<?php echo $io_eng; ?>UrlStr, "useragent", <?php echo $io_eng; ?>UserAgent);
<?php echo $io_eng; ?>AppendScreenRes(<?php echo $io_eng; ?>UrlStr, request.getHeader("UA-pixels"),
    request.getHeader("x-up-devcap-screenpixels"),
    request.getHeader("x-jphone-display"));

if (<?php echo $io_eng; ?>UserAgent == null || <?php echo $io_eng; ?>UserAgent.length() == 0) {
  <?php echo $io_eng; ?>AppendViaAndAccept(<?php echo $io_eng; ?>UrlStr, request.getHeader("Via"),
      request.getHeader("Accept"));
}

try {
  URL <?php echo $io_eng; ?>Url = new URL(<?php echo $io_eng; ?>UrlStr.toString());
  BufferedReader reader = new BufferedReader(new InputStreamReader(<?php echo $io_eng; ?>Url.openStream(), "<?php echo $ad_display_char_encoding; ?>"));
  for (String line; (line = reader.readLine()) != null;) {
    out.println(line);
  }
} catch (Throwable e) {
out.println(e.toString());
}
%&gt;
</textarea>
		

  
        </div>	
		
		
	    <div id="index1_div_3"  style="display:none;">
			  <textarea name="text2" rows="15" cols="80"  style="border:0px;width:99%;" readonly="readonly">
#!C:/Perl/bin/perl.exe


my $searchKeywords = "&lt;YOUR KEYWORDS HERE&gt;";

use LWP::UserAgent;
use Time::HiRes qw(gettimeofday);
use URI::Escape;

sub <?php echo $io_eng; ?>_append_screen_res {
  my $screen_res = $ENV{"HTTP_UA_PIXELS"};
  if ($screen_res == "") {
    $screen_res = $ENV{"HTTP_X_UP_DEVCAP_SCREENPIXELS"};
  }
  if ($screen_res == "") {
    $screen_res = $ENV{"HTTP_X_JPHONE_DISPLAY"};
  }
  my @res_array = split("[x,*]", $screen_res);
  if (@res_array == 2) {
    return "&u_w=" . $res_array[0] . "&u_h=" . $res_array[1];
  }
}

sub <?php echo $io_eng; ?>_append_muid {
  my $muid = $ENV{"HTTP_X_DCMGUID"};
  if ($muid) {
    return "&muid=" . $muid;
  }
  my $muid = $ENV{"HTTP_X_UP_SUBNO"};
  if ($muid) {
    return "&muid=" . $muid;
  }
  my $muid = $ENV{"HTTP_X_JPHONE_UID"};
  if ($muid) {
    return "&muid=" . $muid;
  }
  my $muid = $ENV{"HTTP_X_EM_UID"};
  if ($muid) {
    return "&muid=" . $muid;
  }
}

sub <?php echo $io_eng; ?>_append_via_and_accept {
  if ($_[0] eq "") {
    my $via_and_accept;
    my $via = uri_escape($ENV{"HTTP_VIA"});
    if ($via) {
      $via_and_accept = "&via=" . $via;
    }
    my $accept = uri_escape($ENV{"HTTP_ACCEPT"});
    if ($accept) {
      $via_and_accept = $via_and_accept . "&accept=" . $accept;
    }
    if ($via_and_accept) {
      return $via_and_accept;
    }
  }
}

my $<?php echo $io_eng; ?>_scheme = ($ENV{"HTTPS"} eq "on") ? "https://" : "http://";
my $<?php echo $io_eng; ?>_user_agent = uri_escape($ENV{"HTTP_USER_AGENT"});

my $<?php echo $io_eng; ?>_ad_url = "<?php echo $server_dir; ?>wap-ads.php?" .
"&pid=0".
"&id=<?php echo $id; ?>".
"&search=".uri_escape($searchKeywords).
"&ip=" . uri_escape($ENV{"REMOTE_ADDR"}) .
"&ref=" . uri_escape($ENV{"HTTP_REFERER"}) .
"&url=" . uri_escape($<?php echo $io_eng; ?>_scheme . $ENV{"HTTP_HOST"} . $ENV{"REQUEST_URI"}) .
"&useragent=" . $<?php echo $io_eng; ?>_user_agent .
<?php echo $io_eng; ?>_append_screen_res() .
<?php echo $io_eng; ?>_append_muid() .
<?php echo $io_eng; ?>_append_via_and_accept($<?php echo $io_eng; ?>_user_agent);

my $<?php echo $io_eng; ?>_ua = LWP::UserAgent-&gt;new;
my $<?php echo $io_eng; ?>_ad_output = $<?php echo $io_eng; ?>_ua-&gt;get($<?php echo $io_eng; ?>_ad_url);
if ($<?php echo $io_eng; ?>_ad_output-&gt;is_success) {
  print "Content-type: text/html\n\n";
  print $<?php echo $io_eng; ?>_ad_output-&gt;content;
}
</textarea>
		

  
        </div>	
		
		
	    <div id="index1_div_4"  style="display:none;">
			  <textarea name="text2" rows="15" cols="80"  style="border:0px;width:99%;" readonly="readonly">
&lt;%
Dim searchKeywords
searchKeywords="&lt;YOUR KEYWORDS HERE&gt;"

Function <?php echo $io_eng; ?>ScreenRes()
  Dim screenRes, delimiter, resArray
  screenRes = Request.ServerVariables("HTTP_UA_PIXELS")
  delimiter = "x"
  If IsEmpty(screenRes) Then
    screenRes = Request.ServerVariables("HTTP_X_UP_DEVCAP_SCREENPIXELS")
    delimiter = ","
  End If
  If IsEmpty(screenRes) Then
    screenRes = Request.ServerVariables("HTTP_X_JPHONE_DISPLAY")
    delimiter = "*"
  End If
  resArray = Split(screenRes, delimiter, 2)
  If (UBound(resArray) + 1) = 2 Then
    <?php echo $io_eng; ?>ScreenRes = "&u_w=" & resArray(0) & "&u_h=" & resArray(1)
  End If
End Function

Function <?php echo $io_eng; ?>Muid()
  Dim muid
  muid = Request.ServerVariables("HTTP_X_DCMGUID")
  If Not IsEmpty(muid) Then
    <?php echo $io_eng; ?>Muid = "&muid=" & muid
  End If
  muid = Request.ServerVariables("HTTP_X_UP_SUBNO")
  If Not IsEmpty(muid) Then
    <?php echo $io_eng; ?>Muid = "&muid=" & muid
  End If
  muid = Request.ServerVariables("HTTP_X_JPHONE_UID")
  If Not IsEmpty(muid) Then
    <?php echo $io_eng; ?>Muid = "&muid=" & muid
  End If
  muid = Request.ServerVariables("HTTP_X_EM_UID")
  If Not IsEmpty(muid) Then
    <?php echo $io_eng; ?>Muid = "&muid=" & muid
  End If
End Function

Function <?php echo $io_eng; ?>ViaAndAccept(<?php echo $io_eng; ?>UserAgent)
  If Len(<?php echo $io_eng; ?>UserAgent) = 0 Then
    Dim via
    via = Server.URLEncode(Request.ServerVariables("HTTP_VIA"))
    If Not IsEmpty(via) Then
      <?php echo $io_eng; ?>ViaAndAccept = "&via=" & via
    End If
    Dim accept
    accept = Server.URLEncode(Request.ServerVariables("HTTP_ACCEPT"))
    If Not IsEmpty(accept) Then
      <?php echo $io_eng; ?>ViaAndAccept = <?php echo $io_eng; ?>ViaAndAccept & "&accept=" & accept
    End If
  End If
End Function

Dim  <?php echo $io_eng; ?>Scheme,<?php echo $io_eng; ?>UserAgent
<?php echo $io_eng; ?>Scheme = "http://"
<?php echo $io_eng; ?>UserAgent = Server.URLEncode(Request.ServerVariables("HTTP_USER_AGENT"))
If StrComp(Request.ServerVariables("HTTPS"), "on") = 0 Then <?php echo $io_eng; ?>Scheme = "https://"

Dim <?php echo $io_eng; ?>Url, <?php echo $io_eng; ?>Output
<?php echo $io_eng; ?>Url = "<?php echo $server_dir; ?>wap-ads.php?" &_
  "&pid=0" &_
  "&id=<?php echo $id; ?>" &_
  "&search=" & Server.URLEncode(searchKeywords) &_
  "&ip=" & Server.URLEncode(Request.ServerVariables("REMOTE_ADDR")) &_
  "&ref=" & Server.URLEncode(Request.ServerVariables("HTTP_REFERER")) &_
  "&url=" & Server.URLEncode(<?php echo $io_eng; ?>Scheme & Request.ServerVariables("HTTP_HOST") & Request.ServerVariables("URL")) &_
  "&useragent=" & <?php echo $io_eng; ?>UserAgent &_
  <?php echo $io_eng; ?>ScreenRes() &_
  <?php echo $io_eng; ?>Muid() &_
  <?php echo $io_eng; ?>ViaAndAccept(<?php echo $io_eng; ?>UserAgent)

Set <?php echo $io_eng; ?>Output = Server.CreateObject("MSXML2.ServerXMLHTTP")
<?php echo $io_eng; ?>Output.Open "GET", <?php echo $io_eng; ?>Url, false
<?php echo $io_eng; ?>Output.Send
Response.Write(<?php echo $io_eng; ?>Output.responseText)
%&gt;
</textarea>
		

  
        </div>	
		
  	  </td>
             
            </tr>
          </table>
<br />

<div  class="heading">        Modify Ad Unit settings  </div><br>
		
<form action="ppc-admin-update-wap-adunits.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="users_resolution1" id="users_resolution1" value="0">
<input type="hidden" name="users_resolution2"  id="users_resolution2" value="0">
<table width="100%"  border="0" cellpadding="0" cellspacing="0"  >
<tr height="25px">
    <td width="30%" height="33">Preferred Language</td>
    
    <td width="30%">
   <select name="language" id="language" >

<?php 
$result=mysql_query("select adlang from ppc_custom_ad_block where id='$id'");
$db=mysql_fetch_row($result);

$res=mysql_query("select id,language,code from adserver_languages  where status='1'");

while($rr=mysql_fetch_row($res))
	{?>
		<option value="<?php echo $rr[0]; ?>" <?php if($db[0]==$rr[0]) {?>selected="selected" <?php } ?>><?php echo$rr[1];  ?></option>
		
<?php 	}
	?><option value="0" <?php if($db[0]==0) echo "selected"; ?>>Any Languages</option>
</select>
    
    </td>
	<td width="40%"> </td>
    </tr>
<tr><td width="" height="27">Ad unit </td>
  <td><label>
    <input name="adunit_name" type="text" id="adunit_name" value="<?php echo $row['name']; ?>"/>
  </label></td>
  <td>&nbsp;</td>
</tr>
<tr  >
  <td height="28"  >Ad type </td>
  <td><?php echo $ad_type; ?></td>
  <td>&nbsp;</td>
</tr>
<tr height="">
<td height="30" align="left">Credit text</td>
            <td height="30" align="left">

<script language="javascript" type="text/javascript">
function updateCreditBorderColorNew(crid,crname,crtype)
{
document.getElementById('cc').value=crid;
if(crtype==1)
{
document.getElementById('selected_credit_cr').innerHTML ='<img src="../credit-image/'+crid+'/'+crname+'" width="200" height="15">';
}
else if(crtype==0)
{

document.getElementById('selected_credit_cr').innerHTML =crname;
}
}
</script>
			<?php 
			$selectedcredit="";
			$crow=mysql_query("select * from ppc_publisher_credits where parent_id='0'");	
			
			$Credit=$Credit.'<li ><a onMouseOver="javascript:updateCreditBorderColorNew(\'0\',\'--Select--\',\'0\')">--Select--</a></li>';
			while($row1=mysql_fetch_row($crow))
		    {
			if($row1[4]==1)
			{
		    if($row['ctext']==$row1[0])
			{
			$selectedcredit='<span id= "selected_credit_cr" style="cursor:default;"><img src="../credit-image/'.$row1[0].'/'.$row1[1].'" width="200" height="15"/></span>'; 
			}
			if($selectedcredit=="")
			{
			$selectedcredit='<span id= "selected_credit_cr" style="cursor:default">--Select--</span>'; 
						
			}
		
			$Credit=$Credit.'<li ><a  onMouseOver="javascript:updateCreditBorderColorNew(\''.$row1[0].'\',\''.$row1[1].'\',\''.$row1[4].'\')" ><img  src="../credit-image/'.$row1[0].'/'.$row1[1].'" style="width:200px;height:15px;" /></a></li>';
			?>
				<?php
				}
				else if($row1[4]==0)
				{
				if($row['ctext']==$row1[0])
			{
			$selectedcredit='<span id= "selected_credit_cr" >'.substr($row1[1],0,72).'</span>'; 
			}
			if($selectedcredit=="")
			{
			$selectedcredit='<span id= "selected_credit_cr"  >--Select--</span>'; 
			}
			$Credit=$Credit.'<li ><a onMouseOver="javascript:updateCreditBorderColorNew(\''.$row1[0].'\',\''.substr($row1[1],0,72).'\',\''.$row1[4].'\')">'.substr($row1[1],0,72).'</a></li>';
				
				
				}
			}
			//echo htmlspecialchars($Credit);
			//exit(0);
			?>
	<dl class="dropdown" style="position:absolute;z-index:1000" >
	<dt id="credit-ddheader" onMouseOver="ddMenu('credit',1)" onMouseOut="ddMenu('credit',-1)" ><?php echo $selectedcredit; ?> </dt>
	<dd id="credit-ddcontent" onMouseOver="cancelHide('credit')" onClick="ddMenu('credit',-1)" onMouseOut="ddMenu('credit',-1)"  style="top:15px;"> 
	  <ul ><div style="background-color:#CCCCCC;">
	    <?php echo $Credit; ?></div>
	    </ul>
	</dd>
			</dl>
	<input name="cc" type="hidden" id="cc" value="<?php echo $row['ctext']; ?>" >			


</td><td align="left" height="50px">&nbsp;</td></tr>













 <!--<?php //$result_credit=mysql_query("select * from ppc_publisher_credits where  parent_id='0'");?><td height="30" align="left">Credit text</td><td height="30" align="left"><select name="credit_text" size="1" id="credit_text"><option value="0" <?php //if($row['ctext']==0) echo "selected"; ?>>--Select--</option><?php //while($credit_row=mysql_fetch_row($result_credit)){?><option value="<?php //echo $credit_row[0]; ?>" <?php //if($credit_row[0]==$row['ctext']) echo "selected"; ?>><?php //echo $credit_row[1]; ?></option><?php //} ?></select></td><td align="left">&nbsp;</td></tr>-->









<tr height="25px">
<?php

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

if($db[0]==0)  //anylanguages
{
	if($clan=='en')
	{
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$row['ctext']."' ");
	}
	else
	{
	//echo "select credit from ppc_publisher_credits where parent_id=".$res['ctext']." and language_id='$lanid'";
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$row['ctext']." and language_id='$lanid'");
		if($credit_text=='')
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$row['ctext']."' ");
	}
}
else
{
		
		if($db[0]==$lanid )
		{
				if($clan=='en')
					$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$row['ctext']."' ");
				else
				{
					 $credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$row['ctext']." and language_id='".$db[0]."'");
				}
		}
		else
		{
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$row['ctext']." and language_id='".$db[0]."'");
			if($credit_text=='')
				$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$row['ctext']." and language_id='$lanid'");
		}
		if($credit_text=='')
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$row['ctext']."' ");
			
		
}
//if($db[0]==0)
//{
//	$clan=$mysql->echo_one("select value from ppc_settings where name='client_language'");
//	$lanid=$mysql->echo_one("select id from adserver_languages where code='$clan'");
//$parid1=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='$lanid'");
//$fl=1;
//}
//else
//{
//$parid1=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='$db[0]'");
//}
//if($parid1==0)
//{
//if($fl==1)
//	{
//		$db[0]=$lanid;
//	}
//	$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where id=\"".$row['ctext']."\" and language_id='$db[0]'");
//}
//else
//{
//if($fl==1)
//	{
//		$db[0]=$lanid;
//	}
//$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=\"".$row['ctext']."\" and language_id='$db[0]'");//echo $res['credit_text'];
//}

$res1=mysql_query("select * from ppc_credittext_bordercolor order by id DESC");


$Credit="";
$selected_credit="";


$cr_type=0;
$cr_type=$mysql->echo_one("select credittype from ppc_publisher_credits where id='".$row['ctext']."'");

		while($row1=mysql_fetch_row($res1))
		{
		 if($row1[0]==$row['credit_color'])
		 	{
			if($cr_type==0)
			$selected_credit='<span id= "selected_credit" style="cursor:default;padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';">'.substr($credit_text,0,72).'</span>'; 
			else
			$selected_credit='<span id= "selected_credit" style="cursor:default;padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';">credit text</span>';
			}

if($cr_type==0)				
$Credit=$Credit."<li ><a onMouseOver=\"javascript:updateCreditBorderColor($row1[0]  ,  '$row1[1]' ,  '$row1[2]' ) \" style=\"background-color:$row1[2];color:$row1[1];\">".substr($credit_text,0,72)."</a></li>";
else
$Credit=$Credit."<li ><a onMouseOver=\"javascript:updateCreditBorderColor($row1[0]  ,  '$row1[1]' ,  '$row1[2]' ) \" style=\"background-color:$row1[2];color:$row1[1];\">credit text</a></li>";
				$i++;
	   } 
	  ?>
  <td height="29">Credit text / border color </td>
  <td ><dl class="dropdown">
<dt id="three-ddheader" onMouseOver="ddMenu('three',1)" onMouseOut="ddMenu('three',-1)"><?php echo $selected_credit; ?></dt>
<dd id="three-ddcontent" onMouseOver="cancelHide('three')" onClick="ddMenu('three',-1)" onMouseOut="ddMenu('three',-1)">
<ul><?php echo $Credit; ?></ul></dd></dl><input name="uc" type="hidden" id="uc"  value="<?php echo $row['credit_color']; ?>"></td>
</tr>
<tr height="">
  <td height="30">Border type </td>
  <td><?php if($row['ad_type']!=2){ ?><select name="border_type" size="1" id="border_type">
    <option value="1" <?php if($row['bordor_type']==1) echo "selected"?>>Regular</option>
    <option value="0" <?php if($row['bordor_type']==0) echo "selected"?>>Rounded</option>
 </select>
    <?php } 
	else 
	{
		if($row['bordor_type']==1) echo "Regular";
		if($row['bordor_type']==0) echo "Rounded";
	}
	?> <span class="info">(Note:Border type will be set to regular style for image credit texts.)</span></td>
  <td>&nbsp;</td>
</tr>
<?php 
//echo $row[3];
if($row['ad_type']!=2){


if($row['ad_type']==4) { ?>

<tr  >
  <td height="27"  >No. of Catalog ads </td>
  <td><?php echo $row['no_of_catalog_ads']; ?></td>
  <td>&nbsp;</td>
</tr>


<?php
}
else
{
?>

<tr  >
  <td height="27"  >No. of text ads </td>
  <td><?php echo $row['no_of_text_ads']; ?></td>
  <td>&nbsp;</td>
</tr>

<?php
}
?>
<tr  >
  <td height="30">Text/Catalog Ad orientation </td>
  <td><?php echo $orientation; ?></td>
  <td>&nbsp;</td>
</tr>
<tr height="50px">
  <td>Ad title color </td>
  <td><div class="form-item"><input name="color1" type="text" ID="color1" class="colorwell"  style="background-color:<?php echo $row['title_color']; ?>" value="<?php echo $row['title_color']; ?>"  ></div>
            &nbsp;</td>
  <td rowspan="4"><div id="picker" ></div></td>
</tr>
<tr height="50px">
  <td>Ad description color </td>
 <td><div class="form-item"><input  type="text" id="color2" name="color2" class="colorwell"  value="<?php echo $row['desc_color']; ?>"  style="background-color:<?php echo $row['desc_color']; ?>"></div>
            &nbsp;</td>
</tr>
<tr height="50px">
  <td>Ad display url color </td>
<td><div class="form-item"><input  type="text" id="color3" name="color3" class="colorwell"  value="<?php echo $row['url_color']; ?>" style="background-color:<?php echo $row['url_color']; ?>"></div>
            &nbsp;</td>
</tr>
<tr height="50px">
  <td>Background color </td>
 <td><div class="form-item"><input ype="text" id="color4" name="color4" class="colorwell" value="<?php echo $row['bg_color']; ?>" style="background-color:<?php echo $row['bg_color']; ?>"></div>
            &nbsp;</td>
</tr>
<?php } ?>

<tr height="50px">
  <td>Include adult ad</td>
 <td> &nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="adult_status" value="0" id="adult_status_no"  <?php if($row['adult_status']==0) { ?> checked="checked" <?php } ?> > &nbsp;
                   Yes&nbsp;<input type="radio" name="adult_status" value="1" id="adult_status_yes"   <?php if($row['adult_status']==1) { ?> checked="checked" <?php } ?> > &nbsp;
                   &nbsp  </td>
</tr>

<tr >
<td colspan="3">
<table width="100%" style="display:<?php if($ad_type_sticky==0) echo "none"?>" id="sticky_tab" cellpadding="0" cellspacing="0"> 
				<tr>
				<td width="30%"  >Sticky ad position&nbsp;</td>
				<td width="70%" ><select name="sticky_ad_pos" size="1" id="sticky_ad_pos">
							<option value="1" selected <?php if($ad_pos==1) echo "selected"?>>Left x Top</option>
							<option value="2" <?php if($ad_pos==2) echo "selected"?>>Center x Top</option>
							<option value="3" <?php if($ad_pos==3) echo "selected"?>>Right  x Top</option>
							<option value="7"  <?php if($ad_pos==7) echo "selected"?>>Left x Bottom</option>
							<option value="8" <?php if($ad_pos==8) echo "selected"?>>Center x Bottom</option>
							<option value="9" <?php if($ad_pos==9) echo "selected"?>>Right  x Bottom</option>
	                </select>
		  &nbsp;</td>
				</tr>
		
			</table><input type="hidden" name="ad_type_sticky" value="0" />			</td>
</tr>
<?php if($row['ad_type']!=2) { 
 if($row['ad_type']!=4) { 
?>
<tr>
  <td>&nbsp;</td>
  <td><input type="hidden" name="scroll_ad" value="0" />&nbsp;</td>
  <td>&nbsp;</td>
</tr>
<?php } } ?>
<tr>
  <td>&nbsp;</td>
  <td> <input type="submit" name="Submit" value="Update &amp; Preview" /></td>
  <td>&nbsp;</td>
</tr>

</table>
</form>
<?php
?><br>
<br>
<?php
}
else
{
echo "<br><strong class=\"already\">Invalid id.</strong><br>";
}
 include("admin.footer.inc.php");
?>
<script language="javascript">
<?php if($row['ad_type']!=3)
{ ?>
index1_Show(<?php echo $row['ad_type'] ;?>);
<?php } ?>
 index1_ShowTab(1);
function  change_type()
	{
	//alert("hai");
	if(document.getElementById('ad_type_sticky').value==1)
		{
		document.getElementById('sticky_tab').style.display="";
		}
	else
		{
		document.getElementById('sticky_tab').style.display="none";
		}
	}
//change_type();	


<?php if($ad_type_sticky!=0){?>

writeCookie();
function writeCookie()
{
		var ie=document.all && !window.opera;
		//var dom=document.getElementById
		var iebody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body ;
		ht=(ie)? iebody.clientHeight: window.innerHeight ;//screen.height
		//alert(ht);
		wt=(ie)? iebody.clientWidth : window.innerWidth ;//screen.width

var the_cookie1 = "users_resolution1="+ wt;
//var the_cookie1 = the_cookie1 + ";expires=" + the_cookie_date;
document.cookie=the_cookie1

var the_cookie2 = "users_resolution2="+ ht;
//var the_cookie2 = the_cookie2+ ";expires=" + the_cookie_date;
document.cookie=the_cookie2
document.getElementById('users_resolution1').value=wt;
document.getElementById('users_resolution2').value=ht;
//location = 'ppc-admin-modify-ad-unit.php';
} 
<?php } ?>
index1_Show(1);
</script>
