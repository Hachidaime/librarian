<?php
use app\cores\App;

/**
 * Menyertakan konfigurasi
 */
require_once 'configs/Config.php';
require_once 'configs/Locale.php';

/**
 * Menyertakan Composer Autoloading
 */
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'librarian',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container()));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

/**
 * Instansiasi AltoRouter
 */
$router = new AltoRouter();
include 'configs/Routes.php';

/**
 * Instansiasi Smarty Template Engine
 */
$smarty = new Smarty();
$smarty->compile_check = true;
$smarty
    ->setTemplateDir(DOC_ROOT . 'app/views') // ? \Templates directory
    ->setCompileDir(DOC_ROOT . 'app/views_c'); // ? Templates cache directory

/**
 * Instansiasi App
 */
$app = new App();
