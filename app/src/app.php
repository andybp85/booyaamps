<?php
error_reporting(E_ALL & ~E_NOTICE);

// Smarty setup
// -----------------------------------------------------------------------------
$baseTemplate = (strpos($_SERVER["REQUEST_URI"], 'admin') !== false ? 'admin-base' : 'base');

// figure out which main template to use
define('IS_AJAX', (bool) (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                        && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
// Fetch DI Container
$container = $app->getContainer();

// Register Smarty View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Smarty(dirname(__FILE__) . '/templates', [
        'cacheDir' => dirname(__FILE__) . '/templates/cache',
        'compileDir' => dirname(__FILE__) . '/templates/compiled',
        'pluginsDir' => [dirname(__FILE__) . '/vendor/smarty/smarty/libs/plugins'],
    ]);

    // Add Slim specific plugins
    $view->addSlimPlugins($c['router'], $c['request']->getUri());
    $view->parserExtensions = array(
        dirname(__FILE__) . '/vendor/slim/views/SmartyPlugins',
    );

    return $view;
};

$smarty = $container['view']->getSmarty();
$smarty->compile_id = ((bool) IS_AJAX ? 'ajaxResponse' : $baseTemplate);

// Auth
// -----------------------------------------------------------------------------
$dotenv = new Dotenv\Dotenv(__dir__);
$dotenv->load();

//$pdo = new \PDO("sqlite:/tmp/users.sqlite");

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => "/admin",
    "secure" => false,
    "users" => [
        "root" => "t00r"
    ]
]));


// Page Routes
// -----------------------------------------------------------------------------
$app->get('/',function ($request, $response, $args) use($smarty) {
    return $this->view->render($response, 'pages/home.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl',
        'page' => 'home'
    ]);
})->setName('home');

$app->get('/about', function($request, $response, $args) use($smarty) {
    return $this->view->render($response, 'pages/about.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl',
        'page' => 'about'
    ]);
})->setName('about');

$app->get('/news', function($request, $response, $args) use($smarty) {
    return $this->view->render($response, 'pages/news.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl',
        'page' => 'news'
    ]);
})->setName('news');

$app->get('/amps', function($request, $response, $args) use($smarty) {
    return $this->view->render($response, 'pages/amps.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl',
        'page' => 'amps'
    ]);
})->setName('amps');

$app->get('/mods', function($request, $response, $args) use($smarty) {
    return $this->view->render($response, 'pages/mods.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl',
        'page' => 'mods'
    ]);
})->setName('mods');

$app->get('/contact', function($request, $response, $args) use($smarty) {
    return $this->view->render($response, 'pages/contact.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl',
        'page' => 'contact'
    ]);
})->setName('contact');



// Admin Routes
// -----------------------------------------------------------------------------
$app->get('/admin', function($request, $response, $args) use($smarty) {
    return $response->withRedirect('/admin/editor');
})->setName('admin');;

$app->get('/admin/editor', function($request, $response, $args) use($smarty) {
    try {
    return $this->view->render($response, 'admin/editor.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl'
    ]);
     } catch (Exception $e) {
        error_status( $e->getMessage() );
    }
})->setName('editor');

$app->get('/admin/amps', function($request, $response, $args) use($smarty) {
    return $this->view->render($response, 'admin/entries.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl',
        'page' => 'amp'
    ]);
})->setName('ampsAdmin');

$app->get('/admin/mods', function($request, $response, $args) use($smarty) {
    return $this->view->render($response, 'admin/entries.tpl', [ 
        'ParentTemplate' => $smarty->compile_id . '.tpl',
        'page' => 'mod'
    ]);
})->setName('modsAdmin');


// Data Routes
// -----------------------------------------------------------------------------

