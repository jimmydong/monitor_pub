<?
/**
 * �����ļ���Function Define
 *
 * new -- for sohu --
 * function cookie_encode()
 * function cookie_decode()
 *
 * -- new --
 * function value2file($the_value,$value_name)
 *
 * function checkuser()
 * function showhead($title)
 * function showhead_utf8($title)
 * function showhelp($showdoc,$helpdoc,$markflag='')
 * function getcache($url)
 * function putcache($url,$data)
 * function mkdirs($pathStr,$mod)
 * function getHashPath($HashStr)
 * function xmlencode($string)
 * function xmlbegin($level,$key,$id)
 * function xmlend($level,$key)
 * function xmlval($level,$key,$val,$flag=0)
 * function ErrExit($msg,$backurl='javascript:window.history.back(-1)')
 * function redirect($backurl="javascript:window.history.go(-1);",$message='',$delay=0)
 * function doshutdown()
 * function trace($val)
 * function addlog($msg)
 * function add_log($Log_type,$Log_userid,$Log_content='',$Log_remark='')
 * function show_array($the_array,$sort_flag=0)
 * function tree_array($the_value,$the_head="",$end_key="",$the_key="")
 * function find_array($item,$the_array)
 * function radom_id($length)
 * function radom_name($length)
 * function day_subtract($begindate,$enddate="")
 * function text2html($mytext)
 * function html2text($str)
 * function text2string($mytext)
 *
 * @author jimmy
 * @packet JDK
 * @version 2004.09.15
 */

/*���������д��inc.php�ļ���ʽ*/
function value2file($the_value,$value_name)
{
    $content="";
    if(!is_array($the_value)){
        return "\${$value_name}='$the_value';\n";
    }
    foreach($the_value as $key => $value){
        if(is_array($value)){
            $valuelist=value2file_sub($value,$key);
            foreach($valuelist as $subvalue){
                $content.="\$$value_name".$subvalue;
            }
        }else{
            $content.="\${$value_name}['{$key}']='".addslashes($value)."';\n";
        }
    }
    return $content;
}
function value2file_sub($value,$key){
    $result=array();
    if(is_array($value)){
        foreach ($value as $subkey => $subvalue){
            $tmp_result=value2file_sub($subvalue, $subkey);
            foreach($tmp_result as $substring){
                $result[]="['$key']".$substring;
            }
        }
    }else{
        $result[]="['$key']='".addslashes($value)."';\n";
    }
    return $result;
}


/* SOHU cookie decode & encode */
$secukey="20051121"; //8bit is good
function cookie_encode($name)//cookie encode
  {
    global $secukey;    
    $ip=$_SERVER[REMOTE_ADDR];
    $bname=base64_encode($name);
    $flag=1;
    for($i=strlen($bname);$i<65;$i++)
    {
        if($flag==1){$bname.='.';$flag=0;}
        else $bname.=chr(rand(ord('A'),ord('Z')));
    }
    $flag=0;    $org_1='';    $org_2='';
    for($i=0;$i<64;$i++){
        if($flag!=1){
            $org_1.=$bname[$i];
            $flag=1;
        }else{
            $org_2.=$bname[$i];
            $flag=0;
        }
    }
    $md5_1=md5($org_1.$secukey);$md5_2=md5($org_2.$secukey);
    $md5_ip=md5($ip);$md5_secukey=md5($secukey);
    $encrypt_1='';$encrypt_2='';$encrypt_3='';
    for($i=0;$i<32;$i++){
        $encrypt_1.=$md5_1[$i].$org_2[$i];
        $encrypt_2.=$md5_2[$i].$org_1[31-$i];
        $encrypt_3.=$md5_ip[31-$i].$md5_secukey[$i];
    }
    $encrypt='';
    for($i=0;$i<64;$i++){
        $encrypt.=$encrypt_1[$i].$encrypt_2[$i].$encrypt_3[$i];
    }
    return str_replace("=",",",$encrypt);
  }

function cookie_decode($encrypt)//cookie decode
  {
    global $secukey;
    //$ip=$_SERVER[REMOTE_ADDR];
    $encrypt=str_replace(",","=",$encrypt);
    $md5_1='';$md5_2='';$md5_ip='';
    $org_1='';$org_2='';$md5_secukey='';
    for($i=0;$i<32;$i++){
        $md5_1.=$encrypt[$i*6];
        $md5_2.=$encrypt[$i*6+1];
        $org_1.=$encrypt[(31-$i)*6+4];
        $org_2.=$encrypt[$i*6+3];
        //$md5_ip.=$encrypt[(31-$i)*6+2];
        $md5_secukey.=$encrypt[$i*6+5];
    }

    //if($md5_ip!=md5($ip))print("Warring: ip");
    //if($md5_secukey!=md5($secukey))print("Warring: secukey");    
    //if($md5_1!=md5($org_1.$secukey))print("Error:1");
    if($md5_2!=md5($org_2.$secukey)) return "";//print("Error: 2");
    
    $bname='';
    for($i=0;$i<32;$i++){
        $bname.=$org_1[$i].$org_2[$i];
    }
    return base64_decode($bname);
  }

