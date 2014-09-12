<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
</head>
<style type="text/css">
#postBar {
position:fixed;
left:0px;

position:relative;
top:200px;
}
</style>
<body>

<?
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$debug=$_REQUEST[debug];
if($_REQUEST['step']<1){
        print <<< end_of_print
        <form mathod=get action=check_lvs.php>
        <select name=ctype><option value=bbs>bbs</option><option value=space>space</option><option value=www>www</option></select>
        | host:<input type=text size=20 name=host value=''> file:<input type=text size=40 name=file value=''>
        <input type=hidden name=step value=1> <input type=checkbox name=debug value=1 > <input type=submit value=check>
        </form>
end_of_print;
        exit;
}else{
        switch($_REQUEST['ctype']){
                case 'bbs':
                $host=$_REQUEST['host'] ? $_REQUEST['host'] : 'bbs.yoka.net';
                $file=$_REQUEST['file'] ? $_REQUEST['file'] : '/thread-1367113-1-1.html';
                $ip_list=array('lvs'=>'59.151.3.196', '10.0.0.202','10.0.0.203','10.0.0.204','10.0.0.205','10.0.0.206','10.0.0.207','10.0.0.208','10.0.0.209');
                $log_file='check_bbs.log';break;
                case 'space': 
                $host=$_REQUEST['host'] ? $_REQUEST['host'] : 'space.yoka.com';
                $file=$_REQUEST['file'] ? $_REQUEST['file'] : '/';
                $ip_list=array('lvs'=>'59.151.9.78', '10.0.0.50','10.0.0.49','10.0.0.48','10.0.0.47','10.0.0.46','10.0.0.45','10.0.0.44','10.0.0.43','10.0.0.42','10.0.0.41');
                $log_file='check_space.log';break;
                case 'www': 
                default: 
                $host=$_REQUEST['host'] ? $_REQUEST['host'] : 'www.yoka.com';
                $file=$_REQUEST['file'] ? $_REQUEST['file'] : '/beauty/face/2010/0315319316.shtml';
                $ip_list=array('source'=>'59.151.9.72', 'CDN'=>'210.192.120.225', 'xj'=>'59.151.3.200','xj1'=>'61.185.133.205','xj2'=>'61.185.133.206','xj3'=>'210.192.120.225','210.192.120.206','210.192.120.207','210.192.120.208','210.192.120.209','210.192.120.210','210.192.120.214','210.192.120.220','210.192.120.211','210.192.120.212','210.192.120.215','210.192.120.216','210.192.120.217','210.192.120.218','210.192.120.205','210.192.120.213','210.192.120.219');
                $log_file='check_www.log';break;
        }
        if($_REQUEST['host'])$host=$_REQUEST['host'];

        if($_REQUEST['step']==2){
                $file=$_REQUEST[file];
                file_put_contents($log_file,$host.$file."\n",FILE_APPEND);
        }
        $file_url=urlencode($file);
        if($debug)$checked_debug='checked';else $checked_debug='';
        echo "<h1> http://$host$file </h1>";
        echo "<form method=get action=check_lvs.php>
        <input type=hidden name=step value=2> <input type=hidden name=ctype value='{$_REQUEST['ctype']}'>
        http://<input type=text size=20 name=host value='$host'><input type=text name=file size=40 value='$file'>
        <input type=checkbox name=debug value=1 $checked_debug><input type=submit value=ok> <a href='$log_file' target=_blank>history</a></form>";
        
        //页面大小
        $count=0;
        //总共大小
        $count_page=0;
        //页面大小数组
        $page_size_list=array();
        //计数器
        $i=0;
        //记录无法链接ip
        $error_ip_list=array();
        //记录请求时间
        $request_time_list=array();
        echo "<div id='postBar' >";
        foreach($ip_list as $key => $ip){
                $time_start = microtime_float();
                //echo "\n<br><b> $key [{$ip}]  <a href={$url} target=_blank>{$url}</a> </b>:";
             
                if($ip=='')$ip==$host;
                if($file=='')$file='/';
            
                $fp = fsockopen($ip, 80, $errno, $errstr, 3);
                if (!$fp) {
                   $error_ip_list[$ip]=$host.$file_url;
                    //echo "$errstr ($errno)<br />\n";
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
                    fwrite($fp, $out);
                    $RF=chr(0x0D).chr(0x0A);
                    while ($buffer=fgets($fp,1024)) {
                        if($_REQUEST[debug])echo $buffer;
                        elseif(ereg("Content-Type:",$buffer))header(trim($buffer));
                        if($buffer==$RF)break;
                    }
                    stream_set_timeout($fp, 3);
                    if(!feof($fp))while($buffer=fread($fp, 4096)){
                        $count +=strlen($buffer);
                        //if($_REQUEST[debug]){ 
                            //if (!$first_flag)
                           // echo "<hr>\n";else $first_flag=true;
                            //echo htmlspecialchars($buffer);
                            //}
                       // else  echo $buffer; 
                       
                    }
                    fclose($fp);
                    
                    //页面大小
                    $page_size=number_format($count/1024);
                    echo "ip地址为:".$ip."  页面大小为:".$page_size."KB  ";
                    $url = "http://monitor.yoka.com:81/pub/tools_http_get.php?ip={$ip}&host={$host}&file={$file_url}&debug={$debug}";
                    echo "<a href={$url} target=_blank>{$url}</a><br>";

                    $page_size_list[$ip]=$page_size;
                    $count_page+=$page_size;
                    if($page_size>0)
                    {
                        $i++;
                    }
                    $count=0;
            
                    $time_end = microtime_float();
                    $time = $time_end - $time_start;
                    $request_time_list[$ip]=$time;
                    echo "<a name=e>\n<hr>Test At ".date("Y-m-d H:i:s").", Used: $time seconds\n";
                    echo "<hr>\n";
                    echo "<br><iframe src={$url} width=800 height=160></iframe><br>";
            }
        }
        //平均值
        echo "</div>";
        $mean=$count_page/$i;
        
        echo "<div style='position:absolute;top:100px;'><ul><li><b>页面平均值大小为:". $mean."KB</b></li>";
        echo "<li><b>错误信息:</b></li>";
        if(!empty($page_size_list))
        {
            foreach($page_size_list as $ip => $page_size){
                //页面大于10KB或者访问时间超过5秒的
                if($page_size<=10 || $request_time_list[$ip]>5)
                {
                    echo "<li><span  style='color:red'>页面超时或错误页面  IP:".$ip."  页面大小为:".$page_size."KB,  请求时间".$request_time_list[$ip]."</span><br>";
                    echo "</li>\n";
                }
                else if($mean-$page_size>10  || $mean-$page_size<-10)
                {
                    echo "<li><span  style='color:red'>页面存在问题  IP:".$ip."  页面大小为:".$page_size."KB</span><br>";
                    echo "</li>\n";
                }
                
            }
        }
        if(!empty($error_ip_list))
        {
            foreach($error_ip_list as $ip => $error){

                    echo "<li><b  style='color:red'>服务器无法访问 IP:".$ip."</b><br>";
                    echo "</li>\n";
            }
        }
        echo "</ul>";
        
        
      
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


?>
<b style="color: gray;"></b>
</body>
</html>
