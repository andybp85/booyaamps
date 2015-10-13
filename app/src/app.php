<?php

// Smarty setup
// -----------------------------------------------------------------------------

// Setup our renderer and add some global variables
$app->view(new \Slim\Views\Smarty());

$view = $app->view();
$view->parserCompileDirectory = dirname(__FILE__) . '/templates/compiled';
$view->parserCacheDirectory = dirname(__FILE__) . '/templates/cache';

// Routes
// -----------------------------------------------------------------------------

$app->get('/', function () use($app) {
   $app->render('base.tpl', array('test' => 'Hello'));
});
