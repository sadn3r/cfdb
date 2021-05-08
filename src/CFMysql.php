<?php
namespace CF\Db;
use mysqli;

class CFMysql extends mysqli {
	public function exec(string $sql, array $data = null) {
		$new_statement = preg_replace_callback('/([i|s])\:([a-z\_]+)/ui', function($matches) use ($data) {

			switch ($matches[1]) {
				case 'i':
					return (int)$data[$matches[2]];
				break;
				case 's':
					return $this->escape_string($data[$matches[2]]);
				break;
			}

		}, $sql);

		return $this->query($new_statement);
	}

	public function insert(string $table, array $data = []): int
	{
		$_row_n	= [];
		$_row_v	= [];

		foreach ($data as $row => $value) {
			$_row_n[]	= '`'.$row.'`';
			$_row_v[]	= "'".$this->escape_string($value)."'";
		}

		$sql = "
		INSERT INTO `$table`(".join(',', $_row_n).") VALUES(
			".join(',', $_row_v)."
		)";

		$r = $this->query($sql);

		return $this->insert_id;
	}
}