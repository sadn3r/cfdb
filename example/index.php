<?php
namespace CF\Db;

include __DIR__.'/../vendor/autoload.php';

$CFMysql = new CFMysql('localhost', 'root', 'kjlderw32jl', 'for_test', 3306);
$Db = Db::instance($CFMysql);

$result = $Db->exec("select * from posts where id = i:id", [
    'id' => 3
]);

var_dump($result->fetch_assoc());