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

$msg="";
$id=$_POST['parent'];
phpsafe($id);
$msg_success="";
$msg_failed="";


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
if($mysql->total("ppc_publisher_credits","credit='$credit' and language_id='0' and parent_id='0' and credittype ='0' and id<>'$id' ")>0)
    {

   $msg="<strong>$credit</strong> already exists ! "." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
   <span class="already"><?php echo $msg;?> </span> 
   <?php
    include("admin.footer.inc.php"); 
    exit;
   

	
	}


$lang=mysql_query("select * from adserver_languages where status='1' order by language asc");

while($lang_row=mysql_fetch_row($lang))
{


$data=$lang_row[0];
$credit=$_POST['credit_'.$data];
phpSafe($credit);



if($credit !="")
{

$query="";
if($lang_row[3]=='en')
$query="id='$id'";
else
$query="parent_id='$id' and language_id='$lang_row[0]'";


$count=$mysql->echo_one("select count(*) from ppc_publisher_credits where ".$query."");

	if($count > 0)
	{
		
	if(mysql_query("update ppc_publisher_credits set credit='$credit' where ".$query.""))
	$msg_success.=$lang_row[1].",";
	else
	$msg_failed.=$lang_row[1].",";

	}
	else
	{
	
	
	if(mysql_query("insert into ppc_publisher_credits values('','$credit','$lang_row[0]','$id','0')"))
	$msg_success.=$lang_row[1].",";
	else
	$msg_failed.=$lang_row[1].",";
 
	
	
	
	
	}
	


}


}
 
	
	
	$msg_success=substr($msg_success,0,-1);
	$msg_failed=substr($msg_failed,0,-1);
	
	
	$msg_new="";


    if($msg_failed != "")
	$msg_failed="Error occurred  in ".$msg_failed." language credit text insertion!";
	if($msg_success !="")
    $msg="New credit text has been added successfully for ".$msg_success." languages! <a href=\"ppc-manage-publisher-credit.php\">Continue</a>";
	else
	$msg_new="<a href=\"javascript:history.back(-1);\">Go Back</a>";


?>
    <span class="inserted"><?php echo $msg;?> </span><br /><br />
	<span class="inserted"><?php echo $msg_failed; ?> </span><br /><br />
	<span class="inserted"><?php echo $msg_new;?> </span><br /><br />
	
	
	
	 <?php
	include("admin.footer.inc.php"); 
    exit;












}
else if($credittype==2)
{
$msg="";
$msg_extension="";
$msg_size="";
$msg_uplode="";
$msg_success="";
$msg_failed="";



$id=$_POST['id'];
phpsafe($id);

$filesize=100;
//$data=$mysql->echo_one("select id from adserver_languages where code='en'");

$lang=mysql_query("select * from adserver_languages where status='1' order by language asc");

while($lang_row=mysql_fetch_row($lang))
{


$data=$lang_row[0];
$image_credit=$_FILES['image_credit_'.$data]['name'];
phpSafe($image_credit);






if($image_credit !="")
{


	
$query="";
if($lang_row[3]=='en')
$query="id='$id'";
else
$query="parent_id='$id' and language_id='$lang_row[0]'";




$exten=strtolower(substr($image_credit,-4));
if($exten!=".jpg" && $exten!="jpeg" && $exten!=".gif" && $exten!=".png")
$msg_extension.=$lang_row[1].",";
else if((($_FILES['image_credit_'.$data]['size'])/1024) > $filesize )
$msg_size.=$lang_row[1].",";
else
{	
	
	if(copy($_FILES['image_credit_'.$data]['tmp_name'],"../credit-image/".$id."/".$data."-".$image_credit))
	{
			$size=getimagesize("../credit-image/".$id."/".$data."-".$image_credit);
	        //  image resizing
  			$rimg = new ImageResizer("../credit-image/".$id."/".$data.'-'.$image_credit);
			$rimg->resize($size[0],15,"../credit-image/".$id."/".$data.'-'.$image_credit);
	
	
	
	
	$count=$mysql->echo_one("select count(*) from ppc_publisher_credits where ".$query."");
	
	if($count > 0)
	{
	
	$old_name=$mysql->echo_one("select credit from ppc_publisher_credits where ".$query."");
	if(mysql_query("update ppc_publisher_credits set credit='".$data."-".$image_credit."' where ".$query.""))
	{
	unlink("../credit-image/".$id."/".$old_name); 
	$msg_success.=$lang_row[1].",";
	}
	else
	{
	unlink("../credit-image/".$id."/".$data."-".$image_credit); 
	$msg_failed.=$lang_row[1].",";
    }
	}
	else
	{
	
	
	if(mysql_query("insert into ppc_publisher_credits values('','".$data."-".$image_credit."','$lang_row[0]','$id','1')"))
	{
	$msg_success.=$lang_row[1].",";
	}
	else
	{
	unlink("../credit-image/".$id."/".$data."-".$image_credit); 
	$msg_failed.=$lang_row[1].",";
    }
	
	
	
	
	
	}
	
	
	
	
	
	
	}
	else
	$msg_failed.=$lang_row[1].",";
	


 
	

	
	
	
}	
	
	

}
//else
//$msg_success.=$lang_row[1].",";
	



 









}

    $msg_extension=substr($msg_extension,0,-1);
	$msg_size=substr($msg_size,0,-1);
	$msg_uplode=substr($msg_uplode,0,-1);
	$msg_success=substr($msg_success,0,-1);
	$msg_failed=substr($msg_failed,0,-1);
	
	
	$msg_new="";

	
	
	if($msg_extension != "")
	$msg_extension="Please upload jpg ,jpeg , gif , png images for ".$msg_extension." languages!";
	if($msg_size != "")
	$msg_size="Please upload size less than or equal to 100KB images for ".$msg_size." languages!";
	if($msg_uplode != "")
	$msg_uplode="Image Uploading Failed for ".$msg_uplode." languages!";
	if($msg_failed != "")
	$msg_failed="Error occurred  in ".$msg_failed." language credit text insertion!";
	if($msg_success !="")
    $msg="New image credit text has been added successfully for ".$msg_success." languages! <a href=\"ppc-manage-publisher-credit.php\">Continue</a>";
	else
	$msg_new="<a href=\"javascript:history.back(-1);\">Go Back</a>";
	
	
	
	?>
    <span class="inserted"><?php echo $msg;?> </span><br /><br />
	<span class="inserted"><?php echo $msg_extension; ?> </span><br /><br />
	<span class="inserted"><?php echo $msg_size; ?> </span><br /><br />
	<span class="inserted"><?php echo $msg_uplode; ?> </span><br /><br />
	<span class="inserted"><?php echo $msg_failed; ?> </span><br /><br />
	<span class="inserted"><?php echo $msg_new;?> </span><br /><br />
	
	
	
	 <?php
	include("admin.footer.inc.php"); 
    exit;



}
?>		
		
		
		
		
		
		
		
			
<br>
<?php include("admin.footer.inc.php"); ?>