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





function SingleLineModifyAction(singlelinevalue)
{

sing=document.getElementById("ad_type").value;
if(sing ==1)
singleline=1;
else
singleline=0;



sidata=document.getElementById('credit_text_positioning');	


if(sidata.options.length >2)
singleline3=1;
else
singleline3=0;



if(singleline ==1 && singleline1 ==1 && singleline2 ==1 && singleline3==0)
{





var optsingle=document.createElement("OPTION");
optsingle.value=2;
optsingle.text="Left";   
document.getElementById('credit_text_positioning').options.add(optsingle);


var optsingle1=document.createElement("OPTION");
optsingle1.value=3;
optsingle1.text="Right";   
document.getElementById('credit_text_positioning').options.add(optsingle1);


if(singlelinevalue==2)
sidata.options[2].selected = true;
else if(singlelinevalue ==3)
sidata.options[3].selected = true;



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











			function adtypeChanged()
				{
				//alert(document.getElementById("tab").rows['t1']);
//				var t1=document.getElementById("ad_type").options[document.getElementById("ad_type").selectedIndex].value;
				var t1=document.getElementById("ad_type").value;
				//alert(t1);
				if(t1==2) 
					{
									//alert("banner only");
					document.getElementById('tab3').style.display="none";  // dummy ad settings
					document.getElementById('l1').style.display="";  // banner only notice in tab2
					document.getElementById('l2').style.display=""; // banner only notice in tab3
					document.getElementById('tab2').style.display="none"; // text/catalog common settings
					//document.getElementById('tab1').rows['t1'].style.display=""; //
					document.getElementById('tab1').rows['borderrow'].style.display="none"; //border
					document.getElementById('tab-catalog').style.display="none"; //catalog settings
					//document.getElementById('tab-common').style.display="none";
					document.getElementById('text1').style.display="none"; //text ad only settings
					document.getElementById('text2').style.display="none";  // display url settings
					}
				else if(t1==1) 
					{
					//			alert("text only");
					//document.getElementById('tab1').rows['t1'].style.display="none";
					document.getElementById('tab1').rows['borderrow'].style.display="";
					document.getElementById('l1').style.display="none";
					document.getElementById('l2').style.display="none";
					document.getElementById('tab3').style.display="";
					document.getElementById('tab2').style.display="";
					document.getElementById('tab-catalog').style.display="none";
					//document.getElementById('tab-common').style.display="";
					document.getElementById('text1').style.display="";
					document.getElementById('text2').style.display="";		
					}
				else if(t1==4) 
					{
					//											alert("catalog");
					//document.getElementById('tab1').rows['t1'].style.display="none";
					document.getElementById('tab1').rows['borderrow'].style.display="";
					document.getElementById('l1').style.display="none";
					document.getElementById('l2').style.display="none";
					document.getElementById('tab3').style.display="";
					document.getElementById('tab2').style.display="";
					document.getElementById('tab-catalog').style.display="";
					//document.getElementById('tab-common').style.display="none";
					document.getElementById('text1').style.display="none";
					document.getElementById('text2').style.display="none";
					}
				else
				{
		//						alert("text/banner");
					document.getElementById('l1').style.display="none";
					document.getElementById('l2').style.display="none";
					//document.getElementById('tab1').rows['t1'].style.display="";
					document.getElementById('tab1').rows['borderrow'].style.display="";
					document.getElementById('tab3').style.display="";
					document.getElementById('tab2').style.display="";
					document.getElementById('tab-catalog').style.display="none";
					//document.getElementById('tab-common').style.display="";
					document.getElementById('text1').style.display="";
					document.getElementById('text2').style.display="";
				}	
				}
	function index1_ShowTab(id)
{
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
  if(id=='4')
	{
	  	document.getElementById('index1_div_1').style.display="none";
		document.getElementById('index1_div_2').style.display="none";
		document.getElementById('index1_div_3').style.display="none";
		
		document.getElementById("index1_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		document.getElementById("index1_li_3").style.background="url(images/li_bgnormal.jpg) repeat-x";
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
		
		//document.getElementById("div_li_2").style.background="url(images/li_bgselect.jpg) repeat-x";
	//	document.getElementById("div_li_1").style.background="url(images/li_bgnormal.jpg) repeat-x";
		
	}
	if(id=='2')
	{
		if(document.getElementById('r2')!=null)
			document.getElementById('r2').checked="Checked";
			
		document.getElementById('div22').style.display="";
		document.getElementById('div11').style.display="none";
		document.getElementById('div33').style.display="none";
		
		//document.getElementById("div_li_1").style.background="url(images/li_bgselect.jpg) repeat-x";
		//document.getElementById("div_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		
	}
		if(id=='3')
	{
		if(document.getElementById('r3')!=null)
			document.getElementById('r3').checked="Checked";
			
		document.getElementById('div22').style.display="none";
		document.getElementById('div11').style.display="none";
		document.getElementById('div33').style.display="";
		
		//document.getElementById("div_li_1").style.background="url(images/li_bgselect.jpg) repeat-x";
		//document.getElementById("div_li_2").style.background="url(images/li_bgnormal.jpg) repeat-x";
		
	}
 
}
	function check_value()
				{
				
				
				if(document.getElementById('width'))
				{
				var val=document.getElementById('width').value;
				if(!(isInteger(val)))
					{
					alert("Pelase enter a valid width value");
					document.getElementById('width').value="";
					document.form1.width.focus();
					return false;
					}
					
				}	
					
					
				if(document.getElementById('height'))
				{
				var val=document.getElementById('height').value;
				if(!(isInteger(val)))
					{
					alert("Pelase enter a valid height value");
					document.getElementById('height').value="";
					document.form1.height.focus();
					return false;
					}
				}	
					
					
					
					var adtype=document.getElementById('ad_type').value;
					if(adtype==2 || adtype==3)
					{
					
					
					
				/*var val=document.getElementById('banner_file_max_size').value;
				if(!(isInteger(val)))
					{
					alert("Pelase enter a valid banner size value");
					document.getElementById('banner_file_max_size').value="";
					document.form1.banner_file_max_size.focus();
					return false;
					}
					*/
					
					
					
					
					}
				
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
	<script language="javascript" type="text/javascript">


function updateCreditBorderColor(credit_border_id,creditcolor,backgroundcolor)
{
document.getElementById('uc').value=credit_border_id;
document.getElementById('selected_credit').style.color=creditcolor;
document.getElementById('selected_credit').style.background=backgroundcolor;


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
<?php

$id= $_GET['id'];
phpsafe($id);

if(isset($_GET['wap']))
{
$wap_flag=$_GET['wap'];
}
else if(isset($_POST['wap']))
{
	$wap_flag=$_POST['wap'];
}
phpsafe($wap_flag);



if($wap_flag==1)
{   
    $ad_val=0;
	$wap_table='wap_ad_block';
	$wap_table1='ppc_custom_ad_block';
	$wap_string='and wapstatus=1';
	$query_string="where wap_status='1'";
	$name='wap';
	$preview_file='wap-adblock-preview.php';
	$wapdev=$mysql->echo_one("select id from ppc_custom_ad_block where bid='$id' and wapstatus=1");
}

else
{
    $ad_val=0;
	$wap_table='ppc_ad_block';
	$wap_table1='ppc_custom_ad_block';
	$query_string="where wap_status='0'";
	$name='ppc';
	$wap_string='and wapstatus=0';
    $preview_file='adblock_preview.php';
    $wapdev=$mysql->echo_one("select id from ppc_custom_ad_block where bid='$id' and wapstatus=0");
}


	$result=mysql_query("select * from $wap_table where id='$id'");
	$row=mysql_fetch_row($result);
	$row22=$row;
if(!$row)
{
?><br />
<span class="already">Invalid id.</span>
<?php
include_once("admin.footer.inc.php");die;
}

?>


<?php
/*if($wap_flag==1)
{  
 $ad_val=0;
 if($row[33]==0)
		{
		$ad_val=0;
		}
	else
		{
		$ad_val=15;
		}


}*/

?>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/adblocks.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading"> Modify Ad block</td>
  </tr>
</table>

<table width="100%" >
	   
	   
	   <!--start -->
 <tr><td >
 
	<?php
	if($row[3]==3)
		{
		?>    <table width="100%"  border="0" cellpadding="0" cellspacing="0"  > 
    <tr height="30px">

    <td   id="div_li_1"><label><input type="radio" value="1" name="r1" id="r1" checked="checked" onClick="javascript:index1_Show(this.value);">Text Preview&nbsp;&nbsp;&nbsp;</label>      <label>&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="r1" id="r2" onClick="javascript:index1_Show(this.value);">Banner Preview </label> </td>
    
    </tr>
    </table>
<?php }
	?>

</td>
  </tr>
	
	
	<!-- end-->
	
  <tr>
    <td  >
	
	<div id="div11" style="padding:0px;" >
		  <iframe height="<?php echo $row[2]+$ad_val; ?>" width="<?php echo $row[1]; ?>" frameborder="0" src="<?php echo $preview_file;?>?id=<?php echo $row[0]; ?>&uid=0" ></iframe>
	  </div>

 <div id="div22"  style="padding:0px;display:none;">
 <iframe height="<?php echo $row[2]+$ad_val; ?>" width="<?php echo $row[1]; ?>" frameborder="0" src="<?php echo $preview_file;?>?id=<?php echo $row[0]; ?>&type=image&uid=0"></iframe>
   </div>	
    <div id="div33"  style="padding:0px;display:none;">
 <iframe height="<?php echo $row[2]+$ad_val; ?>" width="<?php echo $row[1]; ?>" frameborder="0" src="<?php echo $preview_file;?>?id=<?php echo $row[0]; ?>&type=catalog&uid=0"></iframe>
   </div>
	</td>
  </tr>
  <tr>
  <td><br>  
  <strong>Note :</strong>
  <span class="info"><br />
1. If the ad preview shown contains scrollbars, the ads will be clipped in  pages where you paste the ad display code; You may modify the ablock settings like height, width,no. of ads,line height, title font size, description font size and display ur  font size until no scrollbars appear in the preview. Also note that, since different characters have different width, you may try editing the dummy ad and ensure that preview has no scrollbars.<br /><br />

<!--2. 15px extra will be used for credit text.<br /><br />-->


  <?php if($row[23]==0)
  	{
 ?> 2. This ad block is currently inactive. If you are satisfied with the ad preview <strong><a href=ppc-activate-ad.php?id=<?php echo $id; ?>&wap=<?php echo $wap_flag; ?>>click here</a></strong> to activate this block. Only active ad blocks can be used to generate ad display units.
	<?php
	}
	?>

  </span>
  <br>
  </td>
  </tr>
  <tr>
    <td >	
	<form method="post" name="form1" action="update-ad-block.php" onSubmit="return check_value()">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="wap" value="<?php echo $wap_flag;?>"><?php //echo $wap_flag; exit(); ?>
	
	<table width="100%"  border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #CCCCCC ">
    <tr>
      <td  valign="top" >

<div align="left" width: "100%">

<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="indexmenus" >
  <tr height="30px">
    <td align="center"  id="index1_li_1" style="border-left-width:0px; "><a href="javascript:index1_ShowTab('1');"  >General settings</a></td>
    <td  align="center" id="index1_li_2"><a href="javascript:index1_ShowTab('2');" ><?php if($row[3]==1||$row[3]==3) echo "Text"; elseif($row[3]==4)  echo "Catalog"; else echo "Text/Catalog"; ?> ad settings</a></td>
    <td  align="center" id="index1_li_3" ><a href="javascript:index1_ShowTab('3');" >Dummy ad settings</a></td>
  </tr>
</table>

</div>
</td>
      </tr>
  

    <tr>
      <td width="100%" valign="top" >	



<!-- Div 1 is starting here...................-->
  


		<div id="index1_div_1" style="padding:5px;" >
		  <table width="100%" border="0" id="tab1">
            <tr>
              <td height="30" align="left"><span class="style3"><strong>Property</strong></span></td>
              <td height="30" align="left"><strong>Value</strong></td>
              <td><strong>  Override </strong></td>
            </tr>
            <tr><td height="30" align="left">Ad block name </td>
            <td height="30" align="left"><input name="ad_block_name" type="text" id="ad_block_name" value="<?php echo $row[32]; ?>" size="30" maxlength="30" /></td>
            <td align="center">-NA-</td>
     </tr>
	 <?php
	 $adblock_used=0;
	 
	 if($wapdev>0)
	 	 $adblock_used=1;
 
 /*	$num=$mysql->echo_one("select count(*) from $wap_table1 where bid='$id' $wap_string");
	$num1=$mysql->echo_one("select count(*) from ppc_ads where bannersize='$id' and adtype=1 $wap_string");
	$num2=$mysql->echo_one("select count(*) from ppc_public_service_ads where bannersize='$id' and adtype=1 $wap_string");
	if($num==0 && $num1==0 && $num2==0)
	{
			 $adblock_used=0;
	}*/
	
?><?php
if($row[3]==1 ||$row[3]==4)
{ ?>
			<tr>
             <td width="35%" height="30" align="left">Width </td>
             <td width="55%" height="30" align="left"><label>
		<?php if($adblock_used==0) {?>
          <input name="width" type="text" id="width" value="<?php echo $row[1]; ?>"  />
          
		  <?php }else{ ?>
		  <input name="width" type="hidden" id="width" value="<?php echo $row[1]; ?>"  /><?php echo $row[1]; ?> px <br />
<span class="note">(Cannot be edited; already used in adunits)</span>
		  <?php } ?>
       </label></td>
        <td align="center" width="10%">-NA-</td>
			</tr>
      <tr>
        <td  height="30" align="left">Height </td>
        <td align="left">
		<?php if($adblock_used==0) {?>
		<input name="height" type="text" id="height" value="<?php echo $row[2]; ?>" />
		<?php }else{ ?>
		<input name="height" type="hidden" id="height" value="<?php echo $row[2]; ?>" /><?php echo $row[2]; ?> px<br />
<span class="note">(Cannot be edited; already used in adunits)</span>
		<?php } ?>
        </td>
        <td align="center">-NA-</td>
      </tr>
      <?php }
      else
      {
      	if($adblock_used==0)
      	{
      	?>     <tr>
        <td  height="30" align="left">Banner size </td>
		 <td align="left">
		<select name="banner_size1" size="1" id="banner_size1" dir="ltr">
			<?php
			//$mysql->echo_one("select id from banner_dimension where id='$row['");
						$banner_block=mysql_query("select * from banner_dimension $query_string order by id ");
			$i=0;
			 while($banner_row=mysql_fetch_row($banner_block))
				{
				
				?>
				<option value="<?php echo $banner_row[0]; ?>" <?php if($row[4]==$banner_row[0]) echo "selected"; ?> ><?php echo $banner_row[1]." X ".$banner_row[2]; ?></option>
              <?php $i++; } ?>
            </select></td>
			<td align="center">-NA-</td>
     		 </tr><?php
			 	 }
		  else
		  { ?>
			 <tr>
			 <td  height="30" align="left">Banner size </td>
			 <td align="left"><input type="hidden" name="banner_size1" value="<?php echo $row[4];?>" > <?php echo $row[1]." px X ".$row[2];?> px <br />
	<span class="note">(Cannot be edited; already used in adunits)</span>
			</td>
			<td align="center">-NA-</td>
			 </tr>
		 <?php 
	 		}
       } ?>
      
      
      <tr>
        <td  height="30" align="left">Ad type<br>
<?php /*if($num>0 ) {?>Already used in ad units/ads, cannot change  <?php } */?> </td>
        <td align="left"><label>
		<?php //if($adblock_used==1 ) {
		if($row[3]==1)
			echo "Text Only";
		else if($row[3]==2) echo "Banner Only";
		else if($row[3]==4) echo "Catalog Only";
		else
			echo "Text/Banner";
		?>
		<input type="hidden" name="ad_type" id="ad_type" value="<?php echo $row[3]; ?>"/>
		<?php 
		
		/*} 
		
		else
			{ ?>
          <input name="ad_type"  id="ad_type" type="text" value="<?php
if($row[3]==1)
			echo "Text Only";
		else if($row[3]==2) echo "Banner Only";
		else if($row[3]==4) echo "Catalog Only";
		else
			echo "Text/Banner";
          ?>" readonly="readonly">
            
		  <?php }*/ ?>
        </label></td>
        <td align="center">-NA-</td>
      </tr>
      <tr>
        <td  height="30" align="left">Allow for publishers </td>
        <td align="left"><select name="allow_publishers" size="1" id="allow_publishers">
          <option value="1" <?php if($row[21]==1) echo "selected"; ?>>Yes</option>
          <option value="0" <?php if($row[21]==0) echo "selected"; ?>>No</option>
                </select></td>
				<td align="center">-NA-</td>
      </tr>
     
          
		  <?php 
		 
		 //$codes=mysql_query("select id from adserver_languages where code='en' and status='1'");	
		 //$code1=mysql_fetch_row($codes);
	     //$return=mysql_query("select id from ppc_publisher_credits where language_id='$code1[0]' and id='$row[33]'");
		 //$return1=mysql_fetch_row($return);
	
			//$result_credit=mysql_query("select * from ppc_publisher_credits where parent_id='0'");	
			
		  ?>
		  
		  
		  
		  
		  
		  
<tr>  
<td height="30" align="left">Credit text </td>
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
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
            <!--<td height="30" align="left">Credit text </td>
            <td height="30" align="left">
			<select name="credit_text" size="1" id="credit_text">
                        
			<?php //while($credit_row=mysql_fetch_row($result_credit))
				  //{
				    ?>
				 <option value="<?php //echo $credit_row[0]; ?>" <?php //if("$credit_row[0]"=="$return1[0]") echo "selected"; ?>><?php //echo $credit_row[1]; ?></option>
            <?php //} ?>
            </select>
			</td>
            <td align="center">-NA-</td>-->
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
          <tr>
		  	<td width="35%" height="30" align="left">Credit text font </td>
            <td width="55%" height="30" align="left"><select name="credit_text_font" size="1" id="credit_text_font">
              <option value="Arial, Helvetica, sans-serif" <?php if($row[16]=="Arial, Helvetica, sans-serif") echo "selected"; ?>>Arial</option>
              <option value="Courier New, Courier, monospace" <?php if($row[16]=="Courier New, Courier, monospace") echo "selected"; ?>>Courier New</option>
              <option value="Georgia, Times New Roman, Times, serif" <?php if($row[16]=="Georgia, Times New Roman, Times, serif") echo "selected"; ?>>Georgia</option>
              <option value="Times New Roman, Times, serif" <?php if($row[16]=="Times New Roman, Times, serif") echo "selected"; ?>>Times New Roman</option>
              <option value="Verdana, Arial, Helvetica, sans-serif" <?php if($row[16]=="Verdana, Arial, Helvetica, sans-serif") echo "selected"; ?>>Verdana</option>
            </select></td>
            <td align="center" width="10%">-NA-</td>
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
           <td  height="30" align="left">Credit text / border color<br />

             (<a href="ppc-edit-credittext-bordercolor.php">Edit color combinations</a>)</td>
            <td align="left"><?php
	$res1=mysql_query("select * from ppc_credittext_bordercolor order by id DESC");
	$Credit="";
	$selected_credit="";
		while($row1=mysql_fetch_row($res1))
		{
		 if($row1[0]==$row22[17])
		 	{
			$selected_credit='<span id= "selected_credit" style="cursor:default;padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';">credit text</span>'; 
			}

				//$Credit=$Credit.'<label style="color:'. $row1[1].';background-color:'. $row1[2].';padding:2px;margin:2px"> <input name="cc" type="radio"  value="'.$row1[0].'" '.$che.'>  credit-text </label>';
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
              <?php 
			  /*
              while($row55=mysql_fetch_row($result1))
			  	{
				?>
              <?php }
			  */ ?>
                  </select></td>
            <td align="center">-NA-</td>
          </tr>
          <tr>
            <td  height="30" align="left">Credit text positioning<br> 
              (relative to ad block) </td>
            <td align="left"><select name="credit_text_positioning" size="1" id="credit_text_positioning">
              <option value="1" <?php if($row22[20]==1) echo "selected"?>>Top</option>
              <option value="0" <?php if($row22[20]==0) echo "selected"?>>Bottom</option>
			  
			  <?php
			  if($row[3]==1 && $row[41]==2 && $row[5]==2)
			 {
			  ?>
			  <option value="2" <?php if($row22[20]==2) echo "selected"?>>Left</option>
              <option value="3" <?php if($row22[20]==3) echo "selected"?>>Right</option>
			  
			  
			  <?php
			  }
			  
			  
			  
			  
			  ?>
			  
             
			 
 
                  </select>
				  
				 <?php   $singlelinevalue=$row22[20];  ?>
				  
				  </td>
            <td align="center">-NA-</td>
          </tr>
          <tr id="borderrow" <?php if($row[3]==2) {?> style="display:none"<?php }?>>
            <td  height="30" align="left">Border type </td>
            <td align="left"><select name="border_type" size="1" id="border_type">
              <option value="1" <?php if($row22[22]==1) echo "selected"?>>Regular</option>
              <option value="0" <?php if($row22[22]==0) echo "selected"?>>Rounded</option>
              
                  </select><span class="info">(Note:Border type will be set to regular style for image credit texts and credit text positioning left/right.)</span></td>
            <td align="center"><input name="allow_bordor_type" type="checkbox" id="allow_bordor_type" value="1" <?php if($row22[39]==1) echo "checked"; ?>></td>
          </tr>
			
          </table>
		</div>






<!-- Div 2 is starting here...................-->

	    <div id="index1_div_2"  style="padding:5px;display:;width:100%;">




<label id="l1" style="display:none;">This is a banner only block. Yo cannot configure  text/catolog ad related settings for this.</label>


<table width="100%" border="0" id="tab2">
<tr>
  <td width="35%"  height="30" align="left"><strong>Property</strong></td>
  <td width="55%" align="left"><strong>Value</strong></td>
  <td width="10%"><strong> Override </strong></td>
</tr>
<tr><td colspan="3">
					<div id="text1" style="padding:0px 0px;margin:0px 0px;display: ">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">	
					<tr id="t_ad_type">
					<td width="35%"  height="30" align="left">Text Ad type</td>
					<td width="55%" align="left">
					<?php //echo $row[41];?>
					<select name="text_ad_type" id="text_ad_type" onchange="SingleLineModify();SingleLineModifyAction(<?php echo $singlelinevalue; ?>)">
						<option value="1" <?php if($row[41]==1) echo "selected"; ?>>Title/Description/Display url</option>
						<option value="2" <?php if($row[41]==2) echo "selected"; ?>>Title only</option>
						<option value="3" <?php if($row[41]==3) echo "selected"; ?>>Title/Description</option>
						
					  </select>
					  </td>
					<td width="10%" align="center">-NA-</td>
					</tr>
					<tr>
					<td  height="30" align="left">No. of text ads </td>
					<td align="left"><!--<input name="no_of_text_ads" type="text" id="no_of_text_ads" value="<?php echo $row[18]; ?>"/>-->
					<select name="no_of_text_ads" size="1" id="no_of_text_ads">
					  <option value="1"  <?php if($row[18]==1) echo "selected"; ?>>1</option>
					  <option value="2"  <?php if($row[18]==2) echo "selected"; ?>>2</option>
					  <option value="3"  <?php if($row[18]==3) echo "selected"; ?>>3</option>
					  <option value="4"  <?php if($row[18]==4) echo "selected"; ?>>4</option>
					  <option value="5"  <?php if($row[18]==5) echo "selected"; ?>>5</option>
					   <option value="6"  <?php if($row[18]==6) echo "selected"; ?>>6</option>
					   <option value="7"  <?php if($row[18]==7) echo "selected"; ?>>7</option>
					   <option value="8"  <?php if($row[18]==8) echo "selected"; ?>>8</option>
					   <option value="9"  <?php if($row[18]==9) echo "selected"; ?>>9</option>
					   <option value="10"  <?php if($row[18]==10) echo "selected"; ?>>10</option>
					</select>		</td>
					<td align="center">-NA-</td>
						</tr>
					</table></div>
					
</td></tr>	

<tr>
<td  height="30" align="left"> Ad orientation </td>
<td align="left"><select name="ad_orientation" size="1" id="ad_orientation" onchange="SingleLineModifyHorizontal();SingleLineModifyAction(<?php echo $singlelinevalue; ?>)">
<option value="1" <?php if($row[5]==1) echo "selected"; ?>>Vertical</option>
<option value="2" <?php if($row[5]==2) echo "selected"; ?>>Horizontal</option>
</select></td>
<td align="center">-NA-</td>
</tr>
<tr>
<td   height="30" align="left">Ad title font </td>
<td   align="left"><label>

<select name="title_font" size="1" id="title_font">
<option value="Arial, Helvetica, sans-serif" <?php if($row[6]=="Arial, Helvetica, sans-serif") echo "selected"; ?>>Arial</option>
<option value="Courier New, Courier, monospace" <?php if($row[6]=="Courier New, Courier, monospace") echo "selected"; ?>>Courier New</option>
<option value="Georgia, Times New Roman, Times, serif" <?php if($row[6]=="Georgia, Times New Roman, Times, serif") echo "selected"; ?>>Georgia</option>
<option value="Times New Roman, Times, serif" <?php if($row[6]=="Times New Roman, Times, serif") echo "selected"; ?>>Times New Roman</option>
<option value="Verdana, Arial, Helvetica, sans-serif" <?php if($row[6]=="Verdana, Arial, Helvetica, sans-serif") echo "selected"; ?>>Verdana</option>
</select>
</label></td>
<td  align="center">-NA-</td>
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
<td height="30" align="left">Ad description font </td>
<td align="left"><select name="desc_font" size="1" id="desc_font">
<option value="Arial, Helvetica, sans-serif" <?php if($row[9]=="Arial, Helvetica, sans-serif") echo "selected"; ?>>Arial</option>
<option value="Courier New, Courier, monospace" <?php if($row[9]=="Courier New, Courier, monospace") echo "selected"; ?>>Courier New</option>
<option value="Georgia, Times New Roman, Times, serif" <?php if($row[9]=="Georgia, Times New Roman, Times, serif") echo "selected"; ?>>Georgia</option>
<option value="Times New Roman, Times, serif" <?php if($row[9]=="Times New Roman, Times, serif") echo "selected"; ?>>Times New Roman</option>
<option value="Verdana, Arial, Helvetica, sans-serif" <?php if($row[9]=="Verdana, Arial, Helvetica, sans-serif") echo "selected"; ?>>Verdana</option>
</select></td>
<td align="center">-NA-</td>
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

<tr><td colspan="3">

					<div id="text2" style="padding:0px 0px;margin:0px 0px;display: ">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">	
					<tr>
					<td width="35%" height="30" align="left">Ad display url font </td>
					<td width="55%" align="left"><select name="display_font" size="1" id="display_font">
					  <option value="Arial, Helvetica, sans-serif" <?php if($row[12]=="Arial, Helvetica, sans-serif") echo "selected"; ?>>Arial</option>
					  <option value="Courier New, Courier, monospace" <?php if($row[12]=="Courier New, Courier, monospace") echo "selected"; ?>>Courier New</option>
					  <option value="Georgia, Times New Roman, Times, serif" <?php if($row[12]=="Georgia, Times New Roman, Times, serif") echo "selected"; ?>>Georgia</option>
					  <option value="Times New Roman, Times, serif" <?php if($row[12]=="Times New Roman, Times, serif") echo "selected"; ?>>Times New Roman</option>
					  <option value="Verdana, Arial, Helvetica, sans-serif" <?php if($row[12]=="Verdana, Arial, Helvetica, sans-serif") echo "selected"; ?>>Verdana</option>
					</select></td>
					<td width="10%" align="center">-NA-</td>
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
					</table></div>
		  
</td></tr>

<tr><td colspan="3">

		   
					   
					<table border="0" width="100%" cellpadding="0" cellspacing="0">
					<tr>            <td width="35%" height="50"  >Ad title color </td>
					<td width="15%" ><div class="form-item"><input name="color1" type="text" ID="color1" class="colorwell"  style="background-color:<?php echo $row22[8]; ?>" value="<?php echo $row22[8]; ?>"  ></div></td>
					<td width="40%"  rowspan="4" ><div id="picker" style="float: left;"></div></td>
					<td width="10%" align="center" ><input name="allow_title_color" type="checkbox" id="allow_title_color" value="1" <?php if($row22[34]==1) echo "checked"; ?>></td>
					</tr>
					<tr>
					<td height="50" align="left">Ad description color </td>
					<td align="left"><div class="form-item"><input  type="text" id="color2" name="color2" class="colorwell"  value="<?php echo $row22[11]; ?>"  style="background-color:<?php echo $row22[11]; ?>"></div></td>
					
					<td align="center"><input name="allow_desc_color" type="checkbox" id="allow_desc_color" value="1" <?php if($row22[35]==1) echo "checked"; ?>></td>
					</tr>
					<tr>
					<td height="50" align="left">Ad display url color </td>
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
		<tr>
<td height="24" align="left">Text Line Height </td>
<td align="left"><input name="line_spacing" type="text" id="line_spacing" value="<?php echo $row[40]; ?>" /></td>
<td align="center">-NA-</td>
</tr>
</table>




<table width="100%" border="0" id="tab-catalog">
<tr>
<td  width="35%" height="24" align="left">Catalog size </td>
<td width="55%"  align="left">
<?php 
$wap_string="where $wap_string";
// echo $wap_string;
$wap_string=str_replace("and","",$wap_string);
	//	 echo "select id,width,height,filesize from catalog_dimension $wap_string order by id";
$catalog_block=mysql_query("select id,width,height,filesize from catalog_dimension $wap_string order by id");	
?>
<select name="catalog_size" size="1" id="catalog_size">
<?php while($catalog_row=mysql_fetch_row($catalog_block))
{				
?>
<option value="<?php echo $catalog_row[0]; ?>" <?php  if($row[42]==$catalog_row[0]) { ?> selected="selected" <?php } ?> ><?php echo $catalog_row[1]." X ".$catalog_row[2]; ?></option>
<?php } ?>
</select>
</td>
<td width="10%"  align="center">-NA-</td>
</tr>
<tr>
<td height="24" align="left">No of catalog ads </td>
<td align="left">
<select name="no_of_catalog_ads" size="1" id="no_of_catalog_ads">
<option value="1" <?php  if($row[43]==1) { ?> selected="selected" <?php } ?>>1</option>
<option value="2" <?php  if($row[43]==2) { ?> selected="selected" <?php } ?>>2</option>
<option value="3" <?php  if($row[43]==3) { ?> selected="selected" <?php } ?>>3</option>
<option value="4" <?php  if($row[43]==4) { ?> selected="selected" <?php } ?>>4</option>
<option value="5" <?php  if($row[43]==5) { ?> selected="selected" <?php } ?>>5</option>
<option value="6" <?php  if($row[43]==6) { ?> selected="selected" <?php } ?>>6</option>
<option value="7" <?php  if($row[43]==7) { ?> selected="selected" <?php } ?>>7</option>
<option value="8" <?php  if($row[43]==8) { ?> selected="selected" <?php } ?>>8</option>
<option value="9" <?php  if($row[43]==9) { ?> selected="selected" <?php } ?>>9</option>
<option value="10" <?php  if($row[43]==10) { ?> selected="selected" <?php } ?>>10</option>
</select>
</td>
<td align="center">-NA-</td>
</tr>
<tr>
<td height="24" align="left">Catalog alignment</td>
<td><select name="catalog_alignment" size="1" id="catalog_alignment">
<option value="1" <?php  if($row[44]==1) { ?> selected="selected" <?php } ?>>Left</option>
<option value="0" <?php  if($row[44]==0) { ?> selected="selected" <?php } ?>>Center</option>
</select></td>
<td align="center">-NA-</td>
</tr>
<tr style="display:none">
<td height="24" align="left">Catalog line seperator</td>
<td><select name="catalog_line_seperator" size="1" id="catalog_line_seperator">
<option value="1" <?php  if($row[45]==1) { ?> selected="selected" <?php } ?>>Yes</option>
<option value="0" <?php  if($row[45]==0) { ?> selected="selected" <?php } ?>>No</option>
</select></td>
<td align="center">-NA-</td>
</tr>

</table>
  
  </div>	
  
  
  
  <!-- Div 3 is starting here...................-->



<?php
if($wap_flag==1)
{
	$title_length='wap_title_length';
	$durl_length='wap_url_length';
	$desc_length='wap_desc_length';
	
	
	$demmy_title_length='wap_ad_title';
	$demmy_durl_length='wap_ad_display_url';
	$demmy_desc_length='wap_ad_description';
	
	
}
else
{
	
	$title_length='ad_title_maxlength';
	$durl_length='ad_displayurl_maxlength';
	$desc_length='ad_description_maxlength';
	
	$demmy_title_length='ad_title';
	$demmy_durl_length='ad_display_url';
	$demmy_desc_length='ad_description';
}

?>
  
	    <div id="index1_div_3" style="padding:5px;width:100%;">
		<label id="l2" style="display:none;">You have selected banner only block. You dont have to change dummy text ad for getting the preview.</label>
<table width="100%" border="0" id="tab3">
            <tr>
              <td width="23%"   align="left">Ad title </td>
              <td width="77%"   align="left"><input name="ad_title" type="text" id="ad_title" size="50" value="<?php echo $mysql->echo_one("select value from ppc_settings where name='$demmy_title_length'");?>" maxlength="<?php echo $mysql->echo_one("select value from ppc_settings where name='$title_length'");?>" /> <?php echo $mysql->echo_one("select value from ppc_settings where name='$title_length'");?> chars max</td>
            </tr>
            <tr>
              <td align="left">Ad description </td>
              <td align="left"><input name="ad_description" type="text" id="ad_description" size="50" value="<?php echo $mysql->echo_one("select value from ppc_settings where name='$demmy_desc_length'");?>" maxlength="<?php echo $mysql->echo_one("select value from ppc_settings where name='$desc_length'");?>" /> <?php echo $mysql->echo_one("select value from ppc_settings where name='$desc_length'");?> chars max</td>
            </tr>
            <tr>
              <td align="left">Display url </td>
              <td align="left"><input name="ad_display_url" type="text" id="ad_display_url" size="50" value="<?php echo $mysql->echo_one("select value from ppc_settings where name='$demmy_durl_length'");?>" maxlength="<?php echo $mysql->echo_one("select value from ppc_settings where name='$durl_length'");?>" /> <?php echo $mysql->echo_one("select value from ppc_settings where name='$durl_length'");?> chars max</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>

		  
		  
	    </div>
		  
      </td>
    
    </tr>
	<tr>
	<td><br><center> <input name="Update and Preview" type="submit" id="Update and Preview" value="Update and Preview"></center><br>

	</td>
	</tr>
    
</table>
</form>	
</td>
</tr>
</table>


<script language="javascript" type="text/javascript">
index1_ShowTab(1);
</script>
<script language="javascript">
index1_Show(1);
</script>
<script language="javascript">
			 adtypeChanged();
			 
			 
			 SingleLineModify();
			 SingleLineModifyHorizontal();
			 //SingleLineModifyAction();
</script>
<?php include("admin.footer.inc.php");?>