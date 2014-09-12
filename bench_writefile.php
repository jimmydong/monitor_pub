<?

echo date("Y-m-d H:i:s")."<br>\n";
for($i=0;$i<10;$i++){
$filename="bench_".rand(0,9);
file_put_contents($filename,"asdfasdfasdfasfdasfasfasdfasfasfasfasfasfasfasfasfasfasfasfasdf",FILE_APPEND);
}
echo "<br>\n".date("Y-m-d H:i:s")."<br>\n";