/* ____________________________________________ Below is old ____________________________________________*/

/**
 * �û������֤
 * @TODO Ԥ������֤ user_id ��������û�δע�ᣬ������ע��ҳ��
 */
function checkuser($user_id=0)
{
    if($user_id==0)$user_id=$GLOBALS['user_id'];
    if($user_id>0)
        return true;
    else
        ErrExit("û�е�½������û��ע�ᣡ");
}

/**
 * ��ʾ����
 */
function showhead($title="")
{
    if($title) $title=" - $title";
    print <<< end_of_print
        <html>
        <head>
        <title>Jimmy Tool Kit - $title</title>
        <META HTTP-EQUIV="Expires" CONTENT="0">
        <META HTTP-EQUIV="Last-Modified" CONTENT="0">
        <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=utf8">
        <link rel="stylesheet" href="include/func.css" type="text/css">
        <script language=javascript src="include/func.js"></script>
        </head>
        <body bgcolor="#FFFFFF" text="#000000" topmargin="10" background="/include/bg.gif" >
end_of_print;
}
function showhead_utf8($title="")
{
    if($title) $title=" - $title";
    print <<< end_of_print
        <html>
        <head>
        <title>Jimmy Tool Kit - $title</title>
        <META HTTP-EQUIV="Expires" CONTENT="0">
        <META HTTP-EQUIV="Last-Modified" CONTENT="0">
        <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="include/func.css" type="text/css">
        <script language=javascript src="include/func.js"></script>
        </head>
        <body bgcolor="#FFFFFF" text="#000000" topmargin="10" background="/include/bg.gif" >
end_of_print;
}

function showhead_monitor($title="")
{
    if($title) $title=" - $title";
    print <<< end_of_print
        <html>
        <head>
        <title> SA - YOKA��������������ϵͳ $title</title>
        <META HTTP-EQUIV="Expires" CONTENT="0">
        <META HTTP-EQUIV="Last-Modified" CONTENT="0">
        <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link rel="stylesheet" href="include/func.css" type="text/css">
        <script language=javascript src="include/func.js"></script>
        </head>
        <body bgcolor="#FFFFFF" text="#000000" topmargin="10" background="/include/bg.gif" >
        <p><a href=new_index.php>SAϵͳ</a> | <a href=tools_server.php>�ű��������ɹ���</a> 
        	| <a href=tools_servername.php>��������Ϣ����</a> | <a href=tools_esxi.php>�������Ϣ</a> | <a href=tools_mt.php>������͹���</a> 
          | <a href=tools_monitor.php>��������趨</a> | <a href=new_monitor_server.php>�����Ŀ-������</a> 
          | <a href=new_server_monitor.php>������-�����Ŀ</a> | <a href=tools_clean.php>��������</a> <br>
         &nbsp; �������ߣ� 
          <a target=_blank href=new_socketwalk.php>ҳ��ץȡ���</a> <a target=_blank href=new_crontab.php>Crontab</a>  
					<a target=_blank href=/mrtg/sjhl>���ͻ���</a>
					<a target=_blank href=/mrtg/shunyi/>�׿Ƶ�Ѷ</a>
					<a target=_blank href=tools/tools_rewrite.php>rewrite</a>
					<a target=_blank href=tools/tools_web.php>web</a>
					<a target=_blank href=tools/tools_squid.php>squid</a>
					<a target=_blank href='http://192.168.1.100:7998'>ASG admin</a>
				</p>
end_of_print;
}

