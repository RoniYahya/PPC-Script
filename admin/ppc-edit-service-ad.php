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



?><?php include("admin.header.inc.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/service-ads.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Edit Public Service Ad</td>
  </tr>
</table>
<style type="text/css">
<!--
.style2 {color: #FF0000}
.style3 {font-size: 18px}
-->
</style><?php

$url1=urldecode($_GET['url1']);
$url1=urlencode($url1);
$id=$_GET['id'];
phpsafe($id);
$result1=mysql_query("select bannersize,link,name,adtype,wapstatus from ppc_public_service_ads where id=$id ");
$row1=mysql_fetch_array($result1);



$adtype=$row1['adtype'];


//////////////////////////wap
$wap_flag=$row1['wapstatus'];

if($wap_flag==1)
{
	$name='wap';
	$wap_string='and wapstatus=1';
	$table='wap_ad_block';
	$wap_string2='where wapstatus=1'; 
		$wap_status=" wap_status=1";
	$title_length=	$GLOBALS['wap_title_length'];
    $desc_length=$GLOBALS['wap_desc_length'];
    $displayurl=$GLOBALS['wap_url_length'];
}
else
{
	$wap_flag=0;
	$name='';
	$wap_string='and wapstatus=0';
	$table='ppc_ad_block';
	$wap_string2='where wapstatus=0'; 
	$wap_status="wap_status=0";
	$title_length=$ad_title_maxlength;
    $desc_length=$ad_description_maxlength;
    $displayurl=$ad_displayurl_maxlength;
}

//////////////////////////wap


if($adtype==1)

{
?>
<form action="ppc-edit-public-service-ad-action.php?adtype=<?php echo $adtype; ?>&id=<?php echo $id; ?>&wap=<?php echo $wap_flag; ?>" method="post" enctype="multipart/form-data" name="form1">
<input type="hidden" name="url1" value="<?php echo $url1; ?>" />
  <table width="100%"  border="0"  cellpadding="0" cellspacing="0">
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr >
      <td height="23" colspan="2"><span class="inserted">Please configure your  public service banner ad settings below</span></td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><span class="style5"><strong>All fields marked <span class="style2"><strong>*</strong></span> are compulsory</strong></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
     <tr>
      <td>Name</td>
      <td><input type="text" name="txt" id="txt" size="50" value="<?php echo $row1[2]; ?>"> <span class="style2"><strong>*</strong></span></td>
    </tr>
	
    <tr>
      <td width="22%">&nbsp;</td>
      <td width="78%">&nbsp;</td>
    </tr>
     <tr> <td >Target Language</td>

<td><select name="language" id="language" >

<?php 
$result=mysql_query("select adlang from ppc_public_service_ads where id='$id'");
$df=mysql_fetch_row($result);
$res=mysql_query("select id,language,code from adserver_languages  where status='1'");

while($row=mysql_fetch_row($res))
	{
		if($df[0]==$row[0])
		{
		echo "<option value=\"$row[0]\" selected>$row[1] </option>";	
		}
		else
		{
			echo "<option value=\"$row[0]\" >$row[1] </option>";
		}
	}
	?><option value="0" <?php if($df[0]==0) echo "selected"; ?>>Any Languages</option>
</select><span class="style2"><strong>*</strong></span></td>
</tr>
<tr> <td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
    <tr>
      <td>Select Banner Size </td>
      <td>
	   <?php
        $result=mysql_query("select id,width,height,file_size from banner_dimension where $wap_status order by id");
       
		
$ini_error_status=1;//ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}

?><select name="size" id="select2"><?php

	while($row=mysql_fetch_row($result))
	{
		if($row1[0]==$row[0])
		echo "<option value=\"$row[0]\" selected >( $row[1] x $row[2] ) Max size:$row[3]</option>";
		else
		echo "<option value=\"$row[0]\"> ( $row[1] x $row[2] ) Max size:$row[3]</option>";
	}
//$selectstring.="</select>";
  
?>
      </select>        <span class="style2"><strong>*</strong></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Select Banner </td>
      <td><input name="banner" type="file" id="banner2" size="50" value="<?php echo $row1[3]; ?>">
          <strong><span class="style2"><strong>*</strong></span></td>
    </tr>
	<tr><td></td><td>
<?php if($wap_flag==1)
{
?>
(GIF/JPG/PNG images)
<?php
}else {
?>
(GIF/JPG/PNG/SWF images)
<?php } ?>

</td></tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Banner Target URL </td>
      <td><input name="url" type="text" id="url2" size="50" value="<?php echo $row1[1]; ?>">
        <span class="style2"><strong>*</strong></span> <br />
        (Eg.http://www.yoursite.com)</td>
    </tr>
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Edit Existing Public Banner Ad ! "></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<?php
}
else if($adtype==2)
{
$result1=mysql_query("select * from ppc_public_service_ads where id=$id $wap_string");
$row1=mysql_fetch_row($result1);
?>
<form action="ppc-edit-public-service-ad-action.php?adtype=<?php echo $adtype; ?>&id=<?php echo $id; ?>&wap=<?php echo $wap_flag ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="return check_value()">
  <table width="100%"  border="0"  cellpadding="0" cellspacing="0">
      
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr >
      <td colspan="2"><span class="inserted">Please configure your new public service catalog ad settings below</span></td>
    </tr>
    <tr >
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><span class="style5"><strong>All fields marked <span class="style2">*</span> are compulsory</strong></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
     <tr>
      <td>Name</td>
      <td><input type="text" name="txt" id="txt" size="50" value="<?php echo $row1[12]; ?>"> <span class="style2"><strong>*</strong></span></td>
    </tr>
    <tr>
      <td width="22%">&nbsp;</td>
      <td width="78%">&nbsp;</td>
    </tr>
     <tr> <td >Target Language</td>

<td><select name="language" id="language" >

<?php 

$result=mysql_query("select adlang from ppc_public_service_ads  where id='$id'");
$df=mysql_fetch_row($result);
$res=mysql_query("select id,language,code from adserver_languages  where status='1'");

while($row=mysql_fetch_row($res))
	{
		if($df[0]==$row[0])
		{
		echo "<option value=\"$row[0]\" selected>$row[1] </option>";	
		}
		else
		{
			echo "<option value=\"$row[0]\" >$row[1] </option>";
		}
	}
	?><option  value="0" <?php if($df[0]==0) echo "selected"; ?>>Any Languages</option>
</select><span class="style2"><strong>*</strong></span></td>
</tr>
<tr> <td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
    <tr>
      <td>Select Catalog Size </td>
      <td><select name="size" id="select2">
        <option selected value="">- select -</option>
        <?php $result=mysql_query("select id,width,height,filesize from catalog_dimension $wap_string2 order by id");
       
		
$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
}

	while($row=mysql_fetch_row($result))
	{
		if($row[0]==$row1[5])
		echo "<option value=\"$row[0]\" selected> $row[1] x $row[2] - Maxsize: $row[3] KB</option>";
		else
		echo "<option value=\"$row[0]\" > $row[1] x $row[2] - Maxsize: $row[3] KB</option>";
		//echo "<option value=\"$row[0]\"> $row[1] x $row[2] Max size:$row[3] KB</option>";
	}
//$selectstring.="</select>";
  
?>
      </select>        <span class="style2"><strong>*</strong></span></td>
    </tr>
	    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
   
    <tr>
      <td>Title</td>
      <td><input name="displayurl" type="text" id="displayurl" size="50" value="<?php echo $row1[8]; ?>" maxlength="<?php echo $displayurl; ?>">        
<span class="style2"><strong>*</strong></span>[Max. <?php echo $displayurl;?> Characters]</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Select Banner </td>
      <td><input name="banner" type="file" id="banner" size="50">        <span class="style1"><span class="style2"><strong>*</strong></span></span><br />
	  
<?php if($wap_flag==1)
{
?>
(GIF/JPG/PNG images)
<?php
}else {
?>
(GIF/JPG/PNG/SWF images)
<?php } ?>  
	  
	  
	  



</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Description</td>
      <td><input name="summary" type="text" id="summary" size="50" value="<?php echo $row1[3]; ?>" maxlength="<?php echo $desc_length; ?>">        
<span class="style2"><strong>*</strong></span>[Max. <?php echo $desc_length;?> Characters]</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Target URL </td>
      <td>
<input name="url" type="text" id="url" size="50" value="<?php echo $row1[1]; ?>">        
<span class="style2"><strong>*</strong></span><br />
(Eg.http://www.yoursite.com)</td>
    </tr>
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Edit Existing Public Banner Ad ! "></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>

<?php
}
else
{
$result1=mysql_query("select * from ppc_public_service_ads where id=$id");
$row1=mysql_fetch_row($result1);

?>

<form name="form1" method="post" action="ppc-edit-public-service-ad-action.php?adtype=<?php echo $adtype; ?>&id=<?php echo $id; ?>&wap=<?php echo $wap_flag; ?>">
<input type="hidden" name="url1" value="<?php echo $url1; ?>" />
  <table width="100%"  border="0"  cellpadding="0" cellspacing="0">
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="26" colspan="2"  class="inserted">Please configure your  public service text ad settings below</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td height="23" colspan="2" align="left"><span class="style5"><strong>All fields marked <span class="style2"><strong>*</strong></span> are compulsory</strong></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
     <tr>
      <td>Name</td>
      <td><input type="text" name="txt" id="txt" size="50" value="<?php echo $row1[12]; ?>"> <span class="style2"><strong>*</strong></span></td>
    </tr>
    <tr>
      <td width="21%">&nbsp;</td>
      <td width="79%">&nbsp;</td>
    </tr>
    <tr> <td >Target Language</td>

<td><select name="language" id="language" >

<?php 

$result=mysql_query("select adlang from ppc_public_service_ads  where id='$id'");
$df=mysql_fetch_row($result);
$res=mysql_query("select id,language,code from adserver_languages  where  status='1'");

while($row=mysql_fetch_row($res))
	{
		if($df[0]==$row[0])
		{
		echo "<option value=\"$row[0]\" selected >$row[1] </option>";	
		}
		else
		{
			echo "<option value=\"$row[0]\" >$row[1] </option>";
		}
	}
	?>
	<option value="0" <?php if($df[0]=="0") echo "selected"; ?>>Any Languages</option>
</select><span class="style2"><strong>*</strong></span></td>
</tr>
<tr> <td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
    <tr>
      <td>Ad Title </td>
      <td><input name="title" type="text" id="title2" value="<?php echo $row1[2]; ?>" size="30" maxlength="<?php echo $title_length;?>">
          <span class="style1 style10 style2"> <span class="style5"><strong><span class="style2"><strong>*</strong></span></strong></span></span> [Max. <?php echo $title_length;?> Characters]</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>Ad Summary </td>
      <td><input name="summary" type="text" id="summary2" value="<?php echo $row1[3]; ?>" size="50" maxlength="<?php echo $desc_length; ?>">
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style2"><strong>*</strong></span></strong></span></strong></span> [Max. <?php echo $desc_length;?> Characters]</td>
    </tr>
    <tr>
      <td valign="middle">&nbsp;</td>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td>Target URL</td>
      <td><input name="url" type="text" id="url2" size="50" value="<?php echo $row1[1]; ?>">
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style2"><strong>*</strong></span></strong></span></strong></span><br />
(Eg.http://www.yoursite.com)</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>Display URL</td>
      <td><input name="disp" type="text" id="url2" value="<?php echo $row1[8]; ?>" size="50" maxlength="<?php echo $displayurl; ?>">
          <span class="style1 style10 style2"><strong> <span class="style5"><strong><span class="style2"><strong>*</strong></span></strong></span></strong></span> [Max. <?php echo $displayurl;?> Characters]</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Edit Existing Public Text Ad ! "></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<?php
}
?>
<?php include("admin.footer.inc.php"); ?>

