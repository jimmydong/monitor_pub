<?
/**
 * YOKA Crontab Contrl Center
 *
 * by jimmy 20090604
 */
include("../server/include/config.inc.php");
$q=new DB_server;
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
$remote=$_SERVER[REMOTE_ADDR];
var_dump($_REQUEST);
echo "cid: $cid \n";
echo "rid: $rid \n";exit;
if(!$cid || !$rid){
	file_put_contents('/YOKA/HTML/monitor.yoka.com/pub/ccc_err.log',date("Y-m-d H:i:s")." ".$_SERVER[REMOTE_ADDR]." ".$_SERVER[REQUEST_URI]." <br>\n",FILE_APPEND);
	echo 0;
	exit;
}
if($step!=1){
	// crontab begin request
	if($cflag==0){$mflag=1;$conflict_status=1;}
	else{
		if($cflag>100)$check_history=$cflag-100;
		else $check_history=3;
		$conflict=0;$waitflag=0;
		$sql="SELECT * FROM YOKA_CCC WHERE cid='$cid' ORDER BY id DESC LIMIT $check_history";
		if($debug)$q->query_all($sql);
		$q->query($sql);
		while($record=$q->next_record()){
			if($record[etime] > '2009-01-01 00:00:00' && $waitflag==0){$waitflag=2;break;}
			elseif($record[conflict_status]==2)$conflict++;
			else $waitflag=1;
		}
		if($debug)echo "WaitFlag: $waitflag | Conflict: $conflict <br>\n";
		if($waitflag==2) $conflict_status='1'; //ok
		elseif($conflict > $check_history-1) {$mflag=2;$conflict_status='3';}//found and repair
		else {$mflag=2;$conflict_status=2;}//conflict!
	}
	$sql="INSERT INTO YOKA_CCC SET cid='$cid', rid='$rid', cflag='$cflag', email='".addslashes($email)."', thecall='".addslashes($call)."', mflag='$mflag', conflict_status='$conflict_status', btime=now(), remote='$remote'";
	if($debug==1)$q->fquery($sql);
	$q->query($sql);
	echo $conflict_status;
	exit;
}else{
	// crontab end request
	if(!$record=$q->query_first("SELECT * FROM YOKA_CCC WHERE cid='$cid' AND rid='$rid' AND eflag='' ORDER BY id DESC limit 1")){
		$return_status=3;
		$q->query("INSERT INTO YOKA_CCE SET cid='$cid', rid='$rid', remote='$remote', thecall='".addslashes($call)."', step='$step', thetime=now()");
	}else{
		$q->query("UPDATE YOKA_CCC SET eflag='$eflag', etime=now() WHERE id='{$record[id]}'");
		$return_status=1;
	}
	echo $return_status;exit;
}
?>
