<?php

/**
 * Class SimpleError
 */
class SimpleError implements IError
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