<?php
/**
 * @name cache.config.php
 * @desc memcache & mysql cache 配置文件
 * @author caoxd
 * @todo 1.支持mysql 主从设置.及多从自动轮循.   2.数据库管理生成配置
 **/
$CACHE['memcached'] = array(
					  'score' => array('server'=>array(array('host'=>'192.168.7.112','port'=>'11211'))
					  				  )
					 );

$CACHE['db'] = array(
					'default'=>array(
									 //主数据库
									 'master' =>array(
									 				array('host'=>'127.0.0.1','user'=>'root', 'password'=>'' , 'database'=>'counter'))
									 ), 
				   );
?>