function showhelp($showdoc,$helpdoc,$markflag='')
{
    $showdoc=text2html($showdoc);
    $helpdoc=text2string(text2html($helpdoc));
    if($markflag==1)$markflag='��';
    if (trim($helpdoc)=='') echo $showdoc;
    else print <<< end_of_print
    <span onmouseover="show_help('$helpdoc');" onmouseout="show_help('');">$showdoc $markflag</span>
end_of_print;
}
function str_showhelp($showdoc,$helpdoc,$markflag='')
{
    //$showdoc=text2html($showdoc);
    $helpdoc=text2string(text2html($helpdoc));
    if($markflag==1)$markflag='��';
    $enter=chr(10).chr(13);
    $helpdoc=str_replace($enter,"\\n",$helpdoc);
    $helpdoc=str_replace("\n","\\n",$helpdoc);
    $helpdoc=str_replace("\r","\\r",$helpdoc);
    if (trim($helpdoc)=='') return $showdoc;
    return "<span onmouseover=\"show_help('$helpdoc');\" onmouseout=\"show_help('');\">$showdoc $markflag</span>";
}
?><? //�����Զ�ʶ���쳣
/*
function getcache($url)
{
    global $cache_path,$cachetimeout;
	  $filename = $cache_path."/".getHashPath(md5($url)).".data";
    if(file_exists($filename)) {
	  if((date("U")-date("U",filectime($filename)))<=$cachetimeout)
	  {
        $fd = fopen( $filename, "r" );
        $data = fread($fd, filesize($filename));
        return $data."\n<!-- get cache from $filename -->";
      }
    }
    return false;
}	
function putcache($url,$data)
{
    global $cache_path,$cachetimeout;
	  $filename = $cache_path."/".getHashPath(md5($url)).".data";
	  if (!mkdirs($filename,0744)) return false;
    if (!$fp = fopen ($filename, "w")) return false;
    fwrite ($fp, $data);
    fclose ($fp);
    return true;
}	
function mkdirs($pathStr,$mod="0755"){
    if(is_file($path) || is_dir($pathStr)) return true;
    $pieces = explode("/", $pathStr);
    $tmpdir = "";
    for($mm=1;$mm<(count($pieces)-1);$mm++){
        $tmpdir      .= "/".$pieces[$mm];
        if(!is_dir($tmpdir)){
    	    if (!mkdir($tmpdir,$mod)) return false;
        }
    }
    return true;
}    
function getHashPath($HashStr){
	if(strlen($HashStr) < 4) return("error/tooshort");
	$hs1 = substr($HashStr,-2);
	$hs2 = substr($HashStr,-4,2);
	return $hs1."/".$hs2."/".$HashStr;
}
*/

/**
 *  XML��ʽ�������
 */
function xmlencode($string)
{
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace('&','&amp;',$string);
    $string = str_replace('\'','&apos;',$string);
    $string = str_replace('"','&quote;',$string);
    return $string;
}

function xmlbegin($level,$key,$id)
{
    $string="";
    for ($i=0;$i<$level;$i++)$string.="  ";
    $string.="<{$key} id='{$id}'>\n";
    return $string;
}
function xmlend($level,$key)
{
    $string="";
    for ($i=0;$i<$level;$i++) $string.="  ";
    $string.="</{$key}>\n";
    return $string;
}
function xmlval($level,$key,$val,$flag=0)
{
    $string="";
    for ($i=0;$i<$level;$i++) $string.="  ";
    if ($flag==0)
    {
        $string.="<{$key}>".xmlencode($val)."</{$key}>\n";
    }
    else $string.="<{$key}><![CDATA[{$val}]]></{$key}>\n";
    return $string;
}

/**
 *  �����쳣���󣬷�����Ϣ
 */
function ErrExit($msg,$backurl='javascript:window.history.back(-1)'){
	$errmsg=$msg;
	if ($backurl=='') $backurl = 'javascript:window.history.back(-1)';
    print <<< end_of_print
<html>
<head>
<title>�����쳣</title>
<meta http-equiv=Content-Type content=text/html; charset=gb2312>
</head>
<body>
<p>�����쳣: $errmsg </p>
<p><a href="$backurl">�����ﷵ��</a></p>
</body>
</html>
end_of_print;
	exit;
}

/**
 * ��ַת��
 */
function redirect($backurl="javascript:window.history.go(-1);",$message='',$delay=0)
{
    echo "<meta http-equiv='content-type' content='text/html; charset=gb2312'>";
    echo "<Meta HTTP-EQUIV='refresh' content='$delay;url=$backurl'>";
    echo "<p><br><table border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse; bordercolor=#444444' width=80% align=center><tr align=center><td>$message</td></tr>\n";
    echo "<tr align=center><td>���û���Զ����أ���<a href='$backurl'>�������</a></td></tr></table>\n";
    echo "<script language=javascript>\nfunction redirect()\n{window.location='$backurl';}\nvar timer = setTimeout('redirect()',".intval($delay*100).");\ntimer;</script>";
    exit;
}

/**
 * XML��ʽ�������
 * ��β����
 * ע�⣺������user_base,user_session������
 */
