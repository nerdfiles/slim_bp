<?php

/* == *
 *
 * SETTINGS
 *
 * ==============================================*/

$php_debug = true;

if ($php_debug) {
  @ini_set('log_errors','On'); // enable or disable php error logging (use 'On' or 'Off')
  @ini_set('display_errors','On'); // enable or disable public display of errors (use 'On' or 'Off')
  @ini_set('error_log','log/errors.log'); // path to server-writable log file
}

// End SETTINGS


/* == *
 *
 * INCLUDES
 *
 * ==============================================*/

require 'Slim/Slim.php';
require 'views/index.php';

// End INCLUDES


/* == *
 *
 * BASE VIEW
 *
 * Call it a design pattern for simple apps.
 *
 * ==============================================*/

// view init
$index_view = new Index_View();
// set base layout
$index_view::set_layout('base.html');

// End BASE VIEW


/* == *
 *
 * SLIM INIT
 *
 * ==============================================*/

$app = new Slim(array(
  'mode' => 'dev',
  'templates.path' => 'templates',
  'view' => $index_view
));

// End SLIM INIT


/* == *
 *
 * CONFIGS
 *
 * ==============================================*/

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

// End CONFIGS


/* == *
 *
 * UTILS
 *
 * ==============================================*/

// recall template
function recall_template() {
  $template_path = $app->config('templates.path'); //returns "../templates"
  return $template_path;
}

// End UTILS


/* == *
 *
 * HOOKS
 *
 * ==============================================*/

  $app->hook('before.body', function() use ( $app ) {

  });

  $app->hook('after.body', function() use ( $app ) {

  });

// End HOOKS


/* == *
 *
 * FILTERS
 *
 * ==============================================*/

  $app->hook('test.filer', function( $argument ) {
    return $argument;
  });

// End FILTERS


/* == *
 *
 * ROUTES
 *
 * ==============================================*/

  $app->map('/', function () use ($app) {
    if ( $app->request()->isPost() ) {
      // if valid login, set auth cookie and redirect

      $app->redirect('/list');
    }

    $app->render('login.html');
  })->via('GET', 'POST');

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

  $app->get('/logout', function () use ($app) {
    //Remove auth cookie and redirect to login page
  });

  $app->get('/protected-page', $authenticateForRole("admin"), function () use ($app) {
    //Show protected information
  });
  */

// End ROUTES


/* == *
 *
 * INIT APP
 *
 * ==============================================*/

  $app->run();

// End INIT APP


?>
