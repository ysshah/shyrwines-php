<?php
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die();
}
mysql_select_db("winelist");
session_set_cookie_params(1209600);
session_start();

mysql_set_charset("UTF8");
?>
