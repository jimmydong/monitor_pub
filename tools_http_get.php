<?
/**
 * Use Socket to connect Http
 * by jimmy 20070423
 *
 */
$ip=$_REQUEST[ip];
$host=$_REQUEST[host] ? $_REQUEST[host] : $ip;
$file=$_REQUEST[file];
$port=$_REQUEST[port] ? $_REQUEST[port] : 80;
$debug=$_REQUEST[debug];
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
if($debug){
    $time_start = microtime_float();
    echo "Request:<pre>";print_r($_REQUEST);
}
if($ip=='')$ip==$host;
if($file=='')$file='/';
$fp = fsockopen($ip, $port, $errno, $errstr, 3);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    $out ="GET {$file} HTTP/1.0\r\n";
    $out.="Host:{$host}\r\n";
    $out.="Accept:*/*\r\n";
    $out.="Pragma:no-cache\r\n";
    $out.="Cache-Control:no-cache\r\n";
    $out.="Referer:http://club.sohu.com/\r\n";
    $out.="User-Agent:Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.8.0.11) Gecko/20070312 Firefox/1.5.0.11\r\n";
    //$out.="Range:bytes=554554-\r\n";
    $out.="\r\n";
if($_REQUEST[debug])echo "<hr>" . $out . "<hr>\n";
    fwrite($fp, $out);
    $RF=chr(0x0D).chr(0x0A);
    while ($buffer=fgets($fp,1024)) {
        if($_REQUEST[debug])echo $buffer;
        elseif(ereg("Content-Type:",$buffer))header(trim($buffer));
        if($buffer==$RF)break;
    }
    stream_set_timeout($fp, 3);
    if(!feof($fp))while($buffer=fread($fp, 4096)){
        if($_REQUEST[debug]){ if (!$first_flag)echo "<hr>\n";else $first_flag=true;echo htmlspecialchars($buffer);}
	else echo $buffer;
    }
    fclose($fp);
    if($_REQUEST[debug]){
        echo "</pre>";
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        echo "<a name=e>\n<hr>Test At ".date("Y-m-d H:i:s").", Used: $time seconds\n";
	echo "<hr>\n";
        //exit;
    }
}
if($debug) print <<< end_of_print
<form name=form1 method=get>
ip:<input type=text name=ip size=16 value='$ip'>
port:<input type=text name=port size=4 value='$port'>
host:<input type=text name=host size=20 value='$host'>
file:<input type=text name=file size=40 value='$file'>
<input type=checkbox value=1 name=debug>debug
<input type=submit value=OK>
end_of_print;

?> 
