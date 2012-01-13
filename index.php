<?php
/*
  require 'settings.php';

  // log php errors
  if ( $debug ) {
    @ini_set('log_errors','On'); // enable or disable php error logging (use 'On' or 'Off')
    @ini_set('display_errors','On'); // enable or disable public display of errors (use 'On' or 'Off')
    @ini_set('error_log','log/errors.log'); // path to server-writable log file
  }
*/
require 'Slim/Slim.php';

$app = new Slim();

$app->config(array(
  'mode' => 'dev',
  'templates.path' => 'templates'
));

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
    'debug' => true,
  ));
});



/*
function recall_template() {
  $template_path = $app->config('templates.path'); //returns "../templates"
  return $template_path;
}
*/
//GET route
$app->get('/', function () use ($app) {
  $app->render('base.php');
});

$app->run();

?>

