<?php

$php_debug = false;

if ($php_debug) {
  @ini_set('log_errors','On'); // enable or disable php error logging (use 'On' or 'Off')
  @ini_set('display_errors','On'); // enable or disable public display of errors (use 'On' or 'Off')
  @ini_set('error_log','log/errors.log'); // path to server-writable log file
}

// deps
require 'Slim/Slim.php';
require 'views/index.php';

// view init
$index_view = new Index_View();

// slim init
$app = new Slim(array(
  'mode' => 'dev',
  'templates.path' => 'templates',
  'view' => $index_view
));

// configs
$app->configureMode('prod', function () use ($app) {
  $app->config(array(
    'log.enable' => true,
    'log.path' => '../logs',
    'debug' => false
  ));
});

$app->configureMode('dev', function () use ($app) {
  $app->config(array(
    'log.enable' => false,
    'debug' => true
  ));
});

/*
  function recall_template() {
    $template_path = $app->config('templates.path'); //returns "../templates"
    return $template_path;
  }
*/

// set base layout
$index_view::set_layout('base.html');

//GET route
$app->get('/', function () use ($app) {
  $app->render('index.html');
});

$app->get('/test/', function() use ($app) {
	$app->render('test.html');
});

$app->run();

?>
