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


include_once("admin.header.inc.php");
?>
<script type="text/javascript" src="../dropdown.js"></script>
<style type="text/css">
dl, dt, dd, ul, ol, li{margin:0;padding:0;vertical-align:baseline;}
</style>

<style type="text/css">
<!--
.style3 {font-weight: bold}

-->
</style>
<link href="style.css" rel="stylesheet" type="text/css">

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

<script language="javascript">
			function adtypeChanged()
				{
				//alert(document.getElementById("tab").rows['t1']);
				var t1=document.getElementById("ad_type").value;
			//	alert(t1);
 		if(t1==6) 
					{
 
					document.getElementById('tab1').rows['borderrow'].style.display=""; // ad block common settings
 					document.getElementById('tab3').style.display=""; // dummy ad settings
					document.getElementById('tab2').style.display=""; // display url settings
					document.getElementById('tab-catalog').style.display="none"; // catalog only settings
					document.getElementById('tab-common').style.display=""; // catalog text common settings
					document.getElementById('text1').style.display="";  // text only settings
					}
			else if(t1==7)   //catalog
					{
			
				//	document.getElementById('tab1').rows['t1'].style.display="none";
						//	alert("Hai");
					document.getElementById('tab1').rows['borderrow'].style.display="";
  					document.getElementById('tab3').style.display="";
					document.getElementById('tab2').style.display="none";
					document.getElementById('tab-catalog').style.display="";
					document.getElementById('tab-common').style.display="";
					document.getElementById('text1').style.display="none";
					
					}
				
	
				}
				
	function index1_ShowTab(id)
{
//alert(id);

	if(id=='1')
	{
		document.getElementById('index1_div_1').style.display="";
		document.getElementById('index1_div_3').style.display="none";
		document.getElementById('index1_div_2').style.display="none";
		
		document.getElementById("index1_li_1").style.background="url(images/li_bgselect.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_3").style.background="url(images/li_bgnormal.jpg) repeat-x";
	}
	if(id=='2')
	{
		document.getElementById('index1_div_1').style.display="none";
		document.getElementById('index1_div_3').style.display="none";
		document.getElementById('index1_div_2').style.display="";
		
		document.getElementById("index1_li_2").style.background="url(images/li_bgselect.jpg) repeat-x";
		document.getElementById("index1_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_3").style.background="url(images/li_bgnormal.jpg) repeat-x";
	}
    if(id=='3')
	{
	  	document.getElementById('index1_div_1').style.display="none";
		document.getElementById('index1_div_2').style.display="none";
		document.getElementById('index1_div_3').style.display="";
		
		document.getElementById("index1_li_3").style.background="url(images/li_bgselect.jpg) repeat-x";
		document.getElementById("index1_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
	}
 
 
	
	
}


	function check_value()
				{
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
			/*	var val=document.getElementById('banner_file_max_size').value;
				if(!(isInteger(val)))
					{
					alert("Pelase enter a valid banner size value");
					document.getElementById('banner_file_max_size').value="";
					document.form1.banner_file_max_size.focus();
					return false;
					}
				*/
				if(document.getElementById('ad_block_name').value=="")
					{
					alert("Pelase enter ad block name");
					document.form1.ad_block_name.focus();
					return false;
					}
				if(document.getElementById('ad_display_url').value=="")
					{
					//refresh();
					alert("Ad display url should not be blank");
					//document.form1.ad_display_url.focus();
					return false;
					}
				if(document.getElementById('ad_description').value=="")
					{
					//refresh();
					alert("Ad description should not be blank");
					//document.form1.ad_description.focus();
					return false;
					}
				if(document.getElementById('ad_title').value=="")
					{
					//refresh();
					alert("Ad display title should not be blank");
					//document.form1.ad_title.focus();
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
<?php
	$id= $_GET['id'];
phpsafe($id);
	if($script_mode=="demo")
	{ 
		if(($id>21 && $id< 32) || $id=="2"|| $id=="4" )
		{
		echo "<br><span class=\"already\">You cannot edit this block in demo.</span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
		}
	}
	$result=mysql_query("select * from ppc_ad_block where id='$id'");
	$row=mysql_fetch_row($result);
	$row22=$row;

  		$ad_val=0;
 		
		
		
		
	?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/adblocks.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading"> Modify Ad block</td>
  </tr>
</table>

<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
 	   

  <tr>
    <td  >
	<?php
	
	if($row[3]==6)
		{
		?>
		 <iframe height="<?php echo $row[2]+$ad_val; ?>" width="<?php echo $row[1]; ?>" frameborder="0" src="adblock_preview.php?id=<?php echo $row[0]; ?>&uid=0" ></iframe>
		 <?php
		 }
		 else
		 {
 		 ?>
    
 <iframe height="<?php echo $row[2]+$ad_val; ?>" width="<?php echo $row[1]; ?>" frameborder="0" src="adblock_preview.php?id=<?php echo $row[0]; ?>&catalogid=<?php echo $row[41] ; ?>&uid=0"></iframe>
   
   <?php 
     }
 ?>
	</td>
  </tr>
  <tr>
  <td><br>  
  <strong>Note :</strong>
  <span class="info"><br /><br />

&raquo;  If the ad preview shown contains scrollbars, the ads will be clipped in  pages where you paste the ad display code; You may modify the ablock settings like height, width, no. of ads, line height, title font size, description font size and display url  font size until no scrollbars appear in the preview. Also note that, since different characters have different width, you may try editing the dummy ad and ensure that preview has no scrollbars.<br /><br>
   <br>
  <br>
  <?php if($row[23]==0)
  	{
 ?>  &raquo; This ad block is currently inactive. If you are satisfied with the ad preview <strong><a href=ppc-activate-ad.php?id=<?php echo $id; ?>>click here</a></strong> to activate this block. Only active ad blocks can be used to generate ad display units.</span><br><br>
	<?php
	}
	?>
  </td>
  </tr>
	
</table>


		<form method="post" name="form1" action="update-ad-block.php" onSubmit="return check_value()">
	<input type="hidden" name="id" value="<?php echo $id;?>">

<table width="100%"  border="0" cellspacing="0" cellpadding="0"  style="border-color:#CCCCCC; border-width:2px; border-style:solid; ">
<tr>
  <td  valign="top" >


		<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus" >
		  <tr height="30px">
			<td align="center"  id="index1_li_1" style="border-left-width:0px; "><a href="javascript:index1_ShowTab('1');"  >General settings</a></td>
			<td  align="center" id="index1_li_2"><a href="javascript:index1_ShowTab('2');" > Text/Catalog ad settings</a></td>
			<td  align="center" id="index1_li_3" ><a href="javascript:index1_ShowTab('3');" >Dummy ad settings</a></td>
		  </tr>
		</table>


</td>
</tr>


<tr>
<td width="100%" valign="top" >	
	



<!-- Div 1 is starting here...................-->
  


		<div id="index1_div_1" style="padding:5px;" >
		  <table width="100%" border="0" id="tab1">
            <tr>
              <td height="30" align="left"><span class="style3">Property</span></td>
              <td height="30" align="left"><strong>Value</strong></td>
              <td><strong>  Override </strong></td>
            </tr>
            <tr><td height="30" align="left">Ad block name </td>
            <td height="30" align="left"><input name="ad_block_name" type="text" id="ad_block_name" value="<?php echo $row[32]; ?>" size="30" maxlength="30" /></td>
            <td align="center">-NA-</td>
     		</tr>
	 <?php
	/* $adblock_used=1;
 	$num=$mysql->echo_one("select count(*) from ppc_custom_ad_block where bid='$id'");
	$num1=$mysql->echo_one("select count(*) from ppc_ads where adtype=1 and bannersize='$id'");
	$num2=$mysql->echo_one("select count(*) from ppc_public_service_ads where adtype=1 and  bannersize='$id'");
	if($num==0 && $num1==0 && $num2==0)
	{
			 $adblock_used=0;
	}*/
?>
			<tr>
             <td width="35%" height="30" align="left">Minimum Width </td>
             <td width="55%" height="30" align="left"><label>          <input name="width" type="text" id="width" value="<?php echo $row[1]; ?>"/>px        </label></td>
        <td align="center" width="10%">-NA-</td>
			</tr>
     	 <tr>
        <td  height="30" align="left">Minimum Height </td>
        <td align="left"> 		<input name="height" type="text" id="height" value="<?php echo $row[2]; ?>" />         px</td>
        <td align="center">-NA-</td>
		  </tr>
		  <tr>
        <td  height="30" align="left">Ad type<br> </td>
        <td align="left">
	
		<label><?php if($row[3]==6) echo "Inline Text Ad "; ?>
		<?php if($row[3]==7) echo "Inline Catalog Ad"; ?>
		
 		  <input type="hidden" id="ad_type" name="ad_type"  value="<?php echo $row[3]; ?>"/>
        </label></td>
        <td align="center">-NA-</td>
    	  </tr>
	   <!--   <tr>
			<td  height="30" align="left">Allow for publishers </td>
			<td align="left"><select name="allow_publishers" size="1" id="allow_publishers">
			  <option value="1" <?php// if($row[21]==1) echo "selected"; ?>>Yes</option>
			  <option value="0" <?php// if($row[21]==0) echo "selected"; ?>>No</option>
					</select></td>
					<td align="center">-NA-</td>
		  </tr>-->
     
          <tr>
		  <?php 
		   $codes=mysql_query("select id from adserver_languages where code='en' and status='1'");	
		   $code1=mysql_fetch_row($codes);
//		   
			 $return=mysql_query("select id from ppc_publisher_credits where language_id='$code1[0]' and id='$row[33]'");
		  $return1=mysql_fetch_row($return);
		  
			$result_credit=mysql_query("select * from ppc_publisher_credits where language_id='$code1[0]'");
		  ?>
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
		    if($row[33]==$row1[0])
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
				if($row[33]==$row1[0])
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
	<input name="cc" type="hidden" id="cc" value="<?php echo $row[33]; ?>" >			</td>
            <td align="center">-NA-</td>
		  </tr>
		  
			
			
			
			
			
			
			
			
			
			
			
			<!--<select name="credit_text" size="1" id="credit_text"><?php //while($credit_row=mysql_fetch_row($result_credit)){?>
			<option value="<?php //echo $credit_row[0]; ?>" <?php //if("$credit_row[0]"=="$return1[0]") echo "selected"; ?>><?php //echo $credit_row[1]; ?></option>
            <?php //} ?></select></td><td align="center">-NA-</td>-->

		 
			
			
		
			
			
          <tr>
			<td   height="30" align="left">Credit text font </td>
            <td  height="30" align="left"><select name="credit_text_font" size="1" id="credit_text_font">
              <option value="Arial, Helvetica, sans-serif" <?php if($row[16]=="Arial, Helvetica, sans-serif") echo "selected"; ?>>Arial</option>
              <option value="Courier New, Courier, monospace" <?php if($row[16]=="Courier New, Courier, monospace") echo "selected"; ?>>Courier New</option>
              <option value="Georgia, Times New Roman, Times, serif" <?php if($row[16]=="Georgia, Times New Roman, Times, serif") echo "selected"; ?>>Georgia</option>
              <option value="Times New Roman, Times, serif" <?php if($row[16]=="Times New Roman, Times, serif") echo "selected"; ?>>Times New Roman</option>
              <option value="Verdana, Arial, Helvetica, sans-serif" <?php if($row[16]=="Verdana, Arial, Helvetica, sans-serif") echo "selected"; ?>>Verdana</option>
            </select></td>
            <td align="center">-NA-</td>
          </tr>
          <tr>
            <td height="30" align="left">Credit text font weight </td>
            <td height="30" align="left"><select name="credit_text_font_weight" size="1" id="credit_text_font_weight">
              <option value="1" <?php if($row[30]==1) echo "selected"; ?>>Normal</option>
              <option value="2" <?php if($row[30]==2) echo "selected"; ?>>Bold</option>
            </select>              </td>
            <td align="center">-NA-</td>
          </tr>
          <tr>
            <td height="30" align="left">Credit text  decoration</td>
            <td height="30" align="left"><select name="credit_text_decoration" size="1" id="credit_text_decoration">
               <option value="1" <?php if($row[31]==1) echo "selected"; ?>>None</option>
              <option value="2" <?php if($row[31]==2) echo "selected"; ?>>Underline</option>

                  </select>              </td>
            <td align="center">-NA-</td>
          </tr>
        <tr>
           <td  height="30" align="left"><p>Credit text / border color</p>
             <p>(<a href="ppc-edit-credittext-bordercolor.php">Edit color combinations</a>)</p></td>
            <td align="left">
			
						
			<script language="javascript" type="text/javascript">

		
		function updateCreditBorderColor(credit_border_id,creditcolor,backgroundcolor)
		{
		document.getElementById('uc').value=credit_border_id;
		document.getElementById('selected_credit').style.color=creditcolor;
		document.getElementById('selected_credit').style.background=backgroundcolor;
		
		
		}
		</script>

			<?php
		$res1=mysql_query("select * from ppc_credittext_bordercolor order by id DESC");
		//echo "select * from ppc_credittext_bordercolor order by id DESC";
		
$Credit="";
$selected_credit="";
		
		
		while($row1=mysql_fetch_row($res1))
		{
		 if($row1[0]==$row22[17])
		 	{
			$selected_credit='<span id= "selected_credit" style="cursor:default;padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';">credit text</span>'; 
			}
		
						$Credit=$Credit."<li ><a onMouseOver=\"javascript:updateCreditBorderColor($row1[0]  ,  '$row1[1]' ,  '$row1[2]' ) \" style=\"background-color:$row1[2];color:$row1[1];\">credit text</a></li>";
						$i++;
	   } 
	   
				  ?><dl class="dropdown">
			<dt id="three-ddheader" onMouseOver="ddMenu('three',1)" onMouseOut="ddMenu('three',-1)"><?php echo $selected_credit; ?></dt>
			<dd id="three-ddcontent" onMouseOver="cancelHide('three')" onClick="ddMenu('three',-1)" onMouseOut="ddMenu('three',-1)">
			<ul>
			<?php echo $Credit; ?>
			</ul>
			</dd>
			</dl>
				<input name="uc" type="hidden" id="uc"  value="<?php echo $row22[17]; ?>">	
			   
			   </td>
            <td align="center"><input name="allow_credit_color" type="checkbox" id="allow_credit_color" value="1" <?php if($row22[38]==1) echo "checked"; ?>></td>
          </tr>
          <tr>
            <td  height="30" align="left">Credit text alignment </td>
            <td align="left"><select name="credit_text_alignment" size="1" id="credit_text_alignment">
              <option value="0" <?php if($row22[19]==0) echo "selected"?>>Left</option>
              <option value="1" <?php if($row22[19]==1) echo "selected"?>>Right</option>
              
                  </select></td>
            <td align="center">-NA-</td>
          </tr>
          <tr>
            <td  height="30" align="left">Credit text positioning<br> 
              (relative to ad block) </td>
            <td align="left"><select name="credit_text_positioning" size="1" id="credit_text_positioning">
              <option value="1" <?php if($row22[20]==1) echo "selected"?>>Top</option>
              <option value="0" <?php if($row22[20]==0) echo "selected"?>>Bottom</option>
             
			<!--  <option value="2" <?php // if($row22[20]==2) echo "selected"?>>Right</option>
			    <option value="3" <?php // if($row22[20]==3) echo "selected"?>>Left</option> -->
			  
                  </select></td>
            <td align="center">-NA-</td>
          </tr>
          <tr id="borderrow"  >
            <td  height="30" align="left">Border type </td>
            <td align="left"><!--<input type="hidden" name="border_type" id="border_type" value="1" />Regular-->
			
			
			<select name="border_type" size="1" id="border_type">
              <option value="1" <?php if($row22[22]==1) echo "selected"?>>Regular</option>
              <option value="0" <?php if($row22[22]==0) echo "selected"?>>Rounded</option>
            
                  </select><span class="info">(Note:Border type will be set to regular style for image credit texts.)</span>
				  
 				  
				  
				  </td>
            <td align="center"><input name="allow_bordor_type" type="checkbox" id="allow_bordor_type" value="1" <?php if($row22[39]==1) echo "checked"; ?>></td>
          </tr>
			<!--<tr id="t1" <?php //if($row[3]==1 || $row[3]==4) {
			?> style="display:none"<?php //}?>>
			<td width="206"  height="30" align="left">Banner file max size </td>
            <td width="219" align="left"><input name="banner_file_max_size" type="text" id="banner_file_max_size" value="<?php// echo $row[4]; ?>" />
              KB</td>
            <td align="center">-NA-</td>
			</tr>-->
          </table>
		</div>






<!-- Div 2 is starting here...................-->

	    <div id="index1_div_2"  style="padding:5px;display:;width:100%;">




 
		<table width="100%" border="0" cellpadding="0" cellspacing="0" >	
            <tr>
              <td  height="30" align="left" width="35%"><strong>Property</strong></td>
              <td align="left" width="55%"><strong>Value</strong></td>
              <td width="10%"><strong>  Override </strong></td>
            </tr>
		</table>
		
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" id="text1">	

            <tr>
             <td width="35%"  height="30" align="left">No. of text ads </td>
        <td width="55%" align="left">1 	</td>
        <td width="10%" align="center">-NA-</td>
            </tr>
		</table> 
		
		<table width="100%" border="0" id="tab-common" >
			
          <tr>
            <td width="35%" height="30" align="left">Ad title font </td>
            <td width="55%" align="left"><label>
			
             <select name="title_font" size="1" id="title_font">
              <option value="Arial, Helvetica, sans-serif" <?php if($row[6]=="Arial, Helvetica, sans-serif") echo "selected"; ?>>Arial</option>
               <option value="Courier New, Courier, monospace" <?php if($row[6]=="Courier New, Courier, monospace") echo "selected"; ?>>Courier New</option>
              <option value="Georgia, Times New Roman, Times, serif" <?php if($row[6]=="Georgia, Times New Roman, Times, serif") echo "selected"; ?>>Georgia</option>
               <option value="Times New Roman, Times, serif" <?php if($row[6]=="Times New Roman, Times, serif") echo "selected"; ?>>Times New Roman</option>
               <option value="Verdana, Arial, Helvetica, sans-serif" <?php if($row[6]=="Verdana, Arial, Helvetica, sans-serif") echo "selected"; ?>>Verdana</option>
              </select>
            </label></td>
            <td width="10%" align="center">-NA-</td>
          </tr>
          <tr>
            <td height="30" align="left">Ad title size </td>
            <td align="left"><label>
              <select name="ad_title_font" id="ad_title_font">
                <option value="8" <?php if($row[7]==8) echo "selected"; ?>>8</option>
                <option value="10" <?php if($row[7]==10) echo "selected"; ?>>10</option>
                <option value="12" <?php if($row[7]==12) echo "selected"; ?>>12</option>
                <option value="14" <?php if($row[7]==14) echo "selected"; ?>>14</option>
                <option value="16" <?php if($row[7]==16) echo "selected"; ?>>16</option>
                </select>
            </label></td>
            <td align="center">-NA-</td>
          </tr>
		   <tr>
            <td height="30" align="left">Ad title font weight</td>
            <td align="left"><select name="ad_title_font_weight" size="1" id="ad_title_font_weight">
              <option value="1" <?php if($row[24]==1) echo "selected"; ?>>Normal</option>
              <option value="2" <?php if($row[24]==2) echo "selected"; ?>>Bold</option>
                  </select>              </td>
            <td align="center">-NA-</td>
		   </tr>
          <tr>
            <td height="30" align="left">Ad title decoration</td>
            <td align="left"><select name="ad_title_decoration" size="1" id="ad_title_decoration">
              <option value="1" <?php if($row[25]==1) echo "selected"; ?>>None</option>
              <option value="2" <?php if($row[25]==2) echo "selected"; ?>>Underline</option>
                  </select>              </td>
            <td align="center">-NA-</td>
          </tr>
          
          <tr>
            <td width="35%" height="30" align="left">Ad description font </td>
            <td width="55%" align="left"><select name="desc_font" size="1" id="desc_font">
              <option value="Arial, Helvetica, sans-serif" <?php if($row[9]=="Arial, Helvetica, sans-serif") echo "selected"; ?>>Arial</option>
               <option value="Courier New, Courier, monospace" <?php if($row[9]=="Courier New, Courier, monospace") echo "selected"; ?>>Courier New</option>
              <option value="Georgia, Times New Roman, Times, serif" <?php if($row[9]=="Georgia, Times New Roman, Times, serif") echo "selected"; ?>>Georgia</option>
               <option value="Times New Roman, Times, serif" <?php if($row[9]=="Times New Roman, Times, serif") echo "selected"; ?>>Times New Roman</option>
               <option value="Verdana, Arial, Helvetica, sans-serif" <?php if($row[9]=="Verdana, Arial, Helvetica, sans-serif") echo "selected"; ?>>Verdana</option>
            </select></td>
            <td width="10%" align="center">-NA-</td>
          </tr>
          <tr>
            <td height="30" align="left">Ad description size </td>
            <td align="left"><select name="desc_size" id="desc_size">
             <option value="8" <?php if($row[10]==8) echo "selected"; ?>>8</option>
                <option value="10" <?php if($row[10]==10) echo "selected"; ?>>10</option>
                <option value="12" <?php if($row[10]==12) echo "selected"; ?>>12</option>
                <option value="14" <?php if($row[10]==14) echo "selected"; ?>>14</option>
                <option value="16" <?php if($row[10]==16) echo "selected"; ?>>16</option>
            </select></td>
            <td align="center">-NA-</td>
          </tr>
		  <tr>
            <td height="30" align="left">Ad description  font weight</td>
            <td align="left"><select name="ad_desc_font_weight" size="1" id="ad_desc_font_weight">
                <option value="1" <?php if($row[26]==1) echo "selected"; ?>>Normal</option>
              <option value="2" <?php if($row[26]==2) echo "selected"; ?>>Bold</option>
            </select>              </td>
            <td align="center">-NA-</td>
		  </tr>
          <tr>
            <td height="30" align="left">Ad description decoration</td>
            <td align="left"><select name="ad_desc_decoration" size="1" id="ad_desc_decoration">
               <option value="1" <?php if($row[27]==1) echo "selected"; ?>>None</option>
              <option value="2" <?php if($row[27]==2) echo "selected"; ?>>Underline</option>
                  </select>              </td>
            <td align="center">-NA-</td>
          </tr>
		  
          <tr>
            <td  colspan="3">
		  
								<table width="100%" border="0" id="tab2">
								
								  <tr>
									<td height="30" width="35%" align="left">Ad display url font </td>
									<td align="left" width="55%"><select name="display_font" size="1" id="display_font">
						  <option value="Arial, Helvetica, sans-serif" <?php if($row[12]=="Arial, Helvetica, sans-serif") echo "selected"; ?>>Arial</option>
						  <option value="Courier New, Courier, monospace" <?php if($row[12]=="Courier New, Courier, monospace") echo "selected"; ?>>Courier New </option>
						  <option value="Georgia, Times New Roman, Times, serif" <?php if($row[12]=="Georgia, Times New Roman, Times, serif") echo "selected"; ?>>Georgia </option>
						  <option value="Times New Roman, Times, serif" <?php if($row[12]=="Times New Roman, Times, serif") echo "selected"; ?>>Times New Roman </option>
						  <option value="Verdana, Arial, Helvetica, sans-serif" <?php if($row[12]=="Verdana, Arial, Helvetica, sans-serif") echo "selected"; ?>>Verdana </option>
									</select></td>
									<td align="center" width="10%">-NA-</td>
								  </tr>
								  <tr>
									<td height="30" align="left">Ad display url size </td>
									<td align="left"><select name="disp_url_size" id="disp_url_size">
									<option value="8" <?php if($row[13]==8) echo "selected"; ?>>8</option>
									 <option value="10" <?php if($row[13]==10) echo "selected"; ?>>10</option>
										<option value="12" <?php if($row[13]==12) echo "selected"; ?>>12</option>
										<option value="14" <?php if($row[13]==14) echo "selected"; ?>>14</option>
										<option value="16" <?php if($row[13]==16) echo "selected"; ?>>16</option>
									</select></td>
									<td align="center">-NA-</td>
								  </tr>
								  <tr>
									<td height="30" align="left">Ad display url font weight</td>
									<td align="left"><select name="ad_disp_url_font_weight" size="1" id="ad_disp_url_font_weight">
									   <option value="1" <?php if($row[28]==1) echo "selected"; ?>>Normal</option>
									  <option value="2" <?php if($row[28]==2) echo "selected"; ?>>Bold</option>
									</select>              </td>
									<td align="center">-NA-</td>
								  </tr>
								  <tr>
									<td height="30" align="left">Ad display url decoration</td>
									<td align="left"><select name="ad_disp_url_decoration" size="1" id="ad_disp_url_decoration">
									  <option value="1" <?php if($row[29]==1) echo "selected"; ?>>None</option>
									  <option value="2" <?php if($row[29]==2) echo "selected"; ?>>Underline</option>
										  </select>              </td>
									<td align="center">-NA-</td>
								  </tr>
								  </table>
		  </td>
		  </tr>
		  
          <tr>
            <td  colspan="3">
            
			
								<table border="0" width="100%" cellpadding="0" cellspacing="0">
							
					<tr>            <td width="35%" height="50"  >Ad title color </td>
								<td width="15%" ><div class="form-item"><input name="color1" type="text" ID="color1" class="colorwell"  style="background-color:<?php echo $row22[8]; ?>" value="<?php echo $row22[8]; ?>"  ></div></td>
								<td width="40%" rowspan="4" ><div id="picker" style="float: left;"></div></td>
								<td width="10%" align="center" ><input name="allow_title_color" type="checkbox" id="allow_title_color" value="1" <?php if($row22[34]==1) echo "checked"; ?>></td>
							  </tr>
							  <tr>
								<td height="50"   align="left">Ad description color </td>
								<td align="left"  ><div class="form-item"><input  type="text" id="color2" name="color2" class="colorwell"  value="<?php echo $row22[11]; ?>"  style="background-color:<?php echo $row22[11]; ?>"></div></td>
					 
								<td align="center"  ><input name="allow_desc_color" type="checkbox" id="allow_desc_color" value="1" <?php if($row22[35]==1) echo "checked"; ?>></td>
							  </tr>
							   <tr <?php 	if($row[3]==7) { ?>  style="display:none" <?php } ?>>
								<td height="50" align="left"  >Ad display url color </td>
								<td align="left"><div class="form-item"><input  type="text" id="color3" name="color3" class="colorwell"  value="<?php echo $row22[14]; ?>" style="background-color:<?php echo$row22[14]; ?>"></div></td>
							
								<td align="center"><input name="allow_disp_url_color" type="checkbox" id="allow_disp_url_color" value="1" <?php if($row22[36]==1) echo "checked"; ?>></td>
							  </tr>
							 
							  <tr>
								<td height="50" align="left">Background color </td>
								<td align="left"><div class="form-item"><input ype="text" id="color4" name="color4" class="colorwell" value="<?php echo$row22[15]; ?>" style="background-color:<?php echo $row22[25]; ?>"></div></td>
								
								<td align="center"><input name="allow_bg_color" type="checkbox" id="allow_bg_color" value="1"  <?php if($row22[37]==1) echo "checked"; ?>></td>
							  </tr>
							</table>
			
			
			</td>
          </tr>
		  
	 
		  
		  
	 <!--     <tr>
        <td  height="24" align="left"> Ad orientation </td>
        <td align="left"><select name="ad_orientation" size="1" id="ad_orientation">
          <option value="1" <?php //if($row[5]==1) echo "selected"; ?>>Vertical</option>
          <option value="2" <?php //if($row[5]==2) echo "selected"; ?>>Horizontal</option>
        </select></td>
        <td align="center">-NA-</td>
      </tr> -->
	            <tr>
            <td height="24" align="left">Text Line Height </td>
            <td align="left"><input name="line_spacing" type="text" id="line_spacing" value="<?php echo $row[40]; ?>" /></td>
            <td align="center">-NA-</td>
          </tr>
			</table>
			
			
			<table width="100%" border="0" id="tab-catalog"  >
		  <tr>
            <td width="35%" height="24" align="left">Catalog size </td>
            <td width="55%" align="left">
			 <?php 
			$catalog_block=mysql_query("select id,width,height,filesize from catalog_dimension where id='$row[41]'");	
 			 $catalog_row=mysql_fetch_row($catalog_block);
			  echo   $catalog_row[1]." X ".$catalog_row[2];  ?>

			</td>
            <td width="10%" align="center">-NA-</td>
          </tr>  
		   <tr>
            <td height="24" align="left"  >No of catalog ads </td>
            <td align="left"   >1</td>
            <td align="center" >-NA-</td>
          </tr>
		    <tr>
            <td height="24" align="left">Catalog alignment</td>
		<td>Left 
		<!--<select name="catalog_alignment" size="1" id="catalog_alignment">
          <option value="1" <?php // if($row[43]==1) { ?> selected="selected" <?php //} ?>>Left</option>
          <option value="0" <?php // if($row[43]==0) { ?> selected="selected" <?php //} ?>>Center</option>
                </select>--></td>
            <td align="center">-NA-</td>
          </tr>
		<!--    <tr>
            <td height="24" align="left">Catalog line seperator</td>
		<td><select name="catalog_line_seperator" size="1" id="catalog_line_seperator">
          <option value="1" <?php  // if($row[44]==1) { ?> selected="selected" <?php //} ?>>Yes</option>
          <option value="0" <?php // if($row[44]==0) { ?> selected="selected" <?php //} ?>>No</option>
                </select></td>
            <td align="center">-NA-</td>
          </tr> -->
		  
          </table>
  
        </div>	

  
  
  
  <!-- Div 3 is starting here...................-->

  
	    <div id="index1_div_3" style="padding:5px;width:100%;">
 <table width="100%" border="0" id="tab3"  >
            <tr>
              <td width="80" align="left">Ad title </td>
              <td width="302" align="left"><input name="ad_title" type="text" id="ad_title" size="50" value="<?php echo $mysql->echo_one("select value from ppc_settings where name='ad_title'");?>" maxlength="<?php echo $mysql->echo_one("select value from ppc_settings where name='ad_title_maxlength'");?>" /> <?php echo $mysql->echo_one("select value from ppc_settings where name='ad_title_maxlength'");?> chars max</td>
            </tr>
            <tr>
              <td align="left">Ad description </td>
              <td align="left"><input name="ad_description" type="text" id="ad_description" size="50" value="<?php echo $mysql->echo_one("select value from ppc_settings where name='ad_description'");?>" maxlength="<?php echo $mysql->echo_one("select value from ppc_settings where name='ad_description_maxlength'");?>" /> <?php echo $mysql->echo_one("select value from ppc_settings where name='ad_description_maxlength'");?> chars max</td>
            </tr>
            <tr>
              <td align="left">Display url </td>
              <td align="left"><input name="ad_display_url" type="text" id="ad_display_url" size="50" value="<?php echo $mysql->echo_one("select value from ppc_settings where name='ad_display_url'");?>" maxlength="<?php echo $mysql->echo_one("select value from ppc_settings where name='ad_displayurl_maxlength'");?>" /> <?php echo $mysql->echo_one("select value from ppc_settings where name='ad_displayurl_maxlength'");?> chars max</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
 

		  
		  
 	 </div>
	  
<br><center> <input name="Update and Preview" type="submit" id="Update and Preview" value="Update and Preview"></center><br>  


</td>

</tr>
 </table>

</form>

<script language="javascript" type="text/javascript">
index1_ShowTab(1);

adtypeChanged();
</script>

 
<?php include("admin.footer.inc.php");?>
