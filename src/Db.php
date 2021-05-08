<?php
namespace CF\Db;
use mysqli;

class Db {

	private static $_instance;
	private $db;

	private function __construct(CFMysql $db) {
		$this->db = $db;
	}

	public static function instance(CFMysql $db) {
		if(is_null(self::$_instance)) {
			self::$_instance = new self($db);
		}

		return self::$_instance;
	}

	public function __call(string $methodName, array $args) {
		return call_user_func_array(array($this::$_instance->db, $methodName), $args);
	}

	
	public static function __callStatic(string $methodName, array $args) {
		return call_user_func_array(array(self::$_instance->db, $methodName), $args);
	}

}