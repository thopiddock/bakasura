<?php

/**
 * Class Error
 */
class Error implements IError
{
    protected $message;
    protected $severity;

    function getErrorMessage ()
    {
        return $this->message;
    }

    function getSeverity ()
    {
        return $this->severity;
    }

    public function __construct ($message, $severity)
    {
        $this->message = $message;
        $this->severity = $severity;
    }
}