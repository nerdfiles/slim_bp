<?php
$php_debug = true;

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

// recall template
function recall_template() {
  $template_path = $app->config('templates.path'); //returns "../templates"
  return $template_path;
}

// set base layout
$index_view::set_layout('base.html');

// routes
$app->get('/list/', function() use ($app) {
  $app->render('index.html');
});

$app->get('/detail/:itemname', function() use ($app) {
  $app->render('detail.html');
});

/*
$app->get('/test/', function() use ($app) {
	$app->render('test.html');
});
*/

/*
$authenticateForRole = function($role="member") {
   return function () use ($role) {
     //Match cookie to existing user with role, else redirect to login page
     $app->render('test.html');

  }
}
*/

$app->map('/', function () use ($app) {
  if ( $app->request()->isPost() ) {
    //If valid login, set auth cookie and redirect
  }

  $app->render('login.html');
})->via('GET', 'POST');

/*
$app->get('/logout', function () use ($app) {
  //Remove auth cookie and redirect to login page
});

$app->get('/protected-page', $authenticateForRole("admin"), function () use ($app) {
  //Show protected information
});
*/

$app->run();

?>
