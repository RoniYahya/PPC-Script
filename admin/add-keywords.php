<?php
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
	
	

	
	
	
	$search_keywords=trim($_POST['key']);
//phpSafe($search_keywords);
//echo $search_keywords;


	if($_POST['key'])
	{
		
	if($search_keywords=="")
	{
		include("admin.header.inc.php");?>
		
		<span class="already"><br><?php echo "Please go back and check whether you filled all mandatory fields !";?>         <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
		
		<?php include("admin.footer.inc.php");
		exit;
		
	}
	///echo $maxcv1."<".$min_click_value;
	
	
	$msg="";
	$ignore_list=$ignoreList;
$a=str_replace("\r\n",",", $search_keywords);
$b=str_replace("\r",",", $a);
$c=str_replace("\n",",", $b);
//$d=str_replace(", "," ", $c);
//$e=str_replace(","."\n"," ", $d);
	$bb=explode(",",$c);
	$num=count($bb);
	//print_r($bb);
	
	for($i=0;$i<$num;$i++)
	{			
	$bb[$i]=trim($bb[$i]);	
				if(substr_count($ignore_list," ".$bb[$i]." ")==0)
									{
								if($bb[$i]!="")
								{

									phpsafe($bb[$i]);
								
									  $systotal=$mysql->echo_one("select id from system_keywords where keyword='$bb[$i]' limit 0,1");
									
							if($systotal=="")
							{
									mysql_query("insert into system_keywords values('0','$bb[$i]','".time()."',0,'1');");

									  
							}
							
							
								}
							}
						
	}
	header("location: system-keywords.php");
							exit;				
	}			
?><?php include("admin.header.inc.php"); 
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/keywords.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Add New Keywords</td>
  </tr>
</table>


<form name="form1" method="post" action="add-keywords.php">

  <table width="100%"  border="0" cellpadding="0" cellspacing="0">

    <tr >
      <td height="19" colspan="5"  scope="row">&nbsp;</td>
    </tr>
    <tr >
      <td height="30" colspan="5"  scope="row"><span class="inserted">Please fill up the following field and Press Add Button </span></td>
    </tr>
      <tr>
      <td colspan="5"  scope="row"><span class="style2">All Fields marked <span class="style3">*</span> are mandatory </span></td>
    </tr>
    <tr>
      <td width="15%" scope="row">&nbsp;</td>
      <td width="15%">&nbsp;</td>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td colspan="3" align="left" valign="top" scope="row">Keyword <span class="style3">*</span><br />
<br />

        <textarea name="key" id="key" rows="10" cols="60" ><?php echo $search_keywords; ?> </textarea><br />

        Enter keywords separated by comma or newline.<br />
        <br />
        <input type="submit" name="Submit" value="Add Keyword!" onblur="showtab()" >      </td>
      <td colspan="2"></td>
    </tr>
    
   
    
    
    
   
	<tr>
	  <td colspan="5" scope="row">&nbsp;</td>
    </tr>
    <tr height="40">
	  <td  colspan="5"    scope="row" class="note"><strong>Note: </strong>Already existing keywords and keywords in ignore list would not be added.</td>
    </tr>
  </table>
</form>

<?php include("admin.footer.inc.php"); ?>