<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

if($script_mode=="demo")
{

    mysql_query("update  `ppc_settings` set value='Inout Adserver Ultimate' where name='ppc_engine_name';");
    mysql_query("update  `ppc_settings` set value=6 where name='min_user_password_length';");
    mysql_query("update  `ppc_settings` set value=15 where name='default_admaxamount';");
    mysql_query("update  `ppc_settings` set value='en' where name='client_language';");
	mysql_query("update  `ppc_settings` set value=35 where where name='ad_title_maxlength';");
    mysql_query("update  `ppc_settings` set value=70 where where name='ad_description_maxlength';");
    mysql_query("update  `ppc_settings` set value=1 where name='auto_keyword_approve';");
    mysql_query("update  `ppc_settings` set value=10 where name='opening_bonus';");
    mysql_query("update  `ppc_settings` set value=5 where name='min_user_transaction_amount';");
    mysql_query("update  `ppc_settings` set value='0.05' where name='min_click_value';");
    mysql_query("update  `ppc_settings` set value='USD' where name='paypal_currency';");
    mysql_query("update  `ppc_settings` set value='info@yoursite.com' where name='admin_general_notification_email';");
    mysql_query("update  `ppc_settings` set value='sales@yoursite.com' where name='paypal_email';");
    mysql_query("update  `ppc_settings` set value='Advertiser Account Fund Deposit' where name='payapl_payment_item_escription';");
    mysql_query("update  `ppc_settings` set value='sales@yoursite.com' where name='admin_payment_notification_email';");
    mysql_query("update  `ppc_settings` set value=1 where name='send_mail_after_payment';");
    mysql_query("update  `ppc_settings` set value=1 where name='revenue_booster';");
    mysql_query("update  `ppc_settings` set value=3 where name='revenue_boost_level';");
    mysql_query("update  `ppc_settings` set value=35 where name='ad_displayurl_maxlength';");
    mysql_query("update  `ppc_settings` set value='utf-8' where name='ad_display_char_encoding';");
    mysql_query("update  `ppc_settings` set value=0 where name='affiliate_id';");
    mysql_query("update  `ppc_settings` set value=50 where name='min_publisher_acc_balance';");
    mysql_query("update  `ppc_settings` set value=50 where name='publisher_profit';");
    mysql_query("update  `ppc_settings` set value=1 where name='ad_ageing_factor';");
    mysql_query("update  `ppc_settings` set value='900px' where name='page_width';");
    mysql_query("update  `ppc_settings` set value='default' where name='color_theme';");
    mysql_query("update  `ppc_settings` set value='This is a dummy title of max length' where name='ad_title';");
    mysql_query("update  `ppc_settings` set value='This is a dummy ad description for the ad with maximum possible length' where name='ad_description';");
    mysql_query("update  `ppc_settings` set value='DummyDisplayURLOfMaxPossibleLength.' where name='ad_display_url';");
    mysql_query("update  `ppc_settings` set value=1 where name='publisher_checkpayment';");
    mysql_query("update  `ppc_settings` set value=1 where name='advertiser_checkpayment';");
   // mysql_query("update  `ppc_settings` set value=0 where name='ppc_keyword_delete';");
    mysql_query("update  `ppc_settings` set value='Inout Administrator' where name='checkpayment_payeename';");
    mysql_query("update  `ppc_settings` set value='Inoutscripts\r\nInout Building\r\nLevel 3,4,5\r\nKerala\r\nIndia' where name='checkpayment_payeeaddress';");
    mysql_query("update  `ppc_settings` set value='' where name='keywords_default';");
    mysql_query("update  `ppc_settings` set value='0' where name='traffic_analysis';");
    mysql_query("update  `ppc_settings` set value='$' where name='currency_symbol';");
    mysql_query("update  `ppc_settings` set value='24' where name='fraud_time_interval';");
    mysql_query("update  `ppc_settings` set value='5' where name='advertiser_minimum_account_balance';");
    mysql_query("update  `ppc_settings` set value='1' where name='local_currency_pay';");
    mysql_query("update  `ppc_settings` set value='100' where name='min_local_currency_pay_amt';");   
    mysql_query("update  `ppc_settings` set value='1' where name='advertiser_bankpayment';");
    mysql_query("update  `ppc_settings` set value='1' where name='proxy_detection';");
    mysql_query("update  `ppc_settings` set value='to' where name='logo_display_option';");
    mysql_query("update  `ppc_settings` set value='1' where name='advertiser_paypalpayment';");
    mysql_query("update  `ppc_settings` set value='USD' where name='system_currency';");
    mysql_query("update  `ppc_settings` set value='1' where name='publisher_paypalpayment';");
    mysql_query("update  `ppc_settings` set value='3' where name='ad_rotation';");
    mysql_query("update  `ppc_settings` set value='1' where name='ad_keyword_mode';");
    mysql_query("update  `ppc_settings` set value='10' where name='advertiser_referral_profit';");
    mysql_query("update  `ppc_settings` set value='10' where name='publisher_referral_profit';");
    mysql_query("update  `ppc_settings` set value='1' where name='referral_system';");
    mysql_query("update  `ppc_settings` set value='1' where name='publisher_bankpayment';");
    mysql_query("update  `ppc_settings` set value='0' where name='portal_system';");
	
    mysql_query("update  `ppc_settings` set value='0' where name='day_of_week';");
    mysql_query("update  `ppc_settings` set value='%e' where name='day_of_month';");
    mysql_query("update  `ppc_settings` set value='%b' where name='month';");
    mysql_query("update  `ppc_settings` set value='%y' where name='year';");
    mysql_query("update  `ppc_settings` set value='/' where name='date_separators';");
    mysql_query("update  `ppc_settings` set value='dayamonth' where name='datefield_format';");
    mysql_query("update  `ppc_settings` set value='%H' where name='hour';");
    mysql_query("update  `ppc_settings` set value='%M' where name='minute';");
    mysql_query("update  `ppc_settings` set value='%S' where name='seconds';");
    mysql_query("update  `ppc_settings` set value='12hour' where name='clock_type';");
    mysql_query("update  `ppc_settings` set value='$' where name='currency_format';");
    mysql_query("update  `ppc_settings` set value=',' where name='thousand_separator';");
    mysql_query("update  `ppc_settings` set value='.' where name='decimal_separator';");
    mysql_query("update  `ppc_settings` set value='2' where name='no_of_decimalplaces';");
	
    mysql_query("update  `ppc_settings` set value='1' where name='budget_period';");
    mysql_query("update  `ppc_settings` set value='1' where name='adv_status';");
    mysql_query("update  `ppc_settings` set value='-1' where name='pub_status';");
    mysql_query("update  `ppc_settings` set value='admin' where name='xml_auth_code';");
	
    mysql_query("update  `ppc_settings` set value='Dummy WAP ad title' where name='wap_ad_title';");
    mysql_query("update  `ppc_settings` set value='Dummy WAP ad desc' where name='wap_ad_description';");
    mysql_query("update  `ppc_settings` set value='DummyWapAdURL' where name='wap_ad_display_url';");
	
    mysql_query("update  `ppc_settings` set value='1' where name='bonous_system_type';");
    mysql_query("update  `ppc_settings` set value='0' where name='captcha_status';");
    mysql_query("update  `ppc_settings` set value='75' where name='premium_profit';");
    mysql_query("update  `ppc_settings` set value='0' where name='raw_data_clearing';");
	
	
	
    mysql_query("update  `admin_payment_details` set value='Inout Admin' where name='bank_beneficiaryname';");
    mysql_query("update  `admin_payment_details` set value='01952365879' where name='bank_account_number';");
    mysql_query("update  `admin_payment_details` set value='547896' where name='routing_number';");
    mysql_query("update  `admin_payment_details` set value='SBT' where name='bank_name';");
    mysql_query("update  `admin_payment_details` set value='Level 1\r\nAve Maria Building\r\nStreet V/1' where name='bank_address';");
    mysql_query("update  `admin_payment_details` set value='KTM' where name='bank_city';");
    mysql_query("update  `admin_payment_details` set value='KL' where name='bank_province';");
    mysql_query("update  `admin_payment_details` set value='India' where name='bank_country';");
    mysql_query("update  `admin_payment_details` set value='Savings' where name='account_type';");
           
    mysql_query("update  `site_content` set item_value='pay per click, pay per click advertising, online advertising, internet marketing, internet advertising, marketing, advertising,advertising network' where item_name='meta-keywords' and item_type=0 ;");
    mysql_query("update  `site_content` set item_value='Pay per click advertising - internet marketing solution for online advertisers.' where item_name='meta-description' and item_type=0;");
    mysql_query("update  `site_content` set item_value='pay per click,affiliate program,pay per click affiliate,pay per click affiliate program,make money,make money online,make money from your web site,webmasters make money,free affiliate program,online affiliate program,affiliate marketing,affiliate marketing program,webmaster affiliate program,internet affiliate program,ppc,webmaster tools,webmaster resources,website tools,web tools,publisher network' where item_name='meta-keywords' and item_type=1;");
    mysql_query("update  `site_content` set item_value='Pay per click publisher  program for Webmasters - place text ads on your website and generate revenue from your website traffic' where item_name='meta-description' and item_type=1;");
    mysql_query("update  `site_content` set item_value='pay per click,affiliate program, pay per click advertising,pay per click affiliate,pay per click affiliate program, online advertising, internet marketing,make money, internet advertising, marketing, advertising,advertising network,make money online,make money from your web site,webmasters make money,free affiliate program,online affiliate program,affiliate marketing,affiliate marketing program,webmaster affiliate program,internet affiliate program,ppc,webmaster tools,webmaster resources,website tools,web tools,publisher network' where item_name='meta-keywords' and item_type=2;");
    mysql_query("update  `site_content` set item_value='Pay Per Click advertising - internet marketing solution for online advertisers  and   Pay Per Click publisher  program for Webmasters - place text ads on your website and generate revenue from your website traffic' where item_name='meta-description' and item_type=2;");
   
    mysql_query("update   `ppc_publisher_credits` set credit='Ads by Inout Adserver' where id= '21';");
	mysql_query("update   `ppc_credittext_bordercolor` set credit_text_color='#FFFFFF', border_color='#000000' where id= '1';");
    mysql_query("update   `ppc_custom_ad_block` set name='Inout Search Vertical', title_color='#000099', desc_color='#393939', url_color='#00CC33', bg_color='#FFFFFF', credit_color='1', bordor_type='0', status=1, ppc_restricted_sites='', credit_text='21' where id='13';");
   
    $my_array=array("sex","porn","fuck","gay", "hack");
	
	
    $count = count($my_array);
    for ($i = 0; $i < $count; $i++)
        {
			 mysql_query("delete from ppc_keywords where sid in (select id from system_keywords where keyword like '%$my_array[$i]%' or  title like '%$my_array[$i]%' or  summary like '%$my_array[$i]%');");

		   mysql_query("delete from ppc_keywords where aid in (select id from ppc_ads where link like '%$my_array[$i]%' or  title like '%$my_array[$i]%' or  summary like '%$my_array[$i]%');");

			mysql_query("delete from ad_location_mapping where adid in (select id from ppc_ads where link like '%$my_array[$i]%' or  title like '%$my_array[$i]%' or  summary like '%$my_array[$i]%');");
		
           mysql_query("delete from ppc_ads where link like '%$my_array[$i]%' or  title like '%$my_array[$i]%' or  summary like '%$my_array[$i]%';");
           
        }

}		

?>