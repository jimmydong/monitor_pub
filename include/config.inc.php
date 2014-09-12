<?
/**
 * JD Kits
 * 全局配置文件
 *
 * writed by jimmy 2005.07.18            
 * 功能：                                
 *      配置文件路径信息                 
 *      配置数据库连接信息               
 *      初始化用户环境                   
 *      调用基本函数库 function.inc.php  
 *
 * @author jimmy 
 * @version 2005.07.18
 * @update 20071023 迁移线上服务器 by jimmy
 */
ini_set("display_errors","on");
error_reporting(7);
/***************************************
 * 变量定义
 */

// 初始化本机设置
$root_path          = "/YOKA/HTML/monitor.yoka.com/pub";                                 //主目录
$root_url           = "http://monitor.yoka.com/pub";                  //根URL
$cache_path         = "$root_path/cache";                       //静态页缓冲
$tmp_path           = "$root_path/tmp";                                   //临时目录
$log_file           = "$root_path/log";                  //系统日志
$cookiepath         = "/";                                      //Cookie有效路径
$cookiedomain       = "";                                       //Cookie有效域
$sessiontimeout     = 3600;                                     //Session超时
$shutdownqueries    = array();                                  //收尾函数数组
$noshutdownfunc     = 0;                                        //收尾函数禁止

$wget_path			= "/usr/bin/wget";							  	//wget路径

$page_title         = "YOKA CMS Interface";                             //Title
$table_style        ="border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse' bordercolor=#111111 width=95%"; //tr bgcolor=#868786
/***************************************
 * 变量处理
 */

// 处理变量 registerglobals=off
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
// 处理变量 magic_quotes=off
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
	$currentfullurl= $_SERVER['REQUEST_URI'];//含参数的路径
} elseif ($_SERVER['REDIRECT_URL'] and substr($_SERVER['REDIRECT_URL'] , -strlen('.php')) == '.php') {
	$currenturl = strtolower($_SERVER['REDIRECT_URL']);
	$currentfullurl = $_SERVER['REDIRECT_URL'];
} else {
	$currenturl = strtolower($_SERVER['PHP_SELF']);
	$currentfullurl= $_SERVER['REQUEST_URI'];//含参数的路径
}

/***************************************
 * session 预处理
 */

/***************************************
 * 数据库声明
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
 * 共用函数声明
 */
include_once("$root_path/include/functions.inc.php");
?>
