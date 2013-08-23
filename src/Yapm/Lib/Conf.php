<?php

namespace Yapm\Lib;

class Conf
{
    private static $data = array();

    public function __construct($file = "")
    {
        if (!file_exists($file)) {
            throw new \Exception("configuration file not found : " . $file);
        }

        if (@!$ini = parse_ini_file($file, true, INI_SCANNER_RAW)) {
            throw new \UnexpectedValueException(
                "configuration parse error : " . $file
            );
        }

        foreach ($ini as $instance => $value) {
            self::$data[$instance] = $value;
        }
    }

    public static function __callStatic($instance, $arguments)
    {
        if (!isset(self::$data[$instance])) {
            throw new \UnexpectedValueException("$instance not found");
        }

        $filter = array_shift($arguments);

        if ($filter) {
            if (!isset(self::$data[$instance][$filter])) {
                throw new \UnexpectedValueException(
                    "$filter not found in $instance"
                );
            }

            return self::$data[$instance][$filter];
        }

        return self::$data[$instance];
    }
}