<?
/** 
 * test CDN image 
 * 
 * by jimmy 2009.07.14
 */
$source['��Ѵ1']='http://images.yoka.com/pic/index/121/2009/0318/U125P1T121D13F2761DT20090714095912.jpg';
$source['����1']='http://man.yoka.com/U125P1T121D13F2761DT20090714095912.jpg';
$source['��Ѵ2']='http://images.yoka.com/pic/men/girls/2009/U101P1T117D7917F2577DT20090713183112_maxw808.jpg';
$source['����2']='http://man.yoka.com/U101P1T117D7917F2577DT20090713183112_maxw808.jpg';

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // ��ȥ��ʱ��

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
<H1>YOKA CDN ��Դ�ٶȲ���ҳ</H1>
<b>����˵���� �����������Ե㣬��������ÿ����Դ�ļ���ʱ�䣬�������·���ʾ�¼��ص���Դ��<br>
���ĳ��Դ��ʱ�䲻����ʾ�����߲���ʾ����ʱ�䣬�������ٶ�̫����ʱ��ɡ�������Ϻ��뽫�����µ����ֲ��ַ���<br></b>
<hr>���IP�ǣ�{$_SERVER[REMOTE_ADDR]}�� �����ԣ�(��) <br>
end_of_print;
?><?
$i=0;
foreach($source as $key=> $img){
	$i++;$div[$i]=$key;$random=rand(1,99999);
	echo "\n<div><b>��Դ��$key</b>  </div><div id='text_{$i}'><input type=button value='Check' onclick='load_img(\"{$i}\",\"{$img}?{$random}\");'></div></div>\n";
}
echo "<hr>\n";
$i=0;
foreach($source as $key=> $img){
	$i++;$div[$i]=$key;
	echo "<span id='img_{$i}'></span>";
}

	
