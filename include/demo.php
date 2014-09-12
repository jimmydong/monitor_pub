<?
/**
 * JD
 * 全局配置文件
 *
 * writed by jimmy 2004.09.10            
 * 功能：                                
 */
include_once("include/include.inc.php");
checkuser("admin");

$q=new DB_glb;
$table=" CMS";
$q->query("SELECT * FROM $table WHERE username='jimmy'");
while($q->next_record());
{
    $newsinfo=$q->Record;
    $line=$q->Row;
    print_r($newsinfo);
}

$newsinfo=$q->query_first("SELECT * FROM $table WHERE news_id=1");
print_r($newsinfo);

$q->query("INSERT INTO $table SET (username,newsid) VALUES ('jimmy','3')");
echo $q->Insert_ID;
 
//DEBUG
$q->fquery("SELECT * FROM $table WHERE username='jimmy'");
$q->query_all("SELECT * FROM $table WHERE username='jimmy'");

//Function
trace($table);
$filename=date("Ymd")."_".radom_id(4).$ext;
redirect("/");
