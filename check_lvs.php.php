<html>
<body>
<?
$debug=$_REQUEST[debug];
if($_REQUEST['step']<1){
	print <<< end_of_print
	<form mathod=get action=check_lvs.php>
	<select name=ctype><option value=bbs>bbs</option><option value=space>space</option><option value=www>www</option></select>
	| ¿É²»Ìî:  host:<input type=text size=20 name=host value=''> file:<input type=text size=40 name=file value=''>
	<input type=hidden name=step value=1> <input type=checkbox name=debug value=1 > <input type=submit value=check>
	</form>
end_of_print;
	exit;
}else{
	switch($_REQUEST['ctype']){
		case 'bbs':	
		$host=$_REQUEST['host'] ? $_REQUEST['host'] : 'bbs.yoka.net';
		$file=$_REQUEST['file'] ? $_REQUEST['file'] : '/thread-1367113-1-1.html';
		$ip_list=array('lvs'=>'59.151.3.196', '10.0.0.202','10.0.0.203','10.0.0.204','10.0.0.205','10.0.0.206');
		$log_file='check_bbs.log';break;
		case 'space': 
		$host=$_REQUEST['host'] ? $_REQUEST['host'] : 'space.yoka.com';
		$file=$_REQUEST['file'] ? $_REQUEST['file'] : '/';
		$ip_list=array('lvs'=>'59.151.9.78', '10.0.0.41', '10.0.0.42', '10.0.0.43', '10.0.0.44', '10.0.0.45', '10.0.0.46','10.0.0.47','10.0.0.48','10.0.0.49');
		$log_file='check_space.log';break;
		case 'www': 
		default: 
		$host=$_REQUEST['host'] ? $_REQUEST['host'] : 'www.yoka.com';
		$file=$_REQUEST['file'] ? $_REQUEST['file'] : '/beauty/face/2010/0315319316.shtml';
		$ip_list=array('source'=>'59.151.9.72', 'CDN'=>'210.192.120.225', 'xj'=>'59.151.3.200','xj1'=>'61.185.133.205','xj2'=>'61.185.133.206','xj3'=>'210.192.120.225','210.192.120.206','210.192.120.207','210.192.120.208','210.192.120.209','210.192.120.210','210.192.120.214','210.192.120.220','210.192.120.211','210.192.120.212','210.192.120.215','210.192.120.216','210.192.120.217','210.192.120.218','210.192.120.205','210.192.120.213','210.192.120.219');		
		$log_file='check_www.log';break;
	}
	if($_REQUEST['host'])$host=$_REQUEST['host'];

	if($_REQUEST['step']==2){
		$file=$_REQUEST[file];
		file_put_contents($log_file,$host.$file."\n",FILE_APPEND);
	}
	$file_url=urlencode($file);
	if($debug)$checked_debug='checked';else $checked_debug='';
	echo "<h1> http://$host$file </h1>";
	echo "<form method=get action=check_lvs.php>
	<input type=hidden name=step value=2> <input type=hidden name=ctype value='{$_REQUEST['ctype']}'>
	http://<input type=text size=20 name=host value='$host'><input type=text name=file size=40 value='$file'>
	<input type=checkbox name=debug value=1 $checked_debug><input type=submit value=ok> <a href='$log_file' target=_blank>history</a></form>";
	foreach($ip_list as $key => $ip){
		$url = "http://monitor.yoka.com:81/pub/tools_http_get.php?ip={$ip}&host={$host}&file={$file_url}&debug={$debug}";
		echo "\n<br><b> $key [{$ip}]  <a href={$url} target=_blank>{$url}</a> </b>: <br><iframe src={$url} width=800 height=160></iframe>";
	}
}
?>
</body>
</html>
