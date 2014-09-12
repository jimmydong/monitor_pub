<?php
/**
 * @name local.inc.php
 * @desc 配置文件
 * @author caoxd
 * @createtime 2009-02-16 11:25
 * @updatetime
 * @caution 路径和URL请不要加反斜线
 **/
/*---------------------------框架级别常量开始---------------------------------*/
//框架根目录
define('YEPF_PATH','/YOKA/HTML/_YEPF');
//是否被正确包含
define('YOKA', true);
//强制关闭转义开关,特殊情况下请设置为true,建议为false
define('YEPF_FORCE_CLOSE_ADDSLASHES', false);
/*--------可以被更小产品级覆盖常量开始,覆盖下面的常量请放在init.php程序中的第一行-------*/
//是否默认打开调试模式
if(!defined('YEPF_IS_DEBUG'))
{
	define('YEPF_IS_DEBUG', true);
}
//自定义错误级别,只有在调试模式下生效
if(!defined('YEPF_ERROR_LEVEL'))
{
	define('YEPF_ERROR_LEVEL', E_ALL & ~E_NOTICE);
}
/*--------可以被更小产品级覆盖常量结束-------*/
/*---------------------------框架级别常量结束---------------------------------*/

/*---------------------------项目级别常量开始---------------------------------*/
//此项目的根目录URL
define('ROOT_DOMAIN','http://monitor.yoka.com:81/pub');
//此项目绝对地址
define('ROOT_PATH','/YOKA/HTML/monitor.yoka.com/pub');
/*--------下面的常量定义都可以被更小项目中前缀为SUB_的同名常量所覆盖-------*/
//此项目日记文件地址
define('LOG_PATH',ROOT_PATH . '/_LOG');
//模板文件目录
define('TEMPLATE_PATH',ROOT_PATH . '/_TEMPLATE/default');
//模板文件编绎目录
define('COMPILER_PATH',ROOT_PATH . '/_TEMPLATE_C/default');
//默认的模板文件后缀名
define('TEMPLATE_TYPE','html');
//配置文件目录
define('AUTOLOAD_CONF_PATH',ROOT_PATH . '/_AUTOLOAD');
//自定义类自动加载路径
define('CUSTOM_CLASS_PATH', ROOT_PATH . '/_CUSTOM_CLASS');
/*--------常量覆盖结束-------*/
/*---------------------------项目级别常量开始---------------------------------*/
?>
