<?php

// backward compatibility
if ( !function_exists('sys_get_temp_dir'))
{
    function sys_get_temp_dir()
    {
        if ($temp = getenv('TMP')) return $temp;
        if ($temp = getenv('TEMP')) return $temp;
        if ($temp = getenv('TMPDIR')) return $temp;
        $temp = tempnam(__FILE__, '');
        if (file_exists($temp))
        {
          unlink($temp);
          return dirname($temp);
        }
        return '/tmp'; // the best we can do
    }
}

class Spark_utils {

    private static $buffer = false;
    private static $lines = array();

    static function get_lines()
    {
        return self::$lines;
    }

    static function buffer()
    {
        self::$buffer = true;
    }

    static function remove_full_directory($dir, $vocally = false)
    {
        if (is_dir($dir))
        {
            $objects = scandir($dir); 
            foreach ($objects as $object)
            {
                if ($object != '.' && $object != '..')
                {
                    if (filetype($dir . '/' . $object) == "dir")
                    {
                        self::remove_full_directory($dir . '/' . $object, $vocally);
                    }
                    else
                    {
                        if ($vocally) self::notice("Removing $dir/$object");
                        unlink($dir . '/' . $object); 
                    }
                } 
            } 
            reset($objects); 
            return rmdir($dir); 
        } 
    } 

    static function notice($msg)
    {
        self::line($msg, 'SPARK');
    }

    static function error($msg)
    {
        self::line($msg, 'ERROR');
    }

    static function warning($msg)
    {
        self::line($msg, 'WARNING');
    }

    static function line($msg = '', $s = null)
    {
        foreach(explode("\n", $msg) as $line)
        {
            if (self::$buffer)
            {
                self::$lines[] = $line;
            }
            else
            {
                echo !$s ? "$line\n" : "[ $s ]  $line\n"; 
            }
        }
    }

}
