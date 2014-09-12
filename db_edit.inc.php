<?php
/**
 * 数据库编辑插件
 *
 * by weiyang 200706115
 * update by jimmy 20070620
 *
 传入参数
 [必须] $db_edit_obj=array("Lang"=>"latin1", "Host"=>"10.11.3.37:8210", "User"=>"club", "Password"=>"DsWz@sohucluB", "Database"=>"server"); //数据库配置信息
 [必须] $table_name = "server"; 	 		//表名
 [必须] $primary_key = "server_id";	 	//此表的键值字段
 [必须] $include_path					//到db_mysql.inc.php的绝对路径
 [可选] $limit = 300;					//记录条数
 [可选] $visible = array('server_id'=>'服务器ID','locatoin'=>'位置');	//要显示的字段及名称 缺省为全显示
 [可选] $invisible = 'delflag, location'; //不显示的字段。注意：覆盖$visible
 [可选] $edit_valible = 'name, service, delflag';	//可编辑的字段 缺省为全可编辑
 [可选] $edit_avalible = 'server_id';	//不可编辑的字段
 [可选] $condition = ' and delflag=0';	//附加查询条件
 [可选] $orderby = 'ORDER BY stype DESC'; //附加排序 
 *
 */
if($_REQUEST[db_edit_Ajax]==1){ //处理Ajax提交数据更新
//ini_set("display_errors","ON");
    $db_edit_obj=unserialize(stripslashes($_REQUEST[db_edit_obj]));
    //var_dump($db_edit_obj);
	if($include_path && 0)$mysql_inc_file="$include_path/db_mysql.inc.php";
	else $mysql_inc_file="include/db_mysql.inc.php";
	if(!class_exists("DB_Sql"))include($mysql_inc_file);
    $tmp_str = "
    class DB_edit extends DB_Sql {
      var \$Host     = '{$db_edit_obj[Host]}';
      var \$Database = '{$db_edit_obj[Database]}';
      var \$User     = '{$db_edit_obj[User]}';
      var \$Password = '{$db_edit_obj[Password]}';
      var \$Lang     = '{$db_edit_obj['Lang']}';
    }
    ";
    //echo $tmp_str;
    eval($tmp_str);
	$q=new DB_edit;
	
    if ($_GET["key"]!="") $key = trim($_GET["key"]);
	elseif ($_POST["key"]!="") $key = trim($_POST["key"]);
	
	if ($_GET["col_key"]!="") $col_key = trim($_GET["col_key"]);
	elseif ($_POST["col_key"]!="") $col_key = trim($_POST["col_key"]);
	
	if ($_GET["new_value"]!="") $new_value = trim($_GET["new_value"]);
	elseif ($_POST["new_value"]!="") $new_value = trim($_POST["new_value"]);
	
	//if ($_GET["outputkind"]==1||$_POST["outputkind"]==1) $outputkind = 1;
	if ($_GET["table_name"]!="") $table_name = trim($_GET["table_name"]);
	elseif ($_POST["table_name"]!="") $table_name = trim($_POST["table_name"]);
	
	if ($_GET["primary_key"]!="") $primary_key = trim($_GET["primary_key"]);
	elseif ($_POST["primary_key"]!="") $primary_key = trim($_POST["primary_key"]);
	
	$result = 0 ;
	
	if($key!="" && $col_key!=""){
	$new_value=iconv("UTF-8","GBK",$new_value);
	$sql="update $table_name set $col_key = '$new_value'  WHERE $primary_key ='$key'";
	$q->query($sql);
	$ret = $q->affected_rows();
	if($ret<=0) $result = 0;
	else{ $result = 1;}
	}
	
    Header("Content-type: text/xml");
    Header("Cache-Control: no-cache");
    echo "<?xml version=\"1.0\" encoding=\"GB2312\"?>\n";
    echo "<response>\n";
    echo "<result>$result</result>\n";
    echo "<msg>update|$key|$col_key|$new_value|$sql</msg>\n";
    echo "</response>";
    exit;
}
//显示表内数据内容
if(!empty($table_name)){
	if($include_path && 0)$mysql_inc_file="$include_path/db_mysql.inc.php";
	else $mysql_inc_file="../include/db_mysql.inc.php";
	if(!class_exists("DB_Sql"))include($mysql_inc_file);
	
    $tmp_str = "
    class DB_edit extends DB_Sql {
      var \$Host     = '{$db_edit_obj[Host]}';
      var \$Database = '{$db_edit_obj[Database]}';
      var \$User     = '{$db_edit_obj[User]}';
      var \$Password = '{$db_edit_obj[Password]}';
      var \$Lang     = '{$db_edit_obj['Lang']}';
    }
    ";
    //echo $tmp_str;
    eval($tmp_str);
	$q=new DB_edit;
	$q2=new DB_edit;
	
	if($invisible){
	    $tmparray=array();
	    $tmp=explode(',',$invisible);
	    foreach($tmp as $val){
	        $key=trim($val);
	        if($key)$tmparray[$key]=1;
	    }
	    $invisible=$tmparray;
	}
	if($edit_valible){
	    $tmparray=array();
	    $tmp=explode(',',$edit_valible);
	    foreach($tmp as $val){
	        $key=trim($val);
	        if($key)$tmparray[$key]=1;
	    }
	    $edit_valible=$tmparray;
	}
	if($edit_avalible){
	    $tmparray=array();
	    $tmp=explode(',',$edit_avalible);
	    foreach($tmp as $val){
	        $key=trim($val);
	        if($key)$tmparray[$key]=1;
	    }
	    $edit_avalible=$tmparray;
	}
	if(!$limit)$limit=300;

    
    //item
    $q2->query("SELECT * FROM $table_name limit 0,0");
    $table_col = $q2->get_fields();
    //$table_col2 = $table_col;
    if(empty($visible)){ $item = " * ";}
    else{
        $item ="";
        foreach($table_col as $key => $val){
            if(!empty($visible[$val[name]])){
                $item .=$val[name].",";
            }
        }
        $item = substr($item,0,-1);
    }

    $sql ="SELECT $item from $table_name where 1 $condition $orderby limit 0,$limit";
}else{
    echo "参数错误，表名为空！";
}

