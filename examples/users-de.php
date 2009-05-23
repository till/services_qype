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

try {

    $qype = new Services_Qype($appKey, $appSecret);
    $qype->setUiLanguage('de_DE'); // get the user profile in German
    var_dump($qype->users()->getUser('till'));

} catch (Exception $e) {
    var_dump($e);
}
