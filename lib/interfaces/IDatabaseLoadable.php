<?php

/**
 * Created by PhpStorm.
 * User: tpidd
 * Date: 28/08/2015
 * Time: 01:30
 */
interface IDatabaseLoadable
{
    static function generateFromDatabase($databaseValue);
    function getDisplayName();
}