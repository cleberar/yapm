<?php

namespace Yapm\Devel;

use Ulrichsg\Getopt;

class Cli
{
    private $options = array(
        array('h', 'help', Getopt::NO_ARGUMENT, 'This help')
    );

    private $operands = array(
        "deploy:upload" => array(
            array(null, 'production', Getopt::NO_ARGUMENT, 'send packages to the server'),
            array('h', 'help', Getopt::NO_ARGUMENT, 'view help for "deploy:upload"')
        ),
        "spec:update" => array(
            array('h', 'help', Getopt::NO_ARGUMENT, 'view help for "spec:update"'),
            array("c", 'comment', Getopt::REQUIRED_ARGUMENT, 'add comment in changelog')
        )
    );

    private function isOptions()
    {
        $getopt = new Getopt($this->options);
        $getopt->parse();

        $operation = array_shift($getopt->getOperands());

        if (empty($operation) || !isset($this->operands[$operation])) {
            foreach($this->operands as $op => $opOptions) {
                print "\n";
                $opt = new Getopt($opOptions);
                $opt->parse();
                $opt->showHelp($op);
            }
            return false;
        }

        return $operation;
    }

    public function run()
    {
        if (false  === ($operation = self::isOptions())) {
            exit(1);
        }

        $getopt = new Getopt($this->operands[$operation]);
        $getopt->parse();

        if (!is_null($getopt->getOption('help'))) {
            $getopt->showHelp();
            exit(0);
        };

        list($method, $action) = explode(":", $operation, 2);

        $class = '\\' . __NAMESPACE__ . '\\' . ucfirst($method);

        $moreOperatos = $getopt->getOperands();
        array_shift($moreOperatos);

        $execClass = new $class($moreOperatos);

        if (isset($action)) {
            $func = $action . "Command";
            $execClass->$func($getopt->getOptions());
        }
    }
}
