<?
/*
 * DEBUGר�ú����� by jimmy
 *
 */

session_register($debug);
if($setdebug==1){$debug=1;};
if($setdebug==-1){$debug=0;}; 

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
    echo "<p><br><table border=1 cellpadding=0 cellspacing=0 style='border-collapse: collapse; bordercolor=#444444' width=80% align=center><tr align=center><td>$message</td></tr>";
    echo "<tr align=center><td>���û���Զ���ת�����ֹ�<a href='$backurl'>�������</a></td></tr></table>";
}

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
    if($logfile=='')$logfile='log.txt';
    if ($debug!=0)
        echo $msg;
    else
        error_log("\n[".date('Y-m-d H:i:s')."] $msg <br>",3,$logfile);
}
