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

// GET routes
$app->get('/galleries/:page', function($page) {
    if (IS_AJAX) {
        $db = getConnection();

        $pages = getPage($db, $page);
        $allMedia = getMediaForEntries($db);

        $pagedMedia = array();
        foreach ( $allMedia as $key => $media ) {
            $pagedMedia[$media['entryId']][] = array('id'=>$media['id'], 'path'=>$media['path']);
        }
        foreach( $pagedMedia as $entryId => $media ) {
            for ($i = 0; $i < sizeof($pages); $i++) {
                if ($pages[$i]['id'] == $entryId) {
                    $pages[$i]['media'] = $media;
                    break;
                }
            }
        }
        echo json_encode($pages);
    }
});

$app->get('/admin/getEntryMedia/(:entryId)', function($entryId = null) use($app) {
    $db = getConnection();
    try {
        echo json_encode( getMediaForEntries($db, $entryId) );
    } catch (Exception $e) {
        $app->response->setStatus(500);
        echo $e->getMessage();
    }
});

$app->get('/admin/getMedia/(:id)', function($id = null) use($app) {
    $db = getConnection();
    try {
        echo json_encode( getMedia($db, $id) );
    } catch (Exception $e) {
        $app->response->setStatus(500);
        echo $e->getMessage();
    }
});

// POST routes
$app->post('/admin/galleryEntry', function() use($app) {
    $post = $app->request->post();
    $db = getConnection();
    try {
        $db->transactional(function($db) use($post) {
            echo $db->insert($post['table'], $post['data']);
        });
    } catch (Exception $e) {
        $app->response->setStatus(500);
        echo $e->getMessage();
    }
});

$app->post('/admin/galleryEntry/:id(/:status)', function($id, $status) use($app) {
    $post = $app->request->post();
    $db = getConnection();
    if (isset($status)) {
        try {
            echo $db->update($post['table'], array('publish' => $status), array('id' => $id));
        } catch (Exception $e) {
            $app->response->setStatus(500);
            echo $e->getMessage();
        }
    } else {
        try {
            $db->transactional(function($db) use($post) {
                echo $db->update($post['table'], $post['data'], array('id' => $post['data']['id']));
            });
        } catch (Exception $e) {
            $app->response->setStatus(500);
            echo $e->getMessage();
        }
    }
});

$app->post('/admin/media', function() use($app) {

    require('upload.php');
    $post = $app->request->post();
    $pathresolver = new FileUpload\PathResolver\Booya($_SERVER['DOCUMENT_ROOT'] . '/img/uploads/' . $post['entryID']);
    $filesystem = new FileUpload\FileSystem\Simple();
    $filenamegenerator = new FileUpload\FileNameGenerator\Booya();
    $fileupload = new FileUpload\Booya($_FILES['files'], $_SERVER);

    $fileupload->setPathResolver($pathresolver);
    $fileupload->setFileSystem($filesystem);
    $fileupload->setFileNameGenerator($filenamegenerator);

    list($files, $headers) = $fileupload->processAll();

    if ($fileupload->getError() > 0) {
        $app->response->setStatus(500);
        echo $fileupload->getErrorMessage();
    } else {
        try {
            $db = getConnection();
            $path = str_replace($_SERVER['DOCUMENT_ROOT'],'',$files[0]->path);

            $db->transactional(function($db) use($post, $files, $path) {
                $db->insert('media', array('type' => $files[0]->type, 'path' => $path, 'entryId' => $post['entryID']));
                $files[0]->id = $db->lastInsertId();
            });

            unset($files[0]->path);

            foreach($headers as $header => $value) {
                header($header . ': ' . $value);
            }
            echo json_encode(array('files' => $files ));

        } catch (Exception $e) {
            unlink($files[0]->path);
            //unset($files[0]->error_code);
            $app->response->setStatus(500);
            echo $e->getMessage();
        }
    }
});

// DELETE routes
$app->delete('/admin/galleryEntry/:id', function($id) {
    $db = getConnection();
    try {
        $db->delete('entries', array('id' => $id));
    } catch (Exception $e) {
        $app->response->setStatus(500);
        echo $e->getMessage();
    }
});

$app->delete('/admin/media/:id', function($id) use($app) {
    $db = getConnection();
    $post = $app->request->post();

    try {
        $db->delete('media', array('id' => $id));
        unlink($_SERVER['DOCUMENT_ROOT'] . $post['path']);
    } catch (Exception $e) {
        $app->response->setStatus(500);
        echo $e->getMessage();
    }
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
    $query = "SELECT id, title, description FROM entries WHERE type=?";
    return $db->fetchAll($query, array($page));
}

function getMedia($db, $id){
    $query = "SELECT entryId, id, path FROM media WHERE id=?";
    return $db->fetchAll($query, array($id));
}

function getMediaForEntries($db, $entryId = null){
    $query = "SELECT entryId, id, path FROM media";
    if ($entryId !== null) {
        $query .= " WHERE entryId=?";
        return $db->fetchAll($query, array($entryId));
    } else
        return $db->fetchAll($query);
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
