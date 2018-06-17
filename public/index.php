<?php
require_once __DIR__ . './../vendor/autoload.php';

foreach (glob("./../config/*.php") as $filename)
{
    require_once $filename;
}

$page = 'index';

if (isset ($_GET['page'])){
	$page = $_GET['page'];
}

$controllerName = 'App\\Controllers\\'. ucfirst($page);

if (class_exists ($controllerName)){
	$controller = new $controllerName();
} else {
	$controller = new App\Controllers\NotFound();
}
$data = $controller->render();
$loader = new Twig_Loader_Filesystem('./../views');
$twig = new Twig_Environment($loader, []);
$function = new Twig_SimpleFunction('path', function ($path) {
	return PATH_PREFIX . $path;
});
$twig->addFunction($function);
$template = $twig->load($data['view']);
$assets = [
	'javascripts' => JAVASCRIPTS,
	'styles' => STYLES
];
$templateData = array_merge($assets, $data['data']);
echo $template->render($templateData);