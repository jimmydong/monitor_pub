<?
include("../server/include/config.inc.php");
include("syspager.class.php");
header("Content-Type: text/html; charset=utf8");
$q=new DB_comment;
if(!$resource_info=$q->query_first("SELECT * FROM resource WHERE id='{$resource_id}'"))die("Can't find the article!");
if(!$content)die("No content!");
$sql="INSERT INTO ".getCommentTable($resource_id)." SET resource_id='{$resource_id}', user_id=0, user_name='YOKA', is_anonymous=1, content='".addslashes($content)."',parent_id=0, replies_num=0, status=1, ip='".$_SERVER[REMOTE_ADDR]."', ip_hidden=0, create_time=NOW()";
if(!$q->query($sql))die("Database Error!");
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
Your Comment Submit OK! <a href=comment.php?resource_id=$resource_id>Click here to back</a>
</body>
</html>
end_of_print;
exit;












/*--------------------------------------------function----------------------------------------------*/
function getCommentTable($resource_id)
{
        return "comment".$resource_id%100;
}
