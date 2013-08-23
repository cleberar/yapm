<?php

namespace Yapm\Lib;

class Interaction
{
    static function confirm($text)
    {
        print $text . "\n Is this ok [y/N] ";
        fscanf(STDIN, "%s", $command);
        return in_array(strtolower($command), array("yes", "y", "sim", "s"));
    }

    static function get($text)
    {
        print "- $text: ";
        fscanf(STDIN, "%s", $command);
        return $command;
    }
}
