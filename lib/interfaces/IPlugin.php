<?php

interface IPlugin
{
    function canGeneratePage ();

    function canGenerateFragment ();

    function getPage ();

    function getFragment ();

    function getPrefix ();

    function install ();
}