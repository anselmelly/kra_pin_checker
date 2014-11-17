<?php

/**
 * Description of app
 *
 * @author Ansel Melly <ansel@anselmelly.com>
 * @date Nov 17, 2014
 * @link http://www.anselmelly.com
 */
?>
<?php

include './libs/Slim/Slim.php';
include './libs/Zebra/Zebra_cURL.php';
include './libs/KRA/PinChecker.php';

Slim\Slim::registerAutoloader();

$app = new Slim\Slim();

$app->map('/', function() use ($app) {
    $humanPin = $app->request->get('pin');
    if (strlen($humanPin) < 11) {
        echo json_encode(array('result' => 'Eleven Characters Required'));
        exit();
    }
    $kra = new KRA\PinChecker($humanPin);
    echo json_encode(array('result' => $kra->kraResult));
})->via('GET');

$app->run();
