<?php

interface IAuthenticator
{
    function authenticate ($id, $password);

    function getAuthenticationLevel ();
}