function doshutdown() {
  global $shutdownqueries,$sessiontimeout;
  $q = new DB_glb;

/* set to crontab done!
  mt_srand ((double) microtime() * 1000000);
  if (mt_rand(1,5)=='3') {
      $q->query("DELETE FROM session_user WHERE su_time<".(time()-$sessiontimeout));
  }
*/  
  //������������
  if (is_array($shutdownqueries)) {
    while (list($devnul,$query)=each($shutdownqueries)) {
      $q->query($query);
    }
  }
  
}
if (!$noshutdownfunc) {
  register_shutdown_function("doshutdown");
}
// bye bye!


/**
 *  ���Ժ���
 */
function trace($val)
{
    echo "<table border=1><tr><td>";
    if (gettype($val)=='array')
    {
        echo "<pre>";
        print_r($val);
        echo "</pre>";
    }else
    echo "<font color=blue>$val</font><br>";
    if ($php_errormsg) echo $php_errormsg."<br>";
    echo mysql_error();
    echo "</td></tr></table>";
}

function addlog($msg)
{
    global $logfile,$debug;
    if ($debug!=0)
        echo $msg;
    else
        error_log("\n[".date('Y-m-d H:i:s')."] $msg <br>",3,$logfile);
}

function add_log($Log_type,$Log_userid,$Log_content='',$Log_remark='')
// ����û�Log��־
{
	$q=new DB_MIS;
	$Log_datetime=date("Y-m-d H-i-s");
	$q->query("INSERT INTO System_Log (Log_type,Log_userid,Log_datetime,Log_content,Log_remark) VALUES ('$Log_type','$Log_userid','$Log_datetime','$Log_content','$Log_remark')");
}

/**
 *  ���鴦��������
 */
function show_array($the_array,$sort_flag=0)
// ��ʾһ��Array�е���������
{
	//echo "<br> ================= ���飺<font color=red><b>".$the_array."</b></font> ================= <br>";
	if (gettype($the_array)!="array")
	{
	    echo "�����������һ�����飡";
	}
	else
	{
	    reset($the_array);
  		echo "<br>";
  		$i=0;
  		if ($sort_flag)ksort($the_array);
  		reset($the_array);
  		while (list($key, $value) = each($the_array)) 
  		{
    		$i++;
    		$the_type=gettype($value);
    		switch ($the_type)
    		{
    			case "integer":
    			case "double":
    			case "string":
    				echo " �� ".$key.":".$value."<br>";
    				break;
    			case "array":
    				echo " �� ".$key." is a array:<blockquote><p><hr size=1 leggth=50%>";
    				show_array($value);
    				echo "<hr size=1 leggth=50%></blockquote></p>";
    				break;
    			case "object":
    				echo " �� ".$key." is a object.<br>";
    				break;
    			default:
    				echo " �� ".$key." is an unknown type.($the_type)<br>";
    				break;
    		}
  		}
	}
}

function tree_array($the_value,$the_head="",$end_key="",$the_key="")
//��״��ʾһ��Array�е���������
{
	//���β�����������
	$the_head=str_replace("��","��",$the_head);
	$the_head=str_replace("��","&nbsp;&nbsp;",$the_head);
	if ($end_key=="" && $the_key=="" && $the_head=="")
	{
	    $the_head="";
	    echo "<p style='line-height: 95%;'>��ʼ";
	}
	elseif ($end_key>$the_key)
	{
	    $the_head.="��";
	    echo $the_head."��";
	}
	else
	{
	    $the_head.="��";
	    echo $the_head."��";
	}
	$the_type=gettype($the_value);
	switch ($the_type)
	{
		case "integer":
		case "double":
		case "string":
			echo "$the_key:$the_value<br>";
			break;
		case "array":
		    echo "$the_key:Array<br>";
			ksort($the_value);
			end($the_value);
			$ekey=key($the_value);
			reset($the_value);
			while(list($key,$value)=each($the_value))
			{
			    tree_array($value,$the_head."&nbsp;&nbsp;",$ekey,$key);
			}
			break;
		case "object":
			echo $the_key." is an Object.<br>";
			break;
		default:
			echo $the_key." is an unknown type: $the_type <br>";
			break;
	}
}

function find_array($item,$the_array)
//����ĳ��Ԫ������Array�е�λ��
{
	if(!is_array($the_array)) return -1;
	reset($the_array);
	while (list($key, $value) = each($the_array))
	{
		if ($item==$value)
		{
			return $key;
		}
	}
	return -1;
} 

/**
 *  �������
 */
