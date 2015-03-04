<?php
/*
 *
i'm actually in resolve hide function un install.php just for fun XD

__Func001__ = ... die or other function for stop script (possible in other script function)
__Func002__ = header
__Func003__ = (possible trim/htmlspecialchars/strtolower/strtoupper or ohter function in script)
__Func004__ = ... die or other function for stop script (possible in other script function)
__Func005__ = is certainly function check license function 
__Func006__ = mysql_connect
mysql_query = mysql_query
__Func008__ = mysql_fetch_array
__Func009__ = strcmp 
__Func010__ = (possible select_last_id mysql.cls.php)
__Func011__ = (possible select_many_rows mysql.cls.php
__Func012__ = ..
__Func013__ = date / timestamp / other date 
__Func014__ = date_default_timezone_get
__Func015__ =
__Func016__ = (possible count)
__Func017__ = (possible select_many_rows mysql.cls.php) // (__Func011__ or __Func017__) 
__Func018__ =
__Func019__ =
__Func020__ =
__Func021__ =
 */
include( "config.inc.php" );

//__Func001__( 1 );

if (!isset($_COOKIE['inout_admin'])){
    header( "Location:index.php" );
    exit(0);
}
$inout_username = $_COOKIE['inout_admin'];
$inout_password = $_COOKIE['inout_pass'];
if(!($inout_username == md5($username) && $inout_password == md5($password))){
    header( "Location:index.php" );
    exit(0);
}
//__Func004__( 0 );
//__Func005__( $license_key, "adserveradv" );
include( "pre-check.php" );
$covert_non_safe_data = 0;
$fresh = 1;
$tables = mysql_connect( $mysql_dbname );
//print_r($tables);
//echo $temp;
//die();
/*while ( list( $temp ) = temp ){
    if (!( $temp == "ppc_settings" ) )    {
        continue;
    }
    $fresh = 0;
    break;
    break;
}*/
if ( $fresh == 0 )
{
    $innodb_conversion = 1;
    $tab = mysql_query( "SHOW TABLE STATUS " );
    while ( $tab1 = __Func008__( $tab ) )
    {
        if ( $tab1[0] == "view_keywords" || $tab1[0] == "ppc_impressions" || $tab1[0] == "ppc_daily_impressions" )
        {
            continue;
        }
        if ( !( strcmp( $tab1['Engine'], "InnoDB" ) != 0 ) && mysql_query( "alter table {$tab1['0']} ENGINE='InnoDB'" ) )
        {
            $innodb_conversion = 0;
        }
    }
    if ( $innodb_conversion == 0 )
    {
        echo "\t\t\t\t  <div class=\"already\">InnoDB conversion failed. We cannot proceed with installation. Please try refreshing this page.  </div>\r\n\t\t\t";
        exit( );
    }
}
$charset_collation = " ";
if ( $fresh == 1 )
{
    mysql_query( "set names utf8 collate utf8_unicode_ci" );
    $charset_collation = " collate utf8_unicode_ci ";
}
else if ( $table_fields_collation != "" )
{
    $charset_collation = " collate {$table_fields_collation} ";
}
else
{
    $covert_non_safe_data = 1;
    $tablefields = mysql_query( "SHOW FULL COLUMNS FROM ppc_ads where collation is not null" );
    if ( !( $tablefield = __Func008__( $tablefields ) ) )
    {
        break;
    }
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'table_fields_collation', '{$tablefield['Collation']}');" );
    $charset_collation = " collate {$tablefield['Collation']} ";
    if ( strcmp( $tablefield['Collation'], "utf8_unicode_ci" ) == 0 )
    {
        mysql_query( "set names utf8 collate utf8_unicode_ci" );
    }
    break;
}
mysql_query( "
	CREATE TABLE IF NOT EXISTS `ppc_keywords` (
		 `id` int(11) NOT NULL auto_increment,
		 `aid` int(11) NOT NULL default '0',
		 `uid` int(11) NOT NULL default '0',
		 `keyword` varchar(255) NOT NULL default '',
		 `maxcv` float NOT NULL default '0',
		 `status` int(11) NOT NULL default '0',
		 `time` int(11) NOT NULL default '0',
		 PRIMARY KEY  (`id`)
	) ENGINE=InnoDB collate utf8_unicode_ci  AUTO_INCREMENT=1 ;" );

$weightageflag = 0;
$systemid = 0;

$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_keywords`" );

while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "weightage" )
    {
        $weightageflag = 1;
    }
    if ( $row['Field'] == "sid" )
    {
        $systemid = 1;
    }
}
if ( $weightageflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_keywords` ADD `weightage` FLOAT NOT NULL default '0';" );
    mysql_query( "UPDATE `ppc_keywords` SET `weightage`=`maxcv`;" );
    echo __Func010__( );
}
if ( $systemid == 0 )
{
    mysql_query( "ALTER TABLE `ppc_keywords` ADD `sid` int(11) NOT NULL default '0';" );
    echo __Func010__( );
}
mysql_query("
	CREATE TABLE IF NOT EXISTS `ppc_settings` (  
		`id` int(11) NOT NULL auto_increment,
		`section` varchar(50) NOT NULL default '',
		`name` varchar(50) NOT NULL default '',
		`value` longtext NOT NULL,
		PRIMARY KEY  (`id`)
	) ENGINE=InnoDB collate utf8_unicode_ci  AUTO_INCREMENT=1 ;" );
echo __Func010__( );

mysql_query( "
	CREATE TABLE IF NOT EXISTS `ppc_publishers` (
		`uid` int(11) NOT NULL auto_increment,
		`username` varchar(255) NOT NULL default '',
		`password` varchar(255) NOT NULL default '',
		`email` varchar(255) NOT NULL default '',
		`status` int(11) NOT NULL default '0',
		`country` varchar(50) NOT NULL default '',
		`domain` varchar(255) NOT NULL default '',
		`regtime` int(11) default NULL,
		`lastlogin` int(11) default NULL,
		`accountbalance` float NOT NULL default '0',
		`xmlstatus` int(11) NOT NULL default '0',
		`lastloginip` varchar(255) NOT NULL,
		PRIMARY KEY  (`uid`)
	) ENGINE=InnoDB collate utf8_unicode_ci  AUTO_INCREMENT=1 ;" );

echo __Func010__( );

$usr_ridflag = 0;
$usr_warning_statusflag = 0;
$usr_traffic_analysis = 0;
$firstname_flag = 0;
$lastname_flag = 0;
$phone_no_flag = 0;
$address_flag = 0;
$common_account_id1 = 0;
$parent_status1 = 0;
$server_id_flag = 0;
$captcha_status = 0;
$captcha_time = 0;
$taxidentification = 0;
$premiumst = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM ppc_publishers" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "rid" )
    {
        $usr_ridflag = 1;
    }
    if ( $row['Field'] == "warning_status" )
    {
        $usr_warning_statusflag = 1;
    }
    if ( $row['Field'] == "traffic_analysis" )
    {
        $usr_traffic_analysis = 1;
    }
    if ( $row['Field'] == "phone_no" )
    {
        $phone_no_flag = 1;
        if ( $row['Type'] == "int(11)" )
        {
            mysql_query( "ALTER TABLE `ppc_publishers` MODIFY `phone_no` BIGINT NOT NULL DEFAULT '0';" );
        }
    }
    if ( $row['Field'] == "firstname" )
    {
        $firstname_flag = 1;
    }
    if ( $row['Field'] == "lastname" )
    {
        $lastname_flag = 1;
    }
    if ( $row['Field'] == "address" )
    {
        $address_flag = 1;
    }
    if ( $row['Field'] == "override_encoding" )
    {
        mysql_query( "ALTER TABLE `ppc_publishers` CHANGE `override_encoding` `xmlstatus` INT( 11 ) NOT NULL DEFAULT '0'" );
    }
    if ( $row['Field'] == "common_account_id" )
    {
        $common_account_id1 = 1;
    }
    if ( $row['Field'] == "parent_status" )
    {
        $parent_status1 = 1;
    }
    if ( $row['Field'] == "server_id" )
    {
        $server_id_flag = 1;
    }
    if ( $row['Field'] == "captcha_status" )
    {
        $captcha_status = 1;
    }
    if ( $row['Field'] == "captcha_time" )
    {
        $captcha_time = 1;
    }
    if ( $row['Field'] == "taxidentification" )
    {
        $taxidentification = 1;
    }
    if ( $row['Field'] == "premium_status" )
    {
        $premiumst = 1;
    }
}
if ( $usr_ridflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `rid` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $usr_warning_statusflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `warning_status` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $usr_traffic_analysis == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `traffic_analysis` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $firstname_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `firstname` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $lastname_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `lastname` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $phone_no_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `phone_no` BIGINT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $address_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `address` LONGTEXT {$charset_collation} ;" );
    echo __Func010__( );
}
if ( $common_account_id1 == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `common_account_id` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $parent_status1 == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `parent_status` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $server_id_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `server_id` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $captcha_status == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `captcha_status` INT not null default 0;" );
    echo __Func010__( );
}
if ( $captcha_time == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `captcha_time` INT not null default 0;" );
    echo __Func010__( );
}
if ( $taxidentification == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `taxidentification` varchar(255) not null default '';" );
    echo __Func010__( );
}
if ( $premiumst == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publishers` ADD `premium_status` int not null default 0;" );
    echo __Func010__( );
}
mysql_query( "
	CREATE TABLE IF NOT EXISTS `ppc_users` (
		`uid` int(11) NOT NULL auto_increment,
		`username` varchar(255) NOT NULL default '',
		`password` varchar(255) NOT NULL default '',
		`email` varchar(255) NOT NULL default '',
		`status` int(11) NOT NULL default '0',
		`country` VARCHAR(2) default NULL,
		`domain` varchar(255) default NULL,
		`regtime` int(11) default NULL,
		`lastlogin` int(11) default NULL,
		`accountbalance` float NOT NULL default '0',
		PRIMARY KEY  (`uid`)
	) ENGINE=InnoDB collate utf8_unicode_ci  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$usr_ridflag = 0;
$usr_bonusbalanceflag = 0;
$firstname_flag = 0;
$lastname_flag = 0;
$phone_no_flag = 0;
$address_flag = 0;
$balancestatus_flag = 0;
$common_account_id = 0;
$parent_status = 0;
$taxidentification = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_users`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "rid" )
    {
        $usr_ridflag = 1;
    }
    if ( $row['Field'] == "bonusbalance" )
    {
        $usr_bonusbalanceflag = 1;
    }
    if ( $row['Field'] == "phone_no" )
    {
        $phone_no_flag = 1;
        if ( $row['Type'] == "int(11)" )
        {
            mysql_query( "ALTER TABLE `ppc_users` MODIFY `phone_no` BIGINT NOT NULL DEFAULT '0';" );
        }
    }
    if ( $row['Field'] == "firstname" )
    {
        $firstname_flag = 1;
    }
    if ( $row['Field'] == "lastname" )
    {
        $lastname_flag = 1;
    }
    if ( $row['Field'] == "address" )
    {
        $address_flag = 1;
    }
    if ( $row['Field'] == "common_account_id" )
    {
        $common_account_id = 1;
    }
    if ( $row['Field'] == "balancestatus" )
    {
        $balancestatus_flag = 1;
    }
    if ( $row['Field'] == "parent_status" )
    {
        $parent_status = 1;
    }
    if ( $row['Field'] == "taxidentification" )
    {
        $taxidentification = 1;
    }
}
if ( $usr_ridflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `rid` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $usr_bonusbalanceflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `bonusbalance` FLOAT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $firstname_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `firstname` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $lastname_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `lastname` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $phone_no_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `phone_no` BIGINT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $address_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `address` LONGTEXT {$charset_collation} ;" );
    echo __Func010__( );
}
if ( $balancestatus_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `balancestatus` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $common_account_id == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `common_account_id` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $parent_status == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `parent_status` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $taxidentification == 0 )
{
    mysql_query( "ALTER TABLE `ppc_users` ADD `taxidentification` varchar(255) NOT NULL DEFAULT '';" );
    echo __Func010__( );
}
$table = "location_country";
$flag_credittext_bordercolor = 0;
$flag_location_country = 0;
$flag_publisher_credit = 0;
$flag_ppc_banner_sizes = 0;
$flag_ppc_ad_block = 0;
$flag_email_templates = 0;
$flag_site_content = 0;
$flag_terms = 0;
$tables = __Func006__( $mysql_dbname );
while ( list( $temp ) = temp )
{
    if ( $temp == $table )
    {
        $flag_location_country = 1;
    }
    if ( $temp == "ppc_publisher_credits" )
    {
        $flag_publisher_credit = 1;
    }
    if ( $temp == "ppc_credittext_bordercolor" )
    {
        $flag_credittext_bordercolor = 1;
    }
    if ( $temp == "ppc_banner_sizes" )
    {
        $flag_ppc_banner_sizes = 1;
    }
    if ( $temp == "ppc_ad_block" )
    {
        $flag_ppc_ad_block = 1;
    }
    if ( $temp == "email_templates" )
    {
        $flag_email_templates = 1;
    }
    if ( $temp == "site_content" )
    {
        $flag_site_content = 1;
    }
    if ( $temp == "terms_and_conditions" )
    {
        $flag_terms = 1;
    }
}
if ( $flag_location_country == 0 )
{
    mysql_query( "
    CREATE TABLE IF NOT EXISTS `location_country` (
    	`code` varchar(2) NOT NULL default '',
    	`name` varchar(255)  NOT NULL default '',
    	PRIMARY KEY  (`code`)
    ) ENGINE=InnoDB collate utf8_unicode_ci ;" );
    mysql_query( "
    INSERT INTO `location_country` (`code`, `name`) VALUES\r\n\t('A1', 'Anonymous Proxy'),\r\n\t('A2', 'Satellite Provider'),\r\n\t('AD', 'Andorra'),\r\n\t('AE', 'United Arab Emirates'),\r\n\t('AF', 'Afghanistan'),\r\n\t('AG', 'Antigua and Barbuda'),\r\n\t('AI', 'Anguilla'),\r\n\t('AL', 'Albania'),\r\n\t('AM', 'Armenia'),\r\n\t('AN', 'Netherlands Antilles'),\r\n\t('AO', 'Angola'),\r\n\t('AP', 'Asia/Pacific Region'),\r\n\t('AQ', 'Antarctica'),\r\n\t('AR', 'Argentina'),\r\n\t('AS', 'American Samoa'),\r\n\t('AT', 'Austria'),\r\n\t('AU', 'Australia'),\r\n\t('AW', 'Aruba'),\r\n\t('AX', 'Aland Islands'),\r\n\t('AZ', 'Azerbaijan'),\r\n\t('BA', 'Bosnia and Herzegovina'),\r\n\t('BB', 'Barbados'),\r\n\t('BD', 'Bangladesh'),\r\n\t('BE', 'Belgium'),\r\n\t('BF', 'Burkina Faso'),\r\n\t('BG', 'Bulgaria'),\r\n\t('BH', 'Bahrain'),\r\n\t('BI', 'Burundi'),\r\n\t('BJ', 'Benin'),\r\n\t('BM', 'Bermuda'),\r\n\t('BN', 'Brunei Darussalam'),\r\n\t('BO', 'Bolivia'),\r\n\t('BR', 'Brazil'),\r\n\t('BS', 'Bahamas'),\r\n\t('BT', 'Bhutan'),\r\n\t('BV', 'Bouvet Island'),\r\n\t('BW', 'Botswana'),\r\n\t('BY', 'Belarus'),\r\n\t('BZ', 'Belize'),\r\n\t('CA', 'Canada'),\r\n\t('CC', 'Cocos (Keeling) Islands'),\r\n\t('CD', 'Congo, The Democratic Republic of the'),\r\n\t('CF', 'Central African Republic'),\r\n\t('CG', 'Congo'),\r\n\t('CH', 'Switzerland'),\r\n\t('CI', 'Cote d''Ivoire'),\r\n\t('CK', 'Cook Islands'),\r\n\t('CL', 'Chile'),\r\n\t('CM', 'Cameroon'),\r\n\t('CN', 'China'),\r\n\t('CO', 'Colombia'),\r\n\t('CR', 'Costa Rica'),\r\n\t('CU', 'Cuba'),\r\n\t('CV', 'Cape Verde'),\r\n\t('CX', 'Christmas Island'),\r\n\t('CY', 'Cyprus'),\r\n\t('CZ', 'Czech Republic'),\r\n\t('DE', 'Germany'),\r\n\t('DJ', 'Djibouti'),\r\n\t('DK', 'Denmark'),\r\n\t('DM', 'Dominica'),\r\n\t('DO', 'Dominican Republic'),\r\n\t('DZ', 'Algeria'),\r\n\t('EC', 'Ecuador'),\r\n\t('EE', 'Estonia'),\r\n\t('EG', 'Egypt'),\r\n\t('EH', 'Western Sahara'),\r\n\t('ER', 'Eritrea'),\r\n\t('ES', 'Spain'),\r\n\t('ET', 'Ethiopia'),\r\n\t('EU', 'Europe'),\r\n\t('FI', 'Finland'),\r\n\t('FJ', 'Fiji'),\r\n\t('FK', 'Falkland Islands (Malvinas)'),\r\n\t('FM', 'Micronesia, Federated States of'),\r\n\t('FO', 'Faroe Islands'),\r\n\t('FR', 'France'),\r\n\t('GA', 'Gabon'),\r\n\t('GB', 'United Kingdom'),\r\n\t('GD', 'Grenada'),\r\n\t('GE', 'Georgia'),\r\n\t('GF', 'French Guiana'),\r\n\t('GG', 'Guernsey'),\r\n\t('GH', 'Ghana'),\r\n\t('GI', 'Gibraltar'),\r\n\t('GL', 'Greenland'),\r\n\t('GM', 'Gambia'),\r\n\t('GN', 'Guinea'),\r\n\t('GP', 'Guadeloupe'),\r\n\t('GQ', 'Equatorial Guinea'),\r\n\t('GR', 'Greece'),\r\n\t('GS', 'South Georgia and the South Sandwich Islands'),\r\n\t('GT', 'Guatemala'),\r\n\t('GU', 'Guam'),\r\n\t('GW', 'Guinea-Bissau'),\r\n\t('GY', 'Guyana'),\r\n\t('HK', 'Hong Kong'),\r\n\t('HM', 'Heard Island and McDonald Islands'),\r\n\t('HN', 'Honduras'),\r\n\t('HR', 'Croatia'),\r\n\t('HT', 'Haiti'),\r\n\t('HU', 'Hungary'),\r\n\t('ID', 'Indonesia'),\r\n\t('IE', 'Ireland'),\r\n\t('IL', 'Israel'),\r\n\t('IM', 'Isle of Man'),\r\n\t('IN', 'India'),\r\n\t('IO', 'British Indian Ocean Territory'),\r\n\t('IQ', 'Iraq'),\r\n\t('IR', 'Iran, Islamic Republic of'),\r\n\t('IS', 'Iceland'),\r\n\t('IT', 'Italy'),\r\n\t('JE', 'Jersey'),\r\n\t('JM', 'Jamaica'),\r\n\t('JO', 'Jordan'),\r\n\t('JP', 'Japan'),\r\n\t('KE', 'Kenya'),\r\n\t('KG', 'Kyrgyzstan'),\r\n\t('KH', 'Cambodia'),\r\n\t('KI', 'Kiribati'),\r\n\t('KM', 'Comoros'),\r\n\t('KN', 'Saint Kitts and Nevis'),\r\n\t('KP', 'Korea, Democratic People''s Republic of'),\r\n\t('KR', 'Korea, Republic of'),\r\n\t('KW', 'Kuwait'),\r\n\t('KY', 'Cayman Islands'),\r\n\t('KZ', 'Kazakhstan'),\r\n\t('LA', 'Lao People''s Democratic Republic'),\r\n\t('LB', 'Lebanon'),\r\n\t('LC', 'Saint Lucia'),\r\n\t('LI', 'Liechtenstein'),\r\n\t('LK', 'Sri Lanka'),\r\n\t('LR', 'Liberia'),\r\n\t('LS', 'Lesotho'),\r\n\t('LT', 'Lithuania'),\r\n\t('LU', 'Luxembourg'),\r\n\t('LV', 'Latvia'),\r\n\t('LY', 'Libyan Arab Jamahiriya'),\r\n\t('MA', 'Morocco'),\r\n\t('MC', 'Monaco'),\r\n\t('MD', 'Moldova, Republic of'),\r\n\t('ME', 'Montenegro'),\r\n\t('MG', 'Madagascar'),\r\n\t('MH', 'Marshall Islands'),\r\n\t('MK', 'Macedonia'),\r\n\t('ML', 'Mali'),\r\n\t('MM', 'Myanmar'),\r\n\t('MN', 'Mongolia'),\r\n\t('MO', 'Macao'),\r\n\t('MP', 'Northern Mariana Islands'),\r\n\t('MQ', 'Martinique'),\r\n\t('MR', 'Mauritania'),\r\n\t('MS', 'Montserrat'),\r\n\t('MT', 'Malta'),\r\n\t('MU', 'Mauritius'),\r\n\t('MV', 'Maldives'),\r\n\t('MW', 'Malawi'),\r\n\t('MX', 'Mexico'),\r\n\t('MY', 'Malaysia'),\r\n\t('MZ', 'Mozambique'),\r\n\t('NA', 'Namibia'),\r\n\t('NC', 'New Caledonia'),\r\n\t('NE', 'Niger'),\r\n\t('NF', 'Norfolk Island'),\r\n\t('NG', 'Nigeria'),\r\n\t('NI', 'Nicaragua'),\r\n\t('NL', 'Netherlands'),\r\n\t('NO', 'Norway'),\r\n\t('NP', 'Nepal'),\r\n\t('NR', 'Nauru'),\r\n\t('NU', 'Niue'),\r\n\t('NZ', 'New Zealand'),\r\n\t('OM', 'Oman'),\r\n\t('PA', 'Panama'),\r\n\t('PE', 'Peru'),\r\n\t('PF', 'French Polynesia'),\r\n\t('PG', 'Papua New Guinea'),\r\n\t('PH', 'Philippines'),\r\n\t('PK', 'Pakistan'),\r\n\t('PL', 'Poland'),\r\n\t('PM', 'Saint Pierre and Miquelon'),\r\n\t('PN', 'Pitcairn'),\r\n\t('PR', 'Puerto Rico'),\r\n\t('PS', 'Palestinian Territory'),\r\n\t('PT', 'Portugal'),\r\n\t('PW', 'Palau'),\r\n\t('PY', 'Paraguay'),\r\n\t('QA', 'Qatar'),\r\n\t('RE', 'Reunion'),\r\n\t('RO', 'Romania'),\r\n\t('RS', 'Serbia'),\r\n\t('RU', 'Russian Federation'),\r\n\t('RW', 'Rwanda'),\r\n\t('SA', 'Saudi Arabia'),\r\n\t('SB', 'Solomon Islands'),\r\n\t('SC', 'Seychelles'),\r\n\t('SD', 'Sudan'),\r\n\t('SE', 'Sweden'),\r\n\t('SG', 'Singapore'),\r\n\t('SH', 'Saint Helena'),\r\n\t('SI', 'Slovenia'),\r\n\t('SJ', 'Svalbard and Jan Mayen'),\r\n\t('SK', 'Slovakia'),\r\n\t('SL', 'Sierra Leone'),\r\n\t('SM', 'San Marino'),\r\n\t('SN', 'Senegal'),\r\n\t('SO', 'Somalia'),\r\n\t('SR', 'Suriname'),\r\n\t('ST', 'Sao Tome and Principe'),\r\n\t('SV', 'El Salvador'),\r\n\t('SY', 'Syrian Arab Republic'),\r\n\t('SZ', 'Swaziland'),\r\n\t('TC', 'Turks and Caicos Islands'),\r\n\t('TD', 'Chad'),\r\n\t('TF', 'French Southern Territories'),\r\n\t('TG', 'Togo'),\r\n\t('TH', 'Thailand'),\r\n\t('TJ', 'Tajikistan'),\r\n\t('TK', 'Tokelau'),\r\n\t('TL', 'Timor-Leste'),\r\n\t('TM', 'Turkmenistan'),\r\n\t('TN', 'Tunisia'),\r\n\t('TO', 'Tonga'),\r\n\t('TR', 'Turkey'),\r\n\t('TT', 'Trinidad and Tobago'),\r\n\t('TV', 'Tuvalu'),\r\n\t('TW', 'Taiwan'),\r\n\t('TZ', 'Tanzania, United Republic of'),\r\n\t('UA', 'Ukraine'),\r\n\t('UG', 'Uganda'),\r\n\t('UM', 'United States Minor Outlying Islands'),\r\n\t('US', 'United States'),\r\n\t('UY', 'Uruguay'),\r\n\t('UZ', 'Uzbekistan'),\r\n\t('VA', 'Holy See (Vatican City State)'),\r\n\t('VC', 'Saint Vincent and the Grenadines'),\r\n\t('VE', 'Venezuela'),\r\n\t('VG', 'Virgin Islands, British'),\r\n\t('VI', 'Virgin Islands, U.S.'),\r\n\t('VN', 'Vietnam'),\r\n\t('VU', 'Vanuatu'),\r\n\t('WF', 'Wallis and Futuna'),\r\n\t('WS', 'Samoa'),\r\n\t('YE', 'Yemen'),\r\n\t('YT', 'Mayotte'),\r\n\t('ZA', 'South Africa'),\r\n\t('ZM', 'Zambia'),\r\n\t('ZW', 'Zimbabwe') ;" );
    echo __Func010__( );
}
mysql_query( "
	CREATE TABLE IF NOT EXISTS `ppc_clicks` (
		`id` int(11) NOT NULL auto_increment,
		`uid` int(11) NOT NULL default '0',
		`aid` int(11) NOT NULL default '0',
		`kid` int(11) NOT NULL default '0',
		`clickvalue` float NOT NULL default '0',
		`ip` varchar(255) NOT NULL default '',
		`time` int(11) NOT NULL default '0',
		PRIMARY KEY  (`id`)
	) ENGINE=InnoDB collate utf8_unicode_ci  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$pidflag = 0;
$publisher_profit_flag = 0;
$clk_rid_flag = 0;
$pub_ref_profit_flag = 0;
$clk_adv_rid_flag = 0;
$adv_ref_profit_flag = 0;
$bidflag = 0;
$vidflag = 0;
$countryflag = 0;
$ctime = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_clicks`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "pid" )
    {
        $pidflag = 1;
    }
    if ( $row['Field'] == "publisher_profit" )
    {
        $publisher_profit_flag = 1;
    }
    if ( $row['Field'] == "pub_rid" )
    {
        $clk_rid_flag = 1;
    }
    if ( $row['Field'] == "pub_ref_profit" )
    {
        $pub_ref_profit_flag = 1;
    }
    if ( $row['Field'] == "adv_rid" )
    {
        $clk_adv_rid_flag = 1;
    }
    if ( $row['Field'] == "adv_ref_profit" )
    {
        $adv_ref_profit_flag = 1;
    }
    if ( $row['Field'] == "bid" )
    {
        $bidflag = 1;
    }
    if ( $row['Field'] == "vid" )
    {
        $vidflag = 1;
    }
    if ( $row['Field'] == "country" )
    {
        $countryflag = 1;
    }
    if ( $row['Field'] == "current_time" )
    {
        $ctime = 1;
    }
}
if ( $pidflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `pid` INT DEFAULT '0' NOT NULL;" );
    echo __Func010__( );
}
if ( $publisher_profit_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `publisher_profit` FLOAT DEFAULT '0.0' NOT NULL;" );
    echo __Func010__( );
}
if ( $clk_rid_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `pub_rid` INT DEFAULT '0' NOT NULL;" );
    echo __Func010__( );
}
if ( $pub_ref_profit_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `pub_ref_profit` FLOAT DEFAULT '0.0' NOT NULL;" );
    echo __Func010__( );
}
if ( $clk_adv_rid_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `adv_rid` INT DEFAULT '0' NOT NULL;" );
    echo __Func010__( );
}
if ( $adv_ref_profit_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `adv_ref_profit` FLOAT DEFAULT '0.0' NOT NULL;" );
    echo __Func010__( );
}
if ( $bidflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `bid` INT DEFAULT '0' NOT NULL;" );
    echo __Func010__( );
}
if ( $vidflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `vid` INT DEFAULT '0' NOT NULL;" );
    echo __Func010__( );
}
if ( $countryflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `country`  VARCHAR( 2 ) NOT NULL default '' " );
    echo __Func010__( );
}
if ( $ctime == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD `current_time`  int( 11 ) NOT NULL default '0' " );
    echo __Func010__( );
}
mysql_query( "
	CREATE TABLE IF NOT EXISTS `ppc_ads` (
		`id` int(11) NOT NULL auto_increment,
		`uid` int(11) NOT NULL default '0',
		`link` longtext NOT NULL,
		`title` varchar(255) default NULL,
		`summary` longtext NOT NULL,
		`maxamount` float NOT NULL default '0',
		`amountused` float NOT NULL default '0',
		`createtime` int(11) NOT NULL default '0',
		`status` int(11) NOT NULL default '0',
		`bannersize` int(11) default NULL,
		`adtype` int(11) NOT NULL default '0',
		PRIMARY KEY  (`id`)
	) ENGINE=InnoDB collate utf8_unicode_ci  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$pausestatus = 0;
$displayurlflag = 0;
$updatedtimeflag = 0;
$wapstatusflag = 0;
$adlanguage = 0;
$adname = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_ads`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "displayurl" )
    {
        $displayurlflag = 1;
    }
    if ( $row['Field'] == "updatedtime" )
    {
        $updatedtimeflag = 1;
    }
    if ( $row['Field'] == "pausestatus" )
    {
        $pausestatus = 1;
    }
    if ( $row['Field'] == "wapstatus" )
    {
        $wapstatusflag = 1;
    }
    if ( $row['Field'] == "adlang" )
    {
        $adlanguage = 1;
    }
    if ( $row['Field'] == "name" )
    {
        $adname = 1;
    }
}
if ( $displayurlflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `displayurl` LONGTEXT {$charset_collation} ;" );
    echo __Func010__( );
}
if ( $updatedtimeflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `updatedtime` int not null default '0';" );
    mysql_query( "update `ppc_ads` set updatedtime=createtime" );
    echo __Func010__( );
}
if ( $pausestatus == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `pausestatus` int not null default '0';" );
    echo __Func010__( );
}
if ( $wapstatusflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `wapstatus` int not null default '0';" );
    echo __Func010__( );
}
if ( $adlanguage == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `adlang` int not null default '0';" );
    echo __Func010__( );
}
if ( $adname == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `name` varchar(100)  not null default '';" );
    echo __Func010__( );
}
mysql_query( "
	CREATE TABLE IF NOT EXISTS `inout_ppc_ipns` (
		`ipnid` int(20) NOT NULL auto_increment,
		`txnid` varchar(50) NOT NULL default '',
		`userid` int(2) NOT NULL default '0',
		`result` enum('0','1') NOT NULL default '0',
		`resultdetails` varchar(50) NOT NULL default '',
		`itemname` varchar(50) NOT NULL default '',
		`itemnumber` varchar(25) NOT NULL default '',
		`amount` float NOT NULL default '0',
		`currency` varchar(10) NOT NULL default '',
		`payeremail` varchar(50) NOT NULL default '',
		`receiveremail` varchar(50) NOT NULL default '',
		`paymenttype` varchar(25) NOT NULL default '',
		`verified` varchar(25) NOT NULL default '',
		`status` varchar(25) NOT NULL default '',
		`pendingreason` varchar(25) NOT NULL default '',
		`fullipn` text NOT NULL,
		`receivedat` datetime NOT NULL default '0000-00-00 00:00:00',
		`timestamp` timestamp(14) NOT NULL,
		PRIMARY KEY  (`ipnid`),
		KEY `result` (`result`),
		KEY `userid` (`userid`)
	) ENGINE=InnoDB collate utf8_unicode_ci  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "
	CREATE TABLE IF NOT EXISTS `ppc_public_service_ads` (
		`id` int(11) NOT NULL auto_increment,`link` longtext NOT NULL,
		`title` varchar(255) default NULL,
		`summary` longtext NOT NULL,
		`createtime` int(11) NOT NULL default '0',
		`bannersize` int(11) default '0',
		`adtype` int(11) NOT NULL default '0',
		`status` int(11) NOT NULL default '1',
		`displayurl` longtext,
		`lastacesstime` int(11) NOT NULL default '0',
		PRIMARY KEY  (`id`)
	) ENGINE=InnoDB collate utf8_unicode_ci  AUTO_INCREMENT=1 ;" );

$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_public_service_ads`" );
$wapstatusflag = 0;
$adl = 0;
$public_name = 0;
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "wapstatus" )
    {
        $wapstatusflag = 1;
    }
    if ( $row['Field'] == "adlang" )
    {
        $adl = 1;
    }
    if ( $row['Field'] == "name" )
    {
        $public_name = 1;
    }
}
if ( $wapstatusflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_public_service_ads` ADD `wapstatus` int not null default '0';" );
    echo __Func010__( );
}
if ( $adl == 0 )
{
    mysql_query( "ALTER TABLE `ppc_public_service_ads` ADD `adlang` int not null default '0';" );
    echo __Func010__( );
}
if ( $public_name == 0 )
{
    mysql_query( "ALTER TABLE `ppc_public_service_ads` ADD `name` VARCHAR(100)  not null default '';" );
    echo __Func010__( );
}
if ( $flag_publisher_credit == 0 )
{
    mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_publisher_credits` (\r\n\t  `id` int(11) NOT NULL auto_increment,\r\n\t  `credit` varchar(255) NOT NULL default '',\r\n\t  PRIMARY KEY  (`id`)\r\n\t) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
    echo __Func010__( );
    mysql_query( "insert into ppc_publisher_credits values('0','Ads by adserver');" );
}
$languageid = 0;
$parentid = 0;
$credittypeflag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_publisher_credits`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "language_id" )
    {
        $languageid = 1;
    }
    if ( $row['Field'] == "parent_id" )
    {
        $parentid = 1;
    }
    if ( $row['Field'] == "credittype" )
    {
        $credittypeflag = 1;
    }
}
if ( $languageid == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_credits` ADD `language_id` int(11) NOT NULL default '0';" );
    echo __Func010__( );
}
if ( $parentid == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_credits` ADD `parent_id` int(11) NOT NULL default '0';" );
    echo __Func010__( );
}
if ( $credittypeflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_credits` ADD `credittype` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_publisher_payment_details` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL default '0',\r\n  `paymentmode` int(11) NOT NULL default '0',\r\n  `paypalemail` varchar(255) default NULL,\r\n  `payeename` varchar(255) default NULL,\r\n  `address1` longtext,\r\n  `address2` longtext,\r\n  `city` varchar(255) default NULL,\r\n  `state` varchar(255) default NULL,\r\n  `country` varchar(255) default NULL,\r\n  `zip` varchar(255) default NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$acc_no = 0;
$routing_no = 0;
$acc_type = 0;
$bank_name = 0;
$bank_address = 0;
$b_city = 0;
$b_state = 0;
$b_country = 0;
$b_zip = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_publisher_payment_details` " );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "acc_no" )
    {
        $acc_no = 1;
    }
    if ( $row['Field'] == "routing_no" )
    {
        $routing_no = 1;
    }
    if ( $row['Field'] == "acc_type" )
    {
        $acc_type = 1;
    }
    if ( $row['Field'] == "bank_name" )
    {
        $bank_name = 1;
    }
    if ( $row['Field'] == "bank_address" )
    {
        $bank_address = 1;
    }
    if ( $row['Field'] == "b_city" )
    {
        $b_city = 1;
    }
    if ( $row['Field'] == "b_state" )
    {
        $b_state = 1;
    }
    if ( $row['Field'] == "b_country" )
    {
        $b_country = 1;
    }
    if ( $row['Field'] == "b_zip" )
    {
        $b_zip = 1;
    }
}
if ( $acc_no == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`  ADD `acc_no` LONGTEXT {$charset_collation} ;" );
    echo __Func010__( );
}
if ( $routing_no == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`  ADD `routing_no` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $acc_type == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`   add `acc_type` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $bank_name == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`   add `bank_name` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $bank_address == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`  ADD `bank_address` LONGTEXT {$charset_collation} ;" );
    echo __Func010__( );
}
if ( $b_city == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`  ADD `b_city` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $b_state == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`  ADD `b_state` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $b_country == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`  ADD `b_country` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $b_zip == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_details`  ADD `b_zip` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_publisher_payment_hist` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL default '0',\r\n  `reqdate` int(11) NOT NULL default '0',\r\n  `paymode` int(11) NOT NULL default '0',\r\n  `txid` varchar(255) default NULL,\r\n  `payeedetails` varchar(255) NOT NULL default '',\r\n  `payerdetails` longtext NOT NULL,\r\n  `amount` float NOT NULL default '0',\r\n  `currency` varchar(255) NOT NULL default '',\r\n  `status` int(11) NOT NULL default '0',\r\n  `processdate` int(11) default NULL,\r\n  `address1` longtext NOT NULL,\r\n  `address2` longtext NOT NULL,\r\n  `city` varchar(255) NOT NULL default '',\r\n  `state` varchar(255) NOT NULL default '',\r\n  `country` varchar(255) NOT NULL default '',\r\n  `zip` varchar(255) NOT NULL default '',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_publisher_payment_hist`" );
$bank_name = 0;
$bank_address = 0;
$acc_no = 0;
$routing_no = 0;
$acc_type = 0;
$b_city = 0;
$b_state = 0;
$b_country = 0;
$b_zip = 0;
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "bank_name" )
    {
        $bank_name = 1;
    }
    if ( $row['Field'] == "bank_address" )
    {
        $bank_address = 1;
    }
    if ( $row['Field'] == "acc_no" )
    {
        $acc_no = 1;
    }
    if ( $row['Field'] == "routing_no" )
    {
        $routing_no = 1;
    }
    if ( $row['Field'] == "acc_type" )
    {
        $acc_type = 1;
    }
    if ( $row['Field'] == "b_city" )
    {
        $b_city = 1;
    }
    if ( $row['Field'] == "b_state" )
    {
        $b_state = 1;
    }
    if ( $row['Field'] == "b_country" )
    {
        $b_country = 1;
    }
    if ( $row['Field'] == "b_zip" )
    {
        $b_zip = 1;
    }
}
if ( $bank_name == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `bank_name` varchar(255) not null default '';" );
    echo __Func010__( );
}
if ( $bank_address == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `bank_address` longtext not null  default '' ;" );
    echo __Func010__( );
}
if ( $acc_no == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `acc_no` longtext NOT NULL   default '';" );
    echo __Func010__( );
}
if ( $routing_no == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `routing_no` varchar(255) NOT NULL default '';" );
    echo __Func010__( );
}
if ( $acc_type == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `acc_type` varchar(255) NOT NULL default '';" );
    echo __Func010__( );
}
if ( $b_city == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `b_city` varchar(255) NOT NULL default '';" );
    echo __Func010__( );
}
if ( $b_state == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `b_state` varchar(255) NOT NULL default '';" );
    echo __Func010__( );
}
if ( $b_country == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `b_country` varchar(255) NOT NULL default '';" );
    echo __Func010__( );
}
if ( $b_zip == 0 )
{
    mysql_query( "ALTER TABLE `ppc_publisher_payment_hist` ADD `b_zip` varchar(255) NOT NULL default '';" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_restricted_sites` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL default '0',\r\n  `site` varchar(255) NOT NULL default '',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( " CREATE TABLE  IF NOT EXISTS `ppc_fraud_clicks` (\r\n`id` INT NOT NULL AUTO_INCREMENT ,\r\n`aid` INT NOT NULL ,\r\n`pid` INT NOT NULL DEFAULT '0',\r\n`clicktime` INT NOT NULL ,\r\n`ip` VARCHAR( 255 ) NOT NULL ,\r\n`publisherfraudstatus` INT NOT NULL  DEFAULT '0',\r\nPRIMARY KEY ( `id` )\r\n)ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$usr_bidflag = 0;
$usr_vidflag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_fraud_clicks`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "bid" )
    {
        $usr_bidflag = 1;
    }
    if ( $row['Field'] == "vid" )
    {
        $usr_vidflag = 1;
    }
}
if ( $usr_bidflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_fraud_clicks` ADD `bid` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $usr_vidflag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_fraud_clicks` ADD `vid` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $flag_terms == 0 )
{
    mysql_query( "  CREATE TABLE IF NOT EXISTS `terms_and_conditions` (\r\n`id` INT NOT NULL AUTO_INCREMENT ,\r\n`terms` LONGTEXT NOT NULL ,\r\n`type` INT NOT NULL ,\r\nPRIMARY KEY ( `id` )\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
    echo __Func010__( );
    mysql_query( " INSERT INTO `terms_and_conditions` (`id`, `terms`, `type`) VALUES\r\n(1, 'This Agreement shall govern participation in the {ENGINE_NAME} advertising program. By participating in the Program, you will be deemed to have agreed to these Terms and Conditions. The term ''customer'' shall refer to any individual or entity who accepts the terms and conditions of this Agreement by submitting the Program registration.\r\nPAYMENT: \r\n\tAll Clients are required to pay one-hundred percent (100%) of the cost of the campaign before its activation. {ENGINE_NAME}  reserves the right to set and negotiate specific payment terms on a client-by-client basis. \r\n\r\nREGISTRATION:\r\n        To use the Service, You must submit a complete {ENGINE_NAME} registration form, which is available at {ADV_LOGIN_PATH}. As part of the registration process for the Service, You agree to:\r\n\tProvide certain limited information about you as prompted to do so by the Service (such information to be current, complete and accurate) \r\n\tMaintain and update this information as required to keep it current, complete and accurate. The information requested on original signup shall be referred to as registration data (\"Registration Data\"). \r\n        If {ENGINE_NAME}  discovers that any of your registration data is inaccurate, incomplete or not current,{ENGINE_NAME}  may terminate your right to access and receive the service immediately with or without notice. {ENGINE_NAME} will evaluate the registration data in good faith and will notify you in a timely manner regarding acceptance or rejection. {ENGINE_NAME}  may reject a registration application if it determines, in its sole discretion that the user is not an appropriate subscriber or user of the Service. Upon acceptance of this agreement and completion of the registration process You will have opened an account with {ENGINE_NAME} and will become a subscriber to the {ENGINE_NAME} advertising service.\r\n\r\nPARTICIPATION:\r\n       {ENGINE_NAME}  shall have absolute discretion as to whether or not it accepts a particular customer or site for participation in the {ENGINE_NAME}  network.\r\n\r\n1. {ENGINE_NAME} will not direct visitors to sites containing content that is not appropriate for viewing by a general audience. The following are examples (including but not limited to) of the type of sites we will not direct visitors to:\r\n>>Sites with child pornography or sites that contain links to such content \r\n>>Sites containing or linking to software piracy \r\n>>Sites containing or linking to any form of illegal activity (i.e., how to build a bomb, hacking, ''phreaking'', etc.) \r\n>>Sites with gratuitous displays of violence, obscene or vulgar language, and abusive content or content which endorses or threatens physical harm \r\n>>Sites promoting any type of hate-mongering (i.e., racial, political, ethnic, religious, gender-based, sexuality-based or personal, etc.) \r\n>>Sites that participate in or transmit inappropriate newsgroup postings or unsolicited e-mail (spam) \r\n>>Sites promoting any type of illegal substance or activity sites with illegal, false or deceptive investment advice and money-making opportunities \r\n>>Sites with any type of content reasonable public consensus deem to be improper or inappropriate \r\n\r\n2. If your site is changed during the campaign such that it includes inappropriate content, {ENGINE_NAME}  may stop your campaign immediately. In this case, you will not be entitled to a refund.\r\nData Reporting (Stats): All campaigns purchased on the {ENGINE_NAME}  network are served, tracked and reported by {ENGINE_NAME}. {ENGINE_NAME}  is the sole owner of all website, campaign, and aggregate web user data collected by {ENGINE_NAME} . Customer only has access to website and web user data that is collected as part of customer''s campaign. \r\nPayment Liability: {ENGINE_NAME} reserves the right to hold customer and any agency, broker or other authorized representative of customer jointly and severally liable for all amounts owed to {ENGINE_NAME}. \r\n\r\nGENERAL: \r\nRepresentations and Warranties: Customer hereby indemnifies and holds harmless {ENGINE_NAME} and the advertisers (including their successors, directors, officers, employees, agents, assigns) from and against all claims, loss, liability, damage and expense of any nature (including attorneys'' reasonable fees) in connection with the Advertisement URL and its linked websites, for any actual or alleged breach of any term of this Agreement. \r\nDamages: In no event shall either party be liable for special, indirect, incidental, or consequential damages, including, but not limited to, loss of data, loss of use, or loss of profits arising there under or from the provision of services. \r\nLimitation of Liability: Neither {ENGINE_NAME}  nor the participating sites will be subject to any liability whatsoever for :\r\n\tAny failures to provide reference or access to all or any part of the Advertisement URL due to systems failures or other technological failures of {ENGINE_NAME}  or of the Internet; \r\n\tDelays in delivery and/or non-delivery of a campaign, including, without limitation, difficulties with a third-party server, or electronic malfunction; \r\n\tErrors in content or omissions in any Advertisement URLs provided by Customer.\r\n\r\nMODIFICATION:\r\nThe Program reserves the right to change any of these terms and conditions at any time without notice. You are responsible for complying with any changes to the terms and conditions within 10 days of the date of change.', 0),\r\n(2, '{ENGINE_NAME}   and Publisher, enter into this Publisher Network Agreement  to establish the terms and conditions by which Publisher may enter the {ENGINE_NAME} Network and display advertisements on behalf of {ENGINE_NAME} Customers . \r\n\r\nDEFINITIONS\r\n\r\nAdvertiser means {ENGINE_NAME} and/or the advertiser or advertising agency providing Ads to {ENGINE_NAME} for use on Publisher''s Website(s) as specified herein. \r\nApproved Websites means the Publisher''s domain(s) and/or specific root URLs and or blogs approved by {ENGINE_NAME} .\r\n{ENGINE_NAME} Network means {ENGINE_NAME}''s affiliated group of third-party Websites by which {ENGINE_NAME} may insert Ads. \r\n{ENGINE_NAME} System refers to the collection of software, including ad serving and measurement technologies that {ENGINE_NAME} uses to provide services to both advertisers and publishers.\r\nClick, Click-Thru or Click-Through means the activation of a hyperlink using a mouse or other input device. The click-through is essential to the interactivity of online advertising. \r\nClick-Through Rate or CTR means the rate of activated ads to total ads displayed. \r\nImpressions means the number of times an Ad is served to, and received by, a unique visitor on Publisher''s Website or other media as measured by {ENGINE_NAME} . \r\nIncentivized Traffic means a Website where Ads are placed where Users have some sort of incentive to click through on Ads.\r\nNetwork IP means the Ads, {ENGINE_NAME} Code or other intellectual property made available to Publisher in connection with its performance under this Agreement.\r\nProhibited Conduct means conduct, during the course of performance of this Agreement that is listed or related to the proscribed conduct listed in Section Publisher Media shall mean the Website, search engine or other electronic media on which Publisher places Ads.\r\nUnique Click means the number of times, as recorded by {ENGINE_NAME} server, a User viewing Publisher''s Media, as identified by a cookie or IP address, clicks on a Creative, provided however, that a click on a specific Creative by a particular User shall only be counted as a Unique Click once every 24-hour period.\r\nUser means any person accessing Publisher Media.  \r\nWebsite means an HTML document containing a set of information available via the Internet.\r\n{ENGINE_NAME} AND PUBLISHER AGREE AS FOLLOWS:\r\n1. {ENGINE_NAME} Network\r\n(a)  Membership: Membership in the Network is subject to prior approval by {ENGINE_NAME} . {ENGINE_NAME} reserves the right to refuse service to any new or existing Publisher for any reason, in its sole discretion. Approval of membership in the Network is limited only to the domains and/or specific root URLs for which Publisher has applied for approval by {ENGINE_NAME} . {ENGINE_NAME} reserves the right, in its sole discretion and without liability, to reject, omit or exclude any Publisher or Website for any reason at any time with or without notice to the Publisher and regardless of whether such Publisher or Website was previously accepted. Without limiting the foregoing, {ENGINE_NAME} reserves the right to require a potential or existing Publisher to submit detailed descriptions or explanations of the Publisher Website(s) or application(s) functionality and back-end technology through a questionnaire or survey. Refusal to participate or answers deemed unsatisfactory constitutes grounds for non-acceptance or termination from the Network. This Agreement is voidable by {ENGINE_NAME} immediately if Publisher fails to disclose, conceals or misrepresents itself in any way. Unless otherwise advised due to technological issues by {ENGINE_NAME} , any person, Publisher, or affiliated group may have only one account, however, each account may include multiple Websites/domains. In the event that a Publisher signs up more than one Website/domain, and it has been approved by {ENGINE_NAME}, each and every additional Website/domain is obligated and bound by these same terms and conditions.  In any event, {ENGINE_NAME} reserves the right to reject or approve any additional Website(s), and is under no obligation to accept any Website(s), even if the additional Website(s) is the property of an already approved Publisher.  All activity for a given account will be consolidated into one report. \r\n\r\n(b)  {ENGINE_NAME} Websites: For purposes of this Agreement, all Websites that are owned, operated or hosted by or on behalf of {ENGINE_NAME}, are referred to herein collectively as the \"{ENGINE_NAME} Websites.\" You agree that you will not use the {ENGINE_NAME} Websites or any content therein or data obtained therefrom for any purposes other than to fulfill this Agreement and that you will not disseminate any of the information contained on {ENGINE_NAME} Websites, without prior consent from {ENGINE_NAME} . You agree that you will not use any automated means, including, without limitation, agents, robots, scripts, or spiders, to access or manage your account with {ENGINE_NAME} or to monitor or copy the {ENGINE_NAME} Websites or the content contained therein except via automated means expressly made available by {ENGINE_NAME} , if any, or authorized in advance and in writing by {ENGINE_NAME} (for example, {ENGINE_NAME} -approved third-party tools and services). You will not interfere or attempt to interfere with the proper working of the {ENGINE_NAME} Websites or any program thereon, or the {ENGINE_NAME} System. Without limitation to the foregoing, you further agree that you will not take any action that imposes an unreasonable or disproportionately large load on the {ENGINE_NAME} Websites, any programs thereon, or {ENGINE_NAME} ''s infrastructure, as determined by {ENGINE_NAME} .\r\n\r\n(c) Services: Publisher understands and agrees that from time to time the {ENGINE_NAME} System hereunder may be inaccessible, unavailable or inoperable for any reason, including, without limitation: (i) equipment malfunctions; (ii) periodic maintenance procedures or repairs which {ENGINE_NAME} may undertake from time to time; or (iii) causes beyond the control of {ENGINE_NAME} or which are not reasonably foreseeable by {ENGINE_NAME}, including, without limitation, interruption or failure of telecommunication or digital transmission links, hostile network attacks, the unavailability, operation, or inaccessibility of Websites or interfaces, network congestion or other failures. While {ENGINE_NAME} will attempt to provide the services on a continuous basis, Publisher acknowledges and agrees that {ENGINE_NAME} has no control over the availability of the services on a continuous or uninterrupted basis. Publisher also understands and agrees that {ENGINE_NAME} is not responsible for the functionality of any third-party Website or interface. Terms of this Agreement are subject to {ENGINE_NAME} hardware, software, and bandwidth traffic limitations. Failure to deliver because of technical difficulties does not represent a failure to meet the obligations of this Agreement. {ENGINE_NAME} reserves the right to discontinue offering any of the {ENGINE_NAME} Systems and/or {ENGINE_NAME} Websites at any time. Except as otherwise specified by {ENGINE_NAME}, Publisher agrees that it will direct all communications relating to any {ENGINE_NAME} Website or your participation therein directly to {ENGINE_NAME} and not to any other entity.\r\n2. Website Content and Prohibited Conduct \r\n(a) Pre-approval Required: Publishers that have Websites that relate to or have any characteristic of the following shall be approved on a case by case basis: (i) excessive ads, app  quest/test, user content (blogs, etc.), (ii) controversial issues, religion, sexual orientation and/or edgy humor, (iii) wrestling, (iv) anime, (v) old content, and/or (vi) poor quality design and functionality.\r\n\r\n(b) Prohibited Conduct: {ENGINE_NAME} does not accept Websites that produce, relate to or have characteristics of Prohibited Conduct. Prohibited Conduct is defined as: \r\n(i) Ad Placement & Tracking: Publisher shall not: (1) Place Creatives or Ads in emails without prior consent and tracking from {ENGINE_NAME}; (2) Intentionally place Creatives on blank web pages or on web pages with no content; (3) Stack Creatives (e.g. place on top of one another so that more than 2 ads are next to each other); (4) Place Creatives on non-approved Websites or web pages, or in such a fashion that may be deceptive to the User; (5) Incentivize offers or create the appearance to incentivize offers; (6) Place statements near the Ads requesting that Users \"click\" on the Ad (i.e., \"Please click here\") or \"visit\" the sponsor (i.e., \"Please visit our sponsor\"); (7) Place misleading statements near the Ad (i.e., \"You will win \$5,000.\"); (8) Redirect traffic to a Website other than that listed by the particular Advertiser; (9) Ask Users to take advantage of other Ads or offers other than those listed by the particular Advertiser; (10) Serve Creatives, or drive traffic to such Creatives, using any downloadable applications without the prior written approval of {ENGINE_NAME}, which, if provided, is subject in each case to the following condition: Creatives delivered in approved downloadable applications may only be shown once per User session when the application is active, enabled and clearly recognizable by the end User as being active and enabled. Serving Creatives at anytime when the downloaded application is not active is strictly prohibited and grounds for immediate termination without pay; (11) Use invisible methods to generate impressions, clicks, or transactions that are not initiated by the affirmative action of the end-user; (12) Attempt in any way to alter, modify, eliminate, conceal, or otherwise render inoperable or ineffective the Website tags, source codes, links, pixels, modules or other data provided by or obtained from {ENGINE_NAME} that allows {ENGINE_NAME} to measure ad performance and provide its service.\r\n(ii) Websites: Publisher shall not place any Creative, Ads or Network IP on Web Sites that contain, promote, reference or have links to: (1) profanity, sexually explicit materials, hate material, promote violence, discrimination based on race, sex, religion, nationality, disability, sexual orientation, age, or family status, or any other materials deemed unsuitable or harmful to the reputation of {ENGINE_NAME}; (2) software piracy (warez, cracking, etc.), hacking, phreaking, emulators, ROMs, or illegal MP3 activity.; (3) illegal activities, deceptive practices or violations of the intellectual property or privacy rights of others; (4) Websites under construction, hosted by a free service, personal home pages, or do not own the domain they are under; (5) charity clicks/donations, paid to surf, personal Websites, Website applicants who are not the owner of or employed by the applying Website, active x downloads, no content (link site), all affiliate links, or incentivized traffic; (6) Promote activities generally understood as Internet abuse, including but not limited to, the sending of unsolicited bulk electronic mail or the use of Spyware.  For purposes hereof, Spyware shall mean computer programs or tools that (i) alter a computer Users browser or other settings or use an ActiveX control or similar device to download ad supporting software without providing fair notice to and obtaining affirmative consent; (ii) prevent a computer Users reasonable efforts to block the installation of or disable or remove unwanted software; (iii) remove or disable any security, anti-spyware or anti-virus technology on a Users computer; (iv) send email through a Users computer without prior authorization; (v) open multiple, sequential, stand-alone advertisements in the consumers Internet browser which cannot be closed without closing the Internet browser or shutting down the computer or (vi) other similar activities that are prohibited by applicable law.\r\n \r\n(iii). Search & Miscellaneous: Publisher shall not: (1) Violate guidelines of any search engines being utilized; (2) Engage in search engine spam, doorway pages, cloaking, etc.; (3) Bid on any trademarked name or terms in any PPC/keyword/adword/campaign; (4) Conduct search Ads falsely suggesting a link between {ENGINE_NAME} and a third- party or otherwise infringing on a third-partys intellectual property rights; (5) Engage in any advertising via facsimile or telemarketing; (6) Engage in any misleading or deceptive conduct.\r\n3. Ad Content and Placement \r\n(a) Compliance with Industry Standards: Publisher agrees to undertake and complete the services as specified by the {ENGINE_NAME} Network, including all Ad placement restrictions or channels specified, in accordance with the highest industry standards. Publisher shall position the Ads in such a manner to assure that they are fully and clearly visible to consumers and displayed in a similar manner as other merchants included in the Website.  \r\n\r\n(b) No Modifications to Creative, Code or Network IP: Except as permitted under this Agreement, Publisher may not alter, copy, modify, take, sell, re-use, or divulge in any manner any Creative, Network IP or computer code provided by {ENGINE_NAME} without {ENGINE_NAME}s prior written consent. Publisher may not copy {ENGINE_NAME}''s Ads and display them from Publisher''s Website directly; redirect traffic to a Website other than that listed by {ENGINE_NAME} or the Advertiser; or ask Users to take advantage of other Ads or offers other than those listed by {ENGINE_NAME} or Advertiser.  Any {ENGINE_NAME} content which is copied, changed or altered without prior written consent will result in non-payment for the campaign and may result in termination. Any approved modifications to {ENGINE_NAME} Code or Network IP shall be owned solely by {ENGINE_NAME}. \r\n\r\n(c) Requirements: Publisher shall be solely responsible for (i) managing its advertising content exclusions in the {ENGINE_NAME} interface, and (ii) placing Ads on the Publisher Media, which placement shall be subject to the terms and conditions of this Agreement. Ads may only be placed on Approved Websites. {ENGINE_NAME} pop-up or pop-under window cannot be launched from Websites that launch more than a total of one pop window, including the {ENGINE_NAME} pop.  Skyscrapers or wide skyscrapers and half page formats cannot be placed on the same page. Publisher will not place ads on blank pages, on pages with no content, on top of one another, on non-approved Websites, or in such a fashion that may be deceptive to the visitor.  Publisher agrees to use the {ENGINE_NAME} Code provided by {ENGINE_NAME} for displaying an Ad not more than ONCE per page view.  Placement of ads in email must be done after notifying {ENGINE_NAME} so appropriate tracking measures can be put in place.  \r\n \r\n(d) Default Ads: Publisher acknowledges and agrees that {ENGINE_NAME} may not be able to fill 100% of advertising requests sent to its servers with paying Ads. If Publisher chooses not to specify a default redirect ad, {ENGINE_NAME} will display so-called ''house'' and/or ''AdCouncil'' ads on Publisher''s Website when paid advertising is unavailable. {ENGINE_NAME} may also display so-called ''house'' and/or ''AdCouncil'' ads on Publisher''s Websites when technical difficulties require it. So-called ''house'' and ''AdCouncil'' ads are not paid advertising. Under no circumstances does {ENGINE_NAME} guarantee to provide any percent fill of paid advertising to a Website.\r\n4. Network Quality\r\n{ENGINE_NAME} will not tolerate or accept any activities it deems harmful or potentially damaging to its reputation and/or business, or that of its customers and/or clients including but not limited to the activities stated in this Agreement. {ENGINE_NAME} employs individuals for the express purpose of monitoring the Publisher''s Websites within our network to ensure that our customers and clients are receiving the highest quality campaigns. {ENGINE_NAME} has also developed an advanced anti-fraud system and regularly audits Publisher''s traffic. Publishers that commit fraudulent activities, including false clicks, false impressions, and incentivized clicks, will have their account permanently removed from the Network and may not be compensated for fraudulent traffic.  {ENGINE_NAME} has several fraud mechanisms at its disposal that will detect most forms within a few days of the initial activity. All Creatives must be served from a {ENGINE_NAME} server or serving location, or through a {ENGINE_NAME} approved 3rd-party-hosted server. Stored images that are loaded from a different location will not count towards any statistic or payment. \r\n5. Proprietary Rights\r\n(a) Licenses: At the agreed upon pay-out price and provided that Publisher complies with all provisions of this Agreement, {ENGINE_NAME} hereby grants to Publisher a nonexclusive, limited, revocable license to use, execute, and display the Network IP solely for purposes of performing its other obligations hereunder. Except for the limited license expressly granted in this Section, nothing in this Agreement shall be construed as {ENGINE_NAME} granting Publisher any right, title or interest in Network IP.  Publisher acknowledges and agrees that {ENGINE_NAME} and/or Advertiser owns all right, title and interest in and to the Network IP and all related intellectual and proprietary rights of any kind anywhere in the world.  Publishers use of the Network IP or the results created thereby, or disseminating or distributing any of this information except as expressly permitted by this Agreement is strictly forbidden and will result in the termination of this limited license and may result in Publisher being held liable under applicable law.  \r\n\r\n(b) Intellectual Property Ownership: Subject to the limited licenses granted to {ENGINE_NAME} and Publisher hereunder, each party shall own and shall retain all right, title and interest in its trade names, logos, trademarks, service marks, trade dress, Internet domain names, copyrights, patents, trade secrets, know how and proprietary technology, including, without limitation, those trade names, logos, trademarks, service marks, trade dress, copyrights, patents, testimonials, endorsements, know how, trade secrets and proprietary technology currently used or which may be developed and/or used by it in the future (\"Intellectual Property\").  Except as provided in this Agreement, neither party may distribute, sell, reproduce, publish, display, perform, prepare derivative works or otherwise use any of the Intellectual Property of the other party without the express prior written consent of such party.  \r\n\r\n(c) Data Ownership: Publisher understands that all data, including, but not limited to, personally identifiable information provided by Users in response to an Ad and/or any or all reports, results, and/or information created, compiled, analyzed and/or derived by {ENGINE_NAME} from such data is the sole and exclusive property of Advertiser and {ENGINE_NAME} and is considered Confidential Information pursuant to this Agreement. {ENGINE_NAME} and/or its Advertisers, in their sole discretion, shall have the right to market and re-market the User(s) and or data without further obligation to Publisher.  Publisher shall not make any use of, copy, make derivative works from, sell, transfer, lease, assign, redistribute, disclose, disseminate, or otherwise make available in any manner, such information, or any portion thereof, to any third-party. Unless otherwise agreed to in writing by the parties, any other use of such information is strictly prohibited.  \r\n6. Representations and Warranties\r\n(a) Publisher Responsibility: The parties hereby acknowledge that Publisher is solely responsible for the method of dissemination of the campaigns, and that {ENGINE_NAME} will not have any control over the method of dissemination and is relying entirely on these warranties made by Publisher.\r\n\r\n(b) Publisher Warranties: Publisher represents, warrants, covenants and acknowledges that (i) it will provide and maintain the resources, personnel and facilities suitable to perform its obligations under the Agreement; (ii) it will comply with all applicable federal (national), state and local laws and regulations including, without limitation, laws relating to advertising, the Internet, privacy and unfair business practices; (iii) it will not engage in Prohibited Conduct; (iv) it will comply with {ENGINE_NAME}s Privacy Policy as amended from time to time; (v) that Publisher is at least 18 years of age on the effective date of this Agreement; and (vi) that {ENGINE_NAME} does not make any specific or implied promises as to the successful outcome of any campaigns.\r\n\r\n(c) Mutual Warranties: Each party represents and warrants to the other that (i) it has the full right, power, legal capacity, and authority to enter into, deliver and fully perform under this Agreement; (ii) neither the execution, delivery, nor performance of this Agreement will result in a violation or breach of any contract, agreement, order, judgment, decree, rule, regulation or law to which such party is bound; and (iii) such party acknowledges that the other party makes no representations, warranties, or agreements related to the subject matter hereof that are not expressly provided for in the Agreement.\r\n7. Privacy \r\n(a) Obligations: Internet consumer privacy is of paramount importance to {ENGINE_NAME}, its subsidiaries, and its customers. {ENGINE_NAME} is committed to protecting the privacy of consumers, clients, and Advertisers, and to do its part to maintain the integrity of the Internet. Publisher therefore affirms and attests that it will adhere to fair information collection practices with respect to its performance under this Agreement.  \r\n\r\n(b) Privacy Requirements: Publisher agrees to the following and must clearly post on its Website an easy to understand privacy policy that (i) is in compliance with all federal (national, in the US compliant with FTC) guidelines and any other applicable laws, rules and regulations with respect to online privacy; (ii) identifies the nature and scope of the collection and use of information gathered by Publisher and offers the User an opportunity to opt out from such collection and use of the data; and (iii) contain language materially similar to the following:\r\n\"We have contracted with {ENGINE_NAME} to monitor certain pages of our website for the purpose of reporting website traffic, statistics, advertisement ''click-throughs'', and/or other activities on our website. Where authorized by us, {ENGINE_NAME} may use cookies, web beacons, and/or other monitoring technologies to compile anonymous statistics about our website visitors. {ENGINE_NAME} may use this data and statistics to track users and serve advertising based on the collected data and statistics.   However, no personally identifiable information is collected by or transferred to any party other than the Advertiser.\r\n(c) Cookies: Publisher acknowledges that (i) cookies are important devices for measuring advertising effectiveness and ensuring a robust online advertising industry and (ii) efforts are required to increase User awareness about the use of cookies and their role in providing free content and other benefits to Users.  Publisher agrees to take such steps as may be commercially reasonable and appropriate to promote User awareness about cookies or similar devices as may be identified by {ENGINE_NAME}.  \r\n8. Payment \r\n(a) Payment Rate: {ENGINE_NAME} reserves the right to set campaign rates, which may vary with market conditions.  Do not invoice {ENGINE_NAME}; all Publisher invoices are discarded. Publishers will be paid at the account level.  All unpaid earnings will rollover to the next pay period. All payments are based on actuals as defined, accounted and audited by {ENGINE_NAME}. {ENGINE_NAME} reserves the absolute right to withhold payment from accounts or Publishers that violate any of the terms and conditions set forth herein. {ENGINE_NAME} will determine, in its sole discretion, whether acts or omissions are deceptive, fraudulent or violate this Agreement. . Examples of such acts may include, without limitation, clicks without referring URLs, extraordinary high numbers of repeat clicks, and clicks from non-approved root URLs.\r\n\r\n(b) Breach or Fraud: If any Publisher violates or refuses to fulfill its responsibilities, or commits fraudulent activity, {ENGINE_NAME} reserves the right to withhold payment and take appropriate legal action.\r\n(c) Calculation: Calculation of Publisher earnings, including Impressions and click through numbers, shall be in {ENGINE_NAME}s sole discretion. In the event Publisher disagrees with any such calculation, Publisher shall immediately send a written request to {ENGINE_NAME} detailing, with specificity, Publisher''s concerns. Thereafter, {ENGINE_NAME} will provide Publisher with an explanation or, if such calculations are determined by {ENGINE_NAME} to be incorrect, an adjustment. {ENGINE_NAME}s calculations shall be final and binding. \r\n9. Indemnity \r\nPublisher is solely responsible for any legal liability arising out of or relating to (i) Publisher''s Website(s), (ii) any material to which Users can link through Publisher''s Website(s), and/or (iii) any consumer and/or governmental/regulatory complaint arising out of any campaign conducted by Publisher, including but not limited to any spam or fraud complaint and/or any complaint relating to failure to have proper permission to conduct such campaign to the consumer. Publisher shall indemnify, defend, and hold harmless {ENGINE_NAME} and its officers, directors, employees, agents, shareholders, partners, affiliates, representatives, agents and Advertisers (collectively {ENGINE_NAME} Parties) harmless from and against any and all allegations, claims, actions, causes of action, lawsuits, damages, liabilities, obligations, costs and expenses (including without limitation reasonable attorneys fees, costs related to in-house counsel time, court costs and witness fees) (collectively Losses) incurred by, or imposed or asserted against, the {ENGINE_NAME} Parties which, if true, would constitute or relate to any claims, suits, or proceedings for (a) libel, defamation, violation of rights of privacy or publicity, copyright infringement, trademark infringement or other infringement of any third-party right, fraud, false advertising, misrepresentation, product liability or violation of any law, statute, ordinance, rule or regulation throughout the world in connection with Publisher''s Website(s); (b) any breach by Publisher of any duty, representation or warranty under this Agreement; (c) any breach by {ENGINE_NAME} of any duty, representation, or warranty to provide Ad(s) for placement on Publisher''s Website(s) due to any breach by Publisher of this Agreement; (d) a contaminated file, virus, worm, or Trojan horse originating from the Publisher''s Website(s); or (e) gross negligence or willful misconduct by Publisher.\r\n10. Limitations of Warranties and Liability\r\n(a) Disclaimer of Warranties: ALL SERVICES PROVIDED BY THE {ENGINE_NAME} ARE PROVIDED ON AN AS IS AS AVAILABLE BASIS.  TO THE FULLEST EXTENT PERMISSIBLE PURSUANT TO APPLICABLE LAW, {ENGINE_NAME} MAKES NO WARRANTIES, GUARANTEES, REPRESENTATIONS, PROMISES, STATEMENTS, ESTIMATES, CONDITIONS, OR OTHER INDUCEMENTS, EXPRESS, IMPLIED, ORAL, WRITTEN, OR OTHERWISE EXCEPT AS EXPRESSLY SET FORTH HEREIN.  {ENGINE_NAME} IS NOT RESPONSIBLE FOR DELAYS CAUSED BY ACCIDENT, WAR, ACT OF GOD, EMBARGO, COMPUTER SYSTEM FAILURE, OR ANY OTHER CIRCUMSTANCE BEYOND ITS CONTROL.\r\n\r\n(b) Limitation of Liability: UNDER NO CIRCUMSTANCES SHALL {ENGINE_NAME} BE LIABLE TO PUBLISHER FOR INDIRECT, INCIDENTAL, CONSEQUENTIAL, SPECIAL OR EXEMPLARY DAMAGES (EVEN IF {ENGINE_NAME} HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES), ARISING FROM ANY ASPECT OF THE ADVERTISING RELATIONSHIP PROVIDED HEREIN. IN NO EVENT SHALL {ENGINE_NAME}S TOTAL OBLIGATIONS OR LIABILITY HEREUNDER EXCEED THE LESSER OF THE SPECIFIC ADVERTISING CAMPAIGN IN QUESTION OR ONE THOUSDAND DOLLARS (\$1,000.00).  REGARDLESS OF ANY LAW TO THE CONTRARY, NO ACTION, SUIT OR PROCEEDING SHALL BE BROUGHT AGAINST {ENGINE_NAME} MORE THAN ONE (1) YEAR AFTER THE DATE UPON WHICH THE CLAIM AROSE. \r\n\r\n(c) Consideration: PUBLISHER ACKNOWLEDGES THAT {ENGINE_NAME} HAS AGREED TO PRICING IN RELIANCE UPON THE LIMITATIONS OF LIABILITY AND THE DISCLAIMERS OF WARRANTIES AND DAMAGES SET FORTH HEREIN, AND THAT THESE CONSIDERATIONS FORM AN ESSENTIAL BASIS OF THE BARGAIN BETWEEN THE PARTIES.  PUBLISHER AGREES THAT THE LIMITATIONS AND EXCLUSIONS OF LIABILITY AND DISCLAIMERS SPECIFIED IN THESE TERMS WILL SURVIVE AND APPLY EVEN IF FOUND TO HAVE FAILED OF THEIR ESSENTIAL PURPOSE. SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF CERTAIN WARRANTIES OR LIABILITIES, SO SOME OF THE ABOVE EXCLUSIONS MAY NOT APPLY TO PUBLISHER.\r\n11. Term and Termination \r\n(a) Termination: This Agreement, as may be amended, applies to Publisher for as long as Publisher distributes Ads for {ENGINE_NAME}. {ENGINE_NAME} reserves the right to terminate any Publisher from the Network at any time, with or without cause.   \r\n(b) Post-termination: Upon termination, Publisher agrees to immediately remove from the Websites any and all {ENGINE_NAME} Code and Network IP supplied to Publisher by {ENGINE_NAME}. Publisher will be paid, in the next scheduled payment cycle following termination, all legitimate, non-fraudulently accrued, earnings due up to the time of termination. Upon termination all ties to referrals will be permanently severed and Publisher will not receive nor be entitled to receive future referral commissions hereunder.\r\n12. Confidentiality      \r\nEach party agrees that it may provide the other with information that is confidential and proprietary to that party or a third- party, as is designated by the disclosing party or that is reasonably understood to be proprietary and/or confidential (\"Confidential Information\"). {ENGINE_NAME}s campaign rates are considered confidential.  Each party may use Confidential Information received from the other party only in connection with and to further the purposes of this Agreement. Confidential Information shall not be commingled with information or materials of others and any copies shall be strictly controlled. The receiving party agrees to make commercially reasonable efforts, but in no case no less effort than it uses to protect its own Confidential Information, to maintain the confidentiality of and to protect any proprietary interests of the disclosing party. Confidential Information shall not include (even if designated by a party) information: (i) that is or becomes part of the public domain through no act or omission of the receiving party; (ii) that is lawfully received by the receiving party from a third-party without restriction on use or disclosure and without breach of this Agreement or any other agreement without knowledge by the receiving party of any breach of fiduciary duty, or (iii) that the receiving party had in its possession prior to the date of this Agreement. Upon termination of this Agreement, or upon written request by {ENGINE_NAME}, Publisher must destroy or return to {ENGINE_NAME} any Confidential Information provided by {ENGINE_NAME} under this Agreement.\r\n13. Choice of Law and Attorneys Fees \r\nThis Agreement is governed by the laws of the State of Delaware (USA), except for its conflict of law provisions. The exclusive forum for any actions related to this Agreement shall be in the state courts, and, to the extent that federal courts have exclusive jurisdiction, in Delaware.  The parties consent to such venue and jurisdiction, waive any right to a trial by jury, and agree to waive the personal service of any process upon them by agreeing that service may be effected by overnight mail (using a commercially recognized service) or by U.S. mail with delivery receipt to the last address provided by Publisher. The application of the United Nations Convention on the International Sale of Goods is expressly excluded.  A party that primarily prevails in an action brought under this Agreement is entitled to recover from the other party its reasonable attorneys fees and costs. \r\n14. Entire Agreement and Modification \r\nThis Agreement, including exhibits, addenda, the {ENGINE_NAME} Privacy Policy (as amended from time to time and which is incorporated herein by reference), contains the entire understanding and agreement of the parties and there have been no promises, representations, agreements, warranties or undertakings by either of the parties, either oral or written, except as stated in this Agreement. This Agreement may only be altered, amended or modified by an instrument that is assented to by each party to this Agreement by verifiable means, including without limitation by written instrument signed by the parties or through a \"click through\" acknowledgement of assent. No interlineations to this Agreement shall be binding unless initialed by both parties. Notwithstanding the foregoing, {ENGINE_NAME} shall have the right to change, modify or amend (\"Change\") this Agreement, in whole or in part, by posting a revised Agreement at least five (5) days prior to the effective date of such Change.  Publishers continued use of the Network after the effective date of such Change shall be deemed Publishers acceptance of the revised Agreement.  No change, amendment, or modification of any provision of the Agreement by Publisher will be valid unless set forth in a written instrument signed by an executive of both Parties with the corporate authority to do so.\r\n15. Assignment \r\nNo rights or obligations under this Agreement may be assigned by Publisher without the prior written consent of {ENGINE_NAME}. Any assignment, transfer or attempted assignment or transfer in violation of this Section shall be void and of no force and effect. {ENGINE_NAME} and any of its subsequent assignees may assign this Agreement, in whole or in part, or any of its rights or delegate any of its duties, under this Agreement to any party. This Agreement shall be binding upon and inure to the benefit of the parties hereto and their respective permitted successors and assigns.\r\n16. Independent Contractors \r\nEach party is an independent contractor.  Any intention to create a joint venture or partnership between the parties is expressly disclaimed.  Except as set forth herein, neither party is authorized or empowered to obligate the other or to incur any costs on behalf of the other without the other partys prior written consent.\r\n17. Marketing \r\nPublisher shall not release any information regarding Campaigns, Creatives, or Publishers relationship with {ENGINE_NAME} or its customers, including, without limitation, in press releases or promotional or merchandising materials, without the prior written consent of {ENGINE_NAME}. {ENGINE_NAME} shall have the right to reference and refer to its work for, and relationship with, Publisher for marketing and promotional purposes. No press releases or general public announcements shall be made without the mutual consent of {ENGINE_NAME} and Publisher.\r\n18. Force Majeure \r\nNeither party shall be liable by reason of any failure or delay in the performance of its obligations hereunder for any cause beyond the reasonable control of such party, including but not limited to electrical outages, failure of Internet service providers, default due to Internet disruption (including without limitation denial of service attacks), riots, insurrection, acts of terrorism, war (or similar), fires, flood, earthquakes, explosions, and other acts of God.\r\n19. Survival and Severability \r\nAny obligations which expressly or by their nature are to continue after termination, cancellation, or expiration of the Agreement shall survive and remain in effect after such happening. Each Party acknowledges that the provisions of the Agreement were negotiated to reflect an informed, voluntary allocation between them of all the risks (both known and unknown) associated with the transactions contemplated hereunder. All provisions are inserted conditionally on their being valid in law. In the event that any provision of the Agreement conflicts with the law under which the Agreement is to be construed or if any such provision is held invalid or unenforceable by a court with jurisdiction over the Parties to the Agreement, then (i) such provision will be restated to reflect as nearly as possible the original intentions of the Parties in accordance with applicable law; and (ii) the remaining terms, provisions, covenants, and restrictions of the Agreement will remain in full force and effect.\r\n20. Remedies and Waiver \r\nExcept as otherwise specified, the rights and remedies granted to a party under this Agreement are cumulative and in addition to, not in lieu of, any other rights and remedies which the party may possess at law or in equity. Failure of either party to require strict performance by the other party of any provision shall not affect the first partys right to require strict performance thereafter. Waiver by either party of a breach of any provision shall not waive either the provision itself or any subsequent breach.', 1);" );
}
if ( $flag_site_content == 0 )
{
    mysql_query( "  CREATE TABLE IF NOT EXISTS `site_content` (\r\n`id` INT NOT NULL AUTO_INCREMENT,\r\n`item_name` VARCHAR( 255 ) NOT NULL ,\r\n`item_value` LONGTEXT NOT NULL ,\r\n`item_type` INT NOT NULL ,\r\nPRIMARY KEY ( `id` )\r\n)ENGINE= InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
    echo __Func010__( );
    mysql_query( "INSERT INTO `site_content` (`id`, `item_name`, `item_value`, `item_type`) VALUES\r\n(1, 'meta-keywords', 'pay per click, pay per click advertising, online advertising, internet marketing, internet advertising, marketing, advertising,advertising network', 0),\r\n(2, 'meta-description', 'Pay per click advertising - internet marketing solution for online advertisers.', 0),\r\n(3, 'meta-keywords', 'pay per click,affiliate program,pay per click affiliate,pay per click affiliate program,make money,make money online,make money from your web site,webmasters make money,free affiliate program,online affiliate program,affiliate marketing,affiliate marketing program,webmaster affiliate program,internet affiliate program,ppc,webmaster tools,webmaster resources,website tools,web tools,publisher network', 1),\r\n(4, 'meta-description', 'Pay per click publisher  program for Webmasters - place text ads on your website and generate revenue from your website traffic', 1);" );
}
if ( $mysql->total( "site_content", "id='5'" ) == 0 )
{
    mysql_query( "INSERT INTO `site_content` (`id`, `item_name`, `item_value`, `item_type`) VALUES\r\n(5, 'meta-keywords', 'pay per click,affiliate program, pay per click advertising,pay per click affiliate,pay per click affiliate program, online advertising, internet marketing,make money, internet advertising, marketing, advertising,advertising network,make money online,make money from your web site,webmasters make money,free affiliate program,online affiliate program,affiliate marketing,affiliate marketing program,webmaster affiliate program,internet affiliate program,ppc,webmaster tools,webmaster resources,website tools,web tools,publisher network', 2),\r\n(6, 'meta-description','Pay Per Click advertising - internet marketing solution for online advertisers  and   Pay Per Click publisher  program for Webmasters - place text ads on your website and generate revenue from your website traffic', 2)" );
}
if ( $fresh == 1 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'table_fields_collation', 'utf8_unicode_ci');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ppc_engine_name'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ppc_engine_name', 'Inout Adserver');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='fopenusage'" ) != 0 )
{
    mysql_query( "delete from `ppc_settings` where name='fopenusage';" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='min_user_password_length'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'min_user_password_length', '6');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='default_admaxamount'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'default_admaxamount', '15');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='client_language'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'client_language', 'en');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_title_maxlength'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_title_maxlength', '35');" );
    echo __Func010__( );
}
else
{
    mysql_query( "update `ppc_settings` set value=35 where name='ad_title_maxlength';" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_description_maxlength'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_description_maxlength', '70');" );
    echo __Func010__( );
}
else
{
    mysql_query( "update `ppc_settings` set value=70 where name='ad_description_maxlength';" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='auto_keyword_approve'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'auto_keyword_approve', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='opening_bonus'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'opening_bonus', '10');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='min_user_transaction_amount'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'min_user_transaction_amount', '5');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='min_click_value'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'min_click_value', '0.05');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='paypal_currency'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'paypal_currency', 'USD');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='admin_general_notification_email'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'admin_general_notification_email', 'info@yoursite.com');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='paypal_email'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'paypal_email', 'sales@yoursite.com');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='payapl_payment_item_escription'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'payapl_payment_item_escription', 'Advertiser Account Fund Deposit');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='admin_payment_notification_email'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'admin_payment_notification_email', 'sales@yoursite.com');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='send_mail_after_payment'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'send_mail_after_payment', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='client_email_subject'" ) != 0 )
{
    mysql_query( "delete  from  `ppc_settings` where name='client_email_subject'" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='revenue_booster'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'revenue_booster', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='revenue_boost_level'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'revenue_boost_level', '3');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_displayurl_maxlength'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_displayurl_maxlength', '35');" );
    echo __Func010__( );
}
else
{
    mysql_query( "update `ppc_settings` set value=35 where name='ad_displayurl_maxlength';" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_display_char_encoding'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_display_char_encoding', 'utf-8');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='affiliate_id'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'affiliate_id', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='min_publisher_acc_balance'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'min_publisher_acc_balance', '50');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='publisher_profit'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'publisher_profit', '50');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_ageing_factor'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_ageing_factor', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='page_width'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'page_width', '972px');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='color_theme'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'color_theme', 'green');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_title'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_title', 'This is a dummy ad title.');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_description'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_description', 'This is a dummy description for the dummy ad.');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_display_url'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_display_url', 'DummyAdDisplayURL');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='publisher_checkpayment'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'publisher_checkpayment', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='advertiser_checkpayment'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'advertiser_checkpayment', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='checkpayment_payeename'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'checkpayment_payeename', '');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='checkpayment_payeeaddress'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'checkpayment_payeeaddress', '');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='advertiser_authpayment'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'advertiser_authpayment', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='authpaymentLoginid'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'authpaymentLoginid', '');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='authSecretCode'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'authSecretCode', '');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='authpaymentTransactionKey'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'authpaymentTransactionKey', '');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='keywords_default'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'keywords_default', 'E-business');" );
    $GLOBALS['keywords_default'] = "E-business";
    echo __Func010__( );
}
else if ( $keywords_default == "" )
{
    mysql_query( "update `ppc_settings` set value='E-business' where name='keywords_default';" );
    $GLOBALS['keywords_default'] = "E-business";
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='traffic_analysis'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'traffic_analysis', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='currency_symbol'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'currency_symbol', '\$');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='adserver_upgradation_date'" ) == 0 )
{
    if ( $fresh == 1 )
    {
        mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'adserver_upgradation_date', 0);" );
    }
    else
    {
        $ppc_clicks_table_count = $mysql->echo_one( "select id from ppc_clicks limit 0,1" );
        $ppc_impressions_table_count = $mysql->echo_one( "select id from ppc_impressions limit 0,1" );
        if ( 1 <= $ppc_clicks_table_count && $ppc_impressions_table_count == "" )
        {
            mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'adserver_upgradation_date',".__Func013__( ).");" );
        }
        else
        {
            mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'adserver_upgradation_date', 0);" );
        }
    }
    echo __Func010__( );
}
echo __Func010__( );
if ( $mysql->total( "ppc_settings", "name='fraud_time_interval'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'fraud_time_interval', '24');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='advertiser_minimum_account_balance'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'advertiser_minimum_account_balance', '5');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='local_currency_pay'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'local_currency_pay', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='min_local_currency_pay_amt'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'min_local_currency_pay_amt', '25');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='advertiser_bankpayment'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'advertiser_bankpayment', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='proxy_detection'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'proxy_detection', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='logo_display_option'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'logo_display_option', 'to');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='advertiser_paypalpayment'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'advertiser_paypalpayment', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='single_account_mode'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'single_account_mode', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='system_currency'" ) == 0 )
{
    $pay1 = mysql_query( "select value from ppc_settings where name='paypal_currency'" );
    $pay = __Func011__( $pay1 );
    $paypalvalue = $pay[0];
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'system_currency', '{$paypalvalue}');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='account_migration'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'account_migration', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='publisher_paypalpayment'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'publisher_paypalpayment', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_rotation'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_rotation', '3');" );
    mysql_query( "update ppc_settings set value='1' where name='ad_ageing_factor'" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='ad_keyword_mode'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'ad_keyword_mode', '3');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='advertiser_referral_profit'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES (0, 'ppc', 'advertiser_referral_profit', '10');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='publisher_referral_profit'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES (0, 'ppc', 'publisher_referral_profit', '10');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='referral_system'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES (0, 'ppc', 'referral_system', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='publisher_bankpayment'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES (0, 'ppc', 'publisher_bankpayment', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='portal_system'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES (0, 'ppc', 'portal_system', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='day_of_week'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'day_of_week', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='day_of_month'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'day_of_month', '%e');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='month'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'month', '%b');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='year'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'year', '%y');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='date_separators'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'date_separators', '/');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='datefield_format'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'datefield_format', 'dayamonth');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='hour'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'hour', '%H');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='minute'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'minute', '%M');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='seconds'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'seconds', '%S');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='clock_type'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'clock_type', '12hour');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='time_separator'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'time_separator', ':');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='currency_format'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'currency_format', '\$');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='currency_position'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'currency_position', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='thousand_separator'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'thousand_separator', ',');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='decimal_separator'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'decimal_separator', '.');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='no_of_decimalplaces'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'format', 'no_of_decimalplaces', '2');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='timezone_change'" ) == 0 )
{
    if ( $fresh == 1 )
    {
        $timezone = "GMT";
    }
    else
    {
        $timezone = __Func014__( );
    }
    if ( __Func015__( $timezone, "System/Localtime" ) != 0 )
    {
        mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'timezone_change', '{$timezone}');" );
    }
    else
    {
        mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'timezone_change', 'GMT');" );
    }
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='budget_period'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc_new', 'budget_period', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='adv_status'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc_new', 'adv_status', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='pub_status'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc_new', 'pub_status', '-1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='xml_auth_code'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc_new', 'xml_auth_code', 'admin');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='logo_path'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'logo_path', '');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='wap_title_length'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'wap', 'wap_title_length', '18');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='wap_url_length'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'wap', 'wap_url_length', '20');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='wap_desc_length'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'wap', 'wap_desc_length', '18');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='wap_ad_title'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'wap', 'wap_ad_title', 'Dummy WAP ad title');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='wap_ad_description'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'wap', 'wap_ad_description', 'Dummy WAP ad desc');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='wap_ad_display_url'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'wap', 'wap_ad_display_url', 'DummyWapAdURL');" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `advertiser_bonus_deposit_history` (\r\n      `id` int(11) NOT NULL auto_increment,\r\n      `aid` int(11) NOT NULL default '0',\r\n      `amount` float NOT NULL default '0',\r\n      `type` int(11) NOT NULL default '0',\r\n      `comment` varchar(255) default NULL,\r\n      `logtime` int(11) NOT NULL default '0',\r\n      PRIMARY KEY  (`id`)\r\n    ) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$coupon_id_flag = 0;
$pay_id_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `advertiser_bonus_deposit_history`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "couponid" )
    {
        $coupon_id_flag = 1;
    }
    if ( $row['Field'] == "payid" )
    {
        $pay_id_flag = 1;
    }
}
if ( $coupon_id_flag == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_bonus_deposit_history` ADD `couponid` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $pay_id_flag == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_bonus_deposit_history` ADD `payid` INT NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `advertiser_fund_deposit_history` (\r\n      `id` int(11) NOT NULL auto_increment,\r\n      `uid` int(11) NOT NULL,\r\n      `checkno` varchar(255) NOT NULL,\r\n      `bankname` varchar(100) NOT NULL,\r\n      `accountholdersname` varchar(100) NOT NULL,\r\n      `amount` float NOT NULL default '0',\r\n      `date` int(11) NOT NULL,\r\n      `status` int(11) NOT NULL,\r\n      PRIMARY KEY  (`id`)\r\n    ) ENGINE=InnoDB {$charset_collation}   AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$currency_type = 0;
$bank_address = 0;
$routing_no = 0;
$pay_type = 0;
$comment = 0;
$bank_city = 0;
$bank_country = 0;
$coupon_id_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `advertiser_fund_deposit_history`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "currency_type" )
    {
        $currency_type = 1;
    }
    if ( $row['Field'] == "bank_address" )
    {
        $bank_address = 1;
    }
    if ( $row['Field'] == "routing_no" )
    {
        $routing_no = 1;
    }
    if ( $row['Field'] == "pay_type" )
    {
        $pay_type = 1;
    }
    if ( $row['Field'] == "comment" )
    {
        $comment = 1;
    }
    if ( $row['Field'] == "bank_city" )
    {
        $bank_city = 1;
    }
    if ( $row['Field'] == "bank_country" )
    {
        $bank_country = 1;
    }
    if ( $row['Field'] == "coupon_id" )
    {
        $coupon_id_flag = 1;
    }
}
if ( $currency_type == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_fund_deposit_history`  add `currency_type` INT( 11 ) NOT NULL DEFAULT '1';" );
    echo __Func010__( );
}
if ( $bank_address == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_fund_deposit_history` ADD `bank_address` LONGTEXT {$charset_collation} ;" );
    echo __Func010__( );
}
if ( $routing_no == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_fund_deposit_history` ADD `routing_no` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $pay_type == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_fund_deposit_history`  add `pay_type` INT( 11 ) NOT NULL DEFAULT '1';" );
    echo __Func010__( );
}
if ( $comment == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_fund_deposit_history` ADD `comment` LONGTEXT {$charset_collation} ;" );
    echo __Func010__( );
}
if ( $bank_city == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_fund_deposit_history` ADD `bank_city` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $bank_country == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_fund_deposit_history` ADD `bank_country` VARCHAR(255) {$charset_collation} NOT NULL;" );
    echo __Func010__( );
}
if ( $coupon_id_flag == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_fund_deposit_history` ADD `coupon_id` INT( 11 ) NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
if ( $flag_email_templates == 0 )
{
    mysql_query( "CREATE TABLE IF NOT EXISTS `email_templates` (\r\n      `id` int(11) NOT NULL default '0',\r\n      `email_subject` longtext NOT NULL,\r\n      `email_body` longtext NOT NULL,\r\n      PRIMARY KEY  (`id`)\r\n    ) ENGINE=InnoDB {$charset_collation} ;" );
    echo __Func010__( );
    mysql_query( "\r\nINSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n(1, '{ENGINE_NAME} Advertiser Registration', 'Hi {USERNAME}\r\n\r\nThank you for registering at {ENGINE_NAME}.\r\nYour advertiser account is active.\r\nYour login name is given below.\r\nUsername:{USERNAME}\r\n\r\n<a href=\"{ADV_LOGIN_PATH}\">Click here to login</a>\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(7, '{ENGINE_NAME} advertiser account blocked', 'Hi {USERNAME}\r\n\r\nYour {ENGINE_NAME} advertiser account has been blocked due to one or more of the following reasons.\r\n\r\n1)Posting spam ads.\r\n\r\n2)Posting ads against our terms.\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(8, '{ENGINE_NAME} advertiser account activated', 'Hi {USERNAME}\r\n\r\nYour {ENGINE_NAME}  advertiser account is active now.\r\n<a href=\"{ADV_LOGIN_PATH}\">Click here to login</a>\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(12, '{ENGINE_NAME} Fund Recieved', 'Hi {USERNAME}\r\n\r\n\r\nYou have successfully added funds to {ENGINE_NAME} advertiser account\r\nPayment Mode   : {PAYMODE} \r\nPayment Amount : {PAYMENT_AMOUNT}\r\nBonus Amount  : {BONOUS_AMOUNT}\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(9, '{ENGINE_NAME} ad activated', 'Hi {USERNAME}\r\n\r\nOne of your ad has been activated.\r\n\r\n{AD_ID}\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(10, '{ENGINE_NAME} ad blocked', 'Hi {USERNAME}\r\n\r\nOne of your ad has been blocked.\r\n\r\n{AD_ID}\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(11, '{ENGINE_NAME} ad deleted', 'Hi {USERNAME}\r\n\r\nOne of your ads has been deleted because of one or more of the following reasons\r\n\r\n1)Your ad is a spam ad.\r\n\r\n2)Your ad is against our terms.\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(2, '{ENGINE_NAME}  Publisher Registration', 'Hi {USERNAME}\r\n\r\nThank you for registering at {ENGINE_NAME}.\r\n\r\nYour login name is given below.\r\nUsername:{USERNAME}\r\n\r\nYou account is pending approval.\r\nAdmin will check your request soon.\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(3, '{ENGINE_NAME} Publisher Account Approval', 'Hi {USERNAME}\r\n\r\nYour request for {ENGINE_NAME} publisher account has been approved.\r\n<a href=\"{PUB_LOGIN_PATH}\">Click here to login</a>\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(4, '{ENGINE_NAME} Publisher Account Rejected', 'Hi  {USERNAME}\r\n\r\nWe are temporarily disabling your publisher application due to unavoidable reasons. \r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(14, '{ENGINE_NAME}  publisher account status', 'Hi  {USERNAME}\r\n\r\nWe have identified that you are doing fraud activity with your publisher account.\r\nYour fraud clicks end earnings have been deleted and your account has been blocked.\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(5, '{ENGINE_NAME}  publisher account status', 'Hi  {USERNAME}\r\n\r\nYour {ENGINE_NAME} publisher account has been blocked due to one or more of the following reasons.\r\n\r\n1)Placing ads aginst our terms.\r\n\r\n2)Fraud clicking activity.\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(13, '{ENGINE_NAME} warning', 'Hi  {USERNAME}\r\n\r\nWe have identified that you are doing fraud activity with your publisher account.\r\nYour fraud clicks end earnings have been deleted.\r\nThis is your first and last warning.\r\nIf you further attempt fraud clicks, your account will be blocked.\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(6, '{ENGINE_NAME} publisher account activated', 'Hi {USERNAME}\r\n\r\nYour {ENGINE_NAME} publisher account is active now.\r\n<a href=\"{PUB_LOGIN_PATH}\">Click here to login</a>\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}');\r\n\r\n" );
    echo __Func010__( );
}
if ( $mysql->total( "email_templates", "id='15'" ) == 0 )
{
    mysql_query( " INSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n(15, '{ENGINE_NAME} Account balance running low', 'Hi  {USERNAME}\r\n\r\nYou account balance is running low.\r\nPlease add sufficient funds to your account immediately to ensure that your ads won''t stop showing. \r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(16, '{ENGINE_NAME}  Ad monthly budget crossed', 'Hi  {USERNAME}\r\n\r\nOne of your ads has crossed the monthly budget limit.\r\n\r\nAd Id: {AD_ID}\r\n\r\nPlease consider increasing the budget for the same.\r\n\r\nRegards\r\n{ENGINE_NAME}');" );
    echo __Func010__( );
}
if ( $mysql->total( "email_templates", "id='17'" ) == 0 )
{
    mysql_query( " INSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n(17, '{ENGINE_NAME} Advertiser Account Approval', 'Hi {USERNAME}\r\n\r\nYour request for {ENGINE_NAME} advertiser account has been approved.\r\n<a href=\"{ADV_LOGIN_PATH}\">Click here to login</a>\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(18, '{ENGINE_NAME} Advertiser Account Rejected', 'Hi {USERNAME}\r\n\r\nWe are temporarily disabling your advertiser application due to unavoidable reasons. \r\n\r\nRegards\r\n{ENGINE_NAME}');" );
    echo __Func010__( );
}
if ( $mysql->total( "email_templates", "id='19'" ) == 0 )
{
    mysql_query( " INSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n(19, '{ENGINE_NAME} Advertiser Email Confirmation', 'Hi {USERNAME}\r\n\r\nYour request for {ENGINE_NAME} advertiser account is under verification.\r\n<a href=\"{ADV_CONFIRM_PATH}\">Click here</a>  to confirm your email.\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(20, '{ENGINE_NAME} Publisher Email Confirmation', 'Hi {USERNAME}\r\n\r\nYour request for {ENGINE_NAME} publisher account is under verification.\r\n<a href=\"{PUB_CONFIRM_PATH}\">Click here</a>  to confirm your email.\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}')" );
    echo __Func010__( );
}
if ( $mysql->total( "email_templates", "id='21'" ) == 0 )
{
    mysql_query( " INSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n(21, '{ENGINE_NAME} advertiser account login info', 'Hello, \r\n\r\nYou had requested for resetting your password. Your new login credentials are given below.\r\n\r\nUsername : {USERNAME} \r\n\r\nNew Password : {PASSWORD} \r\n\r\nPlease login and change the the temporary password \r\n\r\nThanks\r\n{ENGINE_NAME}'),\r\n(22, '{ENGINE_NAME} publisher account login info',  'Hello, \r\n\r\nYou had requested for resetting your password. Your new login credentials are given below.\r\n\r\nUsername : {USERNAME} \r\n\r\nNew Password : {PASSWORD} \r\n\r\nPlease login and change the the temporary password \r\n\r\nThanks\r\n{ENGINE_NAME}')" );
    echo __Func010__( );
}
if ( $mysql->total( "email_templates", "id='23'" ) == 0 )
{
    mysql_query( " INSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n\r\n(23, '{ENGINE_NAME} Account login info', 'Hello, \r\n\r\nYou had requested for resetting your password. Your new login credentials are given below.\r\n\r\nUsername  : {USERNAME} \r\n\r\nNew Password : {PASSWORD} \r\n\r\nPlease login and change the the temporary password \r\n\r\nThanks\r\n{ENGINE_NAME}'),\r\n(24, '{ENGINE_NAME} Registration', 'Hi {USERNAME}\r\n\r\nThank you for registering at {ENGINE_NAME}.\r\n\r\nYour login name is given below.\r\nUsername:{USERNAME}\r\n\r\nYour advertiser account is {ADV_STATUS} and publisher account is {PUB_STATUS}.\r\nAdmin will check your request soon.\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}')" );
    echo __Func010__( );
}
if ( $mysql->total( "email_templates", "id='25'" ) == 0 )
{
    mysql_query( " INSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n\r\n(25, '{ENGINE_NAME} Email Confirmation', 'Hi {USERNAME}\r\n\r\nYour request for {ENGINE_NAME} account is under verification.\r\n\r\n<a href=\"{CONFIRM_PATH}\">Click here</a>  to confirm your email.\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}')" );
    echo __Func010__( );
}
if ( $mysql->total( "email_templates", "id='26'" ) == 0 )
{
    mysql_query( " INSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n\r\n(26, '{ENGINE_NAME}  Account blocked', 'Hi {USERNAME}\r\n\r\nYour {ENGINE_NAME} advertiser and publisher accounts have been blocked .\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(27, '{ENGINE_NAME} Account activated', 'Hi {USERNAME}\r\n\r\nYour {ENGINE_NAME} advertiser and publisher accounts are active now.\r\n<a href=\"{LOGIN_PATH}\">Click here to login</a>\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(28, '{ENGINE_NAME} Account Rejected', 'Hi  {USERNAME}\r\n\r\nWe are temporarily disabling your advertiser and publisher applications due to unavoidable reasons. \r\n\r\nRegards\r\n{ENGINE_NAME}'),\r\n(29, '{ENGINE_NAME} Account Approval', 'Hi {USERNAME}\r\n\r\nYour request for {ENGINE_NAME} advertiser and publisher accounts has been approved.\r\n<a href=\"{LOGIN_PATH}\">Click here to login</a>\r\n\r\n\r\nRegards\r\n{ENGINE_NAME}')" );
    echo __Func010__( );
}
if ( $mysql->total( "email_templates", "id='30'" ) == 0 )
{
    mysql_query( " INSERT INTO `email_templates` (`id`, `email_subject`, `email_body`) VALUES\r\n\r\n(30, '{ENGINE_NAME}  Change status', 'Hi {USERNAME}\r\n\r\nYour advertiser account has been {ADV_STATUS} and publisher account has been {PUB_STATUS}.\r\n<a href=\"{LOGIN_PATH}\">Click here to login</a>\r\n\r\nRegards\r\n{ENGINE_NAME}')" );
    echo __Func010__( );
}
echo __Func010__( );
if ( $flag_credittext_bordercolor == 0 )
{
    mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_credittext_bordercolor` (\r\n\t  `id` int(11) NOT NULL auto_increment,\r\n\t  `credit_text_color` varchar(255) NOT NULL,\r\n\t  `border_color` varchar(255) NOT NULL,\r\n\t  PRIMARY KEY  (`id`)\r\n\t) ENGINE=InnoDB {$charset_collation}   AUTO_INCREMENT=1 ;" );
    echo __Func010__( );
    mysql_query( "insert into ppc_credittext_bordercolor values('0','#FFFFFF','#000000')" );
    mysql_query( "insert into ppc_credittext_bordercolor values('0','#000000','#FFFFFF')" );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_ad_block` (\r\n      `id` int(11) NOT NULL auto_increment,\r\n      `width` int(11) NOT NULL,\r\n      `height` int(11) NOT NULL,\r\n      `ad_type` int(11) NOT NULL,\r\n      `max_size` int(11) NOT NULL,\r\n      `orientaion` int(11) NOT NULL,\r\n      `title_font` varchar(255) NOT NULL,\r\n      `title_size` float NOT NULL,\r\n      `title_color` varchar(225) NOT NULL,\r\n      `desc_font` varchar(255) NOT NULL,\r\n      `desc_size` float NOT NULL,\r\n      `desc_color` varchar(225) NOT NULL,\r\n      `url_font` varchar(255) NOT NULL,\r\n      `url_size` float NOT NULL,\r\n      `url_color` varchar(255) NOT NULL,\r\n      `bg_color` varchar(255) NOT NULL,\r\n      `credit_font` varchar(255) NOT NULL,\r\n      `credit_text_border_color` varchar(255) NOT NULL,\r\n      `no_of_text_ads` int(11) NOT NULL,\r\n      `credit_text_alignment` int(11) NOT NULL,\r\n      `credit_text_positioning` int(11) NOT NULL,\r\n      `allow_publishers` int(11) NOT NULL,\r\n      `border_type` int(11) NOT NULL,\r\n      `status` int(11) NOT NULL default '0',\r\n      `ad_font_weight` int(11) NOT NULL,\r\n      `ad_title_decoration` int(11) NOT NULL,\r\n      `ad_desc_font_weight` int(11) NOT NULL,\r\n      `ad_desc_decoration` int(11) NOT NULL,\r\n      `ad_disp_url_font_weight` int(11) NOT NULL,\r\n      `ad_disp_url_decoration` int(11) NOT NULL,\r\n      `credit_text_font_weight` int(11) NOT NULL,\r\n      `credit_text_decoration` int(11) NOT NULL,\r\n      `ad_block_name` varchar(50) NOT NULL,\r\n      `credit_text` varchar(50) NOT NULL,\r\n      `allow_title_color` int(11) NOT NULL,\r\n      `allow_desc_color` int(11) NOT NULL,\r\n      `allow_disp_url_color` int(11) NOT NULL,\r\n      `allow_bg_color` int(11) NOT NULL,\r\n      `allow_credit_color` int(11) NOT NULL,\r\n      `allow_bordor_type` int(11) NOT NULL,\r\n      PRIMARY KEY  (`id`)\r\n    ) ENGINE=InnoDB {$charset_collation}   AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$line_height_flag = 0;
$text_ad_type_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_ad_block`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "line_height" )
    {
        $line_height_flag = 1;
    }
    if ( $row['Field'] == "text_ad_type" )
    {
        $text_ad_type_flag = 1;
    }
}
if ( $line_height_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ad_block`  add `line_height` int(11) NOT NULL default '15';" );
    echo __Func010__( );
}
if ( $text_ad_type_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ad_block`  add `text_ad_type` int(11) NOT NULL default '1';" );
    echo __Func010__( );
}
$credittextid = $mysql->echo_one( "select id from ppc_publisher_credits order by id desc limit 0,1" );
echo __Func010__( );
if ( $flag_ppc_ad_block == 0 )
{
    if ( $flag_ppc_banner_sizes == 0 )
    {
        mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height` ) VALUES\r\n\t\t(13, 728, 90, 3, 100, 2, 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 3, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Leaderboard', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(14, 468, 60, 2, 100, 2, 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 10, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Banner', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(15, 160, 60, 2, 100, 2, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Half Banner', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(16, 120, 600, 3, 100, 1, 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 10, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 4, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Skyscraper', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(17, 160, 600, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 4, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Wide Skyscraper', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(18, 120, 240, 2, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Vertical Banner', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(19, 336, 280, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 3, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Large Rectangle', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(20, 300, 250, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 3, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Medium Rectangle', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(21, 250, 250, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 2, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Square', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(22, 200, 200, 3, 100, 1, 'Arial, Helvetica, sans-serif', 16, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Small Square', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(23, 180, 150, 3, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Small Rectangle', '{$credittextid}', 1, 1, 1, 1, 1, 1,15),\r\n\t\t(24, 125, 125, 2, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Button', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
        echo __Func010__( );
    }
    else
    {
        $lastbannerid = $mysql->echo_one( "select id from ppc_banner_sizes order by id desc limit 0,1" );
        $existing_banners = mysql_query( "select * from ppc_banner_sizes" );
        $new_blocks = array( );
        $new_blocks[0] = array( 728, 90, 3, 2, 3, "Leaderboard" );
        $new_blocks[1] = array( 468, 60, 2, 2, 1, "Banner" );
        $new_blocks[2] = array( 160, 60, 2, 2, 1, "Half Banner" );
        $new_blocks[3] = array( 120, 600, 3, 1, 4, "Skyscraper" );
        $new_blocks[4] = array( 160, 600, 3, 1, 4, "Wide Skyscraper" );
        $new_blocks[5] = array( 120, 240, 2, 1, 1, "Vertical Banner" );
        $new_blocks[6] = array( 336, 280, 3, 1, 3, "Large Rectangle" );
        $new_blocks[7] = array( 300, 250, 3, 1, 3, "Medium Rectangle" );
        $new_blocks[8] = array( 250, 250, 3, 1, 2, "Square" );
        $new_blocks[9] = array( 200, 200, 3, 1, 1, "Small Square" );
        $new_blocks[10] = array( 180, 150, 3, 1, 1, "Small Rectangle" );
        $new_blocks[11] = array( 125, 125, 2, 1, 1, "Button" );
        $i = 0;
        while ( $i < __Func016__( $new_blocks ) )
        {
            __Func017__( $existing_banners, 0 );
            $block_found = 0;
            while ( $exisitng_banner_row = __Func011__( $existing_banners ) )
            {
                if ( $exisitng_banner_row[1] == $new_blocks[$i][0] && $exisitng_banner_row[2] == $new_blocks[$i][1] && $exisitng_banner_row[3] == 100 )
                {
                    mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height`) VALUES\r\n\t\t({$exisitng_banner_row['0']}, {$exisitng_banner_row['1']}, {$exisitng_banner_row['2']}, ".$new_blocks[$i][2].", {$exisitng_banner_row['3']}, ".$new_blocks[$i][3].", 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', ".$new_blocks[$i][4].", 1, 0, 1, 1, 1, 2, 2, 1, 1, 1, 1, 2, 1, '".$new_blocks[$i][5]."', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
                    echo __Func010__( );
                    $block_found = 1;
                    break;
                }
            }
            if ( $block_found == 0 )
            {
                ++$lastbannerid;
                mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`,`line_height` ) VALUES\r\n\t\t({$lastbannerid}, ".$new_blocks[$i][0].", ".$new_blocks[$i][1].", ".$new_blocks[$i][2].", 100, ".$new_blocks[$i][3].", 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', ".$new_blocks[$i][4].", 1, 0, 1, 1, 1, 2, 2, 1, 1, 1, 1, 2, 1, '".$new_blocks[$i][5]."', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
                echo __Func010__( );
            }
            ++$i;
        }
        __Func017__( $existing_banners, 0 );
        while ( $exisitng_banner_row = __Func011__( $existing_banners ) )
        {
            $block_found = 0;
            $i = 0;
            while ( $i < __Func016__( $new_blocks ) )
            {
                if ( $exisitng_banner_row[1] == $new_blocks[$i][0] && $exisitng_banner_row[2] == $new_blocks[$i][1] && $exisitng_banner_row[3] == 100 )
                {
                    $block_found = 1;
                    break;
                }
                ++$i;
            }
            if ( $block_found == 0 )
            {
                mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type` ,  \t`line_height` ) VALUES\r\n\t\t({$exisitng_banner_row['0']}, {$exisitng_banner_row['1']}, {$exisitng_banner_row['2']}, 2 , {$exisitng_banner_row['3']}, 1, 'Arial, Helvetica, sans-serif', 12, '#000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '1', 1, 1, 0, 1, 1, 1, 2, 2, 1, 1, 1, 1, 2, 1, '{$exisitng_banner_row['1']} x {$exisitng_banner_row['2']} - Banner Only', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
                echo __Func010__( );
            }
        }
    }
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_custom_ad_block` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `pid` int(11) NOT NULL,\r\n  `bid` int(11) NOT NULL,\r\n  `name` varchar(100) NOT NULL,\r\n  `title_color` varchar(255) NOT NULL,\r\n  `desc_color` varchar(255) NOT NULL,\r\n  `url_color` varchar(255) NOT NULL,\r\n  `bg_color` varchar(255) NOT NULL,\r\n  `credit_color` varchar(255) NOT NULL,\r\n  `bordor_type` int(11) NOT NULL,\r\n  `status` int(11) NOT NULL,\r\n  `ppc_restricted_sites` varchar(250) NOT NULL,\r\n  `credit_text` int(11) NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}   AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$scroll_ad_flag = 0;
$wapstatus_flag = 0;
$ad_language = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_custom_ad_block`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "scroll_ad" )
    {
        $scroll_ad_flag = 1;
    }
    if ( $row['Field'] == "wapstatus" )
    {
        $wapstatus_flag = 1;
    }
    if ( $row['Field'] == "adlang" )
    {
        $ad_language = 1;
    }
}
if ( $scroll_ad_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_custom_ad_block`  add `scroll_ad` int(11) NOT NULL default '0';" );
    echo __Func010__( );
}
if ( $wapstatus_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_custom_ad_block`  add `wapstatus` int(11) NOT NULL default '0';" );
    echo __Func010__( );
}
if ( $ad_language == 0 )
{
    mysql_query( "ALTER TABLE `ppc_custom_ad_block`  add `adlang` int(11) NOT NULL default '0';" );
    echo __Func010__( );
}
$country_flag = 0;
$ctime = 0;
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_daily_clicks` \r\n(  `id` int(11) NOT NULL auto_increment, \r\n`uid` int(11) NOT NULL default '0', \r\n`aid` int(11) NOT NULL default '0',\r\n `kid` int(11) NOT NULL default '0',\r\n  `clickvalue` float NOT NULL default '0',\r\n  `ip` varchar(255) NOT NULL default '',  \r\n  `time` int(11) NOT NULL default '0', \r\n  `pid` int(11) NOT NULL default '0', \r\n  `publisher_profit` float NOT NULL default '0', \r\n  `pub_rid` int(11) NOT NULL default '0', \r\n  `pub_ref_profit` float NOT NULL default '0',  \r\n  `adv_rid` int(11) NOT NULL default '0', \r\n  `adv_ref_profit` float NOT NULL default '0', \r\n  `bid` int(11) NOT NULL default '0', \r\n  `vid` int(11) NOT NULL default '0', \r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$tblcolums1 = mysql_query( "SHOW COLUMNS FROM `ppc_daily_clicks`" );
while ( $row = __Func008__( $tblcolums1 ) )
{
    if ( $row['Field'] == "country" )
    {
        $country_flag = 1;
    }
    if ( $row['Field'] == "current_time" )
    {
        $ctime = 1;
    }
}
if ( $country_flag != 1 )
{
    mysql_query( "ALTER TABLE `ppc_daily_clicks` ADD `country`  VARCHAR( 2 ) NOT NULL default '' " );
    echo __Func010__( );
}
if ( $ctime != 1 )
{
    mysql_query( "ALTER TABLE `ppc_daily_clicks` ADD `current_time`  int(11) NOT NULL default '0' " );
    echo __Func010__( );
}
$idx_uid_aid = false;
$idx_uid_aid_kid = false;
$idx_pid_bid = false;
$result = mysql_query( "SHOW INDEX FROM ppc_daily_clicks" );
while ( $row = __Func011__( $result ) )
{
    if ( $row[2] == "idx_uid_aid" )
    {
        $idx_uid_aid = true;
    }
    if ( $row[2] == "idx_uid_aid_kid" )
    {
        $idx_uid_aid_kid = true;
    }
    if ( $row[2] == "idx_pid_bid" )
    {
        $idx_pid_bid = true;
    }
}
if ( $idx_uid_aid == true )
{
    mysql_query( "ALTER TABLE `ppc_daily_clicks` DROP INDEX `idx_uid_aid`" );
    echo __Func010__( );
}
if ( $idx_uid_aid_kid == false )
{
    mysql_query( "CREATE  INDEX idx_uid_aid_kid ON ppc_daily_clicks (uid,aid,kid);" );
    echo __Func010__( );
}
if ( $idx_pid_bid == false )
{
    mysql_query( "CREATE  INDEX idx_pid_bid ON ppc_daily_clicks (pid,bid);" );
    echo __Func010__( );
}
$idx_uid_aid_kid = false;
$idx_pid_bid = false;
$result = mysql_query( "SHOW INDEX FROM ppc_clicks" );
while ( $row = __Func011__( $result ) )
{
    if ( $row[2] == "idx_uid_aid_kid" )
    {
        $idx_uid_aid_kid = true;
    }
    if ( $row[2] == "idx_pid_bid" )
    {
        $idx_pid_bid = true;
    }
}
if ( $idx_uid_aid_kid == false )
{
    mysql_query( "CREATE  INDEX idx_uid_aid_kid ON ppc_clicks (uid,aid,kid);" );
    echo __Func010__( );
}
if ( $idx_pid_bid == false )
{
    mysql_query( "CREATE  INDEX idx_pid_bid ON ppc_clicks (pid,bid);" );
    echo __Func010__( );
}
mysql_query( "\r\nCREATE TABLE IF NOT EXISTS `statistics_updation` (\r\n `id` int(11) NOT NULL auto_increment,\r\n `task` varchar(255) NOT NULL,\r\n `e_time` int(11) NOT NULL default '0',\r\n `status` int(11) NOT NULL default '2',\r\n `last_id` int(11) NOT NULL default '0',\r\n PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB  {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
if ( $mysql->total( "statistics_updation", "task='impression_backup'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'impression_backup', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='click_backup'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('click_backup', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='advertiser_daily_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('advertiser_daily_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='publisher_daily_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('publisher_daily_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='advertiser_monthly_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('advertiser_monthly_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='publisher_monthly_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('publisher_monthly_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='advertiser_yearly_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('advertiser_yearly_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='publisher_yearly_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('publisher_yearly_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='last_clearance_time'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('last_clearance_time', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='visits_backup'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('visits_backup', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='refferral_backup'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('refferral_backup', 0, 2, 0);" );
    echo __Func010__( );
}
mysql_query( "\r\nCREATE TABLE IF NOT EXISTS `admin_payment_details` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `name` varchar(255) NOT NULL,\r\n  `value` longtext,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB  {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
if ( $mysql->total( "admin_payment_details", "name='bank_beneficiaryname'" ) == 0 )
{
    mysql_query( "INSERT INTO `admin_payment_details` (`id`, `name`, `value`) VALUES\r\n('0', 'bank_beneficiaryname', ''),\r\n('0', 'bank_account_number', ''),\r\n('0', 'routing_number', ''),\r\n('0', 'bank_name', ''),\r\n('0', 'bank_address', ''),\r\n('0', 'bank_city', ''),\r\n('0', 'bank_province', ''),\r\n('0', 'bank_country', ''),\r\n('0', 'account_type', '');" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS  `advertiser_daily_statistics`\r\n(\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL,\r\n  `aid` int(11) NOT NULL,\r\n  `kid` int(11) NOT NULL,\r\n  `impression` int(11) NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `clk_count` int(11) NOT NULL,\r\n  `money_spent` float NOT NULL,\r\n  `publisher_profit` float NOT NULL,\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS  `advertiser_monthly_statistics`\r\n(\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL,\r\n  `aid` int(11) NOT NULL,\r\n  `kid` int(11) NOT NULL,\r\n  `impression` int(11) NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `clk_count` int(11) NOT NULL,\r\n  `money_spent` float NOT NULL,\r\n  `publisher_profit` float NOT NULL,\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS  `advertiser_yearly_statistics`\r\n(\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL,\r\n  `aid` int(11) NOT NULL,\r\n  `kid` int(11) NOT NULL,\r\n  `impression` int(11) NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `clk_count` int(11) NOT NULL,\r\n  `money_spent` float NOT NULL,\r\n  `publisher_profit` float NOT NULL,\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS  `publisher_daily_statistics`\r\n(\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL,\r\n  `bid` int(11) NOT NULL,\r\n  `impression` int(11) NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `clk_count` int(11) NOT NULL,\r\n  `money_spent` float NOT NULL,\r\n  `publisher_profit` float NOT NULL,\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS  `publisher_monthly_statistics`\r\n(\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL,\r\n  `bid` int(11) NOT NULL,\r\n  `impression` int(11) NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `clk_count` int(11) NOT NULL,\r\n  `money_spent` float NOT NULL,\r\n  `publisher_profit` float NOT NULL,\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS  `publisher_yearly_statistics`\r\n(\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `uid` int(11) NOT NULL,\r\n  `bid` int(11) NOT NULL,\r\n  `impression` int(11) NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `clk_count` int(11) NOT NULL,\r\n  `money_spent` float NOT NULL,\r\n  `publisher_profit` float NOT NULL,\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `publisher_visits_statistics` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `pid` int(11) NOT NULL,\r\n  `page_url` varchar(255) NOT NULL,\r\n  `direct_hits` int(11) NOT NULL default '0',\r\n  `referred_hits` int(11) NOT NULL default '0',\r\n  `direct_clicks` int(11) NOT NULL default '0',\r\n  `referred_clicks` int(11) NOT NULL default '0',\r\n  `direct_impressions` int(11) NOT NULL default '0',\r\n  `referred_impressions` int(11) NOT NULL default '0',\r\n  `direct_invalid_clicks` int(11) NOT NULL default '0',\r\n  `referred_invalid_clicks` int(11) NOT NULL default '0',\r\n  `direct_fraud_clicks` int(11) NOT NULL default '0',\r\n  `referred_fraud_clicks` int(11) NOT NULL default '0',\r\n  `direct_repeated_click` varchar(11) NOT NULL default '0',\r\n  `referred_repeated_click` varchar(11) NOT NULL default '0',\r\n  `time` int(11) NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$serverid_flag = 0;
$tblcolums1 = mysql_query( "SHOW COLUMNS FROM `publisher_visits_statistics`" );
while ( $row = __Func008__( $tblcolums1 ) )
{
    if ( $row['Field'] == "serverid" )
    {
        $serverid_flag = 1;
    }
}
if ( $serverid_flag == 0 )
{
    mysql_query( "ALTER TABLE `publisher_visits_statistics`  add `serverid` INT( 11 ) NOT NULL DEFAULT '0';" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `affiliate_banners` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `height` int(11) NOT NULL,\r\n  `width` int(11) NOT NULL,\r\n  `filename` varchar(255) NOT NULL,\r\n  `level` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `referral_visits` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `ip` varchar(255) default NULL,\r\n  `time` int(11) NOT NULL,\r\n  `rid` int(11) NOT NULL default '0',\r\n  `host_name` varchar(255) default NULL,\r\n  `ref_url` longtext,\r\n  `unique_hits` int(11) NOT NULL default '0',\r\n  `repeated_hits` int(11) NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `referral_daily_visits` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `ip` varchar(255) default NULL,\r\n  `time` int(11) NOT NULL,\r\n  `rid` int(11) NOT NULL default '0',\r\n  `host_name` varchar(255) default NULL,\r\n  `ref_url` longtext,\r\n  `unique_hits` int(11) NOT NULL default '0',\r\n  `repeated_hits` int(11) NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `ad_location_mapping` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `adid` int(11) NOT NULL default '0',\r\n  `country` varchar(10)  NOT NULL default '',\r\n  `region` varchar(10)  default NULL,\r\n  `city` varchar(255)  default NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `daily_referral_statistics` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `pid` int(11) NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `time` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `monthly_referral_statistics` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `pid` int(11) NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `time` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `yearly_referral_statistics` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `pid` int(11) NOT NULL default '0',\r\n  `adv_ref_profit` float NOT NULL default '0',\r\n  `pub_ref_profit` float NOT NULL default '0',\r\n  `time` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
if ( $mysql->total( "statistics_updation", "task='daily_referral_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'daily_referral_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='monthly_referral_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'monthly_referral_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='yearly_referral_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'yearly_referral_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='reset_ads'" ) == 0 )
{
    $currentmonth = __Func018__( 0, 0, 0, __Func019__( "m" ), 1, __Func019__( "y" ) );
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'reset_ads', {$currentmonth}, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='weightage_updation'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'weightage_updation', 0, 2, 0);" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `authorize_ipn` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `x_response_code` int(11) NOT NULL,\r\n  `x_response_subcode` int(11) NOT NULL,\r\n  `x_response_reason_code` int(11) NOT NULL,\r\n  `x_response_reason_text` text {$charset_collation} NOT NULL,\r\n  `x_auth_code` varchar(6) {$charset_collation} NOT NULL,\r\n  `x_trans_id` varchar(20) {$charset_collation} NOT NULL,\r\n  `x_invoice_num` varchar(20) {$charset_collation} NOT NULL,\r\n  `x_description` varchar(255) {$charset_collation} NOT NULL,\r\n  `x_amount` float NOT NULL,\r\n  `x_method` varchar(6) {$charset_collation} NOT NULL,\r\n  `x_type` varchar(20) {$charset_collation} NOT NULL,\r\n  `x_cust_id` int(11) NOT NULL,\r\n  `x_first_name` varchar(255) {$charset_collation} NOT NULL,\r\n  `x_last_name` varchar(255) {$charset_collation} NOT NULL,\r\n  `x_company` varchar(255) {$charset_collation} NOT NULL,\r\n  `x_address` longtext {$charset_collation} NOT NULL,\r\n  `x_city` varchar(100) {$charset_collation} NOT NULL,\r\n  `x_state` varchar(100) {$charset_collation} NOT NULL,\r\n  `x_zip` varchar(20) {$charset_collation} NOT NULL,\r\n  `x_country` varchar(100) {$charset_collation} NOT NULL,\r\n  `x_phone` bigint(20) NOT NULL,\r\n  `x_fax` varchar(25) {$charset_collation} NOT NULL,\r\n  `x_email` varchar(255) {$charset_collation} NOT NULL,\r\n  `x_MD5_Hash` varchar(255) {$charset_collation} NOT NULL,\r\n  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `catalog_dimension` (\r\n\t  `id` int(11) NOT NULL auto_increment,\r\n\t  `height` int(11) NOT NULL,\r\n\t  `width` int(11) NOT NULL,\r\n\t  `filesize` int(11) NOT NULL,\r\n\t  PRIMARY KEY  (`id`)\r\n\t) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1;" );
echo __Func010__( );
$wapstatusflag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `catalog_dimension`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "wapstatus" ) )
    {
        continue;
    }
    $wapstatusflag = 1;
    break;
    break;
}
if ( $wapstatusflag == 0 )
{
    mysql_query( "ALTER TABLE `catalog_dimension` ADD `wapstatus` INT NOT NULL default '0';" );
    echo __Func010__( );
}
if ( $mysql->total( "catalog_dimension", "wapstatus='0'" ) == 0 )
{
    mysql_query( "insert into catalog_dimension values ('0',50,100,100,0);" );
    echo __Func010__( );
}
if ( $mysql->total( "catalog_dimension", "wapstatus='1'" ) == 0 )
{
    mysql_query( "insert into catalog_dimension values ('0',50,50,50,1);" );
    echo __Func010__( );
}
$catalog_size_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_ad_block`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "catalog_size" ) )
    {
        continue;
    }
    $catalog_size_flag = 1;
    break;
    break;
}
if ( $catalog_size_flag == 0 )
{
    mysql_query( "ALTER TABLE ppc_ad_block add\r\n\t(`catalog_size` int(11) NOT NULL default '0',\r\n\t  `no_of_catalog_ads` int(11) NOT NULL default '0',\r\n\t  `catalog_alignment` int(11) NOT NULL default '0',\r\n\t  `catalog_line_seperator` int(11) NOT NULL default '0');" );
    echo __Func010__( );
}
$credit_border_color = $mysql->echo_one( "select id from ppc_credittext_bordercolor limit 0,1" );
$inline_cat = $mysql->echo_one( "select count(*) from ppc_ad_block where ad_type='7'" );
$cat_size = $mysql->echo_one( "select id from catalog_dimension where wapstatus=0 limit 0,1" );
if ( $inline_cat == 0 )
{
    mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height`,  \ttext_ad_type,catalog_size,no_of_catalog_ads,  \tcatalog_alignment,catalog_line_seperator ) VALUES\r\n\r\n('0', 300, 100, 7, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Inline Catalog Ad Block', '{$credittextid}', 1, 1, 1, 1, 1, 1,15,1,{$cat_size},1,0,0);" );
}
$inline_text = $mysql->echo_one( "select count(*) from ppc_ad_block where ad_type='6'" );
if ( $inline_text == 0 )
{
    mysql_query( "INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height` ) VALUES\r\n\r\n('0', 300, 100, 6, 100, 1, 'Arial, Helvetica, sans-serif', 14, '#000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 0, 1, 2, 2, 1, 1, 1, 1, 2, 1, 'Inline Text Ad Block', '{$credittextid}', 1, 1, 1, 1, 1, 1,15);" );
}
$catalog_count = $mysql->echo_one( "select count(*) from ppc_ad_block where ad_type='4'" );
if ( $catalog_count == 0 )
{
    mysql_query( "\r\n INSERT INTO `ppc_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height`, `text_ad_type`, `catalog_size`, `no_of_catalog_ads`, `catalog_alignment`, `catalog_line_seperator`) VALUES\r\n('0', 728, 90, 4, 100, 2, 'Arial, Helvetica, sans-serif', 14, ' #000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'Catalog horizontal', {$credittextid}, 1, 1, 1, 1, 1, 1, 15, 1, 1, 2, 1, 0),\r\n('0', 135, 320, 4, 100, 1, 'Arial, Helvetica, sans-serif', 14, ' #000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'Catalog Vertical', {$credittextid}, 1, 1, 1, 1, 1, 1, 15, 1, 1, 2, 0, 0);\r\n" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `wap_ad_block` (\r\n      `id` int(11) NOT NULL auto_increment,\r\n      `width` int(11) NOT NULL,\r\n      `height` int(11) NOT NULL,\r\n      `ad_type` int(11) NOT NULL,\r\n      `max_size` float(11) NOT NULL,\r\n      `orientaion` int(11) NOT NULL,\r\n      `title_font` varchar(255) NOT NULL,\r\n      `title_size` float NOT NULL,\r\n      `title_color` varchar(225) NOT NULL,\r\n      `desc_font` varchar(255) NOT NULL,\r\n      `desc_size` float NOT NULL,\r\n      `desc_color` varchar(225) NOT NULL,\r\n      `url_font` varchar(255) NOT NULL,\r\n      `url_size` float NOT NULL,\r\n      `url_color` varchar(255) NOT NULL,\r\n      `bg_color` varchar(255) NOT NULL,\r\n      `credit_font` varchar(255) NOT NULL,\r\n      `credit_text_border_color` varchar(255) NOT NULL,\r\n      `no_of_text_ads` int(11) NOT NULL,\r\n      `credit_text_alignment` int(11) NOT NULL,\r\n      `credit_text_positioning` int(11) NOT NULL,\r\n      `allow_publishers` int(11) NOT NULL,\r\n      `border_type` int(11) NOT NULL,\r\n      `status` int(11) NOT NULL default '0',\r\n      `ad_font_weight` int(11) NOT NULL,\r\n      `ad_title_decoration` int(11) NOT NULL,\r\n      `ad_desc_font_weight` int(11) NOT NULL,\r\n      `ad_desc_decoration` int(11) NOT NULL,\r\n      `ad_disp_url_font_weight` int(11) NOT NULL,\r\n      `ad_disp_url_decoration` int(11) NOT NULL,\r\n      `credit_text_font_weight` int(11) NOT NULL,\r\n      `credit_text_decoration` int(11) NOT NULL,\r\n      `ad_block_name` varchar(50) NOT NULL,\r\n      `credit_text` varchar(50) NOT NULL,\r\n      `allow_title_color` int(11) NOT NULL,\r\n      `allow_desc_color` int(11) NOT NULL,\r\n      `allow_disp_url_color` int(11) NOT NULL,\r\n      `allow_bg_color` int(11) NOT NULL,\r\n      `allow_credit_color` int(11) NOT NULL,\r\n      `allow_bordor_type` int(11) NOT NULL,\r\n      `line_height` int(11) NOT NULL default '15',\r\n      `text_ad_type` int(11) NOT NULL default '1',\r\n      `catalog_size` int(11) NOT NULL default '0',\r\n\t  `no_of_catalog_ads` int(11) NOT NULL default '0',\r\n\t  `catalog_alignment` int(11) NOT NULL default '0',\r\n\t  `catalog_line_seperator` int(11) NOT NULL default '0',\r\n      \r\n      PRIMARY KEY  (`id`)\r\n    ) ENGINE=InnoDB {$charset_collation}   AUTO_INCREMENT=1 ;" );
echo __Func010__( );
if ( $mysql->total( "wap_ad_block", "" ) == 0 )
{
    $credit_text = $mysql->echo_one( "select id from ppc_publisher_credits limit 0,1" );
    $catalog = $mysql->echo_one( "select id from catalog_dimension where wapstatus=1  limit 0,1" );
    mysql_query( "\r\nINSERT INTO `wap_ad_block` (`id`, `width`, `height`, `ad_type`, `max_size`, `orientaion`, `title_font`, `title_size`, `title_color`, `desc_font`, `desc_size`, `desc_color`, `url_font`, `url_size`, `url_color`, `bg_color`, `credit_font`, `credit_text_border_color`, `no_of_text_ads`, `credit_text_alignment`, `credit_text_positioning`, `allow_publishers`, `border_type`, `status`, `ad_font_weight`, `ad_title_decoration`, `ad_desc_font_weight`, `ad_desc_decoration`, `ad_disp_url_font_weight`, `ad_disp_url_decoration`, `credit_text_font_weight`, `credit_text_decoration`, `ad_block_name`, `credit_text`, `allow_title_color`, `allow_desc_color`, `allow_disp_url_color`, `allow_bg_color`, `allow_credit_color`, `allow_bordor_type`, `line_height`, `text_ad_type`, `catalog_size`, `no_of_catalog_ads`, `catalog_alignment`, `catalog_line_seperator`) VALUES\r\n('0', 300, 50, 3, 10, 1, 'Arial, Helvetica, sans-serif', 12, ' #000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '6:1 Format-1', {$credit_text}, 1, 1, 1, 1, 1, 1, 12, 3, 0, 1, 0, 0),\r\n('0', 216, 36, 3, 10, 1, 'Arial, Helvetica, sans-serif', 10, ' #000099', 'Arial, Helvetica, sans-serif', 10, '#0F0F0F', 'Arial, Helvetica, sans-serif', 8, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '6:1 Format-2', {$credit_text}, 1, 1, 1, 1, 1, 1, 10, 2, 0, 1, 0, 0),\r\n('0', 168, 28, 3, 10, 1, 'Arial, Helvetica, sans-serif', 10, ' #000099', 'Arial, Helvetica, sans-serif', 8, '#0F0F0F', 'Arial, Helvetica, sans-serif', 8, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '6:1 Format-3', {$credit_text}, 1, 1, 1, 1, 1, 1, 10, 2, 0, 1, 0, 0),\r\n('0', 300, 75, 3, 10, 1, 'Arial, Helvetica, sans-serif', 14, ' #000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '4:1 Format-1', {$credit_text}, 1, 1, 1, 1, 1, 1, 15, 1, 0, 1, 0, 0),\r\n('0', 216, 54, 3, 10, 1, 'Arial, Helvetica, sans-serif', 12, ' #000099', 'Arial, Helvetica, sans-serif', 12, '#0F0F0F', 'Arial, Helvetica, sans-serif', 12, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '4:1 Format-2', {$credit_text}, 1, 1, 1, 1, 1, 1, 12, 1, 0, 1, 0, 0),\r\n('0', 168, 42, 3, 10, 1, 'Arial, Helvetica, sans-serif', 10, ' #000099', 'Arial, Helvetica, sans-serif', 10, '#0F0F0F', 'Arial, Helvetica, sans-serif', 10, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', {$credit_border_color}, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, '4:1 Format-3', {$credit_text}, 1, 1, 1, 1, 1, 1, 10, 3, 0, 1, 0, 0),\r\n('0', 210, 85, 4, 10, 2, 'Arial, Helvetica, sans-serif', 14, ' #000099', 'Arial, Helvetica, sans-serif', 14, '#0F0F0F', 'Arial, Helvetica, sans-serif', 14, '#009933', '#FFFFFF', 'Arial, Helvetica, sans-serif', '{$credit_border_color}', 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 'Wap catalog', '{$credit_text}', 1, 1, 1, 1, 1, 1, 15, 1, {$catalog}, 1, 1, 0);\r\n" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `adserver_languages` (\r\n`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\r\n`language` VARCHAR( 255 )  NOT NULL ,\r\n`encoding` VARCHAR( 50 )  NOT NULL ,\r\n`code` VARCHAR( 5 )  NOT NULL ,\r\n`direction` VARCHAR( 5 )  NOT NULL ,\r\n`status` INT( 5 ) NOT NULL\r\n) ENGINE = InnoDB {$charset_collation}" );
echo __Func010__( );
if ( $mysql->total( "adserver_languages", "" ) == 0 )
{
    mysql_query( "\r\nINSERT INTO  `adserver_languages` (`id`,`language`,`encoding`,`code`,`direction`,`status`) VALUES\r\n('1','English' ,'utf-8','en','ltr',1),\r\n('2','Spanish' ,'utf-8','es','ltr',1),\r\n('3','French' ,'utf-8','fr','ltr',1),\r\n('4','German' ,'utf-8','de','ltr',1),\r\n('5','Russian' ,'utf-8','ru','ltr',1),\r\n('6','Arabic' ,'utf-8','ar','rtl',1),\r\n('7','Hindi' ,'utf-8','hi','ltr',1),\r\n('10','Indonesian' ,'utf-8','id','ltr',1),\r\n('11','Italian' ,'utf-8','it','ltr',1),\r\n\r\n('12','Japanese' ,'utf-8','ja','ltr',1),\r\n('13','Chinese' ,'utf-8','zh','ltr',1),\r\n('14','Thai' ,'utf-8','th','ltr',1),\r\n('15','Dutch' ,'utf-8','nl','ltr',1),\r\n\r\n('16','Portuguese' ,'utf-8','pt','ltr',1) " );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_currency` (\r\n`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,\r\n`currency` VARCHAR( 50 )  NOT NULL ,\r\n`symbol` VARCHAR( 50 )  NOT NULL ,\r\n`status` INT( 5 ) NOT NULL\r\n) ENGINE = InnoDB {$charset_collation}" );
echo __Func010__( );
if ( $mysql->total( "ppc_currency", "" ) == 0 )
{
    $afn = "؋";
    $khr = "៛";
    $irr = "﷼";
    $omr = "﷼";
    $qar = "﷼";
    $sar = "﷼";
    $yer = "﷼";
    mysql_query( "\r\nINSERT INTO  `ppc_currency` (`id`,`currency`,`symbol`,`status`) \r\nVALUES('0','ALL' ,'Lek',1),\r\n('0','USD' ,'\$',1),\r\n('0','AFN','{$afn}','1'),\r\n('0','ARS' ,'\$',1),\r\n('0','AWG' ,'ƒ',1),\r\n('0','AUD' ,'\$',1),\r\n('0','AZN' ,'ман',1),\r\n('0','BSD' ,'\$',1),\r\n('0','BBD' ,'\$',1),\r\n('0','BYR' ,'p.',1),\r\n('0','EUR' ,'€',1),\r\n('0','BZD' ,'BZ\$',1),\r\n('0','BMD' ,'\$',1),\r\n('0','BOB' ,'\$b',1),\r\n('0','BAM' ,'KM',1),\r\n('0','BWP' ,'P',1),\r\n('0','BGN' ,'лв',1),\r\n('0','BRL' ,'R\$',1),\r\n('0','BND' ,'\$',1),\r\n('0','KHR' ,'{$khr}',1),\r\n('0','CAD' ,'\$',1),\r\n('0','KYD' ,'\$',1),\r\n('0','CLP' ,'\$',1),\r\n('0','CNY' ,'¥',1),\r\n('0','COP' ,'\$',1),\r\n('0','CRC' ,'₡',1),\r\n('0','HRK' ,'kn',1),\r\n('0','CUP' ,'₱',1),\r\n('0','CZK' ,'Kč',1),\r\n('0','DKK' ,'kr',1),\r\n('0','DOP' ,'RD\$',1),\r\n('0','XCD' ,'\$',1),\r\n('0','EGP' ,'£',1),\r\n('0','SVC' ,'\$',1),\r\n('0','GBP' ,'£',1),\r\n('0','FKP' ,'£',1),\r\n('0','FJD' ,'\$',1),\r\n('0','GHC' ,'¢',1),\r\n('0','GIP' ,'£',1),\r\n('0','GTQ' ,'Q',1),\r\n('0','GGP' ,'£',1),\r\n('0','GYD' ,'\$',1),\r\n('0','HNL' ,'L',1),\r\n('0','HKD' ,'\$',1),\r\n('0','HUF' ,'Ft',1),\r\n('0','ISK' ,'kr',1),\r\n('0','INR' ,'Rs',1),\r\n('0','IDR' ,'Rp',1),\r\n('0','IRR' ,'{$irr}',1),\r\n('0','IMP' ,'£',1),\r\n('0','ILS' ,'₪',1),\r\n('0','JMD' ,'J\$',1),\r\n('0','JPY' ,'¥',1),\r\n('0','JEP' ,'£',1),\r\n('0','KZT' ,'лв',1),\r\n('0','KPW' ,'₩',1),\r\n('0','KGS' ,'лв',1),\r\n('0','LAK' ,'₭',1),\r\n('0','LVL' ,'Ls',1),\r\n('0','LBP' ,'£',1),\r\n('0','LRD' ,'\$',1),\r\n('0','LTL' ,'Lt',1),\r\n('0','MKD' ,'ден',1),\r\n('0','MYR' ,'RM',1),\r\n('0','MUR' ,'₨',1),\r\n('0','MXN' ,'\$',1),\r\n('0','MNT' ,'₮',1),\r\n('0','MZN' ,'MT',1),\r\n('0','NAD' ,'\$',1),\r\n('0','NPR' ,'₨',1),\r\n('0','ANG' ,'ƒ',1),\r\n('0','NZD' ,'\$',1),\r\n('0','NIO' ,'C\$',1),\r\n('0','NGN' ,'₦',1),\r\n('0','NOK' ,'kr',1),\r\n('0','OMR' ,'{$omr}',1),\r\n('0','PKR' ,'₨',1),\r\n('0','PAB' ,'B/.',1),\r\n('0','PYG' ,'Gs',1),\r\n('0','PEN' ,'S/.',1),\r\n('0','PHP' ,'Php',1),\r\n('0','PLN' ,'zł',1),\r\n('0','QAR' ,'{$qar}',1),\r\n('0','RON' ,'lei',1),\r\n('0','RUB' ,'руб',1),\r\n('0','SHP' ,'£',1),\r\n('0','SAR' ,'{$sar}',1),\r\n('0','RSD' ,'Дин.',1),\r\n('0','SCR' ,'₨',1),\r\n('0','SGD' ,'\$',1),\r\n('0','SBD' ,'\$',1),\r\n('0','SOS' ,'S',1),\r\n('0','ZAR' ,'R',1),\r\n('0','KRW' ,'₩',1),\r\n('0','LKR' ,'₨',1),\r\n('0','SEK' ,'kr',1),\r\n('0','CHF' ,'CHF',1),\r\n('0','SRD' ,'\$',1),\r\n('0','SYP' ,'£',1),\r\n('0','TWD' ,'NT\$',1),\r\n('0','THB' ,'฿',1),\r\n('0','TTD' ,'TT\$',1),\r\n('0','TRY' ,'TL',1),\r\n('0','TRL' ,'₤',1),\r\n('0','TVD' ,'\$',1),\r\n('0','UAH' ,'₴',1),\r\n('0','UYU' ,'\$U',1),\r\n('0','UZS' ,'лв',1),\r\n('0','VEF' ,'Bs',1),\r\n('0','VND' ,'₫',1),\r\n('0','YER' ,'{$yer}',1),\r\n('0','ZWD' ,'Z\$',1)" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `nesote_inoutscripts_users` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `username` varchar(255)  NOT NULL ,\r\n  `password` varchar(255)  NOT NULL ,\r\n  `name` varchar(255)  NOT NULL ,\r\n  `email` varchar(255)  NOT NULL ,\r\n  `joindate` int(11) NOT NULL,\r\n  `status` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n)  ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
if ( $mysql->total( "ppc_settings", "name='bonous_system_type'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'bonous_system_type', '1');" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `gift_code` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `name` varchar(255) NOT NULL default '', \r\n  `coupon_code` varchar(50) NOT NULL default '', \r\n  `status` int(11) NOT NULL,\r\n  `type` int(11) NOT NULL,\r\n  `amount` float NOT NULL default '0',\r\n  `expirydate` int(11) NOT NULL,\r\n  `no_times` int(11) NOT NULL default 0, \r\n  `count` int(11) NOT NULL default 0, \r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( " CREATE TABLE  IF NOT EXISTS `url_updation` (\r\n`id` INT NOT NULL AUTO_INCREMENT ,\r\n`name` varchar(255)  NOT NULL ,\r\n  `oldname` varchar(255)  NOT NULL ,\r\n  `seoname` varchar(255)  NOT NULL ,\r\nPRIMARY KEY ( `id` )\r\n)ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
if ( $mysql->total( "url_updation", "name='home'" ) == 0 )
{
    mysql_query( "INSERT INTO `url_updation` VALUES \r\n('0', 'home', 'index.php',''),\r\n('0', 'advertisers','ppc-user-login.php',''),\r\n('0','publishers','ppc-publisher-login.php',''),\r\n('0','signin','login.php',''),\r\n('0','signup','registration.php',''),\r\n('0','advfaq', 'ppc-advertiser-faq.php',''),\r\n('0','pubfaq', 'ppc-publisher-faq.php',''),\r\n('0','forgotpassword', 'forgot-password.php',''),\r\n('0','contactus', 'ppc-contact-us.php',''),\r\n('0','advertisersignup', 'ppc-user-registration.php',''),\r\n('0','publishersignup', 'ppc-publisher-registration.php',''),\r\n('0','advertiserforgotpassword', 'ppc-forgot-password.php',''),\r\n('0','publisherforgotpassword', 'ppc-publisher-forgot-password.php','');" );
    echo __Func010__( );
}
$tblcolums = mysql_query( "SHOW COLUMNS FROM `inout_ppc_ipns`" );
$row = __Func020__( $tblcolums );
if ( $row != 0 )
{
    mysql_query( "ALTER TABLE `inout_ppc_ipns` CHANGE `amount` `amount` float NOT NULL " );
}
mysql_query( " CREATE TABLE  IF NOT EXISTS `messages` (\r\n`id` INT NOT NULL AUTO_INCREMENT ,\r\n`message`  longtext NOT NULL ,\r\n  `messagefor` varchar(255)  NOT NULL ,\r\n  `date` int(11)  NOT NULL ,\r\n  `status` int(11)  NOT NULL ,\r\nPRIMARY KEY ( `id` )\r\n)ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( " CREATE TABLE  IF NOT EXISTS `server_configurations` (\r\n`id` INT NOT NULL AUTO_INCREMENT ,\r\n`name` varchar(255)  NOT NULL ,\r\n`server_url` longtext NOT NULL,\r\n`min_range` int(11) NOT NULL default '0',\r\n`max_range` int(11) NOT NULL default '0',\r\n`srv_type` int(11) NOT NULL default '0',\r\n`status` varchar(255) NOT NULL default '0',\r\nPRIMARY KEY ( `id` )\r\n)ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `advertiser_impression_hourly_master` \r\n(`id` int(11) NOT NULL auto_increment,\r\n`uid` int(11) NOT NULL default '0',\r\n`aid` int(11) NOT NULL default '0',\r\n`kid` int(11) NOT NULL default '0',\r\n`time` int(11) NOT NULL default '0', \r\n`cnt` int(11) NOT NULL default '0',\r\n`server_id` int(11) NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `publisher_impression_hourly_master` \r\n(`id` int(11) NOT NULL auto_increment,\r\n`pid` int(11) NOT NULL default '0',\r\n`bid` int(11) NOT NULL default '0',\r\n`time` int(11) NOT NULL default '0', \r\n`cnt` int(11) NOT NULL default '0',\r\n`server_id` int(11) NOT NULL default '0',\r\n\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `advertiser_impression_daily` \r\n(`id` int(11) NOT NULL auto_increment,\r\n`uid` int(11) NOT NULL default '0',\r\n`aid` int(11) NOT NULL default '0',\r\n`kid` int(11) NOT NULL default '0',\r\n`time` int(11) NOT NULL default '0', \r\n`cnt` int(11) NOT NULL default '0',\r\n`server_id` int(11) NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `publisher_impression_daily` \r\n(`id` int(11) NOT NULL auto_increment,\r\n`pid` int(11) NOT NULL default '0',\r\n`bid` int(11) NOT NULL default '0',\r\n`time` int(11) NOT NULL default '0', \r\n`cnt` int(11) NOT NULL default '0',\r\n`server_id` int(11) NOT NULL default '0',\r\n\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `publisher_daily_visits_statistics_master` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `pid` int(11) NOT NULL,\r\n  `page_url` varchar(255) NOT NULL,\r\n  `direct_hits` int(11) NOT NULL default '0',\r\n  `referred_hits` int(11) NOT NULL default '0',\r\n  `direct_clicks` int(11) NOT NULL default '0',\r\n  `referred_clicks` int(11) NOT NULL default '0',\r\n  `direct_impressions` int(11) NOT NULL default '0',\r\n  `referred_impressions` int(11) NOT NULL default '0',\r\n   `direct_invalid_clicks` int(11) NOT NULL default '0',\r\n  `referred_invalid_clicks` int(11) NOT NULL default '0',\r\n  `direct_fraud_clicks` int(11) NOT NULL default '0',\r\n  `referred_fraud_clicks` int(11) NOT NULL default '0',\r\n  `direct_repeated_click` varchar(11) NOT NULL default '0',\r\n  `referred_repeated_click` varchar(11) NOT NULL default '0',\r\n  `time` int(11) NOT NULL default '0',\r\n  `serverid` int(11) NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
if ( $mysql->total( "statistics_updation", "task='advertiser_impression_hourly_master_1'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'advertiser_impression_hourly_master_1', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='publisher_impression_hourly_master_1'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'publisher_impression_hourly_master_1', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='visit_back_master_1'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n( 'visit_back_master_1', 0, 2, 0);" );
    echo __Func010__( );
}
mysql_query( " CREATE TABLE  IF NOT EXISTS `banner_dimension` (\r\n`id` INT NOT NULL AUTO_INCREMENT ,\r\n`width` int(11) NOT NULL,\r\n `height` int(11) NOT NULL,\r\n `file_size` int(11) NOT NULL,\r\n `wap_status` int(11) NOT NULL,\r\n PRIMARY KEY ( `id` )\r\n)ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$browser = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_daily_clicks`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "browser" ) )
    {
        continue;
    }
    $browser = 1;
    break;
    break;
}
if ( $browser == 0 )
{
    mysql_query( "ALTER TABLE `ppc_daily_clicks` ADD \r\n\t(`browser` VARCHAR(255) NOT NULL default '',\r\n\t`platform` VARCHAR(255) NOT NULL default '',\r\n\t`version` VARCHAR(255) NOT NULL default '',\r\n\t`user_agent` TEXT NOT NULL default '',\r\n\t`serverid` INT DEFAULT '0' NOT NULL,\r\n\t`direct_status` INT DEFAULT '0' NOT NULL);" );
    echo __Func010__( );
}
$browser = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_clicks`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "browser" ) )
    {
        continue;
    }
    $browser = 1;
    break;
    break;
}
if ( $browser == 0 )
{
    mysql_query( "ALTER TABLE `ppc_clicks` ADD \r\n\t(`browser` VARCHAR(255) NOT NULL default '',\r\n\t`platform` VARCHAR(255) NOT NULL default '',\r\n\t`version` VARCHAR(255) NOT NULL default '',\r\n\t`user_agent` TEXT NOT NULL default '',\r\n\t`serverid` INT DEFAULT '0' NOT NULL,\r\n\t`direct_status` INT DEFAULT '0' NOT NULL);" );
    echo __Func010__( );
}
$browser = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_fraud_clicks`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "browser" ) )
    {
        continue;
    }
    $browser = 1;
    break;
    break;
}
if ( $browser == 0 )
{
    mysql_query( "ALTER TABLE `ppc_fraud_clicks` ADD\r\n\t( `browser` VARCHAR(255) NOT NULL default '',\r\n\t`platform` VARCHAR(255) NOT NULL default '',\r\n\t`version` VARCHAR(255) NOT NULL default '',\r\n\t`user_agent` TEXT NOT NULL default '',\r\n\t`serverid` INT DEFAULT '0' NOT NULL,\r\n\t`direct_status` INT DEFAULT '0' NOT NULL);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='keyword_daily_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('keyword_daily_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='keyword_monthly_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('keyword_monthly_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='keyword_yearly_statistics'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES\r\n('keyword_yearly_statistics', 0, 2, 0);" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `system_keywords` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `keyword` varchar(255) NOT NULL default '',\r\n  `createtime` int(11) default NULL,\r\n  `rating` int(11) default NULL,\r\n    `status` int(11) NOT NULL default '0',\r\n    PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `keyword_daily_statistics` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `keyid` int(11) NOT NULL default '0',\r\n  `impressions` int(11) NOT NULL default '0',\r\n  `click_count` int(11) NOT NULL default '0',\r\n  `money_spent` float default NULL,\r\n  `time` int(11) default NULL,\r\n    PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `keyword_monthly_statistics` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `keyid` int(11) NOT NULL default '0',\r\n  `impressions` int(11) NOT NULL default '0',\r\n  `click_count` int(11) NOT NULL default '0',\r\n  `money_spent` float default NULL,\r\n  `time` int(11) default NULL,\r\n    PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
mysql_query( "CREATE TABLE IF NOT EXISTS `keyword_yearly_statistics` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `keyid` int(11) NOT NULL default '0',\r\n  `impressions` int(11) NOT NULL default '0',\r\n  `click_count` int(11) NOT NULL default '0',\r\n  `money_spent` float default NULL,\r\n  `time` int(11) default NULL,\r\n    PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
echo __Func010__( );
$contenttypestatus = 0;
$hardcodedstatus = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_ads`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "contenttype" )
    {
        $contenttypestatus = 1;
    }
    if ( $row['Field'] == "hardcodelinks" )
    {
        $hardcodedstatus = 1;
    }
}
if ( $contenttypestatus == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `contenttype` varchar(255) not null default '';" );
    echo __Func010__( );
}
if ( $hardcodedstatus == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `hardcodelinks` int not null default '0';" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='hardcode_check_url'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'hardcode_check_url', 'http://inoutscripts.com');" );
    echo __Func010__( );
}
$contenttypestatus = 0;
$hardcodedstatus = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_public_service_ads`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( $row['Field'] == "contenttype" )
    {
        $contenttypestatus = 1;
    }
    if ( $row['Field'] == "hardcodelinks" )
    {
        $hardcodedstatus = 1;
    }
}
if ( $contenttypestatus == 0 )
{
    mysql_query( "ALTER TABLE `ppc_public_service_ads` ADD `contenttype` varchar(255) not null default '';" );
    echo __Func010__( );
}
if ( $hardcodedstatus == 0 )
{
    mysql_query( "ALTER TABLE `ppc_public_service_ads` ADD `hardcodelinks` int not null default '0';" );
    echo __Func010__( );
}
include( "trigger.php" );
if ( $mysql->total( "ppc_settings", "name='captcha_status'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'captcha_status', 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='captcha_time'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'captcha_time', 24);" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='premium_profit'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'premium_profit', '75');" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='rate_updation'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES ( 'rate_updation', 0, 2, 0);" );
    echo __Func010__( );
}
if ( $mysql->total( "statistics_updation", "task='cron_run_time'" ) == 0 )
{
    mysql_query( "INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES ( 'cron_run_time', 0, 2, 0);" );
    echo __Func010__( );
}
$ppc_ad_index = mysql_query( "SHOW INDEX FROM ppc_ads WHERE KEY_NAME = 'idx_uid'" );
if ( __Func020__( $ppc_ad_index ) == 0 )
{
    mysql_query( "CREATE INDEX idx_uid ON ppc_ads (uid)" );
}
$ppc_location_index = mysql_query( "SHOW INDEX FROM ad_location_mapping WHERE KEY_NAME = 'idx_adid'" );
if ( __Func020__( $ppc_location_index ) == 0 )
{
    mysql_query( "CREATE INDEX idx_adid ON ad_location_mapping (adid)" );
}
$ppc_location_index1 = mysql_query( "SHOW INDEX FROM ad_location_mapping WHERE KEY_NAME = 'idx_country'" );
if ( __Func020__( $ppc_location_index1 ) == 0 )
{
    mysql_query( "CREATE INDEX idx_country ON ad_location_mapping (country(2))" );
}
$ppc_keywords_index = mysql_query( "SHOW INDEX FROM ppc_keywords WHERE KEY_NAME = 'idx_uid'" );
if ( __Func020__( $ppc_keywords_index ) == 0 )
{
    mysql_query( "CREATE INDEX idx_uid ON ppc_keywords (uid)" );
}
$ppc_keywords_index1 = mysql_query( "SHOW INDEX FROM ppc_keywords WHERE KEY_NAME = 'idx_aid'" );
if ( __Func020__( $ppc_keywords_index1 ) == 0 )
{
    mysql_query( "CREATE INDEX idx_aid ON ppc_keywords (aid)" );
}
$ppc_keywords_index2 = mysql_query( "SHOW INDEX FROM ppc_keywords WHERE KEY_NAME = 'idx_keyword'" );
if ( __Func020__( $ppc_keywords_index2 ) == 0 )
{
    mysql_query( "CREATE INDEX idx_keyword ON ppc_keywords (keyword(5))" );
}
if ( $flag_location_country == 0 )
{
    $r1 = mysql_query( "select country from ppc_users" );
    while ( $temp = __Func011__( $r1 ) )
    {
        $country_name = $temp[0];
        $ctry_result = mysql_query( "select code from location_country where LCASE(name)=LCASE('{$country_name}')" );
        $ctry_code = __Func011__( $ctry_result );
        if ( $ctry_code[0] == "" )
        {
            $ctry_code[0] = $country_name;
        }
        mysql_query( "UPDATE ppc_users SET country='{$ctry_code['0']}' WHERE country='{$country_name}'" );
    }
    mysql_query( "ALTER TABLE `ppc_users` CHANGE `country` `country` VARCHAR( 2 ) {$charset_collation} " );
    $r1 = mysql_query( "select country from ppc_publishers" );
    while ( $temp = __Func011__( $r1 ) )
    {
        $country_name = $temp[0];
        $ctry_result = mysql_query( "select code from location_country where LCASE(name)=LCASE('{$country_name}')" );
        $ctry_code = __Func011__( $ctry_result );
        if ( $ctry_code[0] == "" )
        {
            $ctry_code[0] = $country_name;
        }
        mysql_query( "UPDATE ppc_publishers SET country='{$ctry_code['0']}' WHERE country='{$country_name}'" );
    }
    mysql_query( "ALTER TABLE `ppc_publishers` CHANGE `country` `country` VARCHAR( 2 ) {$charset_collation} " );
}
if ( $mysql->echo_one( "select uid from ppc_publishers where accountbalance < 0 limit 0,1" ) != "" )
{
    mysql_query( "update ppc_publishers set accountbalance=0 where accountbalance<0" );
    echo __Func010__( );
}
if ( 0 < $mysql->total( "ppc_clicks", "clickvalue = 0" ) )
{
    mysql_query( "delete from ppc_clicks where clickvalue=0 " );
    echo __Func010__( );
}
if ( $adname == 0 )
{
    $i = 0;
    $ad = mysql_query( "select id from ppc_ads" );
    echo __Func010__( );
    while ( $ads = __Func011__( $ad ) )
    {
        $ss = "AD_".$ads[0];
        mysql_query( "update `ppc_ads` set name='{$ss}' where id={$ads['0']}" );
        echo __Func010__( );
    }
}
if ( $public_name == 0 )
{
    $i = 0;
    $ad1 = mysql_query( "select id from ppc_public_service_ads" );
    echo __Func010__( );
    while ( $ads1 = __Func011__( $ad1 ) )
    {
        $ss1 = "AD_".$ads1[0];
        mysql_query( "update `ppc_public_service_ads` set name='{$ss1}' where id={$ads1['0']}" );
        echo __Func010__( );
    }
}
if ( $covert_non_safe_data == 1 )
{
    $res = mysql_query( "select id,item_value  from site_content " );
    while ( $row = __Func011__( $res ) )
    {
        if ( strcmp( $ad_display_char_encoding, "UTF-8" ) == 0 )
        {
            $row[1] = __Func021__( $row[1], ENT_QUOTES, "UTF-8" );
        }
        else
        {
            $row[1] = __Func021__( $row[1], ENT_QUOTES );
        }
        mysql_query( "update site_content set item_value='".$row[1]."' where id='{$row['0']}' " );
    }
    $res = mysql_query( "select id,name  from ppc_custom_ad_block where pid<>0" );
    while ( $row = __Func011__( $res ) )
    {
        if ( strcmp( $ad_display_char_encoding, "UTF-8" ) == 0 )
        {
            $row[1] = __Func021__( $row[1], ENT_QUOTES, "UTF-8" );
        }
        else
        {
            $row[1] = __Func021__( $row[1], ENT_QUOTES );
        }
        mysql_query( "update ppc_custom_ad_block set name='".$row[1]."' where id='{$row['0']}' " );
    }
}
if ( $mysql->echo_one( "select id from ppc_ads where  status=2 limit 0,1" ) != "" )
{
    mysql_query( "update `ppc_ads` set status=1 where status=2;" );
}
echo __Func010__( );
mysql_query( "update ppc_ads set status=-1 where status=1 and ( CHAR_LENGTH(title)>35 or CHAR_LENGTH(summary)>70 or CHAR_LENGTH(displayurl)>35)" );
echo __Func010__( );
$max_clk_id = $mysql->echo_one( "select max(id) from ppc_clicks" );
$max_daily_clk_id = $mysql->echo_one( "select max(id) from ppc_daily_clicks" );
if ( $max_daily_clk_id < $max_clk_id )
{
    $next_auto_incr = $max_clk_id + $max_daily_clk_id + 100;
    $incr_value = 100 + $max_clk_id;
    mysql_query( "ALTER TABLE `ppc_daily_clicks` AUTO_INCREMENT ={$next_auto_incr}" );
    mysql_query( "update `ppc_daily_clicks` set id=id+{$incr_value}" );
}
if ( $mysql->total( "ppc_settings", "name='raw_data_clearing'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'raw_data_clearing', '1');" );
    echo __Func010__( );
    $cmonthtime = __Func018__( 0, 0, 0, __Func019__( "n", __Func013__( ) ) + 1, 1, __Func019__( "Y", __Func013__( ) ) );
    mysql_query( "DELETE FROM `advertiser_monthly_statistics` WHERE time>={$cmonthtime}" );
    echo __Func010__( );
    mysql_query( "DELETE FROM `publisher_monthly_statistics` WHERE time>={$cmonthtime}" );
    echo __Func010__( );
    mysql_query( "DELETE FROM `monthly_referral_statistics` WHERE time>={$cmonthtime}" );
    echo __Func010__( );
    $cyeartime = __Func018__( 0, 0, 0, 1, 1, __Func019__( "Y", __Func013__( ) ) + 1 );
    mysql_query( "DELETE FROM `advertiser_yearly_statistics` WHERE time>={$cyeartime}" );
    echo __Func010__( );
    mysql_query( "DELETE FROM `publisher_yearly_statistics` WHERE time>={$cyeartime}" );
    echo __Func010__( );
    mysql_query( "DELETE FROM `yearly_referral_statistics` WHERE time>={$cyeartime}" );
    echo __Func010__( );
}
$row = mysql_query( "select a.id from ppc_ads a left join ad_location_mapping b on a.id=b.adid where b.country is null " );
while ( 0 < __Func020__( $row ) && ( $rowdata = __Func011__( $row ) ) )
{
    mysql_query( "INSERT INTO `ad_location_mapping` (`adid` , `country` , `region` , `city`) VALUES ('{$rowdata['0']}', '00', '00', '00')" );
}
if ( $mysql->total( "banner_dimension", "" ) == 0 )
{
    mysql_query( "BEGIN" );
    $err = 0;
    $condition12 = "ad_type=3 or ad_type=2";
    $banner_dime = mysql_query( "select height,width,max_size,id from ppc_ad_block where {$condition12}" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $banner_dime1 = __Func011__( $banner_dime ) )
    {
        $repeatno = $mysql->echo_one( "select id from banner_dimension where width='{$banner_dime1['1']}' and height='{$banner_dime1['0']}' and wap_status='0'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        if ( $repeatno == "" )
        {
            mysql_query( "insert into banner_dimension values('0',{$banner_dime1['1']},{$banner_dime1['0']},{$banner_dime1['2']},'0')" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
            $lastid = __Func012__( );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
            mysql_query( "update ppc_ad_block set max_size='{$lastid}' where id='{$banner_dime1['3']}'" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
        }
        else
        {
            mysql_query( "update ppc_ad_block set max_size='{$repeatno}' where id='{$banner_dime1['3']}'" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
        }
    }
    $wapsize5 = mysql_query( "select height,width,max_size,id from wap_ad_block where {$condition12}" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $wapbann51 = __Func011__( $wapsize5 ) )
    {
        $repeatwapno = $mysql->echo_one( "select id from banner_dimension where width='{$wapbann51['1']}' and height='{$wapbann51['0']}' and wap_status='1'" );
        if ( $repeatwapno == "" )
        {
            mysql_query( "insert into banner_dimension values('0',{$wapbann51['1']},{$wapbann51['0']},{$wapbann51['2']},'1')" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
            $lastid1 = __Func012__( );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
            mysql_query( "update wap_ad_block set max_size='{$lastid1}' where id='{$wapbann51['3']}'" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
        }
        else
        {
            mysql_query( "update wap_ad_block set max_size='{$repeatwapno}' where id='{$wapbann51['3']}'" );
            if ( __Func010__( ) )
            {
                echo __Func010__( );
                $err = 1;
            }
        }
    }
    $ban1 = mysql_query( "select distinct(bannersize) from ppc_ads where bannersize is not null and adtype='1' and wapstatus='0'" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $bann501 = __Func011__( $ban1 ) )
    {
        $filesize = mysql_query( "select max_size from ppc_ad_block where id='{$bann501['0']}'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        $filesize1 = __Func011__( $filesize );
        mysql_query( "update ppc_ads set bannersize=".$filesize1[0]." where bannersize=".$bann501[0]." and adtype='1' and wapstatus='0'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
    }
    $wapban1 = mysql_query( "select distinct(bannersize) from ppc_ads where bannersize is not null and adtype='1' and wapstatus='1'" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $wapbann501 = __Func011__( $wapban1 ) )
    {
        $fsize = $mysql->echo_one( "select max_size from wap_ad_block where id='{$wapbann501['0']}'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        mysql_query( "update ppc_ads set bannersize=".$fsize." where bannersize=".$wapbann501[0]." and adtype='1' and wapstatus='1'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
    }
    $pub_ser_ban1 = mysql_query( "select distinct(bannersize) from ppc_public_service_ads where bannersize is not null and adtype='1' and wapstatus='0'" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $pub_ser_ads = __Func011__( $pub_ser_ban1 ) )
    {
        $filesize = mysql_query( "select max_size from ppc_ad_block where id='{$pub_ser_ads['0']}'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        $filesize1 = __Func011__( $filesize );
        mysql_query( "update ppc_public_service_ads set bannersize=".$filesize1[0]." where bannersize=".$pub_ser_ads[0]." and adtype='1' and wapstatus='0'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
    }
    $wappublicban1 = mysql_query( "select distinct(bannersize) from ppc_public_service_ads where bannersize is not null and adtype='1' and wapstatus='1'" );
    if ( __Func010__( ) )
    {
        echo __Func010__( );
        $err = 1;
    }
    while ( $wappublicban = __Func011__( $wappublicban1 ) )
    {
        $fsize = $mysql->echo_one( "select max_size from wap_ad_block where id='{$wappublicban['0']}'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
        mysql_query( "update ppc_public_service_ads set bannersize=".$fsize." where bannersize=".$wappublicban[0]." and adtype='1' and wapstatus='1'" );
        if ( __Func010__( ) )
        {
            echo __Func010__( );
            $err = 1;
        }
    }
    if ( $err == 1 )
    {
        mysql_query( "ROLLBACK" );
    }
    else
    {
        mysql_query( "COMMIT" );
    }
}
$pay_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `advertiser_bonus_deposit_history`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "pay_type" ) )
    {
        continue;
    }
    $pay_flag = 1;
    break;
    break;
}
if ( $pay_flag == 0 )
{
    mysql_query( "ALTER TABLE `advertiser_bonus_deposit_history` ADD `pay_type` VARCHAR(10) NOT NULL default '';" );
    if ( $ini_error_status != 0 )
    {
        echo __Func010__( );
    }
}
mysql_query( "CREATE TABLE IF NOT EXISTS `time_targeting` (\r\n  `id` int(11) NOT NULL AUTO_INCREMENT,\r\n  `aid` int(11) NOT NULL,\r\n  `date_tar_s` int(11) NOT NULL DEFAULT '0',\r\n  `date_tar_e` int(11) NOT NULL DEFAULT '0',\r\n  `time_tar_s` int(11) NOT NULL DEFAULT '0',\r\n  `time_tar_e` int(11) NOT NULL DEFAULT '0',\r\n  `day_tar_s` int(11) NOT NULL DEFAULT '0',\r\n  `day_tar_e` int(11) NOT NULL DEFAULT '0',\r\n  `date_flg` int(11) NOT NULL DEFAULT '0',\r\n  `time_flg` int(11) NOT NULL DEFAULT '0',\r\n  `day_flg` int(11) NOT NULL DEFAULT '0',                                    \r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
$pay_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_ads`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "time_status" ) )
    {
        continue;
    }
    $pay_flag = 1;
    break;
    break;
}
if ( $pay_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `time_status` int(11) NOT NULL DEFAULT '1';" );
    if ( $ini_error_status != 0 )
    {
        echo __Func010__( );
    }
}
$flag_time = 0;
$flag_chat1 = 0;
$flag_chat2 = 0;
$flag_chat3 = 0;
while ( list( $temp ) = temp )
{
    if ( $temp == "time_hour" )
    {
        $flag_time = 1;
    }
    if ( $temp == "nesote_chat_country" )
    {
        $flag_chat1 = 1;
    }
    if ( $temp == "nesote_chat_login_status" )
    {
        $flag_chat2 = 1;
    }
    if ( $temp == "nesote_chat_public_user" )
    {
        $flag_chat3 = 1;
    }
}
if ( $flag_time == 0 )
{
    mysql_query( "CREATE TABLE IF NOT EXISTS `time_hour` (\r\n  `code` int(11) NOT NULL,\r\n  `name` varchar(255) NOT NULL,\r\n  PRIMARY KEY (`code`)\r\n) ENGINE=InnoDB {$charset_collation} " );
    mysql_query( "INSERT INTO `time_hour` (`code`, `name`) VALUES\r\n(0, '12 AM'),\r\n(1, '1 AM'),\r\n(2, '2 AM'),\r\n(3, '3 AM'),\r\n(4, '4 AM'),\r\n(5, '5 AM'),\r\n(6, '6 AM'),\r\n(7, '7 AM'),\r\n(8, '8 AM'),\r\n(9, '9 AM'),\r\n(10, '10 AM'),\r\n(11, '11 AM'),\r\n(12, '12 PM'),\r\n(13, '1 PM'),\r\n(14, '2 PM'),\r\n(15, '3 PM'),\r\n(16, '4 PM'),\r\n(17, '5 PM'),\r\n(18, '6 PM'),\r\n(19, ' 7 PM'),\r\n(20, '8 PM'),\r\n(21, '9 PM'),\r\n(22, '10 PM'),\r\n(23, '11 PM');" );
}
$pay_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_ads`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "beg_time" ) )
    {
        continue;
    }
    $pay_flag = 1;
    break;
    break;
}
if ( $pay_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `beg_time` int(11) NOT NULL DEFAULT '0';" );
    if ( $ini_error_status != 0 )
    {
        echo __Func010__( );
    }
}
$pay_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_ads`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "end_time" ) )
    {
        continue;
    }
    $pay_flag = 1;
    break;
    break;
}
if ( $pay_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `end_time` int(11) NOT NULL DEFAULT '0';" );
    if ( $ini_error_status != 0 )
    {
        echo __Func010__( );
    }
}
if ( $mysql->total( "ppc_settings", "name='time_targeting'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'time_targeting', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='time_date_targetting'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'time_date_targetting', '0');" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_publishing_urls` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `pid` int(11) NOT NULL default '0',\r\n  `url` varchar(255) NOT NULL,\r\n  `status` int(11) NOT NULL default '-1',\r\n  `description` longtext NOT NULL,\r\n  `create_time` int(11) NOT NULL default '0',\r\n  `twt_ac` varchar(255) NOT NULL default '0',\r\n  `twt_ac_no` int(11) NOT NULL default '0',\r\n  `face_ac` varchar(255) NOT NULL default '0',\r\n  `face_ac_no` int(11) NOT NULL default '0',\r\n  `page_link` varchar(255) NOT NULL default '0',\r\n  `feed` varchar(255) NOT NULL default '0',\r\n  `feed_no` int(11) NOT NULL default '0',\r\n  `rank` int(11) NOT NULL default '0',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}   AUTO_INCREMENT=1 ;" );
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_site_adunit` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `auid` int(11) NOT NULL,\r\n  `siteid` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}   AUTO_INCREMENT=1 ;" );
if ( $mysql->total( "ppc_settings", "name='site_targeting'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'site_targeting', '1');" );
    echo __Func010__( );
}
$pay_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_custom_ad_block`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "adult_status" ) )
    {
        continue;
    }
    $pay_flag = 1;
    break;
    break;
}
if ( $pay_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_custom_ad_block` ADD `adult_status` int(11) NOT NULL DEFAULT '0';" );
    if ( $ini_error_status != 0 )
    {
        echo __Func010__( );
    }
}
$pay_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_ads`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "adult_status" ) )
    {
        continue;
    }
    $pay_flag = 1;
    break;
    break;
}
if ( $pay_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_ads` ADD `adult_status` int(11) NOT NULL DEFAULT '0';" );
    if ( $ini_error_status != 0 )
    {
        echo __Func010__( );
    }
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ppc_ad_templates` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `banner_size` int(11) NOT NULL,\r\n  `name` varchar(255) NOT NULL,\r\n  `createtime` int(11) NOT NULL,\r\n  `filename` varchar(255) NOT NULL,\r\n  `status` int(11) NOT NULL default '1',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation}  AUTO_INCREMENT=1 ;" );
$pay_flag = 0;
$tblcolums = mysql_query( "SHOW COLUMNS FROM `ppc_custom_ad_block`" );
while ( $row = __Func008__( $tblcolums ) )
{
    if ( !( $row['Field'] == "template" ) )
    {
        continue;
    }
    $pay_flag = 1;
    break;
    break;
}
if ( $pay_flag == 0 )
{
    mysql_query( "ALTER TABLE `ppc_custom_ad_block` ADD `template` int(11) NOT NULL DEFAULT '-1';" );
    if ( $ini_error_status != 0 )
    {
        echo __Func010__( );
    }
}
if ( $flag_chat1 == 0 ){
    mysql_query( "CREATE TABLE IF NOT EXISTS `nesote_chat_country` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `country_name` varchar(444) collate utf8_unicode_ci NOT NULL,\r\n  `country_iso2` varchar(444) collate utf8_unicode_ci NOT NULL,\r\n  `country_iso3` varchar(444) collate utf8_unicode_ci NOT NULL,\r\n  `country_flag` varchar(444) collate utf8_unicode_ci NOT NULL,\r\n  `country_status` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} ;" );
    mysql_query( "INSERT INTO `nesote_chat_country` (`id`, `country_name`, `country_iso2`, `country_iso3`, `country_flag`, `country_status`) VALUES\r\n(1, 'India', 'IN', 'IND', 'f369108df4de46c0d1e82ba05d4f1ed9.png', 1),\r\n(2, 'United States', 'US', 'USA', '2251349e86613bcf6c19447a824ca2bd.png', 1),\r\n(4, 'Romania', 'RO', 'ROU', '8fa6b2efac44f4d129e76d145d4e9dd4.png', 1),\r\n(5, 'Kenya', 'KE', 'KEN', 'b9db106be0c5d8c8e68f83f54a47b05d.png', 1),\r\n(26, 'Afghanistan', 'AF', 'AFG', '7d1321ec2cf6cf97b42bad54866655b8.png', 1),\r\n(27, 'Albania', 'AL', 'ALB', '0730121d1affc6dc97a6b94f74d0d1ca.png', 1),\r\n(28, 'Algeria', 'DZ', 'DZA', '400f270c7bc2c0bc6958bd8c685a948c.png', 1),\r\n(29, 'American Samoa', 'AS', 'ASM', '60fc6fd2f63fdf099b2fd6367d108c03.png', 1),\r\n(30, 'Andorra', 'AD', 'AND', '8614da910ef483900aee280ff905ee7e.png', 1),\r\n(31, 'Angola', 'AO', 'AGO', 'c4a1fdc5d144bda8bd42c53029eb507e.png', 1),\r\n(32, 'Anguilla', 'AI', 'AIA', 'a83bd15cd6e403c42b968aab038fb957.png', 1),\r\n(33, 'Antigua And Barbuda', 'AG', 'ATG', '22eeb7c940ebd71f592516f7276d8a4e.png', 1),\r\n(34, 'Argentina', 'AR', 'ARG', 'a284b40bb72c785706ce59721980e210.png', 1),\r\n(35, 'Armenia', 'AM', 'ARM', 'c0ee3e3abb30d169ad5813d0dbbd5ad4.png', 1),\r\n(36, 'Aruba', 'AW', 'ABW', '2a48b04947bd1c8a45a0b19bd3cb7d83.png', 1),\r\n(37, 'Australia', 'AU', 'AUS', '430c8e6693b5b0dcf6b8018fad5dd743.png', 1),\r\n(38, 'Austria', 'AT', 'AUT', '7fd12483afe14fba8b9f1c31a5d581d2.png', 1),\r\n(39, 'Azerbaijan', 'AZ', 'AZE', '0564271caff717411e530843589e3710.png', 1),\r\n(40, 'Bahamas', 'BS', 'BHS', '54b737b766d73179eba05134460a1036.png', 1),\r\n(41, 'Bahrain', 'BH', 'BHR', '1a229bd2b2caa66e891bc4c7f87542e5.png', 1),\r\n(42, 'Bangladesh', 'BD', 'BGD', '9e41ee39f8413839a658ea42430c60d8.png', 1),\r\n(43, 'Barbados', 'BB', 'BRB', 'ffd257cb394ed50869fb378b09dc4ba6.png', 1),\r\n(44, 'Belarus', 'BY', 'BLR', '5c442398ca33800d38c9fbcd0c705f3a.png', 1),\r\n(45, 'Belgium', 'BE', 'BEL', '3209d0b2a3e35f8bfa7b2b540a71af59.png', 1),\r\n(46, 'Belize', 'BZ', 'BLZ', 'dd609db925daf6d6be5cd0a774175dcc.png', 1),\r\n(47, 'Benin', 'BJ', 'BEN', '0d6a596a31f610e1d863b1adac0b3c96.png', 1),\r\n(48, 'Bermuda', 'BM', 'BMU', 'a31bee728ebf59958547f2d97f00a404.png', 1),\r\n(49, 'Bhutan', 'BT', 'BTN', '85acd618942317ef8ed6998bcb9dba5e.png', 1),\r\n(50, 'Bolivia', 'BO', 'BOL', '38f4f836ee12763b215bb428f6c9d3ae.png', 1),\r\n(51, 'Bosnia', 'BA', 'BIH', 'ad40a223c31181f619ad6bfac509e81b.png', 1),\r\n(52, 'Botswana', 'BW', 'BWA', '1ee554b3ba7f78d15d07d32bf5911f64.png', 1),\r\n(53, 'Brazil', 'BR', 'BRA', '8cd0769b641c4b6109f1f42e611fda32.png', 1),\r\n(54, 'British Indian Ocean Territory', 'IO', 'IOT', 'cac3c414b00c1824ba9c9d18951ba3ed.png', 1),\r\n(55, 'Brunei', 'BN', 'BRN', '9c6049e61477ec58c636a49973dc3bbd.png', 1),\r\n(56, 'Bulgaria', 'BG', 'BGR', 'f4fcaf84421a1e614e440e4166cb7af9.png', 1),\r\n(57, 'Burkina Faso', 'BF', 'BFA', '5e9506f093722167223016d6fe861de8.png', 1),\r\n(58, 'Burundi', 'BI', 'BDI', '6352b68ac75d3e0a3bcfd84dcabb6e72.png', 1),\r\n(59, 'Cambodia', 'KH', 'KHM', '1b8dc159b93334776fac1add76b89e5c.png', 1),\r\n(60, 'Cameroon', 'CM', 'CMR', '92bae1e9b81905389b5fe3c41308829e.png', 1),\r\n(61, 'Canada', 'CA', 'CAN', 'a3fc814ece1aa301053ec8785b4160e8.png', 1),\r\n(62, 'Cape Verde', 'CV', 'CPV', 'cd7509d21a147b3fc5328425fe687cb2.png', 1),\r\n(63, 'Cayman Islands', 'KY', 'CYM', 'f062ebfe266ff8bde34a566061b32ebd.png', 1),\r\n(64, 'Central African Republic', 'CF', 'CAF', 'd3d77dd39ff87dde439cf8c964992085.png', 1),\r\n(65, 'Chad', 'TD', 'TCD', '799f8a4df8ab251bafcba62388d34bbe.png', 1),\r\n(66, 'Chile', 'CL', 'CHL', 'fa879a54071d4e1e2c0925a98069fa86.png', 1),\r\n(67, 'China', 'CN', 'CHN', '812d61be50d4754b23b661bc34a04a39.png', 1),\r\n(68, 'Christmas Island', 'CX', 'CXR', '15f53443278d0c02fec532f7369d986e.png', 1),\r\n(69, 'Colombia', 'CO', 'COL', '80510f4a43ee9d5478269f46058050d3.png', 1),\r\n(70, 'Comoros', 'KM', 'COM', '03ebafd180179ab0f1117c8ba03481ee.png', 1),\r\n(71, 'Cook Islands', 'CK', 'COK', '374cc1fa6aa54a88c3c272d596081241.png', 1),\r\n(72, 'Costa Rica', 'CR', 'CRI', 'daef44907da0011f358b43f9cd4541e7.png', 1),\r\n(73, 'Côte d''Ivoire', 'CI', 'CIV', '78825c2326b84482eaef407efa272d35.png', 1),\r\n(74, 'Croatia', 'HR', 'HRV', '94e5a7dc4d1d5484483c550b5739e6af.png', 1),\r\n(75, 'Cuba', 'CU', 'CUB', '6c005be1b73d27848796006a8924568a.png', 1),\r\n(76, 'Cyprus', 'CY', 'CYP', '646ac34fdee912b93d6ad4b3f648f665.png', 1),\r\n(77, 'Czech Republic', 'CZ', 'CZE', '5d98a23c660e4877691af6c5507b33eb.png', 1),\r\n(78, 'Democratic Republic of the Congo', 'CD', 'COD', '5978fca70d5b4dfe59dae2d506ac1ed4.png', 1),\r\n(79, 'Denmark', 'DK', 'DNK', '694620a4ed293727a0594ef4d8555f7d.png', 1),\r\n(80, 'Djibouti', 'DJ', 'DJI', 'e275db7fc1518388d071d50f6fd42c47.png', 1),\r\n(81, 'Dominica', 'DM', 'DMA', '276f0be14ce53e10d5cb7271d81accff.png', 1),\r\n(82, 'Dominican Republic', 'DO', 'DOM', '5bac27c54e7935131e1c4f291c5e362c.png', 1),\r\n(83, 'Ecuador', 'EC', 'ECU', 'b291696b6cf49b8d2544135db8e6427d.png', 1),\r\n(84, 'Egypt', 'EG', 'EGY', '374172a9c9421dbe3636e5e5adaa8524.png', 1),\r\n(85, 'El Salvador', 'SV', 'SLV', '5ca863dcd78336d203ad57e879eaa729.png', 1),\r\n(86, 'Equatorial Guinea', 'GQ', 'GNQ', 'f557df9f04d591b79c2458d33bd24bc1.png', 1),\r\n(87, 'Eritrea', 'ER', 'ERI', '8b8caff0d65b11f637c87dcdd10f05a1.png', 1),\r\n(88, 'Estonia', 'EE', 'EST', '6fd621693f445ff2ca3e59199bf473a0.png', 1),\r\n(89, 'Ethiopia', 'ET', 'ETH', '068f16374af8744a2f5387585c554d93.png', 1),\r\n(90, 'Falkland Islands', 'FK', 'FLK', '3e143f272a02da8d6832e2f54071001b.png', 1),\r\n(91, 'Faroe Islands', 'FO', 'FRO', '23331de2803079e2cd62367ced6ec866.png', 1),\r\n(92, 'Fiji', 'FJ', 'FJI', 'c10880dee3473c2276d6d9ef7ac7e18d.png', 1),\r\n(93, 'Finland', 'FI', 'FIN', 'c47e9b63d6a666525cc136f06b2303c8.png', 1),\r\n(94, 'France', 'FR', 'FRA', 'ded0fdb8d8ac3055046b881b4cdf2fdc.png', 1),\r\n(95, 'French Polynesia', 'PF', 'PYF', 'b4c9c824d75fa477309de854fef1aecb.png', 1),\r\n(96, 'Gabon', 'GA', 'GAB', 'e7346fb4cc72a2ae6e74d291fb5c1a18.png', 1),\r\n(97, 'Gambia', 'GM', 'GMB', '43f245cecdd7102694155ee8299618a7.png', 1),\r\n(98, 'Georgia', 'GE', 'GEO', '196893ef9a611e5c385abeb6bba4ecbc.png', 1),\r\n(99, 'Germany', 'DE', 'DEU', '9b959ba56ff97aec2f8ed4317571e5dc.png', 1),\r\n(100, 'Ghana', 'GH', 'GHA', '8891a3e243336631871041277186ef9b.png', 1),\r\n(101, 'Gibraltar', 'GI', 'GIB', '1829221f57b61dea1fb092833e581763.png', 1),\r\n(102, 'Greece', 'GR', 'GRC', 'd8eee37db6416aeaab7162a5c3f8b5b4.png', 1),\r\n(103, 'Greenland', 'GL', 'GRL', 'f6eb8212a364cb716f0666389bfe12c9.png', 1),\r\n(104, 'Grenada', 'GD', 'GRD', '5f5fd9572e8ddac693df60cb4ba0fa44.png', 1),\r\n(105, 'Guam', 'GU', 'GUM', 'e94fccef0f2def525018ca032a8141bb.png', 1),\r\n(106, 'Guatemala', 'GT', 'GTM', '552f92e7611ae98853d1cc7834ceb6f7.png', 1),\r\n(107, 'Guinea', 'GN', 'GIN', 'b2520ac8d32e27f41719593ccd8e6d64.png', 1),\r\n(108, 'Guinea_Bissau', 'GW', 'GNB', '95bf350e37d866fcb1927d7c337e716a.png', 1),\r\n(109, 'Guyana', 'GY', 'GUY', '1f2163bc256b92a4a03487d9428c3708.png', 1),\r\n(110, 'Haiti', 'HT', 'HTI', '28d0b92342df68b356adf62b4fbfbe36.png', 1),\r\n(111, 'Honduras', 'HN', 'HND', '090ece409e01a5cc66b73a0868c39102.png', 1),\r\n(112, 'Hong Kong', 'HK', 'HKG', '638282a51793529e80e40771fefd25f3.png', 1),\r\n(113, 'Hungary', 'HU', 'HUN', '6a3dc3fe2b20f1cdffc88c8ea4bd227a.png', 1),\r\n(114, 'Iceland', 'IS', 'ISL', 'c1e32501535672de955c5a4da11566ce.png', 1),\r\n(115, 'Indonesia', 'ID', 'IDN', '85e40aaa91000e21c42b044fb4c9d03f.png', 1),\r\n(116, 'Iran', 'IR', 'IRN', 'd19b7a75b336b5d8c3e74d1403b8de69.png', 1),\r\n(117, 'Iraq', 'IQ', 'IRQ', 'c4139f299de29be69880d7fd73914aea.png', 1),\r\n(118, 'Ireland', 'IE', 'IRL', '4bc75990f36a8bc5042aacdf0da4e9ce.png', 1),\r\n(119, 'Israel', 'IL', 'ISR', '940e570b62072cca7b718d7ff4822147.png', 1),\r\n(120, 'Italy', 'IT', 'ITA', '2af39e94e93c739bf2fcd40291e2a72a.png', 1),\r\n(121, 'Jamaica', 'JM', 'JAM', 'e777ac9711b136ba8f168b6f80b605ee.png', 1),\r\n(122, 'Japan', 'JP', 'JPN', 'fce9760a35a40026845a660417b66784.png', 1),\r\n(123, 'Jordan', 'JO', 'JOR', 'e4925d7b83214f137b661564451b1eef.png', 1),\r\n(124, 'Kazakhstan', 'KZ', 'KAZ', '0eaed11e059a203646fcf95260d81c52.png', 1),\r\n(125, 'Kiribati', 'KI', 'KIR', 'c014537fb3e43fb7df805877ba6a2150.png', 1),\r\n(126, 'Korea,Democratic People''s  Republic Of', 'KP', 'PRK', '8a320ade62a9f4c6587030a92863a61c.png', 1),\r\n(127, 'korea,Republic Of', 'KR', 'KOR', '9c6db1490bb7e3075159e7959ef34d34.png', 1),\r\n(128, 'Kuwait', 'KW', 'KWT', '5c594be781b21a8559eae66e35a409ec.png', 0),\r\n(129, 'Kyrgyzstan', 'KG', 'KGZ', 'ceb08676ff590d122baf227c65ff3414.png', 1),\r\n(130, 'Laos', 'LA', 'LAO', '048b7c01db092cb65ed93d31a3f0a28e.png', 1),\r\n(131, 'Latvia', 'LV', 'LVA', '7c8be1dcd5dc45bee97ad96cc2fb7e4d.png', 1),\r\n(132, 'Lebanon', 'LB', 'LBN', '3628e64c5a8017d0c0e79ae397e4f0a9.png', 1),\r\n(133, 'Lesotho', 'LS', 'LSO', '13c2850fc0029c187c487df2d82fb0db.png', 1),\r\n(134, 'Liberia', 'LR', 'LBR', '052f1a6524d8a9026662a7f1e1914169.png', 1),\r\n(135, 'Libya', 'LY', 'LBY', '45ccb16f866bc1d5b7765612549c8a79.png', 1),\r\n(136, 'Liechtenstein', 'LI', 'LIE', '6f60a4077e1e34903b1a17271b4180a6.png', 1),\r\n(137, 'Lithuania', 'LT', 'LTU', 'd965d8b92f83a93ee2b78559520cf5f6.png', 1),\r\n(138, 'Luxembourg', 'LU', 'LUX', '3fe51731aee20f1c4feadeed4378e1ff.png', 1),\r\n(139, 'Macao', 'MO', 'MAC', '68624dfb2d00ebff60287991a2b50c4b.png', 1),\r\n(140, 'Macedonia', 'MK', 'MKD', 'd214ba3d337f81bd89865da5d8270c05.png', 1),\r\n(141, 'Madagascar', 'MG', 'MDG', '9caa313fea5b686588322f7aca2e8e9a.png', 1),\r\n(142, 'Malawi', 'MW', 'MWI', '08d425ed09f1e7ded854e61fecbb217d.png', 1),\r\n(143, 'Malaysia', 'MY', 'MYS', 'dadd1785f78fa264c47caaac5d9f99a3.png', 1),\r\n(144, 'Maldives', 'MV', 'MDV', 'aaccf4f5fa6e1413c42fd89217555f70.png', 1),\r\n(145, 'Mali', 'ML', 'MLI', 'f0019cc600d5c0030873f0da68029aed.png', 1),\r\n(146, 'Malta', 'MT', 'MLT', '1050121ecc6570c81d105509c84a6b98.png', 1),\r\n(147, 'Marshall Islands', 'MH', 'MHL', '3d92e137bd1558cd7b36532063589d75.png', 1),\r\n(148, 'Martinique', 'MQ', 'MTQ', '51efd43e0b728220c6d6814cd43f5f6c.png', 1),\r\n(149, 'Mauritania', 'MR', 'MRT', 'f22fd930ef46540afb40eb4991877182.png', 1),\r\n(150, 'Mauritius', 'MU', 'MUS', '13f6e478e0dacc4ad5340ec3a4e95c05.png', 1),\r\n(151, 'Mexico', 'MX', 'MEX', '0ad9adf3df98db6efbcc11ca1b8b99ec.png', 1),\r\n(152, 'Micronesia', 'FM', 'FSM', '789c571d4594a2b1894de4f529f76d3a.png', 1),\r\n(153, 'Moldova', 'MD', 'MDA', '3b59ab5ba3dd668d9581991a1ab48f17.png', 1),\r\n(154, 'Monaco', 'MC', 'MCO', '413de0c27a62a439d1b1faef59c78ce9.png', 1),\r\n(155, 'Mongolia', 'MN', 'MNG', 'bcf72c21a6783e9771c658351ba5092f.png', 1),\r\n(156, 'Montserrat', 'MS', 'MSR', '46b7c1dd58a5c7a953a4a21e484c5d97.png', 1),\r\n(157, 'Morocco', 'MA', 'MAR', 'e40408e87f106961d3aecf0333d6896b.png', 1),\r\n(158, 'Mozambique', 'MZ', 'MOZ', '1909ace3439f1aab03a892c9b3430ef1.png', 1),\r\n(159, 'Myanmar', 'MM', 'MMR', '76d5cb3e0acf6e02c1145a645e3062f4.png', 1),\r\n(160, 'Namibia', 'NA', 'NAM', 'ce0a0cc9b730a5078ba33c4616911f28.png', 1),\r\n(161, 'Nauru', 'NR', 'NRU', 'ebc29a9508a198ec03d145d85e13e010.png', 1),\r\n(162, 'Nepal', 'NP', 'NPL', '3a0d768cc3c362eb900117e55bebb947.png', 1),\r\n(163, 'Netherlands', 'NL', 'NLD', '32d6c7fe0037a8017d3afb20835a161c.png', 1),\r\n(164, 'Netherlands Antilles', 'AN', 'ANT', '461eba6a3af5385365a8e850a51c3b64.png', 1),\r\n(165, 'New Zealand', 'NZ', 'NZL', 'd9e68a36ce3d0348812d45f59114e2d1.png', 1),\r\n(166, 'Nicaragua', 'NI', 'NIC', '91cd66c1eae988815faceea0c75a9172.png', 1),\r\n(167, 'Niger', 'NE', 'NER', '721c2414ce07fe3c6476839e33f4e282.png', 1),\r\n(168, 'Nigeria', 'NG', 'NGA', '596c245b2784227f433fa2a656a6f54b.png', 1),\r\n(169, 'Niue', 'NU', 'NIU', '32e3688c1a501d24243e31209be88d54.png', 1),\r\n(170, 'Norfolk Island', 'NF', 'NFK', '123dfbd998c4739ae32c44b0f0f5d71e.png', 1),\r\n(171, 'Norway', 'NO', 'NOR', '0f7799c7337aa6723f1732e9cc94571b.png', 1),\r\n(172, 'Oman', 'OM', 'OMN', '27a4a05f971fbab7d5d9791f563e5a89.png', 1),\r\n(173, 'Pakistan', 'PK', 'PAK', 'f38f49f0afd5c20b0ab3f08e1ade748b.png', 1),\r\n(174, 'Palau', 'PW', 'PLW', 'efaf40642693e83962d7acd430784ebc.png', 1),\r\n(175, 'Panama', 'PA', 'PAN', '3ffdada27bbe43d907c7272dc57cccf0.png', 1),\r\n(176, 'Papua New Guinea', 'PG', 'PNG', 'edd711cdf03bae66534a74e5053d0628.png', 1),\r\n(177, 'Paraguay', 'PY', 'PRY', '57abc9037f0ac6f582f555d8acd5c35c.png', 1),\r\n(178, 'Peru', 'PE', 'PER', 'a82b9437e2ecd60c39b79c32fb536343.png', 1),\r\n(179, 'Philippines', 'PH', 'PHL', '3b22c8d5006870590cbdf4abad4a96db.png', 1),\r\n(180, 'Pitcairn Islands', 'PN', 'PCN', '83f4548704013188caac1009250d9a22.png', 1),\r\n(181, 'Poland', 'PL', 'POL', '99d991b8f36893326d4f2edcc614bd46.png', 1),\r\n(182, 'Portugal', 'PT', 'PRT', '124ea146ee25cf08798a778025426183.png', 1),\r\n(183, 'Puerto Rico', 'PR', 'PRI', '87bd0acd88887be43f1778c2714502a2.png', 1),\r\n(184, 'Qatar', 'QA', 'QAT', '816fa012ae915b789aa53f8abb8666cd.png', 1),\r\n(185, 'Republic of the  Congo', 'CG', 'COG', 'c8d9e6ed75afda30a420f00929da5aed.png', 1),\r\n(186, 'Russian Federation', 'RU', 'RUS', 'feb3251a5d74b587aefb5b5d30abfa37.png', 1),\r\n(187, 'Rwanda', 'RW', 'RWA', '8a5304752898a80446c9d7c38cb9c477.png', 1),\r\n(188, 'Saint Kitts and Nevis', 'KN', 'KNA', '7ada67d32066ed4ff9365010717f54ae.png', 1),\r\n(189, 'Saint Lucia', 'LC', 'LCA', '667b881a85ed494600cf88f252a3f2ed.png', 1),\r\n(190, 'Saint Pierre', 'PM', 'SPM', 'cc263ec58c078f9405273f966807446d.png', 1),\r\n(191, 'Saint Vicent and the Grenadines', 'VC', 'VCT', '15d613b03de119d23c4e915582ca2c78.png', 1),\r\n(192, 'Samoa', 'WS', 'WSM', '767012da23117a1eb689199d9184ced1.png', 1),\r\n(193, 'San Marino', 'SM', 'SMR', 'd81245582ec652c89005f6c2e9a85c05.png', 1),\r\n(194, 'Sao Tomé and Príncipe', 'ST', 'STP', '329c085f6c7aa971e2757e2fb9ea3e3d.png', 1),\r\n(195, 'Saudi Arabia', 'SA', 'SAU', '1fe712ea7fcce73ec2fb754f1a78fa14.png', 1),\r\n(196, 'Senegal', 'SN', 'SEN', '22f9269ba5007cabec49706a56af3456.png', 1),\r\n(197, 'Serbia and Montenegro', 'CS', 'SCG', '5973c162b447b67105d902f5682a1ab6.png', 1),\r\n(198, 'Seychelles', 'SC', 'SYC', '03e4953046e5a2bd628437d15a3dff15.png', 1),\r\n(199, 'Sierra Leone', 'SL', 'SLE', '9a00caae732efa0c33d46995205f0250.png', 1),\r\n(200, 'Singapore', 'SG', 'SGP', 'c3574a08f1b791d307023c458b131079.png', 1),\r\n(201, 'Slovakia', 'SK', 'SVK', '89b86f0a7ad34c3c8ba5d466e310333a.png', 1),\r\n(202, 'Slovenia', 'SI', 'SVN', '3273b7699ae86a272f7c208357d42218.png', 1),\r\n(203, 'Soloman Islands', 'SB', 'SLB', 'fa41e2752bbc67d44e7c2d4bcb352c59.png', 1),\r\n(204, 'Somalia', 'SO', 'SOM', '01892aa9347d370eeed62f29910c79e0.png', 1),\r\n(205, 'South Africa', 'ZA', 'ZAF', 'ef76c0b3650e2751c09f25cc360a3a32.png', 1),\r\n(206, 'South Georgia', 'GS', 'SGS', 'c52aa6e69777be98c2b60e4adc1c0e3a.png', 1),\r\n(207, 'Spain', 'ES', 'ESP', '58fb42154a2fc1e7c0b0ec8c94547fee.png', 1),\r\n(208, 'Sri Lanka', 'LK', 'LKA', '23d5ed96f24e6ac65639fbc4c492b6cf.png', 1),\r\n(209, 'Sudan', 'SD', 'SDN', '38ff2229801cee5f053ec14b4c3e5517.png', 1),\r\n(210, 'Suriname', 'SR', 'SUR', 'b82127c86ddfabb8e81617b73a2a66db.png', 1),\r\n(211, 'Swaziland', 'SZ', 'SWZ', '662d8542d65ec9ce364a84ff42960084.png', 1),\r\n(212, 'Sweden', 'SE', 'SWE', '9841d8812e56a50b76181f92574bf388.png', 1),\r\n(213, 'Switzerland', 'CH', 'CHE', '01cec16897f98c996fdb608af2b26ca4.png', 1),\r\n(214, 'Syria', 'SY', 'SYR', '0f942a30f491904b70e26b248a02a009.png', 1),\r\n(215, 'Taiwan', 'TW', 'TWN', '7418a90d5980ac4b351553e9d0c190ae.png', 1),\r\n(216, 'Tajikistan', 'TJ', 'TJK', 'bef50a1b4f82b4b8e9324b1b1d97fe41.png', 1),\r\n(217, 'Tanzania', 'TZ', 'TZA', '61091fc9dfc41c1f74e24ba6c9e5c35c.png', 1),\r\n(218, 'Thailand', 'TH', 'THA', 'b508111bb2a3da48097d8e476ade7ad8.png', 1),\r\n(219, 'Timor Leste', 'TL', 'TLS', '90e07b230c8a990cee19535c162c45d5.png', 1),\r\n(220, 'Togo', 'TG', 'TGO', '9832e9c741cc66e2cac344620b3865cd.png', 1),\r\n(221, 'Tonga', 'TO', 'TON', '3765dcaf9319c295c5e96f6c85683b26.png', 1),\r\n(222, 'Trinidad and Tobago', 'TT', 'TTO', '4cad3f477599fb5f1da4104432d41508.png', 1),\r\n(223, 'Tunisia', 'TN', 'TUN', '08807d2c3cc880bda5faa5647adad1bb.png', 1),\r\n(224, 'Turkey', 'TR', 'TUR', 'b3574792044a1f5962c6a72bbd0c1452.png', 1),\r\n(225, 'Turkmenistan', 'TM', 'TKM', '0a0d3b496951809ba48fec7bc69ac750.png', 1),\r\n(226, 'Turks and Caicos Islands', 'TC', 'TCA', '980c994940fa5a799d439ed138e4c663.png', 1),\r\n(227, 'Tuvalu', 'TV', 'TUV', '220c8a189a341e6448ce209f1f113c00.png', 1),\r\n(228, 'UAE', 'AE', 'ARE', 'f255715e4138eb8588e588118bb13584.png', 1),\r\n(229, 'Uganda', 'UG', 'UGA', '4c33fb0ae9e1feb0a1dc3c8082bfb777.png', 1),\r\n(230, 'Ukraine', 'UA', 'UKR', '1abeb02ee28691eceb296249579f13e6.png', 1),\r\n(231, 'United Kingdom', 'GB', 'GBR', '29e3de954ae3a6b44c3728233dc899e7.png', 1),\r\n(232, 'Uruguay', 'UY', 'URY', '29f0c7574b5671dd5e34d49284070578.png', 1),\r\n(233, 'Uzbekistan', 'UZ', 'UZB', '1427a459809f849882a1537d95332df4.png', 1),\r\n(234, 'Vanuatu', 'VU', 'VUT', 'bf97f00fdf10a2d6476545de39f61995.png', 1),\r\n(235, 'Vatican City', 'VA', 'VAT', '578be996d51076cb5e06f7c8e60f717b.png', 1),\r\n(236, 'Venezuela', 'VE', 'VEN', '5d895e9f07a396c8d4217bf98612659f.png', 1),\r\n(237, 'Vietnam', 'VN', 'VNM', 'f0ab7897857e049acbff3133c3a59238.png', 1),\r\n(238, 'Wallis and Futuna', 'WF', 'WLF', 'ba8af664e31810b00779c15e782d8388.png', 1),\r\n(239, 'Yemen', 'YE', 'YEM', 'fc7f6435c89daaabaaf0b692ee16ab74.png', 1),\r\n(240, 'Zambia', 'ZM', 'ZMB', 'ad22657beefb25f766479c645d77eb9c.png', 1),\r\n(241, 'Zimbabwe', 'ZW', 'ZWE', '57208889b660755eb816e468086f3b96.png', 1),\r\n(242, 'US Virgin Islands', 'VI', 'VIR', 'cfbbd0d8e62d2e9d71a762ca1fc17677.png', 1);" );
}
if ( $flag_chat2 == 0 ){
    mysql_query( "CREATE TABLE IF NOT EXISTS `nesote_chat_login_status` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `user_id` int(11) NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `ip_address` varchar(255) NOT NULL,\r\n  `status` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
    mysql_query( "INSERT INTO `nesote_chat_login_status` (`id`, `user_id`, `time`, `ip_address`, `status`) VALUES\r\n(1, 1, 1368184092, '117.196.172.135', 0);" );
}
if ( $flag_chat3 == 0 ){
    mysql_query( "CREATE TABLE IF NOT EXISTS `nesote_chat_public_user` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `user_sectionid` int(11) NOT NULL,\r\n  `username` varchar(444) NOT NULL,\r\n  `email` varchar(444) NOT NULL,\r\n  `name` varchar(444) NOT NULL,\r\n  `joindate` varchar(444) NOT NULL,\r\n  `user_ip` varchar(444) NOT NULL,\r\n  `browser` varchar(444) NOT NULL,\r\n  `status` int(11) NOT NULL COMMENT 'status-1(public usees),2-admin user',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
    mysql_query( "INSERT INTO `nesote_chat_public_user` (`id`, `user_sectionid`, `username`, `email`, `name`, `joindate`, `user_ip`, `browser`, `status`) VALUES\r\n(1, 721854427, 'Admin', '', 'Admin', '', '', '', 2);" );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `nesote_chat_session` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `time` int(11) NOT NULL,\r\n  `xml_status` int(11) NOT NULL,\r\n  `group_status` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
mysql_query( "CREATE TABLE IF NOT EXISTS `nesote_chat_session_users` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `chat_id` int(11) NOT NULL,\r\n  `user_id` int(11) NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `xml_status` int(11) NOT NULL,\r\n  `typing_status` int(11) NOT NULL,\r\n  `active_status` int(11) NOT NULL,\r\n  `present_identified_time` int(11) NOT NULL,\r\n  `initiators` varchar(256) NOT NULL,\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
mysql_query( "CREATE TABLE IF NOT EXISTS `nesote_chat_temporary_messages` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `chat_id` int(11) NOT NULL,\r\n  `sender` int(11) NOT NULL,\r\n  `responders` varchar(256) NOT NULL,\r\n  `message` text NOT NULL,\r\n  `time` int(11) NOT NULL,\r\n  `read_flag` int(11) NOT NULL,\r\n  PRIMARY KEY  (`id`),\r\n  KEY `chat_id` (`chat_id`),\r\n  KEY `sender` (`sender`),\r\n  KEY `responders` (`responders`),\r\n  KEY `read_flag` (`read_flag`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
mysql_query( "CREATE TABLE IF NOT EXISTS `nesote_chat_user_friend` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `user_id` int(11) NOT NULL,\r\n  `friend_user_id` int(11) NOT NULL,\r\n  `request_date_time` int(11) NOT NULL,\r\n  `response_date_time` int(11) NOT NULL,\r\n  `request_message` varchar(255) NOT NULL,\r\n  `request_status` enum('accepted','rejected','pending','blocked') NOT NULL default 'pending',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
if ( $mysql->total( "ppc_settings", "name='default_chat_status'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'default_chat_status', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='chat_status'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'chat_status', '0');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='offline_image'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'offline_image', 'offline_image.png');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='online_image'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'online_image', 'online_image.png');" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `adserver_public_images` (\r\n `id` int(11) NOT NULL AUTO_INCREMENT,\r\n `image_name` varchar(444) NOT NULL,\r\n `date` varchar(444) NOT NULL,\r\n `status` varchar(444) NOT NULL,\r\n PRIMARY KEY (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
if ( $mysql->total( "adserver_public_images" ) == 0 )
{
    mysql_query( "INSERT INTO `adserver_public_images` (`id`, `image_name`, `date`, `status`) VALUES\r\n(1, 'd91c17982a3727764836e08f5ebacbd8.png', '1369375007', '1'),\r\n(2, 'fd1849c2514783e8380745529e1b2911.png', '1369376623', '1'),\r\n(3, '8aef80b7d701d71f7675e9c291243e7a.png', '1369382664', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='public_background'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'public_background', 'public_background.jpg');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='advertiser_image'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'advertiser_image', 'advertiser_image.png');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='publisher_image'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'publisher_image', 'publisher_image.png');" );
    echo __Func010__( );
}
mysql_query( "CREATE TABLE IF NOT EXISTS `ad_logos_details` (\r\n  `id` int(11) NOT NULL auto_increment,\r\n  `cid` int(11) NOT NULL,\r\n  `name` varchar(444) NOT NULL,\r\n  `ad_logos_name` varchar(444) NOT NULL,\r\n  `status` int(11) NOT NULL COMMENT '0-block,1-active,-1:pending',\r\n  `user_status` int(11) NOT NULL default '0' COMMENT '0-admin,1-publisher,2-advertiser',\r\n  PRIMARY KEY  (`id`)\r\n) ENGINE=InnoDB {$charset_collation} AUTO_INCREMENT=1 ;" );
if ( $mysql->total( "ppc_settings", "name='bgimage_type'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'bgimage_type', '1');" );
    echo __Func010__( );
}
if ( $mysql->total( "ppc_settings", "name='chat_visible_status'" ) == 0 )
{
    mysql_query( "INSERT INTO `ppc_settings` VALUES ('0', 'ppc', 'chat_visible_status', '0');" );
    echo __Func010__( );
}
if ( $mysql->echo_one( "select id from system_keywords where  1 limit 0,1" ) == "" )
{
    include( "build-keywords.php" );
}*/
include( "admin.header.inc.php" );
include( "ppc-first-settings.php" );
include( "admin.footer.inc.php" );
function addTable(){
	
}
?>
