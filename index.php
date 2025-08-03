<?php

require_once __DIR__ . '/routes/Router.php';
require_once __DIR__ . '/database/Database.php';

// Initialize database
$db = Database::getInstance();
$db->createTables();

// Create router and controller
$router = new Router();
$controller = new TaskController();

// Define routes
$router->addRoute('GET', '/tasks', [$controller, 'index']);
$router->addRoute('POST', '/tasks', [$controller, 'store']);
$router->addRoute('GET', '/tasks/{id}', [$controller, 'show']);
$router->addRoute('PUT', '/tasks/{id}', [$controller, 'update']);
$router->addRoute('DELETE', '/tasks/{id}', [$controller, 'delete']);

// Handle the request
$router->handleRequest(); 