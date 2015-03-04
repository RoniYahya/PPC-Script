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
?>
<?php include("admin.header.inc.php"); ?>
<script language="javascript">
function check_value()
				{
					 if (trim(document.getElementById('ppc_engine_name').value).length == 0)
					 	{
						alert("Please enter adserver name ");
						 return false;
						 }
				
					 if (trim(document.getElementById('ad_display_char_encoding').value).length == 0)
					 	{
						alert("Please enter default charset  ");
						 return false;
						 }
				
					
				}
			function check_adsettings()
				{
					 
				/*	 if (trim(document.getElementById('ageing').value).length == 0)
					 	{
						alert("Please enter ageing priority ");
						 return false;
						 }
					*/


				}
			function trim(stringValue){
					return stringValue.replace(/(^\s*|\s*$)/, "");
					}
			function emailsettings()
				{
				
					 if (trim(document.getElementById('admin_general_notification_email').value).length == 0)
					 	{
						alert("Please enter admin notification email ");
						 return false;
						 }
					
			
					 if (trim(document.getElementById('admin_payment_notification_email').value).length == 0)
					 	{
						alert("Please enter payment notification email");
						 return false;
						 }
						 
											 if (trim(document.getElementById('minimum_acc_balance').value).length == 0)
					 	{
						alert("Please enter minimum advertiser account balance for email notification. ");
						 return false;
						 } 
				}
				function formatsettings()
				{
				
				if(trim(document.getElementById('thousand_sp').value).length == 0)				
				{
				alert("Please enter thousand separator");
				
						 return false;
				}
				if(trim(document.getElementById('thousand_sp').value).length >1)				
				{
				alert("Thousand separator must be one character");
						 return false;
				}
				if(trim(document.getElementById('decimal_sp').value).length == 0)				
				{
				alert("Please enter decimal separator");
				
						 return false;
				}
				if(trim(document.getElementById('decimal_sp').value).length > 1)				
				{
				alert("Decimal separator must be one character");
				
						 return false;
				}
				
				if(trim(document.getElementById('nodecimal_pl').value).length == 0)				
				{
				alert("Please enter No. of  decimal places");
				
						 return false;
				}
				if(trim(document.getElementById('nodecimal_pl').value).length > 1)				
				{
				alert("No. of  decimal places must be one digit");
				
						 return false;
				}
				}
				
</script>
<script type="text/javascript">


var dateFormat = function () {
	var	token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
		timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
		timezoneClip = /[^-+\dA-Z]/g,
		pad = function (val, len) {
			val = String(val);
			len = len || 2;
			while (val.length < len) val = "0" + val;
			return val;
		};

	// Regexes and supporting functions are cached through closure
	return function (date, mask, utc) {
		var dF = dateFormat;

		// You can't provide utc if you skip other args (use the "UTC:" mask prefix)
		if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
			mask = date;
			date = undefined;
		}

		// Passing date through Date applies Date.parse, if necessary
		date = date ? new Date(date) : new Date;
		if (isNaN(date)) throw SyntaxError("invalid date");

		mask = String(dF.masks[mask] || mask || dF.masks["default"]);

		// Allow setting the utc argument via the mask
		if (mask.slice(0, 4) == "UTC:") {
			mask = mask.slice(4);
			utc = true;
		}

		var	_ = utc ? "getUTC" : "get",
			d = date[_ + "Date"](),
			D = date[_ + "Day"](),
			m = date[_ + "Month"](),
			y = date[_ + "FullYear"](),
			H = date[_ + "Hours"](),
			M = date[_ + "Minutes"](),
			s = date[_ + "Seconds"](),
			L = date[_ + "Milliseconds"](),
			o = utc ? 0 : date.getTimezoneOffset(),
			flags = {
				d:    d,
				dd:   pad(d),
				ddd:  dF.i18n.dayNames[D],
				dddd: dF.i18n.dayNames[D + 7],
				m:    m + 1,
				mm:   pad(m + 1),
				mmm:  dF.i18n.monthNames[m],
				mmmm: dF.i18n.monthNames[m + 12],
				yy:   String(y).slice(2),
				yyyy: y,
				h:    H % 12 || 12,
				hh:   pad(H % 12 || 12),
				H:    H,
				HH:   pad(H),
				M:    M,
				MM:   pad(M),
				s:    s,
				ss:   pad(s),
				l:    pad(L, 3),
				L:    pad(L > 99 ? Math.round(L / 10) : L),
				t:    H < 12 ? "a"  : "p",
				tt:   H < 12 ? "am" : "pm",
				T:    H < 12 ? "A"  : "P",
				TT:   H < 12 ? "AM" : "PM",
				Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
				o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
				S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
			};

		return mask.replace(token, function ($0) {
			return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
		});
	};
}();

