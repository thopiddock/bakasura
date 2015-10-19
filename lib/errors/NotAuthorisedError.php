<?php

/**
 * class NotAuthorisedError
 *
 * Description for class NotAuthorisedError
 *
 * @author:
 */
class NotAuthorisedError implements IError
{

    protected $message;

    function getErrorMessage ()
    {
        return 'NotAuthorisedError: ' . $this->message;
    }

    function getSeverity ()
    {
        return ErrorSeverityEnum::Error;
    }

    public function __construct ($message)
    {
        $this->message = $message;
    }
}