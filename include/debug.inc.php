<?
/*
 * DEBUG专用函数库 by jimmy
 *
 */

session_register($debug);
if($setdebug==1){$debug=1;};
if($setdebug==-1){$debug=0;}; 

/**
 *  处理异常错误，返回信息
 */
function ErrExit($msg,$backurl='javascript:window.history.back(-1)'){
	$errmsg=$msg;
	if ($backurl=='') $backurl = 'javascript:window.history.back(-1)';
    print <<< end_of_print
<html>
<head>
<title>错作异常</title>
<meta http-equiv=Content-Type content=text/html; charset=gb2312>
</head>
<body>
<p>操作异常: $errmsg </p>
<p><a href="$backurl">点这里返回</a></p>
</body>
</html>
end_of_print;
	exit;
}

/**
 * 地址转跳
 */
function redirect($backurl="javascript:window.history.go(-1);",$message='',$delay=0)
{
    echo "<meta http-equiv='content-type' content='text/html; charset=gb2312'>";
    echo "<Meta HTTP-EQUIV='refresh' content='$delay;url=$backurl'>";
    echo "<p><br><table border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse; bordercolor=#444444' width=80% align=center><tr align=center><td>$message</td></tr>";
    echo "<tr align=center><td>如果没有自动跳转，请手工<a href='$backurl'>点击这里</a></td></tr></table>";
}

/**
 *  调试函数
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
    if($logfile=='')$logfile='log.txt';
    if ($debug!=0)
        echo $msg;
    else
        error_log("\n[".date('Y-m-d H:i:s')."] $msg <br>",3,$logfile);
}
