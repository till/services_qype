## This is simple and straight forward.

Get your API key and secret from [Qype][dev].

    <?php
    require_once '/path/to/Services/Qype.php';
    require_once '/path/to/external/OAuth.php';

    $qype = new Services_Qype('api key', 'api secret');
    var_dump($qype->places()->getPlace(some_place_id));

    $qype->places()->setGeoLocation(XX, XX);
    var_dump($qype->places()->getPlaces(true));

## TODO

 * Create package.xml to install this
 * Clean up OAuth implementation
 * Services_Qype_Categories
 * Services_Qype_Tags
 * Services_Qype_Users

[dev]: http://www.qype.com/developers/api



