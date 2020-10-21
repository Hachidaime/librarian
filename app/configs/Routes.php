<?php
// Set Base Path
$router->setBasePath(BASE_PATH);

// Dashboard
$router->addRoutes([
    ['GET', '/', 'DashboardController::index'],
    ['GET', '/dashboard', 'DashboardController::index', 'dashboard'],
]);

// User Login & Logout
$router->addRoutes([
    ['GET', '/login', 'LoginController::login', 'login'],
    ['POST', '/login/submit', 'LoginController::submit', 'login_action'],
    ['GET', '/logout', 'LoginController::logout', 'logout'],
]);

$master = ['User', 'Profile'];

foreach ($master as $value) {
    $controller = $value;
    $route = strtolower($value);

    $router->addRoutes([
        ['GET', "/{$route}", "{$controller}Controller::index", $route],
        ['GET', "/{$route}/add", "{$controller}Controller::form"],
        ['GET', "/{$route}/edit/[i:id]?", "{$controller}Controller::form"],
        ['POST', "/{$route}/detail/[i:id]?", "{$controller}Controller::detail"],
        ['POST', "/{$route}/submit", "{$controller}Controller::submit"],
        ['POST', "/{$route}/search", "{$controller}Controller::search"],
        ['POST', "/{$route}/remove", "{$controller}Controller::remove"],
    ]);
}

$router->addRoutes([
    ['POST', '/package/submitexpires', 'PackageController::submitExpires'],
    ['GET', '/package/activity', 'ProfileController::activity'],
]);

$router->map('POST', '/file/upload', 'FileController::upload');

$router->addRoutes([
    ['GET', '/403', 'PageController::error403', '403'],
    ['GET', '/404', 'PageController::error404', '404'],
]);
