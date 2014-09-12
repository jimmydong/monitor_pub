<?
/**
 * 公共文件：数据库结构说明
 * 
 * DOC 表的结构
 * CREATE TABLE `_doc` (
 *   `table` varchar(60) NOT NULL default '',
 *   `field` varchar(60) NOT NULL default '',
 *   `content` varchar(200) NOT NULL default '',
 *   `remark` text NOT NULL,
 *   PRIMARY KEY  (`table`,`field`)
 * ) TYPE=MyISAM COMMENT='数据表说明文档';
 *
 * DOC 表table字段保留字含义
 *   _all - 全局缺省值
 *
 * DOC 表field字段保留字含义
 *   _remark - 对表的特殊说明 content=简要说明 remark=详细说明
 *   _log - 对表的修改说明 content=修改说明 remark=历史纪录
 *
 * @author jimmy
 * @packet JDK
 * @version 2004.09.15
 * @param $q $q2 注意：必须提供两个相同的数据库连接 | $action | $modify=1 编辑模式
 */

if($action=='updatecomment')
{
    if ($table=='') ErrExit("Error: 传入参数错误!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=action value='updatecommentdone'>
    <p>为 $table 表修改注释
    <p>新注释：<input type=text size=60 name=comment value='$comment'> （少于60字节）
    <p><input type=submit value=确定>
    </form>
end_of_print;
exit;
}

if($action=='updatecommentdone')
{
    if ($table=='') ErrExit("Error: 传入参数错误!");
    $comment=addslashes($comment);
    if ($q->query("ALTER TABLE `$table` COMMENT = '$comment'"))
    {
        redirect("$currenturl?modify=1#$table", "系统信息：信息更新成功！", 1);
        exit;
    }
    else
        ErrExit("数据库操作失败！");
}
    
if($action=='addfield')
{
    if ($table=='' || $field=='') ErrExit("Error: 传入参数错误!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='$field'>
    <input type=hidden name=action value='addfielddone'>
    <p>为 $table 表 $field 项添加说明
    <p>设定为缺省值：<input type=checkbox name=all value=1>是
    <p>一般说明：<input type=text size=60 name=content value=''> （少于200字节）
    <p>详细说明：<textarea rows=6 cols=60 name=remark></textarea>
    <p><input type=submit value=确定>
    </form>
end_of_print;
exit;
}

if($action=='addfielddone')
{
    if ($table=='' || $field=='') ErrExit("Error: 传入参数错误!");
    $content=addslashes($content);
    $remark=addslashes($remark);
    $tablename=$table;
    if($all==1)$table="_all";
    if ($q->query("REPLACE _doc SET `table`='$table', `field`='$field', `content`='$content', `remark`='$remark'"))
    {
        redirect("$currenturl?modify=1#$tablename", "系统信息：信息更新成功！", 1);
        exit;
    }
    else
        ErrExit("数据库操作失败！");
}

if($action=='updatefield')
{
    if (!$fieldinfo=$q->query_first("SELECT * FROM `_doc` WHERE `table`='$table' AND `field`='$field'"))
    {
        if (!$fieldinfo=$q->query_first("SELECT * FROM `_doc` WHERE `table`='_all' AND `field`='$field'"))
            ErrExit("Error: 传入参数错误!");
        $notice="<font color=red>注意：当前项目未定义，显示为缺省值</font>";
    }
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='$field'>
    <input type=hidden name=action value='addfielddone'>
    <p>为 $table 表 $field 项添加说明 $notice
    <p>设定为缺省值：<input type=checkbox name=all value=1>是
    <p>一般说明：<input type=text size=60 name=content value='{$fieldinfo[content]}'> （少于200字节）
    <p>详细说明：<textarea rows=6 cols=60 name=remark>{$fieldinfo[remark]}</textarea>
    <p><input type=submit value=确定>
    </form>
end_of_print;
exit;
}

if($action=='addlog')
{
    if ($table=='') ErrExit("Error: 传入参数错误!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='_log'>
    <input type=hidden name=action value='addlogdone'>
    <p>为 $table 表添加修改LOG
    <p>LOG内容：<input type=text size=60 name=content value=''> （少于200字节）
    <p><input type=submit value=确定>
    </form>
end_of_print;
exit;
}

if($action=='addlogdone')
{
    if ($table=='' || $field!='_log') ErrExit("Error: 传入参数错误!");
    $loginfo=$q->query_first("SELECT * FROM `_doc` WHERE `table`='$table' AND `field`='_log'");
    $content=date("Y-m-d H:i:s ").addslashes($content);
    $remark=$loginfo['content']."<br>\n".$loginfo['remark'];
    if ($q->query("REPLACE _doc SET `table`='$table', `field`='$field', `content`='$content', `remark`='$remark'"))
    {
        redirect("$currenturl?modify=1#$table", "系统信息：LOG录入操作成功！", 1);
        exit;
    }
    else
        ErrExit("数据库操作失败！");
}

if($action=='addremark')
{
    if ($table=='') ErrExit("Error: 传入参数错误!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='_remark'>
    <input type=hidden name=action value='addfielddone'>
    <p>为 $table 表添加特别说明
    <p>一般说明：<input type=text size=60 name=content value=''> （少于200字节）
    <p>详细说明：<textarea rows=6 cols=60 name=remark></textarea>
    <p><input type=submit value=确定>
    </form>
end_of_print;
exit;
}
if($action=='updateremark')
{
    if (!$fieldinfo=$q->query_first("SELECT * FROM `_doc` WHERE `table`='$table' AND `field`='_remark'")) ErrExit("Error: 传入参数错误!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='_remark'>
    <input type=hidden name=action value='addfielddone'>
    <p>为 $table 表添加特别说明
    <p>一般说明：<input type=text size=60 name=content value='{$fieldinfo[content]}'> （少于200字节）
    <p>详细说明：<textarea rows=6 cols=60 name=remark>{$fieldinfo[remark]}</textarea>
    <p><input type=submit value=确定>
    </form>
end_of_print;
exit;
}

/*
 *-------------------------------------------缺省操作：显示数据库结构信息----------------------------------
 */
//获取数据库结构信息
$no_doc=true;
$q->query("SHOW TABLE STATUS");
while($q->next_record())
{
    $table=$q->Record;
    if($table[Name]!="_doc")
    {
        $db_index[]=$table[Name];
        $db_comment[$table[Name]] = $table[Comment];
        $db_rows[$table[Name]] = $table[Rows];
        $q2->query("SELECT * FROM {$table[Name]}");
        $db_info[$table[Name]] = $q2->get_fields();
    }
    else
    {
        $q2->query("SELECT * FROM {$table[Name]}");
        $tmp_info = $q2->get_fields();
        if ($tmp_info[0]['name']=='table' && $tmp_info[1]['name']=='field') $no_doc=false;
    }        
}

if(count($db_index)==0) ErrExit("当前库中没有可显示的表结构");

echo "<a name=top></a><div class=boxh>表索引</div>\n<div class=boxb>\n<table border=0>\n";
asort($db_index);
foreach($db_index as $tablename)
{
    echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;· <a href=#$tablename>$tablename</a> </td><td> <i>...$db_comment[$tablename]</i></td></tr>\n";
}
echo "</table>\n</div>\n";

//读出DOC表内容
if(!$no_doc)
{
    $q->query("SELECT * FROM _doc");
    while($q->next_record())
    {
        if ($q->f('table')=='' || $q->f('field')=='') continue;
        $doc_content[$q->f('table')][$q->f('field')] = $q->f('content');
        $doc_remark[$q->f('table')][$q->f('field')] = $q->f('remark');
    }
}
else
{
    if ($modify==1)
    {
        echo "<div class=boxb>Warrning: <p>未找到内容介绍文档结构，编辑模式禁止！</div>";
        $modify = 0;
    }
}

foreach($db_info as $table_name=>$table_info)
{
    ?>
    <a name='<?=$table_name?>'>
    <h3><a href=#top>↑</a>数据表【<?=$table_name?>】 - <?=$db_comment[$table_name]?><?if($modify==1)echo "<a href={$currenturl}?action=updatecomment&table={$table_name}&comment=".urlencode($db_comment[$table_name]).">&raquo;</a>"?>(数据量:<?=$db_rows[$table_name]?>)</h3>
    <div class=boxc>
    <?
    showhelp($doc_content[$table_name][_log],$doc_content[$table_name][_log]);
    if($modify==1) //编辑模式
    {
        echo "<a href={$currenturl}?action=addlog&table={$table_name}>newlog&raquo;</a><br>";
    }
    
    if($doc_content[$table_name][_remark])
    {
        showhelp($doc_content[$table_name][_remark],$doc_remark[$table_name][_remark]);
        if($modify==1) 
        {
            echo "<a href={$currenturl}?action=updateremark&table={$table_name}>upremark&raquo;</a>";
        }
    }
    else
    {
        if($modify==1) 
        {
            echo "<a href={$currenturl}?action=addremark&table={$table_name}>addremark&raquo;</a>";
        }
    }    
    ?>
    </div>
    <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="80%" align=center>
      <tr>
        <td bgcolor="#868786" width=200><b><font color=white>字段名</font></b></td>
        <td bgcolor="#868786" width=150><b><font color=white>类型</font></b></td>
        <td bgcolor="#868786" width=*><b><font color=white>说明</font></b></td>
      </tr>
        <?
        foreach($table_info as $key=>$val)
        {
            ?>
      <tr>
        <td><?showhelp($val[name],$val[flags]);?></span></td>
        <td><?=$val[type]?>(<?=$val[len]?>)</td>
        <td><?
                $showdoc=$doc_content[$table_name][$val[name]];
                if($showdoc=='') $showdoc=$doc_content[_all][$val[name]];
                $helpdoc=$doc_remark[$table_name][$val[name]];
                if($helpdoc=='') $helpdoc=$doc_remark[_all][$val[name]];
                if($modify==1) //编辑模式
                {
                    if($showdoc=='')
                    {
                        echo "<a href={$currenturl}?action=addfield&table={$table_name}&field={$val[name]}>&raquo;</a>";
                    }
                    else
                    {
                        echo "<a href={$currenturl}?action=updatefield&table={$table_name}&field={$val[name]}>&raquo;</a>";
                    }
                }
                showhelp($showdoc,$helpdoc,1);
             ?></td>
      </tr>
            <?
        }
        ?>
    </table>
    <br>
    <br>
    <?
}    
?>
