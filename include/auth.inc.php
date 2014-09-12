<?
// Takes three arguments: last attempted username, the authorization
// status, and the Auth object. 
// We won't use them in this simple demonstration -- but you can use them
// to do neat things.
session_start();
function loginFunction($username = null, $status = null, &$auth = null)
{
    /*
     * Change the HTML output so that it fits to your
     * application.
     */
    echo "<form method=\"post\" action=\".".$_SERVER["PHP_SELF"]."\">";
    echo "用户名：<input type=\"text\" name=\"username\"></br>";
    echo "密码：<input type=\"password\" name=\"password\"></br>";
    echo "<input type=\"submit\" value=\"提交\">";
    echo "</form>";
    echo "当前用户名：test 密码：test";
}
$User=$db_define['DB_server']['User'];
$password=$db_define['DB_server']['Password'];
$host=$db_define['DB_server']['Host'];
$database=$db_define['DB_server']['Database'];
$dsn="mysql://$User:".$password."@".$host."/".$database;

$options = array(
  'dsn' => $dsn,
  'table'=>'user',
 'usernamecol'=>'user_name',
  'passwordcol'=>'user_passwd',
   );

?>
