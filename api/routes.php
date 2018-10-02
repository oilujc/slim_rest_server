<?php
use api\lib\Auth,
    api\lib\Response,
    api\validation\TestValidation,
    api\middleware\AuthMiddleware;

$app->get('/user[/{name}]', function($request, $response, $args){
    return $this->view->render($response, 'index.twig', [
        'data' => $args
    ]);
});

$app->post('/login', 'LoginController:login');

$app->post('/signup', 'RegisterController:signup');
