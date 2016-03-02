<?php
/*****************************************************
 * booyahamps.com
 * @author Andrew Stanish <andybp85@gmail.com>
 * @
 ****************************************************/
date_default_timezone_set('UTC');


require __DIR__ . '/../src/vendor/autoload.php';

//$composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'));

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

require_once __DIR__ . '/../src/app.php';

$app->run();
