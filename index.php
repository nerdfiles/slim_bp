<?php

// log php errors
@ini_set('log_errors','On'); // enable or disable php error logging (use 'On' or 'Off')
@ini_set('display_errors','On'); // enable or disable public display of errors (use 'On' or 'Off')
@ini_set('error_log','log/errors.log'); // path to server-writable log file

require 'Slim/Slim.php';

$app = new Slim();

/*
function recall_template() {
  $template_path = $app->config('templates.path'); //returns "../templates"
  return $template_path;
}
*/

//GET route
$app->get('/', function () {
});

$app->run();

?>

