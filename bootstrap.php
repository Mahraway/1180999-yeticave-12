<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Moscow");

// Основные настройки сайта
$is_auth = rand(0, 1);
$user_name = 'Рашид';
$title = 'YetiCave';

require_once('helpers.php');
require_once('config.php');
require_once('queries.php');

$config = require 'config.php';

$connection = db_connect($config['db']);