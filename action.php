<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
date_default_timezone_set('UTC');


// Load the configuration
require_once(realpath(dirname(__file__)) . '/config.php');
require_once(realpath(dirname(__file__)) . '/initialise.php');

$implementsIAction = array();
foreach (get_declared_classes() as $className) {
    if (in_array('IAction', class_implements($className))) {
        $implementsIAction[] = $className;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!isset($_POST['performer']))
    {
        return null;
    }

    $performerName = $_POST['performer'];
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $vars = isset($_POST['vars']) ? $_POST['vars'] : null;

    if (class_exists($performerName))
    {
        print json_encode($performerName::processAction($action, $vars));
    }
}