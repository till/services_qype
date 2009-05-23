## This is simple and straight forward.

Get your API key and secret from [Qype][dev].

    <?php
    require_once '/path/to/Services/Qype.php';
    require_once '/path/to/external/OAuth.php';

    $qype = new Services_Qype('api key', 'api secret');
    var_dump($qype->places()->getPlace(some_place_id));

    $qype->places()->setGeoLocation(XX, XX);
    var_dump($qype->places()->getPlaces(true));
    
## More examples?

Check out the examples [directory][ex].

## TODO

 * Create package.xml to install this
 * Clean up OAuth implementation
 * add support for pagination in search results
 * add support for rectangle geo search

[dev]: http://www.qype.com/developers/api
[ex]: http://github.com/till/services_qype/tree/0159eba3e62f83c4e984e0634108b5ab7b3beb94/examples
