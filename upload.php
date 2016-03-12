<?php
require 'login.php';

echo "[version] 0.1.0\n";
$hash = strtoupper($_GET['hash']);
if (strlen($hash) != 32)
    die("[PixON] FAIL: Wrong hash: $hash\n");
for ($i = 0; $i < 32; $i++)
{
    if ($hash[$i]>='0' and $hash[$i]<='9')
        continue;
    if ($hash[$i]>='A' and $hash[$i]<='F')
        continue;
    die("[PixON] FAIL: Wrong hash: $hash\n");
}
$key = $_GET['key'];
if ($key == NULL)
    die("[PixON] FAIL: Missing argument 'key'\n");
$value = $_GET['value'];
if ($value == NULL)
    die("[PixON] FAIL: Missing argument 'value'\n");

$con = mysql_connect("$server:$port", $user, $pass);
if (!$con)
    die('[PixON] FAIL: Connect failed: '.mysql_error()."\n");
if (!mysql_select_db($database, $con))
    die('[PixON] FAIL: Connect failed: '.mysql_error()."\n");

# FIXME: 这里不检查用户传来的key和value是否真的为hex，是一个对其他用户有害的漏洞
$query = sprintf('INSERT INTO pixon_data (hash, `key`, value)
    VALUES ("%s", "%s", "%s")',
    mysql_real_escape_string($hash),
    mysql_real_escape_string($key),
    mysql_real_escape_string($value));
if (! mysql_query($query))
    die('[PixON] FAIL: MySQL error: '.mysql_error()."\n");
echo "[PixON] DONE.\n";

mysql_close($con);
?>
