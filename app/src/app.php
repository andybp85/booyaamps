<?php

// Smarty setup
// -----------------------------------------------------------------------------

// Setup our renderer
$app->view(new \Slim\Views\Smarty());

$view = $app->view();
$view->parserCompileDirectory = dirname(__FILE__) . '/templates/compiled';
$view->parserCacheDirectory = dirname(__FILE__) . '/templates/cache';
$view->parserExtensions = array(
    dirname(__FILE__) . '/vendor/slim/views/SmartyPlugins',
);

// figure out which main template to use
define('IS_AJAX', (bool) (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                        && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));

$baseTemplate = (strpos($_SERVER["REQUEST_URI"], 'admin') !== false ? 'admin-base' : 'base');

$smarty = $view->getInstance();
$smarty->setCompileId((bool) IS_AJAX ? 'ajaxResponse' : $baseTemplate);

// Pages Routes
// -----------------------------------------------------------------------------

$app->get('/', function() use($app, $smarty) {
   //$app->render('pages/home.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
   $app->render('admin/amps.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('home');

$app->get('/about', function() use($app, $smarty) {
   $app->render('pages/about.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('about');

/*$app->get('/news', function() use($app, $smarty) {*/
   //$app->render('pages/news.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
//})->name('news');

//$app->get('/amps', function() use($app, $smarty) {
   //$app->render('pages/amps.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
//})->name('amps');

//$app->get('/mods', function() use($app, $smarty) {
   //$app->render('pages/mods.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
//})->name('mods');

//$app->get('/contact', function() use($app, $smarty) {
   //$app->render('pages/contact.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
/*})->name('contact');*/


// Admin Routes
// -----------------------------------------------------------------------------

/*$app->get('/admin', function() use($app, $smarty) {*/
   //$app->render('admin/editor.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
//})->name('admin');

//$app->get('/admin/editor', function() use($app, $smarty) {
   //$app->render('admin/editor.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
//})->name('editor');

//$app->get('/admin/amps', function() use($app, $smarty) {
   //$app->render('admin/amps.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
//})->name('ampsAdmin');

//$app->get('/admin/mods', function() use($app, $smarty) {
   //$app->render('admin/editor.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
/*})->name('modsAdmin');*/


// Data Routes
// -----------------------------------------------------------------------------

/*$app->get('/galleries/:page', function($page) {*/
    //if (IS_AJAX) {
        //$db = getConnection();
        //echo json_encode(getPage($db, $page));
    //}
//})->name('galleries');

//$app->post('/admin/galleryEntry', function() use($app) {
    //$response = $app->response();
    //$response->header('Access-Control-Allow-Origin', '*');

    //$post = $app->request->post();
    //$db = getConnection();
    //try {
        //$db->transactional(function() use($db, $post) {
            //$db->insert($post('table'), $post('data'));
        //});
    //} catch  (Exception $e) {
        //echo $e->getMessage();
    //}
//});

//$app->delete('/admin/galleryEntry/:id', function($id) {
    //$db = getConnection();
    //$db->delete('entries', array('id' => $id));
//});

//$app->delete('/admin/image/:id', function($id) {
    //$db = getConnection();
    //$db->delete('media', array('id' => $id));
//});


// Database Access Layer
// -----------------------------------------------------------------------------

function getConnection() {

    $dotenv = new Dotenv\Dotenv(__dir__);
    $dotenv->load();

    $connectionParams = array(
        'dbname'   => $_ENV['BOOYA_DB'],
        'user'     => $_ENV['BOOYA_DB_USER'],
        'password' => $_ENV['BOOYA_DB_PASSWD'],
        'host'     => $_ENV['BOOYA_DB_HOST'],
        'driver'   => 'pdo_mysql'
    );

    $config = new \Doctrine\DBAL\Configuration();

    return \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

}

function getPage($db, $page){
    return $db->fetchAll('SELECT * FROM entries LEFT JOIN media ON entries.id=media.entryId WHERE entries.type = ?', array($page));
}

/*function addEntry($db, $type, $title, $desc) {*/
    //return $db->insert('entries', array('type' => $type, 'title' => $title, 'description' => $desc));
//}

//function addMedia($db, $type, $path, $entryId) {
    //return $db->insert('entries', array('type' => $type, 'path' => $path, 'entryId' => $entryId));
//}

//function delete($db, $table, $id) {
    //
/*}*/
