<?php

class ErrorHandler
{
    private $errors = array ();

    public function addError (IError $error)
    {
        $this->errors[] = $error;
    }

    public function getErrors ()
    {
        return $this->errors;
    }
}