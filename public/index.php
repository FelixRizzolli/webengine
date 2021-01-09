<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use app\controllers\SiteController;
use app\controllers\AuthController;
use app\core\Application;
use app\models\User;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'userClass' => User::class,
    'layout' => 'main',
    'database' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];
$app = new Application(dirname(__DIR__), $config);

/* ---------------------[ EVENTS ]--------------------- */
$app->on(Application::EVENT_BEFORE_REQUEST, function() {
    /* log request */
});


/* ---------------------[ PUBLIC PAGES ]--------------------- */
$app->getRouter()->get('/', [SiteController::class, 'homepage']);
$app->getRouter()->get('/contact', [SiteController::class, 'contact']);
$app->getRouter()->post('/contact', [SiteController::class, 'contact']);


/* ---------------------[ PAGES WITH AUTHENTIFICATION ]--------------------- */
$app->getRouter()->get('/login', [AuthController::class, 'login']);
$app->getRouter()->post('/login', [AuthController::class, 'login']);
$app->getRouter()->get('/register', [AuthController::class, 'register']);
$app->getRouter()->post('/register', [AuthController::class, 'register']);
$app->getRouter()->get('/logout', [AuthController::class, 'logout']);
$app->getRouter()->get('/profile', [AuthController::class, 'profile']);

$app->run();