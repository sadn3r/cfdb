$db = Db::instance($cfmysqli);
$result = $db->exec('SELECT now()');
var_dump($db, $result);
var_dump(Db::exec('SELECT now()'));