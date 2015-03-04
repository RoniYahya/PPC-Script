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


$d_description=$ad_description;
		$ad_display_url=$ad_display_url;

$result=mysql_query("select pad.*,cad.*,cad.id as cadid,cad.credit_text as ctext,cad.title_color as tcolor,cad.desc_color as dcolor,cad.url_color as ucolor from ppc_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$id' and cad.bid<>'-1' and cad.bid<>'-2'");
if($row=mysql_fetch_array($result))
{

$bordertype=$row['bordor_type'];


	
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



$crstr=$row['ctext'];

$ct=$mysql->echo_one("select credittype from ppc_publisher_credits where id='".$crstr."'");


if($row['adlang']==0)  //anylanguages
{
	if($clan=='en')
	{   
	    if($ct==0)
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		else if($ct==1)
		{
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
        $credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';
		
		}
		
	}
	else
	{
	
	
	    if($ct==0)
	    {
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($credit_text=='')
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		}
		else if($ct==1)
		{
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='$lanid'");
		if($ctimage=='')
		$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		
		$credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';
		}
		
		
	}
	
	
}
else
{
	
		if($row['adlang']==$lanid )
		{
		
		
		if($ct==0)
	    {
				if($clan=='en')
					$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				else
					$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$row['adlang']."'");
					
		}
		else if($ct==1)
		{
		
		   if($clan=='en')
					$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
		   else
					$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$row['adlang']."'");
					
			
			$credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
					
		
		}			
					
				
		}
		else
		{
		
		    if($ct==0)
	        {
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$row['adlang']."'");
			if($credit_text=='')
				$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
				
			}
			else if($ct==1)
		   {
			
			$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$crstr." and language_id='".$row['adlang']."'");
			if($ctimage=='')
			$ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			
			
			$credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
			
		   }
				
		}
		if($credit_text=='')
		{
		     if($ct==0)
			 $credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			 else if($ct==1)
		     {
			 $ctimage=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$crstr."' ");
			 $credit_text='<img src="../credit-image/'.$crstr.'/'.$ctimage.'" border="0" height="15px"/>';		
			 }
			 
		}	 
			
		
}











/*

if($row['adlang']==0)  //anylanguages
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
		
		if($row['adlang']==$lanid )
		{
				if($clan=='en')
					$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$row['ctext']."' ");
				else
				{
					 $credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$row['ctext']." and language_id='".$row['adlang']."'");
				}
		}
		else
		{
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$row['ctext']." and language_id='".$row['adlang']."'");
			if($credit_text=='')
				$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=".$row['ctext']." and language_id='$lanid'");
		}
		if($credit_text=='')
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$row['ctext']."' ");
			
		
}
	
*/




	
	
//	
//if($row['adlang']==0)
//{
//	$clan=$mysql->echo_one("select value from ppc_settings where name='client_language'");
//	$lanid=$mysql->echo_one("select id from adserver_languages where code='$clan'");
//$parid=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='$lanid'");
//$fl=1;
//}
//else
//{
//$parid=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='".$row['adlang']."'");
//}
////echo $parid;
//if($parid==0)
//{
//if($fl==1)
//	{
//		$row['adlang']=$lanid;
//	}
//	$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where id='".$row['ctext']."' and language_id='".$row['adlang']."'");
//}
//else
//{
//if($fl==1)
//	{
//		$row['adlang']=$lanid;
//	}
//$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id='".$row['ctext']."' and language_id='".$row['adlang']."'");
//
//}
	

 //$ctext=$mysql->echo_one("select credit from ppc_publisher_credits where id=\"".$row['credit_text']."\"");


//echo "select credit from ppc_publisher_credits where id=\"".$row['credit_text']."\"";




 

//$credittextpreview="<table class=\"inout-inline-credit\" cellpadding=\"0\"  cellspacing=\"0\" ><tr ><td ><a target=\"_blank\" href=\"$server_dir"."index.php\">$ctext</a></td></tr></table>";


$credittextpreview="<tr class=\"inout-inline-credit\" width=100%><td  ><a target=\"_blank\" href=\"$server_dir"."index.php\">$credit_text</a></td></tr>";


 $catalogdimension=mysql_query("select * from catalog_dimension limit 0,1");
 $catalogcount=mysql_num_rows( $catalogdimension);
 if($catalogcount>0)
 {
 $catalog=mysql_fetch_row($catalogdimension);
 
 }


$res=mysql_query("select * from ppc_credittext_bordercolor where id=\"".$row['credit_color']."\"");
$r1=mysql_fetch_row($res);

if($row['ad_type']==7)
	{
	$ad_type="Inline Catalog Ad";
	}
	if($row['ad_type']==6)
	{
	$ad_type="Inline Text Ad";
	}

if($row['orientaion']==1)
	{
	$orientation="vertical";
	}
