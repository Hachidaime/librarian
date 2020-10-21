<?php

define('ENVIRONMENT', 'development');

/**
 * Nama Proyek
 *
 * @var string
 */
define('PROJECT_NAME', 'LIBRARIAN');

/**
 * Path letak aplikasi dijalankan
 * bisa diganti slash '/' jika aplikasi tidak di dalam folder
 *
 * @var string
 */
define('BASE_PATH', '/librarian');

/**
 * URL Aplikasi
 * disesuaikan dengan url aplikasi
 *
 * @var string
 */
define('BASE_URL', 'http://localhost' . BASE_PATH);

/* Development Database  */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '123456');
define('DB_NAME', 'librarian');
define('DB_DRIVER', 'mysql');
define('DB_CHARSET', 'utf8');
define('DB_COLLATION', 'utf8_unicode_ci');
define('DB_PREFIX', '');

/* Production Database */
define('DB_HOST_PROD', '');
define('DB_USER_PROD', '');
define('DB_PASS_PROD', '');
define('DB_NAME_PROD', '');
define('DB_DRIVER_PROD', 'mysql');
define('DB_CHARSET_PROD', 'utf8');
define('DB_COLLATION_PROD', 'utf8_unicode_ci');
define('DB_PREFIX_PROD', '');

/* Encryption Key */
define('MY_KEY', 'everybodyjump');
