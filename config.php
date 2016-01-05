<?php

/**
 * Class Config class is responsible for
 */
class Config
{
    private static $instance;
    private $iniArray;

    public static function GetValue($config, $group = "general")
    {
        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance->iniArray[$group][$config];
    }

    public static function GetGroup($group = "general")
    {
        if (is_null(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance->iniArray[$group];
    }

    private function __construct()
    {
        $this->iniArray = parse_ini_file("testsettings.ini", true);
    }
}