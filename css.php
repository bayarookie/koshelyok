<?php
$id = 1;
if (!empty($_COOKIE['CSSID'])) $id = $_COOKIE['CSSID'];
if (!empty($_GET['CSSID'])) $id = $_GET['CSSID'];
setcookie ('CSSID' , $id, time()+3600 * 24 * 365);
header('Content-Type: text/css; charset=utf-8');
if (file_exists('style'.$id.'.css'))
	require 'style'.$id.'.css';
else
	require 'style1.css';
?>
