<?php

include_once __DIR__ . '/vendor/autoload.php';

use ixapek\BuyItAgain\Component\Http\{
    Exception\HttpException,
    Exception\InternalErrorException,
    Exception\MethodNotAllowedException,
    Exception\NotFoundException,
    Response};
use ixapek\BuyItAgain\Controller\IController;

try {

    list($crumbs) = explode('?', $_SERVER['REQUEST_URI']);

    $controllerName = '\ixapek\BuyItAgain\Controller' . str_replace('/', '\\', rtrim($crumbs, '/'));

    if (false === class_exists($controllerName)) {
        throw new NotFoundException("Controller not found");
    }

    /** @var IController $controller */
    $controller = new $controllerName();
    if( false === ($controller instanceof IController) ){
        throw new NotFoundException("Controller not found");
    }

    $method = strtoupper($_SERVER['REQUEST_METHOD']);
    if(false === in_array($method, $controller->getAllowed())){
        throw new MethodNotAllowedException("Controller method not allowed");
    }

    if(false === method_exists($controller, $method)){
        throw new MethodNotAllowedException("Controller method not allowed");
    }

    /** @var Response $response */
    $response = $controller->$method();

} catch (HttpException $httpException){
    $headers = [];
    if( true === ($httpException instanceof MethodNotAllowedException) && true === isset($controller)){
        $headers['Allowed'] = implode(',', $controller->getAllowed());
    }

    $response = Response::fromException($httpException, $headers);
} catch (Exception $e){
    error_log($e);
    $response = Response::fromException(new InternalErrorException("Internal error"));
}

$response->render();