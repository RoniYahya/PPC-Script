<?php
include '../admin/config.inc.php';
define('host', $mysql_server);
define('user', $mysql_username);
define('pass', $mysql_password);
define('db', $mysql_dbname);

function checkstep1(){
	@$mysqli = new mysqli(host , user , pass , db) ;
	return ($mysqli->connect_errno) ? false : true;
}

function checkstep2(){
	@$mysqli = new mysqli(host , user , pass , db) ;
	$result = @$mysqli->query('SHOW TABLES' ) ;
	$i=0;
	$list = array();
	if(!empty($result)){
	while ($row = $result->fetch_row()){
		if($row[0]!="view_keywords"){
			$list[] = $row[0];
		 }
		$i++;
	}}
	return (in_array('admin_payment_details', $list) && in_array('ppc_settings', $list) && in_array('yearly_referral_statistics', $list)) ? true : false;
}

function checkstep3(){
	@$mysqli = new mysqli(host , user , pass , db) ;
	$Nbr = $mysqli->query("select * from ppc_settings");
	return (count($Nbr->fetch_all()) == 0) ? false : true;
}


//print_r($tableList);
?><html>
<head>
<body>
<div style="width:50%; margin:auto;">
<h1>Insttal Inout Adserver Ultimate Nulled - By Hg wells</h1>
<b>intall</b><br>
Salut, pour installer ce script nulled vous devez modifier les information a la base de donnée.<br>
comme partout (host, user, password, database) rien de plus.<br>
<br>
Quand script installer n'oublier pas de supprimé le dossier install<br>
<br>
Hi, to install this nulled script you must change the information in the database.<br>
as everywhere (host, user, password, database) nothing more.<br>
<br>
When install script do not forget to delete the install folder<br>
<br>
<br>
Step 1 config database (<?php echo (checkstep1() == true) ? 'Ok Next Step 2' : 'Edit file "http://yourdomain.ltd/admin/config.inc.php" and add your database information';?>)<br>
Step 2 install table (<?php echo (checkstep1() == true && checkstep2() == true) ? 'Ok Next Step 3' : '<a href="install.php?install=table">Install table</a>';?>)<br>
Step 3 install data (<?php echo (checkstep1() == true && checkstep2() == true && checkstep3() == true) ? 'Ok Next Step 4' : ((checkstep1() == true && checkstep2() == true) ? '<a href="install.php?install=data">Install table</a>' : 'Step 1 and 2 first');?>)<br>
Step 4 remove install script (<?php echo (checkstep1() == true && checkstep2() == true && checkstep3() == true) ? 'Enjoy you have successfull installed this script' : ' Complete step 1, 2, and 3 please ' ;?>)<br>
</div>
</body>
</head></html>
