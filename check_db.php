<?
include("../server/include/config.inc.php");
include("../db_admin/config.db.php");
showhead('check db');

print <<< end_of_print
<h1>Check DB</H1>
<table $table_style>
<tr style='background: #EEEEEE'><td>IP</td><td>Port</td><td>name</td><td>flag</td></tr>
end_of_print;

foreach($cfg['Servers'] as $server){
if(!$server[host])continue;
print <<< end_of_print
<tr>
<td>$server[host]</td>
<td>$server[port]</td>
<td>$server[ext_name]</td>
<td> - </td>
</tr>
end_of_print;
}
echo "</table>";



