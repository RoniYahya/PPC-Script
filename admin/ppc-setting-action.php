<?php

/*--------------------------------------------------+
 |													 |
 | Copyright � 2006 http://www.inoutscripts.com/  
 | All Rights Reserved.								 |
 | Email: contact@inoutscripts.com                    |
 |                                                    |
 +---------------------------------------------------*/
/*<?php
//if(isset($_POST['paypal_email']))
//{
//	$name12=safeRead('paypal_email');//$_POST['ppc_engine_name'];
//	if($name12=="")
//	{?>
//<span class="already"><br>
//	<?php echo "Invalid data. Cannot update Paypal email address.";?></span>
//<p></p>
//	<?php
//	}
//	else
//	{
//		mysql_query("update ppc_settings set value='$name12' where name='paypal_email'");
//		?>
//<span class="inserted"><br>
//		<?php echo "Paypal email  has been successfully updated . ";?></span>
//<p></p>
//		<?php
//	}
//}
//?>
//<?php
//if(isset($_POST['payapl_payment_item_escription']))
//{
//	$name13=safeRead('payapl_payment_item_escription');//$_POST['ppc_engine_name'];
//	if($name13=="")
//	{?>
//<span class="already"><br>
//	<?php echo "Invalid data. Cannot update Paypal payment description.";?></span>
//<p></p>
//	<?php
//	}
//	else
//	{
//		mysql_query("update ppc_settings set value='$name13' where name='payapl_payment_item_escription'");
//		?>
//<span class="inserted"><br>
//		<?php echo "Paypal payment description has been successfully updated . ";?></span>
//<p></p>
//		<?php
//	}
//}
//?>*/


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


$ini_error_status=ini_get('error_reporting');

