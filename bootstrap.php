<?php
const UPLOAD_DIR = __DIR__ . '/uploads';

session_start();
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set("Europe/Moscow");

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/functions/db.php');
require_once(__DIR__ . '/functions/views.php');
require_once(__DIR__ . '/functions/filters.php');
require_once(__DIR__ . '/functions/file.php');
require_once(__DIR__ . '/functions/mail.php');
require_once(__DIR__ . '/functions/calc.php');
require_once(__DIR__ . '/functions/requests.php');
require_once(__DIR__ . '/functions/validators.php');
require_once(__DIR__ . '/functions/validators/validate_lot.php');
require_once(__DIR__ . '/functions/validators/validate_bet.php');
require_once(__DIR__ . '/functions/validators/validate_user.php');

require_once('config.php');

$config = require 'config.php';

$title = $config['main']['name'];
$connection = db_connect($config['db']);
