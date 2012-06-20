<?php

require_once __DIR__.'/../vendor/silex.phar';
require_once __DIR__.'/../vendor/recaptcha-php/recaptchalib.php';

$env = 'dev';
if (preg_match('/fly-and\.ch/', $_SERVER['HTTP_HOST'])){
    $env = 'prod';
}

$app = new Silex\Application();
$app['debug'] = $env == 'dev';
$app['rc_public_key'] = '6Leh4ccSAAAAAMi846x5jbZgW9JfYwjunELh82bv';
$app['rc_private_key'] = '6Leh4ccSAAAAACXTE2QJaFb9lZah919yzlbL8GyK';

// session
//$app->register(new Silex\Extension\SessionExtension());

// twig
$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/../views',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));

// doctrine
//require_once __DIR__.'/../data/db.php';
//
//$app->register(new Silex\Extension\DoctrineExtension(), array(
//    'db.options'            => $dbOptions,
//    'db.dbal.class_path'    => __DIR__.'/../vendor/doctrine-dbal/lib',
//    'db.common.class_path'  => __DIR__.'/../vendor/doctrine-common/lib',
//));

// home
$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig');
});

// competition
//$app->get('/wettbewerb', function() use ($app) {
//    return $app['twig']->render('competition.twig');
//});

$app->run();