include_once("admin.header.inc.php");?>
<?php
if(isset($_POST['ppc_engine_name']))
{
	$name=safeRead('ppc_engine_name');//$_POST['ppc_engine_name'];
	if($name=="")
	{?>

<span class="already"><br>
	<?php echo "Invalid data. Cannot update your Adserver name.";?></span>
<p></p>
	<?php
	}
	else
	{//echo "hii";
		mysql_query("update ppc_settings set value='$name' where name='ppc_engine_name'");

		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
		?>
<span class="inserted"><br>
		<?php echo "Your Adserver name has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>


<?php
if(isset($_POST['lan']))
{
	$lang=safeRead('lan');//$_POST['ppc_engine_name'];
	if($lang=="")
	{?>

<span class="already"><br>
	<?php echo "Invalid data. Cannot update default language.";?></span>
<p></p>
	<?php
	}
	else
	{//echo "hii";
		mysql_query("update ppc_settings set value='$lang' where name='client_language'");

		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
		?>
<span class="inserted"><br>
		<?php echo "Language has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>


<?php
if(isset($_POST['adv_status']))
{
	$adv_status=safeRead('adv_status');//

	mysql_query("update ppc_settings set value='$adv_status' where name='adv_status'");
	?>
<span class="inserted"><br>
	<?php echo " Default advertiser status value  has been successfully updated . ";?></span>
<p></p>
	<?php

}
?>
<?php $pub_status=$GLOBALS['pub_status'];?>

<?php
if(isset($_POST['pub_status']))
{
	$pub_status=safeRead('pub_status');//

	mysql_query("update ppc_settings set value='$pub_status' where name='pub_status'");
	?>
<span class="inserted"><br>
	<?php echo " Default publisher status value  has been successfully updated . ";?></span>
<p></p>
	<?php

}
?>


<?php
if(isset($_POST['ad_display_char_encoding']))
{
	$name19=safeRead('ad_display_char_encoding');//$_POST['ppc_engine_name'];
	if($script_mode=="demo")
	{
		?>
<span class="already"><br>
		<?php echo "Cannot update character encoding  in demo.";?></span>
<p></p>
		<?php
	}
	else if($name19=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update character encoding.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name19' where name='ad_display_char_encoding'");
		?>
<span class="inserted"><br>
		<?php echo "Character encoding has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>


<?php
if(isset($_POST['min_user_password_length']))
{
	$name2=safeRead('min_user_password_length');//$_POST['ppc_engine_name'];
	if($name2=="" || $name2<=0 )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update minimum user password length.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name2' where name='min_user_password_length'");
		?>
<span class="inserted"><br>
		<?php echo " Minimum user password length has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php
if(isset($_POST['single_account_mode']))
{
	$name2=safeRead('single_account_mode');//$_POST['ppc_engine_name'];
	if($name2=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update single-sign-on mode.";?></span>
<p></p>
	<?php
	}
elseif($script_mode=="demo")
	{?>
<span class="already"><br>
	<?php echo "Cannot update single-sign-on mode in demo.";?></span>
<p></p>
	<?php
	}
	else
	{
		if($name2==1)
		{
			$users=$mysql->echo_one("select uid from ppc_users where common_account_id='0'");
			$publi=$mysql->echo_one("select uid from ppc_publishers where common_account_id='0'");
			if(($users!="")||($publi!=""))
			mysql_query("update ppc_settings set value='1' where name='account_migration'");
			else
			mysql_query("update ppc_settings set value='0' where name='account_migration'");
		}
		else
		{
				mysql_query("update ppc_settings set value='0' where name='account_migration'");
		}			
		mysql_query("update ppc_settings set value='$name2' where name='single_account_mode'");
		
		
	
		
		?>
<span class="inserted"><br>
		<?php echo " Single-sign-on mode has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>




<?php

if(isset($_POST['site_targeting']))
{
	$site_targeting=safeRead('site_targeting');//
//echo  $time_date_targetting;exit;
//echo"..."."update ppc_settings set value='$time_date_targetting' where name='time_date_targetting'";exit;
	mysql_query("update ppc_settings set value='$site_targeting' where name='site_targeting'");
	?>
<span class="inserted"><br>
	<?php echo "Site targeting mode has been successfully updated . ";?></span>
<p></p>
	<?php

}

?>



<?php
if(isset($_POST['portal_system']))
{
	$name2=safeRead('portal_system');//$_POST['ppc_engine_name'];
	if($name2=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update portal system.";?></span>
<p></p>
	<?php
	}
	elseif(($_POST['portal_system']==1) && ($GLOBALS['account_migration']==1))
	{
	
	?>
<span class="already"><br>
	<?php echo "You cannot yet make adserver part of portal. Your system still contains unmerged accounts.";?></span>
<p></p>	
<?php 	
	}
	else
	{

		if($_POST['single_account_mode']==0)
		{
			mysql_query("update ppc_settings set value='0' where name='portal_system'");
		}
		else
		mysql_query("update ppc_settings set value='$name2' where name='portal_system'");
		?>
<span class="inserted"><br>
		<?php echo "Portal system has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>


<?php
if(isset($_POST['periodic_budget']))
	{
	$periodic_budget=safeRead('periodic_budget');
	mysql_query("update ppc_settings set value='$periodic_budget' where name='budget_period'");
			?>
<span class="inserted"><br>
<?php echo "Budget Period has been successfully updated . ";?></span>
<p></p>
<?php
	}
?>

<?php
if(isset($_POST['default_admaxamount']))
{
	$name3=safeRead('default_admaxamount');//$_POST['ppc_engine_name'];
	if($name3=="" ||$name3<=0 || !is_numeric($name3) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update default  budget for ads.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name3' where name='default_admaxamount'");
		?>
<span class="inserted"><br>
		<?php echo " Default  budget for ads has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
if(isset($_POST['min_click_value']))
{
	$name9=safeRead('min_click_value');//$_POST['ppc_engine_name'];
	if($name9=="" ||$name9<=0 || !is_numeric($name9) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  keyword min. click value.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name9' where name='min_click_value'");
		?>
<span class="inserted"><br>
		<?php echo "Keyword min. click value has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
if(isset($_POST['ad_title_maxlength']))
{
	$name4=safeRead('ad_title_maxlength');//$_POST['ppc_engine_name'];
	if($name4=="" ||$name4<=0 || !is_numeric($name4) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update max. length for PPC ad title.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name4' where name='ad_title_maxlength'");
		?>
<span class="inserted"><br>
		<?php echo " Max. length for PPC ad title has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
if(isset($_POST['ad_description_maxlength']))
{
	$name5=safeRead('ad_description_maxlength');//$_POST['ppc_engine_name'];
	if($name5=="" ||$name5<=0 || !is_numeric($name5) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update max. length for PPC ad description.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name5' where name='ad_description_maxlength'");
		?>
<span class="inserted"><br>
		<?php echo " Max. length for PPC ad description has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php

//added on 04 november 2009
if(isset($_POST['ad_keyword_mode']))
{
	$name6=safeRead('ad_keyword_mode');//$_POST['ppc_engine_name'];
	if($name6=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update adserver operation mode option.";?></span>
<p></p>
	<?php
	}
	else if(trim($_POST['default_keyword'])=="" && $name6!=1)
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update adserver operation mode option. Please enter a default keyword";?></span>
<p></p>
	<?php

	}
	else
	{
		mysql_query("update ppc_settings set value='$name6' where name='ad_keyword_mode'");
		?>
<span class="inserted"><br>
		<?php echo "Adserver operation mode has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}

//added on 04 november 2009 ?>


<?php
if(isset($_POST['default_keyword']))
{
	$default_keywords=safeRead('default_keyword');//$_POST['ppc_engine_name'];
	//echo "default keywords-$default_keywords<br>";
	//echo "update ppc_settings set value='$default_keywords' where name='keywords_default'";
	if($script_mode=="demo")
	{
		?>
<span class="already"><br>
		<?php echo "Cannot update default keyword  in demo.";?></span>
<p></p>
		<?php
	}
	elseif(trim($default_keywords)=="" && trim($_POST['ad_keyword_mode'])!=1)
	{
		?>
<span class="already"><br>
		<?php echo "Invalid data. Cannot update default keyword.";?></span>
<p></p>
		<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$default_keywords' where name='keywords_default'");
		?>
<span class="inserted"><br>
		<?php echo "Default keyword has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}


?>

<?php
if(isset($_POST['auto_keyword_approve']))
{
	$name6=safeRead('auto_keyword_approve');//$_POST['ppc_engine_name'];
	if($name6=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update automatic keyword approve option.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name6' where name='auto_keyword_approve'");
		?>
<span class="inserted"><br>
		<?php echo " Automatic keyword approve option has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}




	if(isset($_POST['rotation_settings']))
	{
		$ad_rotation=safeRead('rotation_settings');
		if($script_mode=="demo")
		{
			?>
<span class="already"><br>
			<?php echo "Cannot update Ad rotation settings in demo.";?></span>
<p></p>
			<?php
		}
		else
		{
			mysql_query("update ppc_settings set value='$ad_rotation' where name='ad_rotation'");
			?>
<span class="inserted"><br>
			<?php echo "Ad rotation setting has been successfully updated . ";?></span>
<p></p>
			<?php
		}
	}	
	

?>
<?php 
if(isset($_POST['fraudtime']))
{
	$fraudtime=safeRead('fraudtime');//$_POST['ppc_engine_name'];
	if($fraudtime=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update fraud detection time interval.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$fraudtime' where name='fraud_time_interval'");
		?>
<span class="inserted"><br>
		<?php echo "Fraud detection time interval has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
	if(isset($_POST['proxy_detection']))
	{
		$proxy_detection=safeRead('proxy_detection');
		mysql_query("update ppc_settings set value='$proxy_detection' where name='proxy_detection'");
		?>
<span class="inserted"><br>
		<?php echo "Proxy detection  setting has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	
	

<?php
if(isset($_POST['captcha_status']))
{
	$captcha_status=safeRead('captcha_status');//$_POST['ppc_engine_name'];
	if($captcha_status=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update Captcha status of publisher.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$captcha_status' where name='captcha_status'");
		?>
<span class="inserted"><br>
		<?php echo "Captcha status of publisher has been successfully updated . ";?></span>
<p></p>
		<?php
		if($captcha_status==1)
		{
			$captcha_time=safeRead('captcha_time');//$_POST['ppc_engine_name'];
				
			

			if($captcha_time=="" )
			{
				?>
<span class="already"><br>
				<?php echo "Invalid data. Cannot update captcha time duration.";?></span>
<p></p>
				<?php
			}
			else
			{
				mysql_query("update ppc_settings set value='$captcha_time' where name='captcha_time'");
				?>
<span class="inserted"><br>
				<?php echo "Publisher captcha time duration has been  successfully updated . ";?></span>
<p></p>
				<?php
			}
		}
	}

}


?>

	
	
		<?php
	if(isset($_POST['raw_data_clearing']))
	{
		$raw_data_clearing=safeRead('raw_data_clearing');
		if($script_mode=="demo")
		{
			?>
<span class="already"><br>
			<?php echo "Cannot update raw data clearing in demo.";?></span>
<p></p>
			<?php
		}
		else
		{
			mysql_query("update ppc_settings set value='$raw_data_clearing' where name='raw_data_clearing'");
			?>
<span class="inserted"><br>
			<?php echo "Raw data clearing status has been successfully updated . ";?></span>
<p></p>
			<?php
		}
	}



	?>
	

<?php
if(isset($_POST['traffic_analysis']))
{
	$traffic=safeRead('traffic_analysis');
	mysql_query("update ppc_settings set value='$traffic' where name='traffic_analysis'");
	?>
<span class="inserted"><br>
	<?php echo "Admin traffic analysis setting has been successfully updated . ";?></span>
<p></p>
	<?php
}

?>

<?php
if(isset($_POST['displayurl_maxlength']))
{
	$name20=safeRead('displayurl_maxlength');//$_POST['ppc_engine_name'];
	if($name20=="" ||$name20<=0 || !is_numeric($name20) )
	{?>

<span class="already"><br>
	<?php echo "Invalid data. Cannot update  max. length for PPC ad display url .";?></span>
<p></p>
	<?php
	}
	else
	{//echo "hii";
		mysql_query("update ppc_settings set value='$name20' where name='ad_displayurl_maxlength'");

		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
		?>
<span class="inserted"><br>
		<?php echo "Max. length for PPC ad display url has been successfully updated. ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
if(isset($_POST['opening_bonus']))
{
	$name7=safeRead('opening_bonus');//$_POST['ppc_engine_name'];
	if($name7=="" ||$name7<0 || !is_numeric($name7) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update account opening bonus value.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name7' where name='opening_bonus'");
		?>
<span class="inserted"><br>
		<?php echo " Account opening bonus value has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php 
if(isset($_POST['bonous_system_type']))
{
$bonous_system=safeRead('bonous_system_type');
mysql_query("update ppc_settings set value='$bonous_system' where name='bonous_system_type'");?>
<span class="inserted"><br>
<?php echo "Masking	bonus only advertisers from publishers has been successfully updated . ";?></span>
<p></p>
<?php
	}

?>
<?php
if(isset($_POST['min_user_transaction_amount']))
{
	$name8=safeRead('min_user_transaction_amount');//$_POST['ppc_engine_name'];
	if($name8=="" ||$name8<=0 || !is_numeric($name8) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update minimum amount advertiser can deposit.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name8' where name='min_user_transaction_amount'");
		?>
<span class="inserted"><br>
		<?php echo " Minimum amount advertiser can deposit has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php

if(isset($_POST['curren']))
{
	$curren=$_POST['curren'];

$currency=$mysql->echo_one("select currency from ppc_currency where status=1 and id='$curren'");
	//$name10=safeRead('curren');//$_POST['ppc_engine_name'];
	if($currency=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update system currency .";?></span>
<p></p>
	<?php
	}
	/*elseif($paypal_currency!=$currency)
	{
	?>
<span class="already"><br>
	<?php echo "You cannot update system currency.Because system currency and paypal currency must be same. ";?></span>
<p></p>
	<?php	
		
	}*/
	else
	{
		mysql_query("update ppc_settings set value='$currency' where name='system_currency'");
		$system_currency=$currency;
		?>
<span class="inserted"><br>
		<?php echo "System currency  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php
//Currency symbol
if(isset($_POST['curren']))
{
$curren=$_POST['curren'];
//$paypal_currency_symb=$mysql->echo_one("select symbol from ppc_currency where status=1 and currency='$paypal_currency'");
$currency_symb=$mysql->echo_one("select symbol from ppc_currency where status=1 and id='$curren'");
	//$name10=safeRead('currency_symbol');//$_POST['ppc_engine_name'];
	if($currency_symb=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update currency symbol.";?></span>
<p></p>
	<?php
	}
	else
	{
		//if($currency_symb==$paypal_currency_symb)
		//{
		mysql_query("update ppc_settings set value='$currency_symb' where name='currency_symbol'");
		//}
		?>
<span class="inserted"><br>
		<?php echo "Currency symbol has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php               //format settings
if(isset($_POST['day_of_week']))
{
	$name15=safeRead('day_of_week');//$_POST['ppc_engine_name'];
	if($name15=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update day of week format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name15' where name='day_of_week'");
		?>
<span class="inserted"><br>
		<?php echo "Day of week format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php               //format settings
if(isset($_POST['day_of_month']) )
{
	$name15=safeRead('day_of_month');
	
	
	
	
	if($name15=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update day of month format.";?></span>
<p></p>
	<?php
	}
	

	else
	{
		mysql_query("update ppc_settings set value='$name15' where name='day_of_month'");
		?>
<span class="inserted"><br>
		<?php echo "Day of month format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php               //format settings
if(isset($_POST['month']))
{
	$name14=safeRead('month');
if($name14=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  month format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name14' where name='month'");
		?>
<span class="inserted"><br>
		<?php echo "Month format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php               //format settings
if(isset($_POST['year']))
{
	$name13=safeRead('year');
if($name13=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  year format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name13' where name='year'");
		?>
<span class="inserted"><br>
		<?php echo "Year format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php               //format settings
if(isset($_POST['date_separator']))
{
	$name12=$_POST['date_separator'];//safeRead('date_separator');
if($name12=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  separator in date format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name12' where name='date_separators'");
		?>
<span class="inserted"><br>
		<?php echo "Separator in date format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php               //format settings
if(isset($_POST['day-position']))
{
	$name11=safeRead('day-position');
if($name11=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  day position in date format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name11' where name='datefield_format'");
		?>
<span class="inserted"><br>
		<?php echo "Day position in  date format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>



<?php               //timeformat settings
if(isset($_POST['hour']))
{
	$name10=safeRead('hour');
if($name10=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  hour format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name10' where name='hour'");
		?>
<span class="inserted"><br>
		<?php echo "Hour format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php               //timeformat settings
if(isset($_POST['minute']))
{
	$name9=safeRead('minute');
if($name9=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  minute format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name9' where name='minute'");
		?>
<span class="inserted"><br>
		<?php echo "Minute format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>



<?php               //timeformat settings
if(isset($_POST['second']))
{
	$name8=safeRead('second');
if($name8=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  seconds format .";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name8' where name='seconds'");
		?>
<span class="inserted"><br>
		<?php echo "Seconds  format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>



<?php               //timeformat settings
if(isset($_POST['hourfmt']))
{
	$name7=safeRead('hourfmt');
if($name7=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  24/12 time format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name7' where name='clock_type'");
		?>
<span class="inserted"><br>
		<?php echo "24/12 time format   has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>


<?php               //timeformat settings
if(isset($_POST['time_separator']))
{
	$name6=safeRead('time_separator');
if($name6=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  separator in time format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name6' where name='time_separator'");
		?>
<span class="inserted"><br>
		<?php echo "Separator in time format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php               //timeformat settings
if(isset($_POST['currency-style']))
{
	$name5=safeRead('currency-style');
if($name5=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  currency format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name5' where name='currency_format'");
		?>
<span class="inserted"><br>
		<?php echo "Currency   format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>




<?php               //timeformat settings
if(isset($_POST['currency-position']))
{
	$name4=safeRead('currency-position');
if($name4=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  currency position format.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name4' where name='currency_position'");
		?>
<span class="inserted"><br>
		<?php echo "Currency position  in currency format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>





<?php               //Number format settings
if(isset($_POST['thousand']))
{
	$name3=safeRead('thousand');
if($name3=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  thousands separator in number format.";?></span>
<p></p>
	<?php
	}
	elseif(strlen($name3)>1)
	{ ?>
	<span class="already"><br>
	<?php echo "Invalid data. Thousands separator must be one character.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name3' where name='thousand_separator'");
		?>
<span class="inserted"><br>
		<?php echo "Thousands separator in number format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php               //Number format settings
if(isset($_POST['decimal']))
{
	$name3=safeRead('decimal');
if($name3=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  decimal separator in number format.";?></span>
<p></p>
	<?php
	}
	elseif(strlen($name3)>1)
	{ ?>
	<span class="already"><br>
	<?php echo "Invalid data. Decimal separator must be one character.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name3' where name='decimal_separator'");
		?>
<span class="inserted"><br>
		<?php echo "Decimal separator in number format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>


<?php               //Number format settings
if(isset($_POST['nodecimal']))
{
	$name2=safeRead('nodecimal');
if($name2=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update  number of decimal places in number format.";?></span>
<p></p>
	<?php
	}
	elseif((strlen($name2)>1) && (!is_numeric($name2)) )
	{ ?>
	<span class="already"><br>
	<?php echo "Invalid data. Number of decimal places must be a one digit number.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name2' where name='no_of_decimalplaces'");
		?>
<span class="inserted"><br>
		<?php echo " Number of decimal places in number format  has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>





<!-- monetary settings -->
<?php 
if(isset($_POST['revenue_booster']))
{
	$name17=safeRead('revenue_booster');//$_POST['ppc_engine_name'];
	if($name17=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update Revenue booster setting.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name17' where name='revenue_booster'");
		?>
<span class="inserted"><br>
		<?php echo "Revenue booster setting has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
if(isset($_POST['revenue_boost_level']))
{
	$name18=safeRead('revenue_boost_level');//$_POST['ppc_engine_name'];
	if($name18=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update Revenue booster level.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name18' where name='revenue_boost_level'");
		?>
<span class="inserted"><br>
		<?php echo "Revenue booster level has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php
if(isset($_POST['min_publisher_acc_balance']))
{
	$name20=safeRead('min_publisher_acc_balance');//$_POST['ppc_engine_name'];
	if($name20=="" ||$name20<=0 || !is_numeric($name20) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update minimum balance for publisher account.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name20' where name='min_publisher_acc_balance'");
		?>
<span class="inserted"><br>
		<?php echo "Minimum balance for publisher account has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php if(($publisher_referral_profit+$advertiser_referral_profit+$_POST['publisher_profit'])>100){ ?>
<br />
<span class="already"><br>
<?php echo "Invalid data. Cannot update publisher profit. Publisher share+advertiser referral share+ publisher referral share should be less than 100";?></span>
<p></p>

<?php } else {?>


<?php
if(isset($_POST['publisher_profit']))
{
	$name20=safeRead('publisher_profit');//$_POST['ppc_engine_name'];
	if($name20=="" ||$name20<0 || !is_numeric($name20))
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update profit settings for publisher account.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name20' where name='publisher_profit'");
		?>
<span class="inserted"><br>
		<?php echo "Profit settings for publisher account has been successfully updated . ";?></span>  

<p></p>
		<?php
	}
}
?>
<?php } ?>
<?php if(($publisher_referral_profit+$advertiser_referral_profit+$_POST['premium_profit'])>100){ ?>
<br />
<span class="already"><br>
<?php echo "Invalid data. Cannot update premium profit. Premium share+advertiser referral share+ publisher referral share should be less than 100";?></span>
<p></p>

<?php } else {?>

<?php
if(isset($_POST['premium_profit']))
{
	$name20=safeRead('premium_profit');//$_POST['ppc_engine_name'];
	if($name20=="" ||$name20<0 || !is_numeric($name20))
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update premium settings for publisher account.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name20' where name='premium_profit'");
		?>
<span class="inserted"><br>
		<?php echo "Premium settings for publisher account has been successfully updated . ";?></span>  

<p></p>
		<?php
	}
}
?>
<?php } ?>
<?php
if(isset($_POST['ageing']))
{
	$name20=safeRead('ageing');//$_POST['ppc_engine_name'];

	if($name20==""||$name20<0 || !is_numeric($name20))
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update ageing factor for ads.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name20' where name='ad_ageing_factor'");
		?>
<span class="inserted"><br>
		<?php echo "Ageing factor for ads has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
if(isset($_POST['xml_auth_id']))
{
	$xml_auth_id=safeRead('xml_auth_id');

		if($script_mode=="demo")
	{
		?>
<span class="already"><br>
		<?php echo "Cannot update  API authentication code in demo.";?></span>
<p></p>
		<?php
	}
	else
	{
	mysql_query("update ppc_settings set value='$xml_auth_id' where name='xml_auth_code'");
	?>
<span class="inserted"><br>
	<?php echo "API authentication code has been successfully updated . ";?></span>
<p></p>
	<?php
}
}

?>


<?php
if(isset($_POST['affiliate_id']))
{
	$name20=safeRead('affiliate_id');//$_POST['ppc_engine_name'];
	if($script_mode=="demo")
	{
		?>
<span class="already"><br>
		<?php echo "Cannot update your affiliate id in demo.";?></span>
<p></p>
		<?php
	}
	else if($name20==""||$name20<0 || !is_numeric($name20) || count(explode(".",$name20))>1 )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update your affiliate id.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name20' where name='affiliate_id'");
		?>
<span class="inserted"><br>
		<?php echo "Inoutscripts affiliate id has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php if(($_POST['publisher_referral']+$_POST['advertiser_referral']+$publisher_profit)>100){ ?>
<br />
<span class="already"><br>
<?php echo "Invalid data. Cannot update referral details. Publisher share+advertiser referral share+ publisher referral share should be less than 100";?></span>
<p></p>
<?php } else {?>
<?php
if(isset($_POST['referral']))
{
	$name20=safeRead('referral');//$_POST['ppc_engine_name'];
	if($name20==""||$name20<0 || !is_numeric($name20) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update referral statistics.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name20' where name='referral_system'");
		?>
<span class="inserted"><br>
		<?php echo "Referral  status has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php 
if(isset($_POST['publisher_referral']))
{
	$name20=safeRead('publisher_referral');//$_POST['ppc_engine_name'];
	if($name20==""||$name20<0 || !is_numeric($name20) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update profit percent for publisher referral.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name20' where name='publisher_referral_profit'");
		?>
<span class="inserted"><br>
		<?php echo "Profit percent for publisher referral has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php 
if(isset($_POST['advertiser_referral']))
{
	$name20=safeRead('advertiser_referral');//$_POST['ppc_engine_name'];

	if($name20==""||$name20<0 || !is_numeric($name20) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update profit percent for advertiser referral.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name20' where name='advertiser_referral_profit'");
		?>
<span class="inserted"><br>
		<?php echo "Profit percent for advertiser referral has been successfully updated . ";?></span>
<p></p>


		<?php
	}
}
?>
<?php } //End of error condition?>
<?php






//echo $_FILES['glogo']['name']."---------------public<br>";
//echo $_FILES['alogo']['name']."----------------adserver<br>";
//echo $_FILES['glogo']['name']."----------------gener<br>";


/*---------------------------------------------------adserver 5.4 public image--------------------------------------------*/
$glogo=trim($_FILES['glogo']['name']);
//echo $_FILES['glogo']['name']."public<br>";
if($glogo!="")	
{




if($script_mode=="demo")
	{
		if($lid<5)
		{
	
	
		$str='<br><br><br><br><br><br><br><center><font color="#FF0000" size="2">These Operation not allowed demo!</font> <br> <br>
		<a href="javascript:history.back(-1);"><strong>Go Back</strong></a>         </center>
		';
		
		echo $str; 
		
		exit;
		}
	}
	$public_background=$public_background;
	$path="../".$GLOBALS['banners_folder']."/logo/";
	$file=$_FILES['glogo']['name'];
	$path_info = pathinfo($file);
			//print_r($path_info);exit;
			$filename=$path_info['filename'];
			
			$ext=$path_info['extension'];
			
			$file=md5($filename.$time);
			$file=$file.".".$ext;
			
	
			
	
	if(!((($_FILES["glogo"]["type"] == "image/gif")
	|| ($_FILES["glogo"]["type"] == "image/jpeg")
	|| ($_FILES["glogo"]["type"] == "image/jpg")
	|| ($_FILES["glogo"]["type"] == "image/png")
	
	)))
	{
             
		?>
<span class="already"><br>
		<?php echo "Public background image is Invalid data. Enter a valid image.";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br>
<p></p>
		<?php
		include("admin.footer.inc.php");
	exit(0);
	}
	else if($file!=''){
		

		///////////////////////////////////
		
		//move_uploaded_file($_FILES["logo"]["tmp_name"],"../".$GLOBALS['banners_folder']."/logo/" . $_FILES["logo"]["name"]);
	if(!file_exists("../".$GLOBALS['banners_folder']."/logo"))
	{
	
	mkdir("../".$GLOBALS['banners_folder']."/logo",0777);	
	}
	
	$size=getimagesize($_FILES['glogo']['tmp_name']);
//
	  
	 if($_FILES['glogo']['tmp_name']!="")
	 {
	
	 unlink($path.$public_background);
	 }
		copy($_FILES['glogo']['tmp_name'],"../".$GLOBALS['banners_folder']."/logo/".$file);
		

			
//			$size1=getimagesize("../".$GLOBALS['banners_folder']."/logo/".$file);
//
//			// propotionally image resizing
//$width=($size1[0]/$size1[1])*60;
//$height=$size[1];
//			$rimg = new ImageResizer("../".$GLOBALS['banners_folder']."/logo/$file");
//			$rimg->resize($width,$height,"../".$GLOBALS['banners_folder']."/logo/$file");
//	
		if($_FILES['glogo']['tmp_name']!="")
	 {
	mysql_query("update ppc_settings set value='$file' where name='public_background'");
		}	
		///////////////////////////////////
		
		?>
<span class="inserted"><br>
		<?php echo "Public background image has been successfully updated . ";?></span>
<p></p>
		<?php
}
}




/*---------------------------------------------------adserver 5.4--------------------------------------------*/





/*---------------------------------------------------adserver 5.4 publisher background image image--------------------------------------------*/
$plogo=trim($_FILES['plogo']['name']);
//echo $_FILES['plogo']['name']."publisher<br>";
if($plogo!="")	
{

if($script_mode=="demo")
	{
		if($lid<5)
		{
	
	
		$str='<br><br><br><br><br><br><br><center><font color="#FF0000" size="2">These Operation not allowed demo!</font> <br> <br>
		<a href="javascript:history.back(-1);"><strong>Go Back</strong></a>         </center>
		';
		
		echo $str; 
		
		exit;
		}
	}
	$publisher_image=$publisher_image;
	$path="../".$GLOBALS['banners_folder']."/logo/";
	$file=$_FILES['plogo']['name'];
	$path_info = pathinfo($file);
			//print_r($path_info);exit;
			$filename=$path_info['filename'];
			
			$ext=$path_info['extension'];
			
			$file=md5($filename.$time);
			$file=$file.".".$ext;
			
	
	
	if(!((($_FILES["plogo"]["type"] == "image/gif")
	|| ($_FILES["plogo"]["type"] == "image/jpeg")
	|| ($_FILES["plogo"]["type"] == "image/jpg")
	|| ($_FILES["plogo"]["type"] == "image/png")
	
	)))
	{
             
		?>
<span class="already"><br>
		<?php echo "Invalid data. Enter a valid image in publisher background image.";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br>
<p></p>
		<?php
		include("admin.footer.inc.php");
	exit(0);
	}
	else if($file!=''){
		

		///////////////////////////////////
		
		//move_uploaded_file($_FILES["logo"]["tmp_name"],"../".$GLOBALS['banners_folder']."/logo/" . $_FILES["logo"]["name"]);
	if(!file_exists("../".$GLOBALS['banners_folder']."/logo"))
	{
	
	mkdir("../".$GLOBALS['banners_folder']."/logo",0777);	
	}
	
	$size=getimagesize($_FILES['plogo']['tmp_name']);
//
	  
	 if($_FILES['plogo']['tmp_name']!="")
	 {
	
	unlink($path.$publisher_image);
	}
		copy($_FILES['plogo']['tmp_name'],"../".$GLOBALS['banners_folder']."/logo/".$file);
	
	if($_FILES['plogo']['tmp_name']!="")
	 {	
	mysql_query("update ppc_settings set value='$file' where name='publisher_image'");
		}	
		///////////////////////////////////
		
		?>
<span class="inserted"><br>
		<?php echo "Publisher background image has been successfully updated . ";?></span>
<p></p>

	<?php
}
}





/*---------------------------------------------------adserver 5.4--------------------------------------------*/









/*---------------------------------------------------adserver 5.4 advertiserbackground image_image image--------------------------------------------*/
//echo $_FILES['alogo']['name']." advertiser<br>";
$alogo=trim($_FILES['alogo']['name']);
if($alogo!="")	
{

if($script_mode=="demo")
	{
		if($lid<5)
		{
	
	
		$str='<br><br><br><br><br><br><br><center><font color="#FF0000" size="2">These Operation not allowed demo!</font> <br> <br>
		<a href="javascript:history.back(-1);"><strong>Go Back</strong></a>         </center>
		';
		
		echo $str; 
		
		exit;
		}
	}
	$advertiser_image=$advertiser_image;
	$path="../".$GLOBALS['banners_folder']."/logo/";
	$file=$_FILES['alogo']['name'];
	$path_info = pathinfo($file);
			//print_r($path_info);exit;
			$filename=$path_info['filename'];
			
			$ext=$path_info['extension'];
			
			$file=md5($filename.$time);
			$file=$file.".".$ext;
			
	
	
	if(!((($_FILES["alogo"]["type"] == "image/gif")
	|| ($_FILES["alogo"]["type"] == "image/jpeg")
	|| ($_FILES["alogo"]["type"] == "image/jpg")
	|| ($_FILES["alogo"]["type"] == "image/png")
	
	)))
	{
             
		?>
<span class="already"><br>
		<?php echo "Invalid data. Enter a valid Advertiser background image.";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br>
<p></p>
		<?php
		include("admin.footer.inc.php");
	exit(0);
	}
	else if($file!=''){
		

		///////////////////////////////////
		
		//move_uploaded_file($_FILES["logo"]["tmp_name"],"../".$GLOBALS['banners_folder']."/logo/" . $_FILES["logo"]["name"]);
	if(!file_exists("../".$GLOBALS['banners_folder']."/logo"))
	{
	
	mkdir("../".$GLOBALS['banners_folder']."/logo",0777);	
	}
	
	$size=getimagesize($_FILES['alogo']['tmp_name']);
//
	  
	 if($_FILES['alogo']['tmp_name']!="")
	 {
	
	unlink($path.$advertiser_image);
	}
		copy($_FILES['alogo']['tmp_name'],"../".$GLOBALS['banners_folder']."/logo/".$file);
	if($_FILES['alogo']['tmp_name']!="")
	 {
	mysql_query("update ppc_settings set value='$file' where name='advertiser_image'");
			}
		///////////////////////////////////
		
		?>
<span class="inserted"><br>
		<?php echo "Advertiser background image has been successfully updated  . ";?></span>
<p></p>
		<?php
	
}
}





/*---------------------------------------------------adserver 5.4--------------------------------------------*/














if((isset($_FILES['logo']['name']))&&($_FILES['logo']['name']!=''))
{
	
	$file=$_FILES['logo']['name'];
	$filesize=100;
		
			if((($_FILES['logo']['size'])/1024) > $filesize )
			{
					//mysql_query("update ppc_settings set value='$file' where name=''");
				//	unlink("../".$GLOBALS['banners_folder']."/logo/".$file);
				
				?><span class="already"><br>
		        <?php echo "Invalid Image. Size is too big (more than 100 kb).";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br><?php
				
				
				include("admin.footer.inc.php");
	exit(0);
			//	exit(0);
			}
			
	
	if(!((($_FILES["logo"]["type"] == "image/gif")
	|| ($_FILES["logo"]["type"] == "image/jpeg")
	|| ($_FILES["logo"]["type"] == "image/jpg")
	|| ($_FILES["logo"]["type"] == "image/png")
	
	)))
	{
             
		?>
<span class="already"><br>
		<?php echo "Invalid data. Enter a valid image.";?></span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>          <br> <br>
<p></p>
		<?php
		include("admin.footer.inc.php");
	exit(0);
	}
	else if($file!=''){
		
			
		///////////////////////////////////
		
		//move_uploaded_file($_FILES["logo"]["tmp_name"],"../".$GLOBALS['banners_folder']."/logo/" . $_FILES["logo"]["name"]);
	if(!file_exists("../".$GLOBALS['banners_folder']."/logo"))
	{
	
	mkdir("../".$GLOBALS['banners_folder']."/logo",0777);	
	}
	
	$size=getimagesize($_FILES['logo']['tmp_name']);
//
	  
	 
	if($size[0]<=400 && $size[1]<=100)
	{
		copy($_FILES['logo']['tmp_name'],"../".$GLOBALS['banners_folder']."/logo/".$file);
		

			
//			$size1=getimagesize("../".$GLOBALS['banners_folder']."/logo/".$file);
//
//			// propotionally image resizing
//$width=($size1[0]/$size1[1])*60;
//$height=$size[1];
//			$rimg = new ImageResizer("../".$GLOBALS['banners_folder']."/logo/$file");
//			$rimg->resize($width,$height,"../".$GLOBALS['banners_folder']."/logo/$file");
//			
	mysql_query("update ppc_settings set value='$file' where name='logo_path'");
			
		///////////////////////////////////
		
		?>
<span class="inserted"><br>
		<?php echo "Page logo has been successfully updated . ";?></span>
<p></p>
		<?php
	}
	else
	{
		copy($_FILES['logo']['tmp_name'],"../".$GLOBALS['banners_folder']."/logo/".$file);
//					$size1=getimagesize("../".$GLOBALS['banners_folder']."/logo/".$file);

			// propotionally image resizing
			if($size[0]>400 && $size[1]<=100)
			{
$width=400;
$height=(400*$size[1])/$size[0];
		$rimg = new ImageResizer("../".$GLOBALS['banners_folder']."/logo/$file");
		$rimg->resize($width,$height,"../".$GLOBALS['banners_folder']."/logo/$file");
			}
			if($size[0]<=400 && $size[1]>100)
			{
$height=100;
$width=($size[0]/$size[1])*100;
		$rimg = new ImageResizer("../".$GLOBALS['banners_folder']."/logo/$file");
		$rimg->resize($width,$height,"../".$GLOBALS['banners_folder']."/logo/$file");
			}
	if($size[0]>400 && $size[1]>100)
			{
$width=400;
$height=(400*$size[1])/$size[0];
if($height>100)
{
	
	
	$width=($width/$height)*100;
	$height=100;
}
		$rimg = new ImageResizer("../".$GLOBALS['banners_folder']."/logo/$file");
		$rimg->resize($width,$height,"../".$GLOBALS['banners_folder']."/logo/$file");
			}
			mysql_query("update ppc_settings set value='$file' where name='logo_path'");
			?>
			<span class="inserted"><br>
		<?php echo "Page logo has been successfully updated . ";?></span>
<p></p>

	<?php
}
}
}

if(isset($_POST['ppc_page_width']))
{
	if($_POST['ppc_page_width']=="" || (!is_numeric($_POST['ppc_page_width'])))
	{
		?>
<span class="already"><br>
		<?php echo "Invalid data. Cannot update page width.";?></span>
<p></p>
		<?php
	}
	else{
		$page_width1=safeRead('ppc_page_width');//$_POST['ppc_engine_name'];
		$size=safeRead('size');//$_POST['ppc_engine_name'];
		$page_width=$page_width1.$size;
		//			echo "update ppc_settings set value='$page_width' where name='page_width'";
		//if($page_width==""||$page_width<0 || !is_numeric($page_width) )
		mysql_query("update ppc_settings set value='$page_width' where name='page_width'");
			
		?>
<span class="inserted"><br>
		<?php echo "Page width has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
if(isset($_POST['color_theme']))
{
	if($_POST['color_theme']=="")
	{
		?>
<span class="already"><br>
		<?php echo "Invalid data. Cannot update color theme.";?></span>
<p></p>
		<?php
	}
	else{
		$color_theme=safeRead('color_theme');//$_POST['ppc_engine_name'];
		mysql_query("update ppc_settings set value='$color_theme' where name='color_theme'");
		?>
<span class="inserted"><br>
		<?php echo "Color theme has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php 
if(isset($_POST['publisher_paypalpayment']))
{
if(($_POST['publisher_checkpayment']==0)&& ($_POST['publisher_bankpayment']==0)&& ($_POST['publisher_paypalpayment']==0))
{
	?><span class="already"><br>
	<?php echo "Atleast one payment mode must be selected for publisher.";?></span>
<p></p><?php  
}
else
{
?>
<?php
if(isset($_POST['publisher_paypalpayment']))
{
	$publisher_status=safeRead('publisher_paypalpayment');//$_POST['ppc_engine_name'];

	if($publisher_status=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update paypal payment mode for publisher.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$publisher_status' where name='publisher_paypalpayment'");
		if($publisher_status==0)
		mysql_query("update ppc_publisher_payment_details set paymentmode='1'");

		?>
<span class="inserted"><br>
		<?php echo "Paypal payment mode for publisher has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php 
if(isset($_POST['publisher_checkpayment']))
{
	$publisher_status=safeRead('publisher_checkpayment');//$_POST['ppc_engine_name'];

	if($publisher_status=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update check payment mode for publisher.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$publisher_status' where name='publisher_checkpayment'");
		if($publisher_status==0)
		mysql_query("update ppc_publisher_payment_details set paymentmode='1'");

		?>
<span class="inserted"><br>
		<?php echo "Check payment mode for publisher has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}

?>


<?php 

if(isset($_POST['publisher_bankpayment']))
{
	$publisher_bankpayment=safeRead('publisher_bankpayment');//$_POST['ppc_engine_name'];

	if($publisher_bankpayment=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update bank payment mode for publisher.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$publisher_bankpayment' where name='publisher_bankpayment'");
		if($publisher_status==0)
		//mysql_query("update ppc_publisher_payment_details set paymentmode='1'");

		?>
<span class="inserted"><br>
		<?php echo "Bank payment mode for publisher has been successfully updated . ";?></span>
<p></p>
		<?php
	}

}
}
}
?>
<?php 

if(isset($_POST['advertiser_paypalpayment']))
{
	
if(($_POST['advertiser_bankpayment']==0)&&($_POST['advertiser_authpayment']==0)&&($_POST['advertiser_paypalpayment']==0)&&($_POST['advertiser_checkpayment']==0))
{
	?><span class="already"><br>
	<?php echo "Atleast one payment mode must be selected for advertiser.";?></span>
<p></p><?php  
}
else
{
if(isset($_POST['advertiser_paypalpayment']))
{
	$advertiser_status=safeRead('advertiser_paypalpayment');//$_POST['ppc_engine_name'];
	if($advertiser_status=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update paypal payment mode for advertiser.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$advertiser_status' where name='advertiser_paypalpayment'");
		?>
		<span class="inserted"><br>
				<?php echo "Paypal payment mode for advertiser has been successfully updated . ";?></span>
		<p></p>
		<?php
				if($advertiser_status==1)
		{
			$paypal_email=safeRead('paypal_email');//$_POST['ppc_engine_name'];

			$description=safeRead('payapl_payment_item_escription');//$_POST['ppc_engine_name'];
$currency=safeRead('paypal_curr');
$paycurren=$mysql->echo_one("select currency from ppc_currency where status=1 and id='$currency'");

			if($paypal_email=="" || $description==""||$currency=="")
			{
				?>
<span class="already"><br>
				<?php echo "Invalid data. Cannot update paypal currency, email and description.";?></span>
<p></p>
				<?php
			}
				elseif($paycurren!=$system_currency)
	{
	?>
<span class="already"><br>
	<?php echo "Paypal settings cannot be updated. System currency and Paypal currency must be same. ";?></span>
<p></p>
	<?php	
		
	}else
			{				mysql_query("update ppc_settings set value='$paycurren' where name='paypal_currency'");
				mysql_query("update ppc_settings set value='$paypal_email' where name='paypal_email'");
				mysql_query("update ppc_settings set value='$description' where name='payapl_payment_item_escription'");
				?>
<span class="inserted"><br>
				<?php echo "Paypal currency, email & description  were  successfully updated . ";?></span>
<p></p>
				<?php
			}
		}
		
	}
} 
?>



<?php
if(isset($_POST['advertiser_authpayment']))
{
	$advertiser_status=safeRead('advertiser_authpayment');//$_POST['ppc_engine_name'];
	if($advertiser_status=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update Authorize.net payment mode for advertiser.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$advertiser_status' where name='advertiser_authpayment'");
		?>
<span class="inserted"><br>
		<?php echo "Authorize.net payment mode for advertiser has been successfully updated . ";?></span>
<p></p>
		<?php
		if($advertiser_status==1)
		{
			$loginid=safeRead('loginid');//$_POST['ppc_engine_name'];
				
			$transkey=safeRead('transkey');//$_POST['ppc_engine_name'];
				
			$mh5hash=safeRead('secretkey');//$_POST['ppc_engine_name'];

			if($loginid=="" || $transkey=="" ||$mh5hash=="")
			{
				?>
<span class="already"><br>
				<?php echo "Invalid data. Cannot update Authorise.net login id/transaction key/md5 hash.";?></span>
<p></p>
				<?php
			}
			else
			{
				mysql_query("update ppc_settings set value='$loginid' where name='authpaymentLoginid'");
				mysql_query("update ppc_settings set value='$transkey' where name='authpaymentTransactionKey'");
				mysql_query("update ppc_settings set value='$mh5hash' where name='authSecretCode'");
				?>
<span class="inserted"><br>
				<?php echo "Authorise.net login id, transaction key  and md5 hash were successfully updated . ";?></span>
<p></p>
				<?php
			}
		}
	}

}


?>


<?php
if(isset($_POST['advertiser_checkpayment']))
{
	$advertiser_status=safeRead('advertiser_checkpayment');//$_POST['ppc_engine_name'];
	if($advertiser_status=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update check payment mode for advertiser.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$advertiser_status' where name='advertiser_checkpayment'");
		?>
<span class="inserted"><br>
		<?php echo "Check payment mode for advertiser has been successfully updated . ";?></span>
<p></p>
		<?php
		if($advertiser_status==1)
		{
			$name=safeRead('payeename');//$_POST['ppc_engine_name'];

			$address=safeRead('address');//$_POST['ppc_engine_name'];

			if($name=="" || $address=="")
			{
				?>
<span class="already"><br>
				<?php echo "Invalid data. Cannot update check payee name and address.";?></span>
<p></p>
				<?php
			}
			else
			{
				mysql_query("update ppc_settings set value='$name' where name='checkpayment_payeename'");
				mysql_query("update ppc_settings set value='$address' where name='checkpayment_payeeaddress'");
				?>
<span class="inserted"><br>
				<?php echo "Check payee name and address  were  successfully updated . ";?></span>
<p></p>
				<?php
			}
		}
	}
} 
?>




<!--added on 29 August 2009-->

<?php
if(isset($_POST['advertiser_bankpayment']))
{
	$advertiser_bankpayment=safeRead('advertiser_bankpayment');
	mysql_query("update ppc_settings set value='$advertiser_bankpayment' where name='advertiser_bankpayment'");
	?>
<span class="inserted"><br>
	<?php echo "Advertiser bank payment mode has been successfully updated . ";?></span>
<p></p>
	<?php
}

?>
<?php
if($advertiser_bankpayment==1)
{
	?>
	<?php
	if(isset($_POST['bank_beneficiaryname']))
	{
		$bank_beneficiaryname=safeRead('bank_beneficiaryname');
		mysql_query("update admin_payment_details set value='$bank_beneficiaryname' where name='bank_beneficiaryname'");
		?>
<span class="inserted"><br>
		<?php echo "Bank payment beneficiary name has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	<?php
	if(isset($_POST['bank_account_number']))
	{
		$bank_account_number=safeRead('bank_account_number');
		mysql_query("update admin_payment_details set value='$bank_account_number' where name='bank_account_number'");
		?>
<span class="inserted"><br>
		<?php echo "Bank payment account number has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	<?php
	if(isset($_POST['routing_number']))
	{
		$routing_number=safeRead('routing_number');
		mysql_query("update admin_payment_details set value='$routing_number' where name='routing_number'");
		?>
<span class="inserted"><br>
		<?php echo "Bank payment routing/swift number has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	<?php
	if(isset($_POST['bank_name']))
	{
		$bank_name=safeRead('bank_name');
		mysql_query("update admin_payment_details set value='$bank_name' where name='bank_name'");
		?>
<span class="inserted"><br>
		<?php echo "Bank name has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	<?php
	if(isset($_POST['bank_address']))
	{
		$bank_address=safeRead('bank_address');
		mysql_query("update admin_payment_details set value='$bank_address' where name='bank_address'");
		?>
<span class="inserted"><br>
		<?php echo "Bank address has been successfully updated . ";?></span>
<p></p>
		<?php
	}
	?>
	<?php
	if(isset($_POST['bank_city']))
	{
		$bank_city=safeRead('bank_city');
		mysql_query("update admin_payment_details set value='$bank_city' where name='bank_city'");
		?>
<span class="inserted"><br>
		<?php echo "Bank city has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	<?php
	if(isset($_POST['bank_province']))
	{
		$bank_province=safeRead('bank_province');
		mysql_query("update admin_payment_details set value='$bank_province' where name='bank_province'");
		?>
<span class="inserted"><br>
		<?php echo "Bank province has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	<?php
	if(isset($_POST['bank_country']))
	{
		$bank_country=safeRead('bank_country');
		mysql_query("update admin_payment_details set value='$bank_country' where name='bank_country'");
		?>
<span class="inserted"><br>
		<?php echo "Bank country has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	<?php
	if(isset($_POST['account_type']))
	{
		$account_type=safeRead('account_type');
		mysql_query("update admin_payment_details set value='$account_type' where name='account_type'");
		?>
<span class="inserted"><br>
		<?php echo "Bank account type has been successfully updated . ";?></span>
<p></p>
		<?php
	}

	?>
	<?php } ?>
	
	
<?php
//added on 24/08/2009

if(isset($_POST['local_currency_pay']))
{
	$local_currency_pay=safeRead('local_currency_pay');
	mysql_query("update ppc_settings set value='$local_currency_pay' where name='local_currency_pay'");
	?>
<span class="inserted"><br>
	<?php echo "Local currency payment mode has been successfully updated . ";?></span>
<p></p>
	<?php
}
?>
<?php
if(isset($_POST['min_local_currency_pay_amt']))
{
	$min_local_currency_pay_amt=safeRead('min_local_currency_pay_amt');
	mysql_query("update ppc_settings set value='$min_local_currency_pay_amt' where name='min_local_currency_pay_amt'");
	?>
<span class="inserted"><br>
	<?php echo "Local currency minimum transaction  amount has been successfully updated . ";?></span>
<p></p>
	<?php
}
//added on 24/08/2009

?>
	
<?php	
} ?>

<!--added on 29 August 2009--><?php
	
} 
?>



<?php
if(isset($_POST['admin_general_notification_email']))
{
	$name11=safeRead('admin_general_notification_email');//$_POST['ppc_engine_name'];
	if($name11=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update admin general notification email Address.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name11' where name='admin_general_notification_email'");
		?>
<span class="inserted"><br>
		<?php echo "Admin general notification email address has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>

<?php
if(isset($_POST['admin_payment_notification_email']))
{
	$name14=safeRead('admin_payment_notification_email');//$_POST['ppc_engine_name'];
	if($name14=="")
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update admin payment notification email address.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name14' where name='admin_payment_notification_email'");
		?>
<span class="inserted"><br>
		<?php echo "Admin payment notification email address has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
if(isset($_POST['minimum_acc_balance']))
{
	$advertiser_minimum_account_balance=safeRead('minimum_acc_balance');//$_POST['ppc_engine_name'];
	if($advertiser_minimum_account_balance=="" ||$advertiser_minimum_account_balance<0 || !is_numeric($advertiser_minimum_account_balance) )
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update minimum advertiser account balance for email notification.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$advertiser_minimum_account_balance' where name='advertiser_minimum_account_balance'");
		?>
<span class="inserted"><br>
		<?php echo "Minimum advertiser account balance for email notification has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>
<?php
if(isset($_POST['send_mail_after_payment']))
{
	$name15=safeRead('send_mail_after_payment');//$_POST['ppc_engine_name'];
	if($name15!=1 && $name15!=0)
	{?>
<span class="already"><br>
	<?php echo "Invalid data. Cannot update setting for email acknowledgement on payment.";?></span>
<p></p>
	<?php
	}
	else
	{
		mysql_query("update ppc_settings set value='$name15' where name='send_mail_after_payment'");
		?>
<span class="inserted"><br>
		<?php echo "Email acknowledgement after payment has been successfully updated . ";?></span>
<p></p>
		<?php
	}
}
?>



<?php	
if(isset($_POST['logo_option']))
{
$logo_option=safeRead('logo_option');
mysql_query("update ppc_settings set value='$logo_option' where name='logo_display_option'");?>
<span class="inserted"><br>
<?php echo "Logo display option has been successfully updated . ";?></span>
<p></p>
<?php
	}

?>



<?php	
if(isset($_POST['bgimage_type']))
{
$bgimage_type=safeRead('bgimage_type');
mysql_query("update ppc_settings set value='$bgimage_type' where name='bgimage_type'");?>
<span class="inserted"><br>
<?php echo "backgroung image type has been successfully updated . ";?></span>
<p></p>
<?php
	}

?>



<?php 
if(isset($_POST['hardlinks']))
{
 $hardlinks=safeRead('hardlinks');

mysql_query("update ppc_settings set value='$hardlinks' where name='hardcode_check_url'");
?>
<span class="inserted"><br><?php echo " Hard Code Check Url has been successfully updated ! ";?></span><p></p>
<?php

}

?>


<?php
if(isset($_POST['install']) && $_POST['install']==1)
{
?>
<strong><a href="main.php">Admin Panel</a></strong> | <strong><a href="javascript:history.back(-1)">Back</a></strong>

<?php
}
elseif(isset($_POST['redir_url']))
{
?>
<strong><a href="<?php echo $_POST['redir_url']; ?>">Back</a></strong>
<?php
}
else
{
?>

<strong><a href="javascript:history.back(-1)">Back</a></strong>
<?php
}
?>
<br>
<br>

<?php include("admin.footer.inc.php");

function safeRead($var)
{
$str=$_POST[$var];
phpSafe($str);
return $str;
}
?>