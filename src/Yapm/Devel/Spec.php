<?php

namespace Yapm\Devel;

use Yapm\Lib\Conf as Conf;

class Spec
{

    private $nameSpec = false;
    private $dataSpec = false;
    private $release = null;
    private $version = null;
    private $changelog = array();

    public function __construct($operands = array())
    {
        if (empty($operands) || !is_array($operands)) {
            throw new \UnexpectedValueException(
                "inform list with the name of the spec file"
            );
        }

        $spec = array_shift($operands);

        if (empty($spec) || !file_exists($spec)) {
            throw new \UnexpectedValueException(
                ".spec file not found"
            );
        }

        $this->nameSpec = $spec;
    }

    public function updateCommand($opts = array())
    {
        self::parse();

        if (isset($opts["c"]) && !empty($opts["c"])) {
            $changelog = trim($opts["c"]);
        } else {
            $changelog = self::specGit();
        }

        $newspec = join("\n", $this->dataSpec);
        $newspec .= sprintf(
               "\n* %s %s <%s> %s-%s",
               date("D M d Y"),
               Conf::packager("name"),
               Conf::packager("mail"),
               $this->version,
               $this->release
        );
        $newspec .= "\n";
        $newspec .= $changelog;
        $newspec .= "\n\n";
        $newspec .= join("\n", $this->changelog);

        file_put_contents($this->nameSpec, $newspec);

        printf(
            "\n [ done ] spec created:  version %s-%s",
            $this->version,
            $this->release
        );

        printf(" %'.9s ", " ok\n");
        print "\n ChangeLog added: \n\n";
        print $changelog;
        print "\n\n";
    }

    private function specGit()
    {
        $allCommits = self::getAllCommits($this->changelog);
        $listRevision = array();
        $lastChange = trim($this->changelog[0]);
        $changeLog = "";

        $date = (explode(" ", trim(str_replace("*", "", $lastChange))));
        $after = date(
            "c",
            strtotime($date[2] . " " . $date[1]. " ". $date[3])
        );

        $command = sprintf('git log --oneline --since="{%s}" --no-merges', $after);
        exec($command, $logs);

        foreach ($logs as $log) {

            if (empty($log)
                || preg_match('/New Package/i', $log)
                || preg_match('/New Release/i', $log)
            ) {
                continue;
            }

            preg_match('/^[A-z0-9]{7}/', $log, $commit);
            $commit = $commit[0];
            $log = trim(str_replace($commit, "", $log));

            $listTickets = array();
            if (preg_match_all('/\#\d*/i', $log, $matches)) {
                foreach($matches[0] as $ticket) {
                    array_push($listTickets, sprintf(" (%s)", $ticket));
                    $log = trim(str_replace($ticket, "", $log));
                }
            }

            $log .= join(",", $listTickets);
            if ($commit && !in_array($commit, $allCommits)) {
                array_push($listRevision, $commit);
            }

            $changeLog .= trim($log) . "\n";
        }

        if (trim($changeLog) == "") {
            $changeLog = "- rebuilt \n";
        }

        if (!empty($listRevision)) {
            sort($listRevision);
            $changeLog .= "- Revision: " . join($listRevision, ", ");
        }

        return $changeLog;
    }

    private function getAllCommits($changelog = array())
    {
        if (!is_array($changelog)) {
            $changelog = array($changelog);
        }

        $listAllRevision = array();
        foreach($changelog  as $lineChange) {
            preg_match_all('/r[A-z0-9]{2,9}/', $lineChange, $mat);
            foreach($mat[0] as $rev) {
                array_push($listAllRevision, substr($rev, 1));
            }
        }
        return $listAllRevision;
    }

    private function parse($data = array())
    {
        $handle = file_get_contents($this->nameSpec);
        $spec = (explode("\n", $handle));
        $changeLog = false;

        foreach ($spec as $i => $line) {

            if (!$changeLog) {
                $this->dataSpec[$i] = $line;
            }

            $lineHeader = explode(":", $line, 2);
            if (count($lineHeader) == 2) {
                if (trim($lineHeader[0]) == "Release") {
                    list($minor, $revison) = explode(".", $lineHeader[1]);
                    $this->release = trim($minor . "." . floatval($revison + 1));
                    $this->dataSpec[$i] = "Release: ". $this->release;

                } else if (trim($lineHeader[0]) == "Version") {
                    $this->version = trim($lineHeader[1]);
                }
            }

            if ($line == "%changelog" || $changeLog) {
                $changeLog[] = $line;
            }
        }

        array_shift($changeLog);
        $this->changelog = $changeLog;
        return true;
    }
}
