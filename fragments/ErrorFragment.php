<?php

class ErrorFragment implements IFragment
{
    function getContent()
    {
        $errors = Site::$errorHandler->getErrors();
        $html   = '';
        if (count($errors) > 0)
        {
            $html .= '<div id="errors"><ul>';
            foreach ($errors as $error)
            {
                $html .= '<li class="debug-' . strtolower(ErrorSeverityEnum::getName($error->getSeverity())) . '">';
                $html .= $error->getErrorMessage();
                $html .= '</li>';
            }

            $html .= '</ul></div>';
        }

        return $html;
    }
}