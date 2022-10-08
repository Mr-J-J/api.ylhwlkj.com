<?php
namespace App\Support;

/**
 * V2 影片数据接口
 */
class MovieApi
{
    
    static $instance = null;
    static $driver = null;
    private function __construct()
    {
        $driverClass = '\\App\\Support\\Api\\MApi';
        self::$driver = $driverClass::getInstance();
    }

    static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __call($name, $arguments)
    {
        return self::$driver->$name(...$arguments);
    }
    
    private function __clone(){}

}