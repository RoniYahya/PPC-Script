<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







 

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


if($script_mode=="demo")
{
?>

<span class="already">You cannot do this in demo.</span>
<?php
include("admin.footer.inc.php"); 
die;
}

if(isset($_GET['wap']) && $_GET['wap']==1)
{
$wap_flag=$_GET['wap'];
$wap_string=' wap ';
$query_str="where wapstatus='1'";
$query_string="where wap_status='1'";
}
else
{
	$wap_flag=0;
	$wap_string='';
	$query_str="where wapstatus='0'";
	$query_string="where wap_status='0'";
}

?>

<style type="text/css">
dl, dt, dd, ul, ol, li{margin:0;padding:0;vertical-align:baseline;}
</style>
<script language="javascript">
var singleline=0;
var singleline1=0;
var singleline2=0;

function SingleLineModify()
{
sing1=document.getElementById("text_ad_type").value;
if(sing1 ==2)
singleline1=1;
else
singleline1=0;
}

function SingleLineModifyHorizontal()
{
sing2=document.getElementById("ad_orientation").value;
if(sing2 ==2)
singleline2=1;
else
singleline2=0;
}





function SingleLineModifyAction()
{
if(singleline ==1 && singleline1 ==1 && singleline2 ==1)
{

var optsingle=document.createElement("OPTION");
optsingle.value=2;
optsingle.text="Left";   
document.getElementById('credit_text_positioning').options.add(optsingle);


var optsingle1=document.createElement("OPTION");
optsingle1.value=3;
optsingle1.text="Right";   
document.getElementById('credit_text_positioning').options.add(optsingle1);


}
else
{
 						
sl_length=document.getElementById('credit_text_positioning');	
		

for(ii=sl_length.options.length-1;ii>=2;ii--)
{
sl_length.remove(ii);
}
			


}






}



