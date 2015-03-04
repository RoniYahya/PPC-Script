<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
//echo $mysql_dbname; exit;
//echo "select value from ".$db_tableprefix."ppc_settings where name ='chat_status'"; exit;
			$sql = mysql_query("select value from ".$db_tableprefix."ppc_settings where name ='chat_status'");
			$rows=mysql_fetch_row($sql);
			echo $rows[0]; exit;
		?>