<?
/**
 * JD Kits
 * ȫ�������ļ�
 *
 * writed by jimmy 2005.07.18            
 * ���ܣ�                                
 *      �����ļ�·����Ϣ                 
 *      �������ݿ�������Ϣ               
 *      ��ʼ���û�����                   
 *      ���û��������� function.inc.php  
 *
 * @author jimmy 
 * @version 2005.07.18
 * @update 20071023 Ǩ�����Ϸ����� by jimmy
 */
ini_set("display_errors","on");
error_reporting(7);
/***************************************
 * ��������
 */

// ��ʼ����������
$root_path          = "/YOKA/HTML/monitor.yoka.com/pub";                                 //��Ŀ¼
$root_url           = "http://monitor.yoka.com/pub";                  //��URL
$cache_path         = "$root_path/cache";                       //��̬ҳ����
$tmp_path           = "$root_path/tmp";                                   //��ʱĿ¼
$log_file           = "$root_path/log";                  //ϵͳ��־
$cookiepath         = "/";                                      //Cookie��Ч·��
$cookiedomain       = "";                                       //Cookie��Ч��
$sessiontimeout     = 3600;                                     //Session��ʱ
$shutdownqueries    = array();                                  //��β��������
$noshutdownfunc     = 0;                                        //��β������ֹ

$wget_path			= "/usr/bin/wget";							  	//wget·��

$page_title         = "YOKA CMS Interface";                             //Title
$table_style        ="border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor=#111111 width=95%"; //tr bgcolor=#868786
/***************************************
 * ��������
 */

// ������� registerglobals=off
if ( function_exists('ini_get') ) {
	$onoff = ini_get('register_globals');
} else {
	$onoff = get_cfg_var('register_globals');
}
if ($onoff != 1) {
	@extract($_SERVER, EXTR_SKIP);
	@extract($_COOKIE, EXTR_SKIP);
	@extract($_FILES, EXTR_SKIP);
	@extract($_POST, EXTR_SKIP);
	@extract($_GET, EXTR_SKIP);
	@extract($_ENV, EXTR_SKIP);
}
if(isset($PHPSESSID)) { 
session_id($PHPSESSID); 
}
else $PHPSESSID = session_id();
// ������� magic_quotes=off
function stripslashesarray (&$arr) {
  while (list($key,$val)=each($arr)) {
    if ($key!="templatesused" and $key!="argc" and $key!="argv") {
			if (is_string($val) AND (strtoupper($key)!=$key OR ("".intval($key)=="$key"))) {
				$arr["$key"] = stripslashes($val);
			} else if (is_array($val) AND ($key == '_POST' OR $key == '_GET' OR strtoupper($key)!=$key)) {
				$arr["$key"] = stripslashesarray($val);
			}
	  }
  }
  return $arr;
}
if (get_magic_quotes_gpc() and is_array($GLOBALS)) {
  if (isset($attachment)) {
    $GLOBALS['attachment'] = addslashes($GLOBALS['attachment']);
  }
  if (isset($avatarfile)) {
    $GLOBALS['avatarfile'] = addslashes($GLOBALS['avatarfile']);
  }
  $GLOBALS = stripslashesarray($GLOBALS);
}
set_magic_quotes_runtime(0);

if ($_SERVER['SCRIPT_NAME'] and substr($_SERVER['SCRIPT_NAME'] , -strlen('.php')) == '.php') {
	$currenturl = strtolower($_SERVER['SCRIPT_NAME']);
	$currentfullurl= $_SERVER['REQUEST_URI'];//��������·��
} elseif ($_SERVER['REDIRECT_URL'] and substr($_SERVER['REDIRECT_URL'] , -strlen('.php')) == '.php') {
	$currenturl = strtolower($_SERVER['REDIRECT_URL']);
	$currentfullurl = $_SERVER['REDIRECT_URL'];
} else {
	$currenturl = strtolower($_SERVER['PHP_SELF']);
	$currentfullurl= $_SERVER['REQUEST_URI'];//��������·��
}

/***************************************
 * session Ԥ����
 */

/***************************************
 * ���ݿ�����
 */
$db_define['DB_server']       = array("Lang"=>"latin1","Host"=>"10.0.0.122:3306", "User"=>"yokacms", "Password"=>"yvch500M", "Database"=>"NewsSum");
$db_define['DB_glb']          = $db_define['DB_server'];
$db_define['DB_ymall']       = array("Lang"=>"utf8","Host"=>"10.0.1.31:6926", "User"=>"yoka", "Password"=>"5Kg9QDOS3czy", "Database"=>"ymall10");


$db_edit_obj=$db_define['DB_server'];


include("$root_path/include/db_mysql.inc.php");
foreach($db_define as $name=> $db_tmp)
{
    $tmp_str = "
    class {$name} extends DB_Sql {
      var \$Host     = '{$db_tmp['Host']}';
      var \$Database = '{$db_tmp['Database']}';
      var \$User     = '{$db_tmp['User']}';
      var \$Password = '{$db_tmp['Password']}';
      var \$Lang     = '{$db_tmp['Lang']}';
    }
    ";
    eval($tmp_str);
}

/***************************************
 * ���ú�������
 */
include_once("$root_path/include/functions.inc.php");
?>