else
	{
	$orientation="Horizontal";
	}

//echo $id;
?>
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



<link href="style.css" rel="stylesheet" type="text/css">
<style type="text/css">
dl, dt, dd, ul, ol, li{margin:0;padding:0;vertical-align:baseline;}
</style>
<script language="javascript">
			


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
	/*
	if(id=='3')
	{
		if(document.getElementById('r3')!=null)
			document.getElementById('r3').checked="Checked";
		document.getElementById('div22').style.display="none";
		document.getElementById('div11').style.display="none";
			document.getElementById('div33').style.display="";
		
		
	}
	*/
}

	</script>
	
	<script language="javascript" type="text/javascript">
		  function selectcolor(color,value)
		  {
		 // alert(value);
		  color.style.backgroundColor=value;
		 // document.getElementById(id).value=document.getElementById('colortext').value;
			
		  }
		  
		  
		  </script>

<!--///////////////////////////-->

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/admin-adunits.php"; ?> </td>
  </tr>
  
  <tr>
   <td   colspan="4" scope="row" class="heading">Edit Ad Unit</td>
  </tr>

</table>
<br />






	<?php 
	
	if($row['ctext']==0)
		{
		$ad_val=0;
		}
	else
		{
		//$ad_val=15;
		$ad_val=0;
		}
	?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
   
   <tr height="10px"><td>&nbsp;</td></tr>
   
   
   <tr height="10px"><td align="left"><strong>Demo : </strong>Please move the mouse over the under lined text for the preview. &nbsp;&nbsp; Hello </td></tr>
   
   
   
      <tr height="10px"><td>&nbsp;</td></tr>

   
   
   
<tr><td class="heading">   Html code </td></tr>

   <tr height="10px"><td>&nbsp;</td></tr>

<tr>
<td>
<span class="info">Please copy the HTML code displayed in the text area below and paste it into your web page where you want to show the ads.</span>

<br>
<br>
<span class="info">Note : Please ensure that proper DOCTYPE is set for the page where the ad code is used.</span>

<br>
<br>
		</td></tr>
<tr>
  <td align="center">
  
  <table width="100%"  border="0" cellpadding="0"  cellspacing="0" style="border:1px solid #CCCCCC; ">
  <tr>
  <td>

	    <div id="index1_div_2" >

   <textarea name="textarea" cols="80" rows="10"  readonly="readonly" style="border:0px;width:99%;">

<script language="javascript" type="text/javascript" src="<?php echo $server_dir; ?>show-inline-ad.js"></script>
<script language="javascript">
inlinekeyword = "<YOUR KEYWORDS HERE>"; 
showInlineAds('<?php echo $id; ?>','<?php echo $server_dir; ?>show-inline-ads.php');
</script>

   </textarea>
 </div>

  

  	  </td>
	 
	</tr>
  	</table>
		
		
</td></tr>
   <tr height="10px"><td>&nbsp;</td></tr>

<tr><td class="heading">   Modify Ad Unit settings   </td></tr>
   <tr height="10px"><td>&nbsp;</td></tr>

<tr><td>
<form action="ppc-admin-update-adunits.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="users_resolution1" id="users_resolution1" value="0">
<input type="hidden" name="users_resolution2"  id="users_resolution2" value="0">
<table   cellpadding="0" cellspacing="0" border="0" width="100%">

<tr>
    <td width="30%" height="26">Preferred Language</td>
    
    <td width="30%">
   <select name="language" id="language" >

<?php 
$result=mysql_query("select adlang from ppc_custom_ad_block where id='$id'");
$db=mysql_fetch_row($result);

$res=mysql_query("select id,language,code from adserver_languages  where status='1'");

while($rr=mysql_fetch_row($res))
	{?><option value="<?php echo $rr[0]; ?>" <?php if($db[0]==$rr[0]) {?>selected="selected" <?php } ?>><?php echo$rr[1];  ?></option>
		<?php
	}
	?><option value="0" <?php if($db[0]==0) echo "selected"; ?>>Any Languages</option>
</select>
    
    </td>
    </tr>
<tr><td width="40%" height="27">Ad unit </td>
  <td><label>
    <input name="adunit_name" type="text" id="adunit_name" value="<?php echo $row['name']; ?>"/>
  </label></td>
  <td>&nbsp;</td>
</tr>
<tr height="">
  <td height="28">Ad type </td>
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


</td><td align="left">&nbsp;</td></tr>




<!--<?php //$result_credit=mysql_query("select * from ppc_publisher_credits"); ?> <select name="credit_text" size="1" id="credit_text"><option value="0" <?php //if($row['ctext']==0) echo "selected"; ?>>--Select--</option><?php //while($credit_row=mysql_fetch_row($result_credit)){	?><option value="<?php //echo $credit_row[0]; ?>" <?php //if($credit_row[0]==$row['ctext']) echo "selected"; ?>><?php //echo $credit_row[1]; ?></option><?php //} ?></select>-->
			
			
			
			
			
			
			
			
			
			
























