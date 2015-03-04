<?php 

/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?><?php

$file="export_data";

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
<style type="text/css">
<!--
.style7 {color: #000000; font-size: 20px; }
.style8 {font-weight: bold}
.style9 {
	color: #000099;
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
<script language="javascript">
function currency_symb()
{
document.getElementById('paypal_curr').value=document.getElementById('curren_cy').value;

}
</script>
<p>
  <?php
$value1=$mysql->echo_one("select value from ppc_settings where name='ppc_engine_name'");
$value3=$mysql->echo_one("select value from ppc_settings where name='min_user_password_length'");
$client_language=$mysql->echo_one("select value from ppc_settings where name='client_language'");
?>
</p>
<form name="form1" method="post" action="ppc-setting-action.php">
<input type="hidden" name="install" value="1" />
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" scope="row"><br>        <strong>Database Setup Complete . </strong><br>        <br>
        You may <a href="main.php">Click Here</a> to go to your control panel now. <br>        
        <br> 
    <span class="style9">But we recommend you to configure the important settings now itself in the below form. </span></td>    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="42%"><span class="heading"> Basic Settings  </span></td>
      <td width="58%">&nbsp;</td>
    </tr>
    <tr align="left">
      <td colspan="2" class="inserted" scope="row">&nbsp;</td>
    </tr>
    
    <tr>
      <td height="22">Your Adserver name </td>
      <td><input name="ppc_engine_name" type="text" id="ppc_engine_name" size="40" value="<?php echo $value1; ?>"></td>
    </tr>
    <tr>
      <td height="22">System Default Language </td>
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
		  <?php $single_account_mode=$mysql->echo_one("select value from ppc_settings where name='single_account_mode'");?>
    <tr>
      <td height="22">Minimum user password length </td>
      <td><select name="min_user_password_length" id="min_user_password_length">
		  <?php for ($i=4;$i<=10;$i++)
		  {?>
          <option value="<?php echo $i;?>" <?php if($i==$value3) echo "selected"; ?>><?php echo $i;?></option>
		  
		  <?php }?>

      </select></td>
    </tr>
    <tr>
      <td height="22">Enable single-sign-on</td>
      <td><select name="single_account_mode" id="single_account_mode">
        <option value="1" <?php if($single_account_mode==1){echo "selected";}?>>Yes</option>
        <option value="0" <?php if($single_account_mode==0){echo "selected";}?>>No</option>
      </select></td>
    </tr>
<?php
$value1=$mysql->echo_one("select value from ppc_settings where name='admin_general_notification_email'");
$value2=$mysql->echo_one("select value from ppc_settings where name='paypal_email'");
$value3=$mysql->echo_one("select value from ppc_settings where name='payapl_payment_item_escription'");
$value4=$mysql->echo_one("select value from ppc_settings where name='admin_payment_notification_email'");
$system_currency=$mysql->echo_one("select value from ppc_settings where name='system_currency'");
$advertiser_paypalpayment=$mysql->echo_one("select value from ppc_settings where name='advertiser_paypalpayment'");
?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td  scope="row"><span class="heading"> Payment Settings </span></td>
      <td align="left" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"  scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td  >System currency </td><td>
      <select name="curren" id="curren_cy" onChange="currency_symb()">
      <?php
$currency=mysql_query("select * from ppc_currency where status=1 order by currency");
$code='';
while($currency1=mysql_fetch_row($currency))
{     
      ?>
     
		<option value="<?php echo $currency1[0]; ?>" <?php if($system_currency==$currency1[1]) { $code=$currency1[0]; ?>selected="selected" <?php } ?>><?php echo $currency1[1];  ?></option>
		
		<?php	}
		
		
	?>
</select><input type="hidden" name="paypal_curr" id="paypal_curr"  value="<?php echo $code;?>" />
    
    </td>
    </tr>
    <tr>
      <td  scope="row">Enable Paypal payment for advertisers</td>
      <td align="left" scope="row"><select name="advertiser_paypalpayment" id="advertiser_paypalpayment" onchange="activate_paypal_filelds()">
	  <option value="1"  <?php if($advertiser_paypalpayment==1)
		{echo "selected";}?>>Yes</option>
        <option value="0" <?php if($advertiser_paypalpayment==0)
		{echo "selected";}?>>No</option>
      </select></td>
    </tr>
    <tr>
      <td height="22">Admin paypal email address </td>
      <td><input name="paypal_email" type="text" id="paypal_email" size="30" value="<?php echo $value2; ?>" ></td>
    </tr>
    <tr>
      <td height="22">Description for paypal payment </td>
      <td><input name="payapl_payment_item_escription" type="text" id="payapl_payment_item_escription" size="30" value="<?php echo $value3; ?>" ></td>
    </tr>
    <tr>
      <td  scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td  scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td><span class="heading"> Email Settings </span></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td   colspan="2" scope="row">&nbsp;</td>
    </tr>
    
    <tr>
      <td height="22">Admin general notification email address </td>
      <td><input name="admin_general_notification_email" type="text" id="admin_general_notification_email" size="30" value="<?php echo $value1; ?>" ></td>
    </tr>
    <tr>
      <td height="22">Admin payment notification email</td>
      <td><input name="admin_payment_notification_email" type="text" id="admin_payment_notification_email" size="30" value="<?php echo $value4; ?>" ></td>
    </tr>
    <tr>
      <td  scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
    </tr>
   <tr>
      <td  scope="row">&nbsp;</td>
      <td align="left" scope="row"><input type="submit" name="Submit" value="Finish Installation and Go to Admin Area!"></td>
    </tr>
  </table>
</form>
<p>