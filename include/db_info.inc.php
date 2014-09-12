<?
/**
 * �����ļ������ݿ�ṹ˵��
 * 
 * DOC ��Ľṹ
 * CREATE TABLE `_doc` (
 *   `table` varchar(60) NOT NULL default '',
 *   `field` varchar(60) NOT NULL default '',
 *   `content` varchar(200) NOT NULL default '',
 *   `remark` text NOT NULL,
 *   PRIMARY KEY  (`table`,`field`)
 * ) TYPE=MyISAM COMMENT='���ݱ�˵���ĵ�';
 *
 * DOC ��table�ֶα����ֺ���
 *   _all - ȫ��ȱʡֵ
 *
 * DOC ��field�ֶα����ֺ���
 *   _remark - �Ա������˵�� content=��Ҫ˵�� remark=��ϸ˵��
 *   _log - �Ա���޸�˵�� content=�޸�˵�� remark=��ʷ��¼
 *
 * @author jimmy
 * @packet JDK
 * @version 2004.09.15
 * @param $q $q2 ע�⣺�����ṩ������ͬ�����ݿ����� | $action | $modify=1 �༭ģʽ
 */

if($action=='updatecomment')
{
    if ($table=='') ErrExit("Error: �����������!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=action value='updatecommentdone'>
    <p>Ϊ $table ���޸�ע��
    <p>��ע�ͣ�<input type=text size=60 name=comment value='$comment'> ������60�ֽڣ�
    <p><input type=submit value=ȷ��>
    </form>
end_of_print;
exit;
}

if($action=='updatecommentdone')
{
    if ($table=='') ErrExit("Error: �����������!");
    $comment=addslashes($comment);
    if ($q->query("ALTER TABLE `$table` COMMENT = '$comment'"))
    {
        redirect("$currenturl?modify=1#$table", "ϵͳ��Ϣ����Ϣ���³ɹ���", 1);
        exit;
    }
    else
        ErrExit("���ݿ����ʧ�ܣ�");
}
    
if($action=='addfield')
{
    if ($table=='' || $field=='') ErrExit("Error: �����������!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='$field'>
    <input type=hidden name=action value='addfielddone'>
    <p>Ϊ $table �� $field �����˵��
    <p>�趨Ϊȱʡֵ��<input type=checkbox name=all value=1>��
    <p>һ��˵����<input type=text size=60 name=content value=''> ������200�ֽڣ�
    <p>��ϸ˵����<textarea rows=6 cols=60 name=remark></textarea>
    <p><input type=submit value=ȷ��>
    </form>
end_of_print;
exit;
}

if($action=='addfielddone')
{
    if ($table=='' || $field=='') ErrExit("Error: �����������!");
    $content=addslashes($content);
    $remark=addslashes($remark);
    $tablename=$table;
    if($all==1)$table="_all";
    if ($q->query("REPLACE _doc SET `table`='$table', `field`='$field', `content`='$content', `remark`='$remark'"))
    {
        redirect("$currenturl?modify=1#$tablename", "ϵͳ��Ϣ����Ϣ���³ɹ���", 1);
        exit;
    }
    else
        ErrExit("���ݿ����ʧ�ܣ�");
}

if($action=='updatefield')
{
    if (!$fieldinfo=$q->query_first("SELECT * FROM `_doc` WHERE `table`='$table' AND `field`='$field'"))
    {
        if (!$fieldinfo=$q->query_first("SELECT * FROM `_doc` WHERE `table`='_all' AND `field`='$field'"))
            ErrExit("Error: �����������!");
        $notice="<font color=red>ע�⣺��ǰ��Ŀδ���壬��ʾΪȱʡֵ</font>";
    }
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='$field'>
    <input type=hidden name=action value='addfielddone'>
    <p>Ϊ $table �� $field �����˵�� $notice
    <p>�趨Ϊȱʡֵ��<input type=checkbox name=all value=1>��
    <p>һ��˵����<input type=text size=60 name=content value='{$fieldinfo[content]}'> ������200�ֽڣ�
    <p>��ϸ˵����<textarea rows=6 cols=60 name=remark>{$fieldinfo[remark]}</textarea>
    <p><input type=submit value=ȷ��>
    </form>
end_of_print;
exit;
}

if($action=='addlog')
{
    if ($table=='') ErrExit("Error: �����������!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='_log'>
    <input type=hidden name=action value='addlogdone'>
    <p>Ϊ $table ������޸�LOG
    <p>LOG���ݣ�<input type=text size=60 name=content value=''> ������200�ֽڣ�
    <p><input type=submit value=ȷ��>
    </form>
end_of_print;
exit;
}

if($action=='addlogdone')
{
    if ($table=='' || $field!='_log') ErrExit("Error: �����������!");
    $loginfo=$q->query_first("SELECT * FROM `_doc` WHERE `table`='$table' AND `field`='_log'");
    $content=date("Y-m-d H:i:s ").addslashes($content);
    $remark=$loginfo['content']."<br>\n".$loginfo['remark'];
    if ($q->query("REPLACE _doc SET `table`='$table', `field`='$field', `content`='$content', `remark`='$remark'"))
    {
        redirect("$currenturl?modify=1#$table", "ϵͳ��Ϣ��LOG¼������ɹ���", 1);
        exit;
    }
    else
        ErrExit("���ݿ����ʧ�ܣ�");
}

if($action=='addremark')
{
    if ($table=='') ErrExit("Error: �����������!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='_remark'>
    <input type=hidden name=action value='addfielddone'>
    <p>Ϊ $table ������ر�˵��
    <p>һ��˵����<input type=text size=60 name=content value=''> ������200�ֽڣ�
    <p>��ϸ˵����<textarea rows=6 cols=60 name=remark></textarea>
    <p><input type=submit value=ȷ��>
    </form>
end_of_print;
exit;
}
if($action=='updateremark')
{
    if (!$fieldinfo=$q->query_first("SELECT * FROM `_doc` WHERE `table`='$table' AND `field`='_remark'")) ErrExit("Error: �����������!");
    print <<< end_of_print
    <form method=post action=$currenturl name=form1>
    <input type=hidden name=table value='$table'>
    <input type=hidden name=field value='_remark'>
    <input type=hidden name=action value='addfielddone'>
    <p>Ϊ $table ������ر�˵��
    <p>һ��˵����<input type=text size=60 name=content value='{$fieldinfo[content]}'> ������200�ֽڣ�
    <p>��ϸ˵����<textarea rows=6 cols=60 name=remark>{$fieldinfo[remark]}</textarea>
    <p><input type=submit value=ȷ��>
    </form>
end_of_print;
exit;
}

/*
 *-------------------------------------------ȱʡ��������ʾ���ݿ�ṹ��Ϣ----------------------------------
 */
//��ȡ���ݿ�ṹ��Ϣ
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

if(count($db_index)==0) ErrExit("��ǰ����û�п���ʾ�ı�ṹ");

echo "<a name=top></a><div class=boxh>������</div>\n<div class=boxb>\n<table border=0>\n";
asort($db_index);
foreach($db_index as $tablename)
{
    echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� <a href=#$tablename>$tablename</a> </td><td> <i>...$db_comment[$tablename]</i></td></tr>\n";
}
echo "</table>\n</div>\n";

//����DOC������
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
        echo "<div class=boxb>Warrning: <p>δ�ҵ����ݽ����ĵ��ṹ���༭ģʽ��ֹ��</div>";
        $modify = 0;
    }
}

foreach($db_info as $table_name=>$table_info)
{
    ?>
    <a name='<?=$table_name?>'>
    <h3><a href=#top>��</a>���ݱ�<?=$table_name?>�� - <?=$db_comment[$table_name]?><?if($modify==1)echo "<a href={$currenturl}?action=updatecomment&table={$table_name}&comment=".urlencode($db_comment[$table_name]).">&raquo;</a>"?>(������:<?=$db_rows[$table_name]?>)</h3>
    <div class=boxc>
    <?
    showhelp($doc_content[$table_name][_log],$doc_content[$table_name][_log]);
    if($modify==1) //�༭ģʽ
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
        <td bgcolor="#868786" width=200><b><font color=white>�ֶ���</font></b></td>
        <td bgcolor="#868786" width=150><b><font color=white>����</font></b></td>
        <td bgcolor="#868786" width=*><b><font color=white>˵��</font></b></td>
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
                if($modify==1) //�༭ģʽ
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
