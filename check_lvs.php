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

//读取站点和ip信息
$lines =file('http://monitor.yoka.com:81/pub/haproxy_num');
//保存haproxy主ip
$haproxy_ip="";
//循环每行
foreach ($lines as $line_num => $line) {
    //获取包含APROXY_
    if(!empty($line) && strpos($line,'APROXY_')>0)
    {
        //获取HAPROXY 主 ip
        $haproxy_ip=substr($line,strpos($line,'HAPROXY_')+8,strlen($line)-(strpos($line,'HAPROXY_')+10));
       
    }else if(!empty($line))
    {
        //根据空格打散
        $site_ip_list=explode('    ',$line);
        //获取站点名称
        $site_name=substr($site_ip_list[0],0,strlen($site_ip_list[0])-2);
        
        if(!empty($site_name))
        {
            //站点名称为key 保存ip
            $ip_list[$site_name][$haproxy_ip]=$haproxy_ip;
        }
        
        $ip_string="";
        //循环每行的 站点名称和ip
        foreach($site_ip_list as $key=>$site_ip)
        {
            
            if(!empty($site_ip) && !preg_match("/{$site_name}/",$site_ip))
            {
                $ip_list[$site_name][trim($site_ip)]=trim($site_ip);
            }
            
        }
      
    }
    
}

//站点信息
$ctype_list=array_keys($ip_list);


if($_REQUEST['step']<1){
        echo"<form mathod=get action=check_lvs.php>
            <select name=ctype id=ctype onchange='show_ip();'>";
                foreach($ctype_list as $ctype)
                {
                    echo "<option value=".$ctype." title=";
                    foreach($ip_list[$ctype] as $ip)
                    {
                        echo $ip."---";
                    }
                    echo ">".$ctype."</option>\n\r";
                        
                }
                /*
    			<option value=bbs>bbs</option>
    			<option value=space>space</option>
    			<option value=brand>brand</option>
    			<option value=brand_service>brand_service</option>
    			<option value=www>www</option>
    			<option value=pq>pq</option>
    			<option value=app>app</option>
    			<option value=blog>blog</option>
    			<option value=uspace>uspace</option>
    			<option value=passport>passport</option>
    			<option value=ucenter>ucenter</option>
    			<option value=brandcenter>brandcenter</option>
    			<option value=watch>watch</option>
    			<option value=try>try</option>
    			<option value=baida>baida</option>
    			<option value=sec>sec</option>
    			<option value=lucky>lucky</option>
    			<option value=star>star</option>
    			<option value=vote_sec>vote_sec</option>
    			<option value=street>street</option>
    			<option value=advlog>advlog</option>*/
       echo "</select>
        | host:<input type=text size=20 name=host value=''> file:<input type=text size=40 name=file value=''>
        <input type=hidden name=step value=1> <input type=checkbox name=debug value=1 > <input type=submit value=check>
        <div id='showip'></div>
        </form>";
        echo "<script>
        function show_ip()
        {
            var ip_list=new Array();
            var host=document.getElementById('ctype').value;
            ";
            foreach($ip_list as $key=>$ips)
            {
                echo "ip_list['".$key."']=new Array();";
                echo "ip_list['".$key."']='";
                foreach($ips as $ip)
                {
                    
                    echo $ip."---";
                }
                echo "';\n\r";
            }
            
            echo ";
            document.getElementById('showip').innerHTML='<b>'+ip_list[host]+'</b>';
        }
        </script>";
        exit;
}else{
        //站点信息
        $ctype=$_REQUEST['ctype'];
        //访问域名
        $host=$_REQUEST['host'];
        //访问文件
        $file=$_REQUEST['file'];
        //日志文件
        $log_file="check_".$ctype.".log";
        
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
        foreach($ip_list[$ctype] as $key => $ip){
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
				    echo "<hr/>ip地址为:<b>".$ip."</b>  页面大小为:".$page_size."KB  ";
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
				    echo "<a name=e/>\nTest At ".date("Y-m-d H:i:s").", Used: $time seconds\n";
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
<script>
document.getElementById('showip')
</script>
<b style="color: gray;"></b>
</body>
</html>
