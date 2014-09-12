<?
$debug_auth_type = "get";
$debug_set_cookie = 1;

function unreg_redirect()
{
	global $uid, $pwd;
	if(!$_POST['uid'] or !$_POST['pwd'])
	{
		//header("Location: /web1/Reg/Reg_pre.php?reg=1");
?>
<script lang='javascript'>
	location.href="/web1/Reg/Reg.php?reg=1";
</script>
<?
		exit;
	}
	$uid = $_POST['uid'];
	$pwd = $_POST['pwd'];
}

function auth_request()
{
	global $uid, $pwd;
	if(!$_REQUEST['uid'])die("uid?");
	if(!$_REQUEST['pwd'])die("pwd?");
	$uid = $_REQUEST['uid'];
	$pwd = $_REQUEST['pwd'];
}

function auth_get()
{
	global $uid, $pwd;
	if(!$_GET['uid'])die("uid?");
	if(!$_GET['pwd'])die("pwd?");
	$uid = $_GET['uid'];
	$pwd = $_GET['pwd'];
}

function auth_post()
{
	global $uid, $pwd;
	if(!$_POST['uid'])die("uid?");
	if(!$_POST['pwd'])die("pwd?");
	$uid = $_POST['uid'];
	$pwd = $_POST['pwd'];
}
function auth_cookie()
{
	global $uid, $pwd;
	if(!$_COOKIE['uid'])die("uid?");
	if(!$_COOKIE['pwd'])die("pwd?");
	$uid = $_COOKIE['uid'];
	$pwd = $_COOKIE['pwd'];
}

function auth_get_auth_db()
{
	auth_get();
	auth_db();
}

function auth_post_auth_db()
{
	auth_post();
	auth_db();
}
function auth_cookie_auth_db()
{
	auth_cookie();
	auth_db();
}

function check_token_request()
{
	global $sid, $tk;
	if(!$_REQUEST['sid'])die("sid?");
	if(!$_REQUEST['tk'])die("tk?");
	$sid = $_REQUEST['sid'];
	$tk = $_REQUEST['tk'];
	if(! check_token($sid, $tk) )
	{
		die ("check failed!");
	}
}
function check_token_get()
{
	global $sid, $tk;
	if(!$_GET['sid'])die("sid?");
	if(!$_GET['tk'])die("tk?");
	$sid = $_GET['sid'];
	$tk = $_GET['tk'];
	if(! check_token($sid, $tk) )
	{
		die ("check failed!");
	}
}
function check_token_post()
{
	global $sid, $tk;
	if(!$_POST['sid'])die("sid?");
	if(!$_POST['tk'])die("tk?");
	$sid = $_POST['sid'];
	$tk = $_POST['tk'];
	if(! check_token($sid, $tk) )
	{
		die ("check failed!");
	}
}


function check_token_get_auth_db()
{
	auth_get();
	
	global $sid, $tk;
	if(!$_GET['sid'])die("sid?");
	if(!$_GET['tk'])die("tk?");
	$sid = $_GET['sid'];
	$tk = $_GET['tk'];
	if(! check_token($sid, $tk) )
	{
		die ("check failed!");
	}
	
	auth_db();
}
function check_token_post_auth_db()
{
	auth_post();
	
	global $sid, $tk;
	if(!$_POST['sid'])die("sid?");
	if(!$_POST['tk'])die("tk?");
	$sid = $_POST['sid'];
	$tk = $_POST['tk'];
	if(! check_token($sid, $tk) )
	{
		die ("check failed!");
	}
	
	auth_db();
}


function auth_db()
{
	global $uid,$pwd;
	////require_once("common.php");
	////db_connect("db1");
	$sql="SELECT * from user where User_ID='$uid'";
	if (! $qh = mysql_query($sql) )
	{
		die("[auth_db]".mysql_error());
	}
	if( mysql_num_rows($qh)<1 )
	{
		die("auth_db not pass!");
	}
	else
	{
		$row = mysql_fetch_array($qh) or die("[auth_db]".mysql_error());
		if( $row['Password_plain'] != decode_pwd($pwd) )
			die("auth_db not pass!");
	}
}

//用户token验证
function check_token($sid, $tk)
{
	if($_REQUEST["PHPSESSID"] != $sid) return 0;
	$tk2 = gen_token($sid);
  //echo "[$tk][$tk2]";
	if($tk == $tk2)return 1;
	return 0;
}
function gen_token($sid)
{
	//4f1aff1acabbb7b3181140d84b65c255
	//0-9[48]-[57] A-Z[65]-[90] a-z[97]-[122]
  for($i=0; $i< strlen($sid); $i++ ){
		$in = ord( substr($sid,$i,1) );
		if( $in<48)
		{
			$out = (10-$in%10)+48;
		}
		if( $in>=48 && $in<=57)
		{
			$out = (57-$in)+65;
		}
		if( $in>=65 && $in<=90)
		{
			$out = (90-$in)+65;
		}
		if( $in>=97 && $in<=122)
		{
			$out = (122-$in)+97;
		}
		if( $in>122)
		{
			$out = (10-$in%10)+48;
		}

		$tk2 .= chr($out);
  }
  return $tk2;
  //return md5($tk2);
}
function encode_pwd($pwd)
{
  for($i=0; $i< strlen($pwd); $i++ ){
		$in = ord( substr($pwd,$i,1) );
  	$tmp = $in ^ 1;
  	$out .= chr($tmp);
  }
  return $out;
}
function decode_pwd($pwd)
{
  for($i=0; $i< strlen($pwd); $i++ ){
		$in = ord( substr($pwd,$i,1) );
  	$tmp = $in ^ 1;
  	$out .= chr($tmp);
  }
  return $out;
}

function refresh_check()
{
	//--refresh check --减少非法访问也用数据库验证
	global $REQUEST_URI;
	$refresh_allowed = 2;
	$timestamp = time();
	$cookietime = $timestamp+3153600;
	if($refresh_allowed) {
	        if ($REQUEST_URI == $HTTP_COOKIE_VARS['lastpath'] && ($timestamp-$HTTP_COOKIE_VARS['lastvisit_fr']<$refresh_allowed)) {
	                die('本次显示禁止，原因：访问同一URL的刷新时间小于'.$refresh_allowed.'秒');
	        }
	        setCookie('lastpath', $REQUEST_URI, $cookietime);
	        setCookie('lastvisit_fr', $timestamp, $cookietime);
	        //echo "setCookie[$REQUEST_URI]";
	}
}

function debug_set_cookie()
{
	global $debug_set_cookie;
	if(!$debug_set_cookie)exit;
	if( $_COOKIE['uid'] && $_COOKIE['pwd'] )
	{}
	else
	{
	  ////require_once "../include/common.php";
	  db_connect("db1");
	  //echo "[$g_dbh]";
		$sql="SELECT * from user where length(password)=32 limit 0,1";
		$qh = mysql_query($sql) or die("[debug_set_cookie]".mysql_error());
		if( mysql_num_rows($qh)<1 )
		{
			die("[debug_set_cookie][$sql]user not found!");
		}
		$row = mysql_fetch_array($qh);
		$cookietime = time()+30*24*3600;
	  setCookie('uid', $row['User_ID'], $cookietime,"/");
	  setCookie('pwd', $row['Password'], $cookietime,"/");
	  //print_r($row);
	}

}
?>