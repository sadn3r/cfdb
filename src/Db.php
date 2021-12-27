<?php

namespace CF\Db;

use Ramsey\Uuid\Codec\TimestampFirstCombCodec;
use Ramsey\Uuid\Generator\CombGenerator;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;

class Db
{

    private static ?Db $_instance = null;
    private UuidFactory $uuidFactory;

    private function __construct(private CFMysql $db)
    {

        $this->uuidFactory = new UuidFactory();
        $codec = new TimestampFirstCombCodec($this->uuidFactory->getUuidBuilder());
        $this->uuidFactory->setCodec($codec);
        $this->uuidFactory->setRandomGenerator(new CombGenerator(
            $this->uuidFactory->getRandomGenerator(),
            $this->uuidFactory->getNumberConverter()
        ));
    }

    public function uuid4(): UuidInterface
    {
        return $this->uuidFactory->uuid4();
    }

    public static function instance(CFMysql $db): Db
    {
        return match (self::$_instance) {
            null => self::$_instance = new self($db),
            default => self::$_instance
        };
    }

    public function __get(string $param)
    {
        return $this::$_instance->db->$param;
    }

    public function __call(string $methodName, array $args)
    {
        return call_user_func_array([$this::$_instance->db, $methodName], $args);
    }

    public static function __callStatic(string $methodName, array $args)
    {
        return call_user_func_array([self::$_instance->db, $methodName], $args);
    }

}
