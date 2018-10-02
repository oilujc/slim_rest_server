<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['view'] = function ($container) {
	$settings = $container->get('settings')['renderer'];
    $view = new \Slim\Views\Twig($settings['template_path'],[
            'cache' => false,
            'debug' => true,
        ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//Eloquent
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

//Validator
$container['validator'] = function () {
        \Respect\Validation\Validator::with('\\api\\validation\\rules\\');
        return new Awurth\SlimValidation\Validator();
    };
// Controllers

//Login
$container['LoginController'] = function($container) {
        return new api\controllers\LoginController($container);
    };

//Register
$container['RegisterController'] = function($container) {
        return new api\controllers\RegisterController($container);
    };

$container['TeamController'] = function($container) {
        return new api\controllers\TeamController($container);
    };