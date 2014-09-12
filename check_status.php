<?
print <<< end_of_print
<form method=get action=check_status.php>
	URL: http://<input type=text name=host size=32 value='{$_REQUEST[host]}'>/<input type=text name=url size=16 value='httpd_status'>
	<input type=checkbox name=raw value=1>RAW
	<input type=submit value=OK>
</form>
<hr>
end_of_print;
if(!$_REQUEST[host])exit;

$url = 'http://' . $_REQUEST[host] . '/' . $_REQUEST[url];
$content = file_get_contents($url);
if(!$content)	die("Cant't get data!");
if($_REQUEST[raw]){
	echo $content;exit;
}
if(!preg_match('|Total accesses: (.*?) - Total Traffic: (.*?)</dt>|s', $content, $result))die("Not a Apache Status Data");
$traffics_old = $result[2];
$access_old = $result[1];
sleep(1);
$content = file_get_contents($url);
if(!$content)	die("Cant't get data!");
if(!preg_match('|Total accesses: (.*?) - Total Traffic: (.*?)</dt>.*?<pre>(.*?)</pre>.*?(<table.*?</table>)|s', $content, $result))die("Not a Apache Status Data(2)");
$traffics_new = $result[2];
$access_new = $result[1];
$access_add = intval($access_new) - intval($access_old);
print <<< end_of_print
<h1>Info of $_REQUEST[host]/$_REQUEST[url]</h1>
in 1 second:<br>
access: $access_old -> $access_new (<b>$access_add</b>/s)<br>
traffics: $traffics_old -> $traffics_new <hr>

end_of_print;

//echo "<pre>";var_dump($result);echo "</pre>";
preg_match_all('|<tr><td>.*?</td><td>.*?</td><td>.*?</td><td>.*?</td><td>(.*?)</td><td>.*?</td><td>.*?</td><td>.*?</td><td>.*?</td><td>.*?</td><td>(.*?)</td><td nowrap>(.*?)</td><td nowrap>(.*?)</td></tr>|s', $result[4],$detail,PREG_SET_ORDER );
//var_dump($detail);
foreach($detail as $tmp){
	$ip_info[$tmp[2]][]=$tmp;
	$host_info[$tmp[3]][]=$tmp;
	$url_info[$tmp[4]][]=$tmp;
}
switch ($_REQUEST[order]){
	case 'ip':
		ksort($ip_info);$info = $ip_info; unset($ip_info);unset($host_info);unset($url_info);
		break;
	case 'host':
		ksort($host_info);$info = $host_info; unset($ip_info);unset($host_info);unset($url_info);
		break;
	case 'url':
	default:
		ksort($url_info);$info = $url_info; unset($ip_info);unset($host_info);unset($url_info);
		break;
}
$current_url='check_status.php?host='.$_REQUEST[host].'&url='.$_REQUEST[url];
echo "
<style>
table { border-collapse: collapse; } 
td{border: 1px solid;}
</style>
<table><tr><td><b>CPU</b></td><td><b><a href={$current_url}&order=ip>IP</a></b></td><td><b><a href={$current_url}&order=host>HOST</a></b></td><td><b><a href={$current_url}&order=url>URL</a></b></td></tr>\n";
foreach($info as $line){
	foreach($line as $tmp){
		echo "<tr><td>$tmp[1]</td><td>$tmp[2]</td><td>$tmp[3]</td><td>$tmp[4]</td></tr>\n";
	}
}
echo "</table>
<br>Apache Config file: <br>
<pre>
".htmlspecialchars("ExtendedStatus On
<Location /httpd_status>
    SetHandler server-status
    Order deny,allow
    Deny from all
    Allow from all
</Location>
")."
<pre>
";
	
		
