<?
	$g_lang = "lang-chinese_gb2312.php";
	$g_root_dir = "/";
	$g_test = 1;
	
	$dir_wwwroot = "/usr/local/apache/htdocs/";
	$url_wwwroot = "/";
	$dir_template = "/usr/local/apache/htdocs/XPlus1.3/Dept.MG/MagLaunch/";
	$dir_upload = "/usr/local/apache/htdocs/down/";
	$dir_download = "/usr/local/apache/htdocs/down/";
	$dir_test_pages = "/usr/local/apache/htdocs/";
	$url_test_pages = "/";
	
	$DB = array
	(     //example : "Name" => array("IP", "DB", "UserName", "Password"),
        "db1" => array("220.194.55.234", "XPlus1_3", "apuser", "34erdfcv"),
        "db2" => array("220.194.55.234", "XPLUS_Users", "xplus", "xplus")
	);
	
	
	###################################我是分隔线###################################
	
	
	function db_connect($idx) 
	{		
		global $DB,$g_dbh;
		$g_dbh = mysql_connect($DB[$idx][0], $DB[$idx][2], $DB[$idx][3]) or die(mysql_error());
		mysql_select_db($DB[$idx][1], $g_dbh) or die("[db_connect]".mysql_error());
	}
	
	include_once("debug.inc.php");
	require_once("db_define.inc.php");

	function db_connect2($idx) 
	{		
		global $db_users,$dbh;
		$dbh = mysql_connect($db_users[$idx][host], $db_users[$idx][user], $db_users[$idx][password]) or die(mysql_error());
		mysql_select_db($db_users[$idx][db] ,$dbh) or die("[db_connect2]".mysql_error());
	}
	
?>