$q->query($sql);
echo "返回结果".$q->nf()."条";
if ($q->nf()==$limit)echo "。由于条数限制，可能有更多数据未能显示<br>\n";
//$table_style_domain = "border=0 cellpadding=2 cellspacing=2 style='border-collapse: collapse;table-layout : auto;word-wrap:break-word;word-break:break-all;' bordercolor=#111111 width=95%";
$table_style = "border=0 cellpadding=1 cellspacing=1  bordercolor=#111111 width=95%";
$content="
<table $table_style>
";
$str_td ="";
foreach($table_col as $key => $val){ //by jimmy 
    if($invisible[$val[name]]==1){ //被禁止显示
        continue;
	}
	if(empty($visible)) //默认显示
	    $str_td .="<td><font color=white><b>".$val[name]."</b></font></td>";
	elseif($visible[$val[name]]){ 
	    $str_td .="<td><font color=white><b>".$visible[$val[name]]."</b></font></td>";
	}
}
$content.= "<tr bgcolor=\"#666666\" >".$str_td."</tr>\n";
while($q->next_record()){
    if($i++%2) $bgcolor = "bgcolor=\"#CCCCCC\"";
    else $bgcolor = "bgcolor=\"#AAAAAA\"";

    $primary_val = $q->f($primary_key);
    $str_tr = "<tr  $bgcolor id=\"$primary_val\">\n";
    $str_td ="";

    foreach($table_col as $key => $val){
        //trace($val);
        if($invisible[$val[name]]==1)continue; //被禁止显示
        $tmp_str=nl2br($q->f($val[name]));
        if(empty($visible) && $invisible[$val[name]]!=1){    //默认显示
            if(  (empty($edit_valible)&& $edit_avalible[$val[name]]!=1)
            		|| (!empty($edit_valible[$val[name]]) && $edit_avalible[$val[name]]!=1) ){
                    $str_td .="<td  id=\"$primary_val"."_".$val[name]."\" style=\"cursor:pointer;\" onMouseOver=\"show_img('".$primary_val."','".$val[name]."')\" onmouseout=\"hide_img('".$primary_val."','".$val[name]."')\" onclick=\"club_manage_2.update('".$primary_val."','".$val[name]."','".$table_name."','".$primary_key."')\" >".$tmp_str."</td>";
            }else{
                    $str_td .="<td  id=\"$primary_val"."_".$val[name]."\">".$tmp_str."</td>";
            }
        }else{    //按配置显示
            if(!empty($visible[$val[name]])){
                if(empty($edit_valible)  || ($edit_valible[$val[name]]==1 && $edit_avalible[$val[name]]!=1))
                {
                    $str_td .="<td  id=\"$primary_val"."_".$val[name]."\" style=\"cursor:pointer;\" onMouseOver=\"show_img('".$primary_val."','".$val[name]."')\" onmouseout=\"hide_img('".$primary_val."','".$val[name]."')\" onclick=\"club_manage_2.update('".$primary_val."','".$val[name]."','".$table_name."','".$primary_key."')\" >".$tmp_str."</td>";
                     
                }else{
                	  $str_td .="<td  id=\"$primary_val"."_".$val[name]."\">".$tmp_str."</td>";
                }
            }else{ //无名字，不显示
            }
        }
    }

    $content.= $str_tr.$str_td."</tr>\n";
}