function radom_id($length)
//�����漴����ID��������$length������
{
	$pool="0123456789ABCDEF";
	for($index=0;$index<$length;$index++)
	{
		srand((double)microtime()*1000000);
		$sid.=substr($pool,(rand()%(strlen($pool))),1);
	}
	return($sid);

}

function radom_name($length)
//���ɴ�ǰ׺��������֣���׺������$length������
{
	$pool="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$pool.="abcdefghijklmnopqrstuvwxyz";
	$sname=date("Y-m-d_");
	for($index=0;$index<$length;$index++)
	{
		srand((double)microtime()*1000000);
		$sname.=substr($pool,(rand()%(strlen($pool))),1);
	}
	return($sname);
}

/**
 *  XML��ʽ�������
 * �����������ڲ�
 * parameter must like: 2001-01-01
 * echo day_subtract("2001-08-01","2001-08-03");
 */
function day_subtract($begindate,$enddate="")
{
    if ($enddate=="")$enddate=date("Y-m-d");
    $tmp1=explode("-",$begindate);
    $tmp2=explode("-",$enddate);
    $year1=intval($tmp1[0]);$month1=intval($tmp1[1]);$day1=intval($tmp1[2]);
    $year2=intval($tmp2[0]);$month2=intval($tmp2[1]);$day2=intval($tmp2[2]);
    $stamp=mktime(0,0,0,$month2,$day2,$year2) - mktime(0,0,0,$month1,$day1,$year1);
    $days=intval($stamp/(24*60*60));
    return $days;
}


/**
 *  XML��ʽ�������
 * ��ʽ��Text�����ı�ΪHtml��ʽ��
 * ����TEXTAREA¼���������ҳ��ֱ����ʾ��
 */
function text2html($mytext)
{
    $mystring=htmlspecialchars($mytext);
    $mystring=str_replace(" ","&nbsp;",$mystring);
    $mystring=nl2br($mystring);
    return $mystring;
}

/**
 *  XML��ʽ�������
 * htmlspecialchars���෴��������(����&),���һ�ԭ�ո�
 * ע�⣺��<br>��</br>û�н��л�ԭ��
 * ֻ�ܽ������޶�ʹ��
 */
function html2text($str){
	$str = eregi_replace('&nbsp;',' ',$str);
	$str = eregi_replace('&quot;','"',$str);
	$str = eregi_replace('&lt;','<',$str);
	$str = eregi_replace('&gt;','>',$str);
	return $str;
}

/**
 *  XML��ʽ�������
 * ��ʽ��Text�����ı�ΪString��ʽ��
 * ����script�����TEXTAREA���
 */
function text2string($mytext)
{
    $mystring=$mytext;
    $s_return=chr(13).chr(10);
    $mystring=str_replace($s_return,"\\n",$mystring);
    return $mystring;
}

function socket_http($host,$ip,$port='80',$file='/',$timeout=3){
        global $max_length;
        global $debug;
        if($debug) echo "<pre>";
        if($ip=='')$ip==$host;
        $fp = @fsockopen($ip, $port, $errno, $errstr, $timeout);
        if (!$fp) {
            return -1;//can't connect or connect timeout
        } else {
            $out ="GET {$file} HTTP/1.1\r\n";
            $out.="Host:{$host}\r\n";
            $out.="Accept:*/*\r\n";
            $out.="Pragma:no-cache\r\n";
            $out.="Cache-Control:no-cache\r\n";
            $out.="Referer:http://www.yoka.com/\r\n";
            $out.="User-Agent:Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.8.0.11) Gecko/20070312 Firefox/1.5.0.11\r\n";
            //$out.="Range:bytes={$max_length}-\r\n";
            $out.="\r\n";
            fwrite($fp, $out);
            $RF=chr(0x0D).chr(0x0A);
            $Content_Length='';
            while ($buffer=fgets($fp,1024)) {
                if($debug)echo $buffer;
                //elseif(ereg("Content-Type:",$buffer))header(trim($buffer));
                if(ereg('Content-Length: ([0-9]+)',$buffer,$result))if($result[1] > 1)$Content_Length=$result[1]-1;
                if($buffer==$RF)break;
            }
if($debug && $Content_Length)echo " -- Content_Length: $Content_Length --";
            stream_set_timeout($fp, $timeout*1000);
            if(!feof($fp))while(false !== $buffer=fgetc($fp)){
if($debug){if($debug_counter++ > 1000){echo "R";flush();$debug_counter=0;}}
                $content.= $buffer;
                if($Content_Length>0 && strlen($content)>$Content_Length)break;
            }
            fclose($fp);
        }
        if($debug)echo "</pre><br>\n";
        return $content;
}

?>
