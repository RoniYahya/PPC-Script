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

?>
<style type="text/css">
<!--
.style1 {
	color: #666666;
	font-weight: bold;
}
.style1 {color: white;
	font-weight: bold;}
-->
</style>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/languages.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Languages</td>
  </tr>
</table>

    <?php
    $pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;

$total=$mysql->echo_one("select count(*) from adserver_languages");
$totalpages=ceil($total/$perpagesize);
if($pageno>$totalpages)
$pageno=1;
$start=($pageno-1)*$perpagesize; 
	$result=mysql_query("select * from adserver_languages order by language ASC limit $start,$perpagesize");
	$no=mysql_num_rows($result);
	
	
  if($total>=1) {?> 
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
	  	<tr>
    <td colspan="2" >  Showing Languages <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
       &nbsp;&nbsp;    </td>
    <td width="50%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-manage-language.php" ); ?></td>
	  </tr>
</table>
<?php	}	
	
	if($no>0)
	{ 
	?>
  <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">

    <tr class="headrow">
      <td width="37%" align="left">Language<br>      </td>
      <td width="27%" align="left">Status<br>      </td>
      <td width="27%" align="left">Action<br>      </td>
    </tr>
    <?php
	    $i=1;
		  while($row=mysql_fetch_row($result))
          {
		  ?>
    <tr <?php if($i%2==1) { ?>class="specialrow" <?php } ?>>
      <td  align="left" style="border-bottom: 1px solid #b7b5b3;"><?php if( $row[3]==$client_language)  {echo $row[1];echo"(default)";} else {echo $row[1];}?></td>
      <td  align="left" style="border-bottom: 1px solid #b7b5b3;"><?php if($row[5]==1) echo "Active"; else echo "Inactive";?></td>
      <td  align="left" style="border-bottom: 1px solid #b7b5b3;"><?php if($row[3]=='en') echo "N.A";elseif( $row[3]!=$client_language)  { echo '<a href="ppc-edit-language.php?id='.$row[0].'">Edit</a>';?>&nbsp;&nbsp;&nbsp;<?php echo'<a href="ppc-delete-language.php?id='.$row[0].'">Delete</a>';?>&nbsp;&nbsp;&nbsp;<?php if($row[5]==1){echo  '<a href="ppc-block-language.php?id='.$row[0].'">Block</a>';}else{echo  '<a href="ppc-activate-language.php?id='.$row[0].'">Activate</a>';} } else{echo '<a href="ppc-edit-language.php?id='.$row[0].'">Edit</a>';}?></td>
    </tr>
    <?php $i++; 
		}
		?>
	</table>
	<?php	
		
		   }
		  else
		  {   echo "<br>No Records Found<br><br>"; 
		  }

?>
  
		   
  <?php if($total>=1) {?> 
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
	  	<tr>
    <td colspan="2" >  Showing Languages <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
       &nbsp;&nbsp;    </td>
    <td width="50%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-manage-language.php" ); ?></td>
	  </tr>
</table>
<?php	} ?>

<?php include("admin.footer.inc.php"); ?>