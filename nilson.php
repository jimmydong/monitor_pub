<?
class CURL {
   var $callback = false;

function setCallback($func_name) {
   $this->callback = $func_name;
}

function doRequest($method, $url, $vars, $ref='') {
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_HEADER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
   curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
   if($ref=='')curl_setopt($ch, CURLOPT_AUTOREFERER, true);
   else curl_setopt($ch, CURLOPT_REFERER, $ref);
   if ($method == 'POST') {
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
   }
   $data = curl_exec($ch);
   curl_close($ch);
   if ($data) {
       if ($this->callback)
       {
           $callback = $this->callback;
           $this->callback = false;
           return call_user_func($callback, $data);
       } else {
           return $data;
       }
   } else {
       return curl_error($ch);
   }
}

function get($url,$ref='') {
   return $this->doRequest('GET', $url, 'NULL', $ref);
}

function post($url, $vars) {
   return $this->doRequest('POST', $url, $vars);
}
}
$curl=new CURL;
$url='http://secure-cn.imrworldwide.com/cgi-bin/m?rnd=1245208120604&ci=cn-fashion2009yoka&cg=0&cc=1&sr=1280x1024&cd=32&lg=zh-CN&je=n&ck=y&tz=8&fl=10&si=http%3A//www.yoka.com/star/starface/2009/0617207775.shtml&rp=http%3A//www.yoka.com/';
$ref='http://www.yoka.com/star/starface/2009/0617207775.shtml';
$url='http://secure-cn.imrworldwide.com/cgi-bin/j?ci=cn-fashion2009yoka&ss=15&cc=1&rd=1245206262214&se=1&sv=';
$ref='http://www.yoka.com/star/model/2009/0615206'.rand(100,999).'.shtml';
$tmp=$curl->get($url,$ref);
$tmp=http_data($tmp);
for($i=0;$i<3000;$i++){
$ref='http://www.yoka.com/star/model/2009/0615206'.rand(100,999).'.shtml';
$tmp_raw=$curl->get($url,$ref);
$tmp_new=http_data($tmp_raw);
if($tmp_new != $tmp) {
//echo " <hr> <pre>".htmlspecialchars(substr($tmp_new, 17))." </pre>\n";
ereg('var _rsID = "([^"]*)";',$tmp_new,$result);//var_dump($result);
echo "http://secure-cn.imrworldwide.com/cgi-bin/perseus6.pl?P=cn0009.htm&Qsystem_1={$result[1]}&Qsystem_6=cn-fashion2009yoka <br>\n";
}
}
echo done;exit;

function http_data($str){
$tmp=explode('Content-Type:',$str);
$key=count($tmp)-1;
return $tmp[$key];
}

