<?
if(Debug!=1){
	$allPageBody=ob_get_contents();
	if(file_exists($cache_filename)) {//缓冲文件是否存在
		$M_TIME = 30;
		if((date("U")-date("U",filectime($cache_filename)))>$M_TIME){
			$fp = fopen ($cache_filename, "w") or die("error");
			fwrite ($fp, $allPageBody);
			fclose ($fp);
			echo "<!--create+$cache_filename-->";
		}	
	}else{
		$fp = fopen ($cache_filename, "w") or die("error");
		fwrite ($fp, $allPageBody);
		fclose ($fp);
		echo "<!--create+$cache_filename-->";
	}
	if(file_exists($cache_filename_tmp)) unlink($cache_filename_tmp);
}
?>
