<?php 
namespace CF\Db;
error_reporting(E_ALL);
require 'src/CFMysql.php';
require 'src/Db.php';

$db = Db::instance(new CFMysql);
var_dump($db);