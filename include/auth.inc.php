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
    echo "�û�����<input type=\"text\" name=\"username\"></br>";
    echo "���룺<input type=\"password\" name=\"password\"></br>";
    echo "<input type=\"submit\" value=\"�ύ\">";
    echo "</form>";
    echo "��ǰ�û�����test ���룺test";
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
