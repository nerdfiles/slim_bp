<?php

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

