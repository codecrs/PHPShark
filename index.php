<?php

/**
 *  Qmvc - Quick PHP MVC
 *  Developed by Contempative Radical Solutions Consulting Private Ltd.
 *  Project Initiative By Ankit Kumar
 *  http://www.contemplativeradicals.com
 *
 *  @copyright  Contempative Radical Solutions Consulting Private Ltd.
 *  @link
 *  @since      1.0.0
 *  @license
 *
 * Change Logs
 * *******************************************************************
 *
 * *******************************************************************
 **/

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
//-------------------DO NOT CHANGE--------------------------------------------------------------//
Defined("DS") || Define('DS', DIRECTORY_SEPARATOR);
//-------------------DO NOT CHANGE--------------------------------------------------------------//
//Path definitions
Defined("ROOT_PATH") || Define('ROOT_PATH', realpath(dirname(dirname(__file__))) . DS);
Defined("BASEPATH") || Define('BASEPATH', realpath(dirname(dirname(__file__))) . DS);
Defined("FOLDER_PATH") || Define('FOLDER_PATH', realpath(dirname(__file__)) . DS);
//Project paths
Defined("APP_PATH") || Define('APP_PATH', FOLDER_PATH . 'app' . DS);
Defined("SETUP_PATH") || Define('SETUP_PATH', FOLDER_PATH . 'setup' . DS);
Defined("PROJECT_PATH") || Define('PROJECT_PATH', FOLDER_PATH . 'project' . DS);
Defined("PROJECT_VENDOR_PATH") || Define('PROJECT_VENDOR_PATH', FOLDER_PATH . 'project' . DS . 'vendors' . DS);
Defined("APP_VENDOR_PATH") || Define('APP_VENDOR_PATH', FOLDER_PATH . 'app' . DS . 'vendors' . DS);
Defined("TEMPLATE_PATH") || Define('TEMPLATE_PATH', PROJECT_PATH . 'templates' . DS);
Defined("PUBLIC_PATH") || Define('PUBLIC_PATH', FOLDER_PATH . 'public' . DS);
//-----------------------------------------------------------------------------------
// PHP Mailer Integration
// Start Reading Configuration File
// Description: These settings correspond to the Email environemnt.
//-----------------------------------------------------------------------------------
require_once('app/vendors/php-mailer/class.phpmailer.php');
require_once('app/vendors/php-mailer/class.phpmaileroauth.php');
require_once('app/vendors/php-mailer/class.phpmaileroauthgoogle.php');
require_once('app/vendors/php-mailer/class.pop3.php');
require_once('app/vendors/php-mailer/class.smtp.php');

function PHPMailerAutoload($classname){
    //Can't use __DIR__ as it's only in PHP 5.3+
    $filename = "app/vendors/php-mailer".DIRECTORY_SEPARATOR.'class.'.strtolower($classname).'.php';
    if (is_readable($filename)) {
        require_once($filename);
    }
}

/*****************************************************************************************/
/* READ XML SETTINGS FILE
/*****************************************************************************************/
$loc = PROJECT_PATH . 'config.xml';
$xmlDoc = new DOMDocument();
$congifArray = array();
$xmlDoc->load($loc);
$x = $xmlDoc->getElementsByTagName('PHP');
for ($i = 0; $i <= $x->length - 1; $i++) {
	//Process only element nodes
	if ($x->item($i)->nodeType == 1) {
		if ($x->item($i)->childNodes->item(0)->nodeValue == 'appconfig') {
			$y = ($x->item($i)->parentNode);
		}
	}
}
$cd = ($y->childNodes);
for ($i = 0; $i < $cd->length; $i++) {
	//Process only element nodes
	if ($cd->item($i)->nodeType == 1) {
		$output['tag'] = $cd->item($i)->nodeName;
		if (!empty($cd->item($i)->childNodes->item(0)->nodeValue)) {
			$output['value'] = $cd->item($i)->childNodes->item(0)->nodeValue;
		}
		if (!empty($cd->item($i)->childNodes->item(0)->nodeValue)) {
			$output[$i] = [
				$cd->item($i)->nodeName => $cd->item($i)->childNodes->item(0)->nodeValue,
			];
		} else {
			$output[$i] = [$cd->item($i)->nodeName => ''];
		}
		//Merge each player row into same array to allow for batch insert
		$congifArray = array_merge($congifArray, $output);
	}
}
$x = '';
$i = '';

