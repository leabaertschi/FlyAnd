<?php

require_once __DIR__.'/vendor/silex.phar';

$env = 'dev';
if (preg_match('/fly-and\.ch/', $_SERVER['HTTP_HOST'])){
    $env = 'prod';
}

$app = new Silex\Application();
$app['debug'] = $env == 'dev';

// twig
$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/views',
    'twig.class_path' => __DIR__.'/vendor/twig/lib',
));

// doctrine
if ($env == 'prod') {
    $dbOptions = array(
        'driver' => 'pdo_mysqli',
        'host' => '',
        'user' => '',
        'password' => '',
        'dbname' => 'fly_and_film',
    );
} else {
    $dbOptions = array(
        'driver' => 'pdo_mysqli',
        'host' => 'localhost',
        'user' => 'fly_and',
        'password' => 'cn3WExfHS5zReTQR',
        'dbname' => 'fly_and_film',
    );
}

$app->register(new Silex\Extension\DoctrineExtension(), array(
    'db.options'            => $dbOptions,
    'db.dbal.class_path'    => __DIR__.'/vendor/doctrine-dbal/lib',
    'db.common.class_path'  => __DIR__.'/vendor/doctrine-common/lib',
));

// home
$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig');
});

// competition
$app->get('/wettbewerb', function() use ($app) {
    return $app['twig']->render('wettbewerb.twig');
});

$app->run();