<?
/**
 * JD kits 1.3 版本
 * 数据库设计参考
 *
 * @author jimmy 
 * @version 2004.09.15
 * @param
 */
include ("config.inc.php");

/*
参数
*/
$no_doc = 1;        //如果没有doc表，请修改为1

showhead("数据库内容");
?>
<h1>JimmyDong -  结婚数据库设计参考</h1>
<hr size=1>
<?
$q=new DB_glb;
$q2=new DB_glb;
include("db_info.inc.php");

if($modify)$modify=0;
else $modify=1;
?>
<hr size=1>
<p><a href=?modify=<?=$modify?>>编辑模式</a> | <a href=http://web1.local/mysql/index.php>PhpMyAdmin</a> | <a href=db_usr.php>用户相关库</a>
<p>
<p>