// Some common format strings
dateFormat.masks = {
	"default":      "ddd mmm dd yyyy HH:MM:ss",
	shortDate:      "m/d/yy",
	mediumDate:     "mmm d, yyyy",
	longDate:       "mmmm d, yyyy",
	fullDate:       "dddd, mmmm d, yyyy",
	shortTime:      "h:MM TT",
	mediumTime:     "h:MM:ss TT",
	longTime:       "h:MM:ss TT Z",
	isoDate:        "yyyy-mm-dd",
	isoTime:        "HH:MM:ss",
	isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
	isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
	dayNames: [
		"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
		"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
	],
	monthNames: [
		"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
		"January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
	]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
	return dateFormat(this, mask, utc);
};
function FormatNumberBy3(num,rnd, decpoint, sep) {

if(rnd>0)
num+=".";
for(i=0;i<rnd;i++)
	num+="9";


  // check for missing parameters and use defaults if so
  if (arguments.length == 3) {
    sep = ",";
  }
  if (arguments.length == 2) {
    sep = ",";
    decpoint = ".";
  }
  // need a string for operations
  num = num.toString();
  // separate the whole number and the fraction if possible
  a = num.split(".");
  x = a[0]; // decimal
  y = a[1]; // fraction
  z = "";


  if (typeof(x) != "undefined") {
    // reverse the digits. regexp works from left to right.
    for (i=x.length-1;i>=0;i--)
      z += x.charAt(i);
    // add seperators. but undo the trailing one, if there
    z = z.replace(/(\d{3})/g, "$1" + sep);
    if (z.slice(-sep.length) == sep)
      z = z.slice(0, -sep.length);
    x = "";
    // reverse again to get back the number
    for (i=z.length-1;i>=0;i--)
      x += z.charAt(i);
    // add the fraction back in, if it was there
    if (typeof(y) != "undefined" && y.length > 0)
      x += decpoint + y;
  }
 
  return x;
}

function showdetails()
{

var dd;
var aa=document.getElementById('day_of_week').value;
if(aa=="%a")
{
 dd="ddd";

}
if(aa=="%A")
{
dd="dddd";

}
if(aa=="0")
{
dd=" ";
}
return dd;
}
function showdt()
{
var dt;
var mn=document.getElementById('day_of_month').value;

if(mn=="%d")
{
//alert("dfgfdg");
dt="d";
}
if(mn=="%e")
{
dt="dd";
}

return dt;
}
function showmonth()
{
var mm;
if(document.getElementById('month').value=="%m")
{
mm="mm";

}
if(document.getElementById('month').value=="%b")
{
mm="mmm";

}
if(document.getElementById('month').value=="%B")
{
mm="mmmm";

}

return mm;
}
 function showyr()
 {
 var yr;
 if(document.getElementById('year').value=="%y")
 {
 yr="yy";
 }
 if(document.getElementById('year').value=="%Y")
 {
 yr="yyyy";
 }

 return yr;
 }
 function showdtsepa()
 {
 var sp;
// var sq=document.getElementById('date_separator').value);
// alert(sq);
 if(document.getElementById('date_separator').value=="/")
 {
 sp="/";
 }
 if(document.getElementById('date_separator').value==":")
 {
 sp=":";
 }
 if(document.getElementById('date_separator').value==".")
 {
 sp=".";
 }
 if(document.getElementById('date_separator').value=="-")
 {
 sp="-";
 }
 if(document.getElementById('date_separator').value=="sp")
 {
 sp=" ";
 }
 return sp;
 
 }
function datefor()
{
var dp;
if(document.getElementById('day-position').value=="dayamonth")
 {
 dp="dayamonth";
 }
 if(document.getElementById('day-position').value=="daybmonth")
 {
 dp="daybmonth";
 }
 return dp;
}
 function showdates()
 {
//alert("dd");
 var dt=showdetails();
 var de=showdt();
 var dm=showmonth();
 var dy=showyr();
 var dtsp=showdtsepa();
 var dp1=datefor();
 var now = new Date();

 if(dp1=="dayamonth")
 {
var ss=dateFormat(now,dt+" "+dm+dtsp+de+dtsp+dy);
 
}
if(dp1=="daybmonth")
 {
 var ss=dateFormat(now,dt+" "+de+dtsp+dm+dtsp+dy);
 
 }
document.getElementById('date').innerHTML="<strong>"+ss+"</strong>";
 }
 
 function tformat()
 {
 var fr;
var ff=document.getElementById('hourfmt').value;
 if(ff=="12hour")
 {
  
 fr="1";
 }
 if(ff=="24hour")
 {
 fr="2";
 }
 return fr;
 }
function shtimesp()
{
var tsp;
if(document.getElementById('time_separator').value=="/")
 {
 tsp="/";
 }
 if(document.getElementById('time_separator').value==":")
 {
 tsp=":";
 }
 if(document.getElementById('time_separator').value==".")
 {
 tsp=".";
 } 
  if(document.getElementById('time_separator').value=="sp")
 {
 tsp=" ";
 }
 return tsp;
}
function shtimes()
{

var hr=document.getElementById('hour').value;
var mt=document.getElementById('minute').value;
var se=document.getElementById('second').value;
var ft=tformat();
var tspe=shtimesp();
var now1= new Date();
//alert("HH"+tspe+"MM"+tspe+"ss");
if(ft=="2")
{
var st=dateFormat(now1,"HH"+tspe+"MM"+tspe+"ss");

}
if(ft=="1")
{
var st=dateFormat(now1,"hh"+tspe+"MM"+tspe+"ss"+" "+"TT");
}
document.getElementById('time').innerHTML="<strong>"+st+"</strong>";
}
function shnum()
{
var th=document.getElementById('thousand_sp').value;
var de=document.getElementById('decimal_sp').value;
var no=document.getElementById('nodecimal_pl').value;
var nn=FormatNumberBy3("1234512345",no,de,th);
document.getElementById('numb').innerHTML="<strong>"+nn+"</strong>";
}
function shmony()
{
var a=document.getElementById('currency-style').value;
var b=document.getElementById('currency-position').value;
var mm=2500;
if(a=="$")
{
sy="$";
}
if(a=="$$")
{

sy="USD";
}
if(b=="0")
{
var my=sy+" "+mm;
}
if(b=="1")
{
var my=mm+" "+sy;
}
 document.getElementById('money').innerHTML="<strong>"+my+"</strong>";
}

