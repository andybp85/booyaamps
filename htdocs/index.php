<?php
/*****************************************************
 * booyahamps.com
 * @author Andrew Stanish <andybp85@gmail.com>
 * @
 ****************************************************/
date_default_timezone_set('UTC');


require __DIR__ . '/../vendor/autoload.php';

$composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'));

$app = new \Slim\Slim(array(
      //'version'        => $composer->version,
      'debug'          => true,
      //'mode'           => 'production',
      'templates.path' => __DIR__ . '/../src/templates'
    )
);

require_once __DIR__ . '/../src/app.php';

$app->run();
