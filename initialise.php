<?php

// SESSION ACTIVITY - START A BASIC SESSION AND CREATE SESSION VARIABLES
// Enable session variables

// Check if session already started and if the user has been active
if (isset($_SESSION['LAST_ACTIVITY'])
    && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)
)
{
    // Last request was more than 30 minutes ago
    session_unset();    // unset $_SESSION variable for the run-time
    session_destroy();  // destroy session data in storage
}

// Update last activity time stamp
$_SESSION['LAST_ACTIVITY'] = time();
if (!isset($_SESSION['CREATED']))
{
    $_SESSION['CREATED'] = time();
}
else
{
    if (time() - $_SESSION['CREATED'] > 1800)
    {
        // Session started more than 30 minutes ago
        session_regenerate_id(true); // change session ID for the current session to invalidate old session ID
        $_SESSION['CREATED'] = time(); // update creation time
    }
}

// DEFINED GLOBAL CONSTANTS
defined("APP_ROOT") or define("APP_ROOT", realpath(dirname(__file__)) . "/");

defined("RES_DIR") or define("RES_DIR", 'res/');
defined("LIB_DIR") or define("LIB_DIR", APP_ROOT . 'lib/');
defined("PAGES_DIR") or define("PAGES_DIR", APP_ROOT . 'pages/');
defined("FRAGMENTS_DIR") or define("FRAGMENTS_DIR", APP_ROOT . 'fragments/');
defined("TEMPLATES_DIR") or define("TEMPLATES_DIR", APP_ROOT . 'templates/');
defined("PLUGINS_DIR") or define("PLUGINS_DIR", APP_ROOT . 'plugins/');
defined("OPAUTH_LIB_DIR") or define('OPAUTH_LIB_DIR', LIB_DIR . 'opauth/');

// ERROR HANDLING CONSTS
defined("MYSQL_CONN_ERROR") or define("MYSQL_CONN_ERROR", "Unable to connect to database.");

// Ensure reporting is setup correctly
mysqli_report(MYSQLI_REPORT_STRICT);

// Require the rest
$requireFiles = [];

/**
 * @param $requireFiles
 * @param $directory
 * @param $pattern
 */
function addRequired(&$requireFiles, $directory, $pattern)
{
    foreach (glob($directory . $pattern) as $file)
    {
        $requireFiles[] = ($file);
    }
}

// Require all the things
addRequired($requireFiles, LIB_DIR . 'facebook/src/Facebook/', 'autoload.php');
addRequired($requireFiles, LIB_DIR . 'interfaces/', 'I*.php');
addRequired($requireFiles, LIB_DIR . 'bases/', 'Base*.php');
addRequired($requireFiles, LIB_DIR . 'enumerators/', '*Enum.php');
addRequired($requireFiles, LIB_DIR . 'classes/', '*.php');
addRequired($requireFiles, LIB_DIR . 'errors/', '*SimpleError.php');
addRequired($requireFiles, FRAGMENTS_DIR, '*Fragment.php');
addRequired($requireFiles, PAGES_DIR, '*Page.php');
addRequired($requireFiles, TEMPLATES_DIR, '*Template.php');

// Process all the pages to include
foreach ($requireFiles as $require)
{
    if (file_exists($require))
    {
        require_once $require;
    }
}

$includeFiles = [];
// Include all the user pages
$path = PLUGINS_DIR . '/';
foreach (scandir($path) as $result)
{
    if ($result === '.' or $result === '..')
    {
        continue;
    }

    $internalPath = $path . $result;
    if (is_dir($internalPath))
    {
        if (file_exists($internalPath . '/Plugin.php'))
        {
            // Code to use if Plugin
            $includeFiles[] = $internalPath . '/Plugin.php';
        }
    }
}

// Process all the pages to include
foreach ($includeFiles as $include)
{
    if (file_exists($include))
    {
        include_once $include;
    }
}