</script>
<table   border="0" width="100%"><tr><td width="218" height="65" colspan="4" scope="row" class="heading"> 
  Basic Configurations </td></tr></table>
  
  <table width="100%"  height="190px" border="0" cellspacing="0" cellpadding="0"  style="border :1px solid #CCCCCC; ">
    <tr>
      <td  valign="top" >
<div style="clear: both"></div>

<div align="left" width: 100%>

<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus"  >  <tr height="30px">
    <td align="center"  id="index1_li_1" ><a href="javascript:index1_ShowTab('1');"  >Basic Settings</a></td>
    <td  align="center" id="index1_li_2"><a href="javascript:index1_ShowTab('2');" >Advanced Settings</a></td>
    <td  align="center" id="index1_li_3" ><a href="javascript:index1_ShowTab('3');" >Email Settings</a></td>
   <td  align="center" id="index1_li_4" ><a href="javascript:index1_ShowTab('4');" >Format Settings</a></td>
  </tr>
</table>





</div>
<div style="clear: both"></div></td>
    </tr>
  

    <tr bordercolor="#FFFFFF"  >
      <td width="100%" valign="top"   >	
  


		<div id="index1_div_1" style="padding:5px;" class="div_font_style">
		  <form name="form1" method="post" action="ppc-setting-action.php" onSubmit="return check_value()">
		  <input type="hidden" name="redir_url" value="basic-settings-tab.php?tab=1"  />
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    
    <tr>
      <td width="48%">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    
    <tr>
      <td height="25">Your adserver name </td>
      <td><input name="ppc_engine_name" type="text" id="ppc_engine_name" size="40" value="<?php echo $ppc_engine_name; ?>"></td>
    </tr>
        <tr>
      <td height="25">Default language </td>
      <td><select name="lan" id="lan">
      <?php
$lan=mysql_query("select * from adserver_languages where status=1 order by language asc");
 while($la1=mysql_fetch_row($lan))  
 {   
      ?>
      <option value="<?php echo $la1[3] ?>" <?php if($la1[3]==$client_language) { ?> selected="selected"<?php }?>><?php echo $la1[1]; ?></option>
      <?php } ?>
      </select>      </td>
          </tr>
    <tr>
      <td height="25">Default charset *  </td>
      <td width="48%"><input name="ad_display_char_encoding" type="text" id="ad_display_char_encoding" size="7" value="<?php echo $ad_display_char_encoding; ?>">         </td>
      </tr>
    <tr>
      <td height="25">Advertiser  default status</td>
      <td><select name="adv_status" id="adv_status">
      <?php $adv_status=$GLOBALS['adv_status']; ?>
	    
        <option value="1" <?php if ($adv_status==1){ ?> selected="selected"<?php }?>>Active</option>
        <option value="-1" <?php if ($adv_status== '-1'){ ?> selected="selected"<?php }?>>Pending</option>
      </select></td>
    </tr>
    <tr>
      <td height="25">Publisher  default status</td>
      <td><select name="pub_status" id="adv_status">
	    
        <option  value="1"  <?php if ($pub_status==1){ ?> selected="selected"<?php }?>>Active</option>
        <option value="-1" <?php if ($pub_status== '-1'){ ?> selected="selected"<?php }?>>Pending</option>
      </select></td>
    </tr>
    
    <tr>
      <td height="25">Minimum password length </td>
      <td><select name="min_user_password_length" id="min_user_password_length">
	    <option value="<?php echo $min_user_password_length; ?>" selected><?php echo $min_user_password_length; ?></option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
	   
      
      </select> 
        characters </td>
    </tr>
	  <tr>
      <td height="25">Enable single sign-on (SSO) * </td>
      <td><select name="single_account_mode" id="single_account_mode">
        <option value="1" <?php if($single_account_mode==1){echo "selected";}?>>Yes</option>
        <option value="0" <?php if($single_account_mode==0){echo "selected";}?>>No</option>
      </select></td>
    </tr> 
	
	 <tr>
      <td height="25">Enable Site Targeting </td>
      <td><select name="site_targeting" id="site_targeting">
        <option value="1" <?php if($site_targeting==1){echo "selected";}?>>Yes</option>
        <option value="0" <?php if($site_targeting==0){echo "selected";}?>>No</option>
      </select></td>
    </tr> 
    
    <!-- <tr>
      <td height="25">Make adserver part of Inoutscripts portal script * </td>
      <td><select name="portal_system" id="portal_system">
        <option value="1" <?php if($portal_system==1){echo "selected";}?>>Yes</option>
        <option value="0" <?php if($portal_system==0){echo "selected";}?>>No</option>
      </select></td>
    </tr> 
	-->
	
	
	 
	
	
	
    <tr>
      <th align="center" scope="row"></th>
      <th align="left" scope="row">&nbsp;</th>
    </tr>
   
    <tr>
      <th align="center" scope="row">&nbsp;</th>
      <th align="left" scope="row"><input type="submit" name="Submit" value="Update !"></th>
    </tr>
    <tr>
      <td colspan="2"   scope="row"><br />
	   <span class="note">*<strong>Default charset :</strong> Applicable for adserver pages and ad display. Make sure that this matches with your database encoding. <br /><br />

	   *<strong>Single sign-on (SSO) :</strong> Enable this if you  prefer to let your users to access their advertiser & publisher accounts using a sinlge login. If you are already running a dual sign-on system, still you can enable this. Existing users will have option to login seapartely and all newly registered users can use single sign-on. Also all the existing users will have an option in their control panel to merge their accounts if they already have separate advertiser & publisher accounts. And those have only advertiser accounts   will have the option to create a new publisher account and vice versa. Such newly created accounts will be placed in default status configured by you.  <br /><br />
