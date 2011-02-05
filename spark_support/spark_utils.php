<?php

class SparkUtils {

    static function remove_full_directory($dir, $vocally = false) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != '.' && $object != '..') { 
                    if (filetype($dir . '/' . $object) == "dir") self::remove_full_directory($dir . '/' . $object, $vocally); 
                    else {
                        if ($vocally) self::line("Removing $dir/$object");
                        unlink($dir . '/' . $object); 
                    }
                } 
            } 
            reset($objects); 
            return rmdir($dir); 
        } 
    } 

    static function line($msg) {
        echo "[SPARK] $msg\n";
    }

}