// GET routes
$app->get('/galleries/{page}', function($request, $response, $args) {
    if (IS_AJAX) {
        $db = getConnection();

        $pages = getPage($db, $args['page']);
        $allMedia = getMediaForEntries($db);

        $pagedMedia = array();
        foreach ( $allMedia as $key => $media ) {
            $pagedMedia[$media['entryId']][] = [
                                                'id'        => $media['id'],
                                                'path'      => $media['path'],
                                                'thumbPath' => $media['thumbPath'],
                                                'type'      => $media['type']
                                            ];
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

$app->get('/admin/entryMedia/{entryId}', function($request, $response, $args) {
    $db = getConnection();
    try {
        echo json_encode( getMediaForEntries($db, $args['entryId']) );
    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});

$app->get('/admin/media/{id}', function($request, $response, $args) {
    $db = getConnection();
    try {
        echo json_encode( getMedia($db, $args['id']) );
    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});

$app->get('/admin/files', function($request, $response, $args) {
    try {
        $file = $request->getQueryParams()['file'];

        if (file_exists($file) && is_file($file)) {

            $dispositionResponse = $response->withHeader('Content-disposition', 'attachment; filename=' . basename($file) );
            $typeResponse = $dispositionResponse->withHeader('Content-type', filemime($file));
            $typeResponse->getBody()->write(file_get_contents(getcwd() . '/' . $file));
            return $typeResponse;

        } elseif (file_exists($file) && is_dir($file)) {
            $typeResponse = $response->withHeader('Content-type', 'text/x-dir');
            $files =  scandir(getcwd() . '/' . $file);
            array_shift($files);
            $typeResponse->getBody()->write($file . '/:' . implode("|",$files));
            return $typeResponse;

        } else {
            error_status($response, 'Error: '.$file.' does not exist.');
        }
    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});

// POST routes
$app->post('/admin/galleryEntry', function($request, $response, $args) {
    $post = $request->post();
    $db = getConnection();
    try {
        $db->transactional(function($db) use($post) {
            echo $db->insert($post['table'], $post['data']);
        });
    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});

$app->post('/admin/galleryEntry/{id}/{status}', function($request, $response, $args) {
    $post = $request->post();
    $db = getConnection();
    if (array_key_exists('status', $args)) {
        try {
            echo $db->update($post['table'], array('publish' => $args['status']), array('id' => $id));
        } catch (Exception $e) {
            error_status($response, $e->getMessage());
        }
    } else {
        try {
            $db->transactional(function($db) use($post) {
                echo $db->update($post['table'], $post['data'], array('id' => $post['data']['id']));
            });
        } catch (Exception $e) {
            error_status($response, $e->getMessage());
        }
    }
});

$app->post('/admin/media', function($request, $response, $args) {

    require('upload.php');

    try {
        $post = $request->getParsedBody();
        $pathresolver = new FileUpload\PathResolver\Booya($_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $post['entryID']);
        $filesystem = new FileUpload\FileSystem\Simple();
        $filenamegenerator = new FileUpload\FileNameGenerator\Booya();
        $fileupload = new FileUpload\Booya($_FILES['files'], $_SERVER);

        $fileupload->setPathResolver($pathresolver);
        $fileupload->setFileSystem($filesystem);
        $fileupload->setFileNameGenerator($filenamegenerator);

        list($files, $headers) = $fileupload->processAll();

    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }

    if ($fileupload->getError() > 0) {
        error_status($response, $e->getMessage());

    } else {

        $dbEntry = null;
        $childs = [];

        try {
            $pathParts = pathinfo( str_replace($_SERVER['DOCUMENT_ROOT'],'',$files[0]->path) );

            $dbEntry = ['type' => $files[0]->type,
                        'path' => $pathParts['dirname'] . '/' . $pathParts['basename'],
                        'entryId' => $post['entryID'],
                        'thumbPath' => null
                    ];

            // needs ffmpeg with --with-libvorbis --with-theora --with-libvpx
            if (strpos($dbEntry['type'], 'video') !== FALSE) {
                $thumbPath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '_thumb.jpg';

                try {
                    $cmd = 'ffmpeg -i ' . $files[0]->path . ' -ss 00:00:01 -vframes 1 ' . $thumbPath;
                    exec($cmd, $output, $value);
                } catch (Exception $e) {
                    error_log( print_r($cmd,1) );
                    error_log( print_r($value,1) );
                    error_log( print_r($output,1) );
                }

                $dbEntry['thumbPath'] = $thumbPath;

                foreach(['webm','mp4','ogg'] as $format) {

                    if (strpos($dbEntry['type'], $format) === FALSE) {
                        $subVideoPath = $pathParts['dirname'] . '/' . $pathParts['filename'] . '.' . $_SERVER['DOCUMENT_ROOT'] . $format;
                        $dbEntry['type'] .= '|video/' . $format;

                        $pid = pcntl_fork();

                        if ($pid) {
                            $childs[] = $pid;
                        } else {
                            try {
                                //$video->save(new FFMpeg\Format\Video\WebM(), $_SERVER['DOCUMENT_ROOT'] . $subVideoPath);
                                $cmd = 'ffmpeg -i ' . $files[0]->path . ' ' . $_SERVER['DOCUMENT_ROOT'] . $subVideoPath . ' 2>&1';
                                exec($cmd, $output, $value);
                            } catch (Exception $e) {
                                error_log( print_r($cmd,1) );
                                error_log( print_r($value,1) );
                                error_log( print_r($output,1) );
                            } finally {
                                posix_kill(posix_getpid(), SIGKILL);
                            }
                        }
                    }
                }
                /*while(count($childs) > 0) {*/
                    //foreach($childs as $key => $pid) {
                        //$res = pcntl_waitpid($pid, $status, WNOHANG);

                        //// If the process has already exited
                        //if($res == -1 || $res > 0)
                            //unset($childs[$key]);
                    //}
                    //sleep(1);
                /*}*/
            }

            $db = getConnection();
            $db->transactional(function($db) use($files, $dbEntry) {
                $db->insert('media', $dbEntry);
                $files[0]->id = $db->lastInsertId();
            });

            unset($files[0]->path);

            foreach($headers as $header => $value) {
                header($header . ': ' . $value);
            }
            echo json_encode(array('files' => $files ));

        } catch (Exception $e) {
            if ($dbEntry['thumbPath'] !== null) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $dbEntry['thumbPath']);
                foreach ($childs as $child) {
                    posix_kill($child, SIGKILL);
                }
                error_log( print_r( $dbEntry['path'], 1 ) );
                foreach ( explode('|', $dbEntry['path']) as $path)
                    unlink($_SERVER['DOCUMENT_ROOT'] . $path);
            }
            unlink($files[0]->path);

            error_status($response, $e->getMessage());
        }
    }
});

$app->post('/admin/files', function($request, $response, $args) {
    $post = $request->getHeaders();
    try {
        $content = json_encode($post['content']);
        $fd = fopen($_SERVER['DOCUMENT_ROOT'] . '/' . $post['file'], 'w');
        fwrite($fd, $content);
        fclose($fd);
    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});

// DELETE routes
$app->delete('/admin/galleryEntry/{id}', function($request, $response, $args) {
    $db = getConnection();
    try {
        $db->delete('entries', array('id' => $args['id']));
    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});

$app->delete('/admin/media/{id}', function($request, $response, $args) {
    $db = getConnection();
    $post = $request->getParsedBody();

    try {
        $db->delete('media', array('id' => $args['id']));
        foreach( explode('|',$post['path']) as $path) {
            unlink($_SERVER['DOCUMENT_ROOT'] . $path);
            $path_parts = pathinfo($path);
            if (is_dir_empty($path_parts['dirname']))
                rmdir($path_parts['dirname']);
        }

    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});

$app->delete('/admin/file/', function($request, $response, $args) {
    try {
        $file = $_SERVER['DOCUMENT_ROOT'] . $request->get('file');
        if (file_exists($file) && is_file($file)) {
            unlink($file);
        } else {
            error_status($response, 'Error: '.$file.' does not exist.');
        }
    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});

$app->delete('/admin/dir/', function($request, $response, $args) {
    try {
        $dir = $_SERVER['DOCUMENT_ROOT'] . $request->get('dir');
        if (file_exists($dir) && is_dir($dir)) {
            rmdir($dir);
        } else {
            error_status($response, 'Error: '.$dir.' does not exist.');
        }
    } catch (Exception $e) {
        error_status($response, $e->getMessage());
    }
});


// Database Access Layer
// -----------------------------------------------------------------------------

function getConnection() {

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
    $query = "SELECT id, title, description, publish FROM entries WHERE type=?";
    return $db->fetchAll($query, array($page));
}

function getMedia($db, $id){
    $query = "SELECT entryId, id, path FROM media WHERE id=?";
    return $db->fetchAll($query, array($id));
}

function getMediaForEntries($db, $entryId = null){
    $query = "SELECT entryId, id, path, thumbPath, type FROM media";
    if ($entryId !== null) {
        $query .= " WHERE entryId=?";
        return $db->fetchAll($query, array($entryId));
    } else
        return $db->fetchAll($query);
}

// Util Functions
// -----------------------------------------------------------------------------
function filemime($file) {

    $mimes = ['css'=>'text/css',
              'html'=>'text/html',
              'js'=>'text/javascript',
              'json'=>'application/json',
              'php'=>'text/x-php',
              'sass'=>'text/x-sass',
              'scss'=>'text/x-scss',
              'tpl'=>'text/x-smarty',
              'sql'=>'text/x-sql',
              'xml'=>'text/xml' ];

    $extension = pathinfo( $file, PATHINFO_EXTENSION );

    if ( array_key_exists( $extension, $mimes ) ) {
        return $mimes[$extension];
    } else {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($file);
    }
}

function is_dir_empty($dir) {
    if (!is_readable($dir)) return NULL; 
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            return FALSE;
        }
    }
    return TRUE;
}

function error_status($response, $msg) {
    $response->withStatus(500);
    echo $msg;
    exit();
}
