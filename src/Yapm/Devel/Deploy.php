<?php
namespace Yapm\Devel;

use Yapm\Lib\Conf;
use Yapm\Lib\Interaction;

class Deploy
{
    private $operands = null;

    public function __construct($operands = array())
    {
        if (!is_array($operands)) {
            throw new \UnexpectedValueException(
                "error in the parameter"
            );
        }

        $this->operands = $operands;
    }

    public function uploadCommand($opts = array())
    {
        // TODO :: production is feature

        if (empty($this->operands)) {
            throw new \UnexpectedValueException("inform the package list");
        }

        if (!$filesUpload = self::getFiles($this->operands)) {
            throw new \UnexpectedValueException("file or directory not found");
        }

        self::uploadFile($filesUpload);
    }

    private function getFiles($listRPM = array())
    {
        $listFiles = array();
        foreach($listRPM as $file) {
            if (file_exists($file)) {
                if (is_dir($file)) {
                    $listFiles = glob("$file/*.rpm");
                } else if (is_file($file)) {
                    $listFiles[] = $file;
                }
            }
        }
        return (!empty($listFiles)) ? $listFiles : false;
    }

    private function uploadFile($listFiles = array())
    {

        $i = 0;
        $arrayUpload = array();
        foreach ($listFiles as $file) {
            $arrayUpload["file-" . $i . "-md5"] =  md5_file($file);
            $arrayUpload["file-" . $i] =  "@". $file;
            $i++;
        }

        $url = Conf::repo("domain");

        printf("\n\n%'-80s\n", "|");
        printf("  Fazer Upload para %s: \n", $url);

        $url .= "/upload";
        $arrayUpload["update_repo"] =  "true";

        $user = Interaction::get("usuario");
        $password = Interaction::get("senha");

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curlHandle, CURLOPT_USERPWD, "$user:$password");
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $arrayUpload);
        //curl_setopt($curlHandle, CURLOPT_NOPROGRESS, false);
        curl_setopt($curlHandle, CURLOPT_BUFFERSIZE, 100);

        $responseBody = curl_exec($curlHandle);
        $responseInfo = curl_getinfo($curlHandle);

        $curlErrno = curl_errno($curlHandle);
        $curlError = curl_error($curlHandle);

        curl_close($curlHandle);

        if ($responseInfo["http_code"] == 401) {
            throw new \Exception(
                sprintf(
                    "invalid username or password: (%s) ",
                    $responseInfo["http_code"]
                )
            );
        } else if ($responseInfo["http_code"] != 200) {
            throw new \Exception(
                sprintf(
                    "error accessing the service upload: %s (%s) - Error: %s (%s)",
                    $url,
                    $responseInfo["http_code"],
                    $curlError,
                    $curlErrno
                )
            );
        } else {
            printf("\n\n%'-80s\n", "|");
            printf(" Return System upload \n");
            print $responseBody;
        }
    }
}
