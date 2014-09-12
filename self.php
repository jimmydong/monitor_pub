<?
include('init.php');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Content-Type:text/html; charset=gb2312");

$mycode[2] = array('600507','600970','000401','600875','000063','000527'); //大盘股
$mycode[1] = array('600803','600133','600503','600184','600582','600061','600716');  //教材
$mycode[0] = array('600710','600316','600636','600477','600575','600581','600568','600481','600367','600375','000707','600783','600218','601233','000810'); //待考察
$mycode[99] = array('002124','600104','600335'); //建仓
$mycode[88] = array('600636','600710','600716');

$color_99 = '#cn_'.implode(',#cn_',$mycode[99]);
$color_88 = '#cn_'.implode(',#cn_',$mycode[88]);

if($_REQUEST[pro] == 1){
	
	$t = pathinfo($_SERVER["SCRIPT_NAME"]);
	$base_url = 'http://'.$_SERVER["SERVER_NAME"].':'.$_SERVER["SERVER_PORT"].$t['dirname'];
	print <<< end_of_print
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
	<!--meta http-equiv="Refresh" content="10"-->
	<style>
	table {border-collapse:collapse;}
	body,tr,td,th {font-size:12px;border:1px solid grey;border-collapse:collapse;margin:1px;padding:1px}
	;.e5 {background-color: #EEE}
	.hot {color: #f00}
	.red {color: #400}
	.green {color: #040}
	.right {text-align: right}
	$color_99 {background-color: #EFF}
	$color_88 {background-color: #EEF}
	</style>
	<script src="http://at.yoka.com/html/js/jquery-1.7.1.min.js"></script>
	</head>
	<div id=content style="float: right"></div>
	<script 
	<script>
	function getData(){
	$.get('{$base_url}/self.php?ajax=1&old={$_REQUEST[old]}',{contentType : "application/x-www-form-urlencoded; charset=gbk"},function(data, textStatus){
		//alert(data);
		$('#content').html(data);
		window.setTimeout(getData, 5000);
	})
	}
	getData();
	</script>
end_of_print;
	exit;
}

if($_REQUEST[ajax] == 1){}
else{
print <<< end_of_print
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
	<meta http-equiv="Refresh" content="10">
	<style>
	table {border-collapse:collapse;}
	body,tr,td,th {font-size:12px;border:1px solid grey;border-collapse:collapse;margin:1px;padding:1px}
	.e5 {background-color: #EEE}
	</style>
	</head>
end_of_print;
}
?><?
switch($_REQUEST[old]){
	case 2:
		$url =  "http://hq.stock.sohu.com/viewMyStockListq?cn_".implode(',cn_',$mycode[2]);
	break;
	case 1:
		$url =  "http://hq.stock.sohu.com/viewMyStockListq?cn_".implode(',cn_',array_merge($mycode[1],$mycode[99]));
	break;
	default:
		$url =  "http://hq.stock.sohu.com/viewMyStockListq?cn_".implode(',cn_',$mycode[0]);
	break;
}

$str = file_get_contents($url);
$str = str_replace('[\'viewMyStockList\',','',$str);
/*
PEAK_ODIA(
['viewMyStockList',
['cn_600037','歌华有线','7.17','-0.14%','-0.01','12297','1','881','0.12%','8.8506','7.23','7.12','54.32','7.18','7.18','/cn/600037/index.shtml'],
['cn_600104','上汽集团','12.23','0.25%','+0.03','64375','115','7868','0.07%','9.9808','12.26','12.10','6.01','12.20','12.15','/cn/600104/index.shtml'],
['cn_600061','中纺投资','4.19','2.20%','+0.09','12277','15','510','0.29%','21.9033','4.25','4.00','0.00','4.10','4.00','/cn/600061/index.shtml'],
['cn_600231','凌钢股份','3.85','0.26%','+0.01','6098','10','234','0.08%','7.2264','3.86','3.81','0.00','3.84','3.83','/cn/600231/index.shtml'],
['cn_600160','巨化股份','8.78','2.93%','+0.25','32336','21','2828','0.25%','13.7433','8.99','8.51','13.26','8.53','8.53','/cn/600160/index.shtml'],
['cn_600184','光电股份','15.92','4.26%','+0.65','15726','68','2462','1.70%','19.2706','16.15','15.03','119.70','15.27','15.28','/cn/600184/index.shtml'],
['cn_600036','招商银行','10.16','0.79%','+0.08','145813','35','14782','0.08%','9.9848','10.17','10.07','4.71','10.08','10.08','/cn/600036/index.shtml'],
['cn_002124','天邦股份','6.54','-5.90%','-0.41','16169','33','1068','1.20%','26.8277','6.87','6.44','0.00','6.95','6.85','/cn/002124/index.shtml']
]);
*/
print <<< end_of_print
<table>
			    <thead>
    				<tr>
    					<th class="e0"  >股票代码</th>
    					<th class="e1"  >股票名称</th>
    					<th class="e2"  >当前价</th>
						  <th class="e11" >今低</th>
						  <th class="e10" >今高</th>
    					<th class="e4"  >涨跌额</th>
    					<th class="{$c}">涨跌幅</th>
    					<th class="e5"  >总手</th>
    					<th class="e8"  >换手</th>
    					<th class="e7"  >成交</th>
    					<th class="right">盘子</th>
    				</tr>
    			</thead><tbody>
end_of_print;
?><?
preg_match_all('/\[(\'.*?\'[,]?)+\]/',$str,$reg);//var_dump($reg);
foreach($reg[0] as $s){
//	echo "<hr/>$s\n";
	preg_match_all('/\'(.*?)\'/',$s,$re); //var_dump($re);
	if(substr($re[1][3],0,1) == '-') $c='green';
	elseif(substr($re[1][3],0,1) > 2) $c='hot';
	else $c='red';

$panzi = intval($re[1][5] / substr($re[1][8],0,-1) / 100);
	print <<< end_of_print
    			<tr id="{$re[1][0]}">
    				  <td class="e0"  >{$re[1][0]}</td>
    					<td class="e1"  ><a target="_blank" href="http://q.stock.sohu.com{$re[1][15]}">{$re[1][1]}</a></td>
    					<td class="e2"  >{$re[1][2]}</td>
    					<td class="e11" >{$re[1][11]}</td>
    					<td class="e10" >{$re[1][10]}</td>
    					<td class="e4"  >{$re[1][4]}</td>
    					<td class="{$c}">{$re[1][3]}</td>
    					<td class="e5"  >{$re[1][5]}</td>
    					<td class="e8"  >{$re[1][8]}</td>
						  <td class="e7"  >{$re[1][7]}</td>
						  <td class="right">$panzi</td>
    				</tr>
end_of_print;
?><?
}
print <<< end_of_print
    				</tbody>
			</table>
end_of_print;
echo "<a href=self.php?pro=1&old=0>new</a> | <a href=self.php?pro=1&old=1>old</a> | <a href=self.php?pro=1&old=2>long</a> | ";
echo date("Y-m-d H:i:s");

