<?php
class Config
{
    static $confArray;
		public static $home = 'http://notes.com'; 
    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }

}

// db
Config::write('db.host', 'localhost');
Config::write('db.basename', 'localhost');
Config::write('db.user', 'root');
Config::write('db.password', 'tgTRr9C6');
