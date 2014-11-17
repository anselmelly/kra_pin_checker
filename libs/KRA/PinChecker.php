<?php

/**
 * Description of PinChecker
 *
 * @author Ansel Melly <ansel@anselmelly.com>
 * @date Nov 17, 2014
 * @link http://www.anselmelly.com
 */

namespace KRA;

use Zebra\Zebra_cURL as curl;

class PinChecker {

    //put your code here
    private $kraUrl = 'http://revenue.go.ke/checker/results.php';
    public $humanPin = 'A004554632S'; // sample of kenya revenue authority pin
    public $kraResult;

    public function __construct($humanPin) {
        $this->humanPin = $humanPin;
        return $this->sendRequest();
    }

    public function sendRequest() {
        $zebraCurl = new curl();
        $zebraCurl->post($this->kraUrl, array('pin' => $this->humanPin), array($this, 'kraResult'));
    }

    public function kraResult($result) {
        $p = html_entity_decode($result->body);
        if (preg_match("/\bsorry\b/i", $p) || preg_match("/\binvalid\b/i", $p)) {
            $this->kraResult = "Invalid PIN NO";
        } elseif (preg_match("/\bfail\b/i", $p) || preg_match("/\bfailed\b/i", $p)) {
            $this->kraResult = "ERROR CONNECTING TO KRA";
        } elseif (preg_match("/\bname\b/i", $p)) {
            $kra_array = array('Results', 'Taxpayer', 'PIN', 'KRA', 'Information', '\\\n');
            preg_match("'<table width=\"100%\">(.*?)</table>'si", $p, $match);
            $z = trim(str_replace($kra_array, "", preg_replace('/<(.|\n)*?>/', '', $match[0])));
            $this->kraResult = preg_replace('/\s\s+/', ' ', strip_tags($z));
        } else {
            $this->kraResult = "ERROR CONNECTING TO KRA";
        }
    }

}
