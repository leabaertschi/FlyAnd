<?php

require_once __DIR__.'/vendor/silex.phar';

$app = new Silex\Application();

// twig
$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/views',
    'twig.class_path' => __DIR__.'/vendor/twig/lib',
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