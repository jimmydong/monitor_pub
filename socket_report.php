<?
/**
 * show the socket monitor report
 */
include("../server/include/config.inc.php");
showhead();
echo '<link rel="stylesheet" href="func.css" type="text/css">';
echo '<script language=javascript src="func.js"></script>';

$report_time = 6*3600;

$q=new DB_server;
$q->query("SELECT * FROM socket");
$counter = 0;
while($re = $q->next_record() ){
	$socket_config[ $re[s_id] ] = $re;
	$counter++;
}

$timelimit = date('Y-m-d H:i:s', time() - $report_time);
$lasttime = '';
$q->query("SELECT *, UNIX_TIMESTAMP(inputtime) as utime FROM socket_log WHERE inputtime > '$timelimit'");
while($re = $q->next_record()){
	$timecell = $report_time / 300 - intval( (time() - $re[utime]) / 300);
	$socket = $socket_config[ $re[s_id] ];
	$report[ $socket[s_channel] ][ $re[s_id] ][ $timecell ] = $re;
	if($re[inputtime]>$lasttime)$lasttime=$re[inputtime];
}
echo '<h1>Last Check is '. $lasttime .'</h1>';

echo "<style>.error{background: #DD0000;color: white;} .ok{background: #99DD66} .other{background: yellow} .no{background: #EDEEEE}</style>\n<table $table_style ><tr>";
for($i = 0 ; $i <= $report_time/300 ; $i++){
	echo "<td> * </td>";
}
echo "</tr>\n";
foreach($report as $s_channel => $sub_report){
	foreach($sub_report as $s_id => $line){
		echo "<tr>";$socket = $socket_config[$s_id];
		echo "<td>" . str_showhelp( "[{$socket[s_channel]}]{$socket[s_name]} {$socket[s_host]}" , 
			"http://{$socket[s_host]}{$socket[s_url]} <br>{$socket[s_ip]} <br>Maxtime: {$socket[s_maxtime]} <br>Minlength: {$socket[s_minlength]} <br>Email: {$socket[s_email]} <br>Msg: {$socket[s_msg]} <br>second: {$socket[second]} <br>s_id: $s_id") . "</td>\n";
		for($i = 0 ; $i < $report_time/300 ; $i++){
			$val = $line[$i];
			if(!is_array($line[$i]))echo "<td class=no>&nbsp</td>";
			else {
				if($val[err_flag]==1)$class = 'error';elseif($val[err_flag]==0) $class = 'ok';else $class = 'other';
				if($val[send_email]==1)$text='E';elseif($val[send_msg]==1)$text='M';else $text='&nbsp;';
				echo "<td class=$class>" . str_showhelp($text, $val[id] . " "  . $val[inputtime] . "\n"  . $val[remark] . "\n\nfilesize: {$val[filesize]}k \nusetime: {$val[time]}s \nsendmail: {$val[send_email]}, sendmsg: {$val[send_msg]}") . "</td>";
			}
		}
		echo "</tr>\n";
	}
}
echo "</table><a href=check_lvs.php>LVS/CDNÒ³Ãæ¼ì²é</a> <script>setTimeout(function(){window.location=document.URL;},300000);</script>";

	