<tr height="">
<?php
/*
if($db[0]==0)
{
$clan=$mysql->echo_one("select value from ppc_settings where name='client_language'");
$lanid=$mysql->echo_one("select id from adserver_languages where code='$clan'");
$parid1=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='$lanid'");
$fl=1;
}
else
{
$parid1=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='$db[0]'");
}
if($parid1==0)
{
if($fl==1)
	{
	$db[0]=$lanid;
	}
	$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where id='".$row['ctext']."' and language_id='$db[0]'");
}
else
{
if($fl==1)
	{
		$db[0]=$lanid;
	}
$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id='".$row['ctext']."' and language_id='$db[0]'");//echo $res['credit_text'];
}
*/




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
			$selected_credit='<span id= "selected_credit" style="cursor:default;padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';\">'.substr($credit_text,0,72).'</span>'; 
			else
			$selected_credit='<span id= "selected_credit" style="cursor:default;padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';\">credit text</span>'; 
			
			}

if($cr_type==0)	        
$Credit=$Credit."<li ><a onMouseOver=\"javascript:updateCreditBorderColor($row1[0]  ,  '$row1[1]' ,  '$row1[2]' ) \" style=\"background-color:$row1[2];color:$row1[1];\">".substr($credit_text,0,72)."</a></li>";
else
$Credit=$Credit."<li ><a onMouseOver=\"javascript:updateCreditBorderColor($row1[0]  ,  '$row1[1]' ,  '$row1[2]' ) \" style=\"background-color:$row1[2];color:$row1[1];\">credit text</a></li>";

				$i++;
	   } 
	  ?>
  <td height="29">Credit text / border color </td>
  <td  ><dl class="dropdown">
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


<tr height="">
  <td height="28">No. of  ads </td>
  <td><?php echo "1";?></td>
  <td>&nbsp;</td>
</tr>
<tr height="">
  <td height="28">Ad orientation </td>
  <td><?php echo $orientation; ?></td>
  <td>&nbsp;</td>
</tr>


<tr height="50px" >
  <td>Ad title color </td>
  <td>
   <?php if($row['ad_type']!=6){?> 
  <input name="color1" type="hidden" ID="color1" class="colorwell"   value="<?php echo $row['tcolor']; ?>"  >N.A.
  <?php }else { ?>
  <div class="form-item">
  <input name="color1" type="text" ID="color1" class="colorwell"  style="background-color:<?php echo $row['tcolor']; ?>" value="<?php echo $row['tcolor']; ?>"  >
  <?php } ?>
  </div>
            &nbsp;</td>
 <td rowspan="4"><div id="picker"  ></div></td>
</tr>

<tr height="50px">
  <td>Ad description color </td>
 <td><div class="form-item"><input  type="text" id="color2" name="color2" class="colorwell"  value="<?php echo $row['dcolor']; ?>"  style="background-color:<?php echo $row['dcolor']; ?>"></div>
            &nbsp;</td>
</tr>
<tr height="50px">
  <td>Ad display url color </td>
<td><div class="form-item"><input  type="text" id="color3" name="color3" class="colorwell"  value="<?php echo $row['ucolor']; ?>" style="background-color:<?php echo $row['ucolor']; ?>"></div>
            &nbsp;</td>
</tr>
<tr height="50px">
  <td>Background color </td>
 <td><div class="form-item"><input ype="text" id="color4" name="color4" class="colorwell" value="<?php echo $row['bg_color']; ?>" style="background-color:<?php echo $row['bg_color']; ?>"></div>
            &nbsp;</td>
</tr>

<tr height="50px">
  <td>Include adult ads</td>
 <td> &nbsp;&nbsp;&nbsp;No&nbsp;<input type="radio" name="adult_status" value="0" id="adult_status_no"  <?php if($row['adult_status']==0) { ?> checked="checked" <?php } ?> > &nbsp;
                   Yes&nbsp;<input type="radio" name="adult_status" value="1" id="adult_status_yes"   <?php if($row['adult_status']==1) { ?> checked="checked" <?php } ?> > &nbsp;
                   &nbsp  </td>
</tr>

<tr height="">
  <td>&nbsp;</td>
  <td><input type="submit" name="Submit" value="Update &amp; Preview" /></td>
  <td>&nbsp;</td>
  
</tr>

</table>
</form>

</td>
</tr></table>
<?php
}
else
{
echo "<br><strong class=\"already\">Invalid id.</strong><br>";
}
 include("admin.footer.inc.php");

//$res=mysql_query("select * from ppc_credittext_bordercolor where id=$row['credit_color']");
//$r1=mysql_fetch_row($res);

$driname="admin";
 include("../inline-preview.php");





?>