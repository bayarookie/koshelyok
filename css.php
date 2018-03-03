<?php
$id = $_COOKIE['CSSID'] ?? 1;
if (isset($_GET['CSSID'])) $id = $_GET['CSSID'];
setcookie('CSSID', $id, time()+60*60*24*365);
header('Content-Type: text/css; charset=utf-8');
if (file_exists('style'.$id.'.css'))
	require 'style'.$id.'.css';
else
	require 'style1.css';
?>
