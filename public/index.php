<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php'; // Load dependencies
require __DIR__ . '/../src/db.php';         // Load database connection
require __DIR__ . '/../src/routes.php';     // Load routes

$app = AppFactory::create();

// Enable error middleware for debugging
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Load routes
$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

// Run the Slim application
$app->run();

