<?
/**
 * �������
 * 
 * ʹ�÷�����

include_once("include/config.inc.php");
$Cache_Expire_Time= 3600*24;
include("$include_path/cache_begin.inc.php");
if($skip_get_content==0){
	... ... //��������
	... ... //��������
include("$include_path/cache_end.inc.php");
} //end skip_get_content

 *
 */
if(!$Cache_Expire_Time>0) $Cache_Expire_Time	= 3600*23;

$skip_get_content=0;
if($Debug!=1){
	if($absoluteFile=="") $absoluteFile = $currentfullurl;
	$absoluteFile=str_replace('&NoCache=1','',$absoluteFile);
	$absoluteFile=str_replace('NoCache=1','',$absoluteFile);
	$htmlfn = Conv_QuyStr($absoluteFile);
	
	$cache_filename = "$cache_path/".getHashPath($htmlfn).".html";
//trace($cache_filename);	
	mkdirs($cache_filename,0744);
	$cache_filename_tmp = "$cache_path/".getHashPath($htmlfn).".tmp";
	
	if($NoCache!=1){
		if(file_exists($cache_filename)) {//�����ļ��Ƿ����
			if((date("U")-date("U",filectime($cache_filename)))<=$Cache_Expire_Time) {
//trace($cache_filename);
				include($cache_filename);
				echo "<!--include $cache_filename-->";
				$skip_get_content=1;
			}else{
				if((file_exists($cache_filename_tmp))&&((date("U")-date("U",filectime($cache_filename_tmp)))<=45)){ //��ʱ�ļ����ڲ���ʱ��С��45�룬��ô�͵����ϵĻ���
					include($cache_filename);
					echo "<!--include temp from $cache_filename-->";
					$skip_get_content=1;
				}  
			}	
		}
	}
	if(file_exists($cache_filename_tmp)){
		//do nothing
		//ErrExit("������æ�����Ժ�����");
	}else{
		if($fp = fopen ($cache_filename_tmp, "w")){
			fwrite ($fp, "in use..");
			fclose ($fp);
			echo "<!-- create temp -->";	
		}
	}
	ob_start();
}

/*--------------------------- Cache �������� -----------------------------*/
function mkdirs($pathStr,$mod){
        if(is_file($path) || is_dir($pathStr)) return true;
        $pieces = explode("/", $pathStr);
        $tmpdir = "";
        for($mm=1;$mm<(count($pieces)-1);$mm++){
                $tmpdir      .= "/".$pieces[$mm];
                if(!is_dir($tmpdir)){
                        mkdir($tmpdir,$mod);
                }
        }
        return true;
}

function getHashPath($HashStr){
        if(strlen($HashStr) < 4) return("");
        $hs1    = substr($HashStr,-2);
        //$hs2    = substr($HashStr,-4,2);
        //return $hs1."/".$hs2."/".$HashStr;
        return $hs1."/".$HashStr;
}

function Conv_QuyStr($Q_str) {
        $filename = md5($Q_str);
        return $filename;
}
?>
