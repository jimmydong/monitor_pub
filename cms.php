<?
/**
 * CMS interface
 * by jimmy 2012.02.17
 */
include('include/config.inc.php');

$max_limit = 1000;
$default_limit = 999;
$sid = $_REQUEST['sid'];
$eid = $_REQUEST['eid']; if($eid < $sid)$eid=$sid+$default_limit;
$aid = $_REQUEST['aid'];
$debug = $_REQUEST['debug'];

header('Content-Type: text/html;charset=utf-8');
if($sid < 1 || $aid!='yoka.com' || ($eid - $sid) > $max_limit){
echo "Error! Usage: xxxx?sid=xxx&eid=xxx&aid=xxx";
exit;
}

$q = new DB_glb;
$sql = "SELECT * FROM sp_t1 WHERE d_id >= '{$sid}' AND d_id <= '{$eid}'";
if($debug){
$q->query_all($sql);
}else{
$q->query($sql);
while($r=$q->next_record()){
$t[ArticleID] = $r[d_id];
$t[Category] = 'cms';
$t[Category2] = $r[sp_f6];
$t[ArticleTitle] = $r[sp_f5];
$t[ArticleBrief] = $r[sp_f4];
$t[ArticleContent] = $r[sp_f11];
$t[ArticleTag] = $r[sp_f15];
$t[Creator] = $r[creator];
$t[CreateDate] = $r[createdate].' '.$r[createtime];
$t[ArticleSource] = $r[sp_f8];
$t[ArticleURL] = $r[url_1];
$t[isFirst] = ($r[sp_f10] == 'yes')?1:0;
$t[Published] = ($r[published_1]=='y')?1:0;
$t[Deleted] = ($r[deleted]=='y')?1:0;
foreach($t as $k=>$v){
$t[$k]=iconv('gbk','UTF-8//IGNORE',$v);
//var_dump($r[$k]);
}
echo json_encode($t)."\n<!--###".date("Y-m-d")."###-->\n";
}
}
echo "<!--done ".date("Y-m-d H:i:s")." -->";