//var_Dump($congifArray);
//-----------------------------------------------------------------------------------
// End of Reading Configuration File
//-----------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------
// BUILD Configuration
//-----------------------------------------------------------------------------------
for ($c = 0; $c < count($congifArray); $c++) {
//////////////////////////////////////////////////////////////////
/***************************************************************
Description: Set Project Name:
Purpose: This may be required for internal application
naming or folder recognisation at runtime.
/***************************************************************/
	//Project Name:
	if (isset($congifArray[$c]['PROJECT']))
		$project = $congifArray[$c]['PROJECT'];
  if (isset($congifArray[$c]['BASE']))
  	$base = $congifArray[$c]['BASE'];
/***************************************************************
Description: Set your work environment
Environments: Development, Testing, Production.
Purpose: Setting these environment values will automatically
	     set the nature of the error handelling in the application.
		 EXAMPLE: 'Production' will disable the error reporting.
/***************************************************************/
	//Set Your Environment
	if (isset($congifArray[$c]['ENVIRONMENT']))
		$environment = $congifArray[$c]['ENVIRONMENT'];
	if (isset($congifArray[$c]['REMOVE_COMMENTS']))
		$remove_comments = $congifArray[$c]['REMOVE_COMMENTS'];
	if (isset($congifArray[$c]['CODE_MINIFY']))
		$code_minify = $congifArray[$c]['CODE_MINIFY'];
	if (isset($congifArray[$c]['ERROR_REPORTING']))
		$error_reporting = $congifArray[$c]['ERROR_REPORTING'];
/***************************************************************
Description: 'ON' will through error on screen.
			 'OFF' will create a log and exit
/***************************************************************/
	//Set Application Error
	if (isset($congifArray[$c]['APPLICATION_ERROR']))
		$application_error = $congifArray[$c]['APPLICATION_ERROR'];
/***************************************************************
Description: These settings correspond to the database environemnt.
			 QMC is built with the PDO Library.
			 It supports both Native and Library DB syntax.

For connection, you may require to refer your corresponding
database manual.
/***************************************************************/
	//Database Settings
	if (isset($congifArray[$c]['PERSISTENT']))
		$persistent = $congifArray[$c]['PERSISTENT'];
	if (isset($congifArray[$c]['DATASOURCE']))
		$datasource = $congifArray[$c]['DATASOURCE'];
	if (isset($congifArray[$c]['HOST']))
		$host = $congifArray[$c]['HOST'];
	if (isset($congifArray[$c]['PORT']))
		$port = $congifArray[$c]['PORT'];
	if (isset($congifArray[$c]['DATABASE']))
		$database = $congifArray[$c]['DATABASE'];
	if (isset($congifArray[$c]['LOGIN']))
		$login = $congifArray[$c]['LOGIN'];
	if (isset($congifArray[$c]['PASSWORD']))
		$password = $congifArray[$c]['PASSWORD'];
	if (isset($congifArray[$c]['CHARSET']))
		$charset = $congifArray[$c]['CHARSET'];
	if (isset($congifArray[$c]['COLLATION']))
		$collation = $congifArray[$c]['COLLATION'];
	if (isset($congifArray[$c]['PREFIX']))
		$prefix = $congifArray[$c]['PREFIX'];

/***************************************************************
Description: These settings correspond to the Email environemnt.
/***************************************************************/

	if (isset($congifArray[$c]['MAIL_HOST']))
		$mail_host = $congifArray[$c]['MAIL_HOST'];
	if (isset($congifArray[$c]['MAIL_USER']))
		$mail_user = $congifArray[$c]['MAIL_USER'];
	if (isset($congifArray[$c]['MAIL_PASSWORD']))
		$mail_password = $congifArray[$c]['MAIL_PASSWORD'];
	if (isset($congifArray[$c]['MAIL_SMTP_SECURE']))
		$mail_smtp_secure = $congifArray[$c]['MAIL_SMTP_SECURE'];
	if (isset($congifArray[$c]['MAIL_PORT']))
		$mail_port = $congifArray[$c]['MAIL_PORT'];
	if (isset($congifArray[$c]['MAIL_SET_FROM']))
		$mail_set_from = $congifArray[$c]['MAIL_SET_FROM'];
	if (isset($congifArray[$c]['MAIL_SET_FROM_TYPE']))
		$mail_set_from_type = $congifArray[$c]['MAIL_SET_FROM_TYPE'];
	if (isset($congifArray[$c]['MAIL_SMTP_AUTH']))
		$mail_smtp_auth = $congifArray[$c]['MAIL_SMTP_AUTH'];
	if (isset($congifArray[$c]['MAIL_ADD_ADDRESS']))
		$mail_add_address = $congifArray[$c]['MAIL_ADD_ADDRESS'];
	if (isset($congifArray[$c]['MAIL_RECIPIENT_NAME']))
		$mail_recipient_name = $congifArray[$c]['MAIL_RECIPIENT_NAME'];
	if (isset($congifArray[$c]['MAIL_IS_HTML']))
		$mail_is_html = $congifArray[$c]['MAIL_IS_HTML'];
	if (isset($congifArray[$c]['TEMPLATE_FOLDER']))
		$template_folder = $congifArray[$c]['TEMPLATE_FOLDER'];
/***************************************************************
Description: These settings correspond to Webadmin user settings.
	 ***************************************************************
/***************************************************************/
	if (isset($congifArray[$c]['WEBADMIN_USER_TABLE']))
		$webadminUserTable = $congifArray[$c]['WEBADMIN_USER_TABLE'];
	if (isset($congifArray[$c]['WEBADMIN_USER_KEY']))
		$webadminUserKey = $congifArray[$c]['WEBADMIN_USER_KEY'];
/***************************************************************
Description: Roles Tables.
	 ***************************************************************
/***************************************************************/
	if (isset($congifArray[$c]['ROLE_MODULE_ACTIVE']))
	$role_module_active = $congifArray[$c]['ROLE_MODULE_ACTIVE'];
	if (isset($congifArray[$c]['ROLE_TABLES']))
	$roles_tables = $congifArray[$c]['ROLE_TABLES'];
	if (isset($congifArray[$c]['PERMISSION_TABLES']))
	$permission_tables = $congifArray[$c]['PERMISSION_TABLES'];
	if (isset($congifArray[$c]['ROLE_PERMISSION_TABLES']))
	$role_permissions = $congifArray[$c]['ROLE_PERMISSION_TABLES'];
	if (isset($congifArray[$c]['USER_ROLES_TABLES']))
	$user_role = $congifArray[$c]['USER_ROLES_TABLES'];
	if (isset($congifArray[$c]['OBJECT_TYPE_TABLE']))
	$obj_type = $congifArray[$c]['OBJECT_TYPE_TABLE'];
	if (isset($congifArray[$c]['ACTIVITY_TYPE_TABLE']))
	$act_type = $congifArray[$c]['ACTIVITY_TYPE_TABLE'];

/***************************************************************
Description: These settings correspond to the Application base language support.
	 ***************************************************************/

	if (isset($congifArray[$c]['USER_ROUTER']))
	$user_router = $congifArray[$c]['USER_ROUTER'];

/***************************************************************
Description: These settings correspond to the Application base language support.
	 ***************************************************************
THIS MODULE IS CURRENTLY UNDER TESTING.
/***************************************************************/
	if (isset($congifArray[$c]['LANGUAGE']))
		$language = $congifArray[$c]['LANGUAGE'];
/***************************************************************
Description: These settings correspond to the Security key for
			 MD5 encrypted user password generation.
/***************************************************************/
	//Security Settings
	if (isset($congifArray[$c]['SECURITY_FUNCTION']))
		$security_function = $congifArray[$c]['SECURITY_FUNCTION'];
	if (isset($congifArray[$c]['SECURITY_SALT']))
		$security_salt = $congifArray[$c]['SECURITY_SALT'];
/***************************************************************
Description: These settings correspond to the Applicaiton Folder settings
			 It sets the taget file for
			 01. Upload Folder
			 02. Download Files Container
			 03. Image File Folder.
			 04. File Prefixes.
			 05. Base Folder.
/***************************************************************/
	//Folder Setting
	if (isset($congifArray[$c]['TARGET_FILE_FOLDER']))
		$TargetFileFolder = $congifArray[$c]['TARGET_FILE_FOLDER'];
	if (isset($congifArray[$c]['ORIGINAL_FILE_FOLDER']))
		$OriginalFileFolder = $congifArray[$c]['ORIGINAL_FILE_FOLDER'];
	if (isset($congifArray[$c]['RESIZED_FILE_FOLDER']))
		$ResizedFileFolder = $congifArray[$c]['RESIZED_FILE_FOLDER'];
	if (isset($congifArray[$c]['THUMBNAIL_FILE_FOLDER']))
		$ThumbnailFileFolder = $congifArray[$c]['THUMBNAIL_FILE_FOLDER'];
	if (isset($congifArray[$c]['FILE_PREFIX']))
		$filePrefix = $congifArray[$c]['FILE_PREFIX'];

	//Folder Settings
	if (isset($congifArray[$c]['UPLOAD_FOLDER']))
	$upload_folder = $congifArray[$c]['UPLOAD_FOLDER'];
	if (isset($congifArray[$c]['DOWNLOAD_FOLDER']))
	$download_folder = $congifArray[$c]['DOWNLOAD_FOLDER'];
/***************************************************************
Description: These settings correspond to the
			 Applicaiton Files READ/WRITE Settings.

It sets the location for XML/CSV/TXT location.
These files will be found in Project folder.
<APP_LOG>logs.application</APP_LOG>
<DB_LOG>logs.database</DB_LOG>
<AUDIT_LOG>logs.audit</AUDIT_LOG>
/***************************************************************/
	if (isset($congifArray[$c]['APP_LOG_SWITCH']))
		$app_log_switch = $congifArray[$c]['APP_LOG_SWITCH'];
	if (isset($congifArray[$c]['DB_LOG_SWITCH']))
		$db_log_switch = $congifArray[$c]['DB_LOG_SWITCH'];
	if (isset($congifArray[$c]['AUDIT_LOG_SWITCH']))
		$audit_log_switch = $congifArray[$c]['AUDIT_LOG_SWITCH'];
	if (isset($congifArray[$c]['APP_LOG']))
		$app_log = $congifArray[$c]['APP_LOG'];
	if (isset($congifArray[$c]['DB_LOG']))
		$db_log = $congifArray[$c]['DB_LOG'];
	if (isset($congifArray[$c]['AUDIT_LOG']))
		$audit_log = $congifArray[$c]['AUDIT_LOG'];
	if (isset($congifArray[$c]['ERROR_LOG']))
		$php_log = $congifArray[$c]['ERROR_LOG'];
	if (isset($congifArray[$c]['APP_FILE']))
		$app_file = $congifArray[$c]['APP_FILE'];
	if (isset($congifArray[$c]['DB_FILE']))
		$db_file = $congifArray[$c]['DB_FILE'];
	if (isset($congifArray[$c]['AUDIT_FILE']))
		$audit_file = $congifArray[$c]['AUDIT_FILE'];
	if (isset($congifArray[$c]['PHP_FILE']))
		$php_file = $congifArray[$c]['PHP_FILE'];
/***************************************************************
Description: These settings correspond to the Cookie and its expiry settings.
/***************************************************************/
	//Cookie Settings
	if (isset($congifArray[$c]['COOLKIE_EXPIRY']))
		$expiry = $congifArray[$c]['COOLKIE_EXPIRY'];
/***************************************************************
Description: These settings correspond to the Session and its expiry settings.
/***************************************************************/
	//Session Settings
	if (isset($congifArray[$c]['SESSION_NAME']))
		$session_name = $congifArray[$c]['SESSION_NAME'];
	if (isset($congifArray[$c]['TOKEN_NAME']))
		$token_name = $congifArray[$c]['TOKEN_NAME'];
/***************************************************************
Description: These settings correspond to the PHP INI SETTINGS.
Refer to the PHP MANUAL for INI documentations.
PHP Version <= 5.0
/***************************************************************/
	//PHP Settings
	if (isset($congifArray[$c]['MAXIMUM_EXECUTION_TIME']))
		$maximum_execution_time = $congifArray[$c]['MAXIMUM_EXECUTION_TIME'];
	if (isset($congifArray[$c]['DATE_TIMEZONE']))
		$date_timezone = $congifArray[$c]['DATE_TIMEZONE'];
	if (isset($congifArray[$c]['SHORT_OPEN_TAG']))
		$short_open_tag = $congifArray[$c]['SHORT_OPEN_TAG'];
	if (isset($congifArray[$c]['SAFE_MODE']))
		$safe_mode = $congifArray[$c]['SAFE_MODE'];
/***************************************************************/
//Manual error Settings
/***************************************************************/
	if (isset($congifArray[$c]['DISPLAY_ERRORS']))
		$display_errors = $congifArray[$c]['DISPLAY_ERRORS'];
	if (isset($congifArray[$c]['SET_ERROR_REPORTING_MANUAL']))
		$set_error_reporting_manual = $congifArray[$c]['SET_ERROR_REPORTING_MANUAL'];
	if (isset($congifArray[$c]['LOG_ERRORS']))
		$log_errors = $congifArray[$c]['LOG_ERRORS'];
	if (isset($congifArray[$c]['ERROR_LOG']))
		$error_log = $congifArray[$c]['ERROR_LOG'];
	if (isset($congifArray[$c]['SET_TIME_LIMIT']))
		$set_time_limit = $congifArray[$c]['SET_TIME_LIMIT'];
/***************************************************************
Description: SMS Settings (API)
/***************************************************************/
	if (isset($congifArray[$c]['API_NAME']))
		$app_name = $congifArray[$c]['API_NAME'];
	if (isset($congifArray[$c]['SET_TIME_LIMIT']))
		$set_time_limit = $congifArray[$c]['SET_TIME_LIMIT'];
	if (isset($congifArray[$c]['API_ID']))
		$api_id = $congifArray[$c]['API_ID'];
	if (isset($congifArray[$c]['API_PASSWORD']))
		$api_password = $congifArray[$c]['API_PASSWORD'];
/***************************************************************
Description: Format Settings
/***************************************************************/
	if (isset($congifArray[$c]['DATE_FORMAT']))
		$date_format = $congifArray[$c]['DATE_FORMAT'];
	if (isset($congifArray[$c]['TIME_FORMAT']))
		$time_format = $congifArray[$c]['TIME_FORMAT'];
	if (isset($congifArray[$c]['CURRENCY_DECIMAL']))
		$currency_decimal = $congifArray[$c]['CURRENCY_DECIMAL'];
	if (isset($congifArray[$c]['CURRENCY_SEPARATOR']))
		$currency_separator = $congifArray[$c]['CURRENCY_SEPARATOR'];
	if (isset($congifArray[$c]['CURRENCY_DECIMAL_PLACES']))
		$currency_decimal_places = $congifArray[$c]['CURRENCY_DECIMAL_PLACES'];
	if (isset($congifArray[$c]['BASE_CURRENCY']))
		$currency_base = $congifArray[$c]['BASE_CURRENCY'];

/***************************************************************
Description: Format Settings
/***************************************************************/
	if (isset($congifArray[$c]['VIEW_FOLDER']))
		$view_folder = $congifArray[$c]['VIEW_FOLDER'];
	if (isset($congifArray[$c]['INDEX_URI']))
		$index_uri = $congifArray[$c]['INDEX_URI'];
	if (isset($congifArray[$c]['ERROR_URI']))
		$error_uri = $congifArray[$c]['ERROR_URI'];
	if (isset($congifArray[$c]['DEFAULT_INDEX_FILE']))
		$default_index_file = $congifArray[$c]['DEFAULT_INDEX_FILE'];
	if (isset($congifArray[$c]['DEFAULT_ERROR_FILE']))
		$default_error_file = $congifArray[$c]['DEFAULT_ERROR_FILE'];
/***************************************************************
Description: GLOBAL FUNCTION
/***************************************************************/

		if (isset($congifArray[$c]['GLOBAL_FUNCTIONS']))
		$global_functions = $congifArray[$c]['GLOBAL_FUNCTIONS'];
	//////////////////////////////////////////////////////////////////
}
//-----------------------------------------------------------------------------------
// End of building Configuration
//-----------------------------------------------------------------------------------
Defined("ENVIRONMENT") || Define('ENVIRONMENT', $environment);
Defined("SCRIPT") || Define('SCRIPT', $_SERVER['SCRIPT_NAME']);

