<?php
namespace CF\Db;
use mysqli;

class Db extends mysqli {

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
}