$content.= "</table></body>";

//－－－－页面输出开始－－－－
?>
<style>
.cake01 {width: 400px;height: 300px;background-color:#999999;padding:0 2px;filter:progid:DXImageTransform.Microsoft.Shadow(Strength=10, Direction=135, color=#000000);border:1px #000000 solid;}
.cake01 .top {width:389px;height:21px;}
.cake01 .top img {float:right;margin:7px 5px 0 auto;}
.cake01 .head{ width:389px;text-align:center; font-size:16px; margin-bottom:5px}
.cake01 .content {width:389px;height:162px;}
.cake01 .content .w358 {width:358px;margin:0 auto;color:#333;text-align:left;}
.cake01 .content .w358 .w66 {font-size:12px;width:50px;float:left;margin-bottom:40px; margin-left:5px;padding-top:4px; }
.cake01 .content .w358 .w290 {width:290px;float:right;margin-bottom:6px;padding-top:4px;}

.cake01 .content .w358 .w290 textarea {font-size:12px;width:284px;height:58px;border:1px #000000 solid;padding:0 2px;line-height:18px;margin-top:-4px!important;margin-top:-5px;background-color:#AAAAAA;}
.cake01 .content .w358 img {float:right;margin-left:16px;}
.cake01 .content .w358 p {width:100%;text-align:left;line-height:24px;margin-bottom:10px;}
</style>
<script	src="http://image.club.sohu.com/js/prototype_1.5.0.js"></script>
<script>
var club_manage_1 = Class.create();
Object.extend(club_manage_1.prototype, {
	/**
	 * @name creatediv
	 * @desc 创建提示框层
	 * @param
	 * @return void 
	 *
	 */
	creatediv: function(fp,class_name) {
		if ($('notice-div')) {
			$('notice-div').remove();
		}
		if (!$('notice-div')) {
			var noticediv = document.createElement("div");
			document.body.appendChild(noticediv);
			noticediv.id = 'notice-div';
			noticediv.className = class_name;
		}
		$('notice-div').update(fp);
		this.screendark();
	},
	
	/**
	 * @name screendark
	 * @desc 透明背景层
	 * @param
	 * @return void
	 *
	 */
	screendark: function() {
		if ($('screen')) {
			var screen = $('screen');
		}
		if (!screen) {
			var screen = document.createElement("div");
			document.body.appendChild(screen);
		}
		screen.id = "screen";
		screen.style.cssText = "position:absolute;top:0px;left:0px;background:#ffffff;";
		var scrollheight = (document.body.scrollHeight == 0) ? document.documentElement.scrollHeight : document.body.scrollHeight;
		screen.style.width = document.body.scrollWidth +"px";
		screen.style.height = scrollheight + "px";
		screen.style.zIndex = "100";
		try {
			screen.style.filter='Alpha(Opacity=30)';
		}
		catch(e) {
		}
		try {
			screen.style.MozOpacity="0.6";
		}
		catch(e){
		}
	},
	
	
	/**
	 * @name position
	 * @desc 移动层的位置，默认为到屏幕中间
	 * @param
	 * @return void
	 *
	 */
	position: function() {
		var x =(document.body.clientWidth-$('notice-div').getWidth())/2;
		var bodyheight = this.bodyHeight();
		var scrollTop = this.scrolltop();
		var y = 300 + scrollTop-80;
		$('notice-div').style.position = "absolute";
		$('notice-div').style.left = x +'px';
		$('notice-div').style.top = y + 'px';
		$('notice-div').style.zIndex = "1000";
	},
	
	bodyHeight: function (){
		var cobj = document.body;
		while(cobj.scrollTop == 0 && cobj.parentNode) {
			if(cobj.tagName.toLowerCase() == 'html') break;
			cobj = cobj.parentNode;
		}
		return cobj.clientHeight;
	},
	
	scrolltop: function (){
		var cobj = document.body;
		while(cobj.scrollTop == 0 && cobj.parentNode) {
			if(cobj.tagName.toLowerCase() == 'html') break;
			cobj = cobj.parentNode;
		}
		return cobj.scrollTop;
	},
	
	closeall: function() {
		this.closesingle('screen');
		this.closesingle('notice-div');
	},
	
	closesingle: function(divid) {
		if($(divid)){
			$(divid).remove();
		}
	}
});

var club_manage_2 = Class.create();

club_manage_2.sdiv = '<div class="top"><a style="cursor:pointer" onclick="club_manage_2.closeall()"><img src="http://image.club.sohu.com/image/pic0524_01.gif" width="16" height="17" alt="关闭" title="关闭" /></a></div><div id="head" class="head"></div><div class="content"><div class="w358"><div class="w66">原来值：</div><div class="w290"><textarea cols="" rows="" id="old_value" name="old_value"></textarea></div><div class="w66">修改为：</div><div class="w290"><textarea cols="" rows="" id="new_value" name="new_value"></textarea></div><div class="w66"></div><div class="w290"><a style="cursor:pointer" id="updatebutton"><img src="http://image.club.sohu.com/image/pic0524_07_01.gif" width="73" height="26" alt="修改" title="修改" /></a></div></div></div>';

Object.extend(club_manage_2, club_manage_1.prototype);

Object.extend(club_manage_2,{
  update:function (key,col_key,table_name,primary_key){
		var class_name = "cake01";
		this.creatediv(this.sdiv, class_name);
		this.position();
		$('head').innerText = col_key;
		hide_img(key,col_key);
		$('old_value').value = $(key+"_"+col_key).innerHTML;

		$('updatebutton').observe('click', function(event){
			var new_value = $('new_value').value;
			club_manage_3.update.apply(club_manage_3, new Array(key,col_key,new_value,table_name,primary_key));
		});
  
  }
});
function show_img(key,col_key){
	try{$(key+"_"+col_key+"_pic").remove();}catch(e){}
	$(key+"_"+col_key).innerHTML = $(key+"_"+col_key).innerHTML+"<img src=\"http://image.club.sohu.com/image/pic129.gif\" alt=\"修改\" id=\""+key+"_"+col_key+"_pic\"  align=\"texttop\" >"
}

function hide_img(key,col_key){
	try{$(key+"_"+col_key+"_pic").remove();}catch(e){}
}

var club_manage_3 = {
	update: function(key,col_key,new_value,table_name,primary_key) {
		var pars = "db_edit_Ajax=1&key="+key+"&col_key="+col_key+"&new_value="+new_value+"&table_name="+table_name+"&primary_key="+primary_key+"&db_edit_obj=<?=urlencode(serialize($db_edit_obj))?>";
		var myAjax = new Ajax.Request("db_edit.inc.php", {method: "post", parameters: pars, onComplete: this._update});
	},
	_update: function(request) {
		var xmlDom = request.responseXML;
		var result = xmlDom.getElementsByTagName('result')[0].firstChild.data;
    var msg = xmlDom.getElementsByTagName('msg')[0].firstChild.data;
    var tmp = msg.split("|");
    msg = tmp[0];
    if(result == 1){ 
		   club_manage_2.closeall();

		 
    	//alert("修改成功！");
		 $(tmp[1]+"_"+tmp[2]).innerHTML= $(tmp[1]+"_"+tmp[2]).innerHTML+"<br><font color=\"#FF0000\" >-->"+tmp[3]+"</font>";
	
    }
    else {
		   club_manage_2.closeall();
    	//alert("修改失败！");
    }
	}
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<?
echo $content;
?>
