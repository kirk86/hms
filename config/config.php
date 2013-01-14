<?php
// SITE_ROOT contains the full path to the hms folder
define('SITE_ROOT', dirname(dirname(__FILE__)));
// Application directories
define('CLASSES_DIR', SITE_ROOT.'/classes/');
define('INCLUDES_DIR', SITE_ROOT.'/includes/');
// Settings needed to configure the hms
define('CONFIG_DIR', SITE_ROOT.'/config/');
define('ERROR_LOGS', SITE_ROOT.'/logs/');


// These should be true while developing the web site
define('IS_WARNING_FATAL', true);
define('DEBUGGING', true);
// The error types to be reported
define('ERROR_TYPES', E_ALL);
// Settings about mailing the error messages to admin
define('SEND_ERROR_MAIL', false);
define('ADMIN_ERROR_MAIL', 'giannismitros@gmail.com');
define('SENDMAIL_FROM', 'info@hms.com');
ini_set('sendmail_from', SENDMAIL_FROM);
// By default we don't log errors to a file
define('LOG_ERRORS', true);
define('LOG_ERRORS_FILE', ERROR_LOGS.'\\error_logs.txt'); // Windows
// define('LOG_ERRORS_FILE', '/home/username/hms/errors.log'); // Linux
/* Generic error message to be displayed instead of debug info
(when DEBUGGING is false) */
define('SITE_GENERIC_ERROR_MESSAGE', '<h1>HMS Error!</h1>');


//COOKIE AUTHNTICATION
define('_COOKIE_KEY_', 'GEn2Li0LsqGyXg7qEIGvvsNSBRgvXm2TzmRrkKDHw11Sv3NIDjWmfFwr');
define('_COOKIE_IV_', 'ENi58bBN');
define('__BASE_URI__', '/');


// Database connectivity setup
define('DB_PERSISTENCY', 'true');
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'hms_support');
define('PDO_DSN', 'mysql:host='.DB_SERVER.'; dbname='.DB_DATABASE);
//define('DSN', 'mysqli://'.DB_USERNAME.':'.DB_PASSWORD.'@'.DB_SERVER.'/'.DB_DATABASE);

