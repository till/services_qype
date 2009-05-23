<?php
require_once dirname(__FILE__) . '/conf.php';
require_once dirname(__FILE__) . '/../Services/Qype.php';
require_once dirname(__FILE__) . '/../external/OAuth.php';

$lat  = 52.50743;
$long = 13.467888;

try {
    $qype = new Services_Qype($appKey, $appSecret);
    $qype->places()->setGeoLocation($long, $lat);
    $qype->places()->getPlaces(true);
} catch (Exception $e) {
    var_dump($e);
}
