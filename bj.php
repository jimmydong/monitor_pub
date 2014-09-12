<?
echo "<html><head><title>BeiJing Traffic Map by Jimmy v1</title></head>";
echo "<body><table><tr><td background=bj_map.gif>";
for($i = 5; $i < 7 ; $i++){
	for($j = 5; $j < 7 ; $j++){
		echo "<img src='http://210.73.74.218/engine/TrafficTileServer?x=168{$j}&y=77{$i}&zoom=6'>";
	}
	echo "<br>";
}
echo "<td></tr></table></html>";
exit;
