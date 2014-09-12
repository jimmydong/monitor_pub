<?
echo time();
phpinfo();
$i=0;

switch($i){
case '': echo aaa;break;
case 0: echo bbb;break;
}

exit;
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time = microtime_float();

for($i=0;$i<100000;$i++){
	$str='asdfasfd'.'asdfasdf'.'asdfsadf';
	$tmp_time=microtime_float();
	if($tmp_time < $time) echo " $i: $time  -- $tmp_time <br>\n";flush();
	$time=$tmp_time;
}
echo done;
exit;
?>
