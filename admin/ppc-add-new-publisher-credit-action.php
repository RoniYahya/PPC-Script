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
include("../extended-config.inc.php");  
includeClass("ImageResizer");
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
  <br />

<?php

$credittype=$_POST['credittype'];




if($credittype==1)
{
$credit="";
$data=$mysql->echo_one("select id from adserver_languages where code='en'");

if(isset($_POST['credit_'.$data]))
$credit=$_POST['credit_'.$data];
phpSafe($credit);



if($credit =="")
{
$msg="Please fill in all fields  and click 'Add' button."." <a href=\"javascript:history.back(-1);\">Go Back</a>";
?>
<span class="already"><?php echo $msg;?> </span>
<?php
include("admin.footer.inc.php"); 
exit;

}
if($mysql->total("ppc_publisher_credits","credit='$credit' and language_id='0' and parent_id='0' and credittype ='0'")>0)
    {

   $msg="<strong>$credit</strong> already exists ! "." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
   <span class="already"><?php echo $msg;?> </span> 
   <?php
    include("admin.footer.inc.php"); 
    exit;
   

	
	}

if(mysql_query("insert into ppc_publisher_credits values('0','$credit','0','0','0')"))
	{
	$last_id=$mysql->select_last_id();


$res_new=mysql_query("select * from adserver_languages where status='1' and code<>'en' order by language asc");
	


	
	$msg_success="";
	$msg_failed="";
	

 while($row=mysql_fetch_row($res_new))
		{
			
		$credit_sub=$_POST['credit_'.$row[0]];
        phpSafe($credit_sub);
	    if($credit_sub !="")
		{
		
	    if(mysql_query("insert into ppc_publisher_credits values('0','$credit_sub','$row[0]','$last_id','0')"))
	    $msg_success.=$row[1].",";
        else
		$msg_failed.=$row[1].",";
    
		
		}
		
		
        }




	$msg_success=substr($msg_success,0,-1);
	$msg_failed=substr($msg_failed,0,-1);
	

if($msg_success != "")
	{
	$st=",";
	$st1="s";
	}
	else
	{
	$st="";
	$st1="";
	}
	
	
	
    if($msg_failed != "")
	$msg_failed="Error occurred  in ".$msg_failed." language credit text insertion!";
	
	
    $msg.="New credit text has been added successfully for english".$st.$msg_success." language".$st1."! <a href=\"ppc-manage-publisher-credit.php\">Continue</a>";
	
	?>
    <span class="inserted"><?php echo $msg;?> </span><br /><br />
	<span class="inserted"><?php echo $msg_failed; ?> </span><br /><br />
	
	
	
	
	 <?php
	include("admin.footer.inc.php"); 
    exit;
	
	
}
else
{
	$msg="Error occurred!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
	<span class="already"><?php echo $msg;?> </span> 
    <?php 
    include("admin.footer.inc.php"); 
    exit;
	



}

	


}
else if($credittype==2)
{
$filesize=100;

$data=$mysql->echo_one("select id from adserver_languages where code='en'");

$image_credit=$_FILES['image_credit_'.$data]['name'];
phpSafe($image_credit);

if($image_credit =="")
{
$msg="Please select the image for english credit text and click 'Add' button."." <a href=\"javascript:history.back(-1);\">Go Back</a>";
?>
<span class="already"><?php echo $msg;?> </span>
<?php
include("admin.footer.inc.php"); 
exit;

}

if($image_credit !="")
{



$exten=strtolower(substr($image_credit,-4));

	if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png")
	{
	
	$msg="You can only upload jpg ,jpeg , gif , png images !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
	<span class="already"><?php echo $msg;?> </span> 
<?php
    include("admin.footer.inc.php"); 
    exit;
    
	}
	




if((($_FILES['image_credit_'.$data]['size'])/1024) > $filesize )
		{
		
		
		$msg="The uploaded image size grater than 100KB !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
	    <span class="already"><?php echo $msg;?> </span> 
<?php 
         include("admin.footer.inc.php"); 
         exit;
		}






/*
if($mysql->total("ppc_publisher_credits","credit='".$data."-".$image_credit."' and credittype='1'")>0)
    {

   $msg.="Image Credit already exists !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
   <span class="already"><?php echo $msg;?> </span> 
   <?php
    include("admin.footer.inc.php"); 
    exit;
   

	
	}
*/
	//else
	//{
	
	if(mysql_query("insert into ppc_publisher_credits values('0','".$data."-".$image_credit."','0','0','1')"))
	{
	$last_id=$mysql->select_last_id();
	
	mkdir("../credit-image/".$last_id."/");
	if(copy($_FILES['image_credit_'.$data]['tmp_name'],"../credit-image/".$last_id."/".$data."-".$image_credit))
	{
			$size=getimagesize("../credit-image/".$last_id."/".$data."-".$image_credit);
			
			
	         //  image resizing
  			$rimg = new ImageResizer("../credit-image/".$last_id."/".$data.'-'.$image_credit);
			$rimg->resize($size[0],15,"../credit-image/".$last_id."/".$data.'-'.$image_credit);
	
	
	
	$res_new=mysql_query("select * from adserver_languages where status='1' and code<>'en' order by language asc");
	
	$msg_extension="";
	$msg_size="";
	$msg_already="";
	$msg_uplode="";
	$msg_success="";
	$msg_failed="";
	
	 while($row=mysql_fetch_row($res_new))
			{
			
		$image_credit_sub=$_FILES['image_credit_'.$row[0]]['name'];
        phpSafe($image_credit_sub);
	    if($image_credit_sub !="")
		{
		
		
		
	$exten=strtolower(substr($image_credit_sub,-4));

	if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png")
	$msg_extension.=$row[1].",";
	else if((($_FILES['image_credit_'.$row[0]]['size'])/1024) > $filesize )
    $msg_size.=$row[1].",";
    //else if($mysql->total("ppc_publisher_credits","credit='".$row[0]."-".$image_credit_sub."' and credittype='1'")>0)
    //$msg_already.=$row[1].",";
    else
	{
	
	
	
	
	
	if(mysql_query("insert into ppc_publisher_credits values('0','".$row[0]."-".$image_credit_sub."','$row[0]','$last_id','1')"))
	{
	$last_id_sub=$mysql->select_last_id();
	
	
	if(copy($_FILES['image_credit_'.$row[0]]['tmp_name'],"../credit-image/".$last_id."/".$row[0]."-".$image_credit_sub))
	{
			$size=getimagesize("../credit-image/".$last_id."/".$row[0]."-".$image_credit_sub);
			
			
	         //  image resizing
  			$rimg = new ImageResizer("../credit-image/".$last_id."/".$row[0].'-'.$image_credit_sub);
			$rimg->resize($size[0],15,"../credit-image/".$last_id."/".$row[0].'-'.$image_credit_sub);
	
	
	$msg_success.=$row[1].",";
	
	
	}
	else
	{
	
	
	mysql_query("delete from ppc_publisher_credits where id='$last_id_sub'");
	$msg_uplode.=$row[1].",";
	
	
	
	}
	
	}
	else
	{
	
	$msg_failed.=$row[1].",";
	
	}
	
	
	
	}
	
	
	
	





	








 
		
		
		
		
		
		}
			
			
			
			
			
			
			
			
			
			
			}
	
	
	
	
	
	
	
	
	$msg_extension=substr($msg_extension,0,-1);
	$msg_size=substr($msg_size,0,-1);
	//$msg_already=substr($msg_already,0,-1);
	$msg_uplode=substr($msg_uplode,0,-1);
	$msg_success=substr($msg_success,0,-1);
	$msg_failed=substr($msg_failed,0,-1);
	
	
	
	if($msg_success != "")
	{
	$st=",";
	$st1="s";
	}
	else
	{
	$st="";
	$st1="";
	}
	
	if($msg_extension != "")
	$msg_extension="Please upload jpg ,jpeg , gif , png images for ".$msg_extension." languages!";
	if($msg_size != "")
	$msg_size="Please upload size less than or equal to 100KB images for ".$msg_size." languages!";
	//if($msg_already != "")
	//$msg_already="Same Image Credits already exists for ".$msg_already." languages!";
	if($msg_uplode != "")
	$msg_uplode="Image Uploading Failed for ".$msg_uplode." languages!";
	if($msg_failed != "")
	$msg_failed="Error occurred  in ".$msg_failed." language credit text insertion!";
	
	
    $msg.="New image credit text has been added successfully for english".$st.$msg_success." language".$st1."! <a href=\"ppc-manage-publisher-credit.php\">Continue</a>";
	
	?>
    <span class="inserted"><?php echo $msg;?> </span><br /><br />
	<span class="inserted"><?php echo $msg_extension; ?> </span><br /><br />
	<span class="inserted"><?php echo $msg_size; ?> </span><br /><br />
	<!--<span class="inserted"><?php //echo $msg_already; ?> </span><br /><br />-->
	<span class="inserted"><?php echo $msg_uplode; ?> </span><br /><br />
	<span class="inserted"><?php echo $msg_failed; ?> </span><br /><br />
	
	
	
	
	 <?php
	include("admin.footer.inc.php"); 
    exit;
	
	}
	else
	{
	mysql_query("delete from ppc_publisher_credits where id='$last_id'");
	$msg.="Image Uploading Failed!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
	

    <span class="inserted"><?php echo $msg;?> </span> <?php
	include("admin.footer.inc.php"); 
    exit;
	
	}
	
	
	
	
	
	
	
	
	
	}
	else
	{
	
	
		$msg="Error occurred!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
	    <span class="already"><?php echo $msg;?> </span> 
        <?php 
        include("admin.footer.inc.php"); 
        exit;
	
	
	
	}
	
	//}



}







}







 include("admin.footer.inc.php"); ?>