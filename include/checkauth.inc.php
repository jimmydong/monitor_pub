<?
require_once "auth.php";
require_once "$root_path/include/auth.inc.php";
//print_r($options);
$a = new Auth("DB", $options, "loginFunction");
$a->start();
if($a->checkAuth()==False) {
    /*
     * The output of your site goes here.
     */
   // echo "cccc";
  //  header('Location: ./login.php');
//  $a->start();
           exit;
}


?>