<!--<strong>*Inoutscripts portal script :</strong> If you have purchased the  portal script from inoutscripts, then you can provide portal access to the user accounts in adserver. Please note that adserver and portal need  to be installed in same database and adserver must be running in SSO enabled mode. Portal script from inoutscripts allows you to provide single sign-on option for all scripts purchased from inoutscripts. If you enable this configuration in adserver, the pages related to login,registration,change details,change password  etc. in adserver will be disabled; such operations must be done through corresponding pages in portal script. -->
</span></td>
      </tr>
    <tr>
		<td colspan="2">&nbsp;</td>
    </tr>
  </table>
</form>
		</div>

	    <div id="index1_div_2"  style="padding:5px;display:;">
<form name="form2" method="post" action="ppc-setting-action.php" onSubmit="return check_adsettings()">
		  <input type="hidden" name="redir_url" value="basic-settings-tab.php?tab=2"  />
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    
    <tr>
      <td width="48%">&nbsp;</td>
      <td width="48%">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="inserted" scope="row">&nbsp;</td>
    </tr>
    

	<tr>
      <td height="26">Adserver operation mode *</td>
      <td><select name="ad_keyword_mode" id="ad_keyword_mode">
        <option value="1" <?php if($ad_keyword_mode==1){echo "selected";}?>>Keyword Based</option>
        <option value="2" <?php if($ad_keyword_mode==2){echo "selected";}?>>Keyword Independent</option>
		<option value="3" <?php if($ad_keyword_mode==3){echo "selected";}?>>Both</option>
      </select></td>
    </tr>
	    <tr align="center">
      <td height="27"><div align="left">Enter default keyword for ads </div></td>
      <td align="left" scope="row"><input name="default_keyword" id="default_keyword" type="text" maxlength="40" value="<?php echo $keywords_default;?>"></td>
    </tr>
     <tr>
      <td height="26">Automatically  approve keywords</td>
      <td><select name="auto_keyword_approve" id="auto_keyword_approve">
        <option value="1" <?php if($auto_keyword_approve==1){echo "selected";}?>>Yes</option>
        <option value="0" <?php if($auto_keyword_approve==0){echo "selected";}?>>No</option>
      </select></td>
    </tr>
	 <tr>
      <td height="27">Ad rotation preference * </td>
      <td width="48%"><select name="rotation_settings" id="rotation_settings">
	  	<option value="0" <?php if($ad_rotation==0){ echo "selected";} ?>>Random</option>
        <option value="1" <?php if($ad_rotation==1){ echo "selected";} ?>>Clickbid based</option>
	   <option value="2" <?php if($ad_rotation==2){ echo "selected";} ?>>Clickbid & CTR based</option>
      <option value="3" <?php if($ad_rotation==3){ echo "selected";} ?>>Clickbid, CTR & Ageing based</option>
      </select></td>
    </tr>
   

	 
	 <tr align="center">
      <td height="27"><div align="left">Fraud detection time interval  * </div></td>
      <td align="left" scope="row">
	    <select name="fraudtime" id="fraudtime">
	   <option value="<?php echo $fraud_time_interval; ?>" selected><?php echo $fraud_time_interval; ?></option>
	   <option value="1">1</option>
	   <option value="2">2</option>
	   <option value="3">3</option>
	   <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="11">11</option>
		<option value="12">12</option>
		<option value="13">13</option>
		<option value="14">14</option>
		<option value="15">15</option>
		<option value="16">16</option>
		<option value="17">17</option>
		<option value="18">18</option>
		<option value="19">19</option>
		<option value="20">20</option>
		<option value="21">21</option>
		<option value="22">22</option>
		<option value="23">23</option>
		<option value="24">24</option>
      </select>	     Hrs	  </td>
    </tr>
	
	
	
	
	
	
	
	    <tr>
      <td height="26">Exclude proxy clicks</td>
      <td><select name="proxy_detection" id="proxy_detection">
        <option value="1" <?php if($proxy_detection==1){echo "selected";}?>>Yes</option>
        <option value="0" <?php if($proxy_detection==0){echo "selected";}?>>No</option>
      </select></td>
    </tr>
	 <tr>
      <td height="30">Enable captcha verification for bot clicks * </td>
      <td><select name="captcha_status" id="captcha_status" onchange="activate_captcha_filelds()">
	  <option value="1"  <?php if($captcha_status==1)
		{echo "selected";}?>>Yes</option>
        <option value="0" <?php if($captcha_status==0)
		{echo "selected";}?>>No</option>
      </select></td>
    </tr>
    <tr>
      <td colspan="2" scope="row">   
    
     <table id="captcha_details" width="100%" cellpadding="0" cellspacing="0" style="display:">
     <tr><td width="50%" >Duration for captcha verification after bot detection</td><td><select name="captcha_time">
     <?php
     $op_str="";
     for($i=0;$i<24;$i++)
     {
     	$sl_str="";
     	if($i+1 ==$captcha_time)
     	$sl_str="selected='selected'";
     	
     	$op_str.="<option value='".($i+1)."' $sl_str >".($i+1)."</option>";
     	
     }
     echo $op_str;
      ?>
      
     </select>
     hours</td></tr>
     
     </table></td></tr>
	
	<tr>
      <td height="27">Automatically clear raw data * </td>
      <td width="48%"><select name="raw_data_clearing" id="raw_data_clearing">
	  
	  	<option value="0" <?php if($raw_data_clearing==0){ echo "selected";} ?>>No</option>
        <option value="1" <?php if($raw_data_clearing==1){ echo "selected";} ?>>Yes</option>
	   
      
      </select></td>
    </tr>

	 <tr>
      <td height="27">Admin Traffic Analysis * </td>
      <td width="48%"><select name="traffic_analysis" id="traffic_analysis">
	  	<option value="0" <?php if($traffic_analysis==0){ echo "selected";} ?>>No</option>
        <option value="1" <?php if($traffic_analysis==1){ echo "selected";} ?>>Yes</option>
	   
      
      </select></td>
    </tr>
	<tr align="center">
      <td height="27"><div align="left">Enter API Authentication Code</div></td>
      <td align="left" scope="row"><input name="xml_auth_id" id="xml_auth_id" type="text" maxlength="40" value="<?php echo $xml_id=$GLOBALS['xml_auth_code'];?>"></td>
    </tr>

    
   
	<?php
	$hardurl=$mysql->echo_one("select value from ppc_settings where name='hardcode_check_url'");
	
	?>
	<tr>
      <td>Hard Coded Link Checking Url (Flash Ads) * </td>
      <td><input name="hardlinks" type="text" id="hardlinks" size="31" value="<?php echo $hardurl; ?>" /><span class="info">(Eg:http://www.yoursite.com)</span></td>
    </tr>
    <tr align="center">
      <th scope="row">&nbsp;</th>
      <th align="left" scope="row"><input type="submit" name="Submit" value="Update !"></th>
    </tr>
    <tr>
      <td height="25" colspan="2" scope="row" class="note"><br />
<strong>*Adserver operation mode :</strong> This configuration can take three values which are explained below.<br>
<u>Keyword Independent</u> : This mode will mask the keyword related operations from advertisers, giving them a feeling that the system uses a keyword independent advertising mode. Internally system adds the default keyword for every ad.<br>
<u>Keyword Based</u> : This mode will ensure that every ad created will have atleast one keyword. If the default keyword configuration above is not left empty, then it will be added to every ad on creation. Advertisers may delete the same anytime and add their own keywords.<br>
<u>Both</u> : This mode is maintained for backward comapatibilty with adserver standard edition. If you choose this mode all ads will have default keyword. Advertisers cannot delete/edit the default keyword. However they may update the click value of default keyword.<br>
<br>
<strong>*Ad rotation preference :</strong> This configuration can take four values which are  based on the following factors.<br />

<u>Last Access Time</u> : Random mode uses only this factor; ads are rotated based on last displayed time irrespective of any other factors. <br />
<u>Click bid</u> : If this factor is considered, ads with higher click bid will have preference over those with lower bids. <br />
<u>CTR ( Click Through Rate )</u> : This factor is calculated as the ratio of clicks to impressions. If you choose to consider this factor, ads with higher ctr will have an edge over those with lower ctr. <br />
<u>Ageing</u> : Ageing factor comes into picture when there are ads with very low click bid.  If you choose to consider this, the better the chance for ads with less click bid  to show up. <br>        <br>
        <strong>*Fraud detection time interval :</strong> This configuration specifies the acceptable interval between 2 clicks on a specific ad from a particular ip, so that they are not duplicate. Also this interval is used for tracking publisher fraud activity ,ie, all clicks received by a publisher from his last login ip within x hours before/after   his/her last login time are treated as fraud clicks. <br>        <br />
        <strong>*Captcha verification for bot clicks :</strong> If you enable this feature adserver will turn on captcha verification for the clicks of a publisher whose click is idetified to be from some automated software. The time period for which captcha is enabled can also be controlled. <br />
<br />
<strong>*Raw data clearing :</strong> Turn on this configuration to clean up unwanted raw data and recover the disk space.	If auto clearing is not enabled, you need to clear it manually from <a href="show-statistics.php">here</a> <br /><br>
	  <strong>*Admin traffic analysis :</strong> Enable this to track all pages ( along with traffic details of such pages)  where admin adcodes are rendered. Please note that traffic analysis can consume more cpu/memory usage; so use it only if you have spare cpu/memory in your server.	 <br />
	  <br>
      <strong>*Hard Coded Link Checking Url :</strong> If you do not have any software to check for hardcoded urls, then you must use this feature. All links in an swf banner will be replaced with this url when you check for hard coded links. You may reject the ad if you find    links other than this url in the swf ads..</td>
    </tr>
    <tr>
      <td height="25">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr align="center">
      <th scope="row">&nbsp;</th>
      <th align="left" scope="row">&nbsp;</th>
    </tr>
  </table>
</form>

        </div>	
  
	    <div id="index1_div_3" style="padding:5px;display:;">
<form name="form3" method="post" action="ppc-setting-action.php" onSubmit="return emailsettings()">
		  <input type="hidden" name="redir_url" value="basic-settings-tab.php?tab=3"  />
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="51%">&nbsp;</td>
      <td width="46%">&nbsp;</td>
    </tr>
    
    <tr>
      <td height="25">Admin general notification email address </td>
      <td><input name="admin_general_notification_email" type="text" id="admin_general_notification_email" size="30" value="<?php echo $admin_general_notification_email; ?>" ></td>
    </tr>
   
	<tr>
      <td height="25">Admin payment notification email</td>
      <td><input name="admin_payment_notification_email" type="text" id="admin_payment_notification_email" size="30" value="<?php echo $admin_payment_notification_email; ?>" ></td>
    </tr>
		 <tr>
      <td height="25">Min. advertiser account balance for email notification * </td>
      <td>        <input name="minimum_acc_balance" type="text" id="minimum_acc_balance" size="5" value="<?php echo $advertiser_minimum_account_balance; ?>"> <?php echo $currency_symbol; ?></td>
    </tr>
	<tr>
      <td height="25">Send acknowledgment email after advertiser payment</td>
      <td><select name="send_mail_after_payment" id="send_mail_after_payment">
	   <option value="<?php echo $send_mail_after_payment; ?>" selected>
        <?php if($send_mail_after_payment==1)
		{echo "Yes";}
		else
		{
		echo "No";
		}
		
		?>
        <option value="1">Yes</option>
        <option value="0">No</option>
      </select>        
       </td>
    </tr>
	
	<tr>
	  <td align="center" scope="row">&nbsp;</td>
      <td align="left" scope="row"><input type="submit" name="Submit" value="Update !"></td>
	</tr>
		<tr align="left">
	  <td colspan="2" scope="row" class="note"><br>
	    * The system sends notifications to advertisers  if account balance falls below this limit. Set this to 0 to disable notifications. <br />
<br />
</td>
      </tr>
  </table>
</form>

		  
	    </div>
  <div id="index1_div_4" style="padding:5px;display:;">
<form name="form4" method="post" action="ppc-setting-action.php" onSubmit="return formatsettings()">
		  <input type="hidden" name="redir_url" value="basic-settings-tab.php?tab=4"  />		 
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" >
    
    
    <tr>
      <td width="27%">&nbsp;</td>
      <td width="73%">&nbsp;</td>
    </tr>
    <tr >
      <td height="25" colspan="2" scope="row" style="border-bottom:1px solid #CCCCCC"><span class="inserted">Date Format settings</span></td>
    </tr>	
  
    
    <tr height="27">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr height="27">
    <td width="27%">Day of week</td>
      <td width="73%">
      <select name="day_of_week" id="day_of_week" onchange="showdates()" >
      <option  value="0" <?php if($day_of_week=="0"){ echo "selected";} ?>>Do not display</option>
      <option value="%a" <?php if($day_of_week=="%a"){ echo "selected";} ?>>NNN</option>
      <option value="%A" <?php if($day_of_week=="%A"){ echo "selected";} ?>>NNNN</option>
      </select>      </td>
    </tr>
    <tr height="27">
    <td width="27%">Day of month</td>
      <td width="73%">
      <select name="day_of_month" id="day_of_month" onchange="showdates()">
      <option value="%d" <?php if($day_of_month=="%d"){ echo "selected";} ?>>D</option>
      <option value="%e" <?php if($day_of_month=="%e"){ echo "selected";} ?>>DD</option>
      </select>      </td>
    </tr>
    <tr height="27">
    <td width="27%">Month</td>
      <td width="73%">
      <select name="month" id="month" onchange="showdates()">
      <option value="%m" <?php if($month=="%m"){ echo "selected";} ?>>MM</option>
      <option value="%b" <?php if($month=="%b"){ echo "selected";} ?>>MMM</option>
      <option value="%B" <?php if($month=="%B"){ echo "selected";} ?>>MMMM</option>
       </select>      </td>
    </tr>
    
     <tr height="27">
    <td width="27%">Year</td>
      <td width="73%">
      <select name="year" id="year" onchange="showdates()">
      <option value="%y" <?php if($year=="%y"){ echo "selected";} ?>>YY</option>
      <option value="%Y" <?php if($year=="%Y"){ echo "selected";} ?>>YYYY</option>
       </select>      </td>
    </tr>
     <tr height="27">
    <td width="27%">Separator</td>
      <td width="73%">
      <select name="date_separator" id="date_separator" onchange="showdates()">
      <option value="/" <?php if($date_separators=="/"){ echo "selected";} ?>>/</option>
      <option value="." <?php if($date_separators=="."){ echo "selected";} ?>>.</option>
      <option value=":" <?php if($date_separators==":"){ echo "selected";} ?>>:</option>
      <option value="-" <?php if($date_separators=="-"){ echo "selected";} ?>>-</option>
      <option value="sp" <?php if($date_separators=="sp"){ echo "selected";} ?>>space</option>
       </select>      </td>
    </tr>
  
  
   <tr height="27">
    <td width="27%">Day positioning </td>
      <td width="73%">  
     <select name="day-position" id="day-position" onchange="showdates()">
      <option value="dayamonth"<?php if($datefield_format=="dayamonth"){ echo "selected";} ?> >Day of month after month</option>
      <option value="daybmonth" <?php if($datefield_format=="daybmonth"){ echo "selected";} ?>>Day of month before month</option>
    </select>    </td>
    </tr>
  <tr>
      <td width="27%">&nbsp;</td>
      <td width="73%">&nbsp;</td>
    </tr>
    <tr>
	<td colspan="2">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="info">
	<tr height="20">
	<td width="27%"><strong>Format</strong></td>
	<td width="73%"><strong>Description</strong></td>
	</tr>
	<tr>
		<td>NNN	</td>
	<td>Abbreviated weekday name.	</td>
	</tr>
	<tr>
		<td>NNNN	</td>
	<td>Full weekday name.	</td>
	</tr>
	<tr>
		<td>D	</td>
	<td>Day of the month as a decimal number, a single digit is preceded by a space.	</td>
	</tr>
	<tr>
		<td>DD	</td>
	<td> Day of the month as a decimal number, a single digit is preceded by a zero.	</td>
	</tr>
	<tr>
		<td>MM	</td>
	<td>Month as a decimal number	</td>
	</tr>
	<tr>
		<td>MMM	</td>
	<td>Abbreviated month name	</td>
	</tr>
	<tr>
		<td>MMMM	</td>
	<td>Full month name	</td>
	</tr>
	</table>	</td>
	</tr>
    <tr height="30">
      <td width="27%" align="left" >Eg. for date format</td>
      <td width="73%"><div id="date"></div></td>
    </tr>
   <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="25" colspan="2" scope="row" style="border-bottom:1px solid #CCCCCC"><span class="inserted">Time Format settings</span> </td>
    </tr>	
     
  
    <tr height="27">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr height="27"> 
    <td width="27%">Hour</td>
      <td width="73%">  
     <select name="hour" id="hour" onchange="shtimes()">
            <option value="%H" <?php if($hour=="%H"){ echo "selected";} ?> >HH</option>
    </select>    </td>
    </tr>   
    
 <tr height="27" >  
    <td width="27%">Minute</td>
      <td width="73%">  
     <select name="minute" id="minute" onchange="shtimes()">
            <option value="%M" <?php if($minute=="%M"){ echo "selected";} ?> >MM</option>
    </select>    </td>
    </tr>   
    
    <tr height="27">
    <td width="27%">Second</td>
      <td width="73%">  
     <select name="second" id="second" onchange="shtimes()">
            <option value="%S" <?php if($seconds=="%S"){ echo "selected";} ?>>SS</option>
    </select>    </td>
    </tr> 
   <tr height="27">
    <td width="27%">Time format</td>
      <td width="73%">  
     <select name="hourfmt" id="hourfmt" onchange="shtimes();">
            <option value="12hour" <?php if($clock_type=="12hour"){ echo "selected";} ?>>12 hour </option>
              <option value="24hour" <?php if($clock_type=="24hour"){ echo "selected";} ?>>24 hour </option>
    </select>    </td>
    </tr>  
     <tr height="27">
    <td width="27%">Separator</td>
      <td width="73%">
      <select name="time_separator" id="time_separator" onChange="shtimes()">
      <option value="/" <?php if($time_separator=="/"){ echo "selected";} ?>> / </option>
      <option value="." <?php if($time_separator=="."){ echo "selected";} ?>> . </option>
      <option value=":" <?php if($time_separator==":"){ echo "selected";} ?>>:</option>
      <option value="sp" <?php if($time_separator=="sp"){ echo "selected";} ?>>space</option>
       </select>      </td>
    </tr>
      
   <tr>
      <td width="27%">&nbsp;</td>
      <td width="73%">&nbsp;</td>
    </tr>
    
     <tr>
	<td colspan="2">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="info">
	<tr height="20">
	<td width="27%"><strong>Format</strong></td>
	<td width="73%"><strong>Description</strong></td>
	</tr>
	<tr>
		<td>HH	</td>
	<td>Hours; leading zero for single-digit hours .	</td>
	</tr>
	<tr>
		<td>MM	</td>
	<td>Minutes; leading zero for single-digit minutes.	</td>
	</tr>
	<tr>
		<td>SS	</td>
	<td>Seconds; leading zero for single-digit seconds.	</td>
	</tr>
	</table>	</td>
	</tr>
    
     <tr height="30">
      <td width="27%" align="left" >Eg. for time format</td>
      <td width="73%"><div id="time"></div></td>
    </tr>
   <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="25" colspan="2" scope="row" style="border-bottom:1px solid #CCCCCC"><span class="inserted">Money Format settings</span> </td>
    </tr>	
        
      
      
       <tr height="27">
         <td>&nbsp;</td>
         <td>&nbsp;</td>
       </tr>
       <tr height="27">
    <td width="27%">Currency</td>
      <td width="73%">
      <select name="currency-style" id="currency-style" onchange="shmony()">
      <option value="$" <?php if($currency_format=="$"){ echo "selected";} ?>>$</option>
      <option value="$$" <?php if($currency_format=="$$"){ echo "selected";} ?>>$$</option>
             </select>      </td>
    </tr>
      <tr>
    <td width="27%">Currency position</td>
      <td width="73%">
      <select name="currency-position" id="currency-position" onchange="shmony()">
      <option value="0" <?php if($currency_position=="0"){ echo "selected";} ?>>prefix</option>
      <option value="1" <?php if($currency_position=="1"){ echo "selected";} ?>>suffix</option>
             </select>      </td>
    </tr> 
   <tr>
      <td width="27%">&nbsp;</td>
      <td width="73%">&nbsp;</td>
    </tr>
     <tr>
	<td colspan="2">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="info">
	<tr height="20">
	<td width="27%"><strong>Format</strong></td>
	<td width="73%"><strong>Description</strong></td>
	</tr>
	<tr>
		<td>$	</td>
	<td>currency symbol.	</td>
	</tr>
	<tr>
		<td>$$	</td>
	<td>currencycode.	</td>
	</tr>
	</table>	</td>
	</tr>
	
    <tr height="30">
      <td width="27%" align="left" >Eg. for money format</td>
      <td width="73%"><div id="money"></div></td>
    </tr>  
   <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="25" colspan="2" scope="row" style="border-bottom:1px solid #CCCCCC"><span class="inserted">Number Format settings</span> </td>
    </tr>	
   
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>     
  <td width="27%">Thousand separator</td>
      <td width="73%"><input type="text" name="thousand" id="thousand_sp" onkeyup="shnum()" value="<?php echo $thousand_separator;?>" maxlength="1">    </td>
    </tr>
   <tr>     
  <td width="27%">Decimal separator</td>
      <td width="73%"><input type="text" name="decimal" id="decimal_sp"  onkeyup="shnum()"  value="<?php echo $decimal_separator;?>" maxlength="1">    </td>
    </tr>
     <tr>     
  <td width="27%">No.of decimal place</td>
      <td width="73%"><input type="text" name="nodecimal" id="nodecimal_pl"  onkeyup="shnum()" value="<?php echo $no_of_decimalplaces;?>" maxlength="1">    </td>
    </tr>
    <tr height="30">
      <td width="27%" align="left" >Eg. for number format</td>
      <td width="73%"><div id="numb"></div></td>
    </tr>  
    <tr height="35">
	  <td align="center" scope="row">&nbsp;</td>
      <td align="left" scope="row"><input type="submit" name="Submit" value="Update !"  ></td>
	</tr>
    </table>
</form>
  </div> 
      
      
      
      </td>
    
    </tr>
    
</table>






<script language="javascript" type="text/javascript">
function index1_ShowTab(id)
{

	if(id=='1')
	{
		document.getElementById('index1_div_1').style.display="";
		document.getElementById('index1_div_3').style.display="none";
	document.getElementById('index1_div_4').style.display="none";
		document.getElementById('index1_div_2').style.display="none";
		
		document.getElementById("index1_li_1").style.background="url(images/li_bgselect.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_3").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_4").style.background="url(images/li_bgnormal.jpg) repeat-x";
	}
	if(id=='2')
	{
		document.getElementById('index1_div_1').style.display="none";
		document.getElementById('index1_div_3').style.display="none";
	document.getElementById('index1_div_4').style.display="none";
		document.getElementById('index1_div_2').style.display="";
		
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
		document.getElementById("index1_li_3").style.background="url(images/li_bgselect.jpg) repeat-x";
		document.getElementById("index1_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
			document.getElementById("index1_li_4").style.background="url(images/li_bgnormal.jpg) repeat-x";
	}
	 if(id=='4')
	{
	  	document.getElementById('index1_div_1').style.display="none";
		document.getElementById('index1_div_2').style.display="none";

		document.getElementById('index1_div_3').style.display="none";
		document.getElementById('index1_div_4').style.display="";
		document.getElementById("index1_li_4").style.background="url(images/li_bgselect.jpg) repeat-x";	
		document.getElementById("index1_li_3").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		
	}

	
	
}
showdates();
shtimes();
shnum();
shmony();
activate_captcha_filelds();

function activate_captcha_filelds()
			{
			if(document.getElementById('captcha_status').value==1)
				document.getElementById('captcha_details').style.display="";
			else
				document.getElementById('captcha_details').style.display="none";
			}

<?php
if(isset($_REQUEST['tab']) && is_numeric($_REQUEST['tab']) && $_REQUEST['tab']>0 && $_REQUEST['tab']<=4)
{
?>
index1_ShowTab(<?php echo $_REQUEST['tab']; ?> );
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

<?php include("admin.footer.inc.php"); ?>