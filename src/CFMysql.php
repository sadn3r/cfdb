<?php

namespace CF\Db;

use mysqli;
use mysqli_result;

class CFMysql extends mysqli
{
    public function exec(string $sql, array $data = null): mysqli_result|bool
    {
        return $this->query(preg_replace_callback(pattern: '/(?P<type>[i|s]):(?P<value>[a-z0-9_]+)/ui', callback: fn($matches) => match ($matches['type']) {
            'i' => (int)$data[$matches['value']],
            's' => $this->escape_string($data[$matches['value']]),
        }, subject: $sql));
    }

    public function insert(string $table, array $data = []): int
    {
        $_row_n = [];
        $_row_v = [];

        foreach ($data as $row => $value) {
            $_row_n[] = '`' . $row . '`';
            $_row_v[] = "'" . $this->escape_string($value) . "'";
        }

        $sql = "
		INSERT INTO `$table`(" . join(',', $_row_n) . ") VALUES(
			" . join(',', $_row_v) . "
		)";

        $this->query($sql);

        return $this->insert_id;
    }
}