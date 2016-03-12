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

$con = mysql_connect("$server:$port", $user, $pass);
if (!$con)
    die('[PixON] FAIL: Connect failed: '.mysql_error()."\n");
if (!mysql_select_db($database, $con))
    die('[PixON] FAIL: Connect failed: '.mysql_error()."\n");

$query = sprintf('SELECT `key`, value, votes FROM pixon_data
    WHERE hash = "%s"',
    mysql_real_escape_string($hash));
if (! ($result = mysql_query($query)))
    die('[PixON] FAIL: MySQL error: '.mysql_error()."\n");
echo "[PixON] DATA.\n";
while ($row = mysql_fetch_array($result))
{
    echo "[DATA] NEXT.\n";
    echo "[DATA] key: $row[key]\n";
    echo "[DATA] value: $row[value]\n";
    echo "[DATA] votes: $row[votes]\n";
}

echo "[DATA] DONE.\n";

mysql_close($con);
?>
