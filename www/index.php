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
$app->register(new Silex\Extension\SessionExtension());

// twig
$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => __DIR__.'/../views',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));

// doctrine
require_once __DIR__.'/../data/db.php';

$app->register(new Silex\Extension\DoctrineExtension(), array(
    'db.options'            => $dbOptions,
    'db.dbal.class_path'    => __DIR__.'/../vendor/doctrine-dbal/lib',
    'db.common.class_path'  => __DIR__.'/../vendor/doctrine-common/lib',
));

// home
$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig');
});

// competition
$app->get('/wettbewerb', function() use ($app) {
    return $app['twig']->render('competition.twig');
});

// voting
$app->get('/abstimmen', function() use ($app) {
    $votes = $films = array();
    if (time() >= strtotime('15.9.2011 00:00:00')) {
        $sql = "SELECT * FROM film";
        $films = $app['db']->fetchAll($sql);
        foreach ($films as $index => $film ) {
            foreach ($film as $field => $value) {
                $films[$index][$field] = utf8_encode($value);
            }
        }
        $sql = "SELECT film_id, (SELECT count(*) FROM vote) AS total, count(film_id) AS byFilm
                FROM vote
                GROUP BY film_id";
        $rawVotes = $app['db']->fetchAll($sql);
        foreach ($rawVotes as $vote) {
            $votes[$vote['film_id']] = round($vote['byFilm'] / $vote['total'] * 100);
        }
    }
    return $app['twig']->render('voting.twig', array(
            'films' => $films,
            'rc_code' => recaptcha_get_html($app['rc_public_key']),
            'selectedFilm' => $app['request']->get('film'),
            'votes' => $votes,
            'over' => (time() > strtotime('22.9.2011 23:59:59'))
        ));
});

$app->post('/stimmen', function() use ($app) {
    if (time() <= strtotime('22.9.2011 23:59:59')) {
        $get = '';
        $filmId = $app['request']->get('film');
        if (!$filmId) {
            $app['session']->setFlash('error', 'Bitte wähle einen Film aus');
        } else {
            if (!checkCaptcha($app['rc_private_key'])) {
                $app['session']->setFlash('error', 'Deine Stimme konnte nicht gezählt werden.
                                                    Hast du die Kontrollwörter korrekt eingegeben?');
                $get = '?film='.$filmId;
            } else {
                $sql = "INSERT INTO vote (film_id, ip) VALUES (?, ?)";
                try {
                    $app['db']->executeQuery($sql, array((int)$filmId, (string)$_SERVER['REMOTE_ADDR']));
                    $app['session']->setFlash('success', 'Danke für deine Stimme');
                    setcookie('fafv', 1);
                } catch (Exception $e) {
                    $app['session']->setFlash('error', 'Deine Stimme konnte nicht gezählt werden.
                                                        Bitte versuche es nochmals oder melde dich bei uns.');
                }
            }
        }
    } else {
        $app['session']->setFlash('error', 'Die Abstimmung ist vorbei');
    }
    return $app->redirect('/abstimmen'.$get);
});

function checkCaptcha($privateKey) {
    $resp = recaptcha_check_answer (
        $privateKey,
        $_SERVER["REMOTE_ADDR"],
        $_POST["recaptcha_challenge_field"],
        $_POST["recaptcha_response_field"]);

    return $resp->is_valid;
}

$app->run();
