<?
$raw=file_get_contents("µçÐÅDNS.txt");
preg_match_all('/ [\d]{1,3}\.[\d]{1,3}\.[\d]{1,3}\.[\d]{1,3} /',$raw,$result);
//var_dump($result);
foreach($result[0] as $dns){
	echo "nslookup club.sohu.com ".trim($dns)." <br>\n";
}