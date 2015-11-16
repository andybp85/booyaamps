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
   $app->render('pages/home.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('home');

$app->get('/about', function() use($app, $smarty) {
   $app->render('pages/about.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('about');

$app->get('/news', function() use($app, $smarty) {
   $app->render('pages/news.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('news');

$app->get('/amps', function() use($app, $smarty) {
   $app->render('pages/amps.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('amps');

$app->get('/mods', function() use($app, $smarty) {
   $app->render('pages/mods.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('mods');

$app->get('/contact', function() use($app, $smarty) {
   $app->render('pages/contact.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('contact');


// Admin Routes
// -----------------------------------------------------------------------------

$app->get('/admin', function() use($app, $smarty) {
   $app->render('admin/editor.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('admin');

$app->get('/admin/editor', function() use($app, $smarty) {
   $app->render('admin/editor.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('editor');

$app->get('/admin/amps', function() use($app, $smarty) {
   $app->render('admin/amps.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('ampsAdmin');

$app->get('/admin/mods', function() use($app, $smarty) {
   $app->render('admin/editor.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('modsAdmin');

$app->get('/admin/media', function() use($app, $smarty) {
   $app->render('admin/media.tpl', array('ParentTemplate' => $smarty->compile_id . '.tpl'));
})->name('mediaAdmin');


// Data Routes
// -----------------------------------------------------------------------------

$app->get('/galleries/:page', function($page) {
    if (IS_AJAX) {
        $db = getConnection();
        echo json_encode(getPage($db, $page));
    }
});

$app->post('/admin/galleryEntry', function() use($app) {
    $post = $app->request->post();
    $db = getConnection();
    try {
        $db->transactional(function($db) use($post) {
            echo $db->insert($post['table'], $post['data']);
        });
    } catch (Exception $e) {
        echo $e->getMessage();
    }
});

$app->post('/admin/galleryEntry/:id(/:status)', function($id, $status) use($app) {
    $post = $app->request->post();
    $db = getConnection();
    if (isset($status)) {
        try {
            echo $db->update($post['table'], array('published' => $post['data']['status']), array('id' => $id));
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {
        try {
            $db->transactional(function($db) use($post) {
                echo $db->update($post['table'], $post['data'], array('id' => $post['data']['id']));
            });
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
});

$app->post('/admin/media', function() use($app) {

    require('upload.php');
    $pathresolver = new FileUpload\PathResolver\Booya($_SERVER['DOCUMENT_ROOT'] . '/img/uploads');
    $filesystem = new FileUpload\FileSystem\Simple();
    $filenamegenerator = new FileUpload\FileNameGenerator\Booya();
    $fileupload = new FileUpload\FileUpload($_FILES['files'], $_SERVER);

    $fileupload->setPathResolver($pathresolver);
    $fileupload->setFileSystem($filesystem);
    $fileupload->setFileNameGenerator($filenamegenerator);

    list($files, $headers) = $fileupload->processAll();

    foreach($headers as $header => $value) {
        header($header . ': ' . $value);
    }

    if (array_key_exists(0, $files)) {
        try {

            $db = getConnection();
            $post = $app->request->post();
            $path = str_replace($_SERVER['DOCUMENT_ROOT'],'',$files[0]->path);

            $db->transactional(function($db) use($post, $files, $path) {
                $db->insert('media', array('type' => $files[0]->type, 'path' => $path, 'entryId' => $post['entryID']));
            });

            unset($files[0]->path);

        } catch (Exception $e) {
            unlink($files[0]->path);
            unset($files[0]->error_code);
            $files[0]->error = $e->getMessage();
        }
    }

    echo json_encode(array('files' => $files ));

});

$app->delete('/admin/galleryEntry/:id', function($id) {
    $db = getConnection();
    $db->delete('entries', array('id' => $id));
});

$app->delete('/admin/media/:id', function($id) {
    $db = getConnection();
    $db->delete('media', array('id' => $id));
});


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
    
$query = <<<QUERY
SELECT
    entries.id,
    entries.title,
    entries.description,
    group_concat(media.path) as paths
FROM
    entries
LEFT JOIN media
    ON entries.id=media.entryId
WHERE
    entries.type = ?
GROUP BY entries.id
QUERY;

    return $db->fetchAll($query, array($page));
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
