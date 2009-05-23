<?php
/**
 * config file (contains key and secret)
 * @ignore
 */
require_once dirname(__FILE__) . '/conf.php';

/**
 * Services_Qype
 * @ignore
 */
require_once dirname(__FILE__) . '/../Services/Qype.php';

/**
 * include external oauth library
 * @ignore
 */
require_once dirname(__FILE__) . '/../external/OAuth.php';

// set longitude and latitude for query
$near = false;
$lat  = 52.50743;
$long = 13.467888;

try {
    $qype = new Services_Qype($appKey, $appSecret);
    //$qype->places()->setGeoLocation($long, $lat);
    //var_dump($qype->places()->getPlaces($near));

    var_dump($qype->places()->getPlace(50766));

    var_dump($qype->places()->getPlaces($near));

} catch (Exception $e) {
    var_dump($e);
}
