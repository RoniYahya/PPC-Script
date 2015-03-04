<?php

$table_fields_collation=$mysql->echo_one("select value from ppc_settings where name ='table_fields_collation'");
if(strcasecmp($table_fields_collation,"utf8_unicode_ci")==0)
{
	mysql_query("set names utf8 collate utf8_unicode_ci");
}	

$resultssettings=mysql_query("select name,value from ppc_settings");

while($settingsrow=mysql_fetch_row($resultssettings))
{	
//echo $settingsrow[0]. "          ====>          ".$settingsrow[1]."<br>\n";
eval('$'.$settingsrow[0].'=\''.addslashes( $settingsrow[1]).'\';');	
if($settingsrow[0]=='ad_rotation')
	{
		if($settingsrow[1]!="3")
		{
			eval('$ad_ageing_factor=\'0\';');	
		}
		else
		{
			eval('$ad_ageing_factor=\'1\';');
		}
	   
	}
}

if ($timezone_change!="")
{
date_default_timezone_set($timezone_change);

}
ini_set('default_charset', $ad_display_char_encoding);

//die;
?>