function deviceChanged()
{

t1=document.getElementById("target_device").value;

if(t1=="0")
{
window.location.href="ppc-new-ad-block.php?wap=0";
}
else
{
window.location.href="ppc-new-ad-block.php?wap=1";
}
}
			function adtypeChanged()
				{
				
				
				//alert(document.getElementById("ad-type").value);
				t1=document.getElementById("ad-type").value;
							
				//alert(t1);
				if(t1=="1")  //text only
					{
					singleline=1;
					
					
					//alert(document.getElementById('tab1').id.display);
					//window.document.form1.t1.display=none;
					document.getElementById('tab1').style.display="";      //text/catalog ad common settings
					document.getElementById('bordertab').style.display="";  // border type
					document.getElementById('t1').style.display="none";     // banner dimensions
					document.getElementById('catalog').style.display="none";  // catalog settings
				//	document.getElementById('tab-common').style.display="";
					document.getElementById('text1').style.display="";  //text ad only settings
					document.getElementById('text2').style.display="";	// display url settings				
					document.getElementById('width_speci').style.display=""; // height & width
					}
				else if(t1=="2") //banner only
					{
					singleline=0;
					document.getElementById('tab1').style.display="none";
					document.getElementById('bordertab').style.display="none";
					document.getElementById('t1').style.display="";
					document.getElementById('catalog').style.display="none";
					//document.getElementById('tab-common').style.display="none";
					document.getElementById('width_speci').style.display="none";
					document.getElementById('text1').style.display="none";
					document.getElementById('text2').style.display="none";
					}
				else  if(t1=="3")  //text/banner
				{   singleline=0;
					document.getElementById('tab1').style.display="";
					document.getElementById('bordertab').style.display="";
					document.getElementById('t1').style.display="";
					document.getElementById('catalog').style.display="none";
				//	document.getElementById('tab-common').style.display="";
					document.getElementById('text1').style.display="";
					document.getElementById('text2').style.display="";
					document.getElementById('width_speci').style.display="none";
				}	
				else   // catalog only
				{   singleline=0;
					document.getElementById('bordertab').style.display="";
					document.getElementById('t1').style.display="none";
					document.getElementById('catalog').style.display="";
				//	document.getElementById('tab-common').style.display="";
					document.getElementById('width_speci').style.display="";
					document.getElementById('tab1').style.display="";
					document.getElementById('text1').style.display="none";
					document.getElementById('text2').style.display="none";
				}	
				}
			function check_value()
				{
				
					if(document.getElementById('ad_block_name').value=="")
					{
					alert("Pelase enter ad block name");
					document.form1.ad_block_name.focus();
					return false;
					}
					
					t1=document.getElementById("ad-type").value;
					if(t1==2 || t1==3)
						return true;
					
					var val=document.getElementById('width').value;
					if(!(isInteger(val)))
					{
					alert("Pelase enter a valid width value");
					document.getElementById('width').value="";
					document.form1.width.focus();
					return false;
					}
					
					var val=document.getElementById('height').value;
					if(!(isInteger(val)))
					{
					alert("Pelase enter a valid height value");
					document.getElementById('height').value="";
					document.form1.height.focus();
					return false;
					}
					


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
			function trim(stringValue){
return stringValue.replace(/(^\s*|\s*$)/, "");
}

			</script>
	<script type="text/javascript" src="../dropdown.js"></script>
		
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

<script language="javascript" type="text/javascript">


function updateCreditBorderColor(credit_border_id,creditcolor,backgroundcolor)
{
document.getElementById('uc').value=credit_border_id;
document.getElementById('selected_credit').style.color=creditcolor;
document.getElementById('selected_credit').style.background=backgroundcolor;


}
</script>




<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/adblocks.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Create Ad Block</td>
  </tr>
</table>
<br />

<form name="form1" action="new-ad-block.php" method="post" onSubmit="return check_value()">

	
	
<table width="100%"  border="0"  cellpadding="2" cellspacing="0" style="table-layout:fixed;border:1px solid #CCCCCC;">
<tr bgcolor="#b7b5b3" height="25px">
<td width="35%"   ><strong>Property</strong></td>
<td width="55%"   ><strong>Value</strong></td>
<td width="10%" ><strong>Override</strong></td>
</tr>

<tr>
<td  height="30">Target Device </td>
<td  ><label>
<select name="target_device" size="1" id="target_device" onChange="deviceChanged()" dir="ltr">
<option value="0" <?php if($wap_flag==0) echo "selected"; ?>>Desktop&laptop</option>
<option value="1" <?php if($wap_flag==1) echo "selected"; ?>>Wap</option>
			
</select>
</label></td>
<td align="center">-NA-</td></tr>
<tr>
<td colspan="3"	width="100%">
			
			
			
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<td width="35%" height="30"  >Ad block name </td>
					<td width="55%" height="30"><input name="ad_block_name" type="text" id="ad_block_name" value="Ad Block"  maxlength="30" /></td>
								 
					<td width="10%" align="center">-NA-</td>
					</tr>
					
					
					<tr>
					<td  height="30">Ad type </td>
					<td  ><label>
					<select name="ad-type" size="1" id="ad-type" onChange="adtypeChanged();SingleLineModifyAction()" dir="ltr">
					<option value="1">Text Only</option>
					<option value="2">Banner Only</option>
					<option value="4" >Catalog Only</option>
					 <option value="3" selected>Text/Banner</option>
					
					</select>
					</label></td>
					<td   align="center">-NA-</td>
					</tr> 
					<tr>
					<td colspan="3" valign="top">
					
										<div id="width_speci" style="display: none;">
										<table width="100%" border="0"  cellpadding="0" cellspacing="0">
										<tr>  
										<td width="35%" height="30">      Width</td>
										<td width="55%" height="30"><label>       <input name="width" type="text" id="width" value="200"/>px        </label></td>
										<td width="10%" align="center">-NA-</td>
										</tr>
										<tr>
										<td  height="30">Height</td>
										<td  ><input name="height" type="text" id="height" value="200"/>        px</td>
										<td  align="center">-NA-</td>
										</tr>
										</table>
										</div> 
					
					 </td></tr>
					 <tr>
					<td  height="30">Allow for publishers </td>
					<td><select name="allow_publishers" size="1" id="allow_publishers" dir="ltr">
					  <option value="1">Yes</option>
					  <option value="0">No</option>
							</select></td>
					<td  align="center">-NA-</td>
					</tr>	  
					</table>

</td>
</tr>
<tr>
<td colspan="3" width="100%">
					
					<table id="t1" width="100%" border="0" cellpadding="0" cellspacing="0">
			   
				   <tr>
					<td width="35%"  height="30">Banner size </td>
					<td width="55%">
					<select name="banner_size1" size="1" id="banner_size1" dir="ltr">
					<?php
					$banner_block=mysql_query("select * from banner_dimension $query_string order by id ");
					$i=0;
					 while($banner_row=mysql_fetch_row($banner_block))
						{
						
						?>
						<option value="<?php echo $banner_row[0]; ?>" <?php if($i==0) echo "selected"; ?> ><?php echo $banner_row[1]." X ".$banner_row[2]; ?></option>
					  <?php $i++; } ?>
					</select></td>
					<td width="10%" align="center">-NA-</td>
					</tr>
					</table>
	
</td>
</tr>
 <tr>
<td colspan="3" valign="top">
					<div id="catalog" style="padding:0;margin:0 ">
					<?php 
					$catalog_block=mysql_query("select id,width,height,filesize from catalog_dimension $query_str order by id ");	
					?>
					<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
					<td width="35%"  height="30">Catalog size </td>
					<td width="55%">
					<select name="catalog_size" size="1" id="catalog_size" dir="ltr">
					<?php
					$i=0;
					 while($catalog_row=mysql_fetch_row($catalog_block))
						{
						
						?>
						<option value="<?php echo $catalog_row[0]; ?>" <?php if($i==0) echo "selected"; ?> ><?php echo $catalog_row[1]." X ".$catalog_row[2]; ?></option>
					  <?php $i++; } ?>
					</select></td>
					<td width="10%" align="center">-NA-</td>
					</tr>
					<tr>
					<td  height="30">No. of Catalog ads</td>
					<td>	<select name="no_of_catalog_ads" size="1" id="no_of_catalog_ads" dir="ltr">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					</select>
					</td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td  height="30">Catalog alignment</td>
					<td><select name="catalog_alignment" size="1" id="catalog_alignment" dir="ltr">
					<option value="1">Left</option>
					<option value="0" selected>Center</option>
						</select></td>
					<td align="center">-NA-</td>
					</tr>
					<tr style="display:none">
					<td  height="30">Catalog line seperator</td>
					<td><select name="catalog_line_seperator" size="1" id="catalog_line_seperator" dir="ltr">
					<option value="1">Yes</option>
					<option value="0" selected>No</option>
						</select></td>
					<td align="center">-NA-</td>
					</tr>
					</table>
					</div>
</td>
</tr>		
<tr>
<td colspan="3" id="td1" valign="top">
					<div id="text1" style="padding:0px 0px;margin:0px 0px; ">
					
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
				   <tr >
					<td width="35%"  height="30">Text Ad type </td>
					<td width="55%"><label>
					  <select name="text_ad_type" id="text_ad_type" dir="ltr" onchange="SingleLineModify();SingleLineModifyAction()">
						<option value="1" selected>Title/Description/Display url</option>
						<option value="2">Title only</option>
						<option value="3">Title/Description</option>
						
					  </select>
					</label></td>
					<td width="10%" align="center">-NA-</td>
				  </tr>
				  <tr>
					<td  height="30">No. of text ads </td>
					<td><select name="no_of_text_ads" size="1" id="no_of_text_ads" dir="ltr">
					  <option value="1">1</option>
					  <option value="2">2</option>
					  <option value="3">3</option>
					  <option value="4">4</option>
					  <option value="5">5</option>
					  <option value="6">6</option>
					  <option value="7">7</option>
					  <option value="8">8</option>
					  <option value="9">9</option>
					  <option value="10">10</option>
					</select>		</td>
					<td width="231" align="center">-NA-</td>
				  </tr>
				  </table>
				  </div>

		
					
					<div id="tab1" style="padding:0px 0px;margin:0px 0px; ">
					
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<td  width="35%" height="30">Ad orientation </td>
					<td width="55%"><select name="ad_orientation" size="1" id="ad_orientation" dir="ltr" onchange="SingleLineModifyHorizontal();SingleLineModifyAction()">
					<option value="1">Vertical</option>
					<option value="2">Horizontal</option>
					</select></td>
					<td width="10%" align="center">-NA-</td>
					</tr>
					<tr>
					<td   height="30"> Ad title font </td>
					<td  ><label>
					
					  <select name="title_font" size="1" id="title_font" dir="ltr">
						<option value="Arial, Helvetica, sans-serif" selected>Arial</option>
						<option value="Courier New, Courier, monospace">Courier New</option>
						<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
						<option value="Times New Roman, Times, serif">Times New Roman</option>
						<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
					  </select>
					</label></td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td height="30">Ad title size </td>
					<td><label>
					  <select name="ad_title_size" id="ad_title_size" dir="ltr">
						<option value="8">8</option>
						<option value="10">10</option>
						<option value="12">12</option>
						<option value="14" selected>14</option>
						<option value="16">16</option>
						</select> px            </label></td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td height="30">Ad title font weight</td>
					<td><select name="ad_title_font_weight" size="1" id="ad_title_font_weight" dir="ltr">
					  <option value="1">Normal</option>
					  <option value="2">Bold</option>
								</select>              </td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td height="30">Ad title decoration</td>
					<td><select name="ad_title_decoration" size="1" id="ad_title_decoration" dir="ltr">
					  <option value="1">None</option>
					  <option value="2">Underline</option>
											  </select>              </td>
					<td align="center">-NA-</td>
					</tr>
					
					<tr>
					<td height="30">Ad description font </td>
					<td><select name="desc_font" size="1" id="desc_font" dir="ltr">
					  <option value="Arial, Helvetica, sans-serif" selected>Arial</option>
						<option value="Courier New, Courier, monospace">Courier New</option>
						<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
						<option value="Times New Roman, Times, serif">Times New Roman</option>
						<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
					</select></td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td height="30">Ad description size </td>
					<td><select name="desc_size" id="desc_size" dir="ltr">
					  <option value="8">8</option>
					  <option value="10">10</option>
					  <option value="12">12</option>
					  <option value="14" selected>14</option>
					  <option value="16">16</option>
					</select>
					  px</td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td height="30">Ad description  font weight</td>
					<td><select name="ad_desc_font_weight" size="1" id="ad_desc_font_weight" dir="ltr">
					   <option value="1">Normal</option>
					  <option value="2">Bold</option>
					</select>              </td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td height="30">Ad description decoration</td>
					<td><select name="ad_desc_decoration" size="1" id="ad_desc_decoration" dir="ltr">
					  <option value="1">None</option>
					  <option value="2">Underline</option>
					 
											</select>              </td>
					<td align="center">-NA-</td>
					</tr>
					
					<tr><td colspan="3">
										<div id="text2" style="padding:0px 0px;margin:0px 0px; ">		
										<table width="100%" border="0" cellpadding="0" cellspacing="0">
										<tr>
										<td width="35%" height="30">Ad display url font </td>
										<td width="55%"><select name="display_font" size="1" id="display_font" dir="ltr">
										<option value="Arial, Helvetica, sans-serif" selected>Arial</option>
										<option value="Courier New, Courier, monospace">Courier New</option>
										<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
										<option value="Times New Roman, Times, serif">Times New Roman</option>
										<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
										</select></td>
										<td width="10%" align="center">-NA-</td>
										</tr>
										<tr>
										<td height="30">Ad display url size </td>
										<td><select name="disp_url_size" id="disp_url_size" dir="ltr">
										<option value="8">8</option>
										<option value="10">10</option>
										<option value="12">12</option>
										<option value="14" selected>14</option>
										<option value="16">16</option>
										</select>
										px</td>
										<td align="center">-NA-</td>
										</tr>
										<tr>
										<td height="30">Ad display url font weight</td>
										<td><select name="ad_disp_url_font_weight" size="1" id="ad_disp_url_font_weight" dir="ltr">
										<option value="1">Normal</option>
										<option value="2">Bold</option>
										</select>              </td>
										<td align="center">-NA-</td>
										</tr>
										<tr>
										<td height="30">Ad display url decoration</td>
										<td><select name="ad_disp_url_decoration" size="1" id="ad_disp_url_decoration" dir="ltr">
										<option value="1" selected>None</option>
										<option value="2">Underline</option>
										
												</select>              </td>
										<td align="center">-NA-</td>
										</tr>
										</table>
										</div>
					
					</td></tr>
									<tr>
					<td height="30">Text Line Height </td>
					<td><input name="line_spacing" type="text" id="line_spacing" value="15" />
					px</td>
					<td align="center">-NA-</td>
					</tr>
					<tr><td colspan="3">
										<table width="100%" border="0" cellpadding="0" cellspacing="0" height="200px">
															  <tr>
										<td width="35%" height="50px">Ad title color </td>
										<td width="15%"><div class="form-item"><input name="color1" type="text" ID="color1" class="colorwell"  style="background-color:<?php echo " #000099"; ?>" value="<?php echo " #000099"; ?>"  ></div></td>
										<td width="40%"  rowspan="4"  style="vertical-align:top "><div id="picker" style="float: left;"></div></td>
										<td width="10%" align="center"><input name="allow_title_color" type="checkbox" id="allow_title_color" value="1" checked></td>
										</tr>
										<tr>
										<td height="50px">Ad description color </td>
										<td><div class="form-item"><input  type="text" id="color2" name="color2" class="colorwell"  value="<?php echo "#0F0F0F"; ?>"  style="background-color:<?php echo "#0F0F0F"; ?>"></div></td>
										<td align="center">
										  <input name="allow_desc_color" type="checkbox" id="allow_desc_color" value="1" checked>           </td>
										</tr>
										<tr>
										<td height="50px">Ad display url color </td>
										<td><div class="form-item"><input  type="text" id="color3" name="color3" class="colorwell"  value="<?php echo "#009933"; ?>" style="background-color:<?php echo "#009933"; ?>"></div></td>
										<td align="center">
										  <input name="allow_disp_url_color" type="checkbox" id="allow_disp_url_color" value="1" checked></td>
										</tr>
										<tr>
										<td height="50px">Background color </td>
										<td><div class="form-item"><input type="text" id="color4" name="color4" class="colorwell" value="<?php echo "#FFFFFF"; ?>" style="background-color:<?php echo "#FFFFFF"; ?>"></div></td>
										<td align="center">
										  <input name="allow_bg_color" type="checkbox" id="allow_bg_color" value="1" checked></td>
										</tr>
										</table>
					</td></tr>
					</table>
					</div>
		
</td>
</tr>
<tr>
<td  colspan="3">
					
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<?php 
					
					//$lang_id=$mysql->echo_one("select id from adserver_languages where code='$client_language'");
					//$result_credit=mysql_query("select * from ppc_publisher_credits where language_id='$lang_id'");	
					?>
					<td width="35%" height="30">Credit text </td>
					<td height="55%">
					
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
		
			$selectedcredit='<span id= "selected_credit_cr" style="cursor:default">--Select--</span>'; 
	
			
		
			$Credit=$Credit.'<li ><a  onMouseOver="javascript:updateCreditBorderColorNew(\''.$row1[0].'\',\''.$row1[1].'\',\''.$row1[4].'\')" ><img  src="../credit-image/'.$row1[0].'/'.$row1[1].'" style="width:200px;height:15px;" /></a></li>';
			?>
				<?php
				}
				else if($row1[4]==0)
				{
			
		
			$selectedcredit='<span id= "selected_credit_cr"  >--Select--</span>'; 
		
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
	<input name="cc" type="hidden" id="cc" value="" >			</td>
            <td align="center">-NA-</td>
		  </tr>
		  
					
					
					
					
					
					
					
					
					
					<!--<select name="credit_text" size="1" id="credit_text" dir="ltr">
					<option value="0">--Select--</option><?php //while($credit_row=mysql_fetch_row($result_credit)){	?>
					<option value="<?php //echo $credit_row[0]; ?>"><?php //echo $credit_row[1]; ?></option><?php //} ?></select>-->
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					<!--</td>
					<td align="center" width="10%">-NA-</td>
					</tr>-->
					<tr>
					<td  height="28">Credit text font </td>
					<td  height="28"><select name="credit_text_font" size="1" id="credit_text_font" dir="ltr">
					<option value="Arial, Helvetica, sans-serif" selected>Arial</option>
					<option value="Courier New, Courier, monospace">Courier New</option>
					<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>
					<option value="Times New Roman, Times, serif">Times New Roman</option>
					<option value="Georgia, Times New Roman, Times, serif">Georgia</option>
					</select></td>
					<td   align="center">-NA-</td>
					</tr>
					<tr>
					<td height="30">Credit text font weight </td>
					<td height="30"><select name="credit_text_font_weight" size="1" id="credit_text_font_weight" dir="ltr">
					<option value="1">Normal</option>
					<option value="2">Bold</option>
					</select>
					</td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td height="30">Credit text  decoration</td>
					<td height="30"><select name="credit_text_decoration" size="1" id="credit_text_decoration">
					<option value="1" selected>None</option>
					<option value="2">Underline</option>
					
					</select>              
					</td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td  height="30">Credit text / border color <br />
(<a href="ppc-edit-credittext-bordercolor.php">Edit color combinations</a>) </td>
					<td>
					<?php 
					$Credit="";
					$selected_credit="";
					$res1=mysql_query("select * from ppc_credittext_bordercolor order by id DESC");
					$row1=mysql_fetch_row($res1);
					?>
					<input name="uc" type="hidden" id="uc"  value="<?php echo $row1[0]; ?>">
					<?php $selected_credit='<span id= "selected_credit" style="cursor:default;padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';">credit text</span>'; 
					
					do
					{
					
					//$Credit=$Credit.'<label style="color:'. $row1[1].';background-color:'. $row1[2].';padding:2px;margin:2px"> <input name="cc" type="radio"  value="'.$row1[0].'" '.$che.'>  credit-text </label>';
					$Credit=$Credit."<li ><a onMouseOver=\"javascript:updateCreditBorderColor($row1[0]  ,  '$row1[1]' ,  '$row1[2]' ) \" style=\"background-color:$row1[2];color:$row1[1];\">credit text</a></li>";
					$i++;
					}while($row1=mysql_fetch_row($res1)); 
					?><dl class="dropdown">
					<dt id="three-ddheader" onMouseOver="ddMenu('three',1)" onMouseOut="ddMenu('three',-1)"><?php echo $selected_credit; ?></dt>
					<dd id="three-ddcontent" onMouseOver="cancelHide('three')" onClick="ddMenu('three',-1)" onMouseOut="ddMenu('three',-1)">
					<ul>
					<?php echo $Credit; ?>
					</ul>
					</dd>
					</dl>
						  
					
					</td>
					<td align="center"><input name="allow_credit_color" type="checkbox" id="allow_credit_color" value="1" checked></td>
					</tr>
					<tr>
					<td  height="30">Credit text alignment </td>
					<td><select name="credit_text_alignment" size="1" id="credit_text_alignment">
					<option value="0" selected>Left</option>
					<option value="1">Right</option>
					</select></td>
					<td align="center">-NA-</td>
					</tr>
					<tr>
					<td  height="30">Credit text positioning <br>
					(relative to ad block) </td>
					<td><select name="credit_text_positioning" size="1" id="credit_text_positioning">
					<option value="1">Top</option>
					<option value="0" selected>Bottom</option>
					
					</select></td>
					<td align="center">-NA-</td>
					</tr>
					<tr><td colspan="3" valign="top">
										<table cellpadding="0" cellspacing="0" width="100%"  border="0" id="bordertab">
										<tr>
										<td width="35%" >Border type </td>
										<td width="55%"><select name="border_type" size="1" id="border_type" dir="ltr">
										<option value="1" selected>Regular</option>
										<option value="0">Rounded</option>
										
										</select><span class="info">(Note:Border type will be set to regular style for image credit texts and credit text positioning left/right.)</span></td>
										<td width="10%" align="center"><input name="allow_bordor_type" type="checkbox" id="allow_bordor_type" value="1" checked>
										</td>
										</tr>
										</table>
					</td></tr>
					</table>
        
        </td>
        </tr>
 
 <tr>
 <td></td>
<td  colspan="2"><input name="Submit" type="submit" id="Submit" value="Submit">  <input name="wap" type="hidden" id="wap" value="<?php echo $wap_flag; ?>" 
</td>
</tr>
   </table>
      
   
</form>
<br>
<?php
 include("admin.footer.inc.php"); ?>

<script language="javascript">
			 adtypeChanged();
			 SingleLineModify();
			 SingleLineModifyHorizontal();
			 SingleLineModifyAction();
</script>