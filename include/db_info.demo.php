<?
/**
 * JD kits 1.3 �汾
 * ���ݿ���Ʋο�
 *
 * @author jimmy 
 * @version 2004.09.15
 * @param
 */
include ("config.inc.php");

/*
����
*/
$no_doc = 1;        //���û��doc�����޸�Ϊ1

showhead("���ݿ�����");
?>
<h1>JimmyDong -  ������ݿ���Ʋο�</h1>
<hr size=1>
<?
$q=new DB_glb;
$q2=new DB_glb;
include("db_info.inc.php");

if($modify)$modify=0;
else $modify=1;
?>
<hr size=1>
<p><a href=?modify=<?=$modify?>>�༭ģʽ</a> | <a href=http://web1.local/mysql/index.php>PhpMyAdmin</a> | <a href=db_usr.php>�û���ؿ�</a>
<p>
<p>

