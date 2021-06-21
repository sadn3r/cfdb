<?php
namespace CF\Db;
use mysqli;

use Ramsey\Uuid\Codec\TimestampFirstCombCodec;
use Ramsey\Uuid\Generator\CombGenerator;
use Ramsey\Uuid\UuidFactory;

class Db {

	private static $_instance;
	private $db;
	private $uuidFactory;

	private function __construct(CFMysql $db) {
		$this->db = $db;

		$this->uuidFactory = new UuidFactory();
		$codec = new TimestampFirstCombCodec($this->uuidFactory->getUuidBuilder());
		$this->uuidFactory->setCodec($codec);
		$this->uuidFactory->setRandomGenerator(new CombGenerator(
			$this->uuidFactory->getRandomGenerator(),
			$this->uuidFactory->getNumberConverter()
		));
	}

	public function gadads()
	{
		return $this->uuidFactory->uuid4();
	}

	public static function instance(CFMysql $db)
	{
		if(is_null(self::$_instance)) {

			self::$_instance = new self($db);

		}

		return self::$_instance;
	}

	public function __call(string $methodName, array $args)
	{
		return call_user_func_array(array($this::$_instance->db, $methodName), $args);
	}

	
	public static function __callStatic(string $methodName, array $args)
	{
		return call_user_func_array(array(self::$_instance->db, $methodName), $args);
	}

}
