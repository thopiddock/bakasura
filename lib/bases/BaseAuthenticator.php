<?php

/**
 * class BaseAuthenticator
 *
 * Description for class BaseAuthenticator
 *
 * @author:
 */
abstract class BaseAuthenticator
{
    const AUTHENTICATION_LEVEL = 'authenticationLevel';

    abstract function authenticate ();
    abstract function deauthenticate();

    final public function getAuthenticationLevel ()
    {
        $this->startSession();

        if (!isset($_SESSION[self::AUTHENTICATION_LEVEL]))
        {
            $this->setAuthenticationLevel(AuthenticationLevelEnum::Visitor);
        }

        return $_SESSION[self::AUTHENTICATION_LEVEL];
    }

    public static function startSession ()
    {
        if (BaseAuthenticator::is_session_started() == false)
        {
            session_start();
        }
    }

    /**
     * @return bool
     */
    private static function is_session_started ()
    {
        if (php_sapi_name() !== 'cli')
        {
            if (version_compare(phpversion(), '5.4.0', '>='))
            {
                return session_status() === PHP_SESSION_ACTIVE ? true : false;
            }
            else
            {
                return session_id() === '' ? false : true;
            }
        }

        return false;
    }

    final protected function setAuthenticationLevel ($authenticationLevel)
    {
        $this->startSession();

        if (AuthenticationLevelEnum::isValidValue($authenticationLevel))
        {
            $_SESSION[self::AUTHENTICATION_LEVEL] = $authenticationLevel;
        }
        else
        {
            $_SESSION[self::AUTHENTICATION_LEVEL] = AuthenticationLevelEnum::Visitor;
        }
    }
}