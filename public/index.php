<?php
// Auth Details.
define('USERNAME', 'admin');
define('PASSWORD', 'password');

// Slim PHP
require 'Slim/Slim.php';
require 'Views/TwigView.php';

// Paris and Idiorm
require 'Paris/idiorm.php';
require 'Paris/paris.php';

// Models
require 'models/Article.php';

// Configuration
TwigView::$twigDirectory = __DIR__ . '/Twig/lib/Twig/';

ORM::configure('mysql:host=localhost;dbname=blog');
ORM::configure('username', 'root');
ORM::configure('password', '');

// Start Slim.
$app = new Slim(array(
	'view' => new TwigView
));



// Auth Check.
$authCheck = function() use ($app) {
	$authRequest 	= isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
	$authUser 		= $authRequest && $_SERVER['PHP_AUTH_USER'] === USERNAME;
	$authPass 		= $authRequest && $_SERVER['PHP_AUTH_PW'] === PASSWORD;

	if (! $authUser || ! $authPass) {
		$app->response()->header('WWW-Authenticate: Basic realm="My Blog Administration"', '');
		$app->response()->header('HTTP/1.1 401 Unauthorized', '');
		$app->response()->body('<h1>Please enter valid administration credentials</h1>');
		$app->response()->send();
		exit;
	}
};



// Blog Homepage.
$app->get('/', function() use ($app) {
	$articles = Model::factory('Article')
					->order_by_desc('timestamp')
					->find_many();
					
	return $app->render('blog_home.html', array('articles' => $articles));		
});

// Blog View.
$app->get('/view/(:id)', function($id) use ($app) {
	$article = Model::factory('Article')->find_one($id);
	if (! $article instanceof Article) {
		$app->notFound();
	}
	
	return $app->render('blog_detail.html', array('article' => $article));
});



// Admin Home.
$app->get('/admin', $authCheck, function() use ($app) {
	$articles = Model::factory('Article')
					->order_by_desc('timestamp')
					->find_many();
					
	return $app->render('admin_home.html', array('articles' => $articles));
});

// Admin Add.
$app->get('/admin/add', $authCheck, function() use ($app) {
	return $app->render('admin_input.html', array('action_name' => 'Add', 'action_url' => '/admin/add'));
});	

// Admin Add - POST.
$app->post('/admin/add', $authCheck, function() use ($app) {
	$article 			= Model::factory('Article')->create();
	$article->title 	= $app->request()->post('title');
	$article->author 	= $app->request()->post('author');
	$article->summary 	= $app->request()->post('summary');
	$article->content 	= $app->request()->post('content');
	$article->timestamp = date('Y-m-d H:i:s');
	$article->save();
	
	$app->redirect('/admin');
});

// Admin Edit.
$app->get('/admin/edit/(:id)', $authCheck, function($id) use ($app) {
	$article = Model::factory('Article')->find_one($id);
	if (! $article instanceof Article) {
		$app->notFound();
	}	
	
	return $app->render('admin_input.html', array(
		'action_name' 	=> 	'Edit', 
		'action_url' 	=> 	'/admin/edit/' . $id,
		'article'		=> 	$article
	));
});

// Admin Edit - POST.
$app->post('/admin/edit/(:id)', $authCheck, function($id) use ($app) {
	$article = Model::factory('Article')->find_one($id);
	if (! $article instanceof Article) {
		$app->notFound();
	}
	
	$article->title 	= $app->request()->post('title');
	$article->author 	= $app->request()->post('author');
	$article->summary 	= $app->request()->post('summary');
	$article->content 	= $app->request()->post('content');
	$article->timestamp = date('Y-m-d H:i:s');
	$article->save();
	
	$app->redirect('/admin');
});

// Admin Delete.
$app->get('/admin/delete/(:id)', $authCheck, function($id) use ($app) {
	$article = Model::factory('Article')->find_one($id);
	if ($article instanceof Article) {
		$article->delete();
	}
	
	$app->redirect('/admin');
});



// Slim Run.
$app->run();