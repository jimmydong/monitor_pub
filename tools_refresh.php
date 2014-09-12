<?
/**
 * Squid»º³åË¢ÐÂ¹¤¾ß
 * by jimmy 2011.3.21
 *
 */
$url = $_REQUEST['url'];
$act = $_REQUEST['act'];
 
//include('include/config.inc.php');
if($act == ''){
	print <<< _END_OF_PRINT
	<html>
		<body>
			<form method=get action='{$_SERVER['PHP_SELF']}'>
				<input type=hidden name=act value=dispatch>
				URL:<input type=text name=url size=80>
				<input type=submit value=OK>
			</form>
		</body>
	</html>
_END_OF_PRINT;
	exit;
}

if($act == 'dispatch'){
	$reg = '/^(https?|ftp):\/\/([-A-Z0-9.]+)(\/[-A-Z0-9+&@#\/%=~_|!:,.;]*)?(\?[A-Z0-9+&@#\/%=~_|!:,.;]*)?$/i';
	if(! preg_match($reg, $url, $urlinfo)) die('Error: Input is Not a Regular URL!');
	//var_dump($urlinfo);
	$domain = $urlinfo[2];
	$script = $urlinfo[3];

	$reg = '/((?=[a-z0-9-]{1,63}\.)[a-z0-9]+(-[a-z0-9]+)*\.)+([a-z]{2,63})/i';
	if(! preg_match($reg, $domain, $domaininfo) ) die('Error: Input is Not a Regular Domain!');
	$base_domain = $domaininfo[1].$domaininfo[3];
	
	$content = get_contents($url);
	if($content == '')die("Error: Can not get content from $url ");
	
	$reg2 = "/src=((\".*?\")|('.*?')|([-A-Z0-9+&@#\/%=~_|?!:,.;]*))/i";
	preg_match_all($reg2, $content, $result);
	foreach( $result[1] as $suburl){
		$suburl=str_replace("'",'',str_replace('"','',$suburl));
//		echo "<hr>".$suburl."\n";
		if(preg_match("|^http://|i",$suburl)){if(ereg($base_domain,$suburl))$sublist[]=$suburl;}
		elseif(preg_match("|^/|i",$suburl)){$sublist[]="http://".$domain.$suburl;}
		elseif(preg_match("/[-A-Z0-9+&@#\/%=~_|!:,.;]*(\?[A-Z0-9+&@#\/%=~_|!:,.;]*)?/",$suburl)){$sublist[]='http://'.$domain.str_replace('\\','/',dirname($script))."/".$suburl;}
	}
//	var_dump($sublist);
	$re = PURGE($url);
	print <<< _END_OF_PRINT
	<html>
		<body>
			<h3>[PURGE] $url -- $re </h1>
			<hr>
			in page resources:<br>
_END_OF_PRINT;
?><?
	if(is_array($sublist))foreach($sublist as $suburl){
		echo "<br>$suburl ...<script src='http://{$_SERVER[HTTP_HOST]}{$_SERVER[PHP_SELF]}?act=js&url=".urlencode($suburl)."'></script>\n";
	}else echo "No in page resource.";
	echo "</body></html>";
	exit;
}

if($act == 'js'){
	$re = PURGE($url);
	echo "document.write('$re');";
	exit;
}

/*------------------------ function ---------------------------*/
function PURGE($url){
		if(!preg_match('|^http://([^/]+)(/.*)$|i',$url,$result))die('PURGE error: $url');
		$host = $result[1];
		$file = $result[2];
		//var_dump($result);
	  $out ="PURGE {$file} HTTP/1.0\r\n";
    $out.="Host:{$host}\r\n";
    $out.="Accept:*/*\r\n";
    $out.="Pragma:no-cache\r\n";
    $out.="Cache-Control:no-cache\r\n";
    $out.="\r\n";
    $fp = fsockopen($host, 80, $errno, $errstr, 3);
    fwrite($fp, $out);
    $buffer=fgets($fp,1024);
    return trim($buffer);
}
function get_contents($url){
		if(!preg_match('|^http://([^/]+)(/.*)$|i',$url,$result))die('PURGE error: $url');
		$host = $result[1];
		$file = $result[2];
		$out ="GET {$file} HTTP/1.0\r\n";
    $out.="Host:{$host}\r\n";
    $out.="Accept:*/*\r\n";
    $out.="Pragma:no-cache\r\n";
    $out.="Cache-Control:no-cache\r\n";
    $out.="\r\n";
    $fp = fsockopen($host, 80, $errno, $errstr, 3);
    fwrite($fp, $out);
    $RF=chr(0x0D).chr(0x0A);
    while ($buffer=fgets($fp,1024)) {
			$header .= $buffer;
			if($buffer==$RF)break;
    }
    stream_set_timeout($fp, 3);
    if(!feof($fp))while($buffer=fread($fp, 4096)){
			$re .= $buffer;
    }
    return $re;
}
