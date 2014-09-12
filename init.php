<?
//include('../init.php');
if(!$no_header)header('Content-Type','text/html;charset=utf-8');
//$db = DB::getInstance('default');

$t = file('shanghai.txt');
foreach($t as $line){
	//echo $line;
	if(preg_match('/(.*)\((.*)\)/i',$line,$re)){
		//var_dump($re);
		$shanghai[$re[2]] = $re[1];
		$code_all[$re[2]] = $re[1];
	}
}

$t = file('shenzhen.txt');
foreach($t as $line){
	//echo $line;
	if(preg_match('/(.*)\((.*)\)/i',$line,$re)){
		//var_dump($re);
		$shenzhen[$re[2]] = $re[1];
		$code_all[$re[2]] = $re[1];

	}
}
