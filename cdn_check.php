<?
/** 
 * test CDN image 
 * 
 * by jimmy 2009.07.14
 */
$source['蓝汛1']='http://images.yoka.com/pic/index/121/2009/0318/U125P1T121D13F2761DT20090714095912.jpg';
$source['网宿1']='http://man.yoka.com/U125P1T121D13F2761DT20090714095912.jpg';
$source['蓝汛2']='http://images.yoka.com/pic/men/girls/2009/U101P1T117D7917F2577DT20090713183112_maxw808.jpg';
$source['网宿2']='http://man.yoka.com/U101P1T117D7917F2577DT20090713183112_maxw808.jpg';

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

print <<< end_of_print
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=gbk">
	<title>YOKA-CDN-Check</title>
</head>
<body>
<script>
var btime;
function load_img(i,j){
	btime=new Date();
	document.getElementById('img_'+i).innerHTML='<img src='+j+' onload="load_ok('+i+');">';
	document.getElementById('text_'+i).innerHTML='Load...';
}
function load_ok(i){
	utime=(new Date()-btime)/1000;
	document.getElementById('text_'+i).innerHTML=utime+'/s';
}
</script>
<H1>YOKA CDN 资源速度测试页</H1>
<b>测试说明： 请逐个点击测试点，程序会测试每个资源的加载时间，并在最下方显示新加载的资源。<br>
如果某资源长时间不能显示，或者不显示加载时间，可能是速度太慢超时造成。测试完毕后请将横线下的文字部分反馈<br></b>
<hr>你的IP是：{$_SERVER[REMOTE_ADDR]}， 你来自：(略) <br>
end_of_print;
?><?
$i=0;
foreach($source as $key=> $img){
	$i++;$div[$i]=$key;$random=rand(1,99999);
	echo "\n<div><b>资源：$key</b>  </div><div id='text_{$i}'><input type=button value='Check' onclick='load_img(\"{$i}\",\"{$img}?{$random}\");'></div></div>\n";
}
echo "<hr>\n";
$i=0;
foreach($source as $key=> $img){
	$i++;$div[$i]=$key;
	echo "<span id='img_{$i}'></span>";
}

	
