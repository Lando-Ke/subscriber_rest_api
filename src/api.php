<?php

use App\Router\Route;
use App\Controller\SubscriberController;
use App\Library\Database\Connection;
use App\Library\ResponseHandler;
use App\Repository\SubscriberRepository;

$db = new Connection();
$responseHandler = new ResponseHandler();

$subscriberRepository = new SubscriberRepository($db);

$subscriberController = new SubscriberController($subscriberRepository, $responseHandler);

// Define the API prefix
$apiPrefix = '/api';

// Prepend the API prefix to each route
$router->addRoute(new Route('POST', $apiPrefix . '/subscriber', [$subscriberController, 'create']));
$router->addRoute(new Route('GET', $apiPrefix . '/subscriber', [$subscriberController, 'read']));
$router->addRoute(new Route('GET', $apiPrefix . '/subscribers', [$subscriberController, 'index']));
