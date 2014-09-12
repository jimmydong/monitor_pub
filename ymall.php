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

$q = new DB_ymall;
$q->Lang = 'utf8';
$sql = "SELECT * FROM ecm_goods WHERE goods_id >= '{$sid}' AND goods_id <= '{$eid}' ORDER BY goods_id";
if($debug){
$q->query_all($sql);
}else{
$q->query($sql);
while($r=$q->next_record()){
$t[ArticleID] = $r[goods_id];
$t[Category] = 'ymall';
$t[Category2] = $r[cate_name];
$t[ArticleTitle] = $r[goods_name];
$t[ArticleBrief] = '';
$t[ArticleContent] = $r[description];
$t[ArticleTag] = $r[tags];
$t[Creator] = $r[store_id];
$t[CreateDate] = date('Y-m-d H:i:s',$r[last_update]);
$t[ArticleSource] = '';
$t[ArticleURL] = 'http://www.ymall.com/st'.$r[store_id].'/'.$r[goods_id].'/';
$t[isFirst] = 1;
$t[Published] = 1;
if($r[sku_inventory] > 0 && $r[is_delete]==0 && $r[approval_status]==1) $t[Deleted] = 0;
else $t[Deleted] = 1;
$t[Price] = $r[price];
$t[Image] = 'http://mp1.yokacdn.com/'.$r[default_image];
echo json_encode($t)."\n<!--###".date("Y-m-d")."###-->\n";
}
}
echo "<!--done ".date("Y-m-d H:i:s")." -->";

