<?php
require_once __DIR__ . './../vendor/autoload.php';

$session = App\Security\Session::getInstance();

foreach (glob("./../config/*.php") as $filename)
{
    require_once $filename;
}

$page = 'index';

if (isset ($_GET['page']))
{
	$page = $_GET['page'];
}

$controllerName = 'App\\Controllers\\'. ucfirst($page);

if (class_exists ($controllerName))
{
	$controller = new $controllerName();
} else {
	$controller = new App\Controllers\NotFound();
}
$data = $controller->render();
$loader = new Twig_Loader_Filesystem('./../views');
$twig = new Twig_Environment($loader, []);
$function = new Twig_SimpleFunction('path', function ($path)
{
	return PATH_PREFIX . $path;
});
$twig->addFunction($function);
$template = $twig->load($data['view']);
$flashMessages = App\Security\Session::getFlashMessages();
$assets = [
	'javascripts' => JAVASCRIPTS,
	'styles' => STYLES
];
$flashMessagesData = [
	'flash_success' => array_key_exists('success', $flashMessages) ? $flashMessages['success']: [],
	'flash_error' =>  array_key_exists('error', $flashMessages) ? $flashMessages['error'] : []
];
$templateData = array_merge($assets, $flashMessagesData, $data['data']);
echo $template->render($templateData);