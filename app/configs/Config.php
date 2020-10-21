<?php

/**
 * Path Root letak file di server
 *
 * @var string
 */
define('DOC_ROOT', realpath(dirname(__FILE__)) . '/../../');

/**
 * Controller default
 *
 * @var string
 */
define('DEFAULT_CONTROLLER', 'DashboardController');

/**
 * Method default
 *
 * @var string
 */
define('DEFAULT_METHOD', 'index');

/**
 * Name default
 *
 * @var string
 */
define('DEFAULT_NAME', 'dashboard');

/**
 * Jumlah baris yang ditampilkan per halaman pada pagination
 *
 * @var integer
 */
define('ROWS_PER_PAGE', 10);

/**
 * Jumlah halaman yang ditampilkan
 * sebelum dan sesudah halaman aktif
 * pada pagination
 *
 * @var integer
 */
define('SURROUND_COUNT', 1);

/**
 * Format Tanggal Waktu yang ditampilkan pada view
 *
 * @var string
 * @see https://www.smarty.net/docs/en/language.modifier.date.format.tpl
 */
define('DATETIME_FORMAT', '%d/%m/%Y %H.%M.%S');

/**
 * Format Tanggal yang ditampilkan pada view
 *
 * @var string
 * @see https://www.smarty.net/docs/en/language.modifier.date.format.tpl
 */
define('DATE_FORMAT', '%d/%m/%Y');

/**
 * Format Waktu yang ditampilkan pada view
 *
 * @var string
 * @see https://www.smarty.net/docs/en/language.modifier.date.format.tpl
 */
define('TIME_FORMAT', '%H:%i:%s');
