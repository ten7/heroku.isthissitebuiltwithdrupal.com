<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/guzzle/GuzzleServiceProvider.php';

use Symfony\Component\HttpFoundation\Request;
use Guzzle\GuzzleServiceProvider;

$app = new Silex\Application();

$app['debug'] = TRUE;

// Register Twig provider, we'll use that for our templates.
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__ . '/../views',
));

$app->register(new GuzzleServiceProvider());

$app->get('/', function (Request $request) use ($app) {
  if ($url = $request->query->get('url')) {
    return $app->redirect($request->getBasePath() . '/' . $url);
  }

  $variables = array(
    'url' => '',
    'is_drupal' => '',
    'tests' => '',
    'errors' => '',
  );

  return $app['twig']->render('index.twig', $variables);
});

// Provide a simple about resource.
$app->get('/about', function() use ($app) {
  $variables = array(
    'url' => NULL,
    'is_drupal' => '',
    'tests' => '',
    'errors' => '',
  );

  return $app['twig']->render('about.twig', $variables) ;
});

// Match any path after / including things with extra slashes in them such as
// example.com/http://drupal.org.
$app->get('/{url}', function(Request $request, $url) use ($app) {
  $guzzle = $app['guzzle.client'];
  $drupalCheck = new Dreamformula\DrupalCheck($url, $guzzle);

  if ($drupalCheck->isDrupal()) {
    $isd = 'yes';
  } else {
    $isd = 'no';
  }

  if (isset($drupalCheck->errors)) {
    $der = $drupalCheck->errors;
  } else {
    $der = 0;
  }
  
  $variables = array(
    'url' => $url,
    'is_drupal' => $isd,
    'tests' => $drupalCheck->results,
    'errors' => $der,
  );

  // Return JSON for anyone that wants it.
  if ($request->headers->get('accept') == 'application/json') {
    return $app->json($variables);
  }
  // Otherwise stick to HTML and let Twig do it's thing.
  else {
    return $app['twig']->render('response.twig', $variables);
  }
})->assert('url', '.+');

return $app;