$c = '';
//Set Base Path
//$url = $_SERVER['HTTP_HOST'].
/***************************************************************
Description: Global Application Configurations
All configurations are comming from the settings.xml file residing
in the project folder.
 ***************************************************************/
//Global Application Configurations
$GLOBALS['config'] = [
	'project' => array(
		'name' => $project,
    'base' => $base,
		'code_minify' => $code_minify,
		'remove_comments' => $remove_comments,
	),

	'router_type' => array(
		'user' => $user_router,
	),

	//Database Configurations
	'database_credits' => array(
		'persistent' => trim($persistent),
		'datasource' => trim($datasource),
		'host' => trim($host),
		'port' => trim($port),
		'database' => trim($database),
		'login' => trim($login),
		'password' => trim($password),
		'charset' => trim($charset),
		'collation' => trim($collation),
		'prefix' => trim($prefix),
		'mailer_template' => trim($template_folder),
		'locale' => trim($language),
		'security_function' => trim($security_function),
		'security_salt' => trim($security_salt),
	),

	//$webadminUser
	'webadmin' => array(
		'userTable' => trim($webadminUserTable),
		'userKey'   => trim($webadminUserKey),
	),

	//Role Table
	'role' => array(
		'module_active' 	=> trim($role_module_active),
		'roles' 			=> trim($roles_tables),
		'permissions' 		=> trim($permission_tables),
		'role_permissions'  => trim($role_permissions),
		'user_roles'		=> trim($user_role),
		'objects'           => trim($obj_type),
		'activity'		    => trim($act_type)
	),

	//Session Configurations
	'session' => array(
		'session_name' => trim($session_name),
		'token_name'   => trim($token_name),
	),

	//folder Configurations
	'folder' => array(
		'upload'   => trim($upload_folder),
		'download' => trim($download_folder),
	),

	//Mail Configurations
	'mail' => array(
		'mail_host' => trim($mail_host),
		'mail_user' => trim($mail_user),
		'mail_password' => trim($mail_password),
		'mail_smtp_secure' => trim($mail_smtp_secure),
		'mail_port' => trim($mail_port),
		'mail_set_from' => trim($mail_set_from),
		'mail_set_from_type' => trim($mail_set_from_type),
		'mail_smtp_auth' => trim($mail_smtp_auth),
		'mail_add_address' => trim($mail_add_address),
		'mail_recipient_name' => trim($mail_recipient_name),
		'mail_is_html' => trim($mail_is_html),
	),

	'uri' => array(
		'index' => trim($index_uri),
		'error' => trim($error_uri),
		'default_index_file' => trim($default_index_file),
		'default_error_file' => trim($default_error_file),
	),

	//Log Configurations
	'logs' => array(
		'appswitch' => trim($app_log_switch),
		'auditswitch' => trim($audit_log_switch),
		'dbswitch' => trim($db_log_switch),
		'appfile' => trim($app_file),
		'dbfile' => trim($db_file),
		'auditfile' => trim($audit_file),
		'phpfile' => trim($php_file),
		'app' => trim($app_log),
		'db' => trim($db_log),
		'audit' => trim($audit_log),
		'php' => trim($php_log),
	),

	//View Configurations
	'src' => array(
		'view' => trim($view_folder),
	),

	//Language Configurations
	'language' => array(
		'base' => trim($language),
	),

	//SMS Configurations
	'sms_settings' => array(
		'api_name' => trim($app_name),
		'set_time_limit' => trim($set_time_limit),
		'api_id' => trim($api_id),
		'api_password' => trim($api_password),
	),

	//Formats Configurations
	'format' => array(
		'date_format' => trim($date_format),
		'time_format' => trim($time_format),
	),

	//Currency Configurations
	'currency' => array(
		'decimal' => trim($currency_decimal),
		'separator' => trim($currency_separator),
		'decimal_places' => trim($currency_decimal_places),
		'base' => trim($currency_base),
	),
];

