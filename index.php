<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
date_default_timezone_set('UTC');


// Load the configuration
require_once(realpath(dirname(__file__)) . '/config.php');
require_once(realpath(dirname(__file__)) . '/initialise.php');

$site = new Site("");
$site->printSite();