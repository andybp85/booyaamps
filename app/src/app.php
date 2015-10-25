<?php

// Smarty setup
// -----------------------------------------------------------------------------

define('IS_AJAX', (bool) (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
                        && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
                        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')); 

// Setup our renderer and add some global variables
$app->view(new \Slim\Views\Smarty());

$view = $app->view();
$view->parserCompileDirectory = dirname(__FILE__) . '/templates/compiled';
$view->parserCacheDirectory = dirname(__FILE__) . '/templates/cache';

$smarty = $view->getInstance();
$smarty->setCompileId((bool) IS_AJAX ? 'ajaxResponse' : 'base');
//$smarty->assign('ParentTemplate', $smarty->compile_id . '.tpl'); 
//$smarty->display('Login.tpl', null, $smarty->compile_id);

// Routes
// -----------------------------------------------------------------------------

$app->get('/', function () use($app, $smarty) {
   $app->render('home.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('home');

$app->get('/about', function () use($app, $smarty) {
   $app->render('about.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('about');

$app->get('/news', function () use($app, $smarty) {
   $app->render('news.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('news');

$app->get('/amps', function () use($app, $smarty) {
   $app->render('amps.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('amps');

$app->get('/mods', function () use($app, $smarty) {
   $app->render('mods.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('mods');

$app->get('/contact', function () use($app, $smarty) {
   $app->render('contact.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('contact');
