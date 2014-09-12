<?
include("../server/include/config.inc.php");
include("syspager.class.php");
header("Content-Type: text/html; charset=utf8");
$q=new DB_comment;

$channel_id=getChannelID($channel);
if($channel_id > 0)
{
	if(empty($resource_id))
	{
		if(!empty($newsid))
		{
			$ids = explode("-", $newsid);//var_dump($ids);
			if(count($ids) >= 3)
			{
				$module_id = $ids[1];
				$resourceid = $ids[2];
			}
		}
	}
}
if($resource_id > 0){
	$resource_info=$q->query_first("SELECT * FROM resource WHERE id = {$resource_id}");
}elseif($channel_id >0 && $module_id>0 && $resourceid >0){
	$resource_info=$q->query_first("SELECT * FROM resource WHERE resource_channel = {$channel_id} and resource_modul = {$module_id} and resource_id = {$resourceid}");
}else {die("Param Error!");}

if(!$resource_info)die("Can not find resource!");
//var_dump($resource_info);
$resource_id=$resource_info[id];
if($page_size<1)$page_size=10;
$page_index =empty($_GET['page']) ? 1 : intval($_GET['page']);
$first = ($page_index - 1) * $page_size;
$first = $first < 0 ? 0 : $first;
if (is_null($status) || !is_numeric($status))$status = 1;
$sql="SELECT * FROM ".getCommentTable($resource_id)." WHERE status = {$status} and resource_id = {$resource_id} order by id desc limit $first, $page_size";
$q->query($sql);
while($record=$q->next_record()){
$comment_list[]=$record;
}
	if(!empty($comment_list) && is_array($comment_list))
	{
		foreach ($comment_list as $key => $value) {
			if($comment_list[$key]['is_anonymous']=="1")
			{
				$comment_list[$key]['user_name'] = "YOKAÍÓ";
			} 
			$comment_list[$key]['create_time'] = date("Y-n-j¡¡H:i:s",$comment_list[$key]['create_time']);
			$comment_list[$key]["ip"] = hidden_ip($comment_list[$key]["ip"]);
		}
	}else $comment_list=array();

//pagesinfo
$record=$q->query_first("SELECT COUNT(*) as cn FROM ".getCommentTable($resource_id)." WHERE resource_id='$resource_id' AND status=1");
	$comment_count =$record[cn];
	$syspager = new SysPager($comment_count,$page_index,$page_size);
	$syspager->url_html='comment.php?resource_id='.$resource_id.'&page=${PAGE}';
	$page_html = $syspager->createHtml();

/*    output,should use smarty */
print <<< end_of_print
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META HTTP-EQUIV="pragma" CONTENT="no-cache" />
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate" />
<META HTTP-EQUIV="expires" CONTENT="0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{$resource_info[resource_title]}</title>
</head>
<body>
Comments of Article <a href={$resource_info[resource_url]}>{$resource_info[resource_title]} </a>
<hr>
end_of_print;
//var_dump($resource_info);
//var_dump($comment_list);
foreach($comment_list as $comment){
echo "<br> {$comment[content]}   -- by {$comment[user_name]} ({$comment[ip]}) at {$comment[create_time]}";
}
//only for anonymous
print <<< end_of_print
<hr>
<br>
Your Comment:<form name=form1 id=form1 method=post action=comment_post.php>
<input type=hidden name=resource_id value='{$resource_id}'>
<input type=text name=content size=32>
<input type=submit value=submit>
</form>
<br> $page_html
</body>
</html>
end_of_print;


/*----------------------------------------function---------------------------------------*/
function getChannelID($channel)
{
	if($channel=="news")
	{
		return 1;
	}
	elseif($channel=="women")
	{
		return 2;
	}
	elseif($channel=="trends")
	{
		return 3;
	}
	elseif($channel=="men")
	{
		return 4;
	}
	else 
	{
		return 0;
	}
}

function getCommentTable($resource_id)
{
	return "comment".$resource_id%100;
}
function hidden_ip($str){
    $pos = strrpos($str, '.');
    return substr($str, 0, $pos + 1) . '*';
}
