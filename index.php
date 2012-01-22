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
$index_view->set_layout('base.html');

// End BASE VIEW


/* == *
 *
 * SLIM INIT
 *
 * ==============================================*/

$app = new Slim(array(
  'mode' => 'dev',
  'templates.path' => 'templates',
  'view' => $index_view,
  'cookies.secret_key'  => 'r+hhiXlmC4NvsQpq/jaZPK6h+sornz0LC3cbdJNj',
  #'cookies.lifetime' => time() + (1 * 24 * 60 * 60), // = 1 day
  'cookies.cipher' => MCRYPT_RIJNDAEL_256,
  'cookies.cipher_mode' => MCRYPT_MODE_CBC,
  'cookies.secure' => false
));

// set name
//$app->setName('reviewApp');

// End SLIM INIT


/* == *
 *
 * CONFIGS
 *
 * ==============================================*/

  $app->configureMode('prod', function() use ($app) {
    $app->config(array(
      'log.enable' => true,
      'log.path' => '../logs',
      'debug' => false
    ));
  });

  $app->configureMode('dev', function() use ($app) {
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
    
    if ( $app->request()->isPost() && sizeof($app->request()->post()) == 2 ) {
      
      // if valid login, set auth cookie and redirect
      $testp = sha1('uAX8+Tdv23/3YQ==');

      $post = (object)$app->request()->post();

      if ( isset($post->username) && isset($post->password) && sha1($post->password) == $testp && $post->username == 'bppenne' ) {
        //$app->setEncryptedCookie('bppasscook', $post->password, 0);
        $app->setCookie('user_cook', $post->username, 0);
        $app->setCookie('pass_cook', $post->password, 0);
        $app->redirect('./review');
      } else {
        $app->redirect('.');
      }

    }

    $app->render('login.html');

  })->via('GET', 'POST')->name('login');

  $authUser = function( $role = 'member') use ($app) {
    return function () use ( $role ) {
      $app = Slim::getInstance();
      
      // Check for password in the cookie
      if ( $app->getCookie('pass_cook') != 'uAX8+Tdv23/3YQ==' || $app->getCookie('user_cook') != 'bppenne' ) {
      //if ( $app->getEncryptedCookie('bppasscook', false) != 'uAX8+Tdv23/3YQ==') {
        $app->redirect('..');
        //$app->redirect('review');
      }
    };
  };

  $app->get('/review/', $authUser('review'), function() use ($app) {
    $json_data = file_get_contents('./data/bp_review.json');
    $data = json_decode($json_data, true);
    $app->render('index.html', array( 'data' => $data ));
  })->name('review');

  $app->get('/detail/:itemname', function($itemname) use ($app) {
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