/***************************************************************
Description: Initialize Application
This includes the non-class library files into the application
to initialize the library functions and costants.
 ***************************************************************/
//Initialize Application
/***************************************************************/
/* Location functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Location.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Location.php';
}
/***************************************************************/
/* Array functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Array.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Array.php';
}
/***************************************************************/
/* Connect functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Database.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Database.php';
}
/***************************************************************/
/* Debug functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Debug.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Debug.php';
}
/***************************************************************/
/* Browser functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Browser.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Browser.php';
}
/***************************************************************/
/* Basic operations functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Utility.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Utility.php';
}
/***************************************************************/
/* Exceptions functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Exceptions.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Exceptions.php';
}
/***************************************************************/
/* Links functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Links.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Links.php';
}
/***************************************************************/
/* String functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Strings.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Strings.php';
}
/***************************************************************/
/* Hash functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Hash.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Hash.php';
}
/***************************************************************/
/* Redirect functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Redirect.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Redirect.php';
}
/***************************************************************/
/* Redirect functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Security.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Security.php';
}
/***************************************************************/
/* System functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'System.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'System.php';
}
/***************************************************************/
/* Date functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Date.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Date.php';
}
/***************************************************************/
/* Files functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Files.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Files.php';
}
/***************************************************************/
/* HTML Helper functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'HTML.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'HTML.php';
}
/***************************************************************/
/* Session functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Session.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Session.php';
}

/***************************************************************/
/* Public functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Public.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Public.php';
}

/***************************************************************/
/* Place functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Country.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Country.php';
}

/***************************************************************/
/* Authorization functions
/***************************************************************/
if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Auth.php')) {
	require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'Auth.php';
}

	/***************************************************************
	Description: AUTOLOAD function to initialize all library classes
	Autoload initiates all the classes in the following folders.
	01. root/app/
	02. root/app/lib/database/
	03. root/app/lib/database/mysql/
	04. root/app/lib/
	05. root/app/lib/files/
	06. root/app/project/template/
	07. root/app/project/template/
	***************************************************************/
	//Autoload Class Locations
	spl_autoload_register(function ($class) {
		$parts = explode('\\', $class);
		$class = end($parts);

		//LEVEL 1 - app
		if (file_exists(FOLDER_PATH . 'app' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . $class . '.php';

		//LEVEL 2 - database
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . $class . '.php';

		//LEVEL 2 - roles
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'roles' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'roles' . DS . $class . '.php';

		//LEVEL 3 - App/library
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . $class . '.php';

		//LEVEL 3 - App/library/Files
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'files' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'files' . DS . $class . '.php';

		//LEVEL 3 - App/library/Helpers
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'helpers' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'helpers' . DS . $class . '.php';

		//LEVEL 2 - project/template
		if (file_exists(FOLDER_PATH . 'project' . DS . 'templates' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'project' . DS . 'templates' . DS . $class . '.php';

		//LEVEL 3 database
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . $class . '.php';


		//LEVEL 3 database
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . 'mysql' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . 'mysql' . DS . $class . '.php';

		//LEVEL 3 - database
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . 'orm' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . 'orm' . DS . $class . '.php';

		//LEVEL 3 - database
		if (file_exists(FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . 'crud' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'lib' . DS . 'database' . DS . 'crud' . DS . $class . '.php';

		/***************************************************************/
		//Simple Excel Integration
		if (file_exists(FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'SimpleExcel' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'SimpleExcel' . DS . $class . '.php';
		if (file_exists(FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'SimpleExcel' . DS . 'Parser' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'SimpleExcel' . DS . 'Parser' . DS . $class . '.php';
		if (file_exists(FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'SimpleExcel' . DS . 'Writer' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'SimpleExcel' . DS . 'Writer' . DS . $class . '.php';
		if (file_exists(FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'SimpleExcel' . DS . 'Exception' . DS . $class . '.php'))
			require_once FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'SimpleExcel' . DS . 'Exception' . DS . $class . '.php';

		/***************************************************************/
		//Form Builder

		if (file_exists(FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'phpformbuilder' . DS . $class . '.php'))
			include_once FOLDER_PATH . 'app' . DS . 'vendors' . DS . 'phpformbuilder' . DS .  $class . '.php';
	});


/***************************************************************/
/* include special classes
/***************************************************************/
$special_path = FOLDER_PATH . 'app' . DS . 'lib' . DS . 'bin' . DS . 'performs';
foreach (glob("{$special_path}/*.php") as $filename){
    require_once $filename;
}


	/***************************************************************/
/* include special classes
/***************************************************************/
if(isset($global_functions) !== ''){
	$global_path = PROJECT_PATH.'global_functions';
}else{
	$global_functions = str_replace('.','/',$global_functions);
	$global_path = PROJECT_PATH.$global_functions;
}

foreach (glob("{$global_path}/*.php") as $filename){
    require_once $filename;
}

	/***************************************************************
	Description: INI File settings
	***************************************************************/
	ini_set('max_execution_time', $maximum_execution_time);
	date_default_timezone_set($date_timezone);
	if (isset($short_open_tag) == true)
		ini_set('short_open_tag', $short_open_tag);
	if (isset($safe_mode) == true)
		ini_set('safe_mode', $safe_mode);
	if (isset($safe_mode_allowed_env_vars) == true)
		ini_set('safe_mode_allowed_env_vars', $safe_mode_allowed_env_vars);
	if (isset($safe_mode_protected_env_vars) == true)
		ini_set('safe_mode_protected_env_vars', $safe_mode_protected_env_vars);
	if (isset($disable_functions) == true)
		ini_set('disable_functions', $disable_functions);
	if (isset($safe_mode_exec_dir) == true)
		ini_set('safe_mode_exec_dir', $safe_mode_exec_dir);
	if (isset($magic_quotes_runtime) == true)
		ini_set('magic_quotes_runtime', $magic_quotes_runtime);
	if (isset($magic_quotes_gpc) == true)
		ini_set('magic_quotes_gpc', $magic_quotes_gpc);
	if (isset($include_path) == true)
		ini_set('include_path', $include_path);
	if (isset($file_uploads) == true)
		ini_set('file_uploads', $file_uploads);
	if (isset($doc_root) == true)
		ini_set('doc_root', $doc_root);
	if (isset($upload_tmp_dir) == true)
		ini_set('upload_tmp_dir', $upload_tmp_dir);
	if (isset($ignore_user_abort) == true)
		ini_set('ignore_user_abort', $ignore_user_abort);
	if (isset($mysql_default_host) == true)
		ini_set('mysql.default_host', $mysql_default_host);
	if (isset($mysql_default_user) == true)
		ini_set('mysql.default_user', $mysql_default_user);
	if (isset($mysql_default_password) == true)
		ini_set('mysql.default_password', $mysql_default_password);
	if (isset($magic_quotes_sybase) == true)
		ini_set('magic_quotes_sybase', $ $magic_quotes_sybase);
	if (isset($auto_prepend_file) == true)
		ini_set('auto-prepend-file', $auto_prepend_file);
	if (isset($auto_append_file) == true)
		ini_set('auto-append-file', $auto_append_file);
	if (isset($session_save_handler) == true)
		ini_set('session.save-handler', $session_save_handler);
	if (isset($warn_plus_overloading) == true)
		ini_set('warn_plus_overloading', $warn_plus_overloading);
	if (isset($error_prepend_string) == true)
		ini_set('error_prepend_string', $error_prepend_string);
	ini_set('set_time_limit', $set_time_limit);

	/******************************
	 * Setting Env for error reporting.
	 * executes important processes before the controller is created.
	 * In this case, it sets proper error reporting values, unregisters globals and escapes all input (GET, POST and COOKIE variables).
	 ******************************/
	$error_reporting = strtolower($error_reporting);
	if ($error_reporting == 'automatic') {
		if (ENVIRONMENT == 'development' || ENVIRONMENT == 'testing') {
			error_reporting(E_ALL);
			ini_set('display_errors', 'On');
		} else {
			error_reporting(E_STRICT);
			ini_set('display_errors', 'Off');
			ini_set('log_errors', 'On');
			$php_error_loc = loc_file_log('php');
			ini_set('error_log', $php_error_loc);
		}
	} elseif ($error_reporting == 'manual') {
		error_reporting($set_error_reporting_manual);
		//Set Display Error Option
		$display_errors = strtolower($display_errors);
		$display_errors = ucfirst($display_errors);
		ini_set('display_errors', $display_errors);
		$log_errors = strtolower($log_errors);
		$log_errors = ucfirst($log_errors);
		ini_set('log_errors', $log_errors);
		$php_error_loc = loc_file_log('php');
		ini_set('error_log', $php_error_loc);
	} else {
		trigger_error('Invalid Error Reporting Configured. Check Manual and File <b>Project->Config.php</b>.');
	}

	// //Set Applcation Sessions
	Defined("DB") || Define('DB', \core\lib\utilities\Config::get('database_credits/database'));
	Defined("TABLES") || Define('TABLES', \orm\Query::getAllDBTables(DB));
	Defined("SYS_USER") || Define('SYS_USER', \core\lib\utilities\Config::get('webadmin/userKey'));
	Defined("SYS_USER_TABLE") || Define('SYS_USER_TABLE', \core\lib\utilities\Config::get('webadmin/userTable'));
	Defined("SYS_DATE") || Define('SYS_DATE', sydate());
	Defined("SYS_TIME") || Define('SYS_TIME', sytime());
	Defined("HOST") || Define('HOST', $_SERVER['HTTP_HOST']);
	Defined("URI") || Define('URI', $_SERVER['REQUEST_URI']);
	Defined("REQUEST") || Define('REQUEST', $_SERVER['REQUEST_METHOD']);
	Defined("QUERY_STRING") || Define('QUERY_STRING', $_SERVER['QUERY_STRING']);
	Defined("METHOD") || Define('METHOD', strtolower($_SERVER['REQUEST_METHOD']));
	if (!empty($_SERVER['QUERY_STRING']))
		Defined("ROUTE") || Define('ROUTE', preg_replace('/^url=index.php&url=(.*)/', '$1', $_SERVER['QUERY_STRING']));
	$link = getServerUrl() . URI;
	Defined("LINK") || Define('LINK', $link);
	Defined("TIMESTAMP") || Define('TIMESTAMP', \core\lib\utilities\Timestamp::setTime());
	if(isset($_SESSION)){
		Defined("SESSION") || Define('SESSION', $_SESSION);
	}else{
		Defined("SESSION") || Define('SESSION', NULL);
	}

	/***************************************************************
	MAKE PUBLIC FOLDER WRITABLE
	***************************************************************/
	makePublicWritable(publicFolder("assets"));
	/***************************************************************
	Description: //QMVC Entry Point
	THE APPLICATION STARTS HERE!!!
	***************************************************************/
	// Set user-defined error handler function
	// set_error_handler("qmvcErrorHandler");

	$application = new \core\App();
	$application->dispatch();
?>
