<?
/**
 * find a nearly word
 *
 * by jimmy 2009.07.10
 */
$debug=$_REQUEST[debug];
$encode=$_REQUEST[encode];
$keyword=$_REQUEST[keyword];
if($debug){
if(!$_REQUEST[step]){
print <<< end_of_print
<form name=form1 id=form1>
<input type=hidden name=step value=1>
<input type=text size=20 name=keyword value='$keyword'>
<input type=submit value=CHECK>
</form>
end_of_print;
exit;
}
}
if($encode && $encode!='gbk' & $encode!='gb2312')iconv($encode,'gbk',$keyword);
$word=urlencode($keyword);
if($debug)echo $word;
$content=file_get_contents("http://www.google.cn/search?hl=zh-CN&q=".$word);
if($debug)echo "RAQ lenth: ".strlen($content);
preg_match('|您是不是要找： </span><a href="/search?[^>]*" class=spell><em>([^<]*)</em></a>|s',$content,$result);
if($debug)var_dump($result);
if($encode && $encode!='gbk' & $encode!='gb2312')return iconv('gbk',$encode,$keyword);
else echo $result[1];


