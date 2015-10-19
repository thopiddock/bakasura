<?php

/**
 * IAction Created by PhpStorm.
 * User: tpidd
 * Date: 03/09/2015
 * Time: 13:37
 */
interface IAction
{
    /**
     * Process an action via GET/POST.
     * @param $action string
     * @param $vars string|array
     *
     * @return string Json string representing the response.
     */
    static function processAction($action, $vars);
}