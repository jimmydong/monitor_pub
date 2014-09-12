<?
include("../server/include/config.inc.php");
$step=$_REQUEST[step];
$log=$_REQUEST[thelog];
showhead_monitor('slowquery');
if(!$step){
print <<< end_of_print

<form name=form1 id=form1 action=tools_slowquery.php method=post>
<input type=hidden name=step value=1>
<textarea cols=120 rows=40 name=thelog></textarea>
<input type=submit value=OK>
</form>

end_of_print;
}else{
$tmp=chr(13).chr(10);
$thelog=str_replace($tmp,chr(10),$thelog);
file_put_contents('slow_query.log',$thelog);
$cmd="/YOKA/SBIN/slow_query.sh";
echo "<pre>";
echo passthru($cmd);
echo "</pre>";

}